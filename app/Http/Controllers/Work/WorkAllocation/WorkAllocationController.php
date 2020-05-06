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
    public function index($monthFilter = null,$yearFilter = null)
    {
        $modelStaff = new QcStaff();
        if ($modelStaff->checkLogin()) {
            $dataAccess = [
                'object' => 'workAllocation'
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
            }elseif ($monthFilter == 100 && $yearFilter > 100) { //xem tất cả
                $dateFilter = date('Y', strtotime("1-1-$yearFilter"));
            } else {
                $dateFilter = date('Y-m');
                $monthFilter = date('m');
                $yearFilter = date('Y');
            }
            $dataStaff = $modelStaff->loginStaffInfo();
            $dataWorkAllocation = $dataStaff->workAllocationOfStaffReceive($dataStaff->staffId(),$dateFilter);
            return view('work.work-allocation.work-allocation.index', compact('dataAccess', 'modelStaff', 'dataStaff', 'dataWorkAllocation','monthFilter','yearFilter'));
        } else {
            return view('work.login');
        }

    }
}
