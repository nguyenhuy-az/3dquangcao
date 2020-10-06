<?php

namespace App\Http\Controllers\Ad3d\Finance\Transfers\Transfers;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\Department\QcDepartment;
use App\Models\Ad3d\Transfers\QcTransfers;
use App\Models\Ad3d\Staff\QcStaff;
use Illuminate\Support\Facades\Session;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Input;
use Request;

class TransfersController extends Controller
{
    public function index($companyFilterId = null, $dayFilter = 0, $monthFilter = 0, $yearFilter = 0, $transfersType = 0, $staffFilterId = 0)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelTransfers = new QcTransfers();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $staffLoginId = $dataStaffLogin->staffId();
        $currentMonth = $hFunction->currentMonth();
        $currentYear = $hFunction->currentYear();
        $dataCompanyLogin = $modelStaff->companyLogin();
        $companyLoginId = $dataCompanyLogin->companyId();
        $companyFilterId = ($companyFilterId == 'null') ? null : $companyFilterId;
        if ($companyFilterId == null || $companyFilterId == 0) {
            $companyFilterId = $companyLoginId;
        }
        if ($staffFilterId > 0) {
            $listStaffId = [$staffFilterId];
        } else {
            $listStaffId = $modelStaff->listIdOfCompany($companyFilterId);
        }
        # lay thong tin cong ty cung he thong
        $dataCompany = $modelCompany->getInfoSameSystemOfCompany($companyLoginId);
        //danh sach NV
        $dataStaff = $modelCompany->staffInfoActivityOfListCompanyId([$companyFilterId]);
        $dataAccess = [
            'accessObject' => 'transfers',
            'subObject' => 'transferTransfer'
        ];
        $dateFilter = null;
        if ($dayFilter == 0 && $monthFilter == 0 && $yearFilter == 0) { //xem  trong tháng
            $yearFilter = date('Y');
            $dateFilter = date('Y', strtotime("1-1-$yearFilter"));
        } elseif ($dayFilter == 0 && $monthFilter == 0 && $yearFilter > 100) { //xem tất cả các ngày trong tháng
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
        $selectTransfers = $modelTransfers->selectInfoByListTransfersStaffAndDate($listStaffId, $companyFilterId, $dateFilter, $transfersType);
        $dataTransfers = $selectTransfers->paginate(30);
        $totalMoneyTransfers = $modelTransfers->totalMoneyByListInfo($selectTransfers->get());
        return view('ad3d.finance.transfers.transfers.list', compact('modelStaff', 'dataCompany','dataStaff', 'dataAccess', 'dataTransfers', 'totalMoneyTransfers', 'companyFilterId', 'dayFilter', 'monthFilter', 'yearFilter', 'transfersType','staffFilterId','totalMoneyTransfers'));

    }

    public function view($transfersId)
    {
        $modelTransfers = new QcTransfers();
        $dataTransfers = $modelTransfers->getInfo($transfersId);
        if (count($dataTransfers) > 0) {
            return view('ad3d.finance.transfers.view', compact('dataTransfers'));
        }
    }

    public function getAdd()
    {
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelDepartment = new QcDepartment();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataAccess = [
            'accessObject' => 'transfers'
        ];
        if ($dataStaffLogin->checkRootManage()) {
            $companyFilterId = $modelCompany->listIdActivity();# lay tat ca NV thu quy cua he thong
        } else {
            $companyFilterId = [$dataStaffLogin->companyId()];# lay NV thu quy cua cty dang lam
        }
        $dataStaffReceive = $modelStaff->getInfoActivityOfListCompanyAndDepartment($companyFilterId, $modelDepartment->treasurerDepartmentId());
        return view('ad3d.finance.transfers.add', compact('modelStaff', 'dataStaffReceive', 'dataAccess'));
    }

    public function postAdd()
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelTransfers = new QcTransfers();
        $cbReceiveStaffId = Request::input('cbReceiveStaff');
        $cbDay = Request::input('cbDay');
        $cbMonth = Request::input('cbMonth');
        $cbYear = Request::input('cbYear');
        $txtMoney = Request::input('txtMoney');
        $txtMoney = $hFunction->convertCurrencyToInt($txtMoney);
        $txtReason = Request::input('txtReason');
        $staffId = $modelStaff->loginStaffId();
        $datePay = $hFunction->convertStringToDatetime("$cbMonth/$cbDay/$cbYear 00:00:00");
        $cbDay = ($cbDay < 10) ? "0$cbDay" : $cbDay;
        $cbMonth = ($cbMonth < 10) ? "0$cbMonth" : $cbMonth;
        if ($hFunction->checkValidDate("$cbYear-$cbMonth-$cbDay")) {
            if ($modelTransfers->insert($txtMoney, $datePay, $txtReason, null, $staffId, $cbReceiveStaffId, null)) {
                return Session::put('notifyAdd', 'Thêm thành công, chọn thông tin và tiếp tục');
            } else {
                return Session::put('notifyAdd', 'Thêm thất bại, hãy thử lại');
            }
        } else {
            return Session::put('notifyAdd', "Ngày '$cbYear-$cbMonth-$cbDay' không hộp lệ ");
        }

    }

    public function getEdit($transfersId)
    {
        $modelStaff = new QcStaff();
        $modelTransfers = new QcTransfers();
        $dataTransfers = $modelTransfers->getInfo($transfersId);
        return view('ad3d.finance.transfers.edit', compact('modelStaff', 'dataTransfers'));
    }

    public function postEdit($transfersId)
    {
        $hFunction = new \Hfunction();
        $modelTransfers = new QcTransfers();
        $cbReceiveStaffId = Request::input('cbReceiveStaff');
        $cbDay = Request::input('cbDay');
        $cbMonth = Request::input('cbMonth');
        $cbYear = Request::input('cbYear');
        $txtMoney = Request::input('txtMoney');
        $txtReason = Request::input('txtReason');
        $cbDay = ($cbDay < 10) ? "0$cbDay" : $cbDay;
        $cbMonth = ($cbMonth < 10) ? "0$cbMonth" : $cbMonth;
        if ($hFunction->checkValidDate("$cbYear-$cbMonth-$cbDay")) {
            $transferDate = $hFunction->convertStringToDatetime("$cbMonth/$cbDay/$cbYear 00:00:00");
            if (!$modelTransfers->updateInfo($transfersId, $txtMoney, $transferDate, $txtReason, $cbReceiveStaffId, null)) {
                return "Cập nhật thất bại";
            }
        } else {
            return "Ngày $cbYear-$cbMonth-$cbDay không hợp lệ ";
        }

    }

    //xác nhận đã nhận tiền
    public function getConfirmReceive($transfersId)
    {
        $modelTransfers = new QcTransfers();
        $dataTransfers = $modelTransfers->getInfo($transfersId);
        if (count($dataTransfers) > 0) {
            return view('ad3d.finance.transfers.confirm-receive', compact('modelStaff', 'dataTransfers'));
        }
    }

    public function postConfirmReceive($transfersId)
    {
        $modelTransfer = new QcTransfers();
        $modelTransfer->updateConfirmReceive($transfersId);
    }


    public function deleteTransfers($transfersId)
    {
        $modelTransfers = new QcTransfers();
        $modelTransfers->deleteTransfers($transfersId);
    }

}
