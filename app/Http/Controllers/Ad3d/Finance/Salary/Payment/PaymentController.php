<?php

namespace App\Http\Controllers\Ad3d\Finance\Salary\Payment;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\Salary\QcSalary;
use App\Models\Ad3d\SalaryPay\QcSalaryPay;
use App\Models\Ad3d\Staff\QcStaff;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ad3d\Work\QcWork;
use Input;
use File;
use DB;
use Request;

class PaymentController extends Controller
{
    public function index($companyFilterId = null, $monthFilter = null, $yearFilter = null, $staffFilterId = 0)
    {
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelWork = new QcWork();
        $modelSalary = new QcSalary();
        $dataCompanyLogin = $modelStaff->companyLogin();
        $companyLoginId = $dataCompanyLogin->companyId();
        $companyFilterId = ($companyFilterId == 'null') ? null : $companyFilterId;
        if ($companyFilterId == null || $companyFilterId == 0) {
            $companyFilterId = $companyLoginId;
        }
        # lay thong tin cong ty cung he thong
        $dataCompany = $modelCompany->getInfoSameSystemOfCompany($companyLoginId);
        $dataAccess = [
            'accessObject' => 'salary'
        ];
        if (empty($monthFilter) && empty($yearFilter)) {
            $dateFilter = date('Y-m');
            $monthFilter = date('m');
            $yearFilter = date('Y');
        } elseif ($monthFilter == 0) { //xem t?t c? các ngày trong tháng
            $dateFilter = date('Y', strtotime("1-1-$yearFilter"));
        } else {
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        }
        if($monthFilter < 8 && $yearFilter <= 2019){ # du lieu cu phien ban cu --  loc theo staff_id
            if ($staffFilterId > 0) {
                $listStaffId = [$staffFilterId];
            } else {
                $listStaffId = $modelStaff->listIdOfListCompany([$companyFilterId]);
            }
            $listWorkId = $modelWork->listIdOfListStaffInBeginDate($listStaffId, $dateFilter);
        }else{ # du lieu phien ban moi - loc theo thong tin lam viec tai cty (companyStaffWork)
            if ($staffFilterId > 0) {
                $listCompanyStaffWorkId = $modelCompanyStaffWork->listIdOfListCompanyAndListStaff([$companyFilterId], [$staffFilterId]);
            } else {
                $listCompanyStaffWorkId = $modelCompanyStaffWork->listIdOfListCompanyAndListStaff([$companyFilterId]);
            }

            $listWorkId = $modelWork->listIdOfListCompanyStaffWorkBeginDate($listCompanyStaffWorkId, $dateFilter);
        }


        $dataSalary = $modelSalary->selectInfoByListWork($listWorkId)->paginate(30);
        //danh sach NV
        $dataStaffFilter = $modelCompany->staffInfoActivityOfListCompanyId([$companyFilterId]);
        return view('ad3d.finance.salary.payment.list', compact('modelStaff','dataStaffFilter', 'dataCompany', 'dataAccess', 'dataSalary', 'companyFilterId', 'monthFilter', 'yearFilter', 'staffFilterId'));


    }

    public function view($salaryId)
    {
        $modelSalary = new QcSalary();
        $dataSalary = $modelSalary->getInfo($salaryId);
        return view('ad3d.finance.salary.payment.view', compact('dataSalary'));
    }

    public function getAdd($salaryId)
    {
        $modelSalary = new QcSalary();
        $dataSalary = $modelSalary->getInfo($salaryId);
        return view('ad3d.finance.salary.payment.add', compact('dataSalary'));
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
        $fromDate = $dataSalary->work->fromDate();

        $upPaid = $salaryPay - $totalPaid;
        $txtMoney = Request::input('txtMoney');
        $txtMoney = $hFunction->convertCurrencyToInt($txtMoney);
        $cbDay = Request::input('cbDay');
        $cbMonth = Request::input('cbMonth');
        $cbYear = Request::input('cbYear');
        $datePay = $hFunction->convertStringToDatetime("$cbMonth/$cbDay/$cbYear 00:00:00");

        if ($txtMoney > ($salaryPay - $totalPaid)) {
            echo "Số tiền không quá $upPaid";
        } else {
            $cbMonth = ($cbMonth < 10) ? "0$cbMonth" : $cbMonth;
            $cbDay = ($cbDay < 10) ? "0$cbDay" : $cbDay;
            if ($hFunction->checkValidDate("$cbYear-$cbMonth-$cbDay")) {
                if ($datePay > $fromDate) {
                    if ($modelSalaryPay->insert($txtMoney, $datePay, $salaryId, $staffLoginId)) {
                        if ($txtMoney == $upPaid) {
                            $modelSalary->updatePayStatus($salaryId);
                        }
                    }
                } else {
                    echo "Ngày thanh toán phải lớn hơn ngày tính lương";
                }
            } else {
                echo "Ngày '$cbDay-$cbMonth-$cbYear' không hộp lệ ";
            }
        }
    }

}
