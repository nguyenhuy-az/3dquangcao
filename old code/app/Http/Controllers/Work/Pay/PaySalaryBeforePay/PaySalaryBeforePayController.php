<?php

namespace App\Http\Controllers\Work\Pay\PaySalaryBeforePay;

use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\Import\QcImport;
use App\Models\Ad3d\ImportPay\QcImportPay;
use App\Models\Ad3d\KeepMoney\QcKeepMoney;
use App\Models\Ad3d\PayActivityDetail\QcPayActivityDetail;

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

class PaySalaryBeforePayController extends Controller
{
    public function index($loginMonth = null, $loginYear = null, $payStatus = 3)
    {
        $modelStaff = new QcStaff();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelSalary = new QcSalary();
        $modelWork = new QcWork();
        $dataStaff = $modelStaff->loginStaffInfo();
        $dataAccess = [
            'object' => 'paySalaryBeforePay',
            'subObjectLabel' => 'Úng lương'
        ];
        if (count($dataStaff) > 0) {
            if (empty($loginMonth) && empty($loginYear)) {
                $dateFilter = date('Y-m');
                $loginMonth = date('m');
                $loginYear = date('Y');
            } elseif ($loginMonth == 0) { //xem tat ca cac thang
                $dateFilter = date('Y', strtotime("1-1-$loginYear"));
            } else {
                $dateFilter = date('Y-m', strtotime("1-$loginMonth-$loginYear"));
            }
            $searchCompanyFilterId = [$dataStaff->companyId()];
            if ($loginMonth < 8 && $loginYear <= 2109) { # du lieu cu phien ban cu --  loc theo staff_id
                $listStaffId = $modelStaff->listIdOfListCompany($searchCompanyFilterId);
                $listWorkId = $modelWork->listIdOfListStaffInBeginDate($listStaffId, $dateFilter);
            } else { # du lieu phien ban moi - loc theo thong tin lam viec tai cty (companyStaffWork)
                $listCompanyStaffWorkId = $modelCompanyStaffWork->listIdOfListCompanyAndListStaff($searchCompanyFilterId);
                $listWorkId = $modelWork->listIdOfListCompanyStaffWorkBeginDate($listCompanyStaffWorkId, $dateFilter);
            }

            //$loginMonth = (empty($loginMonth)) ? date('m') : $loginMonth;
            //$loginYear = (empty($loginYear)) ? date('Y') : $loginYear;
            //$dataSalary = $modelSalary->selectInfoOfListCompany($searchCompanyFilterId, $dateFilter, $payStatus)->get();
            $dataSalary = $modelSalary->selectInfoByListWork($listWorkId)->get();
            return view('work.pay.pay-salary.index', compact('dataAccess', 'modelStaff', 'dataStaff', 'dataSalary', 'loginMonth', 'loginYear', 'payStatus'));
        } else {
            return view('work.login');
        }

    }
    public function getAdd($salaryId)
    {
        $modelSalary = new QcSalary();
        $dataAccess = [
            'object' => 'paySalary',
            'subObjectLabel' => 'Thanh toán lương'
        ];
        $dataSalary = $modelSalary->getInfo($salaryId);
        return view('work.pay.pay-salary.add', compact('dataAccess', 'dataSalary'));
    }

    public function postAdd($salaryId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelSalary = new QcSalary();
        $modelSalaryPay = new QcSalaryPay();
        $staffLoginId = $modelStaff->loginStaffId();
        $dataSalary = $modelSalary->getInfo($salaryId);
        $salaryPay = $dataSalary->salary();
        $totalPaid = $dataSalary->totalPaid();

        $upPaid = $salaryPay - $totalPaid;
        $txtMoney = Request::input('txtMoney');
        $txtMoney = $hFunction->convertCurrencyToInt($txtMoney);
        /*if ($txtMoney > ($salaryPay - $totalPaid)) {
            echo "Số tiền không quá $upPaid";
        } else {
            if ($modelSalaryPay->insert($txtMoney, $hFunction->carbonNow(), $salaryId, $staffLoginId)) {
                if ($txtMoney == $upPaid) {
                    $modelSalary->updatePayStatus($salaryId);
                }
            } else {
                echo "Ngày thanh toán phải lớn hơn ngày tính lương";
            }
        }*/
    }

    //xóa
    public function deletePaySalaryBeforePay($payId)
    {
        $modelPayActivityDetail = new QcPayActivityDetail();
        $modelPayActivityDetail->deletePay($payId);
    }
}
