<?php

namespace App\Http\Controllers\Work\WorkAllocation;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\Customer\QcCustomer;
use App\Models\Ad3d\Order\QcOrder;
use App\Models\Ad3d\Staff\QcStaff;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WorkAllocationManageController extends Controller
{
    public function index($dayFilter = 0, $monthFilter = 0, $yearFilter = 0, $orderFilterName = null, $orderCustomerFilterName = null, $staffFilterId = 0)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCustomer = new QcCustomer();
        $modelCompany = new QcCompany();
        $modelOrder = new QcOrder();

        $dataAccess = [
            'object' => 'workAllocationManage'
        ];
        $currentMonth = $hFunction->currentMonth();
        $currentYear = $hFunction->currentYear();
        $orderFilterName = ($orderFilterName == 'null') ? null : $orderFilterName;
        $orderCustomerFilterName = ($orderCustomerFilterName == 'null') ? null : $orderCustomerFilterName;
        //$staffFilterName = ($staffFilterId == 'null') ? null : $staffFilterName;
        $dataStaffLogin = $modelStaff->loginStaffInfo();

        $dateFilter = null;
        if ($dayFilter == 0 && $monthFilter == 0 && $yearFilter == 0) { //xem  trong tháng
            $dayFilter = 100;
            $monthFilter = date('m');
            $yearFilter = date('Y');
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        } elseif ($dayFilter == 100 && $monthFilter == 100 && $yearFilter > 100) { //xem tất cả các ngày trong tháng
            $dateFilter = date('Y', strtotime("1-1-$yearFilter"));
        } elseif ($dayFilter == 100 && $monthFilter > 0 && $monthFilter < 100 && $yearFilter > 100) { //xem tất cả các ngày trong tháng
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        } elseif ($dayFilter < 100 && $dayFilter > 0 && $monthFilter > 0 && $monthFilter < 100 && $yearFilter > 100) { //xem tất cả các ngày trong tháng
            $monthFilter = $currentMonth;
            $yearFilter = $currentYear;
            $dateFilter = date('Y-m-d', strtotime("$dayFilter-$currentMonth-$currentYear"));
        } elseif ($dayFilter == 100 && $monthFilter == 100 && $yearFilter == 100) { //xem tất cả
            $dateFilter = null;
        } else {
            $dateFilter = date('Y-m');
            $dayFilter = 100;
            $monthFilter = date('m');
            $yearFilter = date('Y');
        }
        $searchCompanyFilterId = [$dataStaffLogin->companyId()];
        if ($staffFilterId > 0) {
            $listStaffId = [$staffFilterId];
        } else {
            $listStaffId = $modelStaff->listIdOfListCompany($searchCompanyFilterId);
        }


        if (!empty($orderCustomerFilterName)) {
            $dataOrderSelect = $modelOrder->selectInfoOfListCustomer($modelCustomer->listIdByKeywordName($orderCustomerFilterName), $dateFilter, 2);
        } else {

            $dataOrderSelect = $modelOrder->selectInfoByListStaffAndNameAndDateAndPayment($listStaffId, $orderFilterName, $dateFilter, 2);
        }

        $dataOrder = $dataOrderSelect->paginate(50);
        //dd($dataOrder);
        //danh sach NV
        $dataStaff = $modelCompany->staffInfoActivityOfListCompanyId([$searchCompanyFilterId]);
        return view('work.work-allocation.orders.index', compact('modelStaff', 'dataStaff', 'dataAccess', 'dataOrder', 'dayFilter', 'monthFilter', 'yearFilter', 'orderFilterName', 'orderCustomerFilterName', 'staffFilterId'));

    }

    // theo ten don hang
    public function filterCheckOrderName($name)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelOrder = new QcOrder();
        $dataOrder = $modelOrder->infoNoCancelFromSuggestionName($name);
        if ($hFunction->checkCount($dataOrder)) {
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

    // theo ten khach hang
    public function filterCheckCustomerName($name)
    {
        $hFunction = new \Hfunction();
        $modelCustomer = new QcCustomer();
        $dataCustomer = $modelCustomer->infoFromSuggestionName($name);
        if ($hFunction->checkCount($dataCustomer)) {
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

}
