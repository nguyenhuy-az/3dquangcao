<?php

namespace App\Models\Ad3d\MinusMoney;

use App\Models\Ad3d\PunishContent\QcPunishContent;
use Illuminate\Database\Eloquent\Model;

class QcMinusMoney extends Model
{
    protected $table = 'qc_minus_money';
    protected $fillable = ['minus_id', 'money', 'dateMinus', 'reason', 'created_at', 'work_id', 'staff_id', 'punish_id'];
    protected $primaryKey = 'minus_id';
    public $timestamps = false;

    private $lastId;

    #========== ========== ========== INSERT && UPDATE ========== ========== ==========
    #---------- Insert ----------
    public function insert($dateMinus, $reason, $workId, $staffId, $punishId)
    {
        $hFunction = new \Hfunction();
        $modelPunishContent = new QcPunishContent();
        $modelMinusMoney = new QcMinusMoney();
        $modelMinusMoney->money = $modelPunishContent->money($punishId)[0];
        $modelMinusMoney->dateMinus = $dateMinus;
        $modelMinusMoney->reason = $reason;
        $modelMinusMoney->work_id = $workId;
        $modelMinusMoney->staff_id = $staffId;
        $modelMinusMoney->punish_id = $punishId;
        $modelMinusMoney->created_at = $hFunction->createdAt();
        if ($modelMinusMoney->save()) {
            $this->lastId = $modelMinusMoney->minus_id;
            return true;
        } else {
            return false;
        }
    }

    public function updateInfo($minusId, $money, $datePay, $reason, $punishId)
    {
        return QcMinusMoney::where('minus_id', $minusId)->update(['money' => $money, 'dateMinus' => $datePay, 'reason' => $reason, 'punish_id' => $punishId]);
    }

    public function deleteInfo($minusId = null)
    {
        $minusId = (empty($minusId)) ? $this->payId() : $minusId;
        return QcMinusMoney::where('minus_id', $minusId)->delete();
    }

    //---------- nhân viên -----------
    public function staff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'staff_id', 'staff_id');
    }

    //kiển tra người nhập
    public function checkStaffInput($staffId, $minusId = null)
    {
        $minusId = (empty($minusId)) ? $this->minusId() : $minusId;
        return (QcMinusMoney::where('staff_id', $staffId)->where('minus_id', $minusId)->count() > 0) ? true : false;
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
        return QcMinusMoney::where('work_id', $workId)->sum('money');
    }

    //----------- lý do phạt ------------
    public function punishContent()
    {
        return $this->belongsTo('App\Models\Ad3d\PunishContent\QcPunishContent', 'punish_id', 'punish_id');
    }

    #============ =========== ============ GET INFO ============= =========== ==========
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
}
