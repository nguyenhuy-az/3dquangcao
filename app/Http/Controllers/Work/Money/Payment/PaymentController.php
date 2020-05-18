<?php

namespace App\Http\Controllers\Work\Money\Payment;

//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ad3d\ImportPay\QcImportPay;
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
            if ($object == 'importPay') {
                $dataImportPay = $modelImportPay->infoAllOfPayStaffAndDate($loginStaffId, $dateFilter);
                return view('work.money.payment.import-pay', compact('dataAccess', 'modelStaff','dataImportPay', 'object', 'monthFilter', 'yearFilter'));
            } elseif ($object == 'salaryPay') {

            } elseif ($object == 'salaryBeforePay') {

            } elseif ($object == 'orderPay') {

            }
        } else {
            return view('work.login');
        }

    }

}
