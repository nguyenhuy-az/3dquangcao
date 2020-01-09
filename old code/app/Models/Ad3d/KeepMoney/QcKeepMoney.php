<?php

namespace App\Models\Ad3d\KeepMoney;

use Illuminate\Database\Eloquent\Model;

class QcKeepMoney extends Model
{
    protected $table = 'qc_keep_money';
    protected $fillable = ['keep_id', 'keepDate', 'money', 'description', 'created_at', 'cancelStatus', 'work_id', 'confirmStaff_id'];
    protected $primaryKey = 'keep_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- thêm ----------
    public function insert($money, $description, $workId, $confirmStaffId)
    {
        $hFunction = new \Hfunction();
        $modelKeepMoney = new QcKeepMoney();
        $modelKeepMoney->money = $money;
        $modelKeepMoney->description = $description;
        $modelKeepMoney->keepDate = $hFunction->carbonNow();
        $modelKeepMoney->work_id = $workId;
        $modelKeepMoney->confirmStaff_id = $confirmStaffId;
        $modelKeepMoney->created_at = $hFunction->createdAt();
        if ($modelKeepMoney->save()) {
            $this->lastId = $modelKeepMoney->keep_id;
            return true;
        } else {
            return false;
        }
    }

    public function insertGetId()
    {
        return $this->lastId;
    }

    public function checkIdNull($keepId)
    {
        return (empty($keepId)) ? $this->keepId() : $keepId;
    }

    public function cancelKeep($keepId = null)
    {
        return QcKeepMoney::where('keep_id', $this->checkIdNull($keepId))->update(['cancelStatus' => 1]);
    }
    //========== ========= ========= CAC MOI QUAN HE DU LIEU ========== ========= ==========
    //---------- lam viec-----------
    public function work()
    {
        return $this->belongsTo('App\Models\Ad3d\Work\QcWork', 'work_id', 'work_id');
    }

    //---------- NHAN VIEN XAC NHAN -----------
    # nha vien xac nhan
    public function confirmStaff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'confirmStaff_id', 'staff_id');
    }

    //========= ========== ========== LAY THONG TIN ========== ========== ==========
    public function getInfo($keepId = '', $field = '')
    {
        if (empty($keepId)) {
            return QcKeepMoney::get();
        } else {
            $result = QcKeepMoney::where('keep_id', $keepId)->first();
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
            return QcKeepMoney::where('keep_id', $objectId)->pluck($column);
        }
    }

    public function keepId()
    {
        return $this->keep_id;
    }

    public function money($keepId = null)
    {
        return $this->pluck('money', $keepId);
    }

    public function description($keepId = null)
    {
        return $this->pluck('description', $keepId);
    }

    public function cancelStatus($keepId = null)
    {

        return $this->pluck('cancelStatus', $keepId);
    }

    public function createdAt($keepId = null)
    {
        return $this->pluck('created_at', $keepId);
    }

    public function workId($keepId = null)
    {
        return $this->pluck('work_id', $keepId);
    }

    public function confirmStaffId($keepId = null)
    {
        return $this->pluck('confirmStaff_id', $keepId);
    }

    // last id
    public function lastId()
    {
        $result = QcKeepMoney::orderBy('keep_id', 'DESC')->first();
        return (empty($result)) ? 0 : $result->keep_id;
    }

}
