<?php

namespace App\Models\Ad3d\TimekeepingProvisional;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\LicenseOffWork\QcLicenseOffWork;
use App\Models\Ad3d\MinusMoney\QcMinusMoney;
use App\Models\Ad3d\PunishContent\QcPunishContent;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\SystemDateOff\QcSystemDateOff;
use App\Models\Ad3d\Timekeeping\QcTimekeeping;
use App\Models\Ad3d\TimekeepingImage\QcTimekeepingImage;
use App\Models\Ad3d\TimekeepingProvisionalImage\QcTimekeepingProvisionalImage;
use App\Models\Ad3d\TimekeepingProvisionalWarning\QcTimekeepingProvisionalWarning;
use App\Models\Ad3d\Work\QcWork;
use Illuminate\Database\Eloquent\Model;

class QcTimekeepingProvisional extends Model
{
    protected $table = 'qc_timekeeping_provisional';
    protected $fillable = ['timekeeping_provisional_id', 'timeBegin', 'timeEnd', 'note', 'afternoonStatus', 'lateTimekeeping', 'workStatus', 'accuracyStatus', 'confirmNote', 'confirmDate', 'confirmStatus', 'created_at', 'updated_at', 'work_id', 'staffConfirm_id'];
    protected $primaryKey = 'timekeeping_provisional_id';
    public $timestamps = false;

    private $lastId;

    //========== ========== ========== them  && cap nhat ========== ========== ==========
    //---------- them ----------
    public function insert($timeBegin, $timeEnd, $note, $afternoonStatus, $workId, $staffCheckId)
    {
        $hFunction = new \Hfunction();
        $modelTimekeeping = new QcTimekeepingProvisional();
        $modelTimekeeping->timeBegin = $timeBegin;
        $modelTimekeeping->timeEnd = $timeEnd;
        $modelTimekeeping->note = $note;
        $modelTimekeeping->afternoonStatus = $afternoonStatus;
        $modelTimekeeping->work_id = $workId;
        $modelTimekeeping->staffConfirm_id = $staffCheckId;
        $modelTimekeeping->created_at = $hFunction->createdAt();
        if ($modelTimekeeping->save()) {
            $this->lastId = $modelTimekeeping->timekeeping_provisional_id;
            return true;
        } else {
            return false;
        }
    }

    // get new id
    public function insertGetId()
    {
        return $this->lastId;
    }


    public function checkNullId($id)
    {
        return (empty($id)) ? $this->timekeepingProvisionalId() : $id;
    }


    public function cancelTimekeepingProvision($id, $staffLoginId)
    {
        $hFunction = new \Hfunction();
        return QcTimekeepingProvisional::where(['timekeeping_provisional_id' => $id])->update(
            [
                'accuracyStatus' => 0,
                'staffConfirm_id' => $staffLoginId,
                'confirmStatus' => 1,
                'confirmDate' => $hFunction->createdAt(),
            ]);
    }

    // kiem tra tong tin cham cong lam viec trong ngay - KIEM TRA NGAY TRƯƠC
    /*goi trong qc_work*/
    public function checkAutoTimekeepingOfWorkAndDate($workId, $checkDate)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelPunishContent = new QcPunishContent();
        $modelMinusMoney = new QcMinusMoney();
        $modelTimekeeping = new QcTimekeeping();
        $modelSystemDateOff = new QcSystemDateOff();
        $modelWork = new QcWork();
        $modelLicenseOffWork = new QcLicenseOffWork();
        $staffId = $modelWork->staffId($workId);
        $dataTimeKeepingProvisional = QcTimekeepingProvisional::where('work_id', $workId)->where('timeBegin', 'like', "%$checkDate%")->first();
        if ($hFunction->checkCount($dataTimeKeepingProvisional)) { # co bao gio vao
            $timeBegin = $dataTimeKeepingProvisional->timeBegin();
            $timeEnd = $dataTimeKeepingProvisional->timeEnd();
            if (empty($timeEnd)) { # co bao gio ra
                if (QcTimekeepingProvisional::where(['timekeeping_provisional_id' => $dataTimeKeepingProvisional->timekeepingProvisionalId()])->update(
                    [
                        'accuracyStatus' => 0,
                        'staffConfirm_id' => null,
                        'confirmNote' => 'Không báo giờ ra',
                        'confirmStatus' => 1,
                        'confirmDate' => $hFunction->createdAt(),
                    ])
                ) {
                    if (!$modelTimekeeping->existDateOfWork($workId, $checkDate)) {
                        $modelTimekeeping->insert($timeBegin, null, null, 0, 0, 0, 0, null, 'Duyệt tự động - không báo giờ ra', 0, 1, 0, null, $workId);
                    }
                }
            }
        } else { // khong bao cham cong
            if (!$hFunction->checkDateIsSunday($checkDate)) { # khong phai la ngay chu nhat
                $companyId = $modelWork->companyIdOfWork($workId);
                if (!$modelSystemDateOff->checkExistsDateOfCompany($companyId, $checkDate)) { # khong phai ngay nghi cua he thong ==> nghi khong phép
                    $dataLicenseOffWork = $modelLicenseOffWork->infoOfStaffAndDate($staffId, $checkDate);
                    # co xin nghi
                    if ($hFunction->checkCount($dataLicenseOffWork)) {
                        # duoc duyet nghi
                        if ($dataLicenseOffWork->checkAgreeStatus()) {
                            # them thong tin vao ngay cham cong
                            if (!$modelTimekeeping->existDateOfWork($workId, $checkDate)) {
                                $modelTimekeeping->insert(null, null, $checkDate, 0, 0, 0, 0, null, 'Duyệt tự động - không chấm công', 0, 1, 0, null, $workId);
                            }
                        } else { # khong duoc duyet
                            if (!$modelTimekeeping->existDateOfWork($workId, $checkDate)) {
                                if ($modelTimekeeping->insert(null, null, $checkDate, 0, 0, 0, 0, null, 'Duyệt tự động - không chấm công', 0, 0, 0, null, $workId)) {
                                    if ($modelStaff->checkApplyRule($staffId)) { # ap dung noi quy
                                        # phat nghi khong phep
                                        $punishIdOfOffWork = $modelPunishContent->punishIdOfOffWork();
                                        if (!empty($punishIdOfOffWork)) {
                                            $reason = $modelPunishContent->note($punishIdOfOffWork);
                                            $modelMinusMoney->insert(date('Y-m-d 00:00:00', strtotime($checkDate)), $reason[0], $workId, $staffId, $punishIdOfOffWork[0], 0, null, null, null, null, 0);
                                        }
                                    }

                                }
                            }
                        }

                    } else { # khong xin nghi
                        # them thong tin vao ngay cham cong
                        if (!$modelTimekeeping->existDateOfWork($workId, $checkDate)) {
                            if ($modelTimekeeping->insert(null, null, $checkDate, 0, 0, 0, 0, '', 'Duyệt tự động - không chấm công', 0, 0, 0, null, $workId)) {
                                if ($modelStaff->checkApplyRule($staffId)) { # ap dung noi quy
                                    # phat nghi khong phep
                                    $punishIdOfOffWork = $modelPunishContent->punishIdOfOffWork();
                                    if (!empty($punishIdOfOffWork)) {
                                        $reason = $modelPunishContent->note($punishIdOfOffWork);
                                        $modelMinusMoney->insert(date('Y-m-d 00:00:00', strtotime($checkDate)), $reason[0], $workId, $staffId, $punishIdOfOffWork[0], 0, null, null, null, null, 0);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

    }


    # kiem tra da bao gio ra hay chu
    public function checkReportedTimeEnd($timekeepingId)
    {
        return QcTimekeepingProvisional::where('timekeeping_provisional_id', $timekeepingId)->whereNotNull('timeEnd')->exists();
    }

    # cap nhat bao gio ra
    public function updateTimeEnd($timekeepingId, $timeEnd, $afternoonStatus, $note)
    {
        $hFunction = new \Hfunction();
        $modelTimekeepingProvisionalWarning = new QcTimekeepingProvisionalWarning();
        $currentDate = $hFunction->carbonNow();
        $dataTimekeepingProvisional = $this->getInfo($timekeepingId);
        # neu cap nhat sau khi bi canh bao
        if ($modelTimekeepingProvisionalWarning->checkExistWarningTimeEndOfTimekeepingProvisional($timekeepingId)) {
            # cap nhat ngay canh bao
            $modelTimekeepingProvisionalWarning->updateTimeEndOfTimekeepingProvisional($timekeepingId, $timeEnd);
            # giu lai thong tin cu - thoi gian bao va thoi gian cham lan 1
            $updateTimeEnd = $dataTimekeepingProvisional->timeEnd();
            $updatedAt = $dataTimekeepingProvisional->updatedAt();
        }else{
            $updateTimeEnd = $timeEnd;
            $updatedAt = $currentDate;
        }
        if (QcTimekeepingProvisional::where(['timekeeping_provisional_id' => $timekeepingId])->update(
            [
                'timeEnd' => $updateTimeEnd,
                'afternoonStatus' => $afternoonStatus,
                'note' => $note,
                'updated_at' => $updatedAt
            ])
        ) {
            # chuyen ve cung dinh dang de so sanh
            $checkTimeEnd = $hFunction->formatDateToYMDHI($timeEnd);
            $checkCurrentDate = $hFunction->formatDateToYMDHI($currentDate);
            # bao truoc gio ra
            if ($checkTimeEnd > $checkCurrentDate) {
                # neu da duoc canh bao => cap nhat ngay canh bao
                if (!$modelTimekeepingProvisionalWarning->checkExistWarningTimeEndOfTimekeepingProvisional($timekeepingId)) {
                    # chua duoc canh bao
                    # canh bao gio ra khong dung
                    $modelTimekeepingProvisionalWarning->insert("Báo giờ ra không đúng - giờ chấm: $currentDate - giờ báo: $timeEnd", null, $modelTimekeepingProvisionalWarning->getDefaultWarningTypeTimeEnd(), $timekeepingId, null);
                }
            }
            return true;
        } else {
            return false;
        }
    }

    public function confirmWork($id, $staffLoginId, $confirmNote, $permissionLateStatus, $accuracyStatus, $applyTimekeepingStatus, $applyRuleStatus)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelWork = new QcWork();
        $modelPunishContent = new QcPunishContent();
        $modelMinusMoney = new QcMinusMoney();
        $modelTimekeeping = new QcTimekeeping();
        $modelTimekeepingImage = new QcTimekeepingImage();
        $modelTimekeepingProvisional = new QcTimekeepingProvisional();
        $model = new QcTimekeepingProvisionalImage();
        if ($accuracyStatus == 0) $confirmNote = $confirmNote . " - Báo giờ không chính xác";
        if ($applyTimekeepingStatus == 0) $confirmNote = $confirmNote . " - Không tính công ngày này";
        if ($applyRuleStatus == 0) $confirmNote = $confirmNote . " - Không áp dụng nội quy phạt";
        if (QcTimekeepingProvisional::where(['timekeeping_provisional_id' => $id])->update(
            [
                'accuracyStatus' => $accuracyStatus,
                'staffConfirm_id' => $staffLoginId,
                'confirmNote' => $confirmNote,
                'confirmStatus' => 1,
                'confirmDate' => $hFunction->createdAt(),
            ])
        ) {
            $dataTimekeepingProvisional = $modelTimekeepingProvisional->getInfo($id);
            $workId = $dataTimekeepingProvisional->workId();
            $dateBegin = $dataTimekeepingProvisional->timeBegin();
            $dateEnd = $dataTimekeepingProvisional->timeEnd();
            $note = $dataTimekeepingProvisional->note();
            $afternoonStatus = $dataTimekeepingProvisional->afternoonStatus();
            $dayBegin = date('d', strtotime($dateBegin));
            $monthBegin = date('m', strtotime($dateBegin));
            $yearBegin = date('Y', strtotime($dateBegin));
            $checkDate = date('Y-m-d', strtotime($dateBegin));// "$monthBegin/$dayBegin/$yearBegin";
            if ($modelTimekeeping->existDateOfWork($workId, $checkDate)) { # da ton tai cham cong
                return false;
            } else {
                $defaultBegin = $hFunction->convertStringToDatetime("$monthBegin/$dayBegin/$yearBegin 08:00:00");
                $defaultEnd = $hFunction->convertStringToDatetime("$monthBegin/$dayBegin/$yearBegin 17:30:00");//date('Y-m-d 17:30', strtotime($dateBegin));//
                $mainMinute = 0;
                $plusMinute = 0;
                $minusMinute = 0;
                $lateStatus = 1;
                # ----- ap dung noi quy phat  --------
                if ($applyRuleStatus == 1) {
                    //khong phai ngay chu nhat
                    if (!$hFunction->checkDateIsSunday(date('Y-m-d', strtotime($dateBegin)))) {
                        if ($defaultBegin < $dateBegin) { // tính trừ thời gian trễ
                            $staffId = $modelWork->staffId($workId);
                            if ($modelStaff->checkApplyRule($staffId)) { # ap dung noi quy
                                # tre khong phep
                                if ($permissionLateStatus == 0) {
                                    $punishIdOfLateWork = $modelPunishContent->punishIdOfLateWork();
                                    if (!empty($punishIdOfLateWork)) {
                                        $reason = $modelPunishContent->note($punishIdOfLateWork);
                                        $modelMinusMoney->insert($dateBegin, $reason[0], $workId, $staffLoginId, $punishIdOfLateWork[0], 0, null, null, null, null, 0);
                                    }
                                    $lateStatus = 0;
                                }
                                # bao gio khong chinh xac
                                if ($accuracyStatus == 0) {
                                    $punishIdOfTimekeepingAccuracy = $modelPunishContent->punishIdOfTimekeepingAccuracy();
                                    if (!empty($punishIdOfTimekeepingAccuracy)) {
                                        $reason = $modelPunishContent->note($punishIdOfTimekeepingAccuracy);
                                        $modelMinusMoney->insert($dateBegin, $reason[0], $workId, $staffLoginId, $punishIdOfTimekeepingAccuracy[0], 0, null, null, null, null, 0);
                                    }
                                }
                            }
                        }
                    }
                }
                # ------ co tinh cong ngay lam --------
                if ($applyTimekeepingStatus == 1) {
                    # co bao gio ra
                    if (!$hFunction->checkEmpty($dateEnd)) {
                        $diffLate = abs(strtotime($dateEnd) - strtotime($dateBegin));
                        $totalWorkMinute = $diffLate / 60; //số phút làm việc
                        //lam luon buoi trua
                        if ($modelTimekeepingProvisional->checkAfternoonWork($id)) $plusMinute = 60;// cộng 1h buổi trưa
                        # ngay chu nhat
                        if ($hFunction->checkDateIsSunday(date('Y-m-d', strtotime($dateBegin)))) {
                            # gio lam ngay chu nhat tinh tang ca
                            if (360 < $totalWorkMinute) { // làm hơn 6 tiếng từ sáng
                                if ($dataTimekeepingProvisional->checkAfternoonWork($id)) {
                                    $plusMinute = $totalWorkMinute - 30; //(trừ 1h30 nghĩ trưa)
                                } else {
                                    $plusMinute = $totalWorkMinute - 90; //(trừ 1h30 nghĩ trưa)
                                }
                            } else {
                                $plusMinute = $totalWorkMinute;
                            }
                        } else {
                            if ($defaultEnd < $dateEnd) { // tăng ca
                                $diffMain = abs(strtotime($defaultEnd) - strtotime($dateBegin));
                                $diffPlus = abs(strtotime($dateEnd) - strtotime($defaultEnd));
                                $plusMinute = $plusMinute + ($diffPlus / 60); //số phút tăng ca
                            } else {
                                $diffMain = abs(strtotime($dateEnd) - strtotime($dateBegin));
                            }
                            $totalMainWorkMinute = $diffMain / 60; //giờ làm chính
                            if ($totalMainWorkMinute > 330) {
                                $mainMinute = $totalMainWorkMinute - 90; // làm giờ hành chính trừ 1h3 nghĩ trưa
                            } else {
                                $mainMinute = $totalMainWorkMinute;
                            }

                        }
                    }
                }
                #------- them thong tin cham cong chinh thuc --------
                if ($modelTimekeeping->insert($dateBegin, $dateEnd, null, $afternoonStatus, $mainMinute, $plusMinute, $minusMinute, $note, $confirmNote, $lateStatus, $permissionLateStatus, 0, $staffLoginId, $workId)) {
                    $newTimekeepingId = $modelTimekeeping->insertGetId();
                    $dataTimekeepingProvisionalImage = $modelTimekeepingProvisional->imageOfTimekeepingProvisional($id);
                    //thêm hình ảnh vào bảng chấm công chính
                    if ($hFunction->checkCount($dataTimekeepingProvisionalImage)) { // tồn tại ảnh xác nhận
                        foreach ($dataTimekeepingProvisionalImage as $timekeepingProvisionalImage) {
                            $imageName = $timekeepingProvisionalImage->name();
                            $reportPeriod = $timekeepingProvisionalImage->reportPeriod();
                            if (copy($model->rootPathSmallImage() . '/' . $imageName, $modelTimekeepingImage->rootPathSmallImage() . '/' . $imageName)) {
                                if (copy($model->rootPathFullImage() . '/' . $imageName, $modelTimekeepingImage->rootPathFullImage() . '/' . $imageName)) {
                                    $modelTimekeepingImage->insert($imageName, $newTimekeepingId, $reportPeriod);
                                }
                            }

                        }
                    }
                }
                return true;
            }

        } else {
            return false;
        }

    }

    # huy cham cong
    public function deleteInfo($timekeepingId = null)
    {
        $model = new QcTimekeepingProvisionalImage();
        $timekeepingId = $this->checkNullId($timekeepingId);
        $dataTimekeepingProvisionalImage = $this->imageOfTimekeepingProvisional($timekeepingId);
        if (QcTimekeepingProvisional::where('timekeeping_provisional_id', $timekeepingId)->delete()) {
            if (count($dataTimekeepingProvisionalImage) > 0) {
                foreach ($dataTimekeepingProvisionalImage as $timekeepingProvisionalImage) {
                    $model->dropImage($timekeepingProvisionalImage->name());
                }
            }
        }
    }

    //kiển tra người nhập
    public function checkStaffInput($staffId, $timekeepingId = null)
    {
        return QcTimekeepingProvisional::where('staffCheck_id', $staffId)->where('timekeeping_provisional_id', $this->checkNullId($timekeepingId))->exists();
    }

    //========== ========== ========== CAC MOI QUAN HE ========== ========== ==========
    //----------- NHAN VIEN ------------
    public function staff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'staffConfirm_id', 'staff_id');
    }

    //----------- LAM VIEC ------------
    public function work()
    {
        return $this->belongsTo('App\Models\Ad3d\Work\QcWork', 'work_id', 'work_id');
    }

    // disable of staff
    public function disableOfWork($workId)
    {
        return QcTimekeepingProvisional::where('work_id', $workId)->update(['action' => 0]);
    }

    public function infoActivityOfStaff($workId)
    {
        return QcTimekeepingProvisional::where(['work_id' => $workId, 'workStatus' => 1])->first();
    }

    public function infoOfWork($workId, $orderBy = null)
    {
        $orderBy = (empty($orderBy)) ? 'DESC' : $orderBy;
        return QcTimekeepingProvisional::where(['work_id' => $workId])->orderBy('timeBegin', "$orderBy")->get();
    }

    public function existDateOfWork($workId, $dateYmd)
    {
        $dateYmd = date('Y-m-d', strtotime($dateYmd));
        $result = QcTimekeepingProvisional::where('work_id', $workId)->where('timeBegin', 'like', "%$dateYmd%")->count();
        return ($result > 0) ? true : false;
    }

    public function getInfoOfWorkAndDate($workId, $dateYmd)
    {
        $dateYmd = date('Y-m-d', strtotime($dateYmd));
        return QcTimekeepingProvisional::where('work_id', $workId)->where('timeBegin', 'like', "%$dateYmd%")->first();
    }

    //----------- TIMEKEEPING-PROVISIONAL-IAMGE ------------
    public function timekeepingProvisionalImage()
    {
        return $this->hasMany('App\Models\Ad3d\TimekeepingProvisionalImage\QcTimekeepingProvisionalImage', 'timekeeping_provisional_id', 'timekeeping_provisional_id');
    }

    # lay tat ca hinh anh bao cao
    public function imageOfTimekeepingProvisional($id = null)
    {
        $model = new QcTimekeepingProvisionalImage();
        return $model->infoOfTimekeepingProvisional($id);
    }

    # lay thông tin anh bao cao cua buoi sang
    public function infoTimekeepingProvisionalImageInMorning($id = null)
    {
        $model = new QcTimekeepingProvisionalImage();
        return $model->infoOfTimekeepingProvisionalInMorning($this->checkNullId($id));
    }

    # kiem tra co ton tại anh bao cao cua buoi sang
    public function checkExistTimekeepingProvisionalImageInMorning($id = null)
    {
        $hFunction = new \Hfunction();
        return ($hFunction->checkCount($this->infoTimekeepingProvisionalImageInMorning($id))) ? true : false;
    }

    # lay thông tin anh bao cao cua buoi chieu
    public function infoTimekeepingProvisionalImageInAfternoon($id = null)
    {
        $model = new QcTimekeepingProvisionalImage();
        return $model->infoOfTimekeepingProvisionalInAfternoon($this->checkNullId($id));
    }

    # kiem tra co ton tại anh bao cao cua buoi chieu
    public function checkExistTimekeepingProvisionalImageInAfternoon($id = null)
    {
        $hFunction = new \Hfunction();
        return ($hFunction->checkCount($this->infoTimekeepingProvisionalImageInAfternoon($id))) ? true : false;
    }

    # lay thông tin anh bao cao tang ca
    public function infoTimekeepingProvisionalImageInEvening($id = null)
    {
        $model = new QcTimekeepingProvisionalImage();
        return $model->infoOfTimekeepingProvisionalInEvening($this->checkNullId($id));
    }

    # kiem tra co ton tại anh bao cao cua buoi toi
    public function checkExistTimekeepingProvisionalImageInEvening($id = null)
    {
        $hFunction = new \Hfunction();
        return ($hFunction->checkCount($this->infoTimekeepingProvisionalImageInEvening($id))) ? true : false;
    }

    //----------- THONG TIN CANH BAP ------------
    public function timekeepingProvisionalWarning()
    {
        return $this->hasMany('App\Models\Ad3d\TimekeepingProvisionalWarning\QcTimekeepingProvisionalWarning', 'timekeeping_provisional_id', 'timekeeping_provisional_id');
    }

    # lay thong tin canh bao cham cong - tat ca
    public function timekeepingProvisionalWarningGetInfo($timekeepingId = null){
        $modelTimekeepingProvisionalWarning = new QcTimekeepingProvisionalWarning();
        return $modelTimekeepingProvisionalWarning->infoOfTimekeepingProvisional($this->checkNullId($timekeepingId));
    }

    #lay thong tin canh bao gio vao
    public function timekeepingProvisionalWarningGetTimeBegin($timekeepingId = null)
    {
        $modelTimekeepingProvisionalWarning = new QcTimekeepingProvisionalWarning();
        return $modelTimekeepingProvisionalWarning->infoTimeBeginOfTimekeepingProvisional($this->checkNullId($timekeepingId));
    }

    #lay thong tinn canh bao gio ra
    public function timekeepingProvisionalWarningGetTimeEnd($timekeepingId = null)
    {
        $modelTimekeepingProvisionalWarning = new QcTimekeepingProvisionalWarning();
        return $modelTimekeepingProvisionalWarning->infoTimeEndOfTimekeepingProvisional($this->checkNullId($timekeepingId));
    }

    //============ =========== ============ GET INFO ============= =========== ==========
    public function getInfo($timekeepingId = '', $field = '')
    {
        if (empty($timekeepingId)) {
            return QcTimekeepingProvisional::where('workStatus', 1)->get();
        } else {
            $result = QcTimekeepingProvisional::where('timekeeping_provisional_id', $timekeepingId)->first();
            if (empty($field)) {
                return $result;
            } else {
                return $result->$field;
            }
        }
    }

    public function pluck($column, $objectId = null)
    {
        if (empty($objectId)) {
            return $this->$column;
        } else {
            return QcTimekeepingProvisional::where('timekeeping_provisional_id', $objectId)->pluck($column);
        }
    }

    //----------- lay  thong tin -------------
    # lay tat ca thong tin
    public function selectInfoByListWorkAndDate($listWorkId, $dateFilter = null, $oderBy = 'DESC')
    {
        if (empty($dateFilter)) {
            return QcTimekeepingProvisional::whereIn('work_id', $listWorkId)->orderBy('timeBegin', $oderBy)->select('*');
        } else {
            return QcTimekeepingProvisional::whereIn('work_id', $listWorkId)->where('timeBegin', 'like', "%$dateFilter%")->orderBy('timeBegin', $oderBy)->select('*');
        }
    }

    # lay thong tin chua xac nhan
    public function selectInfoUnconfirmedByListWorkAndDate($listWorkId, $dateFilter = null, $oderBy = 'DESC')
    {
        if (empty($dateFilter)) {
            return QcTimekeepingProvisional::whereIn('work_id', $listWorkId)->where('confirmStatus', 0)->orderBy('timeBegin', $oderBy)->select('*');
        } else {
            return QcTimekeepingProvisional::whereIn('work_id', $listWorkId)->where('confirmStatus', 0)->where('timeBegin', 'like', "%$dateFilter%")->orderBy('timeBegin', $oderBy)->select('*');
        }
    }


    public function timekeepingProvisionalId()
    {
        return $this->timekeeping_provisional_id;
    }

    public function timeBegin($timekeepingId = null)
    {
        return $this->pluck('timeBegin', $timekeepingId);
    }

    public function timeEnd($timekeepingId = null)
    {
        return $this->pluck('timeEnd', $timekeepingId);
    }

    public function afternoonStatus($timekeepingId = null)
    {
        return $this->pluck('afternoonStatus', $timekeepingId);
    }

    public function  lateTimekeeping($timekeepingId = null)
    {
        return $this->pluck('lateTimekeeping', $timekeepingId);
    }

    public function note($timekeepingId = null)
    {
        return $this->pluck('note', $timekeepingId);
    }

    public function workStatus($timekeepingId = null)
    {
        return $this->pluck('workStatus', $timekeepingId);
    }

    public function accuracyStatus($timekeepingId = null)
    {
        return $this->pluck('accuracyStatus', $timekeepingId);
    }

    public function confirmNote($timekeepingId = null)
    {
        return $this->pluck('confirmNote', $timekeepingId);
    }

    public function confirmDate($timekeepingId = null)
    {
        return $this->pluck('confirmDate', $timekeepingId);
    }

    public function confirmStatus($timekeepingId = null)
    {
        return $this->pluck('confirmStatus', $timekeepingId);
    }

    public function workId($timekeepingId = null)
    {
        return $this->pluck('work_id', $timekeepingId);
    }

    public function staffConfirmId($timekeepingId = null)
    {
        return $this->pluck('staffConfirm_id', $timekeepingId);
    }

    public function createdAt($timekeepingId = null)
    {
        return $this->pluck('created_at', $timekeepingId);
    }

    public function updatedAt($timekeepingId = null)
    {
        return $this->pluck('updated_at', $timekeepingId);
    }

    //======= kiểm tra thông tin =========
    # kiem tra hang bao gio ra
    public function checkTimeOutToEndWork($timekeepingId = null)
    {
        $hFunction = new \Hfunction();
        $modelCompany = new QcCompany();
        # kiem tra han bao gio ra
        $currentDateCheck = $hFunction->carbonNow();
        $beginCheckDate = $modelCompany->getDefaultTimeBeginToWorkOfDate($this->timeBegin($timekeepingId));
        $endCheck = $hFunction->datetimePlusDay($beginCheckDate, 1); # sau 1 ngay
        return ($endCheck < $currentDateCheck) ? false : true;
    }

    # kiem tra co tang ca hay khong
    public function checkHasOverTime($timekeepingId = null)
    {
        $modelCompany = new QcCompany();
        # thoi gian bao ra
        $timeEnd = $this->timeEnd($timekeepingId);
        # thoi gia ra mac dinh trong ngay yeu cau tang ca
        $timeEndDefault = $modelCompany->getDefaultTimeEndToWorkOfDate($timeEnd);
        return ($timeEnd > $timeEndDefault) ? true : false;
    }

    # kiem tra co lam trua hay khong
    public function checkAfternoonWork($timekeepingId = null)
    {
        return ($this->afternoonStatus($timekeepingId)[0] == 0) ? false : true;
    }

    # kiem tra thong tin co xac nhan chua
    public function checkConfirmStatus($timekeepingId = null)
    {
        $result = $this->confirmStatus($timekeepingId);
        $result = (is_int($result)) ? $result : $result[0];
        return ($result == 0) ? false : true;
    }

    # kiem tra bao gio co chinh xac hay khong
    public function checkAccuracyStatus($timekeepingId = null)
    {
        return ($this->accuracyStatus($timekeepingId)[0] == 0) ? false : true;
    }

    //======= thống kê =========
    public function totalNewTimekeepingProvisional($companyId = null)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelWork = new QcWork();
        if (empty($companyId)) {
            return QcTimekeepingProvisional::where('confirmStatus', 0)->count();
        } else {
            $listWorkId = $modelWork->listIdOfListCompanyStaffId($modelCompanyStaffWork->listIdOfListCompanyAndListStaff([$companyId]));
            return QcTimekeepingProvisional::whereIn('work_id', $listWorkId)->where('confirmStatus', 0)->count();
        }

    }

    # thong tin chua duyet cua thang hien hanh cua 1 cong ty
    public function totalInfoUnconfirmed($companyId)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelWork = new QcWork();
        $date = date('Y-m');
        $listWorkId = $modelWork->listIdOfListCompanyStaffId($modelCompanyStaffWork->listIdOfListCompanyAndListStaff([$companyId]));
        return QcTimekeepingProvisional::whereIn('work_id', $listWorkId)->where('timeBegin', 'like', "%$date%")->where('confirmStatus', 0)->count();
    }
}
