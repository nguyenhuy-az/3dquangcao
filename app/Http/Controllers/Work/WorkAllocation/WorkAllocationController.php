<?php

namespace App\Http\Controllers\Work\WorkAllocation;

use App\Models\Ad3d\ProductDesign\QcProductDesign;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\TimekeepingProvisionalImage\QcTimekeepingProvisionalImage;
use App\Models\Ad3d\WorkAllocation\QcWorkAllocation;
use App\Models\Ad3d\WorkAllocationReport\QcWorkAllocationReport;
use App\Models\Ad3d\WorkAllocationReportImage\QcWorkAllocationReportImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WorkAllocationController extends Controller
{
    public function index()
    {
        $modelStaff = new QcStaff();
        if ($modelStaff->checkLogin()) {
            $dataAccess = [
                'object' => 'workAllocation'
            ];
            $dataStaff = $modelStaff->loginStaffInfo();
            $dataWorkAllocation = $dataStaff->workAllocationOfStaffReceive();
            return view('work.work-allocation.work-allocation.index', compact('dataAccess', 'modelStaff', 'dataStaff', 'dataWorkAllocation'));
        } else {
            return view('work.login');
        }

    }
}
