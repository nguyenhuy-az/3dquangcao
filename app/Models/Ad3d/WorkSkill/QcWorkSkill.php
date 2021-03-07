<?php

namespace App\Models\Ad3d\WorkSkill;

use App\Models\Ad3d\DepartmentWork\QcDepartmentWork;
use Illuminate\Database\Eloquent\Model;

class QcWorkSkill extends Model
{
    protected $table = 'qc_work_skill';
    protected $fillable = ['skill_id', 'level', 'action', 'created_at', 'departmentWork_id', 'companyStaffWork_id'];
    protected $primaryKey = 'skill_id';
    public $timestamps = false;

    private $lastId;

    #mac dinh khong biet lam
    public function getDefaultNotLevel()
    {
        return 1;
    }

    # mac dinh biet lam
    public function getDefaultMediumLevel()
    {
        return 2;
    }

    #mac dinh lam gioi
    public function getDefaultGoodLevel()
    {
        return 3;
    }

    #mac dinh dang hoat dong
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
    public function insert($level, $departmentWorkId, $companyStaffWorkId)
    {
        $hFunction = new \Hfunction();
        $modelStaffWorkSkill = new QcWorkSkill();
        $modelStaffWorkSkill->level = $level;
        $modelStaffWorkSkill->departmentWork_id = $departmentWorkId;
        $modelStaffWorkSkill->companyStaffWork_id = $companyStaffWorkId;
        $modelStaffWorkSkill->created_at = $hFunction->createdAt();
        if ($modelStaffWorkSkill->save()) {
            $this->lastId = $modelStaffWorkSkill->skill_id;
            return true;
        } else {
            return false;
        }
    }

    # lay ma ky nang moi them
    public function insertGetId()
    {
        return $this->lastId;
    }

    # kiem tra id
    public function checkNullId($id = null)
    {
        $hFunction = new \Hfunction();
        return ($hFunction->checkEmpty($id)) ? $this->skillId() : $id;
    }

    # delete
    public function deleteInfo($skillId = null)
    {
        return QcWorkSkill::where('skill_id', $this->checkNullId())->delete();
    }

    # vo hieu hoa 1 ky nang
    public function disableInfo($skillId = null)
    {
        return QcWorkSkill::where('skill_id', $this->checkNullId($skillId))->update(['action' => $this->getDefaultNotAction()]);
    }
    #========== ========== ========== RELATION ========== ========== ==========
    #----------- cong viec cua bo phan ------------
    public function departmentWork()
    {
        return $this->belongsTo('App\Models\Ad3d\DepartmentWork\QcDepartmentWork', 'departmentWork_id', 'work_id');
    }

    #----------- thong tin ho so ------------
    public function companyStaffWork()
    {
        return $this->belongsTo('App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork', 'companyStaffWork_id', 'work_id');
    }

    # lay danh sach ky nang dang hoat dong
    public function getInfoHasActionOfCompanyStaffWork($companyStaffWorkId)
    {
        return QcWorkSkill::where('companyStaffWork_id', $companyStaffWorkId)->get();
    }

    # lay ky nang theo bo phan
    public function getInfoOfCompanyStaffWorkInDepartment($companyStaffWorkId, $departmentId)
    {
        $modelDepartmentWork = new QcDepartmentWork();
        # danh sach cong viec cua bo phan
        $listDepartmentWorkId = $modelDepartmentWork->getListIdOfDepartment($departmentId);
        return QcWorkSkill::where('companyStaffWork_id', $companyStaffWorkId)->whereIn('departmentWork_id', $listDepartmentWorkId)->get();
    }

    # lay thong tin ky nang sau cung cua 1 cong viec trong bo phan
    public function getInfoLastOfCompanyStaffWorkAndWork($companyStaffWorkId, $departmentWorkId)
    {
        return QcWorkSkill::where('companyStaffWork_id', $companyStaffWorkId)->where('departmentWork_id', $departmentWorkId)->orderBy('skill_id', 'DESC')->first();
    }

    # lay thong tin ky nang dang hoat dong cua 1 cong viec trong bo phan
    /*public function getInfoEnableOfCompanyStaffWorkAndWorkAndLevel($companyStaffWorkId, $departmentWorkId, $level)
    {
        return QcWorkSkill::where([
            'companyStaffWork_id' => $companyStaffWorkId,
            'departmentWork_id' => $departmentWorkId,
            'level' => $level,
            'action' => $this->getDefaultHasAction()
        ])->first();
    }*/

    #============ =========== ============ GET INFO ============= =========== ==========
    public function selectInfoAll()
    {
        return QcWorkSkill::select('*');
    }

    public function getInfo($skillId = null, $field = null)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($skillId)) {
            return QcWorkSkill::get();
        } else {
            $result = QcWorkSkill::where('skill_id', $skillId)->first();
            if ($hFunction->checkEmpty($field)) {
                return $result;
            } else {
                return $result->$field;
            }
        }
    }

    # lay 1 thong tin
    public function pluck($column, $objectId = null)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($objectId)) {
            return $this->$column;
        } else {
            return QcWorkSkill::where('skill_id', $objectId)->pluck($column)[0];
        }
    }

    public function skillId()
    {
        return $this->skill_id;
    }

    public function level($skillId = null)
    {
        return $this->pluck('level', $skillId);
    }

    public function levelLabel($level)
    {
        if ($level == $this->getDefaultNotLevel()) {
            return 'Không biết';
        } elseif ($level == $this->getDefaultMediumLevel()) {
            return 'Biêt';
        } elseif ($level == $this->getDefaultGoodLevel()) {
            return 'Giỏi';
        } else {
            return 'Chưa xác định';
        }
    }

    public function departmentWorkId($skillId)
    {
        return $this->pluck('departmentWork_id', $skillId);
    }

    public function companyStaffWorkId($skillId)
    {
        return $this->pluck('companyStaffWork_id', $skillId);
    }

    public function createdAt($skillId = null)
    {
        return $this->pluck('created_at', $skillId);
    }

    # total record
    public function totalRecords()
    {
        return QcWorkSkill::count();
    }

    #============ =========== ============ CHECK INFO ============= =========== ==========
    # ton tai 1 ky nang dang hoat dong cua 1 nhan vien
    public function existSkillIsActivity($companyStaffWorkId, $departmentWorkId)
    {
        return QcWorkSkill::where('companyStaffWork_id', $companyStaffWorkId)->where('departmentWork_id', $departmentWorkId)->where('action', $this->getDefaultHasAction())->exists();
    }

    # ton tai 1 ky nang dang hoat dong cua 1 level cua 1 nhan vien - tre
    public function existSkillIsActivityOnLevel($companyStaffWorkId, $departmentWorkId, $level)
    {
        return QcWorkSkill::where('companyStaffWork_id', $companyStaffWorkId)->where('departmentWork_id', $departmentWorkId)->where('action', $this->getDefaultHasAction())->where('level', $level)->exists();
    }
}
