<?php

namespace App\Http\Controllers\Ad3d\Work\WorkAllocation;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\Customer\QcCustomer;
use App\Models\Ad3d\Order\QcOrder;
use App\Models\Ad3d\OrderPay\QcOrderPay;
use App\Models\Ad3d\Product\QcProduct;
use App\Models\Ad3d\ProductType\QcProductType;
use App\Models\Ad3d\Staff\QcStaff;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ad3d\WorkAllocation\QcWorkAllocation;
use File;
use Illuminate\Support\Facades\Session;
use Input;
use Request;

class WorkAllocationController extends Controller
{
    public function index($dayFilter = 0, $monthFilter = 0, $yearFilter = 0, $finishStatus = 2, $nameFiler = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelWorkAllocation = new QcWorkAllocation();

        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataAccess = [
            'accessObject' => 'workAllocation',
            'subObject' => 'workAllocation'
        ];
        if (empty($companyFilterId)) {
            $companyFilterId = $dataStaffLogin->companyId();
        }

        $searchCompanyFilterId = [$companyFilterId];

        if (!empty($nameFiler)) {
            $listStaffId = $modelStaff->listIdOfListCompanyAndName($searchCompanyFilterId, $nameFiler);
        } else {
            $listStaffId = $modelStaff->listIdOfListCompany($searchCompanyFilterId);
        }

        $currentMonth = $hFunction->currentMonth();
        $currentYear = $hFunction->currentYear();
        $dateFilter = null;
        if ($yearFilter == 100) { # lay tat ca thong tin
            $dayFilter = null;
            $dayFilter = 100;
            $monthFilter = 100;
        } elseif ($dayFilter == 0 && $monthFilter == 0 && $yearFilter == 0) { //xem  trong tháng
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
        #tat ca dang thi cong va ket thuc
        $dataWorkAllocation = $modelWorkAllocation->selectInfoOfReceiveListStaff($listStaffId, $finishStatus, $dateFilter)->paginate(30);
        return view('ad3d.work.work-allocation.allocation.list', compact('modelStaff', 'dataAccess', 'dataWorkAllocation', 'companyFilterId', 'dayFilter', 'monthFilter', 'yearFilter', 'finishStatus', 'nameFiler'));

    }

    public function view($allocationId)
    {
        $modelWorkAllocation = new QcWorkAllocation();
        $dataAccess = [
            'accessObject' => 'workAllocation'
        ];
        $dataWorkAllocation = $modelWorkAllocation->getInfo($allocationId);
        if (count($dataWorkAllocation) > 0) {
            return view('ad3d.work.work-allocation.allocation.view', compact('dataAccess', 'dataWorkAllocation'));
        }

    }


    public function cancelWorkAllocation($allocationId = null)
    {
        $hFunction = new \Hfunction();
        $modelWorkAllocation = new QcWorkAllocation();
        if (!empty($allocationId)) {
            $modelWorkAllocation->confirmFinish($allocationId, $hFunction->carbonNow(), 0, 1);
        }
    }

}
