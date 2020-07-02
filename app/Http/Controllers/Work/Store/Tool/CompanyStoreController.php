<?php

namespace App\Http\Controllers\Work\Store\Tool;


use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\CompanyStore\QcCompanyStore;
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

class CompanyStoreController extends Controller
{
    public function index($type = 0)
    {
        $modelStaff = new QcStaff();
        $modelCompanyStore = new QcCompanyStore();
        $modelTool = new QcTool();
        $modelToolAllocation = new QcToolAllocation();
        $modelToolAllocationDetail = new QcToolAllocationDetail();
        $dataAccess = [
            'object' => 'storeTool',
            'subObjectLabel' => 'đồ nghề'
        ];
        $dataStaff = $modelStaff->loginStaffInfo();
        # danh sach dung cu dung de phat cho ca nha
        $listToolId = $modelTool->listIdByType($type);
        # danh dach dung cua trong kho cua 1 cty
        $dataCompanyStore = $modelCompanyStore->getInfoOfListToolAndCompany($dataStaff->companyId(), $listToolId);
        return view('work.store.tool.list', compact('dataAccess', 'modelStaff', 'dataCompanyStore', 'type'));
    }

}
