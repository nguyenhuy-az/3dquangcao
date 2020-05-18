<?php

namespace App\Http\Controllers\Ad3d\Statistic\Revenue\Company;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\OrderPay\QcOrderPay;
use App\Models\Ad3d\Payment\QcPayment;
use App\Models\Ad3d\Staff\QcStaff;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RevenueCompanyController extends Controller
{
    public function index($companyStatisticId = null, $dayFilter = 0, $monthFilter = 0, $yearFilter = 0)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelPayment = new QcPayment();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $currentMonth = $hFunction->currentMonth();
        $currentYear = $hFunction->currentYear();
        //payment
        $dataAccess = [
            'accessObject' => 'receiveAndPay',
        ];
        $dateFilter = null;
        if ($dayFilter == 0 && $monthFilter == 0 && $yearFilter == 0) { //xem  trong tháng
            $dayFilter = 100;
            $monthFilter = date('m');
            $yearFilter = date('Y');
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        } elseif ($dayFilter == 100 && $monthFilter == 100 && $yearFilter > 100) { //xem tất cả các ngày trong tháng
            $dateFilter = date('Y', strtotime("1-1-$yearFilter"));
        } elseif ($dayFilter == 100 && $monthFilter > 0 && $monthFilter < 100 && $yearFilter > 100) { //xem tất cả các ngày trong tháng
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        } elseif ($dayFilter < 100 && $dayFilter > 0 && $monthFilter > 0 && $monthFilter < 100 && $yearFilter > 100) { //xem tất cả các ngày trong tháng
            $monthFilter = $currentMonth;
            $yearFilter = $currentYear;
            $dateFilter = date('Y-m-d', strtotime("$dayFilter-$currentMonth-$currentYear"));
        } elseif ($dayFilter == 100 && $monthFilter == 100 && $yearFilter == 100) { //xem tất cả
            $dateFilter = null;
        } else {
            $dateFilter = date('Y-m');
            $dayFilter = 100;
            $monthFilter = date('m');
            $yearFilter = date('Y');
        }

        $companyStatisticId = (empty($companyStatisticId)) ? $dataStaffLogin->companyId() : $companyStatisticId;
        if ($dataStaffLogin->checkRootManage()) {
            $dataCompany = $modelCompany->getInfo();
        } else {
            $dataCompany = $modelCompany->selectInfo($dataStaffLogin->companyId())->get();
        }

        $dataCompanyStatistic = $modelCompany->getInfo($companyStatisticId);
        $dataStaffOfCompany = $dataCompanyStatistic->staffInfoActivityOfListCompanyId([$companyStatisticId]);
        return view('ad3d.statistic.revenue.company.list', compact('modelStaff', 'modelCompany', 'dataAccess', 'dataCompany', 'dataCompanyStatistic', 'dataStaffOfCompany', 'dateFilter', 'dayFilter', 'monthFilter', 'yearFilter'));

    }

    public function staffStatistic($staffId, $statisticDate)
    {
        $modelStaff = new QcStaff();
        $dataStaff = $modelStaff->getInfo($staffId);
        $dataAccess = [
            'accessObject' => 'receiveAndPay',
            'statisticDate' => $statisticDate,
        ];
        return view('ad3d.statistic.revenue.company.detail', compact('modelStaff','dataAccess', 'dataStaff', 'statisticDate'));
    }

    // nhan tien tu don hang
    public function detailOrderPay($staffId, $statisticDate)
    {
        $modelStaff = new QcStaff();
        $dataStaff = $modelStaff->getInfo($staffId);
        $dataOrderPay = $modelStaff->orderPayInfoOfStaff($staffId, $statisticDate);
        return view('ad3d.statistic.revenue.company.detail-order-pay', compact('modelStaff','dataStaff','dataOrderPay', 'statisticDate'));
    }

    // chi tiet mua vat tu
    public function detailImport($staffId, $statisticDate)
    {
        $modelStaff = new QcStaff();
        $dataStaff = $modelStaff->getInfo($staffId);
        $dataImport = $dataStaff->importInfoOfStaff($staffId, 3, $statisticDate);
        return view('ad3d.statistic.revenue.company.detail-import', compact('modelStaff','dataStaff','dataImport', 'statisticDate'));
    }

    // chi tiet thanh toan luong
    public function detailSalaryPay($staffId, $statisticDate)
    {
        $modelStaff = new QcStaff();
        $dataStaff = $modelStaff->getInfo($staffId);
        $dataSalaryPay = $dataStaff->salaryPayInfoConfirmedOfStaff($staffId, $statisticDate);
        return view('ad3d.statistic.revenue.company.detail-salary-pay', compact('modelStaff','dataStaff','dataSalaryPay', 'statisticDate'));
    }

    // chi tiet ung luong
    public function detailSalaryBeforePay($staffId, $statisticDate)
    {
        $modelStaff = new QcStaff();
        $dataStaff = $modelStaff->getInfo($staffId);
        $dataSalaryBeforePay = $dataStaff->salaryBeforePayInfo($staffId, $statisticDate);
        return view('ad3d.statistic.revenue.company.detail-salary-before-pay', compact('modelStaff','dataStaff','dataSalaryBeforePay', 'statisticDate'));
    }

    // chuyen tien va da duoc xac nhan
    public function detailTransferMoney($staffId, $statisticDate)
    {
        $modelStaff = new QcStaff();
        $dataStaff = $modelStaff->getInfo($staffId);
        $dataTransfer = $dataStaff->transferConfirmedOfTransferStaff($staffId, $statisticDate);
        return view('ad3d.statistic.revenue.company.detail-transfer-money', compact('modelStaff','dataStaff','dataTransfer', 'statisticDate'));
    }

    // nhan tien duoc giao va da xac nhan
    public function detailReceiveMoney($staffId, $statisticDate)
    {
        $modelStaff = new QcStaff();
        $dataStaff = $modelStaff->getInfo($staffId);
        $dataTransfer = $dataStaff->transferConfirmedOfReceiveStaff($staffId, $statisticDate);
        return view('ad3d.statistic.revenue.company.detail-receive-money', compact('modelStaff','dataStaff','dataTransfer', 'statisticDate'));
    }

    // chi hoat dong
    public function detailPayActivity($staffId, $statisticDate)
    {
        $modelStaff = new QcStaff();
        $dataStaff = $modelStaff->getInfo($staffId);
        $dataPayActivityDetail = $dataStaff->payActivityDetailConfirmedOfReceiveStaff($staffId, $statisticDate);
        return view('ad3d.statistic.revenue.company.detail-pay-activity', compact('modelStaff','dataStaff','dataPayActivityDetail', 'statisticDate'));
    }
}
