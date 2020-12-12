<?php

namespace App\Http\Controllers\Work\Pay\PaySalary;

use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\Import\QcImport;
use App\Models\Ad3d\ImportPay\QcImportPay;
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

class PaySalaryController extends Controller
{
    public function index($filterMonth = null, $filterYear = null, $payStatus = 3)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelSalary = new QcSalary();
        $modelSalaryPay = new QcSalaryPay();
        $modelWork = new QcWork();
        $dataStaff = $modelStaff->loginStaffInfo();
        $dataAccess = [
            'object' => 'paySalary',
            'subObjectLabel' => 'Thanh toán lương'
        ];
        $loginStaffId = $modelStaff->loginStaffId();
        if (empty($filterMonth) && empty($filterYear)) {
            $dateFilter = date('Y-m');
            $filterMonth = date('m');
            $filterYear = date('Y');
        } elseif ($filterMonth == 0) { //xem tat ca cac thang
            $dateFilter = date('Y', strtotime("1-1-$filterYear"));
        } else {
            $dateFilter = date('Y-m', strtotime("1-$filterMonth-$filterYear"));
        }
        $searchCompanyFilterId = [$dataStaff->companyId()];
        if ($filterMonth < 8 && $filterYear <= 2019) { # du lieu cu phien ban cu --  loc theo staff_id
            $listStaffId = $modelStaff->listIdOfListCompany($searchCompanyFilterId);
            $listWorkId = $modelWork->listIdOfListStaffInBeginDate($listStaffId, $dateFilter);
        } else { # du lieu phien ban moi - loc theo thong tin lam viec tai cty (companyStaffWork)
            $listCompanyStaffWorkId = $modelCompanyStaffWork->listIdOfListCompanyAndListStaff($searchCompanyFilterId);
            $listWorkId = $modelWork->listIdOfListCompanyStaffWorkBeginDate($listCompanyStaffWorkId, $dateFilter);
        }
        #thong tin bang lương
        $dataSalary = $modelSalary->selectInfoByListWork($listWorkId)->get();
        # thong tin thanh toan trong tháng
        $dataSalaryPay = $modelSalaryPay->infoOfStaffAndDate($loginStaffId, $dateFilter);
        return view('work.pay.pay-salary.list', compact('dataAccess', 'modelStaff', 'dataStaff', 'dataSalary', 'dataSalaryPay', 'dateFilter', 'filterMonth', 'filterYear', 'payStatus'));

    }

    public function detailPay($salaryId)
    {
        $modelSalary = new QcSalary();
        $dataSalary = $modelSalary->getInfo($salaryId);
        return view('work.pay.pay-salary.detail', compact('dataSalary'));
    }

    #====== ======= cong tien them
    public function getAddBenefit($salaryId)
    {
        $modelSalary = new QcSalary();
        $dataSalary = $modelSalary->getInfo($salaryId);
        return view('work.pay.pay-salary.add-benefit', compact('dataAccess', 'dataSalary'));
    }

    public function postAddBenefit($salaryId)
    {
        $hFunction = new \Hfunction();
        $modelSalary = new QcSalary();
        $txtBenefitMoney = Request::input('txtBenefitMoney');
        $txtBenefitMoney = $hFunction->convertCurrencyToInt($txtBenefitMoney);
        $txtBenefitMoneyDescription = Request::input('txtBenefitMoneyDescription');
        return $modelSalary->updateBenefitMoney($salaryId, $txtBenefitMoney, $txtBenefitMoneyDescription);
    }

    #======== ====== giu tien
    public function getAddKeepMoney($salaryId)
    {
        $modelSalary = new QcSalary();
        $dataSalary = $modelSalary->getInfo($salaryId);
        return view('work.pay.pay-salary.add-keep-money', compact('dataAccess', 'dataSalary'));
    }

    public function postAddKeepMoney($salaryId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelKeepMoney = new QcKeepMoney();
        $txtKeepMoney = Request::input('txtKeepMoney');
        $txtKeepMoney = $hFunction->convertCurrencyToInt($txtKeepMoney);
        $txtKeepMoneyDescription = Request::input('txtKeepMoneyDescription');
        $modelKeepMoney->insert($txtKeepMoney, $txtKeepMoneyDescription, $salaryId, $modelStaff->loginStaffId());
    }

    #====== ====== thanh toán =====
    public function getAddPayment($salaryId)
    {
        $modelStaff = new QcStaff();
        $modelSalary = new QcSalary();
        $dataSalary = $modelSalary->getInfo($salaryId);
        $dataWork = $dataSalary->work;
        $fromDate = $dataWork->fromDate();
        $dataStaff = $dataWork->staffInfoOfWork();
        $staffId = $dataStaff->staffId();
        # ma cong ty
        $companyId = $dataWork->companyStaffWork->companyId();
        // danh sach mua vat tu dc xac nhan va chua thanh toan
        $totalMoneyImportUnpaid = $dataStaff->importTotalMoneyHasConfirmNotPay($companyId, $staffId, date('Y-m', strtotime($fromDate)));
        return view('work.pay.pay-salary.add-payment', compact('modelStaff', 'dataSalary', 'totalMoneyImportUnpaid'));
    }

    public function postAddPayment($salaryId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelSalary = new QcSalary();
        $modelSalaryPay = new QcSalaryPay();
        $modelImport = new QcImport();
        $modelImportPay = new QcImportPay();
        $staffLoginId = $modelStaff->loginStaffId();
        $dataSalary = $modelSalary->getInfo($salaryId);
        $dataWork = $dataSalary->work;
        $fromDate = $dataWork->fromDate();
        $dataStaff = $dataWork->staffInfoOfWork();
        $staffId = $dataStaff->staffId();
        # ma cong ty
        $companyId = $dataWork->companyStaffWork->companyId();
        $totalPayment = $dataSalary->totalSalaryUnpaid();
        # thanh toan luong
        if ($modelSalaryPay->insert($totalPayment, $hFunction->carbonNow(), $salaryId, $staffLoginId)) {
            $modelSalary->updateFinishPay($salaryId);
        }

        # mua vat tu da duyet chua thanh toán
        $totalMoneyImportUnpaid = $dataStaff->importTotalMoneyHasConfirmNotPay($companyId, $staffId, date('Y-m', strtotime($fromDate)));
        if ($totalMoneyImportUnpaid > 0) {
            # thanh toan nhap vat tu
            # danh sach mua vat tu dc xac nhan va chua thanh toan
            $dataImport = $modelImport->selectInfoOfListStaffIdAndHasConfirmNotPay($companyId, [$staffId], date('Y-m', strtotime($fromDate)))->get();
            if ($hFunction->checkCount($dataImport)) {
                foreach ($dataImport as $key => $import) {
                    $importId = $import->importId();
                    $totalImportPay = $import->totalMoneyOfImport();
                    if ($modelImportPay->insert($totalImportPay, $importId, $staffLoginId)) {
                        $modelImport->confirmPaid($importId);
                    }
                }
            }
        }
    }

    //huy thong tin thanh toan
    public function deletePay($payId)
    {
        $modelSalary = new QcSalary();
        $modelSalaryPay = new QcSalaryPay();
        $salaryId = $modelSalaryPay->salaryId($payId);
        if ($modelSalaryPay->deleteSalaryPay($payId)) {
            $modelSalary->updateUnFinishPay($salaryId);
        }
    }
}
