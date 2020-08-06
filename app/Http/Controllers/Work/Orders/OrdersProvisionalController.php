<?php

namespace App\Http\Controllers\Work\Orders;

use App\Models\Ad3d\Customer\QcCustomer;
use App\Models\Ad3d\Order\QcOrder;
use App\Models\Ad3d\OrderAllocation\QcOrderAllocation;
use App\Models\Ad3d\OrderPay\QcOrderPay;
use App\Models\Ad3d\Staff\QcStaff;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ad3d\StaffNotify\QcStaffNotify;
use File;
use Illuminate\Support\Facades\Session;
use Input;
use Request;

class OrdersProvisionalController extends Controller
{
    public function index($monthFilter = 0, $yearFilter = 0, $orderFilterName = null, $orderCustomerFilterName = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCustomer = new QcCustomer();
        $modelOrders = new QcOrder();
        $hFunction->dateDefaultHCM();
        if ($modelStaff->checkLogin()) {
            $dataAccess = [
                'object' => 'ordersProvisional'
            ];
            $dataStaffLogin = $modelStaff->loginStaffInfo();
            $loginStaffId = $dataStaffLogin->staffId();
            $orderFilterName = ($orderFilterName == 'null') ? null : $orderFilterName;
            $orderCustomerFilterName = ($orderCustomerFilterName == 'null') ? null : $orderCustomerFilterName;
            if ($monthFilter == 100 && $yearFilter == 100) {//xem tất cả đơn hang
                $dateFilter = null;
            } elseif ($monthFilter < 100 && $yearFilter == 100) {
                $dateFilter = date('Y');
                $yearFilter = date('Y');
            } elseif ($monthFilter == 100 && $yearFilter != 100) {
                $yearFilter = date('Y');
                $dateFilter = date('Y', strtotime("1-1-$yearFilter"));
            } elseif ($monthFilter < 100 && $yearFilter == 100) {
                $yearFilter = $hFunction->currentYear();
                $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
            } elseif ($monthFilter == 0 && $yearFilter == 0) {
                $dateFilter = date('Y-m');
                $monthFilter = date('m');
                $yearFilter = date('Y');

            } else {
                $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
            }
            if (!empty($orderCustomerFilterName)) {
                $dataOrders = $modelOrders->infoProvisionalNoCancelOfListCustomer($modelCustomer->listIdByKeywordName($orderCustomerFilterName), $dateFilter);
            } else {
                $dataOrders = $dataStaffLogin->orderProvisionNoCancelAndPayInfoOfStaffReceive($loginStaffId, $dateFilter, 0, $orderFilterName);
            }

            return view('work.orders.provisional.index', compact('modelOrders', 'dataAccess', 'modelStaff', 'dataOrders', 'dataStaffLogin', 'dateFilter', 'monthFilter', 'yearFilter', 'provisionalConfirm', 'orderFilterName', 'orderCustomerFilterName'));
        } else {
            return view('work.login');
        }
    }

    # thong tin chi tiet
    public function viewOrders($orderId)
    {
        $modelOrder = new QcOrder();
        $dataAccess = [
            'object' => 'orders',
            'subObjectLabel' => 'Chi tiết đơn hàng'
        ];
        $dataOrders = $modelOrder->getInfo($orderId);
        if (count($dataOrders) > 0) {
            return view('work.orders.provisional.view', compact('dataAccess', 'dataOrders'));
        }

    }

    # xem thong tin khach hang
    public function viewCustomer($customerId)
    {
        $modelCustomer = new QcCustomer();
        $dataCustomer = $modelCustomer->getInfo($customerId);
        if (count($dataCustomer) > 0) {
            $dataOrders = $modelCustomer->orderInfoAllOfCustomer($dataCustomer->customerId());
            return view('work.orders.customer.view', compact('dataCustomer', 'dataOrders'));
        }
    }

    #in bao gia
    public function printOrders($orderId)
    {
        $modelStaff = new QcStaff();
        $modelOrder = new QcOrder();
        $dataAccess = [
            'object' => 'orders'
        ];
        if ($modelStaff->checkLogin()) {
            $dataOrders = $modelOrder->getInfo($orderId);
            if (count($dataOrders) > 0) {
                return view('work.orders.provisional.print', compact('modelStaff', 'dataAccess', 'dataOrders'));
            }
        } else {
            return view('work.login');
        }

    }
    // ================ LOC DON HÀNG DON HANG ===================
    # theo ten don hang
    public function filterCheckOrderName($name)
    {
        $modelStaff = new QcStaff();
        $modelOrder = new QcOrder();
        $dataOrder = $modelOrder->infoFromSuggestionNameOffReceiveStaff($modelStaff->loginStaffId(), $name);
        if (count($dataOrder) > 0) {
            $result = array(
                'status' => 'exist',
                'content' => $dataOrder
            );
        } else {
            $result = array(
                'status' => 'notExist',
                'content' => "null"
            );
        }
        die(json_encode($result));
    }

    #theo ten khach hang
    public function filterCheckCustomerName($name)
    {
        $modelCustomer = new QcCustomer();
        $dataCustomer = $modelCustomer->infoFromSuggestionName($name);
        if (count($dataCustomer) > 0) {
            $result = array(
                'status' => 'exist',
                'content' => $dataCustomer
            );
        } else {
            $result = array(
                'status' => 'notExist',
                'content' => "null"
            );
        }
        die(json_encode($result));
    }

    // ================ XAC NHAN DAT HANG ===================
    public function getConfirm($orderId)
    {
        $hFunction = new \Hfunction();
        $modelOrder = new QcOrder();
        $dataOrder = $modelOrder->getInfo($orderId);
        if ($hFunction->checkCount($dataOrder)) {
            return view('work.orders.provisional.confirm', compact('dataOrder'));
        }
    }

    public function postConfirm($orderId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelOrder = new QcOrder();
        $modelStaffNotify = new QcStaffNotify();
        $modelOrderAllocation = new QcOrderAllocation();
        $modelOrderPay = new QcOrderPay();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataOrder = $modelOrder->getInfo($orderId);
        $txtPayMoney = Request::input('txtMoney');
        $txtPayMoney = $hFunction->convertCurrencyToInt($txtPayMoney);
        $txtDateReceive = Request::input('txtDateReceive');
        $txtDateDelivery = Request::input('txtDateDelivery');
        $updateStatus = false;
        if ($hFunction->checkCount($dataStaffLogin)) {
            $staffLoginId = $dataStaffLogin->staffId();
            if ($hFunction->checkCount($dataOrder)) {
                $updateStatus = true;
                $dataCustomer = $dataOrder->customer;
                if ($modelOrder->confirmProvision($orderId, $txtDateReceive, $txtDateDelivery)) {
                    # thanh toan
                    if ($txtPayMoney > 0) {
                        # thanh toan don hang
                        if($modelOrderPay->insert($txtPayMoney, null, $txtDateReceive, $orderId, $staffLoginId, $dataCustomer->name(), $dataCustomer->phone())){
                            # xet thuong
                            $modelOrderPay->applyBonusDepartmentBusiness($modelOrderPay->insertGetId());
                        }
                        # cap nhat thong tin thanh toan don hang
                        $modelOrder->updateFinishPayment($orderId);
                        # bàn giao don hang = cong trinh
                        //$modelOrderAllocation->insert($txtDateReceive, 0, $txtDateDelivery, 'Bàn giao khi nhận đơn hàng', $orderId, $staffLoginId, null);
                    }
                    # thong bao them don hang
                    $listStaffReceiveNotify = $modelStaff->infoStaffReceiveNotifyNewOrder($dataStaffLogin->companyId());
                    if ($hFunction->checkCount($listStaffReceiveNotify)) {
                        foreach ($listStaffReceiveNotify as $staff) {
                            $modelStaffNotify->insert($orderId, $staff->staffId(), null);
                        }
                    }
                }

            }
        }
        return view('work.orders.provisional.confirm-notify', compact('dataOrder', 'updateStatus'));
    }

    // ================ HUY DON HANG ===================
    public function cancelOrders($orderId = null)
    {
        $modelOrder = new QcOrder();
        if (!empty($orderId)) {
            $modelOrder->cancelOrderProvisional($orderId);
        }
    }
}
