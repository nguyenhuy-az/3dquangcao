<?php

namespace App\Http\Controllers\Work\Pay\PaySalaryBeforePay;

use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\PayActivityDetail\QcPayActivityDetail;

use App\Models\Ad3d\Salary\QcSalary;
use App\Models\Ad3d\SalaryBeforePay\QcSalaryBeforePay;
use App\Models\Ad3d\SalaryPay\QcSalaryPay;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\Work\QcWork;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use File;
use Input;
use Request;

class PaySalaryBeforePayController extends Controller
{
    public function index($dayFilter = 0, $monthFilter = 0, $yearFilter = 0)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelSalaryBeforePay = new QcSalaryBeforePay();
        $dataStaff = $modelStaff->loginStaffInfo();
        $currentMonth = $hFunction->currentMonth();
        $currentYear = $hFunction->currentYear();
        $dataAccess = [
            'object' => 'paySalaryBeforePay',
            'subObjectLabel' => 'Chi ứng lương'
        ];
        if (count($dataStaff) > 0) {
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

            $dataSalaryBeforePay = $modelSalaryBeforePay->infoOfStaffAndDate($dataStaff->staffId(), $dateFilter);
            return view('work.pay.salary-before-pay.list', compact('dataAccess', 'modelStaff', 'dataSalaryBeforePay', 'dayFilter', 'monthFilter', 'yearFilter'));
        } else {
            return view('work.login');
        }

    }

    # them ung uong
    public function getAdd($workId = null)
    {
        $modelStaff = new QcStaff();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelWork = new QcWork();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataAccess = [
            'object' => 'paySalaryBeforePay',
            'subObjectLabel' => 'Chi ứng lương'
        ];
        if (count($dataStaffLogin) > 0) {
            $companyLoginId = $dataStaffLogin->companyId();
            $listCompanyStaffWorkId = $modelCompanyStaffWork->listIdOfListCompanyAndListStaff([$companyLoginId], null);
            $dataWork = $modelWork->infoActivityOfListCompanyStaffWork($listCompanyStaffWorkId);
            $dataWorkSelect = (empty($workId)) ? null : $modelWork->getInfo($workId);
            return view('work.pay.salary-before-pay.add', compact('modelStaff', 'modelCompanyStaffWork', 'dataAccess', 'dataWork', 'dataWorkSelect'));
        } else {
            return view('work.login');
        }

    }

    public function postAdd()
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelSalaryBeforePay = new QcSalaryBeforePay();
        $workId = Request::input('cbWork');
        $txtMoney = Request::input('txtMoney');
        $txtMoney = $hFunction->convertCurrencyToInt($txtMoney);
        $description = Request::input('txtDescription');
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        if (count($dataStaffLogin) > 0) {
            $staffId = $dataStaffLogin->staffId();
            if ($modelSalaryBeforePay->insert($txtMoney, $hFunction->carbonNow(), $description, $workId, $staffId)) {
                return Session::put('notifyAdd', 'Ứng thành công, chọn thông tin và tiếp tục');
            } else {
                return Session::put('notifyAdd', 'Ứng thất bại, hãy thử lại');
            }
        } else {
            return view('work.login');
        }


    }

    #cap nhat ung luong
    public function getEdit($payId)
    {
        $modelStaff = new QcStaff();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelSalaryBeforePay = new QcSalaryBeforePay();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        if (count($dataStaffLogin) > 0) {
            $dataSalaryBeforePay = $modelSalaryBeforePay->getInfo($payId);
            return view('work.pay.salary-before-pay.edit', compact('modelStaff', 'modelCompanyStaffWork','dataSalaryBeforePay'));
        }
    }

    public function postEdit($payId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelSalaryBeforePay = new QcSalaryBeforePay();
        $txtMoney = Request::input('txtMoney');
        $txtMoney = $hFunction->convertCurrencyToInt($txtMoney);
        $description = Request::input('txtDescription');
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        if (count($dataStaffLogin) > 0) {
            $modelSalaryBeforePay->updateInfo($payId,$txtMoney,$hFunction->carbonNow(), $description);
        } else {
            return redirect()->back();
        }
    }

    # huy ung luong
    public function deletePay($payId)
    {
        $modelSalaryBeforePay = new QcSalaryBeforePay();
        return $modelSalaryBeforePay->deleteInfo($payId);
    }

}
