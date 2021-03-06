<?php

namespace App\Http\Controllers\Work\WorkAllocation\OrderAllocation;


use App\Models\Ad3d\OrderAllocation\QcOrderAllocation;
use App\Models\Ad3d\Product\QcProduct;
use App\Models\Ad3d\ProductDesign\QcProductDesign;

use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\StaffNotify\QcStaffNotify;

use App\Models\Ad3d\TimekeepingProvisionalImage\QcTimekeepingProvisionalImage;
use App\Models\Ad3d\WorkAllocation\QcWorkAllocation;
use App\Models\Ad3d\WorkAllocationReportImage\QcWorkAllocationReportImage;
use App\Models\Ad3d\WorkAllocationReportImage\QcWorkAllocationReportImageImage;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderAllocationController extends Controller
{
    // danh sach cong trinh dc ban giao
    public function index($finishStatus = 100, $monthFilter = 100, $yearFilter = 0)
    {
        $modelStaff = new QcStaff();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataAccess = [
            'object' => 'workAllocationOrderAllocation'
        ];
        $loginStaffId = $dataStaffLogin->staffId();
        $dateFilter = null;
        if ($monthFilter == 0 && $yearFilter == 0) { //khong chon thoi gian xem
            $monthFilter = 100;
            $yearFilter = 100;
        } elseif ($monthFilter == 100 && $yearFilter == 0) { //xam tat ca cac thang va khong chon nam
            $yearFilter = date('Y');
            $dateFilter = date('Y', strtotime("1-1-$yearFilter"));
        } elseif ($monthFilter > 0 && $monthFilter < 100 && $yearFilter == 100) { //co chon thang va khong chon nam
            $yearFilter = date('Y');
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        } elseif ($monthFilter > 0 && $monthFilter < 100 && $yearFilter > 100) { //co chon thang va chon nam
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        } elseif ($monthFilter == 100 && $yearFilter == 100) { //xem tất cả
            $dateFilter = null;
        } elseif ($monthFilter == 100 && $yearFilter > 100) { //xem tất cả
            $dateFilter = date('Y', strtotime("1-1-$yearFilter"));
        } else {
            $dateFilter = date('Y-m');
            $monthFilter = date('m');
            $yearFilter = date('Y');
        }
        $dataOrdersAllocation = $dataStaffLogin->selectOrderAllocationInfoOfReceiveStaff($loginStaffId, $dateFilter, $finishStatus)->paginate(50);
        return view('work.work-allocation.order-allocation.list', compact('dataAccess', 'modelStaff', 'dataOrdersAllocation', 'dateFilter', 'finishStatus', 'monthFilter', 'yearFilter'));
    }

    # bao hoan thanh thi cong don hang
    public function getConstructionReportFinish($allocationId = null)
    {
        $hFunction = new \Hfunction();
        $modelOrderAllocation = new QcOrderAllocation();
        $dataOrderAllocation = $modelOrderAllocation->getInfo($allocationId);
        if ($hFunction->checkCount($dataOrderAllocation)) {
            return view('work.work-allocation.order-allocation.report-finish', compact('dataOrderAllocation'));
        }
    }

    # bao hoan than cong viec duoc phan cong
    public function postConstructionReportFinish(Request $request, $allocationId = null)
    {
        $hFunction = new \Hfunction();
        $modelOrderAllocation = new QcOrderAllocation();
        if (!$hFunction->checkEmpty($allocationId)) {
            $paymentStatus = $request->input('cbPaymentStatus');
            $finishNote = $request->input('txtFinishNote');
            $modelOrderAllocation->reportFinishAllocation($allocationId, $hFunction->carbonNow(), $finishNote, $paymentStatus);
        }

    }

    # chi tiet thi cong
    public function viewWorkAllocation($allocationId)
    {
        $hFunction = new \Hfunction();
        $modelWorkAllocation = new QcWorkAllocation();
        $dataWorkAllocation = $modelWorkAllocation->getInfo($allocationId);
        if ($hFunction->checkCount($dataWorkAllocation)) {
            return view('work.work-allocation.order-allocation.product.view-report', compact('dataWorkAllocation'));
        }
    }

    // SAN PHAM
    #xem chi tiet hinh anh thiet ke
    public function viewProductDesign($designId)
    {
        $hFunction = new \Hfunction();
        $modelProductDesign = new QcProductDesign();
        $dataProductDesign = $modelProductDesign->getInfo($designId);
        if ($hFunction->checkCount($dataProductDesign)) {
            return view('work.work-allocation.order-allocation.product.view-design-image', compact('dataProductDesign'));
        }
    }

    #xem anh bao cao truc tiep
    public function viewReportImageDirect($imageId)
    {
        $modelWorkAllocationReportImage = new QcWorkAllocationReportImage();
        $dataWorkAllocationReportImage = $modelWorkAllocationReportImage->getInfo($imageId);
        return view('work.work-allocation.order-allocation.product.view-report-image-direct', compact('dataWorkAllocationReportImage'));
    }

    # xem anh bao cao qua cham cong
    public function viewReportImageTimekeeping($imageId)
    {
        $modelTimekeepingProvisionalImage = new QcTimekeepingProvisionalImage();
        $dataTimekeepingProvisionalImage = $modelTimekeepingProvisionalImage->getInfo($imageId);
        return view('work.work-allocation.order-allocation.product.view-report-image', compact('dataTimekeepingProvisionalImage'));
    }

    //quan ly san pham
    public function constructionProduct($allocationId)
    {
        $modelStaff = new QcStaff();
        $modelStaffNotify = new QcStaffNotify();
        $modelOrderAllocation = new QcOrderAllocation();
        if ($modelStaff->checkLogin()) {
            $dataAccess = [
                'object' => 'workAllocationOrderAllocation',
                'subObjectLabel' => 'Sản phẩm'
            ];
            $dataOrdersAllocation = $modelOrderAllocation->getInfo($allocationId);
            $modelStaffNotify->updateViewedOfStaffAndOrderAllocation($modelStaff->loginStaffId(), $allocationId);
            return view('work.work-allocation.order-allocation.product.product', compact('dataAccess', 'modelStaff', 'dataOrdersAllocation'));
        } else {
            return view('work.login');
        }
    }

    # xac nhan san pham
    public function getConstructionProductConfirm($productId = null)
    {
        $hFunction = new \Hfunction();
        $modelProduct = new QcProduct();
        $dataProduct = $modelProduct->getInfo($productId);
        if ($hFunction->checkCount($dataProduct)) {
            return view('work.work-allocation.order-allocation.product.confirm-finish', compact('dataProduct'));
        }
    }

    public function postConstructionProductConfirm($productId = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelProduct = new QcProduct();
        $modelProduct->confirmFinish($modelStaff->loginStaffId(), $hFunction->carbonNow(), $productId);
    }
}
