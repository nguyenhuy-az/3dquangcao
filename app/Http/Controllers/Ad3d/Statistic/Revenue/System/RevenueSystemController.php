<?php

namespace App\Http\Controllers\Ad3d\Statistic\Revenue\System;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\Payment\QcPayment;
use App\Models\Ad3d\Staff\QcStaff;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RevenueSystemController extends Controller
{
    public function index($dayFilter = null, $monthFilter = null, $yearFilter = null)
    {
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelPayment = new QcPayment();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        if ($dataStaffLogin->checkRootManage()) {
            $dataCompany = $modelCompany->getInfo();
        } else {
            $dataCompany = $modelCompany->selectInfo($dataStaffLogin->companyId())->get();
        }
        if ($dayFilter == null && $monthFilter == null && $yearFilter == null) {
            $dateFilter = date('Y-m');
            $monthFilter = date('m');
            $yearFilter = date('Y');
            //$dayFilter = date('d');
        }
        if ($yearFilter == 0) { // tất cả các năm
            $dayFilter = 0;
            $monthFilter = 0;
            $yearFilter = 0;
            $dateFilter = null;
        } elseif ($yearFilter > 0) {
            if ($monthFilter > 0) { // tìm theo tháng
                if ($dayFilter > 0) { //tìm theo ngày
                    $dateFilter = date('Y-m-d', strtotime("$dayFilter-$monthFilter-$yearFilter"));
                } else { // tất cả các ngày trong tháng
                    $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
                }
            } else { // tất cả các tháng
                $dayFilter = 0;
                $dateFilter = date('Y', strtotime("1-1-$yearFilter"));
            }
        }

        //payment
        $dataAccess = [
            'accessObject' => 'revenue',
            'statisticDate' => $dateFilter,
            //'dataPaymentStaffInfo' => $modelStaff->staffPaidInfo($statisticCompanyId, $dateFilter),
            #'totalPaid' => $modelPayment->totalPaidOfCompany($statisticCompanyId, $dateFilter),
            #'totalSalaryBeforePay' => $modelStaff->totalSalaryBeforeOfCompany($statisticCompanyId, $dateFilter),
            #'totalOrderPay' => $modelCompany->totalOrderPayOfCompany($statisticCompanyId, $dateFilter),
            #'dataStaffOrderPayInfo'=>$modelStaff->staffOrderPayInfo($statisticCompanyId, $dateFilter),
            #'totalSalaryPaid'=>$modelStaff->totalSalaryPaidOfCompany($statisticCompanyId, $dateFilter),
        ];
        return view('ad3d.statistic.revenue.system.list', compact('modelStaff', 'modelCompany', 'dataAccess', 'dataCompany','dayFilter', 'monthFilter', 'yearFilter'));

    }
}
