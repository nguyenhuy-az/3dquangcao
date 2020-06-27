<?php

namespace App\Http\Controllers\Ad3d\Store\Store;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\CompanyStore\QcCompanyStore;
use App\Models\Ad3d\Import\QcImport;
use App\Models\Ad3d\Staff\QcStaff;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ad3d\Tool\QcTool;
use App\Models\Ad3d\ToolAllocation\QcToolAllocation;
use App\Models\Ad3d\ToolAllocationDetail\QcToolAllocationDetail;
use Illuminate\Support\Facades\Session;
use Input;
use File;
use DB;
use Request;


class StoreController extends Controller
{
    public function index($companyFilterId = null, $nameFiler = null, $type = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelCompanyStore = new QcCompanyStore();
        $modelTool = new QcTool();
        $companyFilterId = ($companyFilterId == 'null')?null:$companyFilterId;
        $nameFiler = ($nameFiler == 'null')?null:$nameFiler;
        $type = ($type == 'null')?null:$type;
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataAccess = [
            'accessObject' => 'store'
        ];
        $dataCompany = $modelCompany->getInfo();
        if (empty($companyFilterId)) {
            $searchCompanyFilterId = [$dataStaffLogin->companyId()];
            $companyFilterId = $dataStaffLogin->companyId();
        } else {
            $searchCompanyFilterId = [$companyFilterId];
        }
        if (empty($nameFiler)) {
            $listToolId = null;
        } else {
            $listToolId = $modelTool->listIdByName($nameFiler);
        }
        $dataCompanyStore = $modelCompanyStore->selectInfoToolOfListCompanyAndListToolAnd($searchCompanyFilterId, $listToolId, null)->paginate(30);

        # danh sach dung cu cua he thong
        $dataTool = $modelTool->selectAllInfo($type)->paginate(30);
        # thong tin cong ty duoc chon
        $dataCompanyFilter = $modelCompany->getInfo($searchCompanyFilterId);
        return view('ad3d.store.store.list-tool', compact('modelStaff', 'dataCompany', 'dataAccess','dataTool', 'dataCompanyStore', 'dataCompanyFilter', 'nameFiler','type'));


    }
}
