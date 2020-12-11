<?php

namespace App\Http\Controllers\Ad3d\Finance\Pay\PayActivity;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\PayActivityDetail\QcPayActivityDetail;
use App\Models\Ad3d\Payment\QcPayment;
use App\Models\Ad3d\PaymentType\QcPaymentType;
use App\Models\Ad3d\Staff\QcStaff;
use Illuminate\Support\Facades\Session;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Input;
use Request;

class PayActivityController extends Controller
{
    public function index($companyFilterId = null, $dayFilter = null, $monthFilter = null, $yearFilter = null, $confirmStatusFilter = 3, $staffFilterId = 0)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelPayActivityDetail = new QcPayActivityDetail();
        $currentMonth = $hFunction->currentMonth();
        $currentYear = $hFunction->currentYear();
        $dataAccess = [
            'accessObject' => 'payActivityDetail'
        ];
        $dataCompanyLogin = $modelStaff->companyLogin();
        $companyLoginId = $dataCompanyLogin->companyId();
        $companyFilterId = ($companyFilterId == 'null') ? null : $companyFilterId;
        if ($companyFilterId == null || $companyFilterId == 0) {
            $companyFilterId = $companyLoginId;
        }
        # lay thong tin cong ty cung he thong
        $dataCompany = $modelCompany->getInfoSameSystemOfCompany($companyLoginId);
        if(empty($dayFilter) && empty($monthFilter) && empty($yearFilter)){
            $monthFilter = date('m');
            $yearFilter = date('Y');
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        }
        elseif ($dayFilter == 0 && $monthFilter == 0 && $yearFilter > 0) { //xem tất cả các ngày trong tháng
            $dateFilter = date('Y', strtotime("1-1-$yearFilter"));
        } elseif ($dayFilter == 0 && $monthFilter == 0 && $yearFilter > 0) { //xem tất cả các ngày trong tháng
            $dateFilter = date('Y', strtotime("1-1-$yearFilter"));
        } elseif ($dayFilter == 0 && $monthFilter > 0 && $yearFilter > 0) { //xem tất cả các ngày trong tháng
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        } elseif ($dayFilter > 0 && $monthFilter > 0 && $yearFilter > 0) {
            $dateFilter = date('Y-m-d', strtotime("$dayFilter-$monthFilter-$yearFilter"));
        } elseif ($dayFilter > 0 && $monthFilter == 0 && $yearFilter == 0) { //xem tất cả các ngày trong tháng
            $monthFilter = $currentMonth;
            $yearFilter = $currentYear;
            $dateFilter = date('Y-m-d', strtotime("$dayFilter-$currentMonth-$currentYear"));
        } elseif ($dayFilter == 0 && $monthFilter > 0 && $yearFilter == 0) { //xem tất cả các ngày trong tháng
            $yearFilter = $currentYear;
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$currentYear"));
        } else {
            $dateFilter = date('Y-m');
            $monthFilter = date('m');
            $yearFilter = date('Y');
        }

        if ($staffFilterId > 0) {
            $selectPayActivity = $modelPayActivityDetail->selectInfoOfListCompanyAndStaff([$companyFilterId], $staffFilterId, $dateFilter, $confirmStatusFilter);
        } else {
            $selectPayActivity = $modelPayActivityDetail->selectInfoOfListCompany([$companyFilterId], $dateFilter, $confirmStatusFilter);
        }

        $dataPayActivityDetail = $selectPayActivity->paginate(30);
        $totalMoneyPayActivity = $modelPayActivityDetail->totalMoneyOfListPayActivity($selectPayActivity->get());
        //danh sach NV
        $dataStaff = $modelCompany->staffInfoActivityOfListCompanyId([$companyFilterId]);
        return view('ad3d.finance.pay.pay-activity.list', compact('modelStaff','modelPayActivityDetail', 'dataCompany', 'dataPayActivityDetail', 'dataAccess', 'dataStaff', 'totalMoneyPayActivity', 'companyFilterId', 'dayFilter', 'monthFilter', 'yearFilter', 'confirmStatusFilter', 'staffFilterId'));

    }

    public function view($paymentId)
    {
        $modelPayment = new QcPayment();
        $dataPayment = $modelPayment->getInfo($paymentId);
        if (count($dataPayment) > 0) {
            return view('ad3d.finance.payment.view', compact('dataPayment'));
        }
    }

    public function getAdd()
    {
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelPaymentType = new QcPaymentType();
        $dataAccess = [
            'accessObject' => 'payment'
        ];

        $dataCompany = $modelCompany->getInfo();
        $dataPaymentType = $modelPaymentType->getInfo();
        return view('ad3d.finance.payment.add', compact('modelStaff', 'dataCompany', 'dataAccess', 'dataPaymentType'));
    }

    public function postAdd()
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelPayment = new QcPayment();
        $cbCompanyId = Request::input('cbCompanyAdd');
        $cbPaymentTypeId = Request::input('cbPaymentType');
        $cbDay = Request::input('cbDay');
        $cbMonth = Request::input('cbMonth');
        $cbYear = Request::input('cbYear');
        $txtMoney = Request::input('txtMoney');
        $txtReason = Request::input('txtReason');
        $staffId = $modelStaff->loginStaffId();
        $datePay = $hFunction->convertStringToDatetime("$cbMonth/$cbDay/$cbYear 00:00:00");
        $cbDay = ($cbDay < 10) ? "0$cbDay" : $cbDay;
        $cbMonth = ($cbMonth < 10) ? "0$cbMonth" : $cbMonth;
        if ($hFunction->checkValidDate("$cbYear-$cbMonth-$cbDay")) {
            if ($modelPayment->insert($txtMoney, $datePay, $txtReason, $cbPaymentTypeId, $staffId, $cbCompanyId)) {
                return Session::put('notifyAdd', 'Thêm thành công, chọn thông tin và tiếp tục');
            } else {
                return Session::put('notifyAdd', 'Thêm thất bại, hãy thử lại');
            }
        } else {
            return Session::put('notifyAdd', "Ngày '$cbYear-$cbMonth-$cbDay' không hộp lệ ");
        }

    }

    public function getConfirm($payId)
    {
        $modelStaff = new QcStaff();
        $modelPayActivityDetail = new QcPayActivityDetail();
        $dataPayActivityDetail = $modelPayActivityDetail->getInfo($payId);
        return view('ad3d.finance.pay.pay-activity.confirm', compact('dataPaymentType', 'modelStaff', 'dataPayActivityDetail'));
    }

    public function postConfirm($paymentId)
    {
        $modelStaff = new QcStaff();
        $modelPayActivityDetail = new QcPayActivityDetail();
        $cbInvalidStatus = Request::input('cbInvalidStatus');
        $txtConfirmNote = Request::input('txtConfirmNote');
        if (!$modelPayActivityDetail->confirmPay($paymentId, $cbInvalidStatus, $txtConfirmNote, $modelStaff->loginStaffId())) {
            return "Tính năng đang bảo trì";
        }
    }

}
