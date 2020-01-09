<?php

namespace App\Models\Ad3d\ProductRepairFinish;

use Illuminate\Database\Eloquent\Model;

class QcProductRepairFinish extends Model
{
    protected $table = 'qc_product_repair_finish';
    protected $fillable = ['finish_id', 'finishDate', 'finishStatus', 'finishLevel','noted', 'created_at', 'repair_id'];
    protected $primaryKey = 'finish_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- thêm ----------
    public function insert($finishDate, $finishStatus, $finishLevel, $noted, $repairId)
    {
        $hFunction = new \Hfunction();
        $modelProductRepairFinish = new QcProductRepairFinish();
        $modelProductRepairFinish->finishDate = $finishDate;
        $modelProductRepairFinish->finishStatus = $finishStatus; # trang thai khi ket thuc 0 - hoan thanh / 1 - khong hoan thanh
        $modelProductRepairFinish->finishLevel = $finishLevel; # muc do hoan thanh # 0 - dung han / 1 - som / 2 tre
        $modelProductRepairFinish->noted = $noted;
        $modelProductRepairFinish->repair_id = $repairId;
        $modelProductRepairFinish->created_at = $hFunction->createdAt();
        if ($modelProductRepairFinish->save()) {
            $this->lastId = $modelProductRepairFinish->finish_id;
            return true;
        } else {
            return false;
        }
    }

    public function insertGetId()
    {
        return $this->lastId;
    }

    //========== ========= ========= RELATION ========== ========= ==========
    //---------- phan viec -----------
    public function productRepair()
    {
        return $this->belongsTo('App\Models\Ad3d\ProductRepair\QcProductRepair', 'repair_id', 'repair_id');
    }

    public function infoOfRepair($repairId)
    {
        return QcProductRepairFinish::where('repair_id', $repairId)->get();
    }

    //========= ========== ========== l?y thông tin ========== ========== ==========
    public function getInfo($finishId = '', $field = '')
    {
        if (empty($finishId)) {
            return QcProductRepairFinish::get();
        } else {
            $result = QcProductRepairFinish::where('finish_id', $finishId)->first();
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
            return QcProductRepairFinish::where('finish_id', $objectId)->pluck($column);
        }
    }

    public function finishId()
    {
        return $this->finish_id;
    }

    public function finishDate($finishId = null)
    {
        return $this->pluck('finishDate', $finishId);
    }

    public function finishStatus($finishId = null)
    {
        return $this->pluck('finishStatus', $finishId);
    }

    public function createdAt($finishId = null)
    {
        return $this->pluck('created_at', $finishId);
    }

    public function finishLevel($finishId = null)
    {
        return $this->pluck('finishLevel', $finishId);
    }

    public function noted($finishId = null)
    {
        return $this->pluck('noted', $finishId);
    }

    public function repairId($finishId = null)
    {
        return $this->pluck('repair_id', $finishId);
    }

    // last id
    public function lastId()
    {
        $result = QcProductRepairFinish::orderBy('finish_id', 'DESC')->first();
        return (empty($result)) ? 0 : $result->finish_id;
    }

    public function checkFinishStatus($finishId = null)
    {
        return ($this->finishStatus($finishId) == 0) ? false : true;
    }

    public function checkFinishSoon($finishId = null)
    {
        return ($this->finishLevel($finishId) == 1) ? true : false;
    }

    public function checkFinishLate($finishId = null)
    {
        return ($this->finishLevel($finishId) == 2) ? true : false;
    }
}
