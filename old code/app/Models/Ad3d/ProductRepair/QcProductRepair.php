<?php

namespace App\Models\Ad3d\ProductRepair;

use App\Models\Ad3d\ProductRepairFinish\QcProductRepairFinish;
use Illuminate\Database\Eloquent\Model;

class QcProductRepair extends Model
{
    protected $table = 'qc_product_repair';
    protected $fillable = ['repair_id', 'beginDate', 'deadline', 'repairType','noted', 'action', 'product_id', 'allocationStaff_id', 'created_at'];
    protected $primaryKey = 'repair_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- them moi ----------
    public function insert($beginDate, $deadline, $repairType, $noted, $productId, $allocationStaffId)
    {
        $hFunction = new \Hfunction();
        $modelProductRepair = new QcProductRepair();
        $modelProductRepair->beginDate = $beginDate;
        $modelProductRepair->deadline = $deadline;
        $modelProductRepair->repairType = $repairType;
        $modelProductRepair->noted = $noted;
        $modelProductRepair->product_id = $productId;
        $modelProductRepair->allocationStaff_id = $allocationStaffId;
        $modelProductRepair->created_at = $hFunction->createdAt();
        if ($modelProductRepair->save()) {
            $this->lastId = $modelProductRepair->repair_id;
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

    // ket thuc cong viec
    public function confirmFinish($repairId, $reportDate, $finishStatus, $finishReason = 0)
    {
        $hFunction = new \Hfunction();
        $modelProductRepairFinish = new QcProductRepairFinish();
        $repairId = (empty($repairId)) ? $this->allocationId() : $repairId;
        /*if (QcProductRepair::where('repair_id', $repairId)->update(['action' => 0])) {
            $receiveDate = $this->receiveDeadline($repairId)[0];
            if (date('Y-m-d H:i', strtotime($reportDate)) > date('Y-m-d H:i', strtotime($receiveDate))) {
                # hoan thanh tre
                $finishLevel = 2;
            } elseif (date('Y-m-d H:i', strtotime($reportDate)) == date('Y-m-d H:i', strtotime($receiveDate))) {
                $finishLevel = 0;
            } else {
                $finishLevel = 1;
            }
            $modelProductRepairFinish->insert($reportDate, $finishStatus, $finishLevel, $finishReason, '', $repairId);
        }*/
    }
    //========== ========= ========= RELATION ========== ========= ==========
    //---------- nhân viên bàn giao -----------
    public function allocationStaff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'allocationStaff_id', 'staff_id');
    }
//---------- san pham -----------
    public function product()
    {
        return $this->belongsTo('App\Models\Ad3d\Product\QcProduct', 'product_id', 'product_id');
    }

    # kiem tra sam pham dang duoc phan viec
    public function existInfoActivityOfProduct($productId)
    {
        return QcProductRepair::where('product_id', $productId)->exists();
    }

    public function infoActivityOfProduct($productId)
    {
        return QcProductRepair::where('product_id', $productId)->where('action', 1)->get();
    }

    public function infoOfProduct($productId)
    {
        return QcProductRepair::where('product_id', $productId)->get();
    }


    //---------- ket thuc cong viec -----------
    public function productRepairFinish()
    {
        return $this->hasMany('App\Models\Ad3d\ProductRepairFinish\QcProductRepairFinish', 'repair_id', 'repair_id');
    }

    public function productRepairFinishInfo($repairId = null)
    {
        $modelProductRepairFinish = new QcProductRepairFinish();
        return $modelProductRepairFinish->infoOfRepair((empty($repairId)) ? $this->repairId() : $repairId);
    }

    //========= ========== ========== lay thong tin ========== ========== ==========
    public function getInfo($repairId = '', $field = '')
    {
        if (empty($repairId)) {
            return QcProductRepair::get();
        } else {
            $result = QcProductRepair::where('repair_id', $repairId)->first();
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
            return QcProductRepair::where('repair_id', $objectId)->pluck($column);
        }
    }

    public function repairId()
    {
        return $this->repair_id;
    }

    public function beginDate($repairId = null)
    {
        return $this->pluck('beginDate', $repairId);
    }

    public function deadline($repairId = null)
    {
        return $this->pluck('deadline', $repairId);
    }


    public function repairType($repairId = null)
    {

        return $this->pluck('repairType', $repairId);
    }

    public function noted($repairId = null)
    {

        return $this->pluck('noted', $repairId);
    }

    public function productId($repairId = null)
    {
        return $this->pluck('product_id', $repairId);
    }

    public function allocationStaffId($repairId = null)
    {
        return $this->pluck('allocationStaff_id', $repairId);
    }

    public function action($repairId = null)
    {
        return $this->pluck('action', $repairId);
    }

    public function createdAt($repairId = null)
    {
        return $this->pluck('created_at', $repairId);
    }

// tong mau tin
    public function totalRecords()
    {
        return QcProductRepair::count();
    }

// id cuoi
    public function lastId()
    {
        $result = QcProductRepair::orderBy('repair_id', 'DESC')->first();
        return (empty($result)) ? 0 : $result->repair_id;
    }

    // kiem tra thong tin
    #con hoat dong
    public function checkActivity($repairId = null)
    {
        return ($this->action($repairId) == 1) ? true : false;
    }
}
