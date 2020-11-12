<?php

namespace App\Http\Controllers\Ad3d\Work\WorkAllocationReport;

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
use App\Models\Ad3d\WorkAllocationReport\QcWorkAllocationReport;
use App\Models\Ad3d\WorkAllocationReportImage\QcWorkAllocationReportImage;
use File;
use Illuminate\Support\Facades\Session;
use Input;
use Request;

class WorkAllocationReportController extends Controller
{
    public function index($dayFilter = 0, $monthFilter = 0, $yearFilter = 0, $nameFiler = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelWorkAllocation = new QcWorkAllocation();
        $modelWorkAllocationReport = new QcWorkAllocationReport();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataAccess = [
            'accessObject' => 'workAllocation',
            'subObject' => 'workAllocationReport'
        ];
        $companyFilterId = $dataStaffLogin->companyId();

        $searchCompanyFilterId = [$companyFilterId];
        //$dataCompany = $modelCompany->getInfo($companyFilterId);
        # lay danh sach ID nhan vien cua cty
        if (!empty($nameFiler)) {
            $listStaffId = $modelStaff->listIdOfCompanyAndName($searchCompanyFilterId, $nameFiler);
        } else {
            $listStaffId = $modelStaff->listIdOfListCompany($searchCompanyFilterId);
        }

        # lay danh sach Id phan viec
        $yearFilter = (empty($yearFilter)) ? date('Y') : $yearFilter;
        $listWorkAllocationId = $modelWorkAllocation->listIdOfReceiveStaff($listStaffId);
        #xem tất cả các ngày trong tháng
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
        $dataWorkAllocationReport = $modelWorkAllocationReport->selectInfoOfListWorkAllocation($listWorkAllocationId,$dateFilter)->paginate(30);
        return view('ad3d.work.work-allocation.report.list', compact('modelStaff', 'dataAccess', 'dataWorkAllocationReport', 'dayFilter', 'monthFilter', 'yearFilter', 'nameFiler'));

    }

    public function viewImage($imageId)
    {
        $modelWorkAllocationReportImage = new QcWorkAllocationReportImage();
        $dataWorkAllocationReportImage = $modelWorkAllocationReportImage->getInfo($imageId);
        if (count($dataWorkAllocationReportImage) > 0) {
            return view('ad3d.work.work-allocation.report.view-image', compact('dataAccess', 'dataWorkAllocationReportImage'));
        }

    }

}
