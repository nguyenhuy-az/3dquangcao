<?php

namespace App\Models\Ad3d\CompanyStaffWork;

use App\Models\Ad3d\CompanyStoreCheck\QcCompanyStoreCheck;
use App\Models\Ad3d\Department\QcDepartment;
use App\Models\Ad3d\OverTimeRequest\QcOverTimeRequest;
use App\Models\Ad3d\Rank\QcRank;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\StaffWorkDepartment\QcStaffWorkDepartment;
use App\Models\Ad3d\StaffWorkSalary\QcStaffWorkSalary;
use App\Models\Ad3d\ToolPackageAllocation\QcToolPackageAllocation;
use App\Models\Ad3d\ToolPackageAllocationDetail\QcToolPackageAllocationDetail;
use App\Models\Ad3d\Work\QcWork;
use Illuminate\Database\Eloquent\Model;

class QcCompanyStaffWork extends Model
{
    protected $table = 'qc_company_staff_work';
    protected $fillable = ['work_id', 'beginDate', 'level', 'action', 'created_at', 'staff_id', 'staffAdd_id', 'company_id'];
    protected $primaryKey = 'work_id';
    public $timestamps = false;

    private $lastId;

    # mac dinh con hoat dong
    public function getDefaultHasAction()
    {
        return 1;
    }

    # mac dinh khong con hoat dong
    public function getDefaultNotAction()
    {
        return 0;
    }

    #mac dinh lay tat ca trang thai hoat dong
    public function getDefaultAllAction()
    {
        return 100;
    }

    # mac dinh cap bac try cap vao ad min
    public function getDefaultLevel()
    {
        return 5; # level binh thuong
    }

    # mac dinh cap bac truy cap admin
    public function getDefaultLevelAdmin()
    {
        return 3;
    }

    # mac dinh gioi hạn truy cap admin
    public function getDefaultLimitAdmin()
    {
        return 3; # <=3 duoc truy cap vao admin
    }

    # mac dinh chon het cap bac
    public function getDefaultAllLevel()
    {
        return 100; # dung chon loc thong tin
    }
    #========== ========== ========== INSERT && UPDATE ========== ========== ==========
    #---------- Insert ----------
    public function insert($beginDate, $level, $staffId, $staffAddId, $companyId)
    {
        $hFunction = new \Hfunction();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelCompanyStaffWork->beginDate = $beginDate;
        $modelCompanyStaffWork->level = $level;
        $modelCompanyStaffWork->staff_id = $staffId;
        $modelCompanyStaffWork->staffAdd_id = $staffAddId;
        $modelCompanyStaffWork->company_id = $companyId;
        $modelCompanyStaffWork->action = $this->getDefaultHasAction();
        $modelCompanyStaffWork->created_at = $hFunction->createdAt();
        if ($modelCompanyStaffWork->save()) {
            $this->lastId = $modelCompanyStaffWork->work_id;
            return true;
        } else {
            return false;
        }
    }

    # lấy id mới thêm
    public function insertGetId()
    {
        return $this->lastId;
    }

    public function checkIdNull($workId)
    {
        $hFunction = new \Hfunction();
        return ($hFunction->checkEmpty($workId)) ? $this->workId() : $workId;
    }

    # nghi - truc tiep
    public function updateEndWork($workId = null)
    {
        $hFunction = new \Hfunction();
        return QcCompanyStaffWork::where('work_id', $this->checkIdNull($workId))->update(
            [
                'action' => $this->getDefaultNotAction(),
                'endDate' => $hFunction->carbonNow()
            ]);
    }

    # khi xoa nhan vien
    public function deleteOfStaff($staffId)
    {
        $hFunction = new \Hfunction();
        return QcCompanyStaffWork::where('staff_id', $staffId)->where('action', $this->getDefaultHasAction())->update(
            [
                'action' => $this->getDefaultNotAction(),
                'endDate' => $hFunction->carbonNow()
            ]);
    }

    #cap nhat quyen admin
    public function updateLevel($level, $workId = null)
    {
        return QcCompanyStaffWork::where('work_id', $this->checkIdNull($workId))->update(['level' => $level]);
    }

    # mo bang cham cong mơi
    public function openWork($companyStaffWorkId)
    {
        $hFunction = new \Hfunction();
        $modelWork = new QcWork();
        $fromDateWork = $hFunction->currentDate();
        $toDateWork = $hFunction->lastDateOfMonthFromDate($fromDateWork);
        $modelWork->insert($fromDateWork, $toDateWork, $companyStaffWorkId);
    }

    # phuc hoi lai vi tri lam viec
    public function restoreWork($companyStaffWorkId)
    {
        $modelStaff = new QcStaff();
        $staffId = $this->staffId($companyStaffWorkId);
        if ($modelStaff->restoreWorkStatus($staffId)) {
            if (QcCompanyStaffWork::where('work_id', $companyStaffWorkId)->update(['action' => $this->getDefaultHasAction()])) {
                return $this->openWork($companyStaffWorkId);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    # ---------- ---------- tra do nghe ---------- ----------
    public function overTimeRequest()
    {
        return $this->hasMany('App\Models\Ad3d\OverTimeRequest\QcOverTimeRequest', 'work_id ', 'work_id');
    }

    # kiem ta ton tai yeu cau tang ca trong ngay
    public function checkExistOverTimeRequestOfDate($date, $workId = null)
    {
        $modelOverTimeRequest = new QcOverTimeRequest();
        return $modelOverTimeRequest->checkExistDateOfCompanyStaffWork($this->checkIdNull($workId), $date);
    }

    # lay thong tin yeu cau tang ca trong ngay
    public function overTimeRequestGetInfoInDate($date, $workId = null)
    {
        $modelOverTimeRequest = new QcOverTimeRequest();
        return $modelOverTimeRequest->getInfoOfCompanyStaffWorkAndDate($this->checkIdNull($workId), $date);
    }

    // kiem ta ton tai yeu cau tang ca đang hoat dong
    public function overTimeRequestGetInfoActivity($workId = null)
    {
        $modelOverTimeRequest = new QcOverTimeRequest();
        return $modelOverTimeRequest->getInfoActivityOfCompanyStaffWork($this->checkIdNull($workId));
    }

    # ---------- ---------- tra do nghe ---------- ----------
    public function toolReturn()
    {
        return $this->hasMany('App\Models\Ad3d\ToolReturn\QcToolReturn', 'work_id ', 'work_id');
    }

    # thong tin bao tra cua 1 NV
    public function totalToolReturn($toolId, $workId)
    {
        $modelToolReturnDetail = new QcToolPackageAllocationDetail();
        return $modelToolReturnDetail->totalToolOfWork($toolId, $workId);
    }

    #----------- tay nghe  ------------
    public function workSkill()
    {
        return $this->hasMany('App\Models\Ad3d\WorkSkill\QcWorkSkill', 'work_id', 'companyStaffWork_id');
    }

    # ---------- ---------- giao do nghe ---------- ----------
    public function toolPackageAllocation()
    {
        return $this->hasMany('App\Models\Ad3d\ToolPackageAllocation\QcToolPackageAllocation', 'work_id ', 'work_id');
    }

    #thong tin nhan do nghe
    public function toolAllocationOfWork($workId = null)
    {
        $modelToolAllocation = new QcToolPackageAllocation();
        return $modelToolAllocation->infoOfWork($this->checkIdNull($workId));
    }

    public function toolAllocationListIdOfWork($workId = null)
    {
        $modelToolAllocation = new QcToolPackageAllocation();
        return $modelToolAllocation->listIdOfWork($this->checkIdNull($workId));
    }

    # bo do nghe dang giao
    public function toolAllocationActivityOfWork($workId = null)
    {
        $modelToolAllocation = new QcToolPackageAllocation();
        return $modelToolAllocation->infoActivityOfWork($this->checkIdNull($workId));
    }
    //---------- ----------- cong cu ----------- -----------
    # thong nhan dung cu tai tat ca cty
    public function totalToolReceive($toolId, $workId)
    {
        $modelToolAllocationDetail = new QcToolPackageAllocationDetail();
        return $modelToolAllocationDetail->totalToolOfWork($toolId, $workId);
    }

    # tai 1 cty
    /*public function totalToolReceiveOfCompany($toolId, $staffId, $companyId)
    {
        $modelToolAllocationDetail = new QcToolPackageAllocationDetail();
        return $modelToolAllocationDetail->totalToolOfStaffAndCompany($staffId, $companyId, $toolId);
    }*/

    //------------- -------- kiem tra do nghe cty ----------- -------
    //---------- giao kiem tra do nghe dung chung -----------
    public function companyStoreCheck()
    {
        return $this->hasMany('App\Models\Ad3d\CompanyStoreCheck\QcCompanyStoreCheck', 'work_id', 'work_id');
    }

    # kiem tra ton tai chua xac nhan trong vong chon
    public function existUnConfirmInRoundCompanyStoreCheck($staffWorkId = null)
    {
        $modelCompanyStoreCheck = new QcCompanyStoreCheck();
        return $modelCompanyStoreCheck->checkExistUnConfirmInRoundOfWork($this->checkIdNull($staffWorkId));
    }

    # ban giao kiem tra thong tin do nghe trong hien tai
    public function checkCompanyStoreOfCurrentDate()
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompanyStoreCheck = new QcCompanyStoreCheck();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        if ($hFunction->checkCount($dataStaffLogin)) {
            $dataCompanyStaffWorkLogin = $modelStaff->loginCompanyStaffWork();
            $companyLoginId = $dataCompanyStaffWorkLogin->companyId();
            # chua duoc phan cong
            $checkHourDefault = date('Y-m-d H:i', strtotime(date('Y-m-d 08:10')));
            $checkHourCurrent = date('Y-m-d H:i');
            # phan cong kiem tra do nghe duoc duyet sau gio cham cong - (chi phan cho nguoi di lam)
            if ($checkHourDefault < $checkHourCurrent) {
                $checkDate = date('Y-m-d');
                # kiem tra ngay hien tai duoc phan cong kiem tra hay chua cua 1  cong cty
                if (!$modelCompanyStoreCheck->checkExistDateOfCompany($companyLoginId, $checkDate)) {
                    # lay danh sach lam viec cua bo phan thi cong cap nhan vien
                    $dataStaffWorkConstruction = $this->infoActivityConstructionStaffRankOfCompany($companyLoginId);
                    if ($hFunction->checkCount($dataStaffWorkConstruction)) {
                        $selectedStaffWorkId = null;
                        $workStatus = false; // trang thai nv thi cong co di lam - xet tranh refesh vong lap vo tan
                        foreach ($dataStaffWorkConstruction as $staffWorkConstruction) {
                            $workId = $staffWorkConstruction->workId();
                            # co bao cham cong
                            if ($this->checkTimekeepingProvisionalOfCurrentDate($workId)) {
                                # chưa duoc phan cong trong vong kiem tra
                                if (!$modelCompanyStoreCheck->checkExistWorkReceived($workId)) {
                                    $selectedStaffWorkId = $workId;
                                    break;
                                }
                                $workStatus = true;
                            }

                        }
                        # co nhan vien dc chon
                        if (!$hFunction->checkEmpty($selectedStaffWorkId)) {
                            # them vao phan cong kiem tra do nghe
                            $modelCompanyStoreCheck->insert($selectedStaffWorkId);
                        } else {
                            # van co nv thi cong di lam viec - tao lai vong moi
                            if ($workStatus) {
                                # lam moi lại vong kiem tra
                                $modelCompanyStoreCheck->refreshCheckAround();
                                # phan cong lai
                                $this->checkCompanyStoreOfCurrentDate();
                            } else {
                                # khong ai cham cong - giu nguyen
                            }
                        }
                    }
                }
            } else {
                # kiem tra ton tai phan cong chua xac nhan kiem tra
                $dataCompanyStoreCheck = $modelCompanyStoreCheck->lastInfoUnConfirmOfCompany($companyLoginId);
                if ($hFunction->checkCount($dataCompanyStoreCheck)) {
                    # cap nhat tu dong
                    $modelCompanyStoreCheck->autoConfirm($dataCompanyStoreCheck->checkId());
                }
            }
        }

    }

    # ----------- thong tin lam viec trong thang--------------
    public function work()
    {
        return $this->hasMany('App\Models\Ad3d\Work\QcWork', 'work_id', 'companyStaffWork_id');
    }

    public function checkExistsActivityWork($workId = null)
    {
        $modelWork = new QcWork();
        return $modelWork->checkCompanyStaffWorkActivity($this->checkIdNull($workId));
    }

    # bang cham cong sau cung
    public function workLastInfo($staffWorkId = null)
    {
        $modelWork = new QcWork();
        return $modelWork->lastInfoOfCompanyStaffWork($this->checkIdNull($staffWorkId));
    }

    # bang cham cong dang hoat dong
    public function workInfoActivity($staffWorkId = null)
    {
        $modelWork = new QcWork();
        return $modelWork->infoActivityOfCompanyStaffWork($this->checkIdNull($staffWorkId));
    }

    # kiem tra nv co cham cong hay khong
    public function checkTimekeepingProvisionalOfCurrentDate($staffWorkId = null)
    {
        $hFunction = new \Hfunction();
        $modelWork = new QcWork();
        $dataWork = $modelWork->infoActivityOfCompanyStaffWork($this->checkIdNull($staffWorkId));
        if ($hFunction->checkCount($dataWork)) { // con mo cham cong
            $dataTimekeeping = $dataWork->timekeepingProvisionalOfDate($dataWork->workId(), date('Y-m-d'));
            return $hFunction->checkCount($dataTimekeeping);
        } else {
            return false;
        }
    }

    # danh sach ma bang cham cong
    public function workGetListId($companyStaffWorkId = null)
    {
        $modelWork = new QcWork();
        return $modelWork->listIdOfListCompanyStaffWork([$this->checkIdNull($companyStaffWorkId)]);
    }

    # lay thong tin cham cong cua trong ngay cua 1 NV
    public function infoTimekeepingProvisionalInDate($date, $companyStaffWorkId = null)
    {
        $hFunction = new \Hfunction();
        $modeWork = new QcWork();
        $date = date('Y-m-d', strtotime($date));
        $companyStaffWorkId = $this->checkIdNull($companyStaffWorkId);
        # bang cham cong theo ngay thang cua bang lam viec
        $dataWork = $modeWork->infoOfCompanyStaffWorkInDate($companyStaffWorkId, $date);
        # thong tin cham cong
        return ($hFunction->checkCount($dataWork)) ? $dataWork->timekeepingProvisionalOfDate($dataWork->workId(), $date) : null;


    }

    # ----------- nghi viec tai 1 cty --------------
    public function companyStaffWorkEnd()
    {
        return $this->hasOne('App\Models\Ad3d\CompanyStaffWorkEnd\QcCompanyStaffWorkEnd', 'work_id', 'work_id');
    }

    # ----------- thong tin bo phan lam viec --------------
    public function staffWorkDepartment()
    {
        return $this->hasMany('App\Models\Ad3d\StaffWorkDepartment\QcStaffWorkDepartment', 'work_id', 'work_id');
    }

    public function checkExistActivityWorkDepartmentAndRank($departmentId, $rankId, $workId = null)
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        return $modelStaffWorkDepartment->checkExistWorkActivityOfDepartmentAndRank($this->checkIdNull($workId), $departmentId, $rankId);
    }

    # danh sach ma bo phan cua NV theo bang cham cong
    public function listIdDepartmentOfWork($workId = null)
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        return $modelStaffWorkDepartment->listIdDepartmentOfWork($this->checkIdNull($workId));
    }

    # danh sach ma bo phan cua NV theo bang cham cong dang hoat dong
    public function listIdActivityDepartmentOfWork($workId = null)
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        return $modelStaffWorkDepartment->listIdDepartmentActivityOfWork($this->checkIdNull($workId));
    }

    # lay thong tin bp phan lam viec dang hoat dong
    public function staffWorkDepartmentInfoActivity($workId = null)
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        return $modelStaffWorkDepartment->infoActivityOfWork($this->checkIdNull($workId));
    }


    public function listStaffIdHasFilter($companyId, $departmentId = null, $level = 100) #level = 1000 ->mac dinh chon tat ca level
    {
        $hFunction = new \Hfunction();
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        #mac dinh chon het cap bac
        $getAllLevel = $this->getDefaultAllLevel();
        if (!$hFunction->checkEmpty($departmentId) && $level < $getAllLevel) { # theo bo phan va cap bac
            $listWorkId = $modelStaffWorkDepartment->workIdOfDepartment($departmentId);
            return QcCompanyStaffWork::where('company_id', $companyId)->whereIn('work_id', $listWorkId)->where('level', $level)->pluck('staff_id');
        } elseif (!$hFunction->checkEmpty($departmentId) && $level == $getAllLevel) { # theo bo phan va ko can cap bac
            $listWorkId = $modelStaffWorkDepartment->workIdOfDepartment($departmentId);
            return QcCompanyStaffWork::where('company_id', $companyId)->whereIn('work_id', $listWorkId)->pluck('staff_id');
        } elseif ($hFunction->checkEmpty($departmentId) && $level < $getAllLevel) { # theo cap bat, khong phan biet bo phan
            return QcCompanyStaffWork::where('company_id', $companyId)->where('level', $level)->pluck('staff_id');
        } else {
            return QcCompanyStaffWork::where('company_id', $companyId)->pluck('staff_id');
        }

    }

    # lay danh sach ma nv theo danh sach ma cong ty
    public function listStaffIdOfListCompanyId($listCompanyId)
    {
        return QcCompanyStaffWork::whereIn('company_id', $listCompanyId)->pluck('staff_id');
    }

    # lay danh sach ma nv theo ma cong ty va danh sach ma bo phan va cap bac lam viec - dang hoat dong
    public function listStaffIdActivityOfCompanyIdAndListDepartmentId($companyId, $listDepartmentId, $rankId = null)
    {
        $hFunction = new \Hfunction();
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $listWorkId = $modelStaffWorkDepartment->listWorkIdActivityOfListDepartment($listDepartmentId, $rankId);
        if ($hFunction->checkCount($listWorkId)) {
            return QcCompanyStaffWork::where('company_id', $companyId)->whereIn('work_id', $listWorkId)->where('action', $this->getDefaultHasAction())->pluck('staff_id');
        } else {
            return $hFunction->getDefaultNull();
        }
    }

    # lay danh sach ma nv theo ma cong ty va danh sach ma bo phan va cap bac lam viec - tat ca
    public function listStaffIdOfCompanyIdAndListDepartmentId($companyId, $listDepartmentId, $rankId = null)
    {
        $hFunction = new \Hfunction();
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $listWorkId = $modelStaffWorkDepartment->listWorkIdActivityOfListDepartment($listDepartmentId, $rankId);
        if ($hFunction->checkCount($listWorkId)) {
            return QcCompanyStaffWork::where('company_id', $companyId)->whereIn('work_id', $listWorkId)->pluck('staff_id');
        } else {
            return $hFunction->getDefaultNull();
        }
    }

    # lay danh sach ma nv dang hoat dong theo ma cong ty va ma bo phan va cap bac lam viec
    public function listStaffIdActivityHasFilter($companyId, $departmentId = null, $level = 100)
    {
        $hFunction = new \Hfunction();
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        #mac dinh chon het cap bac
        $getAllLevel = $this->getDefaultAllLevel();
        # theo bo phan va cap bac
        if (!$hFunction->checkEmpty($departmentId) && $level < $getAllLevel) {
            $listWorkId = $modelStaffWorkDepartment->workIdActivityOfDepartment($departmentId);
            return QcCompanyStaffWork::where('company_id', $companyId)->whereIn('work_id', $listWorkId)->where('level', $level)->where('action', $this->getDefaultHasAction())->pluck('staff_id');
        } elseif (!$hFunction->checkEmpty($departmentId) && $level == $getAllLevel) { # theo bo phan va ko can cap bac
            $listWorkId = $modelStaffWorkDepartment->workIdActivityOfDepartment($departmentId);
            return QcCompanyStaffWork::where('company_id', $companyId)->whereIn('work_id', $listWorkId)->where('action', $this->getDefaultHasAction())->pluck('staff_id');
        } elseif ($hFunction->checkEmpty($departmentId) && $level < $getAllLevel) { # theo cap bat, khong phan biet bo phan
            return QcCompanyStaffWork::where('company_id', $companyId)->where('level', $level)->where('action', $this->getDefaultHasAction())->pluck('staff_id');
        } else {
            return QcCompanyStaffWork::where('company_id', $companyId)->where('action', $this->getDefaultHasAction())->pluck('staff_id');
        }
    }

    # lay danh sach ma nv bo phan quan ly theo danh sach ma cong ty
    public function listStaffIdManageOfListCompany($listCompanyId)
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $listWorkId = $modelStaffWorkDepartment->listManageWorkId();
        return QcCompanyStaffWork::whereIn('company_id', $listCompanyId)->whereIn('work_id', $listWorkId)->pluck('staff_id');
    }

    //---------- nhan vien -----------
    public function staff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'staff_id', 'staff_id');
    }

    #lay thong tin lam viec sau cung cua 1 nhan vie
    public function getLastInfoOfStaff($staffId)
    {
        return QcCompanyStaffWork::where('staff_id', $staffId)->orderBy('work_id', 'DESC')->first();
    }

    # lay danh sach ma bo phan dang lam viec cua 1 nv
    public function listIdDepartmentActivityOfStaff($staffId)
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $dataWork = $this->infoActivityOfStaff($staffId);
        return $modelStaffWorkDepartment->listIdDepartmentActivityOfWork($dataWork->workId());
    }

    # thong tin bo phan dang lam viec
    public function infoDepartmentActivityOfStaff($companyStaffWorkId = null)
    {
        $modelDepartment = new QcDepartment();
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        return $modelDepartment->getInfoByListId($modelStaffWorkDepartment->listIdDepartmentActivityOfWork($this->checkIdNull($companyStaffWorkId)));
    }

    # lay danh sach tat ca ma lam viec cua 1 nv
    public function listIdOfStaff($staffId)
    {
        return QcCompanyStaffWork::where(['staff_id' => $staffId])->pluck('work_id');
    }

    # lay danh sach tat ca ma lam viec cua nhieu nv
    public function listIdOfListStaffId($listStaffId)
    {
        return QcCompanyStaffWork::whereIn('staff_id', $listStaffId)->pluck('work_id');
    }

    # lay ma dang lam viec cua 1 nhan vien
    public function workIdActivityOfStaff($staffId)
    {
        return QcCompanyStaffWork::where('staff_id', $staffId)->where('action', $this->getDefaultHasAction())->pluck('work_id');
    }

    //lay thong tin dang lam viec cua NV
    public function infoActivityOfStaff($staffId)
    {
        return QcCompanyStaffWork::where(['staff_id' => $staffId, 'action' => $this->getDefaultHasAction()])->first();
    }

    # kiem tra da lam viec tai cty - da nghi / dang lam
    public function checkExistStaffOfCompany($staffId, $companyId)
    {
        return QcCompanyStaffWork::where(['staff_id' => $staffId, 'company_id' => $companyId])->exists();
    }

    # kiem tra dang lam viec tai cty - dang lam
    public function checkExistActivityStaffOfCompany($staffId, $companyId)
    {
        return QcCompanyStaffWork::where(['staff_id' => $staffId, 'company_id' => $companyId, 'action' => $this->getDefaultHasAction()])->exists();
    }

    public function levelActivityOfStaff($staffId)
    {
        return $this->infoActivityOfStaff($staffId)->level();
    }

    #lay ma cty dang lam viec cua 1 NV
    public function companyIdActivityOfStaff($staffId)
    {
        return $this->infoActivityOfStaff($staffId)->companyId();
    }

    #lay danh sach ma nv dang dang lam viec  theo danh sach cong ty va thuoc 1 bo phan
    public function listStaffIdActivityOfCompanyAndDepartment($listCompanyId, $departmentId)
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $listWorkId = $modelStaffWorkDepartment->workIdActivityOfDepartment($departmentId);
        return QcCompanyStaffWork::whereIn('company_id', $listCompanyId)->WhereIn('work_id', $listWorkId)->where('action', $this->getDefaultHasAction())->pluck('staff_id');
    }

    //---------- nhan vien them nha su vao cty -----------
    public function staffAdd()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaffs', 'staffAdd_id', 'staff_id');
    }

    # ----------- thong tin luong tai cong ty --------------
    public function staffWorkSalary()
    {
        return $this->hasMany('App\Models\Ad3d\StaffWorkSalary\QcStaffWorkSalary', 'work_id', 'work_id');
    }

    # kiem tra da ton tai bang luong co ban dang hoat dong
    public function checkExistsActivityStaffWorkSalary($workId = null)
    {
        $modelStaffWorkSalary = new QcStaffWorkSalary();
        return $modelStaffWorkSalary->checkExistsActivityOfWork($this->checkIdNull($workId));
    }

    # lay thong tin bang luong co ban dang hoat dong theo ma lam viec
    public function staffWorkSalaryActivity($workId = null)
    {
        $modelStaffWorkSalary = new QcStaffWorkSalary();
        return $modelStaffWorkSalary->infoActivityOfWork($this->checkIdNull($workId));
    }

    # lay thong tin bang luong co ban dang hoat dong theo ma nv
    public function staffWorkSalaryActivityOfStaff($staffId)
    {
        $hFunction = new \Hfunction();
        $dataInfoActivityOfStaff = $this->infoActivityOfStaff($staffId);
        if ($hFunction->checkCount($dataInfoActivityOfStaff)) {
            return $this->staffWorkSalaryActivity($dataInfoActivityOfStaff->workId());
        } else {
            return $hFunction->getDefaultNull();
        }
    }

    #======== ======== ========== KIEM TRA BO PHAN CUA NV ======== ======== =========
    # kiem tra hien tai NV co lam bon phan ke toan hay khong
    public function checkCurrentDepartmentAccountantOfStaff($staffId)
    {
        $hFunction = new \Hfunction();
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if ($hFunction->checkCount($result)) {
            if ($modelStaffWorkDepartment->checkCurrentDepartmentAccountantOfWork($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    #-------------- ------------ kiem tra bo phan QUAN LY ------------- --------------
    # kiem tra hien tai NV co lam bon phan quan ly hay khong
    public function checkCurrentDepartmentManageOfStaff($staffId)
    {
        $hFunction = new \Hfunction();
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if ($hFunction->checkCount($result)) {
            if ($modelStaffWorkDepartment->checkCurrentDepartmentManageOfWork($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    //kiem tra nv thuoc bp quan ly
    public function checkManageDepartment($staffId)
    {
        $hFunction = new \Hfunction();
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if ($hFunction->checkCount($result)) {
            if ($modelStaffWorkDepartment->checkManageDepartment($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    // kiem tra nv theo bo phan quan ly cap quan ly
    public function checkManageDepartmentAndManageRank($staffId)
    {
        $hFunction = new \Hfunction();
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if ($hFunction->checkCount($result)) {
            if ($modelStaffWorkDepartment->checkManageDepartmentAndManageRank($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    // kiem tra nv theo bo phan quan ly cap thong thuong
    public function checkManageDepartmentAndNormalRank($staffId)
    {
        $hFunction = new \Hfunction();
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if ($hFunction->checkCount($result)) {
            if ($modelStaffWorkDepartment->checkManageDepartmentAndNormalRank($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    #-------------- ------------kiem tra bo phan THI CONG------------- --------------
    # kiem tra hien tai NV co lam bon phan thi cong hay khong
    public function checkCurrentDepartmentConstructionOfStaff($staffId)
    {
        $hFunction = new \Hfunction();
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if ($hFunction->checkCount($result)) {
            if ($modelStaffWorkDepartment->checkCurrentDepartmentConstructionOfWork($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    // kiem tra nv co thuoc bo phan thi cong cap quan ly hay khong
    public function checkConstructionDepartmentAndManageRank($staffId)
    {
        $hFunction = new \Hfunction();
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if ($hFunction->checkCount($result)) {
            if ($modelStaffWorkDepartment->checkConstructionDepartmentAndManageRank($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    #-------------- ------------kiem tra bo phan THIET KE------------- --------------
    # kiem tra hien tai NV co lam bon phan thiet ke hay khong
    public function checkCurrentDepartmentDesignOfStaff($staffId)
    {
        $hFunction = new \Hfunction();
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if ($hFunction->checkCount($result)) {
            if ($modelStaffWorkDepartment->checkCurrentDepartmentDesignOfWork($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    //kiem tra nv thuoc bo phan thiet ke
    public function checkDesignDepartment($staffId)
    {
        $hFunction = new \Hfunction();
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if ($hFunction->checkCount($result)) {
            if ($modelStaffWorkDepartment->checkDesignDepartment($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    //kiem tra nv thuoc bo phan thiet ke cap quan ly hay khong
    public function checkDesignDepartmentAndManageRank($staffId)
    {
        $hFunction = new \Hfunction();
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if ($hFunction->checkCount($result)) {
            if ($modelStaffWorkDepartment->checkDesignDepartmentAndManageRank($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    //kiem tra nv thuoc bo phan thiet ke cap thong thuong
    public function checkDesignDepartmentAndNormalRank($staffId)
    {
        $hFunction = new \Hfunction();
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if ($hFunction->checkCount($result)) {
            if ($modelStaffWorkDepartment->checkDesignDepartmentAndNormalRank($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    #-------------- ------------kiem tra bo phan KINH DOANH------------- --------------
    # kiem tra hien tai NV co lam bon phan kinh doanh hay khong
    public function checkCurrentDepartmentBusinessOfStaff($staffId)
    {
        $hFunction = new \Hfunction();
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if ($hFunction->checkCount($result)) {
            if ($modelStaffWorkDepartment->checkCurrentDepartmentBusinessOfWork($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    //kiem tra nv thuoc bp kinh doanh
    public function checkBusinessDepartment($staffId)
    {
        $hFunction = new \Hfunction();
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if ($hFunction->checkCount($result)) {
            if ($modelStaffWorkDepartment->checkBusinessDepartment($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    //kiem tra nv thuoc bo phan kinh doanh cap quan ly hay khong
    public function checkBusinessDepartmentAndManageRank($staffId)
    {
        $hFunction = new \Hfunction();
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if ($hFunction->checkCount($result)) {
            if ($modelStaffWorkDepartment->checkBusinessDepartmentAndManageRank($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    //kiem tra nv thuoc bo phan kinh doanh cap thong thuong
    public function checkBusinessDepartmentAndNormalRank($staffId)
    {
        $hFunction = new \Hfunction();
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if ($hFunction->checkCount($result)) {
            if ($modelStaffWorkDepartment->checkBusinessDepartmentAndNormalRank($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    #-------------- ------------kiem tra bo phan NHAN SU------------- --------------
    # kiem tra hien tai NV co lam bon phan nhan su hay khong
    public function checkCurrentDepartmentPersonnelOfStaff($staffId)
    {
        $hFunction = new \Hfunction();
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if ($hFunction->checkCount($result)) {
            if ($modelStaffWorkDepartment->checkCurrentDepartmentPersonnelOfWork($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    // kiem tra nv theo cap bac nhan su
    public function checkPersonnelDepartment($staffId)
    {
        $hFunction = new \Hfunction();
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if ($hFunction->checkCount($result)) {
            if ($modelStaffWorkDepartment->checkPersonnelDepartment($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    //kiem tran nv thuoc bo phan nhan su cap quan ly
    public function checkPersonnelDepartmentAndManageRank($staffId)
    {
        $hFunction = new \Hfunction();
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if ($hFunction->checkCount($result)) {
            if ($modelStaffWorkDepartment->checkPersonnelDepartmentAndManageRank($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    //kiem tran nv thuoc bo phan nhan su cap thong thuong
    public function checkPersonnelDepartmentAndNormalRank($staffId)
    {
        $hFunction = new \Hfunction();
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if ($hFunction->checkCount($result)) {
            if ($modelStaffWorkDepartment->checkPersonnelDepartmentAndNormalRank($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    #-------------- ------------ kiem tra bo phan THU QUY ------------- --------------
    # kiem tra hien tai NV co lam bon phan thu quy hay khong
    public function checkCurrentDepartmentTreasureOfStaff($staffId)
    {
        $hFunction = new \Hfunction();
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if ($hFunction->checkCount($result)) {
            if ($modelStaffWorkDepartment->checkCurrentDepartmentTreasureOfWork($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    //kiem tra nv thuoc bp thu quy
    public function checkTreasureDepartment($staffId)
    {
        $hFunction = new \Hfunction();
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if ($hFunction->checkCount($result)) {
            if ($modelStaffWorkDepartment->checkCurrentDepartmentTreasureOfWork($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    //kiem tra nv thuoc bo phan thu quy cap quan ly hay khong
    public function checkTreasureDepartmentAndManageRank($staffId)
    {
        $hFunction = new \Hfunction();
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if ($hFunction->checkCount($result)) {
            if ($modelStaffWorkDepartment->checkTreasureDepartmentAndManageRank($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    //kiem tra nv thuoc bo phan thu quy cap thong thuong
    public function checkTreasureDepartmentAndNormalRank($staffId)
    {
        $hFunction = new \Hfunction();
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if ($hFunction->checkCount($result)) {
            if ($modelStaffWorkDepartment->checkTreasureDepartmentAndNormalRank($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    //---------- cong ty -----------
    public function company()
    {
        return $this->belongsTo('App\Models\Ad3d\Company\QcCompany', 'company_id', 'company_id');
    }

    # lay danh sach thong tin chua ban giao tui do nghe
    public function getInfoForToolPackageAllocationOfCompany($companyId)
    {
        # chi lay thong tin do nghe chua duoc ban giao
        $modelDepartment = new QcDepartment();
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $modelToolPackageAllocation = new QcToolPackageAllocation();
        # danh sach lam viec cua bo phan thi cong
        $listWorkIdOfDepartment = $modelStaffWorkDepartment->listWorkIdActivityOfListDepartment([$modelDepartment->constructionDepartmentId()], null);
        # danh sach lam viec da phat do nghe
        $listWorkId = $modelToolPackageAllocation->listWorkIdIsActive();
        return QcCompanyStaffWork::where('company_id', $companyId)->whereIn('work_id', $listWorkIdOfDepartment)->whereNotIn('work_id', $listWorkId)->where('action', $this->getDefaultHasAction())->get();
    }

    # lay thong tin lam viec theo bo phan thi cong cap nhan vien tai 1 cty - dang lam viec
    public function infoActivityConstructionStaffRankOfCompany($companyId)
    {
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $listWorkId = $modelStaffWorkDepartment->listWorkIdActivityOfListDepartment([$modelDepartment->constructionDepartmentId()], $modelRank->staffRankId());
        return QcCompanyStaffWork::where('company_id', $companyId)->whereIn('work_id', $listWorkId)->where('action', $this->getDefaultHasAction())->get();
    }

    # lay tat ca thong tin lam viec theo bo phan thi cong tai 1 cty - dang lam viec
    public function infoAllActivityConstructionOfCompany($companyId)
    {
        $modelDepartment = new QcDepartment();
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $listWorkId = $modelStaffWorkDepartment->listWorkIdActivityOfListDepartment([$modelDepartment->constructionDepartmentId()], null);
        return QcCompanyStaffWork::where('company_id', $companyId)->whereIn('work_id', $listWorkId)->where('action', $this->getDefaultHasAction())->get();
    }

    # lay danh sach tat ca ma lam viec tai 1 cty
    public function listIdOfCompany($companyId)
    {
        return QcCompanyStaffWork::where('company_id', $companyId)->pluck('work_id');
    }

    # lay danh sach ma thong tin lam theo danh sach cong ty va danh sach NV - tat ca
    public function listIdOfListCompanyAndListStaff($listCompanyId, $listStaffId = null)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($listStaffId)) {
            return QcCompanyStaffWork::whereIn('company_id', $listCompanyId)->pluck('work_id');
        } else {
            return QcCompanyStaffWork::whereIn('company_id', $listCompanyId)->whereIn('staff_id', $listStaffId)->pluck('work_id');
        }

    }

    # lay danh sach ma thong tin lam theo danh sach cong ty va danh sach NV - dang hoat dong
    public function listIdActivityOfListCompanyAndListStaff($listCompanyId, $listStaffId = null)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($listStaffId)) {
            return QcCompanyStaffWork::whereIn('company_id', $listCompanyId)->where('action', $this->getDefaultHasAction())->pluck('work_id');
        } else {
            return QcCompanyStaffWork::whereIn('company_id', $listCompanyId)->whereIn('staff_id', $listStaffId)->where('action', $this->getDefaultHasAction())->pluck('work_id');
        }

    }

    # lay danh sach ma nv theo 1 cty
    public function staffIdOfCompany($companyId)
    {
        return QcCompanyStaffWork::where('company_id', $companyId)->pluck('staff_id');
    }

    # lay danh sach thong tin theo 1 cty va trang thai lam viẹc
    public function selectInfoOfCompanyAndActionStatus($companyId, $actionStatus = 100) // mac dinh chon tat ca = 100
    {
        if ($actionStatus == $this->getDefaultAllAction()) {
            return QcCompanyStaffWork::where('company_id', $companyId)->select('*');
        } else {
            return QcCompanyStaffWork::where('company_id', $companyId)->where('action', $actionStatus)->select('*');
        }
    }

    # lay danh sach ma nv theo 1 cty va trang thai lam viẹc
    public function staffIdOfCompanyAndActionStatus($companyId, $actionStatus = 100) // mac dinh chon tat ca = 100
    {
        if ($actionStatus == $this->getDefaultAllAction()) {
            return QcCompanyStaffWork::where('company_id', $companyId)->pluck('staff_id');
        } else {
            return QcCompanyStaffWork::where('company_id', $companyId)->where('action', $actionStatus)->pluck('staff_id');
        }
    }

    # lay danh sach ma nv đang hoat dong theo 1 cty
    public function staffIdActivityOfCompany($companyId = null)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($companyId)) {
            return QcCompanyStaffWork::where('action', $this->getDefaultHasAction())->pluck('staff_id');
        } else {
            return QcCompanyStaffWork::where('company_id', $companyId)->where('action', $this->getDefaultHasAction())->pluck('staff_id');
        }
    }

    # lay danh sach ma nv theo danh sach ma cty
    public function staffIdOfListCompany($listCompanyId = null)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($listCompanyId)) {
            return QcCompanyStaffWork::pluck('staff_id');
        } else {
            return QcCompanyStaffWork::whereIn('company_id', $listCompanyId)->pluck('staff_id');
        }
    }

    # lay danh sach ma nv đang hoat dong theo danh sach ma cty
    public function staffIdActivityOfListCompany($listCompanyId = null)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($listCompanyId)) {
            return QcCompanyStaffWork::where('action', $this->getDefaultHasAction())->pluck('staff_id');
        } else {
            return QcCompanyStaffWork::whereIn('company_id', $listCompanyId)->where('action', $this->getDefaultHasAction())->pluck('staff_id');
        }
    }

    // lay danh sach ma nv cua bo phan quan ly cua 1 cty
    public function listStaffIdManage($companyId, $level = 100)
    {
        $modelDepartment = new QcDepartment();
        return $this->listStaffIdActivityHasFilter($companyId, $modelDepartment->manageDepartmentId(), $level);
    }

    //lay danh sach ma nv cua bo phan thu quy cua 1 cty
    public function listStaffIdTreasure($companyId, $level = 100)
    {
        $modelDepartment = new QcDepartment();
        return $this->listStaffIdActivityHasFilter($companyId, $modelDepartment->treasurerDepartmentId(), $level);
    }

    // lay danh sach ma nv cua bo phan thi thi cong
    public function listConstructionStaffId()
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        return QcCompanyStaffWork::whereIn('work_id', $modelStaffWorkDepartment->listConstructionWorkId())->pluck('staff_id');
    }

    //lay danh sach ma nv cua bo phan thi thi cong cua 1 cty
    public function listStaffIdConstruction($companyId, $level = 100)
    {
        $modelDepartment = new QcDepartment();
        return $this->listStaffIdActivityHasFilter($companyId, $modelDepartment->constructionDepartmentId(), $level);
    }

    // lay danh sach ma nv cua bo phan thiet ke
    public function listDesignStaffId()
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        return QcCompanyStaffWork::whereIn('work_id', $modelStaffWorkDepartment->listDesignWorkId())->pluck('staff_id');
    }

    // lay danh sach ma nv cua bo phan ke toan
    public function listAccountantStaffId()
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        return QcCompanyStaffWork::whereIn('work_id', $modelStaffWorkDepartment->listAccountantWorkId())->pluck('staff_id');
    }

    //lay danh sach ma nv cua bo phan nhan su
    public function listPersonnelStaffId()
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        return QcCompanyStaffWork::whereIn('work_id', $modelStaffWorkDepartment->listPersonnelWorkId())->pluck('staff_id');
    }

    #============ =========== ============  lay thong tin chi tiet ============= =========== ==========
    public function listStaffIdActivityByLevel($level)
    {
        return QcCompanyStaffWork::where('level', $level)->pluck('staff_id');
    }

    public function getInfo($workId = '', $field = '')
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($workId)) {
            return QcCompanyStaffWork::get();
        } else {
            $result = QcCompanyStaffWork::where('work_id', $workId)->first();
            if ($hFunction->checkEmpty($field)) {
                return $result;
            } else {
                return $result->$field;
            }
        }
    }

    public function pluck($column, $objectId = null)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($objectId)) {
            return $this->$column;
        } else {
            return QcCompanyStaffWork::where('work_id', $objectId)->pluck($column)[0];
        }
    }

    public function workId()
    {
        return $this->work_id;
    }

    public function beginDate($workId = null)
    {
        return $this->pluck('beginDate', $workId);
    }

    public function level($workId = null)
    {
        return $this->pluck('level', $workId);
    }

    public function action($workId = null)
    {
        return $this->pluck('action', $workId);
    }

    public function createdAt($workId = null)
    {
        return $this->pluck('created_at', $workId);
    }

    public function staffId($workId = null)
    {
        return $this->pluck('staff_id', $workId);
    }

    public function staffAddId($workId = null)
    {
        return $this->pluck('staffAdd_id', $workId);
    }

    public function companyId($workId = null)
    {
        return $this->pluck('company_id', $workId);
    }
}
