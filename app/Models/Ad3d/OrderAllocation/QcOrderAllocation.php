<?php

namespace App\Models\Ad3d\OrderAllocation;

use App\Models\Ad3d\Bonus\QcBonus;
use App\Models\Ad3d\Order\QcOrder;
use App\Models\Ad3d\Product\QcProduct;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\StaffNotify\QcStaffNotify;
use Illuminate\Database\Eloquent\Model;

class QcOrderAllocation extends Model
{
    protected $table = 'qc_order_allocation';
    protected $fillable = ['allocation_id', 'allocationDate', 'receiveStatus', 'receiveDeadline', 'noted', 'finishStatus', 'finishDate', 'finishNote', 'confirmStatus', 'confirmFinish', 'confirmDate', 'action', 'confirmStaff_id', 'order_id', 'allocationStaff_id', 'receiveStaff_id', 'created_at'];
    protected $primaryKey = 'allocation_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- them moi ----------
    public function insert($allocationDate, $receiveStatus, $receiveDeadline, $noted, $orderId, $receiveStaffId, $allocationStaffId = null)
    {
        $hFunction = new \Hfunction();
        $modelOrderAllocation = new QcOrderAllocation();
        $modelOrderAllocation->allocationDate = $allocationDate;
        $modelOrderAllocation->receiveStatus = $receiveStatus;
        $modelOrderAllocation->receiveDeadline = $receiveDeadline;
        $modelOrderAllocation->noted = $hFunction->convertValidHTML($noted);
        $modelOrderAllocation->order_id = $orderId;
        $modelOrderAllocation->allocationStaff_id = $allocationStaffId;
        $modelOrderAllocation->receiveStaff_id = $receiveStaffId;
        $modelOrderAllocation->created_at = $hFunction->createdAt();
        if ($modelOrderAllocation->save()) {
            $this->lastId = $modelOrderAllocation->allocation_id;
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

    public function checkNullId($allocationId = null)
    {
        return (empty($allocationId)) ? $this->allocationId() : $allocationId;
    }

    # huy ban giao
    public function cancel($allocationId = null)
    {
        return QcOrderAllocation::where('allocation_id', $this->checkNullId($allocationId))->update(['action' => 0]);
    }


    //========== ========= DUYET TU BAO CAO BAN GIAO CONG TRINH ========= ==========
    # bao cao hoan thanh cong trinh
    public function reportFinishAllocation($allocationId, $reportDate, $finishNote = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelStaffNotify = new QcStaffNotify();
        $modelBonus = new QcBonus();
        $modelProduct = new QcProduct();
        $modelOrder = new QcOrder();
        $orderId = $this->orderId($allocationId);
        $dataProduct = $modelOrder->productActivityOfOrder($orderId);
        if (QcOrderAllocation::where('allocation_id', $allocationId)->update(['confirmFinish' => 1, 'confirmDate' => $hFunction->carbonNow(), 'finishStatus' => 1, 'finishNote' => $finishNote, 'finishDate' => $reportDate])) {
            if (!$this->checkLate($allocationId)) {
                $receiveStaffId = $this->receiveStaffId($allocationId);
                $dataWork = $modelStaff->firstInfoActivityToWork($receiveStaffId);
                if ($hFunction->checkCount($dataWork)) {
                    $dataOrder = $modelOrder->getInfo($orderId);
                    $orderTotalPrice = $dataOrder->totalPrice();
                    # bonus price & minus price
                    if ($orderTotalPrice > 20) {
                        $orderBonusPrice = $orderTotalPrice * 0.05;
                    } else {
                        $orderBonusPrice = $orderTotalPrice * 0.03;
                    }
                    if ($modelBonus->insert($orderBonusPrice, $hFunction->carbonNow(), 'Thưởng hoàn thành đơn hàng', 0, $dataWork->workId(), $allocationId)) {
                        $bonusId = $modelBonus->insertGetId();
                        $receiveStaffId = (is_int($receiveStaffId))?$receiveStaffId:$receiveStaffId[0];
                        $modelStaffNotify->insert(null, $receiveStaffId, 'Thưởng hoàn thành đơn hàng', null, null, $bonusId);
                    }
                }
            }
            # bao ket thuc san pham
            if ($hFunction->checkCount($dataProduct)) {
                foreach ($dataProduct as $product) {
                    $modelProduct->confirmFinishFromFinishOrder($product->productId(), $this->receiveStaffId($allocationId)[0]);
                }
            }
        }
    }

    // ket thuc cong viec
    public function confirmFinishAllocation($allocationId, $confirmFinish, $confirmStaffId)
    {
        $hFunction = new \Hfunction();
        $modelOrder = new QcOrder();
        $allocationId = $this->checkNullId($allocationId);
        if (QcOrderAllocation::where('allocation_id', $allocationId)->update(
            [
                'confirmStatus' => 1,
                'confirmDate' => $hFunction->carbonNow(),
                'confirmFinish' => $confirmFinish,
                'confirmStaff_id' => $confirmStaffId,
                'action' => 0
            ])
        ) {
            if ($confirmFinish == 1) { # xac nhan dong y  hoan thanh
                $modelOrder->updateFinish($this->orderId($allocationId), 1, $confirmStaffId); // xac nhan hoan thanh don hang
            }
            return true;
        } else {
            return false;
        }

    }

    //========== ========= DUYET TU DON HANG  ========= ==========

    public function confirmFinishFromFinishOrder($orderId, $finishStatus, $confirmFinish, $staffReportConformId)
    {
        $hFunction = new \Hfunction();
        return QcOrderAllocation::where('order_id', $orderId)->where('action', 1)->update(
            [
                'finishStatus' => $finishStatus,
                'finishDate' => $hFunction->carbonNow(),
                'confirmStatus' => 1,
                'confirmDate' => $hFunction->carbonNow(),
                'confirmFinish' => $confirmFinish,
                'confirmStaff_id' => $staffReportConformId,
                'action' => 0
            ]);
    }

    //========== ========= ========= RELATION ========== ========= ==========
    //---------- thuong  -----------
    public function bonus()
    {
        return $this->hasMany('App\Models\Ad3d\Bonus\QcBonus', 'allocation_id', 'orderAllocation_id');
    }

    //---------- nhan vien ban giao -----------
    public function allocationStaff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'allocationStaff_id', 'staff_id');
    }

    //---------- nhan vien xac nhan -----------
    public function confirmStaff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'confirmStaff_id', 'staff_id');
    }

    //---------- thong bao ban giao don hang moi -----------
    public function staffNotify()
    {
        return $this->hasMany('App\Models\Ad3d\StaffNotify\QcStaffNotify', 'orderAllocation_id', 'allocation_id');
    }

    public function checkViewedNewOrderAllocation($orderAllocationId, $staffId)
    {
        $modelStaffAllocation = new QcStaffNotify();
        return $modelStaffAllocation->checkViewedOrderAllocationOfStaff($staffId, $orderAllocationId);
    }

    //---------- nhan vien nhan -----------
    public function receiveStaff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'receiveStaff_id', 'staff_id');
    }

    # kiem tra nhan vien da duoc phan cong cong trinh
    public function checkStaffReceiveOrder($staffId, $orderId)
    {
        return QcOrderAllocation::where('receiveStaff_id', $staffId)->where('order_id', $orderId)->exists();
    }

    # lay cong trinh cua NV nhan
    public function infoOfReceiveStaff($staffId, $date = null, $order = 'DESC')
    {
        if (!empty($date)) {
            return QcOrderAllocation::where(['receiveStaff_id' => $staffId])->where('allocationDate', 'like', "%$date%")->orderBy('allocationDate', $order)->get();
        } else {
            return QcOrderAllocation::where(['receiveStaff_id' => $staffId])->orderBy('allocationDate', $order)->get();
        }
    }

    # lay thong tin dang thi cong cua nhan vien
    public function infoActivityOfStaffReceive($receiveStaffId)
    {
        return QcOrderAllocation::where('receiveStaff_id', $receiveStaffId)->where('action', 1)->orderBy('receiveDeadline', 'ASC')->get();
    }

    # lay thong tin viec da ket thuc cua nhan vien cua nhan vien
    public function infoFinishOfStaffReceive($receiveStaffId)
    {
        return QcOrderAllocation::where('receiveStaff_id', $receiveStaffId)->where('action', 0)->orderBy('receiveDeadline', 'ASC')->get();
    }

    //---------- don hang -----------
    public function orders()
    {
        return $this->belongsTo('App\Models\Ad3d\Order\QcOrder', 'order_id', 'order_id');
    }

    # kiem tra sam pham dang duoc phan viec
    public function existInfoActivityOfOrder($orderId)
    {
        return QcOrderAllocation::where('order_id', $orderId)->where('action', 1)->exists();
    }

    public function infoActivityOfOrder($orderId)
    {
        return QcOrderAllocation::where('order_id', $orderId)->where('action', 1)->get();
    }

    public function infoAllOfOrder($orderId, $orderBy = 'DESC')
    {
        return QcOrderAllocation::where('order_id', $orderId)->orderBy('allocationDate', $orderBy)->get();
    }

    public function infoOfOrder($orderId)
    {
        return QcOrderAllocation::where('order_id', $orderId)->get();
    }

    //========= ========== ========== lay thong tin ========== ========== ==========
    public function getInfo($allocationId = '', $field = '')
    {
        if (empty($allocationId)) {
            return QcOrderAllocation::get();
        } else {
            $result = QcOrderAllocation::where('allocation_id', $allocationId)->first();
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
            return QcOrderAllocation::where('allocation_id', $objectId)->pluck($column);
        }
    }

    public function allocationId()
    {
        return $this->allocation_id;
    }

    public function allocationDate($allocationId = null)
    {
        return $this->pluck('allocationDate', $allocationId);
    }

    public function receiveStatus($allocationId = null)
    {
        return $this->pluck('receiveStatus', $allocationId);
    }


    public function receiveDeadline($allocationId = null)
    {

        return $this->pluck('receiveDeadline', $allocationId);
    }

    public function finishStatus($allocationId = null)
    {

        return $this->pluck('finishStatus', $allocationId);
    }

    public function finishDate($allocationId = null)
    {

        return $this->pluck('finishDate', $allocationId);
    }

    public function confirmStatus($allocationId = null)
    {

        return $this->pluck('confirmStatus', $allocationId);
    }

    public function confirmFinish($allocationId = null)
    {

        return $this->pluck('confirmStatus', $allocationId);
    }

    public function confirmDate($allocationId = null)
    {

        return $this->pluck('confirmDate', $allocationId);
    }


    public function noted($allocationId = null)
    {
        $hFunction = new \Hfunction();
        return $hFunction->htmlEntities($this->pluck('noted', $allocationId));
    }

    public function confirmStaffId($allocationId = null)
    {
        return $this->pluck('confirmStaff_id', $allocationId);
    }

    public function orderId($allocationId = null)
    {
        return $this->pluck('order_id', $allocationId);
    }

    public function allocationStaffId($allocationId = null)
    {
        return $this->pluck('allocationStaff_id', $allocationId);
    }

    public function receiveStaffId($allocationId = null)
    {
        return $this->pluck('receiveStaff_id', $allocationId);
    }

    public function action($allocationId = null)
    {
        return $this->pluck('action', $allocationId);
    }

    public function createdAt($allocationId = null)
    {
        return $this->pluck('created_at', $allocationId);
    }

// tong mau tin
    public function totalRecords()
    {
        return QcOrderAllocation::count();
    }

// id cuoi
    public function lastId()
    {
        $result = QcOrderAllocation::orderBy('allocation_id', 'DESC')->first();
        return (empty($result)) ? 0 : $result->allocation_id;
    }

    // kiem tra thong tin
    #con hoat dong
    public function checkActivity($allocationId = null)
    {
        $result = $this->action($allocationId);
        $result = is_int($result) ? $result : $result[0];
        return ($result == 1) ? true : false;
    }

    # kiem tra bao cao hoan thanh
    public function checkFinish($allocationId = null)
    {
        $result = $this->finishStatus($allocationId);
        $result = is_int($result) ? $result : $result[0];
        return ($result == 1) ? true : false;
    }

    # kiem tra xac nhan ket thuc giao viec
    public function checkConfirm($allocationId = null)
    {
        $result = $this->confirmStatus($allocationId);
        $result = is_int($result) ? $result : $result[0];
        return ($result == 1) ? true : false;
    }

    # he thong xac nhan da hoanh thanh
    public function checkConfirmFinish($allocationId = null)
    {
        $result = $this->confirmFinish($allocationId);
        $result = is_int($result) ? $result : $result[0];
        return ($result == 1) ? true : false;
    }

    # kiem tra huy ban giao
    public function checkCancelAllocation($allocationId = null)
    {
        if (!$this->checkActivity($allocationId) && !$this->checkFinish($allocationId)) { # da ngung hoat dong va chua ket thuc
            return true;
        } else {
            return false;
        }
    }

    #kiem tra phan viec bi tre
    public function checkLate($allocationId)
    {
        $hFunction = new \Hfunction();
        $checkDate = $hFunction->carbonNow();
        $lateStatus = false;
        $receiveDeadline = $this->receiveDeadline($allocationId);
        if (!$this->checkActivity($allocationId)) { # cong viec da xong
            if ($this->checkFinish()) { # co bao hoan thanh
                $finishDate = $this->finishDate($allocationId);
                if ($finishDate > $receiveDeadline[0]) $lateStatus = true;
            }
        } else { # don hang chưa ket thuc
            if ($checkDate > $receiveDeadline[0]) $lateStatus = true;
        }
        return $lateStatus;
    }
}
