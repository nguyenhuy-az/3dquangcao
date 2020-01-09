<?php

namespace App\Http\Controllers\Ad3d\Finance\Salary\BeforePayRequest;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\SalaryBeforePay\QcSalaryBeforePay;
use App\Models\Ad3d\SalaryBeforePayRequest\QcSalaryBeforePayRequest;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\Work\QcWork;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Input;
use File;
use DB;
use Request;

class BeforePayRequestController extends Controller
{
    public function index($companyFilterId = null, $dayFilter = null, $monthFilter = null, $yearFilter = null, $nameFiler = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelWork = new QcWork();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataAccess = [
            'accessObject' => 'payBeforeRequest'
        ];
        if (empty($dayFilter) && empty($monthFilter) && empty($yearFilter)) {
            $dateFilter = null;// date('Y-m-d');
            //$dayFilter = date('d');
            //$monthFilter = date('m');
            //$yearFilter = date('Y');
        } elseif ($dayFilter == 0) { //xem tất cả các ngày trong tháng
            $dateFilter = null;// date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        } else {
            $dateFilter = null;// date('Y-m-d', strtotime("$dayFilter-$monthFilter-$yearFilter"));
        }
        $dataCompany = $modelCompany->getInfo();
        if (empty($companyFilterId)) {
            if (!$dataStaffLogin->checkRootManage()) {
                $searchCompanyFilterId = [$dataStaffLogin->companyId()];
                $companyFilterId = $dataStaffLogin->companyId();
            } else {
                $searchCompanyFilterId = $modelCompany->listIdActivity();
            }
        } else {
            $searchCompanyFilterId = [$companyFilterId];
        }

        if (!empty($nameFiler)) {
            $listStaffId = $modelStaff->listIdOfListCompanyAndName($searchCompanyFilterId, $nameFiler);
        } else {
            $listStaffId = $modelStaff->listIdOfListCompany($searchCompanyFilterId);
        }
        $listWorkId = $modelWork->listIdOfListStaffId($listStaffId);
        //$listCompanyStaffWorkId = $modelCompanyStaffWork->listIdOfListCompanyAndListStaff($searchCompanyFilterId);
        //$listWorkId = $modelWork->listIdOfListCompanyStaffWorkBeginDate($listCompanyStaffWorkId, $dateFilter);

        if (empty($dateFilter)) {
            $dataSalaryBeforePayRequest = QcSalaryBeforePayRequest::whereIn('work_id', $listWorkId)->orderBy('created_at', 'DESC')->select('*')->paginate(30);
        } else {
            // phat trien loc theo ngay
            $dataSalaryBeforePayRequest = QcSalaryBeforePayRequest::whereIn('work_id', $listWorkId)->where('created_at', 'like', "%$dateFilter%")->orderBy('created_at', 'DESC')->select('*')->paginate(30);
        }
        return view('ad3d.finance.salary.before-pay-request.list', compact('modelStaff', 'dataCompany', 'dataAccess', 'dataSalaryBeforePayRequest', 'companyFilterId', 'dayFilter', 'monthFilter', 'yearFilter', 'nameFiler'));

    }

    public function getConfirm($requestId)
    {
        $modelSalaryBeforePayRequest = new QcSalaryBeforePayRequest();
        $dataSalaryBeforePayRequest = $modelSalaryBeforePayRequest->getInfo($requestId);
        if (count($dataSalaryBeforePayRequest) > 0) {
            return view('ad3d.finance.salary.before-pay-request.confirm', compact('dataSalaryBeforePayRequest'));
        }
    }

    public function postConfirm()
    {
        $modelStaff = new QcStaff();
        $modelSalaryBeforePayRequest = new QcSalaryBeforePayRequest();
        $moneyConfirm = Request::input('txtMoneyConfirm');
        $agreeStatus = Request::input('txtAgreeStatus');
        $confirmNote = Request::input('txtConfirmNote');
        $requestId = Request::input('txtRequest');
        $staffLoginId = $modelStaff->loginStaffId();

        if (!$modelSalaryBeforePayRequest->confirmRequest($requestId, $moneyConfirm, $agreeStatus, $confirmNote, $staffLoginId)) {
            return 'Hệ thống đang bảo trì';
        }

    }

    //xác nhận chuyển tiền
    public function getTransfer($requestId)
    {
        $modelSalaryBeforePayRequest = new QcSalaryBeforePayRequest();
        $dataSalaryBeforePayRequest = $modelSalaryBeforePayRequest->getInfo($requestId);
        if (count($dataSalaryBeforePayRequest) > 0) {
            return view('ad3d.finance.salary.before-pay-request.transfer', compact('dataSalaryBeforePayRequest'));
        }
    }

    public function postTransfer()
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelSalaryBeforePayRequest = new QcSalaryBeforePayRequest();
        $requestId = Request::input('txtRequest');
        $cbDay = Request::input('cbDay');
        $cbMonth = Request::input('cbMonth');
        $cbYear = Request::input('cbYear');
        $description = Request::input('txtDescription');
        $staffId = $modelStaff->loginStaffId();
        $datePay = $hFunction->convertStringToDatetime("$cbMonth/$cbDay/$cbYear 00:00:00");
        if (!$modelSalaryBeforePayRequest->confirmTransfer($requestId, $datePay, $description, $staffId)) {
            return 'Hệ thống đang bảo trì';
        }

    }

}
