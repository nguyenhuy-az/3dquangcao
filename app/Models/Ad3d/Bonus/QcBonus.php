<?php

namespace App\Models\Ad3d\Bonus;

use App\Models\Ad3d\StaffNotify\QcStaffNotify;
use Illuminate\Database\Eloquent\Model;

class QcBonus extends Model
{
    protected $table = 'qc_bonus';
    protected $fillable = ['bonus_id', 'money', 'bonusDate', 'note', 'applyStatus', 'cancelStatus', 'action', 'created_at', 'work_id', 'orderAllocation_id', 'orderConstruction_id', 'orderPay_id'];
    protected $primaryKey = 'bonus_id';
    public $timestamps = false;
    private $lastId;

    #========== ========== ========== INSERT && UPDATE ========== ========== ==========
    #---------- Insert ----------
    public function insert($money, $bonusDate, $note, $applyStatus, $workId, $orderAllocationId = null, $orderConstructionId = null, $orderPayId = null)
    {
        $hFunction = new \Hfunction();
        $modelBonus = new QcBonus();
        $modelBonus->money = $money;
        $modelBonus->bonusDate = $bonusDate;
        $modelBonus->note = $note;
        $modelBonus->applyStatus = $applyStatus;
        $modelBonus->work_id = $workId;
        $modelBonus->orderAllocation_id = $orderAllocationId;
        $modelBonus->orderConstruction_id = $orderConstructionId;
        $modelBonus->orderPay_id = $orderPayId;
        $modelBonus->created_at = $hFunction->createdAt();
        if ($modelBonus->save()) {
            $this->lastId = $modelBonus->bonus_id;
            return true;
        } else {
            return false;
        }
    }

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
        return QcBonus::where('bonus_id', $bonusId)->update(['cancelStatus' => 1, 'action' => 0]);
    }

    # ---------- Thanh toan dơn hNG -----------------
    public function orderPay()
    {
        return $this->belongsTo('App\Models\Ad3d\OrderPay\QcOrderPay', 'orderPay_id', 'pay_id');
    }

    # kiem nv da duoc thuong khi thanh toan don hang hay chua
    public function checkOrderPayBonus($workId, $orderPayId)
    {
        return QcBonus::where('work_id', $workId)->where('orderPay_id', $orderPayId)->exists();
    }


    # ---------- trien khai don hang thi cong -----------------
    public function orderConstruction()
    {
        return $this->belongsTo('App\Models\Ad3d\Order\QcOrder', 'orderConstruction_id', 'order_id');
    }

    public function checkExistBonusWorkOfOrderConstruction($workId, $orderConstructionId)
    {
        return QcBonus::where('work_id', $workId)->where('orderConstruction_id', $orderConstructionId)->exists();
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
        return QcBonus::where('work_id', $workId)->where('cancelStatus', 0)->sum('money');
    }

    public function totalMoneyApplyOfWork($workId)
    {
        return QcBonus::where('work_id', $workId)->where('applyStatus', 1)->sum('money');
    }

    public function totalMoneyNotApplyOfWork($workId)
    {
        return QcBonus::where('work_id', $workId)->where('applyStatus', 0)->sum('money');
    }

    public function totalMoneyAppliedOfWork($workId)
    {
        return QcBonus::where('work_id', $workId)->where('applyStatus', 1)->where('cancelStatus', 0)->where('action', 0)->sum('money');
    }

    public function autoCheckApplyBonusEndWork($workId)
    {
        return QcBonus::where('work_id', $workId)->where('cancelStatus', 0)->where('action', 1)->update(['applyStatus' => 1, 'action' => 0]);
    }

    //---------- thong bao ban giao don hang moi -----------
    public function orderAllocation()
    {
        return $this->belongsTo('App\Models\Ad3d\OrderAllocation\QcOrderAllocation', 'orderAllocation_id', 'allocation_id');
    }

    public function checkExistBonusWorkOfOrderAllocation($workId, $orderAllocationId)
    {
        return QcBonus::where('work_id', $workId)->where('orderAllocation_id', $orderAllocationId)->exists();
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
    public function selectInfoHasFilter($listWorkId, $dateFilter)
    {
        if (empty($dateFilter)) {
            return QcBonus::whereIn('work_id', $listWorkId)->orderBy('bonusDate', 'DESC')->select('*');
        } else {
            return QcBonus::where('bonusDate', 'like', "%$dateFilter%")->whereIn('work_id', $listWorkId)->orderBy('bonusDate', 'DESC')->select('*');
        }
    }

    public function totalMoneyHasFilter($listWorkId, $dateFilter)
    {
        if (empty($dateFilter)) {
            return QcBonus::where('cancelStatus', 0)->whereIn('work_id', $listWorkId)->sum('money');
        } else {
            return QcBonus::where('cancelStatus', 0)->where('bonusDate', 'like', "%$dateFilter%")->whereIn('work_id', $listWorkId)->sum('money');
        }
    }

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

    public function action($bonusId = null)
    {
        return $this->pluck('action', $bonusId);
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

    public function orderConstructionId($bonusId = null)
    {
        return $this->pluck('orderConstruction_id', $bonusId);
    }
    public function orderPayId($bonusId = null)
    {
        return $this->pluck('orderPay_id', $bonusId);
    }

    #========= ============= ============= ============= =============
    public function checkCancelStatus($bonusId = null)
    {
        return ($this->cancelStatus($bonusId) == 1) ? true : false;
    }

    public function checkEnableApply($bonusId = null)
    {
        if ($this->applyStatus($bonusId) == 1 && $this->cancelStatus() == 0) {
            return true; # ap dung phat
        } else {
            return false; # khong ap dung phat
        }
    }

}
