<?php

namespace App\Models\Ad3d\ProductCancel;

use Illuminate\Database\Eloquent\Model;

class QcProductCancel extends Model
{
    protected $table = 'qc_product_cancel';
    protected $fillable = ['cancel_id', 'reason', 'cancelDate', 'created_at', 'product_id', 'staff_id'];
    protected $primaryKey = 'cancel_id';
    public $timestamps = false;

    private $lastId;

    #========== ========== ========== THEM - SUA ========== ========== ==========
    #---------- Them ----------
    public function insert($reason, $productId, $staffId)
    {
        $hFunction = new \Hfunction();
        $modelProductCancel = new QcProductCancel();
        $modelProductCancel->reason = $reason;
        $modelProductCancel->cancelDate = $hFunction->carbonNow();
        $modelProductCancel->product_id = $productId;
        $modelProductCancel->staff_id = $staffId;
        $modelProductCancel->created_at = $hFunction->createdAt();
        if ($modelProductCancel->save()) {
            $this->lastId = $modelProductCancel->cancel_id;
            return true;
        } else {
            return false;
        }
    }

    public function checkIdNull($cancelId)
    {
        return (empty($cancelId)) ? $this->cancalId() : $cancelId;
    }

    #========== ========== ========== CAC MOI QUAN HE DU LIEU ========== ========== ==========
    //---------- NHAN VIEN -----------
    public function staff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'staff_id', 'staff_id');
    }

    public function infoOfStaff($staffId, $date, $orderBy = 'DESC')
    {
        if (!empty($date)) {
            return QcProductCancel::where('staff_id', $staffId)->where('cancelDate', 'like', "%$date%")->orderBy('cancelDate', $orderBy)->get();
        } else {
            return QcProductCancel::where('staff_id', $staffId)->orderBy('cancelDate', $orderBy)->get();
        }

    }

    public function listProductIdOfStaff($staffId, $date)
    {
        if (!empty($date)) {
            return QcProductCancel::where('staff_id', $staffId)->where('cancelDate', 'like', "%$date%")->groupBy('product_id')->pluck('product_id');
        } else {
            return QcProductCancel::where('staff_id', $staffId)->groupBy('product_id')->pluck('product_id');
        }
    }

    //---------- DON HANG -----------
    public function order()
    {
        return $this->belongsTo('App\Models\Ad3d\Product\QcProduct', 'product_id', 'product_id');
    }

    public function infoOfProduct($productId)
    {
        return QcProductCancel::where('product_id', $productId)->first();
    }

    #============ =========== ============ LAY THONG TIN ============= =========== ==========
    public function getInfo($cancelId = '', $field = '')
    {
        if (empty($cancelId)) {
            return QcProductCancel::get();
        } else {
            $result = QcProductCancel::where('cancel_id', $cancelId)->first();
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
            return QcProductCancel::where('cancel_id', $objectId)->pluck($column);
        }
    }

    public function cancelId()
    {
        return $this->cancel_id;
    }

    public function reason($cancelId = null)
    {
        return $this->pluck('reason', $cancelId);
    }

    public function cancelDate($cancelId = null)
    {
        return $this->pluck('cancelDate', $cancelId);
    }

    public function productId($cancelId = null)
    {
        return $this->pluck('product_id', $cancelId);
    }

    public function staffId($cancelId = null)
    {
        return $this->pluck('staff_id', $cancelId);
    }

    public function createdAt($cancelId = null)
    {
        return $this->pluck('created_at', $cancelId);
    }
}
