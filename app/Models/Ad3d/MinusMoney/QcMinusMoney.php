<?php

namespace App\Models\Ad3d\MinusMoney;

use App\Models\Ad3d\OrderAllocation\QcOrderAllocation;
use App\Models\Ad3d\PunishContent\QcPunishContent;
use App\Models\Ad3d\StaffNotify\QcStaffNotify;
use Illuminate\Database\Eloquent\Model;

class QcMinusMoney extends Model
{
    protected $table = 'qc_minus_money';
    protected $fillable = ['minus_id', 'money', 'dateMinus', 'reason', 'applyStatus', 'cancelStatus', '', 'created_at', 'work_id', 'staff_id', 'punish_id', 'orderAllocation_id'];
    protected $primaryKey = 'minus_id';
    public $timestamps = false;

    private $lastId;

    #========== ========== ========== INSERT && UPDATE ========== ========== ==========
    #---------- Insert ----------
    public function insert($dateMinus, $reason, $workId, $staffId = null, $punishId, $applyStatus = 1, $orderAllocationId = null)
    {
        $hFunction = new \Hfunction();
        $modelOrderAllocation = new QcOrderAllocation();
        $modelPunishContent = new QcPunishContent();
        $modelMinusMoney = new QcMinusMoney();
        $money = 0;
        if (empty($orderAllocationId)) {
            # tien dinh
            $money = $modelPunishContent->money($punishId)[0];
        } else {
            $dataOrderAllocation = $modelOrderAllocation->getInfo($orderAllocationId);
            $dataOrder = $dataOrderAllocation->orders;
            $money = (int)$dataOrder->getMinusMoneyOrderAllocationLate();
        }
        $modelMinusMoney->money = $money;
        $modelMinusMoney->dateMinus = $dateMinus;
        $modelMinusMoney->reason = $reason;
        $modelMinusMoney->applyStatus = $applyStatus;
        $modelMinusMoney->work_id = $workId;
        $modelMinusMoney->staff_id = $staffId;
        $modelMinusMoney->punish_id = $punishId;
        $modelMinusMoney->orderAllocation_id = $orderAllocationId;
        $modelMinusMoney->created_at = $hFunction->createdAt();
        if ($modelMinusMoney->save()) {
            $this->lastId = $modelMinusMoney->minus_id;
            return true;
        } else {
            return false;
        }
    }

    //lay id moi them
    public function insertGetId()
    {
        return $this->lastId;
    }

    public function checkNullId($minusId)
    {
        return (empty($minusId)) ? $this->minusId() : $minusId;
    }

    public function updateInfo($minusId, $money, $datePay, $reason, $punishId)
    {
        return QcMinusMoney::where('minus_id', $minusId)->update(['money' => $money, 'dateMinus' => $datePay, 'reason' => $reason, 'punish_id' => $punishId]);
    }

    public function cancelMinus($minusId = null)
    {
        return QcMinusMoney::where('minus_id', $this->checkNullId($minusId))->update(['cancelStatus'=> 1]);
    }


    public function deleteInfo($minusId = null)
    {
        return QcMinusMoney::where('minus_id', $this->checkNullId($minusId))->delete();
    }


    //---------- nhân viên -----------
    public function staff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'staff_id', 'staff_id');
    }

    //kiển tra người nhập
    public function checkStaffInput($staffId, $minusId = null)
    {
        return (QcMinusMoney::where('staff_id', $staffId)->where('minus_id', $this->checkNullId($minusId))->count() > 0) ? true : false;
    }

    //---------- thong bao ban giao don hang moi -----------
    public function staffNotify()
    {
        return $this->hasMany('App\Models\Ad3d\StaffNotify\QcStaffNotify', 'minusMoney_id', 'minusMoney_id');
    }

    public function checkViewedNewMinusMoney($minusMoneyId, $staffId)
    {
        $modelStaffAllocation = new QcStaffNotify();
        return $modelStaffAllocation->checkViewedMinusMoneyOfStaff($staffId, $minusMoneyId);
    }

    //----------- làm việc ------------
    public function work()
    {
        return $this->belongsTo('App\Models\Ad3d\Work\QcWork', 'work_id', 'work_id');
    }

    public function infoOfWork($workId)
    {
        return QcMinusMoney::where('work_id', $workId)->orderBy('dateMinus', 'DESC')->get();
    }

    public function totalMoneyOfWork($workId)
    {
        return QcMinusMoney::where('work_id', $workId)->where('cancelStatus', 0)->sum('money');
    }

    //---------- ban giao don hang -----------
    public function orderAllocation()
    {
        return $this->belongsTo('App\Models\Ad3d\OrderAllocation\QcOrderAllocation', 'orderAllocation_id', 'allocation_id');
    }

    public function checkExistMinusMoneyAllocationLate($orderAllocationId)
    {
        $modelPunishContent = new QcPunishContent();
        $punishId = $modelPunishContent->getPunishIdOfOrderAllocationLate();
        return QcMinusMoney::where('orderAllocation_id', $orderAllocationId)->where('punish_id', $punishId)->exists();
    }

    //----------- lý do phạt ------------
    public function punishContent()
    {
        return $this->belongsTo('App\Models\Ad3d\PunishContent\QcPunishContent', 'punish_id', 'punish_id');
    }

    #============ =========== ============ GET INFO ============= =========== ==========
    public function selectInfoHasFilter($listWorkId, $punishId, $dateFilter)
    {
        if (empty($punishId)) {
            return QcMinusMoney::where('dateMinus', 'like', "%$dateFilter%")->whereIn('work_id', $listWorkId)->orderBy('dateMinus', 'DESC')->select('*');
        } else {
            return QcMinusMoney::where('punish_id', $punishId)->where('dateMinus', 'like', "%$dateFilter%")->whereIn('work_id', $listWorkId)->orderBy('dateMinus', 'DESC')->select('*');
        }
    }

    public function totalMoneyHasFilter($listWorkId, $punishId, $dateFilter)
    {
        if (empty($punishId)) {
            return QcMinusMoney::where('cancelStatus', 0)->where('dateMinus', 'like', "%$dateFilter%")->whereIn('work_id', $listWorkId)->sum('money');
        } else {
            return QcMinusMoney::where('cancelStatus', 0)->where('punish_id', $punishId)->where('dateMinus', 'like', "%$dateFilter%")->whereIn('work_id', $listWorkId)->sum('money');
        }
    }

    public function getInfo($minusId = '', $field = '')
    {
        if (empty($minusId)) {
            return QcMinusMoney::get();
        } else {
            $result = QcMinusMoney::where('minus_id', $minusId)->first();
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
            return QcMinusMoney::where('minus_id', $objectId)->pluck($column);
        }
    }

    public function minusId()
    {
        return $this->minus_id;
    }

    public function money($minusId = null)
    {
        return $this->pluck('money', $minusId);
    }

    public function dateMinus($minusId = null)
    {
        return $this->pluck('dateMinus', $minusId);
    }

    public function reason($minusId = null)
    {
        return $this->pluck('reason', $minusId);
    }

    public function applyStatus($minusId = null)
    {
        return $this->pluck('applyStatus', $minusId);
    }


    public function cancelStatus($minusId = null)
    {
        return $this->pluck('cancelStatus', $minusId);
    }


    public function createdAt($minusId = null)
    {
        return $this->pluck('created_at', $minusId);
    }

    public function workId($minusId = null)
    {
        return $this->pluck('work_id', $minusId);
    }

    public function staffId($minusId = null)
    {
        return $this->pluck('staff_id', $minusId);
    }

    public function punishId($minusId = null)
    {
        return $this->pluck('punish_id', $minusId);
    }

    public function orderAllocationId($minusId = null)
    {
        return $this->pluck('orderAllocation_id', $minusId);
    }

    #========= ======
    public function checkCancelStatus($minusId = null)
    {
        return ($this->cancelStatus($minusId) == 1) ? true : false;
    }

    public function checkEnableApply($minusId = null)
    {
        if ($this->applyStatus($minusId) == 1 && $this->cancelStatus() == 0) {
            return true; # ap dung phat
        } else {
            return false; # khong ap dung phat
        }
    }
}
