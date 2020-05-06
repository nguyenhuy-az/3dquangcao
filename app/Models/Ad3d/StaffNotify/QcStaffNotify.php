<?php

namespace App\Models\Ad3d\StaffNotify;

use Illuminate\Database\Eloquent\Model;

class QcStaffNotify extends Model
{
    protected $table = 'qc_staff_notify';
    protected $fillable = ['notify_id', 'note', 'viewStatus', 'viewDate', 'created_at', 'order_id', 'orderAllocation_id', 'workAllocation_id', 'staff_id'];
    protected $primaryKey = 'notify_id';
    public $timestamps = false;

    private $lastId;

    #========== ========== ========== THEM - SUA ========== ========== ==========
    #---------- Them ----------
    public function insert($orderId, $staffId, $note = null, $orderAllocationId = null, $workAllocationId = null)
    {
        $hFunction = new \Hfunction();
        $modelStaffNotify = new QcStaffNotify();
        $modelStaffNotify->note = $note;
        $modelStaffNotify->order_id = $orderId;
        $modelStaffNotify->orderAllocation_id = $orderAllocationId;
        $modelStaffNotify->workAllocation_id = $workAllocationId;
        $modelStaffNotify->staff_id = $staffId;
        $modelStaffNotify->created_at = $hFunction->createdAt();
        if ($modelStaffNotify->save()) {
            $this->lastId = $modelStaffNotify->notify_id;
            return true;
        } else {
            return false;
        }
    }

    public function checkIdNull($notifyId)
    {
        return (empty($notifyId)) ? $this->cancalId() : $notifyId;
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
            return QcStaffNotify::where('staff_id', $staffId)->where('created_at', 'like', "%$date%")->orderBy('created_at', $orderBy)->get();
        } else {
            return QcStaffNotify::where('staff_id', $staffId)->orderBy('created_at', $orderBy)->get();
        }

    }

    public function selectInfoOfStaff($staffId)
    {
        return QcStaffNotify::where('staff_id', $staffId)->orderBy('created_at', 'DESC')->select('*');

    }

    public function listOrderIdOfStaff($staffId, $date)
    {
        if (!empty($date)) {
            return QcStaffNotify::where('staff_id', $staffId)->whereNotNull('order_id')->where('created_at', 'like', "%$date%")->groupBy('order_id')->pluck('order_id');
        } else {
            return QcStaffNotify::where('staff_id', $staffId)->whereNotNull('order_id')->groupBy('order_id')->pluck('order_id');
        }
    }

    #tong thong them don hang moi cua 1 nhan vien
    public function totalNotifyNewOrderOfStaff($staffId)
    {
        return QcStaffNotify::where('staff_id', $staffId)->whereNotNull('order_id')->where('viewStatus', 0)->count();
    }

    #tong thong báo moi cua 1 nhan vien
    public function totalNewNotifyOfStaff($staffId)
    {
        return QcStaffNotify::where('staff_id', $staffId)->where('viewStatus', 0)->count();
    }

    #cap nhat nhan vien da xem thong bao
    public function updateViewedAllNewOrderOfStaff($staffId)
    {
        $hFunction = new \Hfunction();
        return QcStaffNotify::where('staff_id', $staffId)->whereNotNull('order_id')->where('viewStatus', 0)->update(['viewStatus' => 1, 'viewDate' => $hFunction->carbonNow()]);
    }

    public function updateViewedOfStaffAndOrder($staffId, $orderId)
    {
        $hFunction = new \Hfunction();
        return QcStaffNotify::where('staff_id', $staffId)->where('order_id', $orderId)->update(['viewStatus' => 1, 'viewDate' => $hFunction->carbonNow()]);
    }

    public function checkViewedNewOrderOfStaff($staffId, $orderId)
    {
        if (QcStaffNotify::where('staff_id', $staffId)->where('order_id', $orderId)->exists()) {
            return QcStaffNotify::where('staff_id', $staffId)->where('order_id', $orderId)->where('viewStatus', 1)->exists();
        } else {
            return true;
        }
    }

    #tong thong bang giao don hang moi cua 1 nhan vien
    public function totalNotifyNewOrderAllocationOfStaff($staffId)
    {
        return QcStaffNotify::where('staff_id', $staffId)->whereNotNull('orderAllocation_id')->where('viewStatus', 0)->count();
    }

    #tong thong phan viec moi cua 1 nhan vien
    public function totalNotifyNewWorkAllocationOfStaff($staffId)
    {
        return QcStaffNotify::where('staff_id', $staffId)->whereNotNull('workAllocation_id')->where('viewStatus', 0)->count();
    }
    //---------- DON HANG -----------
    public function orders()
    {
        return $this->belongsTo('App\Models\Ad3d\Order\QcOrder', 'order_id', 'order_id');
    }

    public function infoOfOrder($orderId)
    {
        return QcStaffNotify::where('order_id', $orderId)->get();
    }

    //---------- ban giao don hang -----------
    public function orderAllocation()
    {
        return $this->belongsTo('App\Models\Ad3d\OrderAllocation\QcOrderAllocation', 'orderAllocation_id', 'allocation_id');
    }

    #cap nhat da xem thong bao giao don hang
    public function updateViewedOfStaffAndOrderAllocation($staffId, $orderAllocationId)
    {
        $hFunction = new \Hfunction();
        return QcStaffNotify::where('staff_id', $staffId)->where('orderAllocation_id', $orderAllocationId)->update(['viewStatus' => 1, 'viewDate' => $hFunction->carbonNow()]);
    }

    # kien tra da xem thong bao
    public function checkViewedOrderAllocationOfStaff($staffId, $orderAllocationId)
    {
        if (QcStaffNotify::where('staff_id', $staffId)->where('orderAllocation_id', $orderAllocationId)->exists()) {
            return QcStaffNotify::where('staff_id', $staffId)->where('orderAllocation_id', $orderAllocationId)->where('viewStatus', 1)->exists();
        } else {
            return true;
        }

    }

    //---------- ban giao thi cong san pham -----------
    public function workAllocation()
    {
        return $this->belongsTo('App\Models\Ad3d\WorkAllocation\QcWorkAllocation', 'workAllocation_id', 'allocation_id');
    }

    #============ =========== ============ LAY THONG TIN ============= =========== ==========
    public function getInfo($notifyId = '', $field = '')
    {
        if (empty($notifyId)) {
            return QcStaffNotify::get();
        } else {
            $result = QcStaffNotify::where('notify_id', $notifyId)->first();
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
            return QcStaffNotify::where('notify_id', $objectId)->pluck($column);
        }
    }

    public function notifyId()
    {
        return $this->notify_id;
    }

    public function viewStatus($notifyId = null)
    {
        return $this->pluck('viewStatus', $notifyId);
    }

    public function note($notifyId = null)
    {
        return $this->pluck('note', $notifyId);
    }

    public function viewDate($notifyId = null)
    {
        return $this->pluck('viewDate', $notifyId);
    }

    public function orderId($notifyId = null)
    {
        return $this->pluck('order_id', $notifyId);
    }

    public function orderAllocationId($notifyId = null)
    {
        return $this->pluck('orderAllocation_id', $notifyId);
    }

    public function workAllocationId($notifyId = null)
    {
        return $this->pluck('workAllocation_id', $notifyId);
    }

    public function staffId($notifyId = null)
    {
        return $this->pluck('staff_id', $notifyId);
    }

    public function createdAt($notifyId = null)
    {
        return $this->pluck('created_at', $notifyId);
    }

    public function checkNewInfo($notifyId = null)
    {
        return ($this->viewStatus($notifyId) == 1) ? false : true;
    }
}
