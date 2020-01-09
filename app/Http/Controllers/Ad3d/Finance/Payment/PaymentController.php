<?php

namespace App\Http\Controllers\Ad3d\Finance\Payment;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\Payment\QcPayment;
use App\Models\Ad3d\PaymentType\QcPaymentType;
use App\Models\Ad3d\Staff\QcStaff;
use Illuminate\Support\Facades\Session;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Input;
use Request;

class PaymentController extends Controller
{
    public function index($companyFilterId = null, $dayFilter = null, $monthFilter = null, $yearFilter = null, $paymentTypeId = null)
    {
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelPaymentType = new QcPaymentType();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataAccess = [
            'accessObject' => 'payment'
        ];
        $dataCompany = $modelCompany->getInfo();
        $dataPaymentType = $modelPaymentType->getInfo();
       /* if (empty($companyFilterId)) {
            if (!$dataStaffLogin->checkRootManage()) {
                $searchCompanyFilterId = [$dataStaffLogin->companyId()];
                $companyFilterId = $dataStaffLogin->companyId();
            } else {
                $searchCompanyFilterId = $modelCompany->listIdActivity();
            }
        } else {
            $searchCompanyFilterId = [$companyFilterId];
        }*/

        if ($dataStaffLogin->checkRootManage()) {
            if (empty($companyFilterId)) {
                $searchCompanyFilterId = $modelCompany->listIdActivity();
            }else{
                $searchCompanyFilterId = [$companyFilterId];
            }
        }else{
            $searchCompanyFilterId = [$dataStaffLogin->companyId()];
            $companyFilterId = $dataStaffLogin->companyId();
        }
        //$listStaffId = $modelStaff->listIdOfListCompany($searchCompanyFilterId);

        if (empty($dayFilter) && empty($monthFilter) && empty($yearFilter)) {
            $dateFilter = date('Y-m');
            //$dayFilter = date('d');
            $monthFilter = date('m');
            $yearFilter = date('Y');
        } elseif ($dayFilter == 0) { //xem tất cả các ngày trong tháng
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        } else {
            $dateFilter = date('Y-m-d', strtotime("$dayFilter-$monthFilter-$yearFilter"));
        }
        if (empty($paymentTypeId)) {
            $dataPayment = QcPayment::where('datePay', 'like', "%$dateFilter%")->whereIn('company_id', $searchCompanyFilterId)->orderBy('datePay', 'DESC')->orderBy('payment_id', 'DESC')->select('*')->paginate(30);
            $totalMoneyPayment = QcPayment::where('datePay', 'like', "%$dateFilter%")->whereIn('company_id', $searchCompanyFilterId)->sum('money');
        } else {
            $dataPayment = QcPayment::where('type_id', $paymentTypeId)->where('datePay', 'like', "%$dateFilter%")->whereIn('company_id', $searchCompanyFilterId)->orderBy('datePay', 'DESC')->orderBy('payment_id', 'DESC')->select('*')->paginate(30);
            $totalMoneyPayment = QcPayment::where('type_id', $paymentTypeId)->where('datePay', 'like', "%$dateFilter%")->whereIn('company_id', $searchCompanyFilterId)->sum('money');
        }

        return view('ad3d.finance.payment.list', compact('modelStaff', 'dataCompany', 'dataPaymentType', 'dataAccess', 'dataPayment', 'totalMoneyPayment', 'companyFilterId', 'dayFilter', 'monthFilter', 'yearFilter', 'paymentTypeId'));

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

    public function getEdit($paymentId)
    {
        $modelStaff = new QcStaff();
        $modelPayment = new QcPayment();
        $modelCompany = new QcCompany();
        $modelPaymentType = new QcPaymentType();
        $dataCompany = $modelCompany->getInfo();
        $dataPayment = $modelPayment->getInfo($paymentId);
        $dataPaymentType = $modelPaymentType->getInfo();
        return view('ad3d.finance.payment.edit', compact('dataPaymentType', 'modelStaff', 'dataCompany', 'dataPayment'));
    }

    public function postEdit($paymentId)
    {
        $hFunction = new \Hfunction();
        $modelPayment = new QcPayment();
        $cbCompanyId = Request::input('cbCompanyAdd');
        $cbPaymentTypeId = Request::input('cbPaymentType');
        $cbDay = Request::input('cbDay');
        $cbMonth = Request::input('cbMonth');
        $cbYear = Request::input('cbYear');
        $txtMoney = Request::input('txtMoney');
        $txtReason = Request::input('txtReason');
        $cbDay = ($cbDay < 10) ? "0$cbDay" : $cbDay;
        $cbMonth = ($cbMonth < 10) ? "0$cbMonth" : $cbMonth;
        if ($hFunction->checkValidDate("$cbYear-$cbMonth-$cbDay")) {
            $datePay = $hFunction->convertStringToDatetime("$cbMonth/$cbDay/$cbYear 00:00:00");
            if (!$modelPayment->updateInfo($paymentId, $txtMoney, $datePay, $txtReason, $cbPaymentTypeId, $cbCompanyId)) {
                return "Cập nhật thất bại";
            }
        } else {
            return "Ngày $cbYear-$cbMonth-$cbDay không hợp lệ ";
        }

    }

    public function deletePayment($paymentId)
    {
        $modelPayment = new QcPayment();
        $modelPayment->deletePayment($paymentId);
    }

}
