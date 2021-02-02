<?php

namespace App\Models\Ad3d\DepartmentWork;

use App\Models\Ad3d\Department\QcDepartment;
use Illuminate\Database\Eloquent\Model;

class QcDepartmentWork extends Model
{
    protected $table = 'qc_department_work';
    protected $fillable = ['work_id', 'name', 'description', 'created_at', 'department_id'];
    protected $primaryKey = 'work_id';
    public $timestamps = false;

    private $lastId;

    # mac dinh mo ta cong viec
    public function getDefaultDescription()
    {
        return null;
    }
    #========== ========== ========== INSERT && UPDATE ========== ========== ==========
    #---------- Insert ----------
    public function insert($name, $description, $departmentId)
    {
        $hFunction = new \Hfunction();
        $modelDepartmentWork = new QcDepartmentWork();
        $modelDepartmentWork->name = $name;
        $modelDepartmentWork->description = $description;
        $modelDepartmentWork->department_id = $departmentId;
        $modelDepartmentWork->created_at = $hFunction->createdAt();
        if ($modelDepartmentWork->save()) {
            $this->lastId = $modelDepartmentWork->work_id;
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

    # kiem tra ma lam viec
    public function checkNullId($id)
    {
        $hFunction = new \Hfunction();
        return ($hFunction->checkEmpty($id)) ? $this->workId() : $id;
    }

    #----------- update ----------
    public function updateInfo($workId, $name, $description)
    {
        return QcDepartmentWork::where('work_id', $workId)->update([
            'name' => $name,
            'description' => $description
        ]);
    }

    # delete
    public function deleteInfo($workId = null)
    {
        return QcDepartmentWork::where('work_id', $this->checkNullId($workId))->delete();
    }

    #========== ========== ========== RELATION ========== ========== ==========
    #----------- tay nghe  ------------
    public function workSkill()
    {
        return $this->hasMany('App\Models\Ad3d\WorkSkill\QcWorkSkill', 'work_id', 'departmentWork_id');
    }

    #----------- qc-punish-content ------------
    public function department()
    {
        return $this->belongsTo('App\Models\Ad3d\Department\QcDepartment', 'department_id', 'department_id');
    }

    public function getInfoOfDepartment($departmentId)
    {
        return QcDepartmentWork::where('department_id', $departmentId)->orderBy('name', 'ASC')->get();
    }

    # lay thong tin cua bo phan thi cong
    public function getInfoOfDepartmentConstruction()
    {
        $modelDepartment = new QcDepartment();
        return $this->getInfoOfDepartment($modelDepartment->constructionDepartmentId());
    }

    # lay danh sach ma cong viec theo bo phan
    public function getListIdOfDepartment($departmentId)
    {
        return QcDepartmentWork::where('department_id', $departmentId)->orderBy('name', 'ASC')->pluck('work_id');
    }

    #============ =========== ============ GET INFO ============= =========== ==========
    public function selectInfoAll()
    {
        return QcDepartmentWork::orderBy('name', 'ASC')->select('*');
    }

    # lay thong tin theo danh sach ma cong viec
    public function getInfoByListId($listWorkId)
    {
        return QcDepartmentWork::whereIn('work_id', $listWorkId)->get();
    }

    public function getInfo($workId = '', $field = '')
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($workId)) {
            return QcDepartmentWork::get();
        } else {
            $result = QcDepartmentWork::where('work_id', $workId)->first();
            if ($hFunction->checkEmpty($field)) {
                return $result;
            } else {
                return $result->$field;
            }
        }
    }

    # lay 1 gia tri
    public function pluck($column, $objectId = null)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($objectId)) {
            return $this->$column;
        } else {
            return QcDepartmentWork::where('work_id', $objectId)->pluck($column)[0];
        }
    }

    #----------- punish type info -------------
    public function workId()
    {
        return $this->work_id;
    }

    public function name($workId = null)
    {
        return $this->pluck('name', $workId);
    }

    public function description($workId = null)
    {
        return $this->pluck('description', $workId);
    }

    public function departmentId($workId)
    {
        return $this->pluck('department_id', $workId);
    }

    public function createdAt($workId = null)
    {
        return $this->pluck('created_at', $workId);
    }

    # total record
    public function totalRecords()
    {
        return QcDepartmentWork::count();
    }

    #============ =========== ============ CHECK INFO ============= =========== ==========
    # exist name (add new)
    public function existName($name)
    {
        return QcDepartmentWork::where('name', $name)->exists();
    }

    public function existEditName($workId, $name)
    {
        return QcDepartmentWork::where('name', $name)->where('work_id', '<>', $workId)->exists();
    }
}
