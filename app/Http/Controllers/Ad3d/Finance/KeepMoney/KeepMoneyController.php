<?php

namespace App\Http\Controllers\Ad3d\Finance\KeepMoney;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\KeepMoney\QcKeepMoney;
use App\Models\Ad3d\Salary\QcSalary;
use App\Models\Ad3d\Transfers\QcTransfers;
use App\Models\Ad3d\Staff\QcStaff;
use Illuminate\Support\Facades\Session;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Input;
use Request;

class KeepMoneyController extends Controller
{
    public function index($companyFilterId = null, $dayFilter = 0, $monthFilter = 0, $yearFilter = 0, $staffFilterId = 0, $payStatus = 0)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelSalary = new QcSalary();
        $modelCompany = new QcCompany();
        $modelKeepMoney = new QcKeepMoney();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $currentMonth = $hFunction->currentMonth();
        $currentYear = $hFunction->currentYear();
        $dataCompanyLogin = $modelStaff->companyLogin();
        $companyLoginId = $dataCompanyLogin->companyId();
        $companyFilterId = ($companyFilterId == 'null') ? null : $companyFilterId;
        if ($companyFilterId == 0) {
            $companyFilterId = $companyLoginId;
        }
        if ($staffFilterId > 0) {
            $listStaffId = [$staffFilterId];
        } else {
            $listStaffId = $modelStaff->listIdOfCompany($companyFilterId);
        }
        # lay thong tin cong ty cung he thong
        $dataCompany = $modelCompany->getInfoSameSystemOfCompany($companyLoginId);
        //danh sach NV
        $dataStaff = $modelCompany->staffInfoActivityOfListCompanyId([$companyFilterId]);
        $dataAccess = [
            'accessObject' => 'keepMoney'
        ];
        $dateFilter = null;
        if ($dayFilter == 0 && $monthFilter == 0 && $yearFilter == 0) { //xem  trong tháng
            $dayFilter = 100;
            $monthFilter = date('m');
            $yearFilter = date('Y');
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        } elseif ($dayFilter == 100 && $monthFilter == 100 && $yearFilter > 100) { //xem tất cả các ngày trong tháng
            $dateFilter = date('Y', strtotime("1-1-$yearFilter"));
        } elseif ($dayFilter == 100 && $monthFilter > 0 && $monthFilter < 100 && $yearFilter > 100) { //xem tất cả các ngày trong tháng
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        } elseif ($dayFilter < 100 && $dayFilter > 0 && $monthFilter > 0 && $monthFilter < 100 && $yearFilter > 100) { //xem tất cả các ngày trong tháng
            $monthFilter = $currentMonth;
            $yearFilter = $currentYear;
            $dateFilter = date('Y-m-d', strtotime("$dayFilter-$currentMonth-$currentYear"));
        } elseif ($dayFilter == 100 && $monthFilter == 100 && $yearFilter == 100) { //xem tất cả
            $dateFilter = null;
        } else {
            $dateFilter = date('Y-m');
            $dayFilter = 100;
            $monthFilter = date('m');
            $yearFilter = date('Y');
        }

        /*if ($dataStaffLogin->checkRootManage()) {
            if (empty($companyFilterId) || $companyFilterId == 1000) {
                $searchCompanyFilterId = $modelCompany->listIdActivity();
                $companyFilterId = 1000;// $dataStaffLogin->companyId();
            } else {
                $searchCompanyFilterId = [$companyFilterId];
            }

        } else {
            $searchCompanyFilterId = [$dataStaffLogin->companyId()];
            $companyFilterId = $dataStaffLogin->companyId();
        }*/

        $listWorkId = $modelStaff->allListWorkIdOfListStaffId($listStaffId);
        $listSalaryId = $modelSalary->listIdOfListWorkId($listWorkId);
        $dataKeepMoneySelect = $modelKeepMoney->selectInfoOfListSalary($listSalaryId, $dateFilter, $payStatus);
        $dataKeepMoney = $dataKeepMoneySelect->paginate(30);
        return view('ad3d.finance.keep-money.list', compact('modelStaff', 'dataCompany','dataStaff', 'dataAccess', 'dataKeepMoney', 'companyFilterId', 'dayFilter', 'monthFilter', 'yearFilter','staffFilterId', 'payStatus'));

    }
}
