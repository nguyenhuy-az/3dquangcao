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
        $dataAccess = [
            'object' => 'moneyStatistical',
            'subObjectLabel' => 'Thống kê'
        ];
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        if ($hFunction->checkCount($dataStaffLogin)) {
            $loginStaffId = $dataStaffLogin->staffId();
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

            # nhan tien da - xac nhan
            $totalMoneyTransferReceive = $modelTransfer->totalMoneyConfirmedOfReceivedStaffAndDate($loginStaffId, $filterDate);

            # thanh toan mua vat tu - da duoc xac nha
            $totalMoneyImportPayOfPayStaff = $modelImportPay->totalMoneyConfirmedOfPayStaffAndDate($loginStaffId, $filterDate); # thanh toán tien mua vat tu cho nv

            # chi hoat dong - da duyet
            $totalMoneyPayActivityDetailOfStaff = $modelPayActivityDetail->totalMoneyConfirmedAndInvalidOfStaffAndDate($loginStaffId, $filterDate);

            # thanh toan luong - nguoi nhan da xac nhan
            $totalMoneySalaryPayOfStaff = $modelSalaryPay->totalMoneyConfirmedOfStaffAndDate($loginStaffId, $filterDate);

            # chi ung luong luong - da xac nhan
            $totalMoneySalaryBeforePayOfStaff = $modelSalaryBeforePay->totalMoneyConfirmedOfStaffAndDate($loginStaffId, $filterDate);

            // chi hoan tien don hang
            $totalPaidOrderCancelOfStaffAndDate = $modelStaff->totalPaidOrderCancelOfStaffAndDate($loginStaffId, $filterDate);
            return view('work.money.statistical.statistical', compact('dataAccess', 'modelStaff', 'loginDay', 'loginMonth', 'loginYear'),
                [
                    'totalMoneyOrderPay' => $totalMoneyOrderPay,
                    'totalMoneyTransferReceive' => $totalMoneyTransferReceive,
                    'totalMoneyImportPayOfPayStaff'=>$totalMoneyImportPayOfPayStaff,
                    'totalMoneyPayActivityDetailOfStaff'=>$totalMoneyPayActivityDetailOfStaff,
                    'totalMoneySalaryPayOfStaff'=>$totalMoneySalaryPayOfStaff,
                    'totalMoneySalaryBeforePayOfStaff' => $totalMoneySalaryBeforePayOfStaff,
                    'totalPaidOrderCancelOfStaffAndDate' => $totalPaidOrderCancelOfStaffAndDate
                ]);
        } else {
            return view('work.login');
        }

    }

}
