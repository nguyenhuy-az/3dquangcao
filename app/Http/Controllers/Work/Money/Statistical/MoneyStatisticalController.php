<?php

namespace App\Http\Controllers\Work\Money\Statistical;

//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\ImportPay\QcImportPay;
use App\Models\Ad3d\PayActivityDetail\QcPayActivityDetail;
use App\Models\Ad3d\SalaryBeforePay\QcSalaryBeforePay;
use App\Models\Ad3d\SalaryPay\QcSalaryPay;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\Transfers\QcTransfers;
use File;
use Illuminate\Support\Facades\Session;
use Input;
use Request;

class MoneyStatisticalController extends Controller
{
    public function index($monthFilter = 100, $yearFilter = 0)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelImportPay = new QcImportPay();
        $modelPayActivityDetail = new QcPayActivityDetail();
        $modelSalaryPay = new QcSalaryPay();
        $modelSalaryBeforePay = new QcSalaryBeforePay();
        $hFunction->dateDefaultHCM();
        $dataAccess = [
            'object' => 'moneyStatistical',
            'subObjectLabel' => 'Thống kê'
        ];
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        if ($hFunction->checkCount($dataStaffLogin)) {
            $loginStaffId = $dataStaffLogin->staffId();
            $dateFilter = null;
            if ($monthFilter == 100 && $yearFilter == 0) { //xem  trong tháng
                $monthFilter = date('m');
                $yearFilter = date('Y');
                $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
            } elseif ($monthFilter == 0 && $yearFilter > 0) { //xem tất cả các thang trong năm
                $dateFilter = date('Y', strtotime("1-1-$yearFilter"));
            } elseif ($monthFilter > 0 && $monthFilter < 100 && $yearFilter > 100) { //xem tất cả các ngày trong tháng
                $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
            } elseif ($monthFilter == 100 && $yearFilter == 100) { //xem tất cả
                $dateFilter = null;
            } else {
                $dateFilter = date('Y-m');
                $monthFilter = date('m');
                $yearFilter = date('Y');
            }
            # tien nhan tu bo phan kinh doanh thu cua don hang
            $totalMoneyOrderPay = $modelStaff->totalMoneyReceiveTransferOrderPayConfirmed($loginStaffId, $dateFilter);

            # nhan tien dau tu - xac nhan
            $totalMoneyTransferReceive = $modelStaff->totalMoneyReceiveTransferInvestmentConfirmed($loginStaffId, $dateFilter);

            # thanh toan mua vat tu - da duoc xac nha
            $totalMoneyImportPayOfPayStaff = $modelImportPay->totalMoneyConfirmedOfPayStaffAndDate($loginStaffId, $dateFilter); # thanh toán tien mua vat tu cho nv

            # chi hoat dong - da duyet
            $totalMoneyPayActivityDetailOfStaff = $modelPayActivityDetail->totalMoneyConfirmedAndInvalidOfStaffAndDate($loginStaffId, $dateFilter);

            # thanh toan luong - da xac nhan
            $totalMoneySalaryPayOfStaff = $modelSalaryPay->totalMoneyConfirmedOfStaffAndDate($loginStaffId, $dateFilter);

            # chi ung luong luong - da xac nhan
            $totalMoneySalaryBeforePayOfStaff = $modelSalaryBeforePay->totalMoneyConfirmedOfStaffAndDate($loginStaffId, $dateFilter);

            # chi hoan tien don hang
            $totalPaidOrderCancelOfStaffAndDate = $modelStaff->totalPaidOrderCancelOfStaffAndDate($loginStaffId, $dateFilter);
            # nop tien cho cong ty
            $totalMoneyTransferForManage = $modelStaff->totalMoneyConfirmedTransferForTreasurerManage($loginStaffId, $dateFilter);
            return view('work.money.statistical.list', compact('dataAccess', 'modelStaff', 'dateFilter', 'monthFilter', 'yearFilter'),
                [
                    'totalMoneyOrderPay' => $totalMoneyOrderPay,
                    'totalMoneyTransferReceive' => $totalMoneyTransferReceive,
                    'totalMoneyTransferForTreasurerManage' => $totalMoneyTransferForManage,
                    'totalMoneyImportPayOfPayStaff' => $totalMoneyImportPayOfPayStaff,
                    'totalMoneyPayActivityDetailOfStaff' => $totalMoneyPayActivityDetailOfStaff,
                    'totalMoneySalaryPayOfStaff' => $totalMoneySalaryPayOfStaff,
                    'totalMoneySalaryBeforePayOfStaff' => $totalMoneySalaryBeforePayOfStaff,
                    'totalPaidOrderCancelOfStaffAndDate' => $totalPaidOrderCancelOfStaffAndDate
                ]);
        } else {
            return view('work.login');
        }

    }

    // --------- ------- Nop tien ---------- --------------
    public function getTransfers()
    {
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $dataCompanyLogin = $modelStaff->companyLogin();
        $dataAccess = [
            'object' => 'moneyStatistical',
            'subObjectLabel' => 'Thống kê'
        ];
        $limitTransfers = $modelCompany->totalKeepMoneyOfTreasurerStaff($modelStaff->loginStaffId(), null);
        # danh sach NV nhan tien la bo phan thu quy cua cty
        $dataStaffReceiveTransfer = $dataCompanyLogin->staffInfoActivityOfTreasurerManage($dataCompanyLogin->companyId());
        return view('work.money.statistical.transfers-add', compact('dataAccess', 'modelStaff', 'dataStaffReceiveTransfer', 'limitTransfers'));
    }

    public function postTransfers()
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelTransfers = new QcTransfers();
        $cbReceiveStaffId = Request::input('cbReceiveStaff');
        $txtMoney = Request::input('txtMoney');
        $txtMoney = $hFunction->convertCurrencyToInt($txtMoney);
        $txtReason = Request::input('txtReason');
        $dataCompanyLogin = $modelStaff->companyLogin();
        $companyLoginId = $dataCompanyLogin->companyId();
        $staffId = $modelStaff->loginStaffId();
        if ($modelTransfers->insert($txtMoney, $hFunction->carbonNow(), $txtReason, null, $staffId, $cbReceiveStaffId, $companyLoginId, 3)) {
            return Session::put('notifyAdd', 'Thêm thành công, chọn thông tin và tiếp tục');
        } else {
            return Session::put('notifyAdd', 'Thêm thất bại, hãy thử lại');
        }
    }

}
