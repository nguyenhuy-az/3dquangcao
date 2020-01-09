<?php

namespace App\Http\Controllers\Ad3d\Finance\OrderPayment;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\Order\QcOrder;
use App\Models\Ad3d\OrderPay\QcOrderPay;
use App\Models\Ad3d\Staff\QcStaff;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ad3d\TransfersDetail\QcTransfersDetail;
use File;
use Illuminate\Support\Facades\Session;
use Input;
use Request;

class OrderPaymentController extends Controller
{
    public function index($companyFilterId = null, $dayFilter = null, $monthFilter = null, $yearFilter = null, $orderFilterName = null, $staffFilterId = null, $transferStatus = 2)
    {
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelOrder = new QcOrder();
        $modelTransferDetail = new QcTransfersDetail();

        $orderFilterName = ($orderFilterName == 'null') ? null : $orderFilterName;
        $dataStaffLogin = $modelStaff->loginStaffInfo();

        $dataAccess = [
            'accessObject' => 'orderPayment'
        ];
        if (empty($dayFilter) && empty($monthFilter) && empty($yearFilter)) {
            $dateFilter = date('Y-m');
            $monthFilter = date('m');
            $yearFilter = date('Y');
        } elseif ($dayFilter == 0) { //xem tất cả các ngày trong tháng
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        } else {
            $dateFilter = date('Y-m-d', strtotime("$dayFilter-$monthFilter-$yearFilter"));
        }
        $dataCompany = $modelCompany->getInfo();
        if ($dataStaffLogin->checkRootManage()) {
            if (empty($companyFilterId)) {
                $searchCompanyFilterId = [$dataStaffLogin->companyId()];//$modelCompany->listIdActivity();
                $companyFilterId = $dataStaffLogin->companyId();
            } else {
                $searchCompanyFilterId = [$companyFilterId];
            }
        } else {
            $searchCompanyFilterId = [$dataStaffLogin->companyId()];
            $companyFilterId = $dataStaffLogin->companyId();
        }

        if ($staffFilterId > 0) {
            $listStaffId = [$staffFilterId];
        } else {
            $listStaffId = $modelStaff->listIdOfListCompany($searchCompanyFilterId);
        }

        $listOrderId = $modelOrder->listIdOfListCompanyAndName($searchCompanyFilterId, $orderFilterName);
        if (count($listOrderId) > 0) {
            if ($transferStatus == 1) { // da giao
                $listPayIdTransferred = $modelTransferDetail->listPayId();
                $dataOrderPay = QcOrderPay:: whereIn('pay_id', $listPayIdTransferred)->whereIn('staff_id', $listStaffId)->whereIn('order_id', $listOrderId)->where('datePay', 'like', "%$dateFilter%")->orderBy('datePay', 'DESC')->select('*')->paginate(30);
                $totalOrderPay = QcOrderPay::whereIn('pay_id', $listPayIdTransferred)->whereIn('staff_id', $listStaffId)->whereIn('order_id', $listOrderId)->where('datePay', 'like', "%$dateFilter%")->sum('money');
            } elseif ($transferStatus == 0) { // chua giao
                $listPayIdTransferred = $modelTransferDetail->listPayId();
                $dataOrderPay = QcOrderPay:: whereNotIn('pay_id', $listPayIdTransferred)->whereIn('staff_id', $listStaffId)->whereIn('order_id', $listOrderId)->where('datePay', 'like', "%$dateFilter%")->orderBy('datePay', 'DESC')->select('*')->paginate(30);
                $totalOrderPay = QcOrderPay::whereNotIn('pay_id', $listPayIdTransferred)->whereIn('staff_id', $listStaffId)->whereIn('order_id', $listOrderId)->where('datePay', 'like', "%$dateFilter%")->sum('money');
            } else {
                $dataOrderPay = QcOrderPay::whereIn('staff_id', $listStaffId)->whereIn('order_id', $listOrderId)->where('datePay', 'like', "%$dateFilter%")->orderBy('datePay', 'DESC')->select('*')->paginate(30);
                $totalOrderPay = QcOrderPay::whereIn('staff_id', $listStaffId)->whereIn('order_id', $listOrderId)->where('datePay', 'like', "%$dateFilter%")->sum('money');
            }
        } else {
            $dataOrderPay = null;
            $totalOrderPay = null;
        }

        //danh sach NV
        $dataStaff = $modelCompany->staffInfoActivityOfListCompanyId([$companyFilterId]);
        return view('ad3d.finance.order-payment.list', compact('modelStaff', 'dataCompany', 'dataAccess', 'dataStaff', 'dataOrderPay', 'totalOrderPay', 'companyFilterId', 'dayFilter', 'monthFilter', 'yearFilter', 'orderFilterName', 'staffFilterId', 'transferStatus'));

    }

    public function view($payId)
    {
        $modelOrderPay = new QcOrderPay();
        $dataOrderPay = $modelOrderPay->getInfo($payId);
        if (count($dataOrderPay) > 0) {
            return view('ad3d.finance.order-payment.view', compact('dataOrderPay'));
        }

    }

    public function getAdd($selectOrderId = null)
    {
        $modelStaff = new QcStaff();
        $modelOrder = new QcOrder();
        $dataAccess = [
            'accessObject' => 'orderPayment'
        ];
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataOrder = $modelOrder->orderUnpaid($dataStaffLogin->companyId());
        return view('ad3d.finance.order-payment.add', compact('modelStaff', 'modelOrder', 'dataAccess', 'dataOrder', 'selectOrderId'));
    }

    public function postAdd()
    {
        $hFunction = new \Hfunction();
        $modelOrder = new QcOrder();
        $modelOrderPay = new QcOrderPay();

        $modelStaff = new QcStaff();

        $staffLoginId = $modelStaff->loginStaffId();
        //customer info
        $cbOrder = Request::input('cbOrder');
        $txtMoneyPay = Request::input('txtMoneyPay');
        $txtMoneyPay = $hFunction->convertCurrencyToInt($txtMoneyPay);
        $txtDatePay = Request::input('txtDatePay');
        $dataOrder = $modelOrder->getInfo($cbOrder);
        $totalUnpaid = $dataOrder->totalUnpaid();

        if ($modelOrderPay->insert($txtMoneyPay, null, $txtDatePay, $cbOrder, $staffLoginId)) {
            if ($txtMoneyPay == $totalUnpaid) {
                $modelOrder->updateFinishPayment($cbOrder);
            }
            return Session::put('notifyAdd', 'Thanh toán thành công, Nhập thông tin để tiếp tục');
        } else {
            return Session::put('notifyAdd', 'Thanh toán thất bại, hãy thử lại');
        }


    }

    public function cancelOrderPay($payId = null)
    {
        $modelOrderPay = new QcOrderPay();
        if (!empty($payId)) {
            $modelOrderPay->deleteOrderPay($payId);
        }
    }
}
