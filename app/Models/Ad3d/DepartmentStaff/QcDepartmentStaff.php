<?php

namespace App\Models\Ad3d\DepartmentStaff;

use App\Models\Ad3d\Department\QcDepartment;
use App\Models\Ad3d\Rank\QcRank;
use Illuminate\Database\Eloquent\Model;

class QcDepartmentStaff extends Model
{
    protected $table = 'qc_department_staffs';
    protected $fillable = ['department_staff_id', 'action', 'created_at', 'staff_id', 'department_id', 'rank_id', 'permission'];
    protected $primaryKey = 'department_staff_id';
    public $timestamps = false;

    private $lastId;

    #========== ========== ========== INSERT && UPDATE ========== ========== ==========
    #---------- Insert ----------
    public function insert($staffId, $departmentId, $rankId, $permission)
    {
        $hFunction = new \Hfunction();
        $modelDepartmentStaff = new QcDepartmentStaff();
        $modelDepartmentStaff->staff_id = $staffId;
        $modelDepartmentStaff->department_id = $departmentId;
        $modelDepartmentStaff->rank_id = $rankId;
        $modelDepartmentStaff->permission = $permission;
        $modelDepartmentStaff->action = 1;
        $modelDepartmentStaff->created_at = $hFunction->createdAt();
        if ($modelDepartmentStaff->save()) {
            $this->lastId = $modelDepartmentStaff->department_staff_id;
            return true;
        } else {
            return false;
        }
    }

    public function disableStaff($staffId)
    {
        return QcDepartmentStaff::where('staff_id', $staffId)->update(['action' => 0]);
    }

    //---------- nhan vien -----------
    public function staff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaffs', 'staff_id', 'staff_id');
    }

    //thông tin đang hoạt động của nv
    //1 nhân viên có thể thuộc nhiều bộ phận
    public function infoActivityOfStaff($staffId)
    {
        return QcDepartmentStaff::where(['staff_id' => $staffId, 'action' => 1])->get();
    }

    public function checkExistStaffOfDepartment($staffId, $departmentId)
    {
        $result = QcDepartmentStaff::where(['staff_id' => $staffId, 'department_id' => $departmentId, 'action' => 1])->count();
        return ($result > 0) ? true : false;
    }


    //--------------------------kiểm tra bộ phậno hiện tại của nhân viên ---------------------------

    public function checkCurrentDepartmentManageOfStaff($staffId)
    {
        $modelDepartment = new QcDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if (count($result) > 0) {
            foreach ($result as $value) {
                if ($modelDepartment->checkManage($value->departmentId())) $resultStatus = true;
            }
        }
        return $resultStatus;
    }

    public function checkCurrentDepartmentConstructionOfStaff($staffId)
    {
        $modelDepartment = new QcDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if (count($result) > 0) {
            foreach ($result as $value) {
                if ($modelDepartment->checkConstruction($value->departmentId())) $resultStatus = true;
            }
        }
        return $resultStatus;
    }

    public function checkCurrentDepartmentAccountantOfStaff($staffId)
    {
        $modelDepartment = new QcDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if (count($result) > 0) {
            foreach ($result as $value) {
                if ($modelDepartment->checkAccountant($value->departmentId())) $resultStatus = true;
            }
        }
        return $resultStatus;
    }

    public function checkCurrentDepartmentDesignOfStaff($staffId)
    {
        $modelDepartment = new QcDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if (count($result) > 0) {
            foreach ($result as $value) {
                if ($modelDepartment->checkDesign($value->departmentId())) $resultStatus = true;
            }
        }
        return $resultStatus;
    }

    public function checkCurrentDepartmentBusinessOfStaff($staffId)
    {
        $modelDepartment = new QcDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if (count($result) > 0) {
            foreach ($result as $value) {
                if ($modelDepartment->checkBusiness($value->departmentId())) $resultStatus = true;
            }
        }
        return $resultStatus;
    }

    public function checkCurrentDepartmentPersonnelOfStaff($staffId)
    {
        $modelDepartment = new QcDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if (count($result) > 0) {
            foreach ($result as $value) {
                if ($modelDepartment->checkPersonnel($value->departmentId())) $resultStatus = true;
            }
        }
        return $resultStatus;
    }

    public function checkCurrentDepartmentTreasureOfStaff($staffId)
    {
        $modelDepartment = new QcDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if (count($result) > 0) {
            foreach ($result as $value) {
                if ($modelDepartment->checkTreasure($value->departmentId())) $resultStatus = true;
            }
        }
        return $resultStatus;
    }

    // kiểm tra nv thuộc bộ phận quản lý
    public function checkManageDepartment($staffId)
    {
        $modelDepartment = new QcDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if (count($result) > 0) {
            foreach ($result as $value) {
                if ($modelDepartment->checkManage($value->departmentId())) $resultStatus = true;
            }
        }
        return $resultStatus;
    }

    //--------------------------kiểm tra bộ theo cấp bậc của nhân viên ---------------------------
    // kiểm tra nv thuộc bộ phận quản lý cấp quản lý
    public function checkManageDepartmentAndManageRank($staffId)
    {
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if (count($result) > 0) {
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

    // kiểm tra nv thuộc bộ phận quản lý cấp thông thường
    public function checkManageDepartmentAndNormalRank($staffId)
    {
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $result = $this->infoActivityOfStaff($staffId);
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

    // kiểm tra nv thuộc bộ phận nhân sự
    public function checkPersonnelDepartment($staffId)
    {
        $modelDepartment = new QcDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if (count($result) > 0) {
            foreach ($result as $value) {
                if ($modelDepartment->checkPersonnel($value->departmentId())) $resultStatus = true;
            }
        }
        return $resultStatus;
    }

    // kiểm tra nv thuộc bộ phận nhận sự cấp quản lý
    public function checkPersonnelDepartmentAndManageRank($staffId)
    {
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $result = $this->infoActivityOfStaff($staffId);
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

    // kiểm tra nv thuộc bộ phận nhân sự cấp thông thường
    public function checkPersonnelDepartmentAndNormalRank($staffId)
    {
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $result = $this->infoActivityOfStaff($staffId);
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

    // kiểm tra nv thuộc bộ phận thiết kế
    public function checkDesignDepartment($staffId)
    {
        $modelDepartment = new QcDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if (count($result) > 0) {
            foreach ($result as $value) {
                if ($modelDepartment->checkDesign($value->departmentId())) $resultStatus = true;
            }
        }
        return $resultStatus;
    }

    // kiểm tra nv thuộc bộ phận thiết kế cấp quản lý
    public function checkDesignDepartmentAndManageRank($staffId)
    {
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $result = $this->infoActivityOfStaff($staffId);
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

    // kiểm tra nv thuộc bộ phận thiết kế cấp thông thường
    public function checkDesignDepartmentAndNormalRank($staffId)
    {
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $result = $this->infoActivityOfStaff($staffId);
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

    // kiểm tra nv thuộc bộ phận kinh doannh
    public function checkBusinessDepartment($staffId)
    {
        $modelDepartment = new QcDepartment();
        $result = $this->infoActivityOfStaff($staffId);
        $resultStatus = false;
        if (count($result) > 0) {
            foreach ($result as $value) {
                if ($modelDepartment->checkBusiness($value->departmentId())) $resultStatus = true;
            }
        }
        return $resultStatus;
    }

    // kiểm tra nv thuộc bộ phận kinh doanh cấp quản lý
    public function checkBusinessDepartmentAndManageRank($staffId)
    {
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $result = $this->infoActivityOfStaff($staffId);
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

    // kiểm tra nv thuộc bộ phận kinh doanh cấp thông thường
    public function checkBusinessDepartmentAndNormalRank($staffId)
    {
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $result = $this->infoActivityOfStaff($staffId);
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

    //---------- Bộ phận -----------
    public function department()
    {
        return $this->belongsTo('App\Models\Ad3d\Department\QcDepartment', 'department_id', 'department_id');
    }

    # lấy danh sách ID của NV theo bộ phận
    public function staffIdOfDepartment($departmentId)
    {
        return QcDepartmentStaff::where('department_id', $departmentId)->pluck('staff_id');
    }

    # lấy danh sách ID dang hoat dong của NV theo bộ phận
    public function staffIdActivityOfDepartment($departmentId)
    {
        return QcDepartmentStaff::where('department_id', $departmentId)->where('action', 1)->pluck('staff_id');
    }

    // lấy id các bộ phận quản lý mà nv đang làm
    public function listManageStaffId()
    {
        $modelDepartment = new QcDepartment();
        return QcDepartmentStaff::where('department_id', $modelDepartment->manageDepartmentId())->pluck('staff_id');
    }

    // lấy id các nhan vien cua bo phan thu quy
    public function listTreasureStaffId()
    {
        $modelDepartment = new QcDepartment();
        return QcDepartmentStaff::where('department_id', $modelDepartment->treasurerDepartmentId())->pluck('staff_id');
    }

    // lấy id các nhan vien cua bo phan thu quy
    public function listConstructionStaffId()
    {
        $modelDepartment = new QcDepartment();
        return QcDepartmentStaff::where('department_id', $modelDepartment->constructionDepartmentId())->pluck('staff_id');
    }

    // lấy id các nhan vien cua bo phan thiet ke
    public function listDesignStaffId()
    {
        $modelDepartment = new QcDepartment();
        return QcDepartmentStaff::where('department_id', $modelDepartment->designDepartmentId())->pluck('staff_id');
    }

    // lấy id các nhan vien cua bo phan kế toán
    public function listAccountantStaffId()
    {
        $modelDepartment = new QcDepartment();
        return QcDepartmentStaff::where('department_id', $modelDepartment->accountantDepartmentId())->pluck('staff_id');
    }

    // lấy id các nhan vien cua bo phan nhan su
    public function listPersonnelStaffId()
    {
        $modelDepartment = new QcDepartment();
        return QcDepartmentStaff::where('department_id', $modelDepartment->treasurerDepartmentId())->pluck('staff_id');
    }


    //---------- cấp bậc -----------
    public function rank()
    {
        return $this->belongsTo('App\Models\Ad3d\Rank\QcRank', 'rank_id', 'rank_id');
    }

    #============ =========== ============ lấy thông tin chi tiết ============= =========== ==========
    public function getInfo($departmentId = '', $field = '')
    {
        if (empty($departmentId)) {
            return QcDepartmentStaff::get();
        } else {
            $result = QcDepartmentStaff::where('department_id', $departmentId)->first();
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
            return QcDepartmentStaff::where('department_staff_id', $objectId)->pluck($column);
        }
    }

    public function departmentStaffId()
    {
        return $this->department_staff_id;
    }

    public function permission($departmentStaffId = null)
    {
        return $this->pluck('permission', $departmentStaffId);
    }

    public function staffId($departmentStaffId = null)
    {
        return $this->pluck('staff_id', $departmentStaffId);
    }

    public function departmentId($departmentStaffId = null)
    {
        return $this->pluck('department_id', $departmentStaffId);
    }

    public function rankId($departmentStaffId = null)
    {
        return $this->pluck('rank_id', $departmentStaffId);
    }

    #============ =========== ============ CHECK INFO ============= =========== ==========
    public function existStaffDepartmentRank($staffId, $departmentId, $rankId)
    {
        $result = QcDepartmentStaff::where(['staff_id' => $staffId, 'department_id' => $departmentId, 'rank_id' => $rankId, 'action' => 1])->count();
        return ($result > 0) ? true : false;
    }
}
