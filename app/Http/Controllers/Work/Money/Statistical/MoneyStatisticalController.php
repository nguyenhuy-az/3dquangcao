<?php

namespace App\Http\Controllers\Work\Money\Statistical;

//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ad3d\Import\QcImport;
use App\Models\Ad3d\ImportDetail\QcImportDetail;
use App\Models\Ad3d\ImportPay\QcImportPay;
use App\Models\Ad3d\OrderPay\QcOrderPay;
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
    public function index($loginDay = null, $loginMonth = null, $loginYear = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelOrderPay = new QcOrderPay();
        $modelTransfer = new QcTransfers();
        $modelImport = new QcImport();
        $modelImportPay = new QcImportPay();
        $modelPayActivityDetail = new QcPayActivityDetail();
        $modelSalaryPay = new QcSalaryPay();
        $modelSalaryBeforePay = new QcSalaryBeforePay();

        $hFunction->dateDefaultHCM();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        if (count($dataStaffLogin) > 0) {
            $loginStaffId = $dataStaffLogin->staffId();
            $dataAccess = [
                'object' => 'moneyStatistical',
                'subObjectLabel' => 'Thống kê'
            ];
            $currentMonth = $hFunction->currentMonth();
            $currentYear = $hFunction->currentYear();
            $loginMonth = (empty($loginMonth)) ? $currentMonth : $loginMonth;
            $loginYear = (empty($loginYear)) ? $currentYear : $loginYear;
            If (empty($loginDay)) {
                $filterDate = date('Y-m', strtotime("$loginYear-$loginMonth"));
            } else {
                $filterDate = date('Y-m-d', strtotime("$loginYear-$loginMonth-$loginDay"));
            }
            # tien thu don hang
            $totalMoneyOrderPay = $modelOrderPay->totalMoneyOfStaffAndDate($loginStaffId, $filterDate);
            # chuyen - nhan tien
            $totalMoneyTransferReceive = $modelTransfer->totalMoneyOfReceiveStaffAndDate($loginStaffId, $filterDate);
            $totalMoneyTransferTransfer = $modelTransfer->totalMoneyOfTransferStaffAndDate($loginStaffId, $filterDate);
            # chi mua vat tu
            $totalMoneyImportOfStaff = $modelImport->totalMoneyImportOfStaff($loginStaffId, $filterDate, 3); # 3 lay tat ca phieu mua bao gom da thanh toan
            $totalMoneyImportPayOfImportStaff = $dataStaffLogin->totalMoneyImportOfStaff($loginStaffId, $filterDate,1); # nhan tien thanh toán
            # thanh toan mua vat tu
            $totalMoneyImportPayOfPayStaff = $modelImportPay->totalMoneyOfPayStaffAndDate($loginStaffId, $filterDate); # thanh toán tien mua vat tu cho nv
            # chi hoat dong
            $totalMoneyPayActivityDetailOfStaff = $modelPayActivityDetail->totalMoneyOfStaffAndDate($loginStaffId, $filterDate);
            # thanh toan luong
            $totalMoneySalaryPayOfStaff = $modelSalaryPay->totalMoneyOfStaffAndDate($loginStaffId, $filterDate);
            # chi ung luong luong
            $totalMoneySalaryBeforePayOfStaff = $modelSalaryBeforePay->totalMoneyOfStaffAndDate($loginStaffId, $filterDate);
            return view('work.money.statistical.statistical', compact('dataAccess', 'modelStaff', 'loginDay', 'loginMonth', 'loginYear'),
                [
                    'totalMoneyOrderPay' => $totalMoneyOrderPay,
                    'totalMoneyTransferReceive' => $totalMoneyTransferReceive,
                    'totalMoneyTransferTransfer' => $totalMoneyTransferTransfer,
                    'totalMoneyImportOfStaff' => $totalMoneyImportOfStaff,
                    'totalMoneyImportPayOfImportStaff' => $totalMoneyImportPayOfImportStaff,
                    'totalMoneyImportPayOfPayStaff'=>$totalMoneyImportPayOfPayStaff,
                    'totalMoneyPayActivityDetailOfStaff'=>$totalMoneyPayActivityDetailOfStaff,
                    'totalMoneySalaryPayOfStaff'=>$totalMoneySalaryPayOfStaff,
                    'totalMoneySalaryBeforePayOfStaff' => $totalMoneySalaryBeforePayOfStaff
                ]);
        } else {
            return view('work.login');
        }

    }

}
