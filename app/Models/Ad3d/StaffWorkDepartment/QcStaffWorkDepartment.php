<?php

namespace App\Models\Ad3d\StaffWorkDepartment;

use App\Models\Ad3d\Department\QcDepartment;
use App\Models\Ad3d\Rank\QcRank;
use Illuminate\Database\Eloquent\Model;

class QcStaffWorkDepartment extends Model
{
    protected $table = 'qc_staff_work_department';
    protected $fillable = ['detail_id', 'beginDate', 'permission', 'action', 'created_at', 'work_id', 'department_id', 'rank_id'];
    protected $primaryKey = 'detail_id';
    public $timestamps = false;

    private $lastId;

    #========== ========== ========== INSERT && UPDATE ========== ========== ==========
    #---------- Insert ----------
    public function insert($workId, $departmentId, $rankId, $beginDate, $permission = 'f')
    {
        $hFunction = new \Hfunction();
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $modelStaffWorkDepartment->beginDate = $beginDate;
        $modelStaffWorkDepartment->permission = $permission;
        $modelStaffWorkDepartment->work_id = $workId;
        $modelStaffWorkDepartment->department_id = $departmentId;
        $modelStaffWorkDepartment->rank_id = $rankId;
        $modelStaffWorkDepartment->action = 1;
        $modelStaffWorkDepartment->created_at = $hFunction->createdAt();
        if ($modelStaffWorkDepartment->save()) {
            $this->lastId = $modelStaffWorkDepartment->detail_id;
            return true;
        } else {
            return false;
        }
    }

    // lay ID moi them
    public function insertGetId()
    {
        return $this->lastId;
    }

    public function disableWorkDepartment($workId, $departmentId)
    {
        return QcStaffWorkDepartment::where('work_id', $workId)->where('department_id', $departmentId)->update(['action' => 0]);
    }

    public function disableWorkAllDepartment($workId)
    {
        return QcStaffWorkDepartment::where('work_id', $workId)->update(['action' => 0]);
    }

    //---------- lam viec -----------
    public function companyStaffWork()
    {
        return $this->belongsTo('App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork', 'work_id', 'work_id');
    }

    //1 NV co the co the lam nhieu bo phan
    public function infoActivityOfWork($workId)
    {
        return QcStaffWorkDepartment::where(['work_id' => $workId, 'action' => 1])->get();
    }

    # kiem tra ton tai dang lam viec tai 1 bo phan
    public function checkExistWorkOfDepartment($workId, $departmentId)
    {
        return QcStaffWorkDepartment::where(['work_id' => $workId, 'department_id' => $departmentId, 'action' => 1])->exists();
    }

    # kiem tra ton tai dang lam viec tai 1 bo phan va vi tri lam
    public function checkExistWorkActivityOfDepartmentAndRank($workId, $departmentId, $rankId)
    {
        return QcStaffWorkDepartment::where(['work_id' => $workId, 'department_id' => $departmentId,'rank_id'=>$rankId, 'action' => 1])->exists();
    }

    public function listIdDepartmentOfWork($workId)
    {
        return QcStaffWorkDepartment::where(['work_id' => $workId])->pluck('department_id');
    }

    public function listIdDepartmentActivityOfWork($workId)
    {
        return QcStaffWorkDepartment::where(['work_id' => $workId, 'action' => 1])->pluck('department_id');
    }

    # lay dang sach tat ca ma lam viẹc theo danh sach ma bo phan va cap bac
    public function listWorkIdOfListDepartment($listDepartmentId, $rankId = null)
    {
        if (empty($rankId)) {
            return QcStaffWorkDepartment::whereIn('department_id', $listDepartmentId)->pluck('work_id');
        } else {
            return QcStaffWorkDepartment::whereIn('department_id', $listDepartmentId)->where('rank_id', $rankId)->pluck('work_id');
        }
    }

    # lay dang sach ma lam viẹc dang hoat dong theo danh sach ma bo phan va cap bac
    public function listWorkIdActivityOfListDepartment($listDepartmentId, $rankId = null)
    {
        if (empty($rankId)) {
            return QcStaffWorkDepartment::whereIn('department_id', $listDepartmentId)->where('action', 1)->pluck('work_id');
        } else {
            return QcStaffWorkDepartment::whereIn('department_id', $listDepartmentId)->where('rank_id', $rankId)->where('action', 1)->pluck('work_id');
        }
    }

    //-------------------------  kiem tra bo phan hien tai cua nv ---------------------------

    public function checkCurrentDepartmentAccountantOfWork($workId)
    {
        $modelDepartment = new QcDepartment();
        $result = $this->infoActivityOfWork($workId);
        $resultStatus = false;
        if (count($result) > 0) {
            foreach ($result as $value) {
                if ($modelDepartment->checkAccountant($value->departmentId())) $resultStatus = true;
            }
        }
        return $resultStatus;
    }

    #======= ======== ======== KIEM TRA BO PHAN - CAP BAC NV ======== ======== ========
    #-------------- ----------- kiem tra bo phan QUAN LY ------------- --------------
    public function checkManageDepartment($workId)
    {
        $hFunction = new \Hfunction();
        $modelDepartment = new QcDepartment();
        $result = $this->infoActivityOfWork($workId);
        $resultStatus = false;
        if ($hFunction->checkCount($result)) {
            foreach ($result as $value) {
                if ($modelDepartment->checkManage($value->departmentId())) $resultStatus = true;
            }
        }
        return $resultStatus;
    }

    public function checkCurrentDepartmentManageOfWork($workId)
    {
        $modelDepartment = new QcDepartment();
        $result = $this->infoActivityOfWork($workId);
        $resultStatus = false;
        if (count($result) > 0) {
            foreach ($result as $value) {
                if ($modelDepartment->checkManage($value->departmentId())) $resultStatus = true;
            }
        }
        return $resultStatus;
    }

    //kiem tra nv thuoc bo phan quan ly cap quan ly
    public function checkManageDepartmentAndManageRank($workId)
    {
        $hFunction = new \Hfunction();
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $result = $this->infoActivityOfWork($workId);
        $resultStatus = false;
        if ($hFunction->checkCount($result)) {
            foreach ($result as $value) {
                if ($modelDepartment->checkManage($value->departmentId())) {
                    if ($modelRank->checkManageRank($value->rankId())) {
                        $resultStatus = true;
                    }
                }
            }
        }
        return $resultStatus;
    }

    //kiem tranv thuoc bo phan quan ly cap nv
    public function checkManageDepartmentAndNormalRank($workId)
    {
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $result = $this->infoActivityOfWork($workId);
        $resultStatus = false;
        if (count($result) > 0) {
            foreach ($result as $value) {
                if ($modelDepartment->checkManage($value->departmentId())) {
                    if ($modelRank->checkNormalRank($value->rankId())) {
                        $resultStatus = true;
                    }
                }
            }
        }
        return $resultStatus;
    }

    #-------------- ----------- kiem tra bo phan THI CONG ------------- --------------
    # kiem tra thong tin lam viec dang hoat dong co thuoc bo phan thi cong cap quan ly hay khong
    public function checkCurrentDepartmentConstructionOfWork($workId)
    {
        $hFunction = new \Hfunction();
        $modelDepartment = new QcDepartment();
        $result = $this->infoActivityOfWork($workId);
        $resultStatus = false;
        if ($hFunction->checkCount($result)) {
            foreach ($result as $value) {
                if ($modelDepartment->checkConstruction($value->departmentId())) $resultStatus = true;
            }
        }
        return $resultStatus;
    }

    # kiem tra thong tin lam viec co thuoc bo phan thi cong cap quan ly hay khong
    public function checkConstructionDepartmentAndManageRank($workId)
    {
        $hFunction = new \Hfunction();
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $result = $this->infoActivityOfWork($workId);
        $resultStatus = false;
        if ($hFunction->checkCount($result)) {
            foreach ($result as $value) {
                if ($modelDepartment->checkConstruction($value->departmentId())) {
                    if ($modelRank->checkManageRank($value->rankId())) {
                        $resultStatus = true;
                    }
                }
            }
        }
        return $resultStatus;
    }

    #-------------- ----------- kiem tra bo phan NHAN SU ------------- --------------
    public function checkCurrentDepartmentPersonnelOfWork($workId)
    {
        $modelDepartment = new QcDepartment();
        $result = $this->infoActivityOfWork($workId);
        $resultStatus = false;
        if (count($result) > 0) {
            foreach ($result as $value) {
                if ($modelDepartment->checkPersonnel($value->departmentId())) $resultStatus = true;
            }
        }
        return $resultStatus;
    }

    public function checkPersonnelDepartment($workId)
    {
        $modelDepartment = new QcDepartment();
        $result = $this->infoActivityOfWork($workId);
        $resultStatus = false;
        if (count($result) > 0) {
            foreach ($result as $value) {
                if ($modelDepartment->checkPersonnel($value->departmentId())) $resultStatus = true;
            }
        }
        return $resultStatus;
    }

    // cap quan ly
    public function checkPersonnelDepartmentAndManageRank($workId)
    {
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $result = $this->infoActivityOfWork($workId);
        $resultStatus = false;
        if (count($result) > 0) {
            foreach ($result as $value) {
                if ($modelDepartment->checkPersonnel($value->departmentId())) {
                    if ($modelRank->checkManageRank($value->rankId())) {
                        $resultStatus = true;
                    }
                }
            }
        }
        return $resultStatus;
    }

    // cap nhan vien
    public function checkPersonnelDepartmentAndNormalRank($workId)
    {
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $result = $this->infoActivityOfWork($workId);
        $resultStatus = false;
        if (count($result) > 0) {
            foreach ($result as $value) {
                if ($modelDepartment->checPersonnel($value->departmentId())) {
                    if ($modelRank->checkNormalRank($value->rankId())) {
                        $resultStatus = true;
                    }
                }
            }
        }
        return $resultStatus;
    }

    #-------------- ----------- kiem tra bo phan THIET KE ------------- --------------
    public function checkCurrentDepartmentDesignOfWork($workId)
    {
        $modelDepartment = new QcDepartment();
        $result = $this->infoActivityOfWork($workId);
        $resultStatus = false;
        if (count($result) > 0) {
            foreach ($result as $value) {
                if ($modelDepartment->checkDesign($value->departmentId())) $resultStatus = true;
            }
        }
        return $resultStatus;
    }

    //kiem tra nv thuoc bo phan thiet ke
    public function checkDesignDepartment($workId)
    {
        $modelDepartment = new QcDepartment();
        $result = $this->infoActivityOfWork($workId);
        $resultStatus = false;
        if (count($result) > 0) {
            foreach ($result as $value) {
                if ($modelDepartment->checkDesign($value->departmentId())) $resultStatus = true;
            }
        }
        return $resultStatus;
    }

    //kiem tra nv thuoc bp thiet ke cap quan ly
    public function checkDesignDepartmentAndManageRank($workId)
    {
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $result = $this->infoActivityOfWork($workId);
        $resultStatus = false;
        if (count($result) > 0) {
            foreach ($result as $value) {
                if ($modelDepartment->checkDesign($value->departmentId())) {
                    if ($modelRank->checkManageRank($value->rankId())) {
                        $resultStatus = true;
                    }
                }
            }
        }
        return $resultStatus;
    }

    public function checkDesignDepartmentAndNormalRank($workId)
    {
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $result = $this->infoActivityOfWork($workId);
        $resultStatus = false;
        if (count($result) > 0) {
            foreach ($result as $value) {
                if ($modelDepartment->checDesign($value->departmentId())) {
                    if ($modelRank->checkNormalRank($value->rankId())) {
                        $resultStatus = true;
                    }
                }
            }
        }
        return $resultStatus;
    }

    #-------------- ----------- kiem tra bo phan KINH DOANH ------------- --------------
    public function checkCurrentDepartmentBusinessOfWork($workId)
    {
        $modelDepartment = new QcDepartment();
        $result = $this->infoActivityOfWork($workId);
        $resultStatus = false;
        if (count($result) > 0) {
            foreach ($result as $value) {
                if ($modelDepartment->checkBusiness($value->departmentId())) $resultStatus = true;
            }
        }
        return $resultStatus;
    }

    // kiem tra nvthuoc BP thu quy
    public function checkBusinessDepartment($workId)
    {
        $modelDepartment = new QcDepartment();
        $result = $this->infoActivityOfWork($workId);
        $resultStatus = false;
        if (count($result) > 0) {
            foreach ($result as $value) {
                if ($modelDepartment->checkBusiness($value->departmentId())) $resultStatus = true;
            }
        }
        return $resultStatus;
    }

    // kiem tra nvthuoc BP kinh doanh cap quan ly
    public function checkBusinessDepartmentAndManageRank($workId)
    {
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $result = $this->infoActivityOfWork($workId);
        $resultStatus = false;
        if (count($result) > 0) {
            foreach ($result as $value) {
                if ($modelDepartment->checkBusiness($value->departmentId())) {
                    if ($modelRank->checkManageRank($value->rankId())) {
                        $resultStatus = true;
                    }
                }
            }
        }
        return $resultStatus;
    }

    // kiem tra nvthuoc BP kinh doanh cap quan nhan vien
    public function checkBusinessDepartmentAndNormalRank($workId)
    {
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $result = $this->infoActivityOfWork($workId);
        $resultStatus = false;
        if (count($result) > 0) {
            foreach ($result as $value) {
                if ($modelDepartment->checkBusiness($value->departmentId())) {
                    if ($modelRank->checkNormalRank($value->rankId())) {
                        $resultStatus = true;
                    }
                }
            }
        }
        return $resultStatus;
    }

    #-------------- ----------- kiem tra bo phan THU QUY ------------- --------------
    public function checkCurrentDepartmentTreasureOfWork($workId)
    {
        $modelDepartment = new QcDepartment();
        $result = $this->infoActivityOfWork($workId);
        $resultStatus = false;
        if (count($result) > 0) {
            foreach ($result as $value) {
                if ($modelDepartment->checkTreasure($value->departmentId())) $resultStatus = true;
            }
        }
        return $resultStatus;
    }

    // kiem tra nvthuoc BP thu quy
    public function checkTreasureDepartment($workId)
    {
        $modelDepartment = new QcDepartment();
        $result = $this->infoActivityOfWork($workId);
        $resultStatus = false;
        if (count($result) > 0) {
            foreach ($result as $value) {
                if ($modelDepartment->checkTreasure($value->departmentId())) $resultStatus = true;
            }
        }
        return $resultStatus;
    }

    // kiem tra nvthuoc BP thu quy cap quan ly
    public function checkTreasureDepartmentAndManageRank($workId)
    {
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $result = $this->infoActivityOfWork($workId);
        $resultStatus = false;
        if (count($result) > 0) {
            foreach ($result as $value) {
                if ($modelDepartment->checkTreasure($value->departmentId())) {
                    if ($modelRank->checkManageRank($value->rankId())) {
                        $resultStatus = true;
                    }
                }
            }
        }
        return $resultStatus;
    }

    // kiem tra nvthuoc BP thu quy cap quan nhan vien
    public function checkTreasureDepartmentAndNormalRank($workId)
    {
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $result = $this->infoActivityOfWork($workId);
        $resultStatus = false;
        if (count($result) > 0) {
            foreach ($result as $value) {
                if ($modelDepartment->checkTreasure($value->departmentId())) {
                    if ($modelRank->checkNormalRank($value->rankId())) {
                        $resultStatus = true;
                    }
                }
            }
        }
        return $resultStatus;
    }

    //---------- Bo phan -----------
    public function department()
    {
        return $this->belongsTo('App\Models\Ad3d\Department\QcDepartment', 'department_id', 'department_id');
    }

    # lay danh sach ID lam viec  cua nv theo bo phan
    public function workIdOfDepartment($departmentId)
    {
        return QcStaffWorkDepartment::where('department_id', $departmentId)->pluck('work_id');
    }

    public function workIdActivityOfDepartment($departmentId)
    {
        return QcStaffWorkDepartment::where('department_id', $departmentId)->where('action', 1)->pluck('work_id');
    }

    //lay id cac bo phan quan ly ma nv dang lam
    public function listManageWorkId()
    {
        $modelDepartment = new QcDepartment();
        return QcStaffWorkDepartment::where('department_id', $modelDepartment->manageDepartmentId())->pluck('work_id');
    }

    // lay id c�c nhan vien cua bo phan thu quy
    public function listTreasureWorkId()
    {
        $modelDepartment = new QcDepartment();
        return QcStaffWorkDepartment::where('department_id', $modelDepartment->treasurerDepartmentId())->pluck('work_id');
    }

    // l?y id c�c nhan vien cua bo phan thu quy
    public function listConstructionWorkId()
    {
        $modelDepartment = new QcDepartment();
        return QcStaffWorkDepartment::where('department_id', $modelDepartment->constructionDepartmentId())->pluck('work_id');
    }

    // l?y id c�c nhan vien cua bo phan thiet ke
    public function listDesignWorkId()
    {
        $modelDepartment = new QcDepartment();
        return QcStaffWorkDepartment::where('department_id', $modelDepartment->designDepartmentId())->pluck('work_id');
    }

    // l?y id c�c nhan vien cua bo phan k? to�n
    public function listAccountantWorkId()
    {
        $modelDepartment = new QcDepartment();
        return QcStaffWorkDepartment::where('department_id', $modelDepartment->accountantDepartmentId())->pluck('work_id');
    }

    // l?y id c�c nhan vien cua bo phan nhan su
    public function listPersonnelWorkId()
    {
        $modelDepartment = new QcDepartment();
        return QcStaffWorkDepartment::where('department_id', $modelDepartment->treasurerDepartmentId())->pluck('work_id');
    }


    //---------- c?p b?c -----------
    public function rank()
    {
        return $this->belongsTo('App\Models\Ad3d\Rank\QcRank', 'rank_id', 'rank_id');
    }

    #============ =========== ============ l?y th�ng tin chi ti?t ============= =========== ==========
    public function getInfo($detailId = '', $field = '')
    {
        if (empty($detailId)) {
            return QcStaffWorkDepartment::get();
        } else {
            $result = QcStaffWorkDepartment::where('detail_id', $detailId)->first();
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
            return QcStaffWorkDepartment::where('detail_id', $objectId)->pluck($column);
        }
    }

    public function detailId()
    {
        return $this->detail_id;
    }

    public function permission($detailId = null)
    {
        return $this->pluck('permission', $detailId);
    }

    public function workId($detailId = null)
    {
        return $this->pluck('work_id', $detailId);
    }

    public function departmentId($detailId = null)
    {
        return $this->pluck('department_id', $detailId);
    }

    public function rankId($detailId = null)
    {
        return $this->pluck('rank_id', $detailId);
    }

    #============ =========== ============ CHECK INFO ============= =========== ==========
    public function existWorkDepartmentRank($workId, $departmentId, $rankId)
    {
        $result = QcStaffWorkDepartment::where(['work_id' => $workId, 'department_id' => $departmentId, 'rank_id' => $rankId, 'action' => 1])->count();
        return ($result > 0) ? true : false;
    }
}
