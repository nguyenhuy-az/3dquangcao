<?php

namespace App\Models\Ad3d\CompanyStaffWork;

use App\Models\Ad3d\CompanyStoreCheck\QcCompanyStoreCheck;
use App\Models\Ad3d\Department\QcDepartment;
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
        $modelCompanyStaffWork->action = 1;
        $modelCompanyStaffWork->created_at = $hFunction->createdAt();
        if ($modelCompanyStaffWork->save()) {
            $this->lastId = $modelCompanyStaffWork->work_id;
            return true;
        } else {
            return false;
        }
    }

    // lấy id mới thêm
    public function insertGetId()
    {
        return $this->lastId;
    }

    public function checkIdNull($workId)
    {
        return (empty($workId)) ? $this->workId() : $workId;
    }

    public function updateEndWork($workId = null)
    {
        return QcCompanyStaffWork::where('work_id', $this->checkIdNull($workId))->update(['action' => 0]);
    }

    public function deleteOfStaff($staffId)
    {
        return QcCompanyStaffWork::where('staff_id', $staffId)->where('action', 1)->update(['action' => 0]);
    }

    //cap nhat quyen admin
    public function updateLevel($level, $workId = null)
    {
        return QcCompanyStaffWork::where('work_id', $this->checkIdNull($workId))->update(['level' => $level]);
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
                    if (!empty($selectedStaffWorkId)) {
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


    public function listStaffIdHasFilter($companyId, $departmentId = null, $level = 1000) #level = 1000 ->mac dinh chon tat ca level
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        if (!empty($departmentId) && $level < 1000) { # theo bo phan va cap bac
            $listWorkId = $modelStaffWorkDepartment->workIdOfDepartment($departmentId);
            return QcCompanyStaffWork::where('company_id', $companyId)->whereIn('work_id', $listWorkId)->where('level', $level)->pluck('staff_id');
        } elseif (!empty($departmentId) && $level == 1000) { # theo bo phan va ko can cap bac
            $listWorkId = $modelStaffWorkDepartment->workIdOfDepartment($departmentId);
            return QcCompanyStaffWork::where('company_id', $companyId)->whereIn('work_id', $listWorkId)->pluck('staff_id');
        } elseif (empty($departmentId) && $level < 1000) { # theo cap bat, khong phan biet bo phan
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

    # lay danh sach ma nv theo ma cong ty va danh sach ma bo phan va cap bac lam viec
    public function listStaffIdActivityOfCompanyIdAndListDepartmentId($companyId, $listDepartmentId, $rankId = null)
    {
        $hFunction = new \Hfunction();
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $listWorkId = $modelStaffWorkDepartment->listWorkIdActivityOfListDepartment($listDepartmentId, $rankId);
        if ($hFunction->checkCount($listWorkId)) {
            return QcCompanyStaffWork::where('company_id', $companyId)->whereIn('work_id', $listWorkId)->where('action', 1)->pluck('staff_id');
        } else {
            return null;
        }
    }

    # lay danh sach ma nv dang hoat dong theo ma cong ty va ma bo phan va cap bac lam viec
    public function listStaffIdActivityHasFilter($companyId, $departmentId = null, $level = 1000)
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        #level = 1000 ->mac dinh chon tat ca level / level <=3 cap admin / 3 < level <= 5 cap thong thuong
        # theo bo phan va cap bac
        if (!empty($departmentId) && $level < 1000) {
            $listWorkId = $modelStaffWorkDepartment->workIdActivityOfDepartment($departmentId);
            return QcCompanyStaffWork::where('company_id', $companyId)->whereIn('work_id', $listWorkId)->where('level', $level)->where('action', 1)->pluck('staff_id');
        } elseif (!empty($departmentId) && $level == 1000) { # theo bo phan va ko can cap bac
            $listWorkId = $modelStaffWorkDepartment->workIdActivityOfDepartment($departmentId);
            return QcCompanyStaffWork::where('company_id', $companyId)->whereIn('work_id', $listWorkId)->where('action', 1)->pluck('staff_id');
        } elseif (empty($departmentId) && $level < 1000) { # theo cap bat, khong phan biet bo phan
            return QcCompanyStaffWork::where('company_id', $companyId)->where('level', $level)->where('action', 1)->pluck('staff_id');
        } else {
            return QcCompanyStaffWork::where('company_id', $companyId)->where('action', 1)->pluck('staff_id');
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

    # lay danh sach ma bo phan dang lam viec cua 1 nv
    public function listIdDepartmentActivityOfStaff($staffId)
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $dataWork = $this->infoActivityOfStaff($staffId);
        return $modelStaffWorkDepartment->listIdDepartmentActivityOfWork($dataWork->workId());
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
        return QcCompanyStaffWork::where('staff_id', $staffId)->where('action', 1)->pluck('work_id');
    }

    //lay thong tin dang lam viec cua NV
    public function infoActivityOfStaff($staffId)
    {
        return QcCompanyStaffWork::where(['staff_id' => $staffId, 'action' => 1])->first();
    }

    # kiem tra da lam viec tai cty - da nghi / dang lam
    public function checkExistStaffOfCompany($staffId, $companyId)
    {
        $result = QcCompanyStaffWork::where(['staff_id' => $staffId, 'company_id' => $companyId])->count();
        return ($result > 0) ? true : false;
    }

    # kiem tra dang lam viec tai cty - dang lam
    public function checkExistActivityStaffOfCompany($staffId, $companyId)
    {
        $result = QcCompanyStaffWork::where(['staff_id' => $staffId, 'company_id' => $companyId, 'action' => 1])->count();
        return ($result > 0) ? true : false;
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
        return QcCompanyStaffWork::whereIn('company_id', $listCompanyId)->WhereIn('work_id', $listWorkId)->where('action', 1)->pluck('staff_id');
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
        $dataInfoActivityOfStaff = $this->infoActivityOfStaff($staffId);
        if (count($dataInfoActivityOfStaff) > 0) {
            return $this->staffWorkSalaryActivity($dataInfoActivityOfStaff->workId());
        } else {
            return null;
        }
    }

    #======== ======== ========== KIEM TRA BO PHAN CUA NV ======== ======== =========
    # kiem tra hien tai NV co lam bon phan ke toan hay khong
    public function checkCurrentDepartmentAccountantOfStaff($staffId)
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if (count($result) > 0) {
            if ($modelStaffWorkDepartment->checkCurrentDepartmentAccountantOfWork($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    #-------------- ------------ kiem tra bo phan QUAN LY ------------- --------------
    # kiem tra hien tai NV co lam bon phan quan ly hay khong
    public function checkCurrentDepartmentManageOfStaff($staffId)
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if (count($result) > 0) {
            if ($modelStaffWorkDepartment->checkCurrentDepartmentManageOfWork($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    //kiem tra nv thuoc bp quan ly
    public function checkManageDepartment($staffId)
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if (count($result) > 0) {
            if ($modelStaffWorkDepartment->checkManageDepartment($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    // kiem tra nv theo bo phan quan ly cap quan ly
    public function checkManageDepartmentAndManageRank($staffId)
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if (count($result) > 0) {
            if ($modelStaffWorkDepartment->checkManageDepartmentAndManageRank($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    // kiem tra nv theo bo phan quan ly cap thong thuong
    public function checkManageDepartmentAndNormalRank($staffId)
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if (count($result) > 0) {
            if ($modelStaffWorkDepartment->checkManageDepartmentAndNormalRank($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    #-------------- ------------kiem tra bo phan THI CONG------------- --------------
    # kiem tra hien tai NV co lam bon phan thi cong hay khong
    public function checkCurrentDepartmentConstructionOfStaff($staffId)
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if (count($result) > 0) {
            if ($modelStaffWorkDepartment->checkCurrentDepartmentConstructionOfWork($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    // kiem tra nv co thuoc bo phan thi cong cap quan ly hay khong
    public function checkConstructionDepartmentAndManageRank($staffId)
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if (count($result) > 0) {
            if ($modelStaffWorkDepartment->checkConstructionDepartmentAndManageRank($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    #-------------- ------------kiem tra bo phan THIET KE------------- --------------
    # kiem tra hien tai NV co lam bon phan thiet ke hay khong
    public function checkCurrentDepartmentDesignOfStaff($staffId)
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if (count($result) > 0) {
            if ($modelStaffWorkDepartment->checkCurrentDepartmentDesignOfWork($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    //kiem tra nv thuoc bo phan thiet ke
    public function checkDesignDepartment($staffId)
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if (count($result) > 0) {
            if ($modelStaffWorkDepartment->checkDesignDepartment($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    //kiem tra nv thuoc bo phan thiet ke cap quan ly hay khong
    public function checkDesignDepartmentAndManageRank($staffId)
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if (count($result) > 0) {
            if ($modelStaffWorkDepartment->checkDesignDepartmentAndManageRank($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    //kiem tra nv thuoc bo phan thiet ke cap thong thuong
    public function checkDesignDepartmentAndNormalRank($staffId)
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if (count($result) > 0) {
            if ($modelStaffWorkDepartment->checkDesignDepartmentAndNormalRank($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    #-------------- ------------kiem tra bo phan KINH DOANH------------- --------------
    # kiem tra hien tai NV co lam bon phan kinh doanh hay khong
    public function checkCurrentDepartmentBusinessOfStaff($staffId)
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if (count($result) > 0) {
            if ($modelStaffWorkDepartment->checkCurrentDepartmentBusinessOfWork($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    //kiem tra nv thuoc bp kinh doanh
    public function checkBusinessDepartment($staffId)
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if (count($result) > 0) {
            if ($modelStaffWorkDepartment->checkBusinessDepartment($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    //kiem tra nv thuoc bo phan kinh doanh cap quan ly hay khong
    public function checkBusinessDepartmentAndManageRank($staffId)
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if (count($result) > 0) {
            if ($modelStaffWorkDepartment->checkBusinessDepartmentAndManageRank($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    //kiem tra nv thuoc bo phan kinh doanh cap thong thuong
    public function checkBusinessDepartmentAndNormalRank($staffId)
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if (count($result) > 0) {
            if ($modelStaffWorkDepartment->checkBusinessDepartmentAndNormalRank($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    #-------------- ------------kiem tra bo phan NHAN SU------------- --------------
    # kiem tra hien tai NV co lam bon phan nhan su hay khong
    public function checkCurrentDepartmentPersonnelOfStaff($staffId)
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if (count($result) > 0) {
            if ($modelStaffWorkDepartment->checkCurrentDepartmentPersonnelOfWork($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    // kiem tra nv theo cap bac nhan su
    public function checkPersonnelDepartment($staffId)
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if (count($result) > 0) {
            if ($modelStaffWorkDepartment->checkPersonnelDepartment($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    //kiem tran nv thuoc bo phan nhan su cap quan ly
    public function checkPersonnelDepartmentAndManageRank($staffId)
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if (count($result) > 0) {
            if ($modelStaffWorkDepartment->checkPersonnelDepartmentAndManageRank($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    //kiem tran nv thuoc bo phan nhan su cap thong thuong
    public function checkPersonnelDepartmentAndNormalRank($staffId)
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if (count($result) > 0) {
            if ($modelStaffWorkDepartment->checkPersonnelDepartmentAndNormalRank($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    #-------------- ------------ kiem tra bo phan THU QUY ------------- --------------
    # kiem tra hien tai NV co lam bon phan thu quy hay khong
    public function checkCurrentDepartmentTreasureOfStaff($staffId)
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if (count($result) > 0) {
            if ($modelStaffWorkDepartment->checkCurrentDepartmentTreasureOfWork($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    //kiem tra nv thuoc bp thu quy
    public function checkTreasureDepartment($staffId)
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if (count($result) > 0) {
            if ($modelStaffWorkDepartment->checkCurrentDepartmentTreasureOfWork($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    //kiem tra nv thuoc bo phan thu quy cap quan ly hay khong
    public function checkTreasureDepartmentAndManageRank($staffId)
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if (count($result) > 0) {
            if ($modelStaffWorkDepartment->checkTreasureDepartmentAndManageRank($result->workId())) $resultStatus = true;
        }
        return $resultStatus;
    }

    //kiem tra nv thuoc bo phan thu quy cap thong thuong
    public function checkTreasureDepartmentAndNormalRank($staffId)
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if (count($result) > 0) {
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
        return QcCompanyStaffWork::where('company_id', $companyId)->whereIn('work_id', $listWorkIdOfDepartment)->whereNotIn('work_id',$listWorkId)->where('action', 1)->get();
    }

    # lay thong tin lam viec theo bo phan thi cong cap nhan vien tai 1 cty - dang lam viec
    public function infoActivityConstructionStaffRankOfCompany($companyId)
    {
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $listWorkId = $modelStaffWorkDepartment->listWorkIdActivityOfListDepartment([$modelDepartment->constructionDepartmentId()], $modelRank->staffRankId());
        return QcCompanyStaffWork::where('company_id', $companyId)->whereIn('work_id', $listWorkId)->where('action', 1)->get();
    }

    # lay tat ca thong tin lam viec theo bo phan thi cong tai 1 cty - dang lam viec
    public function infoAllActivityConstructionOfCompany($companyId)
    {
        $modelDepartment = new QcDepartment();
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $listWorkId = $modelStaffWorkDepartment->listWorkIdActivityOfListDepartment([$modelDepartment->constructionDepartmentId()], null);
        return QcCompanyStaffWork::where('company_id', $companyId)->whereIn('work_id', $listWorkId)->where('action', 1)->get();
    }

    # lay danh sach tat ca ma lam viec tai 1 cty
    public function listIdOfCompany($companyId)
    {
        return QcCompanyStaffWork::where('company_id', $companyId)->pluck('work_id');
    }

    # lay danh sach ma thong tin lam theo danh sach cong ty va danh sach NV
    public function listIdOfListCompanyAndListStaff($listCompanyId, $listStaffId = null)
    {
        if (empty($listStaffId)) {
            return QcCompanyStaffWork::whereIn('company_id', $listCompanyId)->pluck('work_id');
        } else {
            return QcCompanyStaffWork::whereIn('company_id', $listCompanyId)->whereIn('staff_id', $listStaffId)->pluck('work_id');
        }

    }

    # lay danh sach ma nv theo 1 cty
    public function staffIdOfCompany($companyId)
    {
        return QcCompanyStaffWork::where('company_id', $companyId)->pluck('staff_id');
    }

    # lay danh sach ma nv đang hoat dong theo 1 cty
    public function staffIdActivityOfCompany($companyId = null)
    {
        if (empty($companyId)) {
            return QcCompanyStaffWork::where('action', 1)->pluck('staff_id');
        } else {
            return QcCompanyStaffWork::where('company_id', $companyId)->where('action', 1)->pluck('staff_id');
        }
    }

# lay danh sach ma nv theo danh sach ma cty
    public function staffIdOfListCompany($listCompanyId = null)
    {
        if (empty($listCompanyId)) {
            return QcCompanyStaffWork::pluck('staff_id');
        } else {
            return QcCompanyStaffWork::whereIn('company_id', $listCompanyId)->pluck('staff_id');
        }
    }

    # lay danh sach ma nv đang hoat dong theo danh sach ma cty
    public function staffIdActivityOfListCompany($listCompanyId = null)
    {
        if (empty($listCompanyId)) {
            return QcCompanyStaffWork::where('action', 1)->pluck('staff_id');
        } else {
            return QcCompanyStaffWork::whereIn('company_id', $listCompanyId)->where('action', 1)->pluck('staff_id');
        }
    }

    // lay danh sach ma nv cua bo phan quan ly cua 1 cty
    public function listStaffIdManage($companyId, $level = 1000)
    {
        $modelDepartment = new QcDepartment();
        return $this->listStaffIdActivityHasFilter($companyId, $modelDepartment->manageDepartmentId(), $level);
    }

    //lay danh sach ma nv cua bo phan thu quy cua 1 cty
    public function listStaffIdTreasure($companyId, $level = 1000)
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
    public function listStaffIdConstruction($companyId, $level = 1000)
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
        if (empty($workId)) {
            return QcCompanyStaffWork::get();
        } else {
            $result = QcCompanyStaffWork::where('work_id', $workId)->first();
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
            return QcCompanyStaffWork::where('work_id', $objectId)->pluck($column);
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
