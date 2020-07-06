<?php

namespace App\Http\Controllers\Work\Store\Tool;


use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\Tool\QcTool;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use File;
use Input;
use Request;

class CompanyStoreController extends Controller
{
    public function index($typeFilter = 0)
    {
        $modelCompany = new QcCompany();
        $modelStaff = new QcStaff();
        $modelTool = new QcTool();
        $dataAccess = [
            'object' => 'storeTool',
            'subObjectLabel' => 'Đồ nghề'
        ];
        $dataStaff = $modelStaff->loginStaffInfo();
        # thong tin cty
        $dataCompany = $modelCompany->getInfo($dataStaff->companyId());
        # lay danh sach cong cu cua he thong
        $dataTool = $modelTool->selectAllInfo($typeFilter)->get();
        return view('work.store.tool.list', compact('dataAccess', 'modelStaff','dataCompany', 'dataTool', 'typeFilter'));
    }

}
