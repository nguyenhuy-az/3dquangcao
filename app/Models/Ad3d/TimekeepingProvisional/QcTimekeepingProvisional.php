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
    # mac dinh cham cong tre
    public function getDefaultHasLateTimekeeping()
    {
        return 1;
    }

    # mac dinh cham cong khong tre
    public function getDefaultNotLateTimekeeping()
    {
        return 0;
    }

    # mac dinh bao gio chinh xac
    public function getDefaultHasAccuracyStatus()
    {
        return 1;
    }

    # xac mac dinh bao gio khong chinh
    public function getDefaultNotAccuracyStatus()
    {
        return 0;
    }

    # mac dinh co xac nhan
    public function getDefaultHasConfirmStatus()
    {
        return 1;
    }

    # mac dinh khong xac nhan
    public function getDefaultNotConfirmStatus()
    {
        return 0;
    }

    # mac dinh co lam trua
    public function getDefaultHasAfternoonStatus()
    {
        return 1;
    }

    # lay trang thai khong co lam trua mac dinh
    public function getDefaultNotAfternoonStatus()
    {
        return 0;
    }

    # mac dinh dang hoat dong
    public function getDefaultHasWorkStatus()
    {
        return 1;
    }

    # mac dinh khong hoat dong
    public function getDefaultNotWorkStatus()
    {
        return 0;
    }

    # mac dinh  tre co khong phep
    public function getDefaultHasPermissionStatus()
    {
        return 1;
    }

    #mac dinh tre khong phep
    public function getDefaultNotPermissionStatus()
    {
        return 0;
    }

    # mac dinh co tinh cong
    public function getDefaultHasTimekeeping()
    {
        return 1;
    }

    # mac dinh khong tinh cong
    public function getDefaultNotTimekeeping()
    {
        return 0;
    }

    # trang thai mac dinh co ap dung noi quy
    public function getDefaultHasApplyRule()
    {
        return 1;
    }

    # trang thai mac dinh khong ap dung noi quy
    public function getDefaultNotApplyRule()
    {
        return 0;
    }

    # mac dinh nguoi duyet
    public function getDefaultConfirmStaff()
    {
        return null; # null -> duyet tu dong
    }

    #mac dinh gio ra
    public function getDefaultTimeEnd()
    {
        return null;
    }

    //---------- them ----------workStatus
    public function insert($timeBegin, $timeEnd, $note, $afternoonStatus, $workId, $staffConfirmId)
    {
        $hFunction = new \Hfunction();
        $modelCompany = new QcCompany();
        $modelTimekeeping = new QcTimekeepingProvisional();
        # mac dinh gio cham cong vao
        $defaultBegin = $modelCompany->getDefaultTimeBeginToWorkOfDate($timeBegin);
        # tính trừ thời gian trễ
        if ($hFunction->formatDateToYMDHI($defaultBegin) < $hFunction->formatDateToYMDHI($timeBegin)) {
            $lateTimekeeping = $this->getDefaultHasLateTimekeeping(); # tre
        } else {
            $lateTimekeeping = $this->getDefaultNotLateTimekeeping();
        }
        $modelTimekeeping->timeBegin = $timeBegin;
        $modelTimekeeping->timeEnd = $timeEnd;
        $modelTimekeeping->note = $note;
        $modelTimekeeping->afternoonStatus = $afternoonStatus;
        $modelTimekeeping->lateTimekeeping = $lateTimekeeping;
        $modelTimekeeping->work_id = $workId;
        $modelTimekeeping->staffConfirm_id = $staffConfirmId;
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

    # cap nhat xac nhan
    public function updateConfirm($timekeepingProvisionalId, $confirmNote, $accuracyStatus, $confirmStaffId)
    {
        $hFunction = new \Hfunction();
        return QcTimekeepingProvisional::where(['timekeeping_provisional_id' => $timekeepingProvisionalId])->update(
            [
                'accuracyStatus' => $accuracyStatus,
                'staffConfirm_id' => $confirmStaffId,
                'confirmNote' => $confirmNote,
                'confirmStatus' => $this->getDefaultHasConfirmStatus(),
                'confirmDate' => $hFunction->createdAt(),
            ]);
    }

    // kiem tra tong tin cham cong lam viec trong ngay - KIEM TRA NGAY TRƯƠC - theo bang cham cong
    /*
     * goi trong qc_work
     * kiem tra theo tung ngay
     * */
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
            if (empty($timeEnd)) { # khong co bao gio ra
                if ($this->updateConfirm($dataTimeKeepingProvisional->timekeepingProvisionalId(), 'Không báo giờ ra', $this->getDefaultNotAccuracyStatus(), $this->getDefaultConfirmStaff())) {
                    # chua ton tai trong bang cham con chinh thuc
                    if (!$modelTimekeeping->existDateOfWork($workId, $checkDate)) {
                        $timekeepingLateStatus = $dataTimeKeepingProvisional->lateTimeKeeping();
                        $timekeepingTimeEnd = $modelTimekeeping->getDefaultTimeEnd();
                        $timekeepingDateOff = $modelTimekeeping->getDefaultDateOff();
                        $timekeepingMainMinute = $modelTimekeeping->getDefaultMainMinute();
                        $timekeepingPlusMinute = $modelTimekeeping->getDefaultPlusMinute();
                        $timekeepingMinute = $modelTimekeeping->getDefaultMinusMinute();
                        $timekeepingConfirmNote = 'Duyệt tự động - không báo giờ ra';
                        $timekeepingConfirmStaffId = $modelTimekeeping->getDefaultConfirmStaffId();
                        $timekeepingNote = $modelTimekeeping->getDefaultConfirmNote();
                        $timekeepingAfternoonStatus = $modelTimekeeping->getDefaultNotAfternoonStatus();
                        $timekeepingPermissionStatus = $modelTimekeeping->getDefaultNotPermissionStatus();
                        $timekeepingWorkStatus = $modelTimekeeping->getDefaultNotWorkStatus();
                        $modelTimekeeping->insert($timeBegin, $timekeepingTimeEnd, $timekeepingDateOff, $timekeepingAfternoonStatus, $timekeepingMainMinute, $timekeepingPlusMinute, $timekeepingMinute, $timekeepingNote, $timekeepingConfirmNote, $timekeepingLateStatus, $timekeepingPermissionStatus, $timekeepingWorkStatus, $timekeepingConfirmStaffId, $workId);
                    }
                }
            }
        } else {
            // khong bao cham cong
            if (!$hFunction->checkDateIsSunday($checkDate)) { # khong phai la ngay chu nhat
                $companyId = $modelWork->companyIdOfWork($workId);
                if (!$modelSystemDateOff->checkExistsDateOfCompany($companyId, $checkDate)) { # khong phai ngay nghi cua he thong ==> nghi khong phép
                    $dataLicenseOffWork = $modelLicenseOffWork->infoOfStaffAndDate($staffId, $checkDate);
                    # lay ma phat nghi khong phep
                    $punishIdOfOffWork = $modelPunishContent->getPunishIdForOffWork();
                    $punishIdOfOffWork = (is_int($punishIdOfOffWork)) ? $punishIdOfOffWork : $punishIdOfOffWork[0];
                    # co xin nghi
                    # lay gia tri mac dinh
                    $timekeepingTimeBegin = $modelTimekeeping->getDefaultTimeBegin();
                    $timekeepingTimeEnd = $modelTimekeeping->getDefaultTimeEnd();
                    $timekeepingMainMinute = $modelTimekeeping->getDefaultMainMinute();
                    $timekeepingPlusMinute = $modelTimekeeping->getDefaultPlusMinute();
                    $timekeepingMinute = $modelTimekeeping->getDefaultMinusMinute();
                    $timekeepingConfirmNote = 'Duyệt tự động - không chấm công';
                    $timekeepingConfirmStaffId = $modelTimekeeping->getDefaultConfirmStaffId();
                    $timekeepingAfternoonStatus = $modelTimekeeping->getDefaultNotAfternoonStatus();
                    $timekeepingLateStatus =  $modelTimekeeping->getDefaultNotLateStatus();
                    $timekeepingWorkStatus = $modelTimekeeping->getDefaultNotWorkStatus();
                    $timekeepingNote = $modelTimekeeping->getDefaultConfirmNote();
                    if ($hFunction->checkCount($dataLicenseOffWork)) {
                        # duoc duyet nghi
                        if ($dataLicenseOffWork->checkAgreeStatus()) {
                            # them thong tin vao ngay cham cong
                            if (!$modelTimekeeping->existDateOfWork($workId, $checkDate)) {
                                $timekeepingPermissionStatus = $modelTimekeeping->getDefaultHasPermissionStatus();
                                $modelTimekeeping->insert($timekeepingTimeBegin, $timekeepingTimeEnd, $checkDate, $timekeepingAfternoonStatus, $timekeepingMainMinute, $timekeepingPlusMinute, $timekeepingMinute, $timekeepingNote,$timekeepingConfirmNote, $timekeepingLateStatus, $timekeepingPermissionStatus, $timekeepingWorkStatus, $timekeepingConfirmStaffId, $workId);
                            }
                        } else {
                            # khong duoc duyet
                            if (!$modelTimekeeping->existDateOfWork($workId, $checkDate)) {
                                $timekeepingPermissionStatus = $modelTimekeeping->getDefaultNotPermissionStatus();
                                if ($modelTimekeeping->insert($timekeepingTimeBegin, $timekeepingTimeEnd, $checkDate, $timekeepingAfternoonStatus, $timekeepingMainMinute, $timekeepingPlusMinute, $timekeepingMinute,$timekeepingNote,$timekeepingConfirmNote, $timekeepingLateStatus, $timekeepingPermissionStatus, $timekeepingWorkStatus, $timekeepingConfirmStaffId, $workId)) {
                                    if ($modelStaff->checkApplyRule($staffId)) { # ap dung noi quy
                                        if (!empty($punishIdOfOffWork)) {
                                            $applyStatus = $modelMinusMoney->getDefaultHasAction();
                                            $minusReason = 'Duyệt tự động';
                                            $minusOrderAllocationId = $modelMinusMoney->getDefaultOrderAllocation();
                                            $minusOrderConstructionId = $modelMinusMoney->getDefaultOrderConstruction();
                                            $minusCompanyStoreCheckReportId = $modelMinusMoney->getDefaultCompanyStoreCheckReport();
                                            $minusWorkAllocationId = $modelMinusMoney->getDefaultWorkAllocation();
                                            $minusMoney = $modelMinusMoney->getDefaultMoney();
                                            $minusReasonImage = $modelMinusMoney->getDefaultReasonImage();
                                            $modelMinusMoney->insert($hFunction->formatDateToYMDHI($checkDate), $minusReason, $workId, $staffId, $punishIdOfOffWork, $applyStatus, $minusOrderAllocationId, $minusOrderConstructionId, $minusCompanyStoreCheckReportId, $minusWorkAllocationId, $minusMoney, $minusReasonImage);
                                        }
                                    }

                                }
                            }
                        }

                    } else { # khong xin nghi
                        # them thong tin vao ngay cham cong
                        if (!$modelTimekeeping->existDateOfWork($workId, $checkDate)) {
                            $timekeepingPermissionStatus = $modelTimekeeping->getDefaultNotPermissionStatus();
                            if ($modelTimekeeping->insert($timekeepingTimeBegin, $timekeepingTimeEnd, $checkDate, $timekeepingAfternoonStatus, $timekeepingMainMinute, $timekeepingPlusMinute, $timekeepingMinute, $timekeepingNote, $timekeepingConfirmNote, $timekeepingLateStatus, $timekeepingPermissionStatus, $timekeepingWorkStatus, $timekeepingConfirmStaffId, $workId)) {
                                if ($modelStaff->checkApplyRule($staffId)) {
                                    # ap dung noi quy
                                    if (!empty($punishIdOfOffWork)) {
                                        $applyStatus = $modelMinusMoney->getDefaultHasApplyStatus();
                                        $minusReason = 'Duyệt tự động';
                                        $minusOrderAllocationId = $modelMinusMoney->getDefaultOrderAllocation();
                                        $minusOrderConstructionId = $modelMinusMoney->getDefaultOrderConstruction();
                                        $minusCompanyStoreCheckReportId = $modelMinusMoney->getDefaultCompanyStoreCheckReport();
                                        $minusWorkAllocationId = $modelMinusMoney->getDefaultWorkAllocation();
                                        $minusMoney = $modelMinusMoney->getDefaultMoney();
                                        $minusReasonImage = $modelMinusMoney->getDefaultReasonImage();
                                        $modelMinusMoney->insert($hFunction->formatDateToYMDHI($checkDate), $minusReason, $workId, $staffId, $punishIdOfOffWork, $applyStatus, $minusOrderAllocationId, $minusOrderConstructionId, $minusCompanyStoreCheckReportId, $minusWorkAllocationId, $minusMoney, $minusReasonImage);
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

    #===== ====== cap nhat bao gio vao ===== ======
    /*chi cap nhat gio vao sau khi bi canh bao*/
    public function updateTimeBegin($timekeepingId, $timeBegin)
    {
        $modelTimekeepingProvisionalWarning = new QcTimekeepingProvisionalWarning();
        # neu da ton tai canh bao
        if ($modelTimekeepingProvisionalWarning->checkExistWarningTimeBeginOfTimekeepingProvisional($timekeepingId)) {
            # cap nhat ngay canh bao gio vao
            return $modelTimekeepingProvisionalWarning->updateTimeBeginOfTimekeepingProvisional($timekeepingId, $timeBegin);
        } else {
            return false;
        }
    }

    # lay gio vao tinh cong ap dung thuc te
    public function getApplyTimeBegin($timekeepingId = null)
    {
        $hFunction = new \Hfunction();
        $modelWarning = new QcTimekeepingProvisionalWarning();
        $timekeepingId = $this->checkNullId($timekeepingId);
        $dataWarning = $modelWarning->infoTimeBeginOfTimekeepingProvisional($timekeepingId);
        # co canh bao
        if ($hFunction->checkCount($dataWarning)) {
            $updateDate = $dataWarning->updateDate();
            if (!$hFunction->checkEmpty($updateDate)) { // co cap nhat
                return $updateDate;
            } else {
                return $this->timeBegin($timekeepingId);
            }
        } else {
            return $this->timeBegin($timekeepingId);
        }

    }

    #===== ====== CAP NHAT BAO GIO RA ===== ======
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
        } else {
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
                    $warningNote = 'Báo giờ ra không đúng - báo trước giờ ra';
                    $warningImage = $modelTimekeepingProvisionalWarning->getDefaultImage();
                    $warningStaffId = $modelTimekeepingProvisionalWarning->getDefaultWarningStaffId();
                    $modelTimekeepingProvisionalWarning->insert($warningNote, $warningImage, $modelTimekeepingProvisionalWarning->getDefaultWarningTypeTimeEnd(), $timekeepingId, $warningStaffId);
                }
            }
            return true;
        } else {
            return false;
        }
    }

    # lay gio ra tinh cong ap dung thuc te
    public function getApplyTimeEnd($timekeepingId = null)
    {
        $hFunction = new \Hfunction();
        $modelWarning = new QcTimekeepingProvisionalWarning();
        $timekeepingId = $this->checkNullId($timekeepingId);
        $dataWarning = $modelWarning->infoTimeEndOfTimekeepingProvisional($timekeepingId);
        # co canh bao
        if ($hFunction->checkCount($dataWarning)) {
            $updateDate = $dataWarning->updateDate();
            if (!$hFunction->checkEmpty($updateDate)) { // co cap nhat
                return $updateDate;
            } else {
                return $this->timeEnd($timekeepingId);
            }
        } else {
            return $this->timeEnd($timekeepingId);
        }

    }

    # kiem tra thoi gian de xac nhan - chi xac nhan sau 5h30 trong ngay - ngay hien tai
    public function checkToConfirmOfDate($date)
    {
        $modelCompany = new QcCompany();
        $timeDefault = $modelCompany->getDefaultTimeEndToWorkOfDate($date);
        $timeCheck = date('Y-m-d H:i');
        return ($timeCheck > $timeDefault) ? true : false;
    }

    //===== ===== XAC NHAN CHAM CONG ===== ======= ======
    # kiem tra duyet tu dong hay duyet tay
    public function checkIsAutoConfirm($confirmStaffId)
    {
        $hFunction = new \Hfunction();
        return ($hFunction->checkEmpty($confirmStaffId)) ? true : false;
    }
    # duyet cham cong tu dong khi xuat bang luong - xac nhan tu dong
    /*
     * goi trong xuat bang luong qc_work
     * */
    public function autoConfirmForMakeSalaryOfWork($workId)
    {
        $hFunction = new \Hfunction();
        $dataTimekeeping = $this->getInfoUnconfirmed($workId);
        if ($hFunction->checkCount($dataTimekeeping)) {
            foreach ($dataTimekeeping as $timekeeping) {
                $timekeepingId = $timekeeping->timekeepingProvisionalId();
                $confirmNote = "Duyệt tự động cuối tháng";
                $permissionLateStatus = $this->getDefaultNotPermissionStatus(); # mac dinh tre khong phep
                $accuracyStatus = $this->getDefaultHasAccuracyStatus();# mac dinh chinh xac
                $applyTimekeepingStatus = $this->getDefaultHasTimekeeping();# mac dinh duoc tin cong
                $applyRuleStatus = $this->getDefaultHasApplyRule();
                $confirmStaffId = $this->getDefaultConfirmStaff(); # duyet tu dong
                $this->confirmWork($timekeepingId, $confirmStaffId, $confirmNote, $permissionLateStatus, $accuracyStatus, $applyTimekeepingStatus, $applyRuleStatus);
            }
        }

    }

    /*
     * duyet khi co gio ra
     * $confirmStaffId = NULL  -> duyet tu dong
     * goi khi quan lý duyet cham cong  -> timekeepingProvisionalController
     * duyet tu dong cuoi thang -> this function
     *
     * */
    public function confirmWork($timekeepingProvisionalId, $confirmStaffId, $confirmNote, $permissionLateStatus, $accuracyStatus, $applyTimekeepingStatus, $applyRuleStatus)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelWork = new QcWork();
        $modelPunishContent = new QcPunishContent();
        $modelMinusMoney = new QcMinusMoney();
        $modelTimekeeping = new QcTimekeeping();
        $modelTimekeepingImage = new QcTimekeepingImage();
        $modelTimekeepingProvisional = new QcTimekeepingProvisional();
        $model = new QcTimekeepingProvisionalImage();
        # kiem tra co cap nhat khi canh bao neu co
        if (!$modelTimekeepingProvisional->checkUpdateTimekeepingProvisionalWaring($timekeepingProvisionalId)) { # khong cap nhat
            # khong tinh cong neu khong cap nhat
            $applyTimekeepingStatus = $this->getDefaultNotTimekeeping();
            $confirmNote = $confirmNote . " - Không báo lại khi bị cảnh báo sai";
        }
        if ($accuracyStatus == $this->getDefaultNotAccuracyStatus()) $confirmNote = $confirmNote . " - Báo giờ không chính xác";
        if ($applyTimekeepingStatus == $this->getDefaultNotTimekeeping()) $confirmNote = $confirmNote . " - Không tính công ngày này";
        if ($applyRuleStatus == $this->getDefaultNotApplyRule()) $confirmNote = $confirmNote . " - Không áp dụng nội quy phạt";
        # cap nhat thong tin
        if ($this->updateConfirm($timekeepingProvisionalId, $confirmNote, $accuracyStatus, $confirmStaffId)) {
            $dataTimekeepingProvisional = $modelTimekeepingProvisional->getInfo($timekeepingProvisionalId);
            $workId = $dataTimekeepingProvisional->workId();
            $note = $dataTimekeepingProvisional->note();
            $afternoonStatus = $dataTimekeepingProvisional->afternoonStatus();
            # lay thoi gian cham cong thuc te duoc ap dung
            $dateBegin = $dataTimekeepingProvisional->getApplyTimeBegin();

            $dateEnd = $dataTimekeepingProvisional->getApplyTimeEnd();
            if ($modelTimekeeping->existDateOfWork($workId, $dateBegin)) { # da ton tai cham cong
                return false;
            } else {
                # mac dinh gio cham cong vao
                $defaultBegin = $modelCompany->getDefaultTimeBeginToWorkOfDate($dateBegin);
                # mac dinh gio cham cong ra
                $defaultEnd = $modelCompany->getDefaultTimeEndToWorkOfDate($dateBegin);
                $mainMinute = $modelTimekeeping->getDefaultMainMinute();
                $plusMinute = $modelTimekeeping->getDefaultPlusMinute();
                $minusMinute = $modelTimekeeping->getDefaultMinusMinute();
                $lateStatus = $modelTimekeeping->getDefaultNotLateStatus();// mac dinh la khong tre
                # ----- ap dung noi quy phat  --------
                if ($applyRuleStatus == $this->getDefaultHasApplyRule()) {
                    //khong phai ngay chu nhat
                    if (!$hFunction->checkDateIsSunday($dateBegin)) {
                        if ($hFunction->formatDateToYMDHI($defaultBegin) < $hFunction->formatDateToYMDHI($dateBegin)) { # tính trừ thời gian trễ
                            $staffId = $modelWork->staffId($workId);
                            if ($modelStaff->checkApplyRule($staffId)) { # ap dung noi quy
                                # ly do
                                $minusApplyStatus = $modelMinusMoney->getDefaultHasApplyStatus();
                                $minusOrderAllocationId = $modelMinusMoney->getDefaultOrderAllocation();
                                $minusOrderConstructionId = $modelMinusMoney->getDefaultOrderConstruction();
                                $minusCompanyStoreCheckReportId = $modelMinusMoney->getDefaultCompanyStoreCheckReport();
                                $minusWorkAllocationId = $modelMinusMoney->getDefaultWorkAllocation();
                                $minusMoney = $modelMinusMoney->getDefaultMoney();
                                $minusReason = $modelMinusMoney->getDefaultReason();
                                $minusReasonImage = $modelMinusMoney->getDefaultReasonImage();
                                # tre khong phep
                                if ($permissionLateStatus == $this->getDefaultNotPermissionStatus()) {
                                    $punishId = $modelPunishContent->getPunishIdForLateWork();
                                    ///$punishId = (is_int($punishId)) ? $punishId : $punishId[0];
                                    if (!empty($punishId)) {
                                        $modelMinusMoney->insert($dateBegin, $minusReason, $workId, $confirmStaffId, $punishId, $minusApplyStatus, $minusOrderAllocationId, $minusOrderConstructionId, $minusCompanyStoreCheckReportId, $minusWorkAllocationId, $minusMoney, $minusReasonImage);
                                    }
                                    $lateStatus = $modelTimekeeping->getDefaultHasLateStatus();
                                }
                                # bao gio khong chinh xac
                                if ($accuracyStatus == $this->getDefaultNotAccuracyStatus()) {
                                    $punishIdOfTimekeepingAccuracy = $modelPunishContent->getPunishIdForTimekeepingAccuracy();
                                    ///$punishIdOfTimekeepingAccuracy = (is_int($punishIdOfTimekeepingAccuracy)) ? $punishIdOfTimekeepingAccuracy : $punishIdOfTimekeepingAccuracy[0];
                                    if (!empty($punishIdOfTimekeepingAccuracy)) {
                                        $modelMinusMoney->insert($dateBegin, $minusReason, $workId, $confirmStaffId, $punishIdOfTimekeepingAccuracy, $minusApplyStatus, $minusOrderAllocationId, $minusOrderConstructionId, $minusCompanyStoreCheckReportId, $minusWorkAllocationId, $minusMoney, $minusReasonImage);
                                    }
                                }
                            }
                        }
                    }
                }
                # ------ co tinh cong ngay lam --------
                if ($applyTimekeepingStatus == $this->getDefaultHasTimekeeping()) {
                    # co bao gio ra
                    if (!$hFunction->checkEmpty($dateEnd)) {
                        $diffLate = abs(strtotime($dateEnd) - strtotime($dateBegin));
                        $totalWorkMinute = $diffLate / 60; //số phút làm việc
                        //lam luon buoi trua
                        if ($modelTimekeepingProvisional->checkAfternoonWork($timekeepingProvisionalId)) $plusMinute = 60;// cộng 1h buổi trưa
                        # ngay chu nhat
                        if ($hFunction->checkDateIsSunday($dateBegin)) {
                            # gio lam ngay chu nhat tinh tang ca
                            if (360 < $totalWorkMinute) { // làm hơn 6 tiếng từ sáng
                                if ($dataTimekeepingProvisional->checkAfternoonWork($timekeepingProvisionalId)) {
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
                $dateOff = $modelTimekeeping->getDefaultDateOff();
                if ($modelTimekeeping->insert($dateBegin, $dateEnd, $dateOff, $afternoonStatus, $mainMinute, $plusMinute, $minusMinute, $note, $confirmNote, $lateStatus, $permissionLateStatus, $modelTimekeeping->getDefaultNotWorkStatus(), $confirmStaffId, $workId)) {
                    $newTimekeepingId = $modelTimekeeping->insertGetId();
                    $dataTimekeepingProvisionalImage = $modelTimekeepingProvisional->imageOfTimekeepingProvisional($timekeepingProvisionalId);
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

    # xoa cham cong
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
        return QcTimekeepingProvisional::where('work_id', $workId)->update(['workStatus' => $this->getDefaultNotWorkStatus()]);
    }

    public function infoActivityOfStaff($workId)
    {
        return QcTimekeepingProvisional::where(['work_id' => $workId, 'workStatus' => $this->getDefaultHasWorkStatus()])->first();
    }

    # lay tat ca thong tin bao cham cong cua bang cham cong
    public function infoOfWork($workId, $orderBy = null)
    {
        $orderBy = (empty($orderBy)) ? 'DESC' : $orderBy;
        return QcTimekeepingProvisional::where(['work_id' => $workId])->orderBy('timeBegin', "$orderBy")->get();
    }

    # lay thong tin bao cong chua xac nhan bang cham cong
    public function getInfoUnconfirmed($workId)
    {
        return QcTimekeepingProvisional::where(['work_id' => $workId])->orderBy('confirmStatus', $this->getDefaultNotConfirmStatus())->get();
    }

    # kiem tra ton tai ngay da bao cham cong
    public function existDateOfWork($workId, $dateYmd)
    {
        $dateYmd = date('Y-m-d', strtotime($dateYmd));
        return QcTimekeepingProvisional::where('work_id', $workId)->where('timeBegin', 'like', "%$dateYmd%")->exists();
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
    public function timekeepingProvisionalWarningGetInfo($timekeepingId = null)
    {
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

    # kiem tra co cap nhat cham cong khi co canh bao hay chua
    public function checkUpdateTimekeepingProvisionalWaring($timekeepingId = null)
    {
        $hFunction = new \Hfunction();
        $result = true; # mac dinh da cap nhat
        $dataWarning = $this->timekeepingProvisionalWarningGetInfo($timekeepingId);
        if ($hFunction->checkCount($dataWarning)) { # co canh bao
            foreach ($dataWarning as $warning) {
                if ($hFunction->checkEmpty($warning->updateDate())) $result = false; # khong co cap nhat gio vao hoac gio ra
            }
        }
        return $result;
    }

    //============ =========== ============ GET INFO ============= =========== ==========
    public function getInfo($timekeepingId = '', $field = '')
    {
        if (empty($timekeepingId)) {
            return QcTimekeepingProvisional::where('workStatus', $this->getDefaultHasWorkStatus())->get();
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
            return QcTimekeepingProvisional::where('timekeeping_provisional_id', $objectId)->pluck($column)[0];
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
        #khong xac nhan
        $notConfirm = $this->getDefaultNotConfirmStatus();
        if (empty($dateFilter)) {
            return QcTimekeepingProvisional::whereIn('work_id', $listWorkId)->where('confirmStatus', $notConfirm)->orderBy('timeBegin', $oderBy)->select('*');
        } else {
            return QcTimekeepingProvisional::whereIn('work_id', $listWorkId)->where('confirmStatus', $notConfirm)->where('timeBegin', 'like', "%$dateFilter%")->orderBy('timeBegin', $oderBy)->select('*');
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

    //======= ======   kiểm tra thông tin ========= ======
    # kiem tra duoc phep huy hay khong
    public function checkAllowCancel($timekeepingId = null)
    {
        $hFunction = new \Hfunction();
        $cancelStatus = false;
        # con han bao gio ra
        if ($this->checkTimeOutToEndWork($timekeepingId)) {
            ;
            # chua bao gio ra
            if ($hFunction->checkEmpty($this->timeEnd($timekeepingId))) {
                $cancelStatus = true;
            } else {
                # duyet roi chua xac nhan
                if (!$this->checkConfirmStatus($timekeepingId)) $cancelStatus = true;
            }
        }
        return $cancelStatus;

    }

    # kiem tra vo hieu hoa bao gio ra trong ngay
    public function checkDisableReportEndCurrentDate($timekeepingId = null)
    {
        $modelTimekeepingProvisionalImage = new QcTimekeepingProvisionalImage();
        $timekeepingId = $this->checkNullId($timekeepingId);
        $disableStatus = false;
        $timeBegin = $this->timeBegin($timekeepingId);
        # thoi gian hien tai
        $currentDateTime = date('Y-m-d H:i');
        # lay hang thoi gian bao cao cuoi ngay
        $fromDateTime = date('Y-m-d 17:10', strtotime($timeBegin)); # cho som 20 phut
        $toDateTime = date('Y-m-d 17:50', strtotime($timeBegin)); # cho tre 20 phut
        # chi kiem tra sau 17h50
        if ($currentDateTime > $toDateTime) {
            if ($modelTimekeepingProvisionalImage->checkExistReportInPeriodOfTimekeepingProvisional($timekeepingId, $fromDateTime, $toDateTime)) {
                # co anh bao cao
                $disableStatus = false;
            } else {
                # khong co anh bao cao
                $disableStatus = true;
            }
        }
        return $disableStatus;

    }

    # kiem tra hang bao gio ra
    public function checkTimeOutToEndWork($timekeepingId = null)
    {
        $hFunction = new \Hfunction();
        $modelCompany = new QcCompany();
        # kiem tra han bao gio ra
        $currentDateCheck = $hFunction->carbonNow();
        $beginCheckDate = $modelCompany->getDefaultTimeBeginToWorkOfDate($this->timeBegin($timekeepingId));
        # sau 1 ngay
        $endCheck = $hFunction->datetimePlusDay($beginCheckDate, 1);
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
        return ($this->afternoonStatus($timekeepingId) == $this->getDefaultNotAfternoonStatus()) ? false : true;
    }

    # kiem tra thong tin co xac nhan chua
    public function checkConfirmStatus($timekeepingId = null)
    {
        $result = $this->confirmStatus($timekeepingId);
        return ($result == $this->getDefaultNotConfirmStatus()) ? false : true;
    }

    # kiem tra bao gio co chinh xac hay khong
    public function checkAccuracyStatus($timekeepingId = null)
    {
        return ($this->accuracyStatus($timekeepingId) == $this->getDefaultNotAccuracyStatus()) ? false : true;
    }

    //======= thống kê =========
    public function totalNewTimekeepingProvisional($companyId = null)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelWork = new QcWork();
        $notConfirmStatus = $this->getDefaultNotConfirmStatus();
        if (empty($companyId)) {
            return QcTimekeepingProvisional::where('confirmStatus', $notConfirmStatus)->count();
        } else {
            $listWorkId = $modelWork->listIdOfListCompanyStaffId($modelCompanyStaffWork->listIdOfListCompanyAndListStaff([$companyId]));
            return QcTimekeepingProvisional::whereIn('work_id', $listWorkId)->where('confirmStatus', $notConfirmStatus)->count();
        }

    }

    # thong tin chua duyet cua thang hien hanh cua 1 cong ty
    public function totalInfoUnconfirmed($companyId)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelWork = new QcWork();
        $date = date('Y-m');
        $listWorkId = $modelWork->listIdOfListCompanyStaffId($modelCompanyStaffWork->listIdOfListCompanyAndListStaff([$companyId]));
        return QcTimekeepingProvisional::whereIn('work_id', $listWorkId)->where('timeBegin', 'like', "%$date%")->where('confirmStatus', $this->getDefaultNotConfirmStatus())->count();
    }
}
