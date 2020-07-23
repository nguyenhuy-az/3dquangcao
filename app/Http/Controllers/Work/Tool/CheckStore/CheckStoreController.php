<?php

namespace App\Http\Controllers\Work\Tool\CheckStore;


use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\Tool\QcTool;
use App\Models\Ad3d\ToolAllocation\QcToolAllocation;
use App\Models\Ad3d\ToolAllocationDetail\QcToolAllocationDetail;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use File;
use Input;
use Request;

class CheckStoreController extends Controller
{
    public function index($monthFilter = 0, $yearFilter = 0)
    {
        $modelStaff = new QcStaff();
        $modelTool = new QcTool();
        $dataAccess = [
            'object' => 'toolCheckStore'
        ];
        $dataStaff = $modelStaff->loginStaffInfo();
        #thong tin lam viec tai 1 cty
        //$dataCompanyStaffWork = $dataStaff->companyStaffWorkInfoActivity();
        $dataToolCheckStore = null;
        return view('work.tool.check-store.list', compact('dataAccess', 'modelStaff', 'dataToolCheckStore', 'monthFilter', 'yearFilter'));
    }

}
