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
    public function index($companyFilterId = null, $dayFilter = null, $monthFilter = null, $yearFilter = null, $nameFiler = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelCompanyStore = new QcCompanyStore();
        $modelTool = new QcTool();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataAccess = [
            'accessObject' => 'store'
        ];
        $dataCompany = $modelCompany->getInfo();
        if (empty($companyFilterId)) {
            if (!$dataStaffLogin->checkRootManage()) {
                $searchCompanyFilterId = [$dataStaffLogin->companyId()];
                $companyFilterId = $dataStaffLogin->companyId();
            } else {
                $searchCompanyFilterId = $modelCompany->listIdActivity();
            }
        } else {
            $searchCompanyFilterId = [$companyFilterId];
        }
        /*if (empty($dayFilter) && empty($monthFilter) && empty($yearFilter)) {
            $monthFilter = date('m');
            $yearFilter = date('Y');
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        } elseif ($dayFilter == 0) { //xem t?t c? các ngày trong tháng
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        } else {
            $dateFilter = date('Y-m-d', strtotime("$dayFilter-$monthFilter-$yearFilter"));
        }*/
        if(empty($nameFiler)){
            $listToolId = null;
        }else{
            $listToolId = $modelTool->listIdByName($nameFiler);
        }

        $dataCompanyStore = $modelCompanyStore->selectInfoToolOfListCompanyAndListToolAnd($searchCompanyFilterId,$listToolId, null)->paginate(30);
        return view('ad3d.store.store.list-tool', compact('modelStaff', 'dataCompany', 'dataAccess', 'dataCompanyStore', 'companyFilterId', 'dayFilter', 'monthFilter', 'yearFilter', 'nameFiler'));


    }
}
