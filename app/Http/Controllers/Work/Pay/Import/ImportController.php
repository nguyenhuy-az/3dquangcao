<?php

namespace App\Http\Controllers\Work\Pay\Import;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\Import\QcImport;
use App\Models\Ad3d\ImportDetail\QcImportDetail;
use App\Models\Ad3d\ImportImage\QcImportImage;
use App\Models\Ad3d\Rule\QcRules;
use App\Models\Ad3d\Salary\QcSalary;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\Supplies\QcSupplies;
use App\Models\Ad3d\Tool\QcTool;
use App\Models\Ad3d\Work\QcWork;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use File;
use Input;
use Request;

class ImportController extends Controller
{
    public function index($monthFilter = 0, $yearFilter = 0, $payStatusFilter = 4, $staffFilterId = null)
    {
        $hFunction = new \Hfunction();
        $modelCompany = new QcCompany();
        $modelStaff = new QcStaff();
        $modelImport = new QcImport();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataAccess = [
            'accessObject' => 'import'
        ];
        $searchCompanyFilterId = [$dataStaffLogin->companyId()];
        if ($staffFilterId > 0) {
            $listStaffId = [$staffFilterId];
        } else {
            $listStaffId = $modelStaff->listIdOfListCompany($searchCompanyFilterId);
        }

        if ($monthFilter == 100 && $yearFilter == 100) {//xem tất cả đơn hang
            $dateFilter = null;
        } elseif ($monthFilter < 100 && $yearFilter == 100) {
            $dateFilter = date('Y');
            $yearFilter = date('Y');
        } elseif ($monthFilter == 100 && $yearFilter != 100) {
            if ($yearFilter == 0) $yearFilter = date('Y');// else $yearFilter =
            $dateFilter = date('Y', strtotime("1-1-$yearFilter"));
        } elseif ($monthFilter < 100 && $yearFilter == 100) {
            $yearFilter = $hFunction->currentYear();
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        } elseif ($monthFilter == 0 && $yearFilter == 0) {
            $dateFilter = null;
        } else {
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        }
        $dataImportAll = $modelImport->getInfoHaveFilter($listStaffId, $searchCompanyFilterId, $dateFilter, $payStatusFilter, 'DESC');
        $dataImport = $dataImportAll->paginate(30);
        //danh sach NV
        $dataListStaff = $modelCompany->staffInfoActivityOfListCompanyId($searchCompanyFilterId);
        return view('work.pay.import.index', compact('dataAccess', 'modelStaff', 'dataImport', 'dayFilter', 'monthFilter', 'yearFilter', 'payStatusFilter', 'staffFilterId'));

    }
}
