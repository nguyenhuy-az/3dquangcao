<?php

namespace App\Http\Controllers\Work\Orders;

use App\Models\Ad3d\Customer\QcCustomer;
use App\Models\Ad3d\Department\QcDepartment;
use App\Models\Ad3d\Order\QcOrder;
use App\Models\Ad3d\Staff\QcStaff;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrdersManageController extends Controller
{
    public function index($finishStatus = 0, $monthFilter = 0, $yearFilter = 0, $paymentStatus = 3, $orderFilterName = null, $orderCustomerFilterName = null, $staffFilterId = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelDepartment = new QcDepartment();
        $modelOrders = new QcOrder();
        $modelCustomer = new QcCustomer();
        $hFunction->dateDefaultHCM();
        $orderFilterName = ($orderFilterName == 'null') ? null : $orderFilterName;
        $orderCustomerFilterName = ($orderCustomerFilterName == 'null') ? null : $orderCustomerFilterName;
        $dataAccess = [
            'object' => 'ordersManage'
        ];
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $loginStaffId = $dataStaffLogin->staffId();
        //$dateFilter = null;
        if ($monthFilter == 100 && $yearFilter == 100) {//xem tất cả đơn hang
            $dateFilter = null;
        } elseif ($monthFilter < 100 && $yearFilter == 100) {
            $dateFilter = date('Y');
            $yearFilter = date('Y');
        } elseif ($monthFilter == 100 && $yearFilter != 100) {
            if ($yearFilter == 0) $yearFilter = date('Y');// else $yearFilter =
            $dateFilter = date('Y', strtotime("1-1-$yearFilter"));
        } elseif ($monthFilter < 100 && $yearFilter == 100) {
            $yearFilter = $hFunction->currentYear();
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        } elseif ($monthFilter == 0 && $yearFilter == 0) {
            $dateFilter = null;// date('Y-m');
            //$monthFilter = date('m');
            //$yearFilter = date('Y');

        } else {
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        }
        if (!$hFunction->checkEmpty($orderCustomerFilterName)) {
            $dataOrderSelect = $modelOrders->selectInfoNoCancelOfListCustomer($modelCustomer->listIdByKeywordName($orderCustomerFilterName), $dateFilter, $paymentStatus, $finishStatus);
        } else {
            $dataOrderSelect = $dataStaffLogin->selectOrderNoCancelAndPayInfoOfStaffReceive($loginStaffId, $dateFilter, $paymentStatus, $finishStatus, $orderFilterName);
        }
        $dataOrders = $dataOrderSelect->paginate(50);
        $dataOrdersProvisional = $dataStaffLogin->orderProvisionNoCancelAndPayInfoOfStaffReceive($loginStaffId, $dateFilter, 0, null);
        //$dataStaffReceiveTransfer = $modelStaff->infoActivityOfCompany($dataStaffLogin->companyId(), $modelDepartment->treasurerDepartmentId());

        if ($staffFilterId > 0) {
            //$listStaffId = [$staffFilterId];
        } else {
           // $listStaffId = $modelStaff->listIdOfListCompany($searchCompanyFilterId);
        }
        if ($dataStaffLogin->checkBusinessDepartmentAndManageRank()) {

        }
        return view('work.orders.manage.index', compact('modelOrders', 'dataAccess', 'modelStaff', 'dataOrders', 'dataOrdersProvisional', 'dataStaffLogin', 'dateFilter', 'finishStatus', 'monthFilter', 'yearFilter', 'paymentStatus', 'orderFilterName', 'orderCustomerFilterName'));

    }
}
