<?php

namespace App\Http\Controllers\Work\Pay\PayKeepMoney;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\KeepMoney\QcKeepMoney;
use App\Models\Ad3d\Salary\QcSalary;
use App\Models\Ad3d\SalaryPay\QcSalaryPay;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\Work\QcWork;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use File;
use Input;
use Request;

class KeepMoneyController extends Controller
{
    public function index($monthFilter = 0, $yearFilter = 0, $staffFilterId = 0, $payStatus = 3)
    {
        $hFunction = new \Hfunction();
        $modelCompany = new QcCompany();
        $modelStaff = new QcStaff();
        $modelKeepMoney = new QcKeepMoney();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelSalary = new QcSalary();
        $modelSalaryPay = new QcSalaryPay();
        $modelWork = new QcWork();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataAccess = [
            'object' => 'payKeepMoney',
            'subObjectLabel' => 'Thanh toán tiền giữ'
        ];
        $loginStaffId = $modelStaff->loginStaffId();
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
            $yearFilter = date('Y');
            $dateFilter = date('Y');
        } else {
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        }

        $searchCompanyFilterId = [$dataStaffLogin->companyId()];
        if ($staffFilterId > 0) {
            $listStaffId = [$staffFilterId];
        } else {
            $listStaffId = $modelStaff->listIdOfListCompany($searchCompanyFilterId);
        }
        #thong tin bang lương
        $listSalaryId = $modelSalary->listIdOfListWorkId($modelStaff->allListWorkIdOfListStaffId($listStaffId));
        $dataKeepMoneySelect = $modelKeepMoney->selectInfoOfListSalary($listSalaryId, $dateFilter, $payStatus);
        $dataKeepMoney = $dataKeepMoneySelect->paginate(30);
        //danh sach NV
        $dataStaff = $modelCompany->staffInfoActivityOfListCompanyId($searchCompanyFilterId);
        return view('work.pay.keep-money.list', compact('dataAccess', 'modelStaff', 'dataStaff', 'dataSalary', 'dataKeepMoney', 'dateFilter', 'monthFilter', 'yearFilter', 'staffFilterId', 'payStatus'));

    }

    public function getAddPay($staffId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelSalary = new QcSalary();
        $modelKeepMoney = new QcKeepMoney();
        $dataAccess = [
            'object' => 'payKeepMoney',
            'subObjectLabel' => 'Thanh toán tiền giữ'
        ];
        $listSalaryId = $modelSalary->listIdOfListWorkId($modelStaff->allListWorkId($staffId));

        $dataKeepMoney = $modelKeepMoney->selectInfoUnPaiOfListSalary($listSalaryId, null)->get();
        return view('work.pay.keep-money.pay', compact('dataAccess', 'modelStaff', 'dataKeepMoney'));
    }

    public function postAddPay($staffId)
    {

    }
}
