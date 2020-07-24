<?php

namespace App\Http\Controllers\Work\Tool\CheckStore;


use App\Models\Ad3d\CompanyStore\QcCompanyStore;
use App\Models\Ad3d\CompanyStoreCheck\QcCompanyStoreCheck;
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
        $modelCompanyStore = new QcCompanyStore();
        $modelCompanyStoreCheck = new QcCompanyStoreCheck();
        $dataAccess = [
            'object' => 'toolCheckStore'
        ];
        $dataStaff = $modelStaff->loginStaffInfo();
        #thong tin lam viec tai 1 cty
        //$dataCompanyStaffWork = $dataStaff->companyStaffWorkInfoActivity();
        $dataCompanyStoreCheck = $modelCompanyStoreCheck->infoReceiveStatusOfStaff($dataStaff->staffId());
        #do nghe dung chung cua he thong
        $dataCompanyStore = $modelCompanyStore->getPublicToolToCheckOfCompany($dataStaff->companyId());
        return view('work.tool.check-store.list', compact('dataAccess', 'modelStaff', 'dataCompanyStoreCheck', 'dataCompanyStore', 'monthFilter', 'yearFilter'));
    }

    // xac nhan kiem tra
    public function postConfirm()
    {

    }
}
