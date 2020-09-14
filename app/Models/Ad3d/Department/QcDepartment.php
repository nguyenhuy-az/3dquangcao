<?php

namespace App\Models\Ad3d\Department;

use App\Models\Ad3d\BonusDepartment\QcBonusDepartment;
use App\Models\Ad3d\DepartmentWork\QcDepartmentWork;
use Illuminate\Database\Eloquent\Model;

class QcDepartment extends Model
{
    protected $table = 'qc_departments';
    protected $fillable = ['department_id', 'departmentCode', 'name', 'activityStatus', 'created_at'];
    protected $primaryKey = 'department_id';
    public $timestamps = false;

    private $lastId;

    #========== ========== ========== INSERT && UPDATE ========== ========== ==========
    #---------- Insert ----------
    public function insert($departmentCode, $name, $activityStatus = 1)
    {
        $hFunction = new \Hfunction();
        $modelDepartment = new QcDepartment();
        $modelDepartment->departmentCode = $departmentCode;
        $modelDepartment->name = $name;
        $modelDepartment->created_at = $hFunction->createdAt();
        if ($modelDepartment->save()) {
            $this->lastId = $modelDepartment->department_id;
            return true;
        } else {
            return false;
        }
    }

    # get new id
    public function insertGetId()
    {
        return $this->lastId;
    }

    public function checkNullId($id = null)
    {
        return (empty($id)) ? $this->departmentId() : $id;
    }

    #----------- update ----------
    public function updateInfo($departmentId, $departmentCode, $name)
    {
        return QcDepartment::where('department_id', $departmentId)->update([
            'departmentCode' => $departmentCode,
            'name' => $name,
        ]);
    }

    # status
    public function updateStatus($departmentId, $status)
    {
        return QcDepartment::where('department_id', $departmentId)->update(['activityStatus' => $status]);
    }

    # delete
    public function actionDelete($departmentId = null)
    {
        if (empty($departmentId)) $departmentId = $this->departmentId();
        return QcDepartment::where('department_id', $departmentId)->delete();
    }

    # ----------- thong tin cong viec cua bo phan --------------
    public function departmentWork()
    {
        return $this->hasMany('App\Models\Ad3d\DepartmentWork\QcDepartmentWork', 'department_id', 'department_id');
    }

    # thong tin cong viec cua bo phan
    public function departmentWorkGetInfo($departmentId = null)
    {
        $modelDepartmentWork = new QcDepartmentWork();
        return $modelDepartmentWork->getInfoOfDepartment($this->checkNullId($departmentId));
    }

    # ----------- thong tin NV lam viec --------------
    public function staffWorkDepartment()
    {
        return $this->hasMany('App\Models\Ad3d\StaffWorkDepartment\QcStaffWorkDepartment', 'department_id', 'department_id');
    }

    #----------- department-staff ------------
    public function departmentStaff()
    {
        return $this->hasMany('App\Models\Ad3d\DepartmentStaff\QcDepartmentStaff', 'department_id', 'department_id');
    }

    #----------- KPI ------------
    public function kpi()
    {
        return $this->hasMany('App\Models\Ad3d\Kpi\QcKpi', 'department_id', 'department_id');
    }

    #----------- thuong theo cap bac-----------
    public function bonusDepartment()
    {
        return $this->hasMany('App\Models\Ad3d\BonusDepartment\QcBonusDepartment', 'department_id', 'department_id');
    }

    # thong tin thuong tho cap quan ly cua bo phan dang hoat dong
    public function bonusInfoActivityManageRank($departmentId = null)
    {
        $modelBonusDepartment = new QcBonusDepartment();
        return $modelBonusDepartment->infoActivityOfManageRank($this->checkNullId($departmentId));
    }

    # thong tin thuong tho cap nhan vien cua bo phan dang hoat dong
    public function bonusInfoActivityStaffRank($departmentId = null)
    {
        $modelBonusDepartment = new QcBonusDepartment();
        return $modelBonusDepartment->infoActivityOfStaffRank($this->checkNullId($departmentId));
    }

    #============ =========== ============ GET INFO ============= =========== ==========
    public function selectInfoAllActivity()
    {
        return QcDepartment::where('activityStatus', 1)->select('*');
    }

    public function selectInfoAll()
    {
        return QcDepartment::orderBy('name', 'ASC')->select('*');
    }


    public function getInfo($departmentId = '', $field = '')
    {
        if (empty($departmentId)) {
            return QcDepartment::get();
        } else {
            $result = QcDepartment::where('department_id', $departmentId)->first();
            if (empty($field)) {
                return $result;
            } else {
                return $result->$field;
            }
        }
    }

    # create option
    public function getOption($selected = '')
    {
        $hFunction = new \Hfunction();
        $result = QcDepartment::select('department_id as optionKey', 'name as optionValue')->get()->toArray();
        return $hFunction->option($result, $selected);
    }

    public function pluck($column, $objectId = null)
    {
        if (empty($objectId)) {
            return $this->$column;
        } else {
            return QcDepartment::where('department_id', $objectId)->pluck($column);
        }
    }

    #----------- DEPARTMENT INFO -------------
    public function departmentId()
    {
        return $this->department_id;
    }

    public function name($departmentId = null)
    {
        return $this->pluck('name', $departmentId);
    }

    public function departmentCode($departmentId = null)
    {
        return $this->pluck('departmentCode', $departmentId);
    }


    public function activityStatus($departmentId = null)
    {
        return $this->pluck('activityStatus', $departmentId);
    }


    public function createdAt($departmentId = null)
    {
        return $this->pluck('created_at', $departmentId);
    }

    public function manageDepartmentId()
    {
        return 1; # quản lý
    }

    public function constructionDepartmentId()
    {
        return 2; # thi công
    }

    public function accountantDepartmentId()
    {
        return 3; # ke toan
    }

    public function designDepartmentId()
    {
        return 4; # thiết kế
    }

    public function businessDepartmentId()
    {
        return 5; #kinh doanh
    }

    public function personnelDepartmentId()
    {
        return 6; # nhân sự
    }

    # thủ quỹ
    public function treasurerDepartmentId()
    {
        return 7;
    }

    # danh sanh ma bo phan nhan thong bao them don hang moi
    public function listIdReceiveNotifyNewOrder()
    {
        return [$this->businessDepartmentId(), $this->designDepartmentId(), $this->constructionDepartmentId(), $this->manageDepartmentId()];
    }

    #----------- KIỂM TRA THÔNG TIN -------------
    public function existName($name)
    {
        $result = QcDepartment::where('name', $name)->count();
        return ($result > 0) ? true : false;
    }

    public function existEditName($departmentId, $name)
    {
        $result = QcDepartment::where('name', $name)->where('department_id', '<>', $departmentId)->count();
        return ($result > 0) ? true : false;
    }

    public function existNameCode($departmentCode)
    {
        $result = QcDepartment::where('departmentCode', $departmentCode)->count();
        return ($result > 0) ? true : false;
    }

    public function existEditNameCode($departmentId, $departmentCode)
    {
        $result = QcDepartment::where('departmentCode', $departmentCode)->where('department_id', '<>', $departmentId)->count();
        return ($result > 0) ? true : false;
    }

    public function checkActivityStatus($departmentId = null)
    {
        return ($this->getInfo((empty($departmentId) ? $this->departmentId() : $departmentId), 'activityStatus') == 1) ? true : false;
    }

    // bộ phận quản lý
    public function checkManage($departmentId)
    {
        return ((empty($departmentId) ? $this->departmentId() : $departmentId) == $this->manageDepartmentId()) ? true : false;
    }

    //bộ phận thi công
    public function checkConstruction($departmentId)
    {
        return ((empty($departmentId) ? $this->departmentId() : $departmentId) == $this->constructionDepartmentId()) ? true : false;
    }

    //bộ phận kế toán
    public function checkAccountant($departmentId)
    {
        return ((empty($departmentId) ? $this->departmentId() : $departmentId) == $this->accountantDepartmentId()) ? true : false;
    }

    //bộ phận thiết kê
    public function checkDesign($departmentId)
    {
        return ((empty($departmentId) ? $this->departmentId() : $departmentId) == $this->designDepartmentId()) ? true : false;
    }

    //bộ phận nhân sự
    public function checkBusiness($departmentId)
    {
        return ((empty($departmentId) ? $this->departmentId() : $departmentId) == $this->businessDepartmentId()) ? true : false;
    }

    //bộ phận nhân sự
    public function checkPersonnel($departmentId)
    {
        return ((empty($departmentId) ? $this->departmentId() : $departmentId) == $this->personnelDepartmentId()) ? true : false;
    }

    //bộ phận nhân sự
    public function checkTreasure($departmentId)
    {
        return ((empty($departmentId) ? $this->departmentId() : $departmentId) == $this->treasurerDepartmentId()) ? true : false;
    }
}
