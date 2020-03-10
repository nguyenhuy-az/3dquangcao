<?php

namespace App\Http\Controllers\Work\WorkAllocation;

use App\Models\Ad3d\Order\QcOrder;
use App\Models\Ad3d\OrderAllocation\QcOrderAllocation;
use App\Models\Ad3d\Product\QcProduct;
use App\Models\Ad3d\ProductDesign\QcProductDesign;
use App\Models\Ad3d\Rule\QcRules;
use App\Models\Ad3d\Salary\QcSalary;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\TimekeepingProvisionalImage\QcTimekeepingProvisionalImage;
use App\Models\Ad3d\Work\QcWork;
//use Illuminate\Http\Request;
use App\Models\Ad3d\WorkAllocation\QcWorkAllocation;
use App\Models\Ad3d\WorkAllocationReport\QcWorkAllocationReport;
use App\Models\Ad3d\WorkAllocationReportImage\QcWorkAllocationReportImage;
use App\Models\Ad3d\WorkAllocationReportImage\QcWorkAllocationReportImageImage;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use File;
use Input;
use Request;

class WorkAllocationConstructionController extends Controller
{
    // danh sach cong trinh dc ban giao
    public function index($loginMonth = null, $loginYear = null)
    {
        $modelStaff = new QcStaff();
        if ($modelStaff->checkLogin()) {
            $dataAccess = [
                'object' => 'workAllocationConstruction'
            ];
            $loginMonth = (empty($loginMonth)) ? date('m') : $loginMonth;
            $loginYear = (empty($loginYear)) ? date('Y') : $loginYear;
            return view('work.work-allocation.construction.construction', compact('dataAccess', 'modelStaff', 'loginMonth', 'loginYear'));
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

    public function postConstructionConfirm($allocationId = null)
    {
        $hFunction = new \Hfunction();
        $modelOrderAllocation = new QcOrderAllocation();
        if(!$hFunction->checkEmpty($allocationId)){
            $modelOrderAllocation->reportFinishAllocation($allocationId, $hFunction->carbonNow());
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
        $modelOrderAllocation = new QcOrderAllocation();
        if ($modelStaff->checkLogin()) {
            $dataAccess = [
                'object' => 'workAllocationConstruction',
                'subObjectLabel' => 'Sản phẩm'
            ];
            $dataOrdersAllocation = $modelOrderAllocation->getInfo($allocationId);
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
