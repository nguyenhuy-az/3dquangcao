<?php

namespace App\Models\Ad3d\OrderPay;

use App\Models\Ad3d\Order\QcOrder;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\TransfersDetail\QcTransfersDetail;
use Illuminate\Database\Eloquent\Model;

class QcOrderPay extends Model
{
    protected $table = 'qc_order_pay';
    protected $fillable = ['pay_id', 'money', 'note', 'datePay', 'payerName', 'payerPhone', 'created_at', 'order_id', 'staff_id'];
    protected $primaryKey = 'pay_id';
    public $timestamps = false;

    private $lastId;

    #========== ========== ========== INSERT && UPDATE ========== ========== ==========
    #---------- Insert ----------
    public function insert($money, $note, $datePay, $orderId, $staffReceiveId, $payerName = null, $payerPhone = null)
    {
        $hFunction = new \Hfunction();
        $modelPay = new QcOrderPay();
        $modelPay->money = $money;
        $modelPay->note = $note;
        $modelPay->datePay = $datePay;
        $modelPay->payerName = $payerName;
        $modelPay->payerPhone = $payerPhone;
        $modelPay->order_id = $orderId;
        $modelPay->staff_id = $staffReceiveId;
        $modelPay->created_at = $hFunction->createdAt();
        if ($modelPay->save()) {
            $this->lastId = $modelPay->pay_id;
            return true;
        } else {
            return false;
        }
    }

    public function checkIdNull($payId)
    {
        return (empty($payId)) ? $this->payId() : $payId;
    }

    public function deleteOrderPay($payId)
    {
        $modelOrder = new QcOrder();
        $orderId = $this->orderId($payId);
        if (QcOrderPay::where('pay_id', $payId)->delete()) {
            $modelOrder->cancelFinishPayment($orderId);
        }
    }

    // cap nhat  thong tin don hang thanh toan
    public function updateInfo($payId, $money, $payerName, $payerPhone)
    {
        $hFunction = new \Hfunction();
        return QcOrderPay::where('pay_id', $payId)->update([
            'money' => $money,
            'payerName' => $payerName,
            'payerPhone' => $payerPhone
        ]);
    }
    #========== ========== ========== RELATION ========== ========== ==========
    //---------- NHAN VIEN -----------
    public function staff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'staff_id', 'staff_id');
    }

    //kiem tra thanh toan thuoc nhan vien
    public function checkStaffInput($staffId, $orderId = null)
    {
        $orderId = (empty($orderId)) ? $this->orderId() : $orderId;
        return (QcOrderPay::where('staff_id', $staffId)->where('order_id', $orderId)->count() > 0) ? true : false;
    }

    public function infoOfStaff($staffId, $date, $orderBy = 'DESC')
    {
        if (!empty($date)) {
            return QcOrderPay::where('staff_id', $staffId)->where('datePay', 'like', "%$date%")->orderBy('datePay', $orderBy)->get();
        } else {
            return QcOrderPay::where('staff_id', $staffId)->orderBy('datePay', $orderBy)->get();
        }

    }

    public function listOrderIdOfStaff($staffId, $date)
    {
        if (!empty($date)) {
            return QcOrderPay::where('staff_id', $staffId)->where('datePay', 'like', "%$date%")->groupBy('order_id')->pluck('order_id');
        } else {
            return QcOrderPay::where('staff_id', $staffId)->groupBy('order_id')->pluck('order_id');
        }
    }

    # cua 1 nhan vien
    public function totalMoneyOfStaffAndDate($staffId, $date)
    {
        if (!empty($date)) {
            return QcOrderPay::where('staff_id', $staffId)->where('datePay', 'like', "%$date%")->sum('money');
        } else {
            return QcOrderPay::where('staff_id', $staffId)->sum('money');
        }

    }
    # cua nhieu vien
    public function totalMoneyOfListStaffAndDate($staffId, $date)
    {
        if (!empty($date)) {
            return QcOrderPay::whereIn('staff_id', $staffId)->where('datePay', 'like', "%$date%")->sum('money');
        } else {
            return QcOrderPay::whereIn('staff_id', $staffId)->sum('money');
        }

    }

    public function infoNoTransferOfStaff($staffId, $date=null, $orderBy = 'DESC')
    {
        $modelTransferDetail = new QcTransfersDetail();
        $listPayId = $modelTransferDetail->listPayId();
        if (!empty($date)) {
            return QcOrderPay::where('staff_id', $staffId)->whereNotIn('pay_id', $listPayId)->where('datePay', 'like', "%$date%")->orderBy('datePay', $orderBy)->get();
        } else {
            return QcOrderPay::where('staff_id', $staffId)->whereNotIn('pay_id', $listPayId)->orderBy('datePay', $orderBy)->get();
        }

    }

    # tong tien chua ban giao cua nhan vien
    public function totalMoneyNotTransferOfStaff($staffId)
    {
        return QcOrderPay::whereIn('staff_id', $staffId)->sum('money');
    }

    //public function
    //---------- DON HANG -----------
    public function order()
    {
        return $this->belongsTo('App\Models\Ad3d\Order\QcOrder', 'order_id', 'order_id');
    }

    public function infoOfOrder($orderId)
    {
        return QcOrderPay::where('order_id', $orderId)->get();
    }

    public function totalPayOfOrder($orderId)
    {
        return QcOrderPay::where('order_id', $orderId)->sum('money');
    }

    public function totalPayOfOrderAndDate($orderId, $date=null)
    {
        if (!empty($date)) {
            return QcOrderPay::where('order_id', $orderId)->where('datePay', 'like', "%$date%")->sum('money');
        } else {
            return QcOrderPay::where('order_id', $orderId)->sum('money');
        }

    }

    //---------- chi tiet chuyen tien -----------
    public function transfersDetail()
    {
        return $this->hasOne('App\Models\Ad3d\TransfersDetail\QcTransfersDetail', 'pay_id', 'pay_id');
    }

    public function checkExistTransfersDetail($payId = null)
    {
        $modelTransfersDetail = new QcTransfersDetail();
        return (count($modelTransfersDetail->getInfoOfPay($this->checkIdNull($payId))) > 0) ? true : false;
    }

    #============ =========== ============ GET INFO ============= =========== ==========
    public function getInfo($payId = '', $field = '')
    {
        if (empty($payId)) {
            return QcOrderPay::get();
        } else {
            $result = QcOrderPay::where('pay_id', $payId)->first();
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
            return QcOrderPay::where('pay_id', $objectId)->pluck($column);
        }
    }

    public function payId()
    {
        return $this->pay_id;
    }

    public function money($payId = null)
    {
        return $this->pluck('money', $payId);
    }

    public function note($payId = null)
    {
        return $this->pluck('note', $payId);
    }

    public function datePay($payId = null)
    {
        return $this->pluck('datePay', $payId);
    }

    public function payerName($payId = null)
    {
        return $this->pluck('payerName', $payId);
    }

    public function payerPhone($payId = null)
    {
        return $this->pluck('payerPhone', $payId);
    }


    public function orderId($payId = null)
    {
        return $this->pluck('order_id', $payId);
    }

    public function staffId($payId = null)
    {
        return $this->pluck('staff_id', $payId);
    }

    public function createdAt($payId = null)
    {
        return $this->pluck('created_at', $payId);
    }

    #============ =========== ============ THONG KE ============= =========== ==========
    public function infoStaffOrderPay($listCompanyId, $dateFilter = null)
    {
        $modelOrder = new QcOrder();
        $listOrderId = $modelOrder->listIdOfListCompanyAndName($listCompanyId, null);
        if (empty($dateFilter)) {
            return QcOrderPay::whereIn('order_id', $listOrderId)->groupBy('staff_id')->pluck('staff_id');
        } else {
            return QcOrderPay::whereIn('order_id', $listOrderId)->where('datePay', 'like', "%$dateFilter%")->groupBy('staff_id')->pluck('staff_id');
        }
    }

    public function totalOrderPayOfCompany($listCompanyId, $dateFilter = null)
    {
        $modelOlder = new QcOrder();
        $listOrderId = $modelOlder->listIdOfListCompanyAndName($listCompanyId, null);
        if (empty($dateFilter)) {
            return QcOrderPay::whereIn('order_id', $listOrderId)->sum('money');
        } else {
            return QcOrderPay::whereIn('order_id', $listOrderId)->where('datePay', 'like', "%$dateFilter%")->sum('money');
        }
    }

    public function totalOrderPayOfCompanyStaffDate($listCompanyId, $staffId, $dateFilter = null)
    {
        $modelOlder = new QcOrder();
        $listOrderId = $modelOlder->listIdOfListCompanyAndName($listCompanyId, null);
        if (empty($dateFilter)) {
            return QcOrderPay::where('staff_id', $staffId)->whereIn('order_id', $listOrderId)->sum('money');
        } else {
            return QcOrderPay::where('staff_id', $staffId)->whereIn('order_id', $listOrderId)->where('datePay', 'like', "%$dateFilter%")->sum('money');
        }
    }


}
