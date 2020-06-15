<?php

namespace App\Models\Ad3d\BonusDepartment;

use App\Models\Ad3d\Rank\QcRank;
use Illuminate\Database\Eloquent\Model;

class QcBonusDepartment extends Model
{
    protected $table = 'qc_bonus_department';
    protected $fillable = ['bonus_id', 'percent', 'description', 'applyStatus', 'action', 'created_at', 'rank_id', 'department_id'];
    protected $primaryKey = 'bonus_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- thêm ----------
    // insert
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

    // l?y id m?i thêm
    public function insertGetId()
    {
        return $this->lastId;
    }

    // c?p nh?t thông tin
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
    //---------- cap bac thuong -----------
    public function rank()
    {
        return $this->belongsTo('App\Models\Ad3d\Rank\QcRank', 'rank_id', 'rank_id');
    }

    //---------- bo phan -----------
    public function department()
    {
        return $this->belongsTo('App\Models\Ad3d\Department\QcRank', 'department_id', 'department_id');
    }

# lay thong tin thuong ?ang hoat dong cua bo phan theo cap quan ly
    public function infoActivityOfManageRank($departmentId)
    {
        $modelRank = new QcRank();
        return $this->infoActivityOfDepartmentRank($departmentId, $modelRank->manageRankId());
    }

# lay thong tin thuong ?ang hoat dong cua bo phan theo cap nhan vien
    public function infoActivityOfStaffRank($departmentId)
    {
        $modelRank = new QcRank();
        return $this->infoActivityOfDepartmentRank($departmentId, $modelRank->staffRankId());
    }

    # lay thong tin thuong ?ang hoat dong cua bo phan theo cap bac
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
}
