<?php

namespace App\Models\Ad3d\BonusDepartment;

use App\Models\Ad3d\Rank\QcRank;
use Illuminate\Database\Eloquent\Model;

class QcBonusDepartment extends Model
{
    protected $table = 'qc_bonus_department';
    protected $fillable = ['bonus_id', 'percent', 'description', 'applyBonus', 'applyMinus', 'applyStatus', 'action', 'created_at', 'rank_id', 'department_id'];
    protected $primaryKey = 'bonus_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- THEM ----------
    # insert
    public function insert($departmentId, $rankId, $percent, $description = null)
    {
        $hFunction = new \Hfunction();
        $modelBonusDepartment = new QcBonusDepartment();
        // insert
        $modelBonusDepartment->percent = $percent;
        $modelBonusDepartment->description = $hFunction->convertValidHTML($description);;
        $modelBonusDepartment->rank_id = $rankId;
        $modelBonusDepartment->department_id = $departmentId;
        $modelBonusDepartment->created_at = $hFunction->createdAt();
        if ($modelBonusDepartment->save()) {
            $this->lastId = $modelBonusDepartment->bonus_id;
            return true;
        } else {
            return false;
        }
    }

    // lay id moi them
    public function insertGetId()
    {
        return $this->lastId;
    }

    // cap nhat thong tin
    /* public function updateInfo($bonusId, $name, $money, $note, $typeId)
     {
         return QcBonusDepartment::where('bonus_id', $bonusId)->update([
             'name' => $name,
             'money' => $money,
             'note' => $note,
             'type_id' => $typeId
         ]);
     }*/

    public function deleteInfo($bonusId = null)
    {
        $bonusId = (empty($bonusId)) ? $this->punishId() : $bonusId;
        return QcBonusDepartment::where('bonus_id', $bonusId)->delete();
    }
    //========== ========= ========= RELATION ========== ========= ==========
    #---------- thong tin thuong tren don hang -----------
    public function orderBonusBudget()
    {
        return $this->belongsTo('App\Models\Ad3d\OrderBonusBudget\QcOrderBonusBudget', 'bonus_id', 'bonus_id');
    }

    #---------- cap bac thuong -----------
    public function rank()
    {
        return $this->belongsTo('App\Models\Ad3d\Rank\QcRank', 'rank_id', 'rank_id');
    }

    #---------- bo phan -----------
    public function department()
    {
        return $this->belongsTo('App\Models\Ad3d\Department\QcRank', 'department_id', 'department_id');
    }

    #lay thong tin thuong dang hoat dong cua bo phan theo cap quan ly
    public function infoActivityOfManageRank($departmentId)
    {
        $modelRank = new QcRank();
        return $this->infoActivityOfDepartmentRank($departmentId, $modelRank->manageRankId());
    }

    # lay thong tin thuong dang hoat dong cua bo phan theo cap nhan vien
    public function infoActivityOfStaffRank($departmentId)
    {
        $modelRank = new QcRank();
        return $this->infoActivityOfDepartmentRank($departmentId, $modelRank->staffRankId());
    }

    # lay thong tin thuong dang hoat dong cua bo phan theo cap bac
    public function infoActivityOfDepartmentRank($departmentId, $rankId)
    {
        return QcBonusDepartment::where('department_id', $departmentId)->where('rank_id', $rankId)->where('action', 1)->first();
    }

    //========= ========== ========== GET INFO ========== ========== ==========
    public function selectInfoAll()
    {
        return QcBonusDepartment::orderBy('created_at', 'ASC')->select('*');
    }

    public function selectInfoByDepartment($departmentId)
    {
        return QcBonusDepartment::where('department_id', $departmentId)->orderBy('created_at', 'ASC')->select('*');
    }

    # lay tat ca thong tin dang hoat dong
    public function getActivityInfo()
    {
        return QcBonusDepartment::where('applyStatus', 1)->where('action', 1)->get();
    }

    public function getInfo($bonusId = '', $field = '')
    {
        if (empty($bonusId)) {
            return QcBonusDepartment::get();
        } else {
            $result = QcBonusDepartment::where('bonus_id', $bonusId)->first();
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
            return QcBonusDepartment::where('bonus_id', $objectId)->pluck($column);
        }
    }

    public function bonusId()
    {
        return $this->bonus_id;
    }

    public function percent($bonusId = null)
    {
        return $this->pluck('percent', $bonusId);
    }


    public function description($bonusId = null)
    {

        return $this->pluck('description', $bonusId);
    }

    public function applyBonus($bonusId = null)
    {

        return $this->pluck('applyBonus', $bonusId);
    }

    public function applyMinus($bonusId = null)
    {

        return $this->pluck('applyMinus', $bonusId);
    }


    public function applyStatus($bonusId = null)
    {

        return $this->pluck('applyStatus', $bonusId);
    }

    public function createdAt($bonusId = null)
    {
        return $this->pluck('created_at', $bonusId);
    }

    public function rankId($bonusId = null)
    {
        return $this->pluck('rank_id', $bonusId);
    }

    public function departmentId($bonusId = null)
    {
        return $this->pluck('department_id', $bonusId);
    }

    // total records
    public function totalRecords()
    {
        return QcBonusDepartment::count();
    }

    // last id
    public function lastId()
    {
        $result = QcBonusDepartment::orderBy('bonus_id', 'DESC')->first();
        return (empty($result)) ? 0 : $result->bonus_id;
    }

    # cap nhat trang thai ap dung thuong
    public function updateApplyBonus($bonusId, $applyBonus)
    {
        return QcBonusDepartment::where('bonus_id', $bonusId)->update([
            'applyBonus' => $applyBonus
        ]);
    }

    # cap nhat trang thai ap dung PHAT
    public function updateApplyMinus($bonusId, $applyBonus)
    {
        return QcBonusDepartment::where('bonus_id', $bonusId)->update([
            'applyMinus' => $applyBonus
        ]);
    }
    // ---------- ---------- CHECK INFO --------- -------
    # ton tai phan tram cua cap bac dang ap dung
    public function existPercentActivityOfDepartmentAndRank($percent, $departmentId, $rankId)
    {
        return QcBonusDepartment::where('percent', $percent)->where('department_id', $departmentId)->where('rank_id', $rankId)->where('action', 1)->where('applyStatus', 1)->exists();
    }

    # Vo hieu hoa muc thuong dang ap dung
    public function disableBonusOfDepartmentAndRank($departmentId, $rankId)
    {
        return QcBonusDepartment::where('department_id', $departmentId)->where('rank_id', $rankId)->update(['action' => 0, 'applyStatus' => 0]);
    }

    # kiem tra co ap dung thuong
    public function checkApplyBonus($bonusId = null)
    {
        $result = $this->applyBonus($bonusId);
        $result = (int)(is_array($result)) ? $result[0] : $result;
        return ($result == 0) ? false : true;
    }

    # kiem tra co ap dung phat
    public function checkApplyMinus($bonusId = null)
    {
        $result = $this->applyMinus($bonusId);
        $result = (int)(is_array($result)) ? $result[0] : $result;
        return ($result == 0) ? false : true;
    }

}
