<?php

namespace App\Models\Ad3d\OrderPay;

use App\Models\Ad3d\Bonus\QcBonus;
use App\Models\Ad3d\Order\QcOrder;
use App\Models\Ad3d\OrderBonusBudget\QcOrderBonusBudget;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\StaffNotify\QcStaffNotify;
use App\Models\Ad3d\TransfersDetail\QcTransfersDetail;
use Illuminate\Database\Eloquent\Model;

class QcOrderPay extends Model
{
    protected $table = 'qc_order_pay';
    protected $fillable = ['pay_id', 'money', 'note', 'datePay', 'payerName', 'payerPhone', 'created_at', 'order_id', 'staff_id'];
    protected $primaryKey = 'pay_id';
    public $timestamps = false;

    private $lastId;

    #mac dinh ghi chu
    public function getDefaultNote()
    {
        return null;
    }

    #mac dinh ten nguoi thanh toan
    public function getDefaultPayerName()
    {
        return null;
    }

    #mac dinh so dien thoai
    public function getDefaultPayerPhone()
    {
        return null;
    }

    # mac dinh da giao
    public function getDefaultHasTransfers()
    {
        return 1;
    }

    # mac dinh chua giao
    public function getDefaultNotTransfers()
    {
        return 0;
    }

    # mac dinh tat ca da giao / chua giao
    public function getDefaultAllTransfers()
    {
        return 100;
    }
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
            $newId = $modelPay->pay_id;
            $this->lastId = $newId;
            # cac hanh dong sau thanh toan
            $this->actionAfterInsert($newId);
            return true;
        } else {
            return false;
        }
    }

    # hanh sau khi them thanh toan
    public function actionAfterInsert($payId)
    {
        $modelOrders = new QcOrder();
        # xet thuong khi thu tien don hang cua bo phan kinh doanh
        $this->applyBonusDepartmentBusiness($payId);
        # cap nhat thong tin thanh toan don hang
        $modelOrders->updateFinishPayment($this->orderId($payId));
    }

    public function insertGetId()
    {
        return $this->lastId;
    }

    public function checkIdNull($payId = null)
    {
        $hFunction = new \Hfunction();
        return ($hFunction->checkEmpty($payId)) ? $this->payId() : $payId;
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
    public function checkOwnerStatusOfStaff($staffId, $payId)
    {
        return QcOrderPay::where('staff_id', $staffId)->where('pay_id', $payId)->exists();
    }

    //kiem tra thanh toan thuoc nhan vien
    public function checkStaffInput($staffId, $payId = null)
    {
        return QcOrderPay::where('staff_id', $staffId)->where('pay_id', $this->checkIdNull($payId))->exists();
    }

    public function infoOfStaff($staffId, $date, $orderBy = 'DESC')
    {
        $hFunction = new \Hfunction();
        if (!$hFunction->checkEmpty($date)) {
            return QcOrderPay::where('staff_id', $staffId)->where('datePay', 'like', "%$date%")->orderBy('datePay', $orderBy)->get();
        } else {
            return QcOrderPay::where('staff_id', $staffId)->orderBy('datePay', $orderBy)->get();
        }

    }

    # danh sach ma thanh toan cua 1 nv
    public function listOrderIdOfStaff($staffId, $date)
    {
        $hFunction = new \Hfunction();
        if (!$hFunction->checkEmpty($date)) {
            return QcOrderPay::where('staff_id', $staffId)->where('datePay', 'like', "%$date%")->groupBy('order_id')->pluck('order_id');
        } else {
            return QcOrderPay::where('staff_id', $staffId)->groupBy('order_id')->pluck('order_id');
        }
    }

    # danh sach ma thanh toan cua 1 nv hoac nhieu nv
    public function listOrderIdOfListStaff($listStaffId, $date)
    {
        $hFunction = new \Hfunction();
        if (!$hFunction->checkEmpty($date)) {
            return QcOrderPay::whereIn('staff_id', $listStaffId)->where('datePay', 'like', "%$date%")->groupBy('order_id')->pluck('order_id');
        } else {
            return QcOrderPay::whereIn('staff_id', $listStaffId)->groupBy('order_id')->pluck('order_id');
        }
    }

    # cua 1 nhan vien
    public function totalMoneyOfStaffAndDate($staffId, $date)
    {
        $hFunction = new \Hfunction();
        if (!$hFunction->checkEmpty($date)) {
            return QcOrderPay::where('staff_id', $staffId)->where('datePay', 'like', "%$date%")->sum('money');
        } else {
            return QcOrderPay::where('staff_id', $staffId)->sum('money');
        }

    }

    # cua nhieu vien
    public function totalMoneyOfListStaffAndDate($staffId, $date)
    {
        $hFunction = new \Hfunction();
        if (!$hFunction->checkEmpty($date)) {
            return QcOrderPay::whereIn('staff_id', $staffId)->where('datePay', 'like', "%$date%")->sum('money');
        } else {
            return QcOrderPay::whereIn('staff_id', $staffId)->sum('money');
        }

    }

    public function infoNoTransferOfStaff($staffId, $date = null, $orderBy = 'DESC')
    {
        $hFunction = new \Hfunction();
        $modelTransferDetail = new QcTransfersDetail();
        $listPayId = $modelTransferDetail->listPayId();
        if (!$hFunction->checkEmpty($date)) {
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

    //---------- thuong don hang -----------
    public function bonus()
    {
        return $this->hasMany('App\Models\Ad3d\Bonus\QcBonus', 'pay_id', 'pay_id');
    }

    // ---------- --------- XET THUONG KHI THU TIEN DON HANG ----------- -----------
    # xet thuong cho bo phan kinh doanh - tat ca cac cap bac
    public function applyBonusDepartmentBusiness($payId)
    {
        # cap quan ly
        $this->applyBonusDepartmentBusinessManage($payId);
        # cap nhan vien
        $this->applyBonusDepartmentBusinessStaff($payId);
    }

    # xet thuong cho bo phan kinh doanh - cap quan ly
    public function applyBonusDepartmentBusinessManage($payId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelStaffNotify = new QcStaffNotify();
        $modelBonus = new QcBonus();
        $dataOrderPay = $this->getInfo($payId);
        if ($hFunction->checkCount($dataOrderPay)) {
            $dataOrder = $dataOrderPay->order;
            # lay tien thương tren 1 lan thanh toan
            $bonusMoney = $this->getBonusMoneyOfBusinessManage($payId);
            if ($bonusMoney > 0) {
                # CAP QUAN LY - lay danh sach NV kinh doanh cap quan ly
                $dataStaffBusiness = $modelStaff->infoActivityStaffBusinessRankManage($dataOrder->companyId());
                if ($hFunction->checkCount($dataStaffBusiness)) {
                    # lay gia trin mac dinh thuong
                    $bonusNote = 'Thưởng Quản lý kinh doanh nhận tiền từ đơn hàng';
                    $bonusHasApply = $modelBonus->getDefaultHasApply();
                    $bonusOrderAllocationId = $modelBonus->getDefaultOrderAllocationId();
                    $bonusOrderConstructionId = $modelBonus->getDefaultOrderConstructionId();
                    $bonusWorkAllocationId = $modelBonus->getDefaultWorkAllocationId();
                    foreach ($dataStaffBusiness as $staffBusiness) {
                        $dataWork = $staffBusiness->workInfoActivityOfStaff();
                        if ($hFunction->checkCount($dataWork)) {
                            $workId = $dataWork->workId();
                            # kiem tra da duoc thuong chua - neu chua thi thuong
                            if (!$modelBonus->checkOrderPayBonus($workId, $payId)) {
                                if ($modelBonus->insert($bonusMoney, $hFunction->carbonNow(), $bonusNote, $bonusHasApply, $workId, $bonusOrderAllocationId, $bonusOrderConstructionId, $payId, $bonusWorkAllocationId)) {
                                    $bonusId = $modelBonus->insertGetId();
                                    $notifyStaffId = $staffBusiness->staffId();
                                    ///$notifyStaffId = (is_int($notifyStaffId)) ? $notifyStaffId : $notifyStaffId[0];
                                    # thong bao cho nguoi nhan thuong
                                    # lay gia tri mac dinh
                                    $notifyOrderId = $modelStaffNotify->getDefaultOrderId();
                                    $orderAllocationId = $modelStaffNotify->getDefaultOrderAllocationId();
                                    $workAllocationId = $modelStaffNotify->getDefaultWorkAllocationId();
                                    $minusId = $modelStaffNotify->getDefaultMinusId();
                                    $orderAllocationFinishId = $modelStaffNotify->getDefaultOrderAllocationFinishId();
                                    $notifyNote = 'Thưởng Quản lý kinh doanh nhận tiền từ đơn hàng';
                                    $modelStaffNotify->insert($notifyOrderId, $notifyStaffId, $notifyNote, $orderAllocationId, $workAllocationId, $bonusId, $minusId, $orderAllocationFinishId);
                                }
                            }
                        }
                    }
                }

            }
        }
    }

    # xet thuong cho bo phan kinh doanh - cap nhan vien
    public function applyBonusDepartmentBusinessStaff($payId)
    {
        $hFunction = new \Hfunction();
        $modelStaffNotify = new QcStaffNotify();
        $modelBonus = new QcBonus();
        $dataOrderPay = $this->getInfo($payId);
        if ($hFunction->checkCount($dataOrderPay)) {
            $dataOrder = $dataOrderPay->order;
            # thong tin nhan vien tao don hang
            $dataStaffCreated = $dataOrder->staffReceive;
            #NGUOI NHAN DON HANG - thuong cho nguoi nhan don hang
            if ($hFunction->checkCount($dataStaffCreated)) {
                $bonusMoney = $this->getBonusMoneyOfBusinessStaff($payId);
                if ($bonusMoney > 0) {
                    $dataWork = $dataStaffCreated->workInfoActivityOfStaff();
                    if ($hFunction->checkCount($dataWork)) {
                        $workId = $dataWork->workId();
                        # kiem tra da duoc thuong chua - neu chua thi thuong
                        if (!$modelBonus->checkOrderPayBonus($workId, $payId)) {
                            # lay gia trin mac dinh thuong
                            $bonusNote = 'Thưởng Quản lý kinh doanh nhận tiền từ đơn hàng';
                            $bonusHasApply = $modelBonus->getDefaultHasApply();
                            $bonusOrderAllocationId = $modelBonus->getDefaultOrderAllocationId();
                            $bonusOrderConstructionId = $modelBonus->getDefaultOrderConstructionId();
                            $bonusWorkAllocationId = $modelBonus->getDefaultWorkAllocationId();
                            if ($modelBonus->insert($bonusMoney, $hFunction->carbonNow(), $bonusNote, $bonusHasApply, $workId, $bonusOrderAllocationId, $bonusOrderConstructionId, $payId, $bonusWorkAllocationId)) {
                                $bonusId = $modelBonus->insertGetId();
                                $notifyStaffId = $dataStaffCreated->staffId();
                                //$notifyStaffId = (is_int($notifyStaffId)) ? $notifyStaffId : $notifyStaffId[0];
                                # thong bao cho nguoi nhan thuong
                                # lay gia tri mac dinh
                                $notifyOrderId = $modelStaffNotify->getDefaultOrderId();
                                $orderAllocationId = $modelStaffNotify->getDefaultOrderAllocationId();
                                $workAllocationId = $modelStaffNotify->getDefaultWorkAllocationId();
                                $minusId = $modelStaffNotify->getDefaultMinusId();
                                $orderAllocationFinishId = $modelStaffNotify->getDefaultOrderAllocationFinishId();
                                $notifyNote = 'Thưởng Nhân viên kinh doanh nhận tiền đơn hàng';
                                $modelStaffNotify->insert($notifyOrderId, $notifyStaffId, $notifyNote, $orderAllocationId, $workAllocationId, $bonusId, $minusId, $orderAllocationFinishId);
                            }
                        }
                    }
                }
            }
        }
    }

    # lay gia tri tien thuong cua bo phan kinh doanh cap quan ly - tren 1 lan thu
    public function getBonusMoneyOfBusinessManage($payId)
    {
        $modelOrderBonusBudget = new QcOrderBonusBudget();
        $dataOrderPay = $this->getInfo($payId);
        $dataOrder = $dataOrderPay->order;
        $bonusPercent = $modelOrderBonusBudget->getPercentOfBusinessManage($dataOrder->orderId());
        if ($bonusPercent > 0) {
            $moneyPay = $dataOrderPay->money();
            //$moneyPay = (is_array($moneyPay)) ? $moneyPay[0] : $moneyPay;
            return (int)($moneyPay * ($bonusPercent / 100));
        } else {
            return 0;
        }
    }

    # lay gia tri tien thuong cua bo phan kinh doanh cap nhan vien - tren 1 lan thu
    public function getBonusMoneyOfBusinessStaff($payId)
    {
        $modelOrderBonusBudget = new QcOrderBonusBudget();
        $dataOrderPay = $this->getInfo($payId);
        $dataOrder = $dataOrderPay->order;
        $bonusPercent = $modelOrderBonusBudget->getPercentOfBusinessStaff($dataOrder->orderId());
        if ($bonusPercent > 0) {
            $moneyPay = $dataOrderPay->money();
            //$moneyPay = (is_array($moneyPay)) ? $moneyPay[0] : $moneyPay;
            return (int)($moneyPay * ($bonusPercent / 100));
        } else {
            return 0;
        }

    }

    //---------- DON HANG -----------
    public function order()
    {
        return $this->belongsTo('App\Models\Ad3d\Order\QcOrder', 'order_id', 'order_id');
    }

    # danh sach thanh toans cua 1 don hang
    public function infoOfOrder($orderId)
    {
        return QcOrderPay::where('order_id', $orderId)->get();
    }

    # tong tien da thanh toan cua 1 don hang
    public function totalPayOfOrder($orderId)
    {
        return QcOrderPay::where('order_id', $orderId)->sum('money');
    }

    # tong tien da thanh toan cua 1 don hang theo ngay
    public function totalPayOfOrderAndDate($orderId, $date = null)
    {
        $hFunction = new \Hfunction();
        if (!$hFunction->checkEmpty($date)) {
            return QcOrderPay::where('order_id', $orderId)->where('datePay', 'like', "%$date%")->sum('money');
        } else {
            return QcOrderPay::where('order_id', $orderId)->sum('money');
        }

    }

    # chon danh sach thanh toan theo: ngay / danh sach don hang / danh sach ma nv / trang thai thanh toan/
    public function selectInfoByListOrderOrListStaffOrDateAndTransferStatus($listOrderId, $listStaffId, $date, $transferStatus = 100)
    {
        $modelTransferDetail = new QcTransfersDetail();
        # 100 - mac dinh chon tat ca
        if ($transferStatus == $this->getDefaultHasTransfers()) { // da giao
            $listPayIdTransferred = $modelTransferDetail->listPayId();
            return QcOrderPay:: whereIn('pay_id', $listPayIdTransferred)->whereIn('staff_id', $listStaffId)->whereIn('order_id', $listOrderId)->where('datePay', 'like', "%$date%")->orderBy('datePay', 'DESC')->select('*');
        } elseif ($transferStatus == $this->getDefaultNotTransfers()) { // chua giao
            $listPayIdTransferred = $modelTransferDetail->listPayId();
            return QcOrderPay:: whereNotIn('pay_id', $listPayIdTransferred)->whereIn('staff_id', $listStaffId)->whereIn('order_id', $listOrderId)->where('datePay', 'like', "%$date%")->orderBy('datePay', 'DESC')->select('*');
        } else {
            return QcOrderPay::whereIn('staff_id', $listStaffId)->whereIn('order_id', $listOrderId)->where('datePay', 'like', "%$date%")->orderBy('datePay', 'DESC')->select('*');
        }
    }

    # tong tin thanh toan tu 1 danh sach thang toan
    public function totalMoneyByListInfo($dataOrderPay)
    {
        $hFunction = new \Hfunction();
        $totalMoney = 0;
        if ($hFunction->checkCount($dataOrderPay)) {
            foreach ($dataOrderPay as $orderPay) {
                $totalMoney = $totalMoney + $orderPay->money();
            }
        }
        return $totalMoney;
    }

    //---------- chi tiet chuyen tien -----------
    public function transfersDetail()
    {
        return $this->hasOne('App\Models\Ad3d\TransfersDetail\QcTransfersDetail', 'pay_id', 'pay_id');
    }

    public function checkExistTransfersDetail($payId = null)
    {
        $hFunction = new \Hfunction();
        $modelTransfersDetail = new QcTransfersDetail();
        return ($hFunction->checkCount($modelTransfersDetail->getInfoOfPay($this->checkIdNull($payId)))) ? true : false;
    }

    #============ =========== ============ GET INFO ============= =========== ==========
    public function getInfo($payId = '', $field = '')
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($payId)) {
            return QcOrderPay::get();
        } else {
            $result = QcOrderPay::where('pay_id', $payId)->first();
            if ($hFunction->checkEmpty($field)) {
                return $result;
            } else {
                return $result->$field;
            }
        }
    }

    public function pluck($column, $objectId = null)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($objectId)) {
            return $this->$column;
        } else {
            return QcOrderPay::where('pay_id', $objectId)->pluck($column)[0];
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
        $hFunction = new \Hfunction();
        $modelOrder = new QcOrder();
        $listOrderId = $modelOrder->listIdOfListCompanyAndName($listCompanyId, $hFunction->getDefaultNull());
        if ($hFunction->checkEmpty($dateFilter)) {
            return QcOrderPay::whereIn('order_id', $listOrderId)->groupBy('staff_id')->pluck('staff_id');
        } else {
            return QcOrderPay::whereIn('order_id', $listOrderId)->where('datePay', 'like', "%$dateFilter%")->groupBy('staff_id')->pluck('staff_id');
        }
    }

    public function totalOrderPayOfCompany($listCompanyId, $dateFilter = null)
    {
        $hFunction = new \Hfunction();
        $modelOlder = new QcOrder();
        $listOrderId = $modelOlder->listIdOfListCompanyAndName($listCompanyId, $hFunction->getDefaultNull());
        if ($hFunction->checkEmpty($dateFilter)) {
            return QcOrderPay::whereIn('order_id', $listOrderId)->sum('money');
        } else {
            return QcOrderPay::whereIn('order_id', $listOrderId)->where('datePay', 'like', "%$dateFilter%")->sum('money');
        }
    }

    public function totalOrderPayOfCompanyStaffDate($listCompanyId, $staffId, $dateFilter = null)
    {
        $hFunction = new \Hfunction();
        $modelOlder = new QcOrder();
        $listOrderId = $modelOlder->listIdOfListCompanyAndName($listCompanyId, $hFunction->getDefaultNull());
        if ($hFunction->checkEmpty($dateFilter)) {
            return QcOrderPay::where('staff_id', $staffId)->whereIn('order_id', $listOrderId)->sum('money');
        } else {
            return QcOrderPay::where('staff_id', $staffId)->whereIn('order_id', $listOrderId)->where('datePay', 'like', "%$dateFilter%")->sum('money');
        }
    }


}
