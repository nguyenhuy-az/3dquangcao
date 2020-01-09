<?php

namespace App\Models\Ad3d\OrderCancel;

use Illuminate\Database\Eloquent\Model;

class QcOrderCancel extends Model
{
    protected $table = 'qc_order_cancel';
    protected $fillable = ['cancel_id', 'payment', 'reason', 'cancelDate', 'created_at', 'order_id', 'staff_id'];
    protected $primaryKey = 'cancel_id';
    public $timestamps = false;

    private $lastId;

    #========== ========== ========== THEM - SUA ========== ========== ==========
    #---------- Them ----------
    public function insert($payment, $reason, $orderId, $staffId)
    {
        $hFunction = new \Hfunction();
        $modelOrderCancel = new QcOrderCancel();
        $modelOrderCancel->payment = $payment;
        $modelOrderCancel->reason = $reason;
        $modelOrderCancel->cancelDate = $hFunction->carbonNow();
        $modelOrderCancel->order_id = $orderId;
        $modelOrderCancel->staff_id = $staffId;
        $modelOrderCancel->created_at = $hFunction->createdAt();
        if ($modelOrderCancel->save()) {
            $this->lastId = $modelOrderCancel->cancel_id;
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
            return QcOrderCancel::where('staff_id', $staffId)->where('cancelDate', 'like', "%$date%")->orderBy('cancelDate', $orderBy)->get();
        } else {
            return QcOrderCancel::where('staff_id', $staffId)->orderBy('cancelDate', $orderBy)->get();
        }

    }

    public function listOrderIdOfStaff($staffId, $date)
    {
        if (!empty($date)) {
            return QcOrderCancel::where('staff_id', $staffId)->where('cancelDate', 'like', "%$date%")->groupBy('order_id')->pluck('order_id');
        } else {
            return QcOrderCancel::where('staff_id', $staffId)->groupBy('order_id')->pluck('order_id');
        }
    }

    #tong tien thanh toan cua 1 nhan vien
    public function totalPaymentOfStaffAndDate($staffId, $date = null)
    {
        if (!empty($date)) {
            return QcOrderCancel::where('staff_id', $staffId)->where('cancelDate', 'like', "%$date%")->sum('payment');
        } else {
            return QcOrderCancel::where('staff_id', $staffId)->sum('payment');
        }

    }

    # cua nhieu vien
    public function totalPaymentOfListStaffAndDate($staffId, $date)
    {
        if (!empty($date)) {
            return QcOrderCancel::whereIn('staff_id', $staffId)->where('cancelDate', 'like', "%$date%")->sum('payment');
        } else {
            return QcOrderCancel::whereIn('staff_id', $staffId)->sum('payment');
        }

    }

    //---------- DON HANG -----------
    public function order()
    {
        return $this->belongsTo('App\Models\Ad3d\Order\QcOrder', 'order_id', 'order_id');
    }

    public function infoOfOrder($orderId)
    {
        return QcOrderCancel::where('order_id', $orderId)->first();
    }

    public function totalPayOfOrder($orderId)
    {
        return QcOrderCancel::where('order_id', $orderId)->sum('payment');
    }

    public function totalPayOfOrderAndDate($orderId, $date = null)
    {
        if (!empty($date)) {
            return QcOrderCancel::where('order_id', $orderId)->where('cancelDate', 'like', "%$date%")->sum('payment');
        } else {
            return QcOrderCancel::where('order_id', $orderId)->sum('payment');
        }

    }

    #============ =========== ============ LAY THONG TIN ============= =========== ==========
    public function getInfo($cancelId = '', $field = '')
    {
        if (empty($cancelId)) {
            return QcOrderCancel::get();
        } else {
            $result = QcOrderCancel::where('cancel_id', $cancelId)->first();
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
            return QcOrderCancel::where('cancel_id', $objectId)->pluck($column);
        }
    }

    public function cancelId()
    {
        return $this->cancel_id;
    }

    public function payment($cancelId = null)
    {
        return $this->pluck('payment', $cancelId);
    }

    public function reason($cancelId = null)
    {
        return $this->pluck('reason', $cancelId);
    }

    public function cancelDate($cancelId = null)
    {
        return $this->pluck('cancelDate', $cancelId);
    }

    public function orderId($cancelId = null)
    {
        return $this->pluck('order_id', $cancelId);
    }

    public function staffId($cancelId = null)
    {
        return $this->pluck('staff_id', $cancelId);
    }

    public function createdAt($cancelId = null)
    {
        return $this->pluck('created_at', $cancelId);
    }

    #============ =========== ============ THONG KE ============= =========== ==========
    public function totalPaymentOfCompany($listCompanyId, $dateFilter = null)
    {
        $modelOlder = new QcOrder();
        $listOrderId = $modelOlder->listIdOfListCompanyAndName($listCompanyId, null);
        if (empty($dateFilter)) {
            return QcOrderCancel::whereIn('order_id', $listOrderId)->sum('payment');
        } else {
            return QcOrderCancel::whereIn('order_id', $listOrderId)->where('cancelDate', 'like', "%$dateFilter%")->sum('payment');
        }
    }

    public function totalPaymentOfCompanyStaffDate($listCompanyId, $staffId, $dateFilter = null)
    {
        $modelOlder = new QcOrder();
        $listOrderId = $modelOlder->listIdOfListCompanyAndName($listCompanyId, null);
        if (empty($dateFilter)) {
            return QcOrderCancel::where('staff_id', $staffId)->whereIn('order_id', $listOrderId)->sum('payment');
        } else {
            return QcOrderCancel::where('staff_id', $staffId)->whereIn('order_id', $listOrderId)->where('cancelDate', 'like', "%$dateFilter%")->sum('payment');
        }
    }
}
