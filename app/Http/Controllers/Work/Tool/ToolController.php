<?php

namespace App\Http\Controllers\Work\Tool;

use App\Models\Ad3d\Import\QcImport;
use App\Models\Ad3d\ImportDetail\QcImportDetail;
use App\Models\Ad3d\ImportImage\QcImportImage;
use App\Models\Ad3d\Rule\QcRules;
use App\Models\Ad3d\Salary\QcSalary;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\Supplies\QcSupplies;
use App\Models\Ad3d\Tool\QcTool;
use App\Models\Ad3d\ToolAllocation\QcToolAllocation;
use App\Models\Ad3d\ToolAllocationDetail\QcToolAllocationDetail;
use App\Models\Ad3d\Work\QcWork;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use File;
use Input;
use Request;

class ToolController extends Controller
{
    public function index($loginMonth = null, $loginYear = null)
    {
        $modelStaff = new QcStaff();
        $modelToolAllocation = new QcToolAllocation();
        $modelToolAllocationDetail = new QcToolAllocationDetail();
        if ($modelStaff->checkLogin()) {
            $dataAccess = [
                'object' => 'tool'
            ];
            $dataStaff = $modelStaff->loginStaffInfo();
            $loginMonth = (empty($loginMonth)) ? date('m') : $loginMonth;
            $loginYear = (empty($loginYear)) ? date('Y') : $loginYear;
            $dataToolAllocationDetail = $modelToolAllocationDetail->infoOfListToolAllocation($modelToolAllocation->listIdOfReceiveStaff($dataStaff->staffId()));
            return view('work.tool.tool', compact('dataAccess', 'modelStaff', 'dataStaff', 'dataToolAllocationDetail', 'loginMonth', 'loginYear'));
        } else {
            return view('work.login');
        }

    }

    // xác nhận thanh toán
    public function getConfirmReceive($allocationId)
    {
        $modelToolAllocation = new QcToolAllocation();
        $modelToolAllocation->receiveConfirm($allocationId);
    }
}
