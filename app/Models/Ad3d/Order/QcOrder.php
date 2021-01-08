<?php

namespace App\Models\Ad3d\Order;

use App\Models\Ad3d\Bonus\QcBonus;
use App\Models\Ad3d\BonusDepartment\QcBonusDepartment;
use App\Models\Ad3d\Department\QcDepartment;
use App\Models\Ad3d\MinusMoney\QcMinusMoney;
use App\Models\Ad3d\OrderAllocation\QcOrderAllocation;
use App\Models\Ad3d\OrderBonusBudget\QcOrderBonusBudget;
use App\Models\Ad3d\OrderCancel\QcOrderCancel;
use App\Models\Ad3d\OrderImage\QcOrderImage;
use App\Models\Ad3d\OrderPay\QcOrderPay;
use App\Models\Ad3d\Product\QcProduct;
use App\Models\Ad3d\PunishContent\QcPunishContent;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\StaffNotify\QcStaffNotify;
use App\Models\Ad3d\WorkAllocation\QcWorkAllocation;
use App\Models\Ad3d\WorkAllocationReportImage\QcWorkAllocationReportImage;
use Illuminate\Database\Eloquent\Model;

class QcOrder extends Model
{
    protected $table = 'qc_orders';
    protected $fillable = ['order_id', 'nameCode', 'name', 'constructionAddress', 'constructionPhone', 'constructionContact',
        'discount', 'vat', 'receiveDate', 'deliveryDate', 'finishDate', 'finishStatus', 'paymentStatus', 'confirmStatus',
        'confirmAgree', 'confirmDate', 'cancelStatus', 'action', 'company_id', 'customer_id', 'staff_id', 'staffReceive_id',
        'staffConfirm_id', 'staffReportFinish_id', 'staffKpi_id', 'created_at', 'provisionalStatus', 'provisionalDate', 'provisionalConfirm'];
    protected $primaryKey = 'order_id';
    public $timestamps = false;

    private $lastId;

    # mac dinh da ket thuc
    public function getDefaultHasFinishStatus()
    {
        return 1;
    }

    # mac dinh chua ket thuc
    public function getDefaultNotFinishStatus()
    {
        return 0;
    }

    # mac dinh da thanh toan
    public function getDefaultHasPayment()
    {
        return 1;
    }

    # mac dinh chua thanh toan
    public function getDefaultNotPayment()
    {
        return 0;
    }

    # mac dinh tat ca thong tin thanh toan
    public function getDefaultAllPayment()
    {
        return 3;
    }

    # mac dinh da xac nhan
    public function getDefaultHasConfirm()
    {
        return 1;
    }

    # mac dinh chua xac nhan
    public function getDefaultNotConfirm()
    {
        return 0;
    }

    # mac dinh da huy
    public function getDefaultHasCancel()
    {
        return 1;
    }

    # mac dinh khong huy
    public function getDefaultNotCancel()
    {
        return 0;
    }

    # mac dinh dang hoat dong
    public function getDefaultHasAction()
    {
        return 1;
    }

    # mac dinh chua ket thuc
    public function getDefaultNotAction()
    {
        return 0;
    }

    # mac dinh don hang bao gia
    public function getDefaultProvisionalOrder()
    {
        return 0;
    }

    # mac dinh khong phai don hang bao
    public function getDefaultRealOrder()
    {
        return 1;
    }

    # mac dinh da xac nhan dat hang bao gia
    public function getDefaultHasProvisionalConfirm()
    {
        return 1;
    }

    #mac dinh chua xac nhan dat hang bao gia
    public function getDefaultNotProvisionalConfirm()
    {
        return 0;
    }

    # mac dinh ngay bao gia
    public function getDefaultProvisionalDate()
    {
        return null;
    }

    # mac dinh ma nhan vien KPI
    public function getDefaultStaffKPIId()
    {
        return null;
    }

    # mac dinh co dong y
    public function getDefaultHasAgree()
    {
        return 1;
    }

    # mac dinh khong dong y
    public function getDefaultNotAgree()
    {
        return 0;
    }

    # mac dinh ngay xac nhan
    public function getDefaultConfirmDate()
    {
        return null;
    }

    # mac dinh nguoi xac nhan
    public function getDefaultConfirmStaffId()
    {
        return null;
    }

    # mac dinh ngay ket thuc
    public function getDefaultFinishDate()
    {
        return null;
    }
    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- them don hang ----------

    // them don hang moi
    public function insert($name, $discount, $vat, $receiveDate, $deliveryDate, $customerId, $staffId, $staffReceiveId, $staffKpiId = null, $confirmStatus = 1, $constructionAddress = null, $constructionPhone = null, $constructionContact = null, $provisionalStatus = 1, $provisionalDate = null, $provisionalConfirm = 1)
    {
        $hFunction = new \Hfunction();
        $modelOrder = new QcOrder();
        $modelStaff = new QcStaff();
        $dataStaff = $modelStaff->getInfo($staffId);
        if ($hFunction->checkCount($dataStaff)) {
            $dataCompanyStaffWork = $modelStaff->companyStaffWorkInfoActivity($staffId);
            if ($hFunction->checkCount($dataCompanyStaffWork)) { #du lieu moi
                $dataCompany = $dataCompanyStaffWork->company;
            } else {
                $dataCompany = $dataStaff->company; # du lieu cu
            }
            if ($confirmStatus == $this->getDefaultHasConfirm()) { # tao tu trang quan ly -->ko can duyet
                $confirmDate = $hFunction->carbonNow();
                $confirmAgree = $this->getDefaultHasAgree();
                $staffConfirmId = $staffReceiveId;
            } else {
                $confirmDate = $this->getDefaultConfirmDate();
                $confirmAgree = $this->getDefaultNotAgree();
                $staffConfirmId = $this->getDefaultConfirmStaffId();
            }
            $lassId = $this->lastId();
            $lassId = (empty($lassId)) ? 1 : $lassId + 1;
            // insert
            $modelOrder->orderCode = $dataCompany->nameCode() . '-' . $lassId;
            $modelOrder->name = $hFunction->convertValidHTML($name);
            $modelOrder->constructionAddress = $hFunction->convertValidHTML($constructionAddress);
            $modelOrder->constructionPhone = $hFunction->convertValidHTML($constructionPhone);
            $modelOrder->constructionContact = $hFunction->convertValidHTML($constructionContact);
            $modelOrder->discount = $discount;
            $modelOrder->vat = $vat;
            $modelOrder->receiveDate = $receiveDate;
            $modelOrder->deliveryDate = $deliveryDate;
            $modelOrder->finishDate = $this->getDefaultFinishDate();
            $modelOrder->finishStatus = $this->getDefaultNotFinishStatus();
            $modelOrder->paymentStatus = $this->getDefaultNotPayment();
            $modelOrder->staffKpi_id = $staffKpiId;
            $modelOrder->confirmStatus = $confirmStatus;
            $modelOrder->confirmAgree = $confirmAgree;
            $modelOrder->confirmDate = $confirmDate;
            $modelOrder->cancelStatus = $this->getDefaultNotCancel();
            $modelOrder->company_id = $dataCompany->companyId();
            $modelOrder->customer_id = $customerId;
            $modelOrder->staff_id = $staffId;
            $modelOrder->staffReceive_id = $staffReceiveId;
            $modelOrder->staffConfirm_id = $staffConfirmId;
            $modelOrder->created_at = $hFunction->createdAt();
            $modelOrder->provisionalStatus = $provisionalStatus;
            $modelOrder->provisionalDate = $provisionalDate;
            $modelOrder->provisionalConfirm = $provisionalConfirm;
            if ($modelOrder->save()) {
                $this->lastId = $modelOrder->order_id;
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }

    }

    # xac nhan don hang
    public function confirm($orderId, $confirmAgree, $staffConfirmId)
    {
        $hFunction = new \Hfunction();
        return QcOrder::where('order_id', $orderId)->update([
            'confirmStatus' => $this->getDefaultHasConfirm(),
            'confirmAgree' => $confirmAgree,
            'confirmDate' => $hFunction->carbonNow(),
            'staffConfirm_id' => $staffConfirmId,
        ]);
    }

    # xac nhan don hang bao gia
    public function confirmProvision($orderId, $txtDateReceive, $deliveryDate)
    {
        $hFunction = new \Hfunction();
        return QcOrder::where('order_id', $orderId)->update([
            'confirmStatus' => $this->getDefaultHasConfirm(),
            'receiveDate' => $txtDateReceive,
            'deliveryDate' => $deliveryDate,
            'provisionalConfirm' => $this->getDefaultHasProvisionalConfirm(),
            'provisionalDate' => $hFunction->carbonNow(),
        ]);
    }

    # kiem tra id
    public function checkIdNull($orderId = null)
    {
        return (empty($orderId) ? $this->orderId() : $orderId);
    }

    #lay ma don hang moi them
    public function insertGetId()
    {
        return $this->lastId;
    }

    public function addProduct($orderId, $width, $height, $depth, $price, $amount, $description, $typeId)
    {
        $modelProduct = new QcProduct();
        return $modelProduct->insert($width, $height, $depth, $price, $amount, $description, $typeId, $orderId);
    }

    // cap nhat  thong tin don hang
    public function updateInfo($orderId, $name, $discount, $vat, $receiveDate, $deliveryDate, $staffReceiveId, $constructionAddress = null, $constructPhone = null, $constructContact = null)
    {
        $hFunction = new \Hfunction();
        return QcOrder::where('order_id', $orderId)->update([
            'name' => $hFunction->convertValidHTML($name),
            'discount' => $discount,
            'vat' => $vat,
            'receiveDate' => $receiveDate,
            'deliveryDate' => $deliveryDate,
            'constructionAddress' => $hFunction->convertValidHTML($constructionAddress),
            'constructionPhone' => $constructPhone,
            'constructionContact' => $hFunction->convertValidHTML($constructContact),
            'staffReceive_id' => $staffReceiveId
        ]);
    }

    # BO PHAN KINH DOANH BAO HOAN THANH BAN GIAO KHACH HANG
    public function confirmReportFinish($orderId, $finishStatus, $staffReportFinishId)
    {
        $hFunction = new \Hfunction();
        $modelOrderBonusBudget = new QcOrderBonusBudget();
        $modelOrderAllocation = new QcOrderAllocation();
        $modelProduct = new QcProduct();
        $dataProduct = $this->productActivityOfOrder($orderId);
        # xac nhan hoan thanh tren don hang
        if (QcOrder::where('order_id', $orderId)->update([
            'finishStatus' => $finishStatus,
            'finishDate' => $hFunction->carbonNow(),
            'staffReportFinish_id' => $staffReportFinishId
        ])
        ) {
            # ket thuc ban giao cong trinh thi cong
            $orderAllocationFinish = $modelOrderAllocation->getDefaultHasFinish();
            $modelOrderAllocation->confirmFinishFromFinishOrder($orderId, $orderAllocationFinish, $staffReportFinishId);

            # bao ket thuc san pham
            if ($hFunction->checkCount($dataProduct)) {
                foreach ($dataProduct as $product) {
                    $modelProduct->confirmFinishFromFinishOrder($product->productId(), $staffReportFinishId);
                }
            }

            # xet thuong cho bo pham thi cong
            $modelOrderBonusBudget->applyBonusDepartmentConstruction($orderId);
            return true;

        } else {
            return false;
        }
    }

    #kiem tra thanh toan cua don hang
    public function updateFinishPayment($orderId = null)
    {
        $orderId = (empty($orderId)) ? $this->orderId() : $orderId;
        # giam gia 100%
        if ($this->discount($orderId) == 100) {
            return $this->finishPayment($orderId);
        } else {
            $totalPaid = $this->totalPaid($orderId);
            $totalPricePayment = $this->totalMoneyPayment($orderId);
            # da thanh toan du
            if ($totalPaid == $totalPricePayment) {
                return $this->finishPayment($orderId);
            }
        }
    }

    public function finishPayment($orderId = null)
    {
        return QcOrder::where('order_id', $this->checkIdNull($orderId))->update(['paymentStatus' => $this->getDefaultHasPayment()]);
    }

    public function cancelFinishPayment($orderId = null)
    {
        return QcOrder::where('order_id', $this->checkIdNull($orderId))->update(['paymentStatus' => $this->getDefaultNotPayment()]);
    }

    /*he thong xoa don hang*/
    public function deleteAction($orderId)
    {
        $modelProduct = new QcProduct();
        if (QcOrder::where('order_id', $orderId)->update(['action' => $this->getDefaultNotAction()])) {
            $modelProduct->cancelByOrder($orderId);
            return true;
        } else {
            return false;
        }
    }

    /*NV huy don hang*/
    public function cancelOrder($orderId, $payment, $reason, $staffCancelId)
    {
        $modelProduct = new QcProduct();
        $modelOrderCancel = new QcOrderCancel();
        $modelOrderAllocation = new QcOrderAllocation();
        if (QcOrder::where('order_id', $orderId)->update(
            [
                'cancelStatus' => $this->getDefaultHasCancel(),
                'action' => $this->getDefaultNotAction()
            ])
        ) {
            #chi tiet huy
            $modelOrderCancel->insert($payment, $reason, $orderId, $staffCancelId);

            # ket thuc ban giao cong trinh
            $modelOrderAllocation->confirmFinishFromFinishOrder($orderId, $modelOrderAllocation->getDefaultHasFinish(), $staffCancelId);

            # huy san pham
            $modelProduct->cancelByOrder($orderId);
            return true;
        } else {
            return false;
        }
    }

    /*NV huy don hang bao gia*/
    public function cancelOrderProvisional($orderId)
    {
        QcOrder::where('order_id', $orderId)->update(
            [
                'cancelStatus' => $this->getDefaultHasCancel(),
                'action' => $this->getDefaultNotAction()
            ]);
    }

    //========== ========= ========= RELATION ========== ========= ==========
    //---------- thiet ke -----------
    public function orderImage()
    {
        return $this->hasMany('App\Models\Ad3d\OrderImage\QcOrderImage', 'order_id', 'order_id');
    }

    public function orderImageInfoActivity($orderId = null)
    {
        $modelOrderImage = new QcOrderImage();
        return $modelOrderImage->infoActivityOfOrder($this->checkIdNull($orderId));
    }

    //---------- khach hang -----------
    public function customer()
    {
        return $this->belongsTo('App\Models\Ad3d\Customer\QcCustomer', 'customer_id', 'customer_id');
    }

    // lay thong tin don hang cua 1 hoac nhieu khach hang
    public function infoOfListCustomer($listCustomerId, $date = null, $paymentStatus = 3, $orderBy = 'DESC')
    {
        return $this->selectInfoOfListCustomer($listCustomerId, $date, $paymentStatus, $orderBy)->get();
    }

    # lay don hang theo thong tin khach hang cua TAT CA CONG
    public function selectInfoOfListCustomer($listCustomerId, $date = null, $paymentStatus = 3, $orderBy = 'DESC')
    {
        # da xac nhan
        $hasConfirmStatus = $this->getDefaultHasConfirm();
        #$paymentStatus = 3 - tat ca /
        if (!empty($date) && $paymentStatus < 2) {
            return QcOrder::whereIn('customer_id', $listCustomerId)->where('confirmStatus', $hasConfirmStatus)->where('receiveDate', 'like', "%$date%")->where('paymentStatus', $paymentStatus)->orderBy('receiveDate', $orderBy)->select('*');
        } else if (!empty($date) && $paymentStatus > 1) {
            return QcOrder::whereIn('customer_id', $listCustomerId)->where('confirmStatus', $hasConfirmStatus)->where('receiveDate', 'like', "%$date%")->orderBy('receiveDate', $orderBy)->select('*');
        } else if (empty($date) && $paymentStatus < 2) {
            return QcOrder::whereIn('customer_id', $listCustomerId)->where('confirmStatus', $hasConfirmStatus)->where('paymentStatus', $paymentStatus)->orderBy('receiveDate', $orderBy)->select('*');
        } else {
            return QcOrder::whereIn('customer_id', $listCustomerId)->where('confirmStatus', $hasConfirmStatus)->orderBy('receiveDate', $orderBy)->select('*');
        }
    }

    # lay don hang theo thong tin khach hang cua 1 CONG TY
    public function selectInfoOfListCustomerOfCompany($companyId, $listCustomerId, $date = null, $paymentStatus = 3, $orderBy = 'DESC')
    {
        #$paymentStatus = 3 - tat ca /
        # da xac nhan
        $hasConfirmStatus = $this->getDefaultHasConfirm();
        if (!empty($date) && $paymentStatus < 2) {
            return QcOrder::where('company_id', $companyId)->whereIn('customer_id', $listCustomerId)->where('confirmStatus', $hasConfirmStatus)->where('receiveDate', 'like', "%$date%")->where('paymentStatus', $paymentStatus)->orderBy('receiveDate', $orderBy)->select('*');
        } else if (!empty($date) && $paymentStatus > 1) {
            return QcOrder::where('company_id', $companyId)->whereIn('customer_id', $listCustomerId)->where('confirmStatus', $hasConfirmStatus)->where('receiveDate', 'like', "%$date%")->orderBy('receiveDate', $orderBy)->select('*');
        } else if (empty($date) && $paymentStatus < 2) {
            return QcOrder::where('company_id', $companyId)->whereIn('customer_id', $listCustomerId)->where('confirmStatus', $hasConfirmStatus)->where('paymentStatus', $paymentStatus)->orderBy('receiveDate', $orderBy)->select('*');
        } else {
            return QcOrder::where('company_id', $companyId)->whereIn('customer_id', $listCustomerId)->where('confirmStatus', $hasConfirmStatus)->orderBy('receiveDate', $orderBy)->select('*');
        }
    }

    // dơn hang khong huy
    public function infoNoCancelOfListCustomer($listCustomerId, $date = null, $paymentStatus = 3, $finishStatus = 100, $orderBy = 'DESC')
    {
        return $this->selectInfoNoCancelOfListCustomer($listCustomerId, $date, $paymentStatus, $finishStatus, $orderBy)->get();
    }

    public function selectInfoNoCancelOfListCustomer($listCustomerId, $date = null, $paymentStatus = 3, $finishStatus = 100, $orderBy = 'DESC')
    {
        # co xac nhan
        $hasConfirmStatus = $this->getDefaultHasConfirm();
        # khong huy
        $notCancel = $this->getDefaultNotCancel();
        if ($finishStatus < 100) {
            if ($paymentStatus < 3) {
                if (!empty($date)) {
                    return QcOrder::whereIn('customer_id', $listCustomerId)->where('finishStatus', $finishStatus)->where('paymentStatus', $paymentStatus)->where('confirmStatus', $hasConfirmStatus)->where('cancelStatus', $notCancel)->where('receiveDate', 'like', "%$date%")->orderBy('receiveDate', $orderBy)->select('*');
                } else {
                    return QcOrder::whereIn('customer_id', $listCustomerId)->where('finishStatus', $finishStatus)->where('paymentStatus', $paymentStatus)->where('confirmStatus', $hasConfirmStatus)->where('cancelStatus', $notCancel)->orderBy('receiveDate', $orderBy)->select('*');
                }
            } else {
                if (!empty($date)) {
                    return QcOrder::whereIn('customer_id', $listCustomerId)->where('finishStatus', $finishStatus)->where('confirmStatus', $hasConfirmStatus)->where('cancelStatus', $notCancel)->where('receiveDate', 'like', "%$date%")->orderBy('receiveDate', $orderBy)->select('*');
                } else {
                    return QcOrder::whereIn('customer_id', $listCustomerId)->where('finishStatus', $finishStatus)->where('confirmStatus', $hasConfirmStatus)->where('cancelStatus', $notCancel)->orderBy('receiveDate', $orderBy)->select('*');
                }
            }

        } else {
            if ($paymentStatus < 3) {
                if (!empty($date)) {
                    return QcOrder::whereIn('customer_id', $listCustomerId)->where('paymentStatus', $paymentStatus)->where('confirmStatus', $hasConfirmStatus)->where('cancelStatus', $notCancel)->where('receiveDate', 'like', "%$date%")->orderBy('receiveDate', $orderBy)->select('*');
                } else {
                    return QcOrder::whereIn('customer_id', $listCustomerId)->where('paymentStatus', $paymentStatus)->where('confirmStatus', $hasConfirmStatus)->where('cancelStatus', $notCancel)->orderBy('receiveDate', $orderBy)->select('*');
                }
            } else {
                if (!empty($date)) {
                    return QcOrder::whereIn('customer_id', $listCustomerId)->where('confirmStatus', $hasConfirmStatus)->where('cancelStatus', $notCancel)->where('receiveDate', 'like', "%$date%")->orderBy('receiveDate', $orderBy)->select('*');
                } else {
                    return QcOrder::whereIn('customer_id', $listCustomerId)->where('confirmStatus', $hasConfirmStatus)->where('cancelStatus', $notCancel)->orderBy('receiveDate', $orderBy)->select('*');
                }
            }
        }

    }

    # lay thong tin don hang BAO GIA cua 1 hoac nhieu khach hang
    public function infoProvisionalOfListCustomer($listCustomerId, $date = null, $orderBy = 'DESC')
    {
        # mac dinh chu dat hang
        $notProvisionalStatus = $this->getDefaultNotProvisionalConfirm();
        if (!empty($date)) {
            return QcOrder::whereIn('customer_id', $listCustomerId)->where('provisionalStatus', $notProvisionalStatus)->where('receiveDate', 'like', "%$date%")->orderBy('receiveDate', $orderBy)->get();
        } else {
            return QcOrder::whereIn('customer_id', $listCustomerId)->where('provisionalStatus', $notProvisionalStatus)->orderBy('receiveDate', $orderBy)->get();
        }
    }

    // lay thong tin don hang BAO GIA cua 1 hoac nhieu khach hang - khong huy
    public function infoProvisionalNoCancelOfListCustomer($listCustomerId, $date = null, $orderBy = 'DESC')
    {
        # mac dinh chu dat hang
        $notProvisionalStatus = $this->getDefaultNotProvisionalConfirm();
        # mac dinh khong huy
        $notCancel = $this->getDefaultNotCancel();
        if (!empty($date)) {
            return QcOrder::whereIn('customer_id', $listCustomerId)->where('provisionalStatus', $notProvisionalStatus)->where('cancelStatus', $notCancel)->where('receiveDate', 'like', "%$date%")->orderBy('receiveDate', $orderBy)->get();
        } else {
            return QcOrder::whereIn('customer_id', $listCustomerId)->where('provisionalStatus', $notProvisionalStatus)->where('cancelStatus', $notCancel)->orderBy('receiveDate', $orderBy)->get();
        }
    }

    public function infoAllOfCustomer($customerId, $orderBy = 'DESC')
    {
        return QcOrder::where('customer_id', $customerId)->orderBy('receiveDate', $orderBy)->get();
    }

    //---------- ---------- ---------- thong tin thuong tren don hang ----------- ----------
    # ngan sach thuong cua don hang
    public function orderBonusBudget()
    {
        return $this->belongsTo('App\Models\Ad3d\OrderBonusBudget\QcOrderBonusBudget', 'order_id', 'order_id');
    }

    # xet them vao ngan sach thuong cua don hang
    public function checkAddBonusBudget($orderId)
    {
        $hFunction = new \Hfunction();
        $modelBonusDepartment = new QcBonusDepartment();
        $modelOrderBonusBudget = new QcOrderBonusBudget();
        # thong tin thuong cua bo phan thi cong cap quan ly dang hoat dong
        $dataBonusDepartment = $modelBonusDepartment->getActivityInfo();
        if ($hFunction->checkCount($dataBonusDepartment)) {
            foreach ($dataBonusDepartment as $bonusDepartment) {
                $bonusId = $bonusDepartment->bonusId();
                $departmentId = $bonusDepartment->departmentId();
                $rankId = $bonusDepartment->rankId();
                #chu ap dung moi them vao
                if (!$modelOrderBonusBudget->checkExistOrderAndBonusDepartment($orderId, $bonusId)) {
                    $modelOrderBonusBudget->insert($orderId, $bonusId, $departmentId, $rankId);
                }
            }
        }
    }

    # thong tin ngan sach thuong cua don hang
    public function orderBonusBudgetInfo($orderId = null)
    {
        $modelOrderBonusBudget = new QcOrderBonusBudget();
        return $modelOrderBonusBudget->infoOfOrder($this->checkIdNull($orderId));
    }

    //---------- ---------- ---------- nhan vien ----------- ---------- ----------
    public function staff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'staff_id', 'staff_id');
    }

    # nha vien xac nhan
    public function staffConfirm()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'staffConfirm_id', 'staff_id');
    }

    # kiem tra don hang thuoc vua NV
    public function checkOwnerStatus($staffId, $orderId)
    {
        return $this->checkStaffInput($staffId, $orderId);
    }

    //kiem tra nguoi nhap
    public function checkStaffInput($staffId, $orderId = null)
    {
        return (QcOrder::where('staff_id', $staffId)->where('order_id', $this->checkIdNull($orderId))->count() > 0) ? true : false;
    }

    //kiem tra don hang thuoc người nhan
    public function checkOrderOfReceiveStaff($staffReceiveId, $orderId = null)
    {
        return QcOrder::where('staffReceive_id', $staffReceiveId)->where('order_id', $this->checkIdNull($orderId))->exists();
    }

    public function staffReceive()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'staffReceive_id', 'staff_id');
    }

    public function infoOfStaffReceive($staffId = null, $date = null, $confirmStatus = 3, $keyWord = null, $orderBy = 'DESC')
    {
        # khong huy
        $notCancel = $this->getDefaultNotCancel();
        if (empty($keyWord)) {
            if (!empty($date) && $confirmStatus < 3) {
                return QcOrder::where(['staffReceive_id' => $staffId])->where('receiveDate', 'like', "%$date%")->where('confirmStatus', $confirmStatus)->where('cancelStatus', $notCancel)->orderBy('receiveDate', $orderBy)->get();
            } else if (!empty($date) && $confirmStatus > 2) {
                return QcOrder::where(['staffReceive_id' => $staffId])->where('receiveDate', 'like', "%$date%")->where('cancelStatus', $notCancel)->orderBy('receiveDate', $orderBy)->get();
            } else if (empty($date) && $confirmStatus < 3) {
                return QcOrder::where(['staffReceive_id' => $staffId])->where('confirmStatus', $confirmStatus)->where('cancelStatus', $notCancel)->orderBy('receiveDate', $orderBy)->get();
            } else {
                return QcOrder::where(['staffReceive_id' => $staffId])->orderBy('receiveDate', $orderBy)->where('cancelStatus', $notCancel)->get();
            }
        } else {
            if (!empty($date) && $confirmStatus < 3) {
                return QcOrder::where(['staffReceive_id' => $staffId])->where('name', 'like', "%$keyWord%")->where('receiveDate', 'like', "%$date%")->where('confirmStatus', $confirmStatus)->where('cancelStatus', $notCancel)->orderBy('receiveDate', $orderBy)->get();
            } else if (!empty($date) && $confirmStatus > 2) {
                return QcOrder::where(['staffReceive_id' => $staffId])->where('name', 'like', "%$keyWord%")->where('receiveDate', 'like', "%$date%")->where('cancelStatus', $notCancel)->orderBy('receiveDate', $orderBy)->get();
            } else if (empty($date) && $confirmStatus < 3) {
                return QcOrder::where(['staffReceive_id' => $staffId])->where('name', 'like', "%$keyWord%")->where('confirmStatus', $confirmStatus)->where('cancelStatus', $notCancel)->orderBy('receiveDate', $orderBy)->get();
            } else {
                return QcOrder::where(['staffReceive_id' => $staffId])->where('name', 'like', "%$keyWord%")->orderBy('receiveDate', $orderBy)->where('cancelStatus', $notCancel)->get();
            }
        }

    }

    // lay thong tin don hang da thanh toan hoac chưa hoan thanh toan theo tg
    public function infoAndPayOfStaffReceive($staffId = null, $date = null, $paymentStatus = 3, $keyWord = null, $orderBy = 'DESC')
    {
        $hFunction = new \Hfunction();
        $modelOrderPay = new QcOrderPay();
        $listOrderOfPaidId = $modelOrderPay->listOrderIdOfStaff($staffId, $date);
        if (empty($keyWord)) {
            if (!empty($date) && $paymentStatus < 3) {
                $listOrderId = QcOrder::where('receiveDate', 'like', "%$date%")->where('paymentStatus', $paymentStatus)->pluck('order_id');
                /*return QcOrder::where(['staffReceive_id' => $staffId])->where('confirmStatus', 1)->where('cancelStatus', 0)->where('receiveDate', 'like', "%$date%")->where('paymentStatus', $paymentStatus)->orWhere(function ($query) use ($listOrderOfPaidId) {
                    $query->whereIn('order_id', $listOrderOfPaidId);
                })->orderBy('receiveDate', $orderBy)->get();*/
            } else if (!empty($date) && $paymentStatus > 2) {
                $listOrderId = QcOrder::where('receiveDate', 'like', "%$date%")->pluck('order_id');
            } else if (empty($date) && $paymentStatus < 3) {
                $listOrderId = QcOrder::where('paymentStatus', $paymentStatus)->pluck('order_id');
            } else {
                $listOrderId = QcOrder::pluck('order_id');
            }
        } else {
            if (!empty($date) && $paymentStatus < 3) {
                $listOrderId = QcOrder::where('name', 'like', "%$keyWord%")->where('receiveDate', 'like', "%$date%")->where('paymentStatus', $paymentStatus)->pluck('order_id');
            } else if (!empty($date) && $paymentStatus > 2) {
                $listOrderId = QcOrder::where('name', 'like', "%$keyWord%")->where('receiveDate', 'like', "%$date%")->pluck('order_id');
            } else if (empty($date) && $paymentStatus < 3) {
                $listOrderId = QcOrder::where('name', 'like', "%$keyWord%")->where('paymentStatus', $paymentStatus)->pluck('order_id');
            } else {
                $listOrderId = QcOrder::where('name', 'like', "%$keyWord%")->pluck('order_id');
            }
        }
        $selectOrderId = $hFunction->arrayUnique($hFunction->arrayMergeTwo($listOrderId->toArray(), $listOrderOfPaidId->toArray()));
        # da xac nhan
        $hasConfirm = $this->getDefaultHasConfirm();
        return QcOrder::where(['staffReceive_id' => $staffId])->whereIn('order_id', $selectOrderId)->where('confirmStatus', $hasConfirm)->orderBy('receiveDate', $orderBy)->get();
    }

    // lay thong tin don hang da thanh toan hoac chưa hoan thanh toan theo tg - khong huy
    public function infoNoCancelAndPayOfStaffReceive($staffId = null, $date = null, $paymentStatus = 3, $keyWord = null, $orderBy = 'DESC')
    {
        return $this->selectInfoNoCancelAndPayOfStaffReceive($staffId, $date, $paymentStatus, $keyWord, $orderBy)->get();
    }

    # cua 1 hay nhieu nguoi
    public function infoNoCancelAndPayOfListStaffReceive($listStaffId, $date = null, $paymentStatus = 3, $keyWord = null, $orderBy = 'DESC')
    {
        return $this->selectInfoNoCancelAndPayOfListStaffReceive($listStaffId, $date, $paymentStatus, $keyWord, $orderBy)->get();
    }

    # cua 1 NV
    public function selectInfoNoCancelAndPayOfStaffReceive($staffId = null, $date = null, $paymentStatus = 3, $finishStatus = 100, $keyWord = null, $orderBy = 'DESC')
    {
        $hFunction = new \Hfunction();
        $modelOrderPay = new QcOrderPay();
        if (empty($keyWord)) {
            if (!empty($date)) {
                $listOrderId = QcOrder::where('receiveDate', 'like', "%$date%")->pluck('order_id');
            } else {
                $listOrderId = QcOrder::select('*')->pluck('order_id');
            }
        } else {
            if (!empty($date)) {
                $listOrderId = QcOrder::where('name', 'like', "%$keyWord%")->where('receiveDate', 'like', "%$date%")->pluck('order_id');
            } else {
                $listOrderId = QcOrder::where('name', 'like', "%$keyWord%")->pluck('order_id');
            }
        }
        # da xac nhan
        $hasConfirm = $this->getDefaultHasConfirm();
        # khong huy
        $notCancel = $this->getDefaultNotCancel();
        $selectOrderId = $listOrderId;
        if ($finishStatus < 100) {
            if ($paymentStatus < 3) {
                return QcOrder::where(['staffReceive_id' => $staffId])->whereIn('order_id', $selectOrderId)->where('finishStatus', $finishStatus)->where('paymentStatus', $paymentStatus)->where('confirmStatus', $hasConfirm)->where('cancelStatus', $notCancel)->orderBy('receiveDate', $orderBy)->select('*');
            } else {
                return QcOrder::where(['staffReceive_id' => $staffId])->whereIn('order_id', $selectOrderId)->where('finishStatus', $finishStatus)->where('confirmStatus', $hasConfirm)->where('cancelStatus', $notCancel)->orderBy('receiveDate', $orderBy)->select('*');
            }
        } else {
            if ($paymentStatus < 3) {
                return QcOrder::where(['staffReceive_id' => $staffId])->whereIn('order_id', $selectOrderId)->where('paymentStatus', $paymentStatus)->where('confirmStatus', $hasConfirm)->where('cancelStatus', $notCancel)->orderBy('receiveDate', $orderBy)->select('*');
            } else {
                return QcOrder::where(['staffReceive_id' => $staffId])->whereIn('order_id', $selectOrderId)->where('confirmStatus', $hasConfirm)->where('cancelStatus', $notCancel)->orderBy('receiveDate', $orderBy)->select('*');
            }
        }

    }

    # cua 1 hay nhieu NV
    public function selectInfoNoCancelAndPayOfListStaffReceive($listStaffId, $date = null, $paymentStatus = 3, $finishStatus = 100, $keyWord = null, $orderBy = 'DESC')
    {
        $hFunction = new \Hfunction();
        $modelOrderPay = new QcOrderPay();
        if (empty($keyWord)) {
            if (!empty($date)) {
                $listOrderId = QcOrder::where('receiveDate', 'like', "%$date%")->pluck('order_id');
            } else {
                $listOrderId = QcOrder::select('*')->pluck('order_id');
            }
        } else {
            if (!empty($date)) {
                $listOrderId = QcOrder::where('name', 'like', "%$keyWord%")->where('receiveDate', 'like', "%$date%")->pluck('order_id');
            } else {
                $listOrderId = QcOrder::where('name', 'like', "%$keyWord%")->pluck('order_id');
            }
        }
        $selectOrderId = $listOrderId;
        # da xac nhan
        $hasConfirm = $this->getDefaultHasConfirm();
        # khong huy
        $notCancel = $this->getDefaultNotCancel();
        if ($finishStatus < 100) {
            if ($paymentStatus < 3) {
                return QcOrder::whereIn('staffReceive_id', $listStaffId)->whereIn('order_id', $selectOrderId)->where('finishStatus', $finishStatus)->where('paymentStatus', $paymentStatus)->where('confirmStatus', $hasConfirm)->where('cancelStatus', $notCancel)->orderBy('receiveDate', $orderBy)->select('*');
            } else {
                return QcOrder::whereIn('staffReceive_id', $listStaffId)->whereIn('order_id', $selectOrderId)->where('finishStatus', $finishStatus)->where('confirmStatus', $hasConfirm)->where('cancelStatus', $notCancel)->orderBy('receiveDate', $orderBy)->select('*');
            }
        } else {
            if ($paymentStatus < 3) {
                //dd($paymentStatus);
                return QcOrder::whereIn('staffReceive_id', $listStaffId)->whereIn('order_id', $selectOrderId)->where('paymentStatus', $paymentStatus)->where('confirmStatus', $hasConfirm)->where('cancelStatus', $notCancel)->orderBy('receiveDate', $orderBy)->select('*');
            } else {
                return QcOrder::whereIn('staffReceive_id', $listStaffId)->whereIn('order_id', $selectOrderId)->where('confirmStatus', $hasConfirm)->where('cancelStatus', $notCancel)->orderBy('receiveDate', $orderBy)->select('*');
            }
        }

    }

    public function selectInfoByListStaffAndNameAndDateAndPayment($listStaffId, $nameFiler = null, $dateFilter = null, $paymentStatus)
    {
        $dataOrder = null;
        if ($paymentStatus >= 2) { // 2 = bao gom  chua thanh toan xong va da thanh toan xong
            if (empty($nameFiler)) {
                if (empty($dateFilter)) {
                    $dataOrder = QcOrder::whereIn('staff_id', $listStaffId)->orderBy('created_at', 'DESC')->orderBy('orderCode', 'DESC')->select('*');

                } else {
                    $dataOrder = QcOrder::whereIn('staff_id', $listStaffId)->where('receiveDate', 'like', "%$dateFilter%")->orderBy('created_at', 'DESC')->orderBy('orderCode', 'DESC')->select('*');
                }

            } else {
                if (empty($dateFilter)) {
                    $dataOrder = QcOrder::where('name', 'like', "%$nameFiler%")->whereIn('staff_id', $listStaffId)->orderBy('created_at', 'DESC')->orderBy('orderCode', 'DESC')->select('*');
                } else {
                    $dataOrder = QcOrder::where('name', 'like', "%$nameFiler%")->whereIn('staff_id', $listStaffId)->where('created_at', 'like', "%$dateFilter%")->orderBy('receiveDate', 'DESC')->orderBy('orderCode', 'DESC')->select('*');
                }
            }

        } else {
            if (empty($nameFiler)) {
                $dataOrder = QcOrder::where('paymentStatus', $paymentStatus)->whereIn('staff_id', $listStaffId)->where('created_at', 'like', "%$dateFilter%")->orderBy('receiveDate', 'DESC')->orderBy('orderCode', 'DESC')->select('*');
            } else {
                $dataOrder = QcOrder::where('name', 'like', "%$nameFiler%")->where('paymentStatus', $paymentStatus)->whereIn('staff_id', $listStaffId)->where('receiveDate', 'like', "%$dateFilter%")->orderBy('receiveDate', 'DESC')->orderBy('orderCode', 'DESC')->select('*');
            }
        }
        return $dataOrder;
    }

    # lay thong tin don hang quan ly thi cong theo danh sach nhan vien
    public function selectInfoManageConstruction($listStaffId, $nameFiler = null, $dateFilter = null, $finishStatus = 100)
    {
        $dataOrder = null;
        if ($finishStatus == 100) { // 100 = tat ca don hang
            if (empty($nameFiler)) {
                if (empty($dateFilter)) {
                    $dataOrder = QcOrder::whereIn('staff_id', $listStaffId)->orderBy('created_at', 'DESC')->orderBy('orderCode', 'DESC')->select('*');

                } else {
                    $dataOrder = QcOrder::whereIn('staff_id', $listStaffId)->where('receiveDate', 'like', "%$dateFilter%")->orderBy('created_at', 'DESC')->orderBy('orderCode', 'DESC')->select('*');
                }

            } else {
                if (empty($dateFilter)) {
                    $dataOrder = QcOrder::where('name', 'like', "%$nameFiler%")->whereIn('staff_id', $listStaffId)->orderBy('created_at', 'DESC')->orderBy('orderCode', 'DESC')->select('*');
                } else {
                    $dataOrder = QcOrder::where('name', 'like', "%$nameFiler%")->whereIn('staff_id', $listStaffId)->where('created_at', 'like', "%$dateFilter%")->orderBy('receiveDate', 'DESC')->orderBy('orderCode', 'DESC')->select('*');
                }
            }

        } else {
            if (empty($nameFiler)) {
                $dataOrder = QcOrder::where('finishStatus', $finishStatus)->whereIn('staff_id', $listStaffId)->where('created_at', 'like', "%$dateFilter%")->orderBy('receiveDate', 'DESC')->orderBy('orderCode', 'DESC')->select('*');
            } else {
                $dataOrder = QcOrder::where('name', 'like', "%$nameFiler%")->where('finishStatus', $finishStatus)->whereIn('staff_id', $listStaffId)->where('receiveDate', 'like', "%$dateFilter%")->orderBy('receiveDate', 'DESC')->orderBy('orderCode', 'DESC')->select('*');
            }
        }
        return $dataOrder;
    }
    //---------- ---------- ĐƠN HANG BÁO GIÁ ---------- ----------
    // lay thong tin don hang bao gia
    public function infoProvisionalOfStaffReceive($staffId = null, $date = null, $provisionalConfirm = 3, $keyWord = null, $orderBy = 'DESC')
    {
        if (empty($keyWord)) {
            if (!empty($date) && $provisionalConfirm < 3) {
                return QcOrder::where(['staffReceive_id' => $staffId])->where('provisionalDate', 'like', "%$date%")->where('provisionalConfirm', $provisionalConfirm)->orderBy('provisionalDate', $orderBy)->get();
            } else if (!empty($date) && $provisionalConfirm > 2) {
                return QcOrder::where(['staffReceive_id' => $staffId])->where('provisionalDate', 'like', "%$date%")->orderBy('provisionalDate', $orderBy)->get();
            } else if (empty($date) && $provisionalConfirm < 3) {
                return QcOrder::where(['staffReceive_id' => $staffId])->where('provisionalConfirm', $provisionalConfirm)->orderBy('provisionalDate', $orderBy)->get();
            } else {
                return QcOrder::where(['staffReceive_id' => $staffId])->orderBy('provisionalDate', $orderBy)->get();
            }
        } else {
            if (!empty($date) && $provisionalConfirm < 3) {
                return QcOrder::where(['staffReceive_id' => $staffId])->where('name', 'like', "%$keyWord%")->where('provisionalDate', 'like', "%$date%")->where('provisionalConfirm', $provisionalConfirm)->orderBy('provisionalDate', $orderBy)->get();
            } else if (!empty($date) && $provisionalConfirm > 2) {
                return QcOrder::where(['staffReceive_id' => $staffId])->where('name', 'like', "%$keyWord%")->where('provisionalDate', 'like', "%$date%")->orderBy('provisionalDate', $orderBy)->get();
            } else if (empty($date) && $provisionalConfirm < 3) {
                return QcOrder::where(['staffReceive_id' => $staffId])->where('name', 'like', "%$keyWord%")->where('provisionalConfirm', $provisionalConfirm)->orderBy('provisionalDate', $orderBy)->get();
            } else {
                return QcOrder::where(['staffReceive_id' => $staffId])->where('name', 'like', "%$keyWord%")->orderBy('provisionalDate', $orderBy)->get();
            }
        }
    }

    public function infoProvisionalNoCancelOfStaffReceive($staffId = null, $date = null, $provisionalConfirm = 3, $keyWord = null, $orderBy = 'DESC')
    {
        # mac dinh khong huy
        $notCancel = $this->getDefaultNotCancel();
        if (empty($keyWord)) {
            if (!empty($date) && $provisionalConfirm < 3) {
                return QcOrder::where(['staffReceive_id' => $staffId])->where('cancelStatus', $notCancel)->where('provisionalDate', 'like', "%$date%")->where('provisionalConfirm', $provisionalConfirm)->orderBy('provisionalDate', $orderBy)->get();
            } else if (!empty($date) && $provisionalConfirm > 2) {
                return QcOrder::where(['staffReceive_id' => $staffId])->where('cancelStatus', $notCancel)->where('provisionalDate', 'like', "%$date%")->orderBy('provisionalDate', $orderBy)->get();
            } else if (empty($date) && $provisionalConfirm < 3) {
                return QcOrder::where(['staffReceive_id' => $staffId])->where('cancelStatus', $notCancel)->where('provisionalConfirm', $provisionalConfirm)->orderBy('provisionalDate', $orderBy)->get();
            } else {
                return QcOrder::where(['staffReceive_id' => $staffId])->where('cancelStatus', $notCancel)->orderBy('provisionalDate', $orderBy)->get();
            }
        } else {
            if (!empty($date) && $provisionalConfirm < 3) {
                return QcOrder::where(['staffReceive_id' => $staffId])->where('cancelStatus', $notCancel)->where('name', 'like', "%$keyWord%")->where('provisionalDate', 'like', "%$date%")->where('provisionalConfirm', $provisionalConfirm)->orderBy('provisionalDate', $orderBy)->get();
            } else if (!empty($date) && $provisionalConfirm > 2) {
                return QcOrder::where(['staffReceive_id' => $staffId])->where('cancelStatus', $notCancel)->where('name', 'like', "%$keyWord%")->where('provisionalDate', 'like', "%$date%")->orderBy('provisionalDate', $orderBy)->get();
            } else if (empty($date) && $provisionalConfirm < 3) {
                return QcOrder::where(['staffReceive_id' => $staffId])->where('cancelStatus', $notCancel)->where('name', 'like', "%$keyWord%")->where('provisionalConfirm', $provisionalConfirm)->orderBy('provisionalDate', $orderBy)->get();
            } else {
                return QcOrder::where(['staffReceive_id' => $staffId])->where('cancelStatus', $notCancel)->where('name', 'like', "%$keyWord%")->orderBy('provisionalDate', $orderBy)->get();
            }
        }
    }

    //---------- ---------- ---------- cong ty ----------- ---------- ----------
    public function company()
    {
        return $this->belongsTo('App\Models\Ad3d\Company\QcCompany', 'company_id', 'company_id');
    }

    # lay thong tin don hang quan ly thi cong CUA 1 CONG TY
    public function selectInfoManageConstructionOfCompany($companyId, $nameFiler = null, $dateFilter = null, $finishStatus = 100)
    {
        $dataOrder = null;
        if ($finishStatus == 100) { // 100 = tat ca don hang
            if (empty($nameFiler)) {
                if (empty($dateFilter)) {
                    $dataOrder = QcOrder::where('company_id', $companyId)->orderBy('created_at', 'DESC')->orderBy('orderCode', 'DESC')->select('*');

                } else {
                    $dataOrder = QcOrder::where('company_id', $companyId)->where('receiveDate', 'like', "%$dateFilter%")->orderBy('created_at', 'DESC')->orderBy('orderCode', 'DESC')->select('*');
                }

            } else {
                if (empty($dateFilter)) {
                    $dataOrder = QcOrder::where('name', 'like', "%$nameFiler%")->where('company_id', $companyId)->orderBy('created_at', 'DESC')->orderBy('orderCode', 'DESC')->select('*');
                } else {
                    $dataOrder = QcOrder::where('name', 'like', "%$nameFiler%")->where('company_id', $companyId)->where('created_at', 'like', "%$dateFilter%")->orderBy('receiveDate', 'DESC')->orderBy('orderCode', 'DESC')->select('*');
                }
            }

        } else {
            if (empty($nameFiler)) {
                $dataOrder = QcOrder::where('finishStatus', $finishStatus)->where('company_id', $companyId)->where('created_at', 'like', "%$dateFilter%")->orderBy('receiveDate', 'DESC')->orderBy('orderCode', 'DESC')->select('*');
            } else {
                $dataOrder = QcOrder::where('name', 'like', "%$nameFiler%")->where('finishStatus', $finishStatus)->where('company_id', $companyId)->where('receiveDate', 'like', "%$dateFilter%")->orderBy('receiveDate', 'DESC')->orderBy('orderCode', 'DESC')->select('*');
            }
        }
        return $dataOrder;
    }

    # lay thong tin cua 1 cong ty
    public function listIdOfCompanyAndName($companyId, $name = null)
    {
        if (empty($name)) {
            return QcOrder::where('company_id', $companyId)->pluck('order_id');
        } else {
            return QcOrder::where('company_id', $companyId)->where('name', 'like', "%$name%")->pluck('order_id');
        }
    }

    # lay thong tin theo danh sach cong ty
    public function listIdOfListCompanyAndName($listCompanyId, $name = null)
    {
        if (empty($name)) {
            return QcOrder::whereIn('company_id', $listCompanyId)->pluck('order_id');
        } else {
            return QcOrder::whereIn('company_id', $listCompanyId)->where('name', 'like', "%$name%")->pluck('order_id');
        }
    }

    public function listIdOfListCompany($listCompanyId, $dateFilter = null)
    {
        if (empty($dateFilter)) {
            return QcOrder::whereIn('company_id', $listCompanyId)->pluck('order_id');
        } else {
            return QcOrder::whereIn('company_id', $listCompanyId)->where('receiveDate', 'like', "%$dateFilter%")->pluck('order_id');
        }
    }

    public function listIdOfListCompanyAndReceiveDateName($companyId, $receiveDate, $name = null)
    {
        if (empty($receiveDate)) {
            if (empty($name)) {
                return QcOrder::whereIn('company_id', $companyId)->pluck('order_id');
            } else {
                return QcOrder::whereIn('company_id', $companyId)->where('name', 'like', "%$name%")->pluck('order_id');
            }
        } else {
            if (empty($name)) {
                return QcOrder::where('receiveDate', 'like', "%$receiveDate%")->whereIn('company_id', $companyId)->pluck('order_id');
            } else {
                return QcOrder::where('receiveDate', 'like', "%$receiveDate%")->whereIn('company_id', $companyId)->where('name', 'like', "%$name%")->pluck('order_id');
            }
        }

    }

    # thong tin don hang chua ket thuc cua 1 cong ty
    public function getInfoNotFinishOfCompany($companyId)
    {
        return QcOrder::where('company_id', $companyId)->where('finishStatus', 0)->orderBy('name', 'ASC')->get();
    }

    //---------- thương trien khai thi cong -----------
    public function bonus()
    {
        return $this->hasMany('App\Models\Ad3d\Bonus\QcBonus', 'order_id', 'orderConstruction_id');
    }

    //---------- ban giao don hang thi cong -----------
    public function orderAllocation()
    {
        return $this->hasMany('App\Models\Ad3d\OrderAllocation\QcOrderAllocation', 'order_id', 'order_id');
    }

    # lay thong tin ket thuc thi cong cua cong trinh
    public function infoAllocationFinish($orderId)
    {
        $modelOrderAllocation = new QcOrderAllocation();
        return $modelOrderAllocation->infoFinishOfOrder($orderId);
    }

    # kiem tra don hang bi tre
    public function checkLate($orderId)
    {
        $currentDate = date('Y-m-d');
        $deliveryDate = $this->deliveryDate($orderId);
        $lateStatus = false;
        if ($this->checkFinishStatus($orderId)) { # don hang da ket thuc
            $finishDate = $this->finishDate($orderId); # ngay bao ket thuc thi cong
            if ($finishDate > $deliveryDate) $lateStatus = true;
        } else { # don hang chua ket thuc
            if ($deliveryDate < $currentDate) $lateStatus = true;
        }
        return $lateStatus;
    }

    #kiem tra don hang cho duyet
    public function checkWaitConfirmFinish($orderId = null)
    {
        $modelOrderConstruction = new QcOrderAllocation();
        return $modelOrderConstruction->checkWaitConfirmFinishOfOrder($this->checkIdNull($orderId));
    }

    # kiem tra don hang co thu ho
    public function existOrderAllocationPaymentStatus($orderId = null)
    {
        $modelOrderConstruction = new QcOrderAllocation();
        return $modelOrderConstruction->existPaymentStatusOfOrder($this->checkIdNull($orderId));
    }

    # thong tin thu ho cua don hang
    public function infoOrderAllocationPaymentStatus($orderId = null)
    {
        $modelOrderConstruction = new QcOrderAllocation();
        return $modelOrderConstruction->infoPaymentStatusOfOrder($this->checkIdNull($orderId));
    }


    # thong tin dang hoat dong
    public function orderAllocationActivity($orderId = null)
    {
        $modelOrderConstruction = new QcOrderAllocation();
        return $modelOrderConstruction->infoActivityOfOrder($this->checkIdNull($orderId));
    }

    # tat ca thong tin
    public function orderAllocationInfoAll($orderId = null)
    {
        $modelOrderConstruction = new QcOrderAllocation();
        return $modelOrderConstruction->infoAllOfOrder($this->checkIdNull($orderId));
    }

    # thong tin ban giao thi cong sau cung
    public function orderAllocationLastInfo($orderId = null)
    {
        $modelOrderConstruction = new QcOrderAllocation();
        return $modelOrderConstruction->lastInfoOfOrder($this->checkIdNull($orderId));
    }

    # lay tat ca thong tin ban giao don hang
    public function orderAllocationAllInfo($orderId = null)
    {
        $modelOrderConstruction = new QcOrderAllocation();
        return $modelOrderConstruction->infoOfOrder($this->checkIdNull($orderId));
    }

    # kiem tra ton tai thong tin ban giao cua don hang
    public function existOrderAllocationOfOrder($orderId = null)
    {
        $modelOrderAllocation = new QcOrderAllocation();
        return $modelOrderAllocation->existInfoOfOrder($this->checkIdNull($orderId));
    }

    # ton tai thong tin ban giao thi cong dang co hieu luc
    public function existOrderAllocationActivityOfOrder($orderId = null)
    {
        $modelOrderAllocation = new QcOrderAllocation();
        return $modelOrderAllocation->existInfoActivityOfOrder($this->checkIdNull($orderId));
    }

    #kiem tra ton tai ban giao da hoan thanh da xac nhan
    public function existOrderAllocationFinishOfOrder($orderId = null)
    {
        $hFunction = new \Hfunction();
        $modelOrderAllocation = new QcOrderAllocation();
        $result = $modelOrderAllocation->infoFinishOfOrder($this->checkIdNull($orderId));
        return ($hFunction->checkCount($result)) ? true : false;
    }

    //---------- thong tin thi cong san pham -----------
    public function workAllocationOnProduct($orderId = null)
    {
        $modelWorkAllocation = new QcWorkAllocation();
        return $modelWorkAllocation->infoAllOfOrder($this->checkIdNull($orderId));
    }

    //---------- lay anh bao cao thi cong san pham -----------
    public function workAllocationReportImage($orderId, $take)
    {
        $modelWorkAllocationReportImage = new QcWorkAllocationReportImage();
        return $modelWorkAllocationReportImage->infoAllOfOrder($orderId, $take);
    }

    //---------- phat tien -----------
    public function minusMoney()
    {
        return $this->hasMany('App\Models\Ad3d\MinusMoney\QcMinusMoney', 'order_id', 'orderConstruction_id');
    }

    //---------- thong bao them don hang -----------
    public function staffNotify()
    {
        return $this->hasMany('App\Models\Ad3d\StaffNotify\QcStaffNotify', 'order_id', 'order_id');
    }

    //---------- huy don hang -----------
    public function orderCancel()
    {
        return $this->hasOne('App\Models\Ad3d\OrderCancel\QcOrderCancel', 'order_id', 'order_id');
    }

    public function orderCancelInfo($orderId = null)
    {
        $modelOrderCancel = new QcOrderCancel();
        return $modelOrderCancel->infoOfOrder($this->checkIdNull($orderId));
    }

    //---------- thanh toan don hang -----------
    public function orderPay()
    {
        return $this->hasMany('App\Models\Ad3d\OrderPay\QcOrderPay', 'order_id', 'order_id');
    }

    public function totalPaid($orderId = null)
    {
        $modelOrderPay = new QcOrderPay();
        return $modelOrderPay->totalPayOfOrder($this->checkIdNull($orderId));
    }

    public function totalPaidInDate($orderId, $date)
    {
        $modelOrderPay = new QcOrderPay();
        return $modelOrderPay->totalPayOfOrderAndDate($this->checkIdNull($orderId), $date);
    }

    public function infoOrderPayOfOrder($orderId = null)
    {
        $modelOrderPay = new QcOrderPay();
        return $modelOrderPay->infoOfOrder($this->checkIdNull($orderId));
    }

    //---------- product -----------
    public function product()
    {
        return $this->hasMany('App\Models\Ad3d\Product\QcProduct', 'order_id', 'order_id');
    }

    public function checkExistsProductNotFinish($orderId = null)
    {
        $modelProduct = new QcProduct();
        return $modelProduct->checkExistsProductNotFinishOfOrder($this->checkIdNull($orderId));
    }

    public function productActivityOfOrder($orderId = null)
    {
        $modelProduct = new QcProduct();
        return $modelProduct->infoActivityOfOrder($this->checkIdNull($orderId));
    }

    // tat ca san pham cua don hang bao dong da huy
    public function allProductOfOrder($orderId = null)
    {
        $modelProduct = new QcProduct();
        return $modelProduct->allInfoOfOrder($this->checkIdNull($orderId));
    }

    # tong tien don hang chua bao gom VAT
    public function totalPrice($orderId = null)
    {
        $modelProduct = new QcProduct();
        return $modelProduct->totalPriceOfOrder($this->checkIdNull($orderId));
    }

    # tong tien don hang da bao gom VAT
    public function totalPriceIncludeVat($orderId = null)
    {
        $orderId = $this->checkIdNull($orderId);
        return $this->totalPrice($orderId) - $this->totalMoneyDiscount($orderId) + $this->totalMoneyOfVat($orderId);
    }

    #tien Vat cua don hang - tinh sau khi giam gia
    public function totalMoneyOfVat($orderId = null)
    {
        $vat = $this->vat($orderId);
        $totalPrice = $this->totalPrice($orderId);
        $totalDiscount = $this->totalMoneyDiscount($orderId);
        return ($totalPrice - $totalDiscount) * $vat / 100;
    }

    # tong tien giam gia cua don hang
    public function totalMoneyDiscount($orderId = null)
    {
        $orderId = $this->checkIdNull($orderId);
        return ($this->totalPrice($orderId) * $this->discount($orderId)) / 100;
    }

    public function totalMoneyPayment($orderId = null)
    {
        return $this->totalPrice($orderId) - $this->totalMoneyDiscount($orderId) + $this->totalMoneyOfVat($orderId);
    }

    public function totalMoney($orderId = null)
    {
        return $this->totalPrice($orderId) + $this->totalMoneyOfVat($orderId);
    }

    public function totalMoneyOfListOrder($listOrder)
    {
        $totalMoney = 0;
        if (count($listOrder) > 0) {
            foreach ($listOrder as $key => $value) {
                $totalMoney = $totalMoney + $this->totalMoney($value['order_id']);
            }
        }
        return $totalMoney;
    }

    public function totalMoneyDiscountOfListOrder($listOrder)
    {
        $totalMoney = 0;
        if (count($listOrder) > 0) {
            foreach ($listOrder as $key => $value) {
                $totalMoney = $totalMoney + $this->totalMoneyDiscount($value['order_id']);
            }
        }
        return $totalMoney;
    }

    # tong tien thanh toan theo danh sach don hang
    public function totalMoneyPaidOfListOrder($listOrder)
    {
        $totalMoney = 0;
        if (count($listOrder) > 0) {
            foreach ($listOrder as $key => $value) {
                $totalMoney = $totalMoney + $this->totalPaid($value['order_id']);
            }
        }
        return $totalMoney;
    }

    public function totalMoneyUnPaidOfListOrder($listOrder)
    {
        $totalMoney = 0;
        if (count($listOrder) > 0) {
            foreach ($listOrder as $key => $value) {
                $totalMoney = $totalMoney + $this->totalMoneyUnpaid($value['order_id']);
            }
        }
        return $totalMoney;
    }

    public function totalMoneyUnpaid($orderId = null)
    {
        $orderId = $this->checkIdNull($orderId);
        $money = $this->totalMoneyPayment($orderId) - $this->totalPaid($orderId);
        return ($money > 0) ? $money : 0;
    }

    //---------- CHAY KPI -----------
    public function staffKpi()
    {
        return $this->belongsTo('App\Models\Ad3d\StaffKpi\QcStaffKpi', 'staffKpi_id', 'staffKpi_id');
    }

    //========= ========== ========== GET INFO ========== ========== ==========
    public function getInfo($orderId = '', $field = '')
    {
        if (empty($orderId)) {
            return QcOrder::get();
        } else {
            $result = QcOrder::where('order_id', $orderId)->first();
            if (empty($field)) {
                return $result;
            } else {
                return $result->$field;
            }
        }
    }

    // ---------- ---------- lay thong tin --------- -------
    # lay tat ca don hang chua ket thuc
    public function infoNoFinish()
    {
        return QcOrder::where('finishStatus', $this->getDefaultNotFinishStatus())->where('cancelStatus', $this->getDefaultNotCancel())->get();
    }

    # lay tat ca don hang
    public function infoFromSuggestionName($name)
    {
        return QcOrder::where('name', 'like', "%$name%")->get();
    }

    # lay tat ca don hang khong huy
    public function infoNoCancelFromSuggestionName($name)
    {
        return QcOrder::where('name', 'like', "%$name%")->where('cancelStatus', $this->getDefaultNotCancel())->get();
    }


    public function infoFromSuggestionNameOffReceiveStaff($staffId, $name)
    {
        return QcOrder::where('staffReceive_id', $staffId)->where('name', 'like', "%$name%")->get();
    }

    public function orderUnpaid($companyId = null)
    {
        # chua thanh toan
        $notPayment = $this->getDefaultNotPayment();
        # dang hoat dong
        $hasAction = $this->getDefaultHasAction();
        if (empty($companyId)) {
            return QcOrder::where('paymentStatus', $notPayment)->where('action', $hasAction)->orderBy('receiveDate', 'DESC')->get();
        } else {
            return QcOrder::where('company_id', $companyId)->where('paymentStatus', $notPayment)->where('action', $hasAction)->orderBy('receiveDate', 'DESC')->get();
        }
    }

    public function infoFromOrderCode($orderCode)
    {
        return QcOrder::where('orderCode', $orderCode)->first();
    }

    public function infoFromKeyword($keyword)
    {
        return QcOrder::where('name', 'like', "%$keyword%")->get();
    }

    public function listIdFromKeyword($keyword)
    {
        return QcOrder::where('name', 'like', "%$keyword%")->pluck('order_id');
    }

    public function pluck($column, $objectId = null)
    {
        if (empty($objectId)) {
            return $this->$column;
        } else {
            return QcOrder::where('order_id', $objectId)->pluck($column)[0];
        }
    }

    public function orderId()
    {
        return $this->order_id;
    }

    public function name($orderId = null)
    {
        $hFunction = new \Hfunction();
        return $hFunction->htmlEntities($this->pluck('name', $orderId));
    }

    public function constructionAddress($orderId = null)
    {
        $hFunction = new \Hfunction();
        return $hFunction->htmlEntities($this->pluck('constructionAddress', $orderId));
    }

    public function constructionPhone($orderId = null)
    {
        $hFunction = new \Hfunction();
        return $hFunction->htmlEntities($this->pluck('constructionPhone', $orderId));
    }

    public function constructionContact($orderId = null)
    {
        $hFunction = new \Hfunction();
        return $hFunction->htmlEntities($this->pluck('constructionContact', $orderId));
    }

    public function orderCode($orderId = null)
    {
        return $this->pluck('orderCode', $orderId);
    }

    public function discount($orderId = null)
    {
        return $this->pluck('discount', $orderId);
    }

    public function vat($orderId = null)
    {

        return $this->pluck('vat', $orderId);
    }

    public function receiveDate($orderId = null)
    {
        return $this->pluck('receiveDate', $orderId);
    }

    public function deliveryDate($orderId = null)
    {
        return $this->pluck('deliveryDate', $orderId);
    }

    public function finishDate($orderId = null)
    {
        return $this->pluck('finishDate', $orderId);
    }

    public function finishStatus($orderId = null)
    {
        return $this->pluck('finishStatus', $orderId);
    }

    public function paymentStatus($orderId = null)
    {
        return $this->pluck('paymentStatus', $orderId);
    }

    public function staffKpiId($orderId = null)
    {
        return $this->pluck('staffKpi_id', $orderId);
    }

    public function confirmStatus($orderId = null)
    {
        return $this->pluck('confirmStatus', $orderId);
    }

    public function confirmAgree($orderId = null)
    {
        return $this->pluck('confirmAgree', $orderId);
    }

    public function confirmDate($orderId = null)
    {
        return $this->pluck('confirmDate', $orderId);
    }

    public function staffConfirmId($orderId = null)
    {
        return $this->pluck('staffConfirm_id', $orderId);
    }

    public function provisionalStatus($orderId = null)
    {
        return $this->pluck('provisionalStatus', $orderId);
    }

    public function provisionalDate($orderId = null)
    {
        return $this->pluck('provisionalDate', $orderId);
    }

    public function provisionalConfirm($orderId = null)
    {
        return $this->pluck('provisionalConfirm', $orderId);
    }


    public function cancelStatus($orderId = null)
    {
        return $this->pluck('cancelStatus', $orderId);
    }

    public function companyId($orderId = null)
    {
        return $this->pluck('company_id', $orderId);
    }

    public function customerId($orderId = null)
    {
        return $this->pluck('customer_id', $orderId);
    }

    public function staff_id($orderId = null)
    {
        return $this->pluck('staff_id', $orderId);
    }

    public function staffId($orderId = null)
    {
        return $this->pluck('staff_id', $orderId);
    }

    public function staffReceiveId($orderId = null)
    {
        return $this->pluck('staffReceive_id', $orderId);
    }

    public function action($orderId = null)
    {
        return $this->pluck('action', $orderId);
    }

    public function createdAt($orderId = null)
    {
        return $this->pluck('created_at', $orderId);
    }

// total records
    public function totalRecords()
    {
        return QcOrder::count();
    }

// lay id cuoi
    public function lastId()
    {
        $result = QcOrder::orderBy('order_id', 'DESC')->first();
        return (empty($result)) ? 0 : $result->order_id;
    }

    public function totalUnpaid($orderId = null)
    {
        return $this->totalMoneyPayment($orderId) - $this->totalPaid($orderId);
    }

    //----------------- kiem tra thong tin --------------
    // kiem tra don hang bao gia
    public function checkProvisionalOfOrder($orderId = null)
    {
        return ($this->provisionalStatus($orderId) == $this->getDefaultProvisionalOrder()) ? true : false;
    }


    // kiem tra don hang da dat hàng tu bao gia
    public function checkProvisionConfirm($orderId = null)
    {
        if ($this->checkProvisionalOfOrder($orderId) && $this->provisionalConfirm($orderId) == $this->getDefaultHasProvisionalConfirm()) {
            return true;
        } else {
            return false;
        }
    }

    // kiem tra don hang bao gia chua xac nhan (chua dat hang)
    public function checkProvisionUnConfirmed($orderId)
    {
        if ($this->checkProvisionalOfOrder($orderId) && $this->provisionalConfirm($orderId) == $this->getDefaultNotProvisionalConfirm()) {
            return true;
        } else {
            return false;
        }
    }

    // kiem tra hang sua don hang
    public function checkExpiredEdit($orderId = null, $hourExpire = 8)
    {
        $hFunction = new \Hfunction();
        $timeCheck = $hFunction->datetimePlusHour($this->createdAt($orderId), $hourExpire);
        $currentDate = $hFunction->carbonNow();
        return ($timeCheck < $currentDate) ? false : true;
    }

    #ton tai ma don hang
    public function existOrderCode($orderCode)
    {
        return QcOrder::where('orderCode', $orderCode)->exists();
    }


    # kiem tra ket thuc thanh toan
    public function checkFinishPayment($orderId = null)
    {
        $orderId = $this->checkIdNull($orderId);
        # tong tien cua don hang
        $totalMoneyPayment = $this->totalMoneyPayment($orderId);
        # tong tien da thanh toan
        $totalPaid = $this->totalPaid($orderId);
        if ($totalMoneyPayment > $totalPaid) {
            $this->cancelFinishPayment($orderId);
            return false;
        } else {
            return true;
        }
    }

    # kiem tra trang thai ket thuc thanh toan cua don hang
    public function checkPaymentStatus($orderId = null)
    {
        return ($this->paymentStatus($orderId) == $this->getDefaultHasPayment()) ? true : false;
    }

    # trang thai huy
    public function checkCancelStatus($orderId = null)
    {
        return ($this->cancelStatus($orderId) == $this->getDefaultHasCancel()) ? true : false;
    }

    #trang thai hoat dong
    public function checkActionStatus($orderId = null)
    {
        return ($this->action($orderId) == $this->getDefaultHasAction()) ? true : false;
    }

    # trang thai xac nhan hay chua
    public function checkConfirmStatus($orderId = null)
    {
        return ($this->confirmStatus($orderId) == $this->getDefaultHasConfirm()) ? true : false;
    }

    # kiem tra ket thuc don hang
    public function checkFinishStatus($orderId = null)
    {
        return ($this->finishStatus($orderId) == $this->getDefaultHasFinishStatus()) ? true : false;
    }

    # xac nhan dong y don hang
    public function checkConfirmAgree($orderId = null)
    {
        return ($this->confirmAgree($orderId) == $this->getDefaultHasAgree()) ? true : false;
    }

    public function checkHasVAT($orderId = null)
    {
        return ($this->vat($orderId) > 0) ? true : false;
    }

    # Tong tien thuong - phạt tren don hang cua bo phan thi cong cap quan ly
    public function getBonusAndMinusMoneyOfConstructionManage($orderId = null)
    {
        $modelOrderBonusBudget = new QcOrderBonusBudget();
        return $modelOrderBonusBudget->totalBudgetMoneyOfConstructionManage($this->checkIdNull($orderId));
    }

    # Tong tien thuong - phạt tren don hang cua bo phan cap nhan vien
    public function getBonusAndMinusMoneyOfConstructionRank($orderId = null)
    {
        $modelOrderBonusBudget = new QcOrderBonusBudget();
        return $modelOrderBonusBudget->totalBudgetMoneyOfConstructionStaff($this->checkIdNull($orderId));

    }

    //============ =========== ============ KIEM TRA THONG TIN ============= =========== ==========
    # kiem tra cap nhat trang thai thanh toan don hang cu
    public function checkUpdatePaymentStatus()
    {
        $hFunction = new \Hfunction();
        $dataOrder = $this->getInfo();
        if ($hFunction->checkCount($dataOrder)) {
            foreach ($dataOrder as $order) {
                if ($order->checkFinishPayment()) {
                    $order->finishPayment();
                } else {
                    $order->cancelFinishPayment();
                }
            }
        }
    }
}
