<?php

namespace App\Models\Ad3d\Bonus;

use App\Models\Ad3d\StaffNotify\QcStaffNotify;
use Illuminate\Database\Eloquent\Model;

class QcBonus extends Model
{
    protected $table = 'qc_bonus';
    protected $fillable = ['bonus_id', 'money', 'bonusDate', 'note', 'applyStatus', 'cancelStatus', 'created_at', 'work_id', 'orderAllocation_id'];
    protected $primaryKey = 'bonus_id';
    public $timestamps = false;

    private $lastId;

    #========== ========== ========== INSERT && UPDATE ========== ========== ==========
    #---------- Insert ----------
    public function insert($money, $bonusDate, $note, $applyStatus, $workId, $orderAllocationId)
    {
        $hFunction = new \Hfunction();
        $modelBonus = new QcBonus();
        $modelBonus = new QcBonus();
        $modelBonus->money = $money;
        $modelBonus->bonusDate = $bonusDate;
        $modelBonus->note = $note;
        $modelBonus->applyStatus = $applyStatus;
        $modelBonus->work_id = $workId;
        $modelBonus->orderAllocation_id = $orderAllocationId;
        $modelBonus->created_at = $hFunction->createdAt();
        if ($modelBonus->save()) {
            $this->lastId = $modelBonus->bonus_id;
            return true;
        } else {
            return false;
        }
    }

    /* public function updateInfo($bonusId, $money, $datePay, $reason, $punishId)
     {
         return QcBonus::where('bonus_id', $bonusId)->update(['money' => $money, 'dateMinus' => $datePay, 'reason' => $reason, 'punish_id' => $punishId]);
     }*/
//lay id moi them
    public function insertGetId()
    {
        return $this->lastId;
    }

    public function checkNullId($bonusId)
    {
        return (empty($bonusId)) ? $this->payId() : $bonusId;
    }

    public function cancelBonus($bonusId = null)
    {
        return QcBonus::where('bonus_id', $bonusId)->update(['cancelStatus' => 1]);
    }

    //----------- làm việc ------------
    public function work()
    {
        return $this->belongsTo('App\Models\Ad3d\Work\QcWork', 'work_id', 'work_id');
    }

    public function infoOfWork($workId)
    {
        return QcBonus::where('work_id', $workId)->orderBy('bonus_id', 'DESC')->get();
    }

    public function totalMoneyOfWork($workId)
    {
        return QcBonus::where('work_id', $workId)->sum('money');
    }

    public function totalMoneyApplyOfWork($workId)
    {
        return QcBonus::where('work_id', $workId)->where('applyStatus', 1)->sum('money');
    }

    public function totalMoneyNotApplyOfWork($workId)
    {
        return QcBonus::where('work_id', $workId)->where('applyStatus', 0)->sum('money');
    }

    //---------- thong bao ban giao don hang moi -----------
    public function orderAllocation()
    {
        return $this->belongsTo('App\Models\Ad3d\OrderAllocation\QcOrderAllocation', 'orderAllocation_id', 'allocation_id');
    }

    //---------- thong bao ban giao don hang moi -----------
    public function staffNotify()
    {
        return $this->hasMany('App\Models\Ad3d\StaffNotify\QcStaffNotify', 'bonus_id', 'bonus_id');
    }

    public function checkViewedNewBonus($bonusId, $staffId)
    {
        $modelStaffAllocation = new QcStaffNotify();
        return $modelStaffAllocation->checkViewedBonusOfStaff($staffId, $bonusId);
    }
    #============ =========== ============ GET INFO ============= =========== ==========
    public function getInfo($bonusId = '', $field = '')
    {
        if (empty($bonusId)) {
            return QcBonus::get();
        } else {
            $result = QcBonus::where('bonus_id', $bonusId)->first();
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
            return QcBonus::where('bonus_id', $objectId)->pluck($column);
        }
    }

    public function bonusId()
    {
        return $this->bonus_id;
    }

    public function money($bonusId = null)
    {
        return $this->pluck('money', $bonusId);
    }

    public function bonusDate($bonusId = null)
    {
        return $this->pluck('bonusDate', $bonusId);
    }

    public function note($bonusId = null)
    {
        return $this->pluck('note', $bonusId);
    }

    public function applyStatus($bonusId = null)
    {
        return $this->pluck('applyStatus', $bonusId);
    }

    public function cancelStatus($bonusId = null)
    {
        return $this->pluck('cancelStatus', $bonusId);
    }

    public function createdAt($bonusId = null)
    {
        return $this->pluck('created_at', $bonusId);
    }

    public function workId($bonusId = null)
    {
        return $this->pluck('work_id', $bonusId);
    }

    public function orderAllocationId($bonusId = null)
    {
        return $this->pluck('orderAllocation_id', $bonusId);
    }

}
