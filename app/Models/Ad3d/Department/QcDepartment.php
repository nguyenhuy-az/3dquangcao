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

    # mac dinh dang hoat dong
    public function getDefaultHasAction()
    {
        return 1;
    }

    # mac dinh khong con hoat dong
    public function getDefaultNotAction()
    {
        return 0;
    }
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
        $hFunction = new \Hfunction();
        return ($hFunction->checkEmpty($id)) ? $this->departmentId() : $id;
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
        return QcDepartment::where('department_id', $this->checkNullId($departmentId))->delete();
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
        return QcDepartment::where('activityStatus', $this->getDefaultHasAction())->select('*');
    }

    public function selectInfoAll()
    {
        return QcDepartment::orderBy('name', 'ASC')->select('*');
    }

    # lay thong tin theo danh sach ma bo phan co san
    public function getInfoByListId($listId)
    {
        return QcDepartment::whereIn('department_id', $listId)->get();
    }

    public function getInfo($departmentId = null, $field = null)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($departmentId)) {
            return QcDepartment::get();
        } else {
            $result = QcDepartment::where('department_id', $departmentId)->first();
            if ($hFunction->checkEmpty($field)) {
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
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($objectId)) {
            return $this->$column;
        } else {
            return QcDepartment::where('department_id', $objectId)->pluck($column)[0];
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
        return QcDepartment::where('name', $name)->exists();
    }

    public function existEditName($departmentId, $name)
    {
        return QcDepartment::where('name', $name)->where('department_id', '<>', $departmentId)->exists();
    }

    public function existNameCode($departmentCode)
    {
        return QcDepartment::where('departmentCode', $departmentCode)->exists();
    }

    public function existEditNameCode($departmentId, $departmentCode)
    {
        return QcDepartment::where('departmentCode', $departmentCode)->where('department_id', '<>', $departmentId)->exists();
    }

    # con hoat dong hay ko
    public function checkActivityStatus($departmentId = null)
    {
        return ($this->activityStatus($departmentId) == $this->getDefaultHasAction()) ? true : false;
    }

    // bộ phận quản lý
    public function checkManage($departmentId)
    {
        return ($this->checkNullId($departmentId) == $this->manageDepartmentId()) ? true : false;
    }

    //bộ phận thi công
    public function checkConstruction($departmentId)
    {
        return ($this->checkNullId($departmentId) == $this->constructionDepartmentId()) ? true : false;
    }

    //bộ phận kế toán
    public function checkAccountant($departmentId)
    {
        return ($this->checkNullId($departmentId) == $this->accountantDepartmentId()) ? true : false;
    }

    //bộ phận thiết kê
    public function checkDesign($departmentId)
    {
        return ($this->checkNullId($departmentId) == $this->designDepartmentId()) ? true : false;
    }

    //bộ phận nhân sự
    public function checkBusiness($departmentId)
    {
        return ($this->checkNullId($departmentId) == $this->businessDepartmentId()) ? true : false;
    }

    //bộ phận nhân sự
    public function checkPersonnel($departmentId)
    {
        return ($this->checkNullId($departmentId) == $this->personnelDepartmentId()) ? true : false;
    }

    //bộ phận nhân sự
    public function checkTreasure($departmentId)
    {
        return ($this->checkNullId($departmentId) == $this->treasurerDepartmentId()) ? true : false;
    }
}
