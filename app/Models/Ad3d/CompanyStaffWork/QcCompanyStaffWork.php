<?php

namespace App\Models\Ad3d\CompanyStaffWork;

use App\Models\Ad3d\Department\QcDepartment;
use App\Models\Ad3d\StaffWorkDepartment\QcStaffWorkDepartment;
use App\Models\Ad3d\StaffWorkSalary\QcStaffWorkSalary;
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

    //cap nhat quyen admin
    public function updateLevel($level, $workId = null)
    {
        return QcCompanyStaffWork::where('work_id', $this->checkIdNull($workId))->update(['level' => $level]);
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

    public function listIdDepartmentOfWork($workId = null)
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        return $modelStaffWorkDepartment->listIdDepartmentOfWork($this->checkIdNull($workId));
    }

    public function staffWorkDepartmentInfoActivity($workId = null)
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        return $modelStaffWorkDepartment->infoActivityOfWork($this->checkIdNull($workId));
    }


    public function listStaffIdHasFilter($companyId, $departmentId = null, $level = 1000) #level = 1000 ->mac dinh chon tat ca level
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        if (!empty($departmentId) && $level < 1000) { # theo bo phan va cap bac
            $listWorkId = $modelStaffWorkDepartment->workIdActivityOfDepartment($departmentId);
            return QcCompanyStaffWork::where('company_id', $companyId)->whereIn('work_id', $listWorkId)->where('level', $level)->pluck('staff_id');
        } elseif (!empty($departmentId) && $level == 1000) { # theo bo phan va ko can cap bac
            $listWorkId = $modelStaffWorkDepartment->workIdActivityOfDepartment($departmentId);
            return QcCompanyStaffWork::where('company_id', $companyId)->whereIn('staff_id', $listWorkId)->pluck('staff_id');
        } elseif (empty($departmentId) && $level < 1000) { # theo cap bat, khong phan biet bo phan
            return QcCompanyStaffWork::where('company_id', $companyId)->where('level', $level)->pluck('staff_id');
        } else {
            return QcCompanyStaffWork::where('company_id', $companyId)->pluck('staff_id');
        }

    }

    public function listStaffIdOfListCompanyId($listCompanyId)
    {
        return QcCompanyStaffWork::whereIn('company_id', $listCompanyId)->pluck('staff_id');
    }

    public function listStaffIdActivityHasFilter($companyId, $departmentId = null, $level = 1000) #level = 1000 ->mac dinh chon tat ca level
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        if (!empty($departmentId) && $level < 1000) { # theo bo phan va cap bac
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

    public function listIdDepartmentActivityOfStaff($staffId)
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $dataWork = $this->infoActivityOfStaff($staffId);
        return $modelStaffWorkDepartment->listIdDepartmentActivityOfWork($dataWork->workId());
    }

    public function listIdOfStaff($staffId)
    {
        return QcCompanyStaffWork::where(['staff_id' => $staffId])->pluck('work_id');
    }

    //thong tin dang lam viec cua NV
    public function infoActivityOfStaff($staffId)
    {
        return QcCompanyStaffWork::where(['staff_id' => $staffId, 'action' => 1])->first();
    }

    # da lam viec tai cty - da nghi / dang lam
    public function checkExistStaffOfCompany($staffId, $companyId)
    {
        $result = QcCompanyStaffWork::where(['staff_id' => $staffId, 'company_id' => $companyId])->count();
        return ($result > 0) ? true : false;
    }

    # da lam viec tai cty - dang lam
    public function checkExistActivityStaffOfCompany($staffId, $companyId)
    {
        $result = QcCompanyStaffWork::where(['staff_id' => $staffId, 'company_id' => $companyId, 'action' => 1])->count();
        return ($result > 0) ? true : false;
    }

    public function levelActivityOfStaff($staffId)
    {
        return $this->infoActivityOfStaff($staffId)->level();
    }

    public function companyIdActivityOfStaff($staffId)
    {
        return $this->infoActivityOfStaff($staffId)->companyId();
    }

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

    public function checkExistsActivityStaffWorkSalary($workId = null)
    {
        $modelStaffWorkSalary = new QcStaffWorkSalary();
        return $modelStaffWorkSalary->checkExistsActivityOfWork($this->checkIdNull($workId));
    }

    public function staffWorkSalaryActivity($workId = null)
    {
        $modelStaffWorkSalary = new QcStaffWorkSalary();
        return $modelStaffWorkSalary->infoActivityOfWork($this->checkIdNull($workId));
    }

    # lay thong tin luong theo ma NV
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
    // kiem tra nv theo bo phan quan ly cap quan ly
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
    //kiem tra nv thuoc bo phan thiet ke cap quan ly
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

    //kiem tra nv thuoc bo phan thiet ke cap nv
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
    #kiem tra bo phan kinh doanh
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
    //kiem tra nv thuoc bp kinh doanh cap nv quan ly
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
    //kiem tra nv thuoc bp kinh doanh cap nv
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
    #kiem tra bo phan nhan su
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
    //kiem tran nv thuoc bo phan nhan su cap nv
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
    # kiem tra bo phan thu quy
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
    //kiem tra nv thuoc bp kinh doanh cap nv quan ly
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
    //kiem tra nv thuoc bp kinh doanh cap nv
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

    public function listIdOfListCompanyAndListStaff($listCompanyId, $listStaffId = null)
    {
        if (empty($listStaffId)) {
            return QcCompanyStaffWork::whereIn('company_id', $listCompanyId)->pluck('work_id');
        } else {
            return QcCompanyStaffWork::whereIn('company_id', $listCompanyId)->whereIn('staff_id', $listStaffId)->pluck('work_id');
        }

    }

    # lay danh sach id cua nv theo cty
    public function staffIdOfCompany($companyId)
    {
        return QcCompanyStaffWork::where('company_id', $companyId)->pluck('staff_id');
    }

    public function staffIdActivityOfCompany($companyId = null)
    {
        if (empty($companyId)) {
            return QcCompanyStaffWork::where('action', 1)->pluck('staff_id');
        } else {
            return QcCompanyStaffWork::where('company_id', $companyId)->where('action', 1)->pluck('staff_id');
        }
    }


    public function staffIdOfListCompany($listCompanyId = null)
    {
        if (empty($listCompanyId)) {
            return QcCompanyStaffWork::pluck('staff_id');
        } else {
            return QcCompanyStaffWork::whereIn('company_id', $listCompanyId)->pluck('staff_id');
        }
    }

    public function staffIdActivityOfListCompany($listCompanyId = null)
    {
        if (empty($listCompanyId)) {
            return QcCompanyStaffWork::where('action', 1)->pluck('staff_id');
        } else {
            return QcCompanyStaffWork::whereIn('company_id', $listCompanyId)->where('action', 1)->pluck('staff_id');
        }
    }

    // lay danh sach id cua nv quanly cua cac cty
    public function listStaffIdManage($companyId, $level = 1000)
    {
        $modelDepartment = new QcDepartment();
        return $this->listStaffIdActivityHasFilter($companyId, $modelDepartment->manageDepartmentId(), $level);
    }

    //lay id cac nv cua bo phan thu quy
    public function listStaffIdTreasure($companyId, $level = 1000)
    {
        $modelDepartment = new QcDepartment();
        return $this->listStaffIdActivityHasFilter($companyId, $modelDepartment->treasurerDepartmentId(), $level);
    }

    // lay id cac nv cua bo phan thi thi cong
    public function listConstructionStaffId()
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        return QcCompanyStaffWork::whereIn('work_id', $modelStaffWorkDepartment->listConstructionWorkId())->pluck('staff_id');
    }

    public function listStaffIdConstruction($companyId, $level = 1000)
    {
        $modelDepartment = new QcDepartment();
        return $this->listStaffIdActivityHasFilter($companyId, $modelDepartment->constructionDepartmentId(), $level);
    }

    // lay id cac nv cua bo phan thiet ke
    public function listDesignStaffId()
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        return QcCompanyStaffWork::whereIn('work_id', $modelStaffWorkDepartment->listDesignWorkId())->pluck('staff_id');
    }

    // lay id cac nv cua bo phan ke toan
    public function listAccountantStaffId()
    {
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        return QcCompanyStaffWork::whereIn('work_id', $modelStaffWorkDepartment->listAccountantWorkId())->pluck('staff_id');
    }

    //lay danh sach id cua nv cua bp nhan su
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
