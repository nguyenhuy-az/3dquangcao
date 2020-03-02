<?php

namespace App\Http\Controllers\Work\WorkAllocation;

use App\Models\Ad3d\Product\QcProduct;
use App\Models\Ad3d\ProductDesign\QcProductDesign;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\TimekeepingProvisionalImage\QcTimekeepingProvisionalImage;
use App\Models\Ad3d\WorkAllocationReportImage\QcWorkAllocationReportImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WorkAllocationFinishController extends Controller
{
    public function index()
    {
        $modelStaff = new QcStaff();
        if ($modelStaff->checkLogin()) {
            $dataAccess = [
                'object' => 'workAllocationFinish',
            ];
            $dataStaff = $modelStaff->loginStaffInfo();
            $dataWorkAllocation = $dataStaff->workAllocationFinishOfStaffReceive();
            return view('work.work-allocation.allocation-finish.index', compact('dataAccess', 'modelStaff', 'dataStaff', 'dataWorkAllocation'));
        } else {
            return view('work.login');
        }
    }

    # xem anh thiet ke
    public function viewDesignImage($designId)
    {
        $hFunction = new \Hfunction();
        $modelProductDesign = new QcProductDesign();
        $dataProductDesign = $modelProductDesign->getInfo($designId);
        if ($hFunction->checkCount($dataProductDesign)) {
            return view('work.work-allocation.allocation-finish.design-image-view', compact('dataProductDesign'));
        }
    }

    # xem anh bao cao qua cham cong
    public function viewReportImage($imageId)
    {
        $modelTimekeepingProvisionalImage = new QcTimekeepingProvisionalImage();
        $dataTimekeepingProvisionalImage = $modelTimekeepingProvisionalImage->getInfo($imageId);
        if (count($dataTimekeepingProvisionalImage) > 0) {
            return view('work.work-allocation.allocation-finish.report-image-view', compact('dataTimekeepingProvisionalImage'));
        }
    }
    #xem anh bao cao truc tiep
    public function viewReportImageDirect($imageId)
    {
        $modelWorkAllocationReportImage = new QcWorkAllocationReportImage();
        $dataWorkAllocationReportImage = $modelWorkAllocationReportImage->getInfo($imageId);
        return view('work.work-allocation.allocation-finish.report-image-direct-view', compact('dataWorkAllocationReportImage'));
    }
}
