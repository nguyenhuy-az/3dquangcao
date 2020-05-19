<?php

namespace App\Http\Controllers\Work\Money\Payment;

//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ad3d\ImportPay\QcImportPay;
use App\Models\Ad3d\OrderCancel\QcOrderCancel;
use App\Models\Ad3d\PayActivityDetail\QcPayActivityDetail;
use App\Models\Ad3d\SalaryBeforePay\QcSalaryBeforePay;
use App\Models\Ad3d\SalaryPay\QcSalaryPay;
use App\Models\Ad3d\Staff\QcStaff;
use File;
use Illuminate\Support\Facades\Session;
use Input;
use Request;

class PaymentController extends Controller
{
    public function index($object = 'importPay', $monthFilter = 0, $yearFilter = 0)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelImportPay = new QcImportPay();
        $modelSalaryPay = new QcSalaryPay();
        $modelSalaryBeforePay = new QcSalaryBeforePay();
        $modelOrderCancel = new QcOrderCancel();
        $modelPayActivityDetail = new QcPayActivityDetail();
        $dataAccess = [
            'object' => 'moneyStatisticalPayment',
        ];
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        if ($hFunction->checkCount($dataStaffLogin)) {
            $loginStaffId = $dataStaffLogin->staffId();
            $dateFilter = null;
            if ($monthFilter == 0 && $yearFilter == 0) { //xem  trong tháng
                $monthFilter = date('m');
                $yearFilter = date('Y');
                $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
            } elseif ($monthFilter == 100 && $yearFilter > 100) { //xem tất cả các ngày trong tháng
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
            if ($object == 'importPay') { # thanh toan mua vat tu
                $dataImportPay = $modelImportPay->infoAllOfPayStaffAndDate($loginStaffId, $dateFilter);
                return view('work.money.payment.import-pay', compact('dataAccess', 'modelStaff', 'dataImportPay', 'object', 'monthFilter', 'yearFilter'));
            }elseif ($object == 'activityPay') { # chi hoat dong
                $dataPayActivityDetail = $modelPayActivityDetail->infoConfirmedAndInvalidOfStaffAndDate($loginStaffId, $dateFilter);
                return view('work.money.payment.activity-pay', compact('dataAccess', 'modelStaff', 'dataPayActivityDetail', 'object', 'monthFilter', 'yearFilter'));
            } elseif ($object == 'salaryPay') { # thanh toan luong
                $dataSalaryPay = $modelSalaryPay->infoOfStaffAndDate($loginStaffId, $dateFilter);
                return view('work.money.payment.salary-pay', compact('dataAccess', 'modelStaff', 'dataSalaryPay', 'object', 'monthFilter', 'yearFilter'));
            } elseif ($object == 'salaryBeforePay') { # cho ung luong
                $dataSalaryBeforePay = $modelSalaryBeforePay->infoOfStaffAndDate($loginStaffId, $dateFilter);
                return view('work.money.payment.salary-before-pay', compact('dataAccess', 'modelStaff', 'dataSalaryBeforePay', 'object', 'monthFilter', 'yearFilter'));
            } elseif ($object == 'orderCancel') { # hoan tien don hang
                $dataOrderCancel = $modelOrderCancel->infoOfStaff($loginStaffId, $dateFilter);
                return view('work.money.payment.order-cancel', compact('dataAccess', 'modelStaff', 'dataOrderCancel', 'object', 'monthFilter', 'yearFilter'));
            }
        } else {
            return view('work.login');
        }

    }

}
