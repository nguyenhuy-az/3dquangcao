<?php

namespace App\Models\Ad3d\ProductRepair;

use App\Models\Ad3d\ProductRepairFinish\QcProductRepairFinish;
use Illuminate\Database\Eloquent\Model;

class QcProductRepair extends Model
{
    protected $table = 'qc_product_repair';
    protected $fillable = ['repair_id', 'image','noted','finishStatus','finishDate','confirmStatus','confirmDate', 'action', 'created_at','product_id','notifyStaff_id', 'confirmStaff_id'];
    protected $primaryKey = 'repair_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- them moi ----------
    public function insert($image, $note, $productId, $notifyStaffId)
    {
        $hFunction = new \Hfunction();
        $modelProductRepair = new QcProductRepair();
        $modelProductRepair->image = $image;
        $modelProductRepair->note = $note;
        $modelProductRepair->product_id = $productId;
        $modelProductRepair->notifyStaff_id = $notifyStaffId;
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
    //========== ========= ========= RELATION ========== ========= ==========
    //---------- nhan vien bao sua -----------
    public function notifyStaff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'notifyStaff_id', 'staff_id');
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
