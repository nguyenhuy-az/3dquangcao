<?php

namespace App\Http\Controllers\Ad3d\Finance\Salary\PayBefore;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\SalaryBeforePay\QcSalaryBeforePay;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\Work\QcWork;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Input;
use File;
use DB;
use Request;

class PayBeforeController extends Controller
{
    public function index($companyFilterId = null, $dayFilter = null, $monthFilter = null, $yearFilter = null, $nameFiler = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelQcSalaryBeforePay = new QcSalaryBeforePay();
        $modelCompany = new QcCompany();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelWork = new QcWork();
        $currentMonth = $hFunction->currentMonth();
        $currentYear = $hFunction->currentYear();
        $dataCompanyLogin = $modelStaff->companyLogin();
        $companyLoginId = $dataCompanyLogin->companyId();
        $companyFilterId = ($companyFilterId == 'null') ? null : $companyFilterId;
        if ($companyFilterId == null || $companyFilterId == 0) {
            $companyFilterId = $companyLoginId;
        }
        # lay thong tin cong ty cung he thong
        $dataCompany = $modelCompany->getInfoSameSystemOfCompany($companyLoginId);
        $dataAccess = [
            'accessObject' => 'payBefore'
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
            if (empty($companyFilterId)) {
                $searchCompanyFilterId = $modelCompany->listIdActivity();
            } else {
                $searchCompanyFilterId = [$companyFilterId];
            }
        } else {
            $searchCompanyFilterId = [$dataStaffLogin->companyId()];
            $companyFilterId = $dataStaffLogin->companyId();
        }*/

        if ($monthFilter < 8 && $yearFilter < 2019) { # du lieu cu phien ban cu --  loc theo staff_id
            if (!empty($nameFiler)) {
                $listStaffId = $modelStaff->listIdOfListCompanyAndName([$companyFilterId], $nameFiler);
            } else {
                $listStaffId = $modelStaff->listIdOfListCompany([$companyFilterId]);
            }
            $listWorkId = $modelWork->listIdOfListStaffInBeginDate($listStaffId, $dateFilter);
        } else { # du lieu phien ban moi - loc theo thong tin lam viec tai cty (companyStaffWork)
            if (!empty($nameFiler)) {
                $listCompanyStaffWorkId = $modelCompanyStaffWork->listIdOfListCompanyAndListStaff([$companyFilterId], $modelStaff->listStaffIdByName($nameFiler));
            } else {
                $listCompanyStaffWorkId = $modelCompanyStaffWork->listIdOfListCompanyAndListStaff([$companyFilterId]);
            }
            $listWorkId = $modelWork->listIdOfListCompanyStaffWorkBeginDate($listCompanyStaffWorkId, $dateFilter);
        }
        $selectSalaryBeforePay = $modelQcSalaryBeforePay->selectInfoOfListWorkAndDate($listWorkId, $dateFilter);
        $dataSalaryBeforePay = $selectSalaryBeforePay->paginate(30);
        $totalMoneyBeforePay = $modelQcSalaryBeforePay->totalMoneyByListInfo($selectSalaryBeforePay->get());
        return view('ad3d.finance.salary.pay-before.list', compact('modelStaff', 'dataCompany', 'dataAccess', 'dataSalaryBeforePay', 'totalMoneyBeforePay', 'companyFilterId', 'dayFilter', 'monthFilter', 'yearFilter', 'nameFiler'));

    }

    public function view($payId)
    {
        $modelSalaryBeforePay = new QcSalaryBeforePay();
        $dataSalaryBeforePay = $modelSalaryBeforePay->getInfo($payId);
        if (count($dataSalaryBeforePay) > 0) {
            return view('ad3d.finance.salary.pay-before.view', compact('dataSalaryBeforePay'));
        }
    }

    public function getAdd($companyLoginId = null, $workId = null)
    {
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelWork = new QcWork();
        $dataAccess = [
            'accessObject' => 'payBefore'
        ];
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataCompany = $modelCompany->getInfo();
        if (empty($companyLoginId)) {
            $companyLoginId = $dataStaffLogin->companyId();
        }
        $listCompanyStaffWorkId = $modelCompanyStaffWork->listIdOfListCompanyAndListStaff([$companyLoginId], null);
        $dataWork = $modelWork->infoActivityOfListCompanyStaffWork($listCompanyStaffWorkId);
        $dataWorkSelect = (empty($workId)) ? null : $modelWork->getInfo($workId);
        return view('ad3d.finance.salary.pay-before.add', compact('modelStaff', 'modelCompanyStaffWork', 'dataAccess', 'dataCompany', 'companyLoginId', 'dataWork', 'dataWorkSelect'));
    }

    public function postAdd()
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelWork = new QcWork();
        $modelSalaryBeforePay = new QcSalaryBeforePay();
        $workId = Request::input('cbWork');
        $cbDay = Request::input('cbDay');
        $cbMonth = Request::input('cbMonth');
        $cbYear = Request::input('cbYear');
        $txtMoney = Request::input('txtMoney');
        $description = Request::input('txtDescription');
        $staffId = $modelStaff->loginStaffId();
        $datePay = $hFunction->convertStringToDatetime("$cbMonth/$cbDay/$cbYear 00:00:00");
        $dataWork = $modelWork->getInfo($workId);
        $limitBeforePay = $dataWork->limitBeforePay();
        if ($limitBeforePay < $txtMoney) {
            return Session::put('notifyAdd', "Không được ứng quá " . $hFunction->dotNumber($limitBeforePay));
        } else {
            $currentDate = date('Y-m-d h:j:s');
            if ($datePay > $currentDate) {
                return Session::put('notifyAdd', 'Ngày ứng phải nhỏ hơn ngày hiện tại');
            } else {
                if ($modelSalaryBeforePay->insert($txtMoney, $datePay, $description, $workId, $staffId)) {
                    return Session::put('notifyAdd', 'Ứng thành công, chọn thông tin và tiếp tục');
                } else {
                    return Session::put('notifyAdd', 'Ứng thất bại, hãy thử lại');
                }
            }

        }

    }

    public function getEdit($payId)
    {
        $modelSalaryBeforePay = new QcSalaryBeforePay();
        $dataSalaryBeforePay = $modelSalaryBeforePay->getInfo($payId);
        $dataWorkSelect = $dataSalaryBeforePay->work;
        return view('ad3d.finance.salary.pay-before.edit', compact('dataWorkSelect', 'dataSalaryBeforePay'));
    }

    public function postEdit($payId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelSalaryBeforePay = new QcSalaryBeforePay();
        $cbDay = Request::input('cbDay');
        $cbMonth = Request::input('cbMonth');
        $cbYear = Request::input('cbYear');
        $txtMoney = Request::input('txtMoney');
        $description = Request::input('txtDescription');
        $datePay = $hFunction->convertStringToDatetime("$cbMonth/$cbDay/$cbYear 00:00:00");
        if (!$modelSalaryBeforePay->updateInfo($payId, $txtMoney, $datePay, $description)) {
            return "Cập nhật thất bại";
        }
    }

    public function deleteSalaryBeforePay($payId)
    {
        $modelSalaryBeforePay = new QcSalaryBeforePay();
        $modelSalaryBeforePay->deleteInfo($payId);
    }

}
