<?php

namespace App\Http\Controllers\Work\Pay\PaySalary;

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

class PaySalaryController extends Controller
{
    public function index($loginMonth = null, $loginYear = null, $payStatus = 3)
    {
        $modelStaff = new QcStaff();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelSalary = new QcSalary();
        $modelWork = new QcWork();
        $dataStaff = $modelStaff->loginStaffInfo();
        $dataAccess = [
            'object' => 'paySalary',
            'subObjectLabel' => 'Thanh toán lương'
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

    public function detailPay($salaryId)
    {
        $modelSalary = new QcSalary();
        $dataSalary = $modelSalary->getInfo($salaryId);
        return view('work.pay.pay-salary.detail', compact('dataSalary'));
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

    public function getPay($salaryId)
    {
        $modelStaff = new QcStaff();
        $modelSalary = new QcSalary();
        $modelImport = new QcImport();
        $dataAccess = [
            'object' => 'paySalary',
            'subObjectLabel' => 'Thanh toán lương'
        ];
        $dataSalary = $modelSalary->getInfo($salaryId);
        $dataWork = $dataSalary->work;
        $dataStaff = $dataWork->staffInfoOfWork();
        $staffId = $dataStaff->staffId();
        // danh sach mua vat tu dc xac nhan va chua thanh toan
        $dataImport = $modelImport->selectInfoOfStaffAndConfirmedAndUnpaid($staffId)->get();
        if (count($dataImport) > 0) {
            $totalMoneyImportUnpaid = $modelImport->totalMoneyOfListImport($dataImport);
        } else {
            $totalMoneyImportUnpaid = 0;
        }
        $totalKPIMoney = 0;
        return view('work.pay.pay-salary.pay', compact('dataAccess', 'dataSalary', 'totalMoneyImportUnpaid', 'totalKPIMoney'));
    }

    public function postPay($salaryId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelSalary = new QcSalary();
        $modelSalaryPay = new QcSalaryPay();
        $modelImport = new QcImport();
        $modelImportPay = new QcImportPay();
        $modelKeepMoney = new QcKeepMoney();
        $staffLoginId = $modelStaff->loginStaffId();
        $dataSalary = $modelSalary->getInfo($salaryId);
        $dataWork = $dataSalary->work;
        $salaryPay = $dataSalary->salary();
        $totalPaid = $dataSalary->totalPaid();
        $txtSalaryMoney = $salaryPay - $totalPaid;  # tong tien luong can thanh toán
        $totalKPIMoney = 0;

        //$txtImport = Request::input('txtImport');
        $txtBenefitMoney = Request::input('txtBenefitMoney');
        $txtBenefitMoney = $hFunction->convertCurrencyToInt($txtBenefitMoney);
        $txtBenefitMoneyDescription = Request::input('txtBenefitMoneyDescription');
        $txtKeepMoney = Request::input('txtKeepMoney');
        $txtKeepMoney = $hFunction->convertCurrencyToInt($txtKeepMoney);
        $txtKeepMoneyDescription = Request::input('txtKeepMoneyDescription');
        # cap nhat thuong
        if ($txtBenefitMoney > 0) {
            $modelSalary->updateBenefitMoney($salaryId, $txtBenefitMoney, $txtBenefitMoneyDescription);# thuong them
            $txtSalaryMoney = $txtSalaryMoney + $txtBenefitMoney;
        }
        # thanh toan luong
        if ($modelSalaryPay->insert($txtSalaryMoney, $hFunction->carbonNow(), $salaryId, $staffLoginId)) {
            $modelSalary->updatePayStatus($salaryId);
        }
        # giu tien
        if ($txtKeepMoney > 0) { # giu  lại tien
            $modelKeepMoney->insert($txtKeepMoney, $txtKeepMoneyDescription, $dataSalary->workId(), $staffLoginId);
        }
        # thanh toan nhap vat tu
        $dataStaff = $dataWork->staffInfoOfWork();
        $staffId = $dataStaff->staffId();
        // danh sach mua vat tu dc xac nhan va chua thanh toan
        $dataImport = $modelImport->selectInfoOfStaffAndConfirmedAndUnpaid($staffId)->get();
        if (count($dataImport) > 0) {
            foreach ($dataImport as $key => $import) {
                $importId = $import->importId();
                $totalImportPay = $import->totalMoneyOfImport();
                if ($modelImportPay->insert($totalImportPay,$importId , $staffLoginId)) {
                    $modelImport->confirmPaid($importId);
                }
            }
        }
        Session::put('notifySalaryPay', "Đã thanh toán thành công");
    }
    // xác nhận thanh toán
    /*public function getConfirmPay($importId)
    {
        $modelImport = new QcImport();
        $modelImport->updateConfirmPayOfImport($importId);
    }*/

    //xóa
    public function deletePayActivity($payId)
    {
        $modelPayActivityDetail = new QcPayActivityDetail();
        $modelPayActivityDetail->deletePay($payId);
    }
}
