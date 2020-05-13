<?php

namespace App\Http\Controllers\Work\WorkAllocation;


use App\Models\Ad3d\OrderAllocation\QcOrderAllocation;
use App\Models\Ad3d\Product\QcProduct;
use App\Models\Ad3d\ProductDesign\QcProductDesign;

use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\StaffNotify\QcStaffNotify;

use App\Models\Ad3d\WorkAllocationReportImage\QcWorkAllocationReportImageImage;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WorkAllocationConstructionController extends Controller
{
    // danh sach cong trinh dc ban giao
    public function index($monthFilter = null, $yearFilter = null)
    {
        $modelStaff = new QcStaff();
        if ($modelStaff->checkLogin()) {
            $dataAccess = [
                'object' => 'workAllocationConstruction'
            ];
            $dateFilter = null;
            if ($monthFilter == 0 && $yearFilter == 0) { //khong chon thoi gian xem
                $monthFilter = date('m');
                $yearFilter = date('Y');
                $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
            } elseif ($monthFilter == 100 && $yearFilter == null) { //xam tat ca cac thang va khong chon nam
                $yearFilter = date('Y');
                $dateFilter = date('Y', strtotime("1-1-$yearFilter"));
            } elseif ($monthFilter > 0 && $monthFilter < 100 && $yearFilter == 100) { //co chon thang va khong chon nam
                $monthFilter = 100;
                $dateFilter = null;
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
            return view('work.work-allocation.construction.construction', compact('dataAccess', 'modelStaff', 'dateFilter', 'monthFilter', 'yearFilter'));
        } else {
            return view('work.login');
        }
    }

    # xac nhan cong trinh
    public function getConstructionConfirm($allocationId = null)
    {
        $modelOrderAllocation = new QcOrderAllocation();
        $dataOrderAllocation = $modelOrderAllocation->getInfo($allocationId);
        if (count($dataOrderAllocation) > 0) {
            return view('work.work-allocation.construction.confirm-finish', compact('dataOrderAllocation'));
        }
    }

    public function postConstructionConfirm(Request $request, $allocationId = null)
    {
        $hFunction = new \Hfunction();
        $modelOrderAllocation = new QcOrderAllocation();
        if (!$hFunction->checkEmpty($allocationId)) {
            $finishNote = $request->input('txtFinishNote');
            $modelOrderAllocation->reportFinishAllocation($allocationId, $hFunction->carbonNow(), $finishNote);
        }

    }

    #xem chi tiet hinh anh thiet ke
    public function viewProductDesign($designId)
    {
        $hFunction = new \Hfunction();
        $modelProductDesign = new QcProductDesign();
        $dataProductDesign = $modelProductDesign->getInfo($designId);
        if ($hFunction->checkCount($dataProductDesign)) {
            return view('work.work-allocation.construction.product.view-design-image', compact('dataProductDesign'));
        }
    }

    //quan ly san pham
    public function constructionProduct($allocationId)
    {
        $modelStaff = new QcStaff();
        $modelStaffNotify = new QcStaffNotify();
        $modelOrderAllocation = new QcOrderAllocation();
        if ($modelStaff->checkLogin()) {
            $dataAccess = [
                'object' => 'workAllocationConstruction',
                'subObjectLabel' => 'Sản phẩm'
            ];
            $dataOrdersAllocation = $modelOrderAllocation->getInfo($allocationId);
            $modelStaffNotify->updateViewedOfStaffAndOrderAllocation($modelStaff->loginStaffId(), $allocationId);
            return view('work.work-allocation.construction.product.product', compact('dataAccess', 'modelStaff', 'dataOrdersAllocation'));
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
            return view('work.work-allocation.construction.product.confirm-finish', compact('dataProduct'));
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
