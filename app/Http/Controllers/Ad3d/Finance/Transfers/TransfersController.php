<?php

namespace App\Http\Controllers\Ad3d\Finance\Transfers;

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
    public function index($companyFilterId = null, $dayFilter = null, $monthFilter = null, $yearFilter = null, $transfersStatus = null)
    {
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $staffLoginId = $dataStaffLogin->staffId();
        $dataAccess = [
            'accessObject' => 'transfers'
        ];
        $dataCompany = $modelCompany->getInfo();
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

        if ($dataStaffLogin->checkRootManage()) {
            if (empty($companyFilterId)) {
                $searchCompanyFilterId = $modelCompany->listIdActivity();
            } else {
                $searchCompanyFilterId = [$companyFilterId];
            }
        } else {
            $searchCompanyFilterId = [$dataStaffLogin->companyId()];
            $companyFilterId = $dataStaffLogin->companyId();
        }

        //$listStaffId = $modelStaff->listIdOfListCompany($searchCompanyFilterId);


        if (empty($transfersStatus)) {
            if ($dataStaffLogin->checkRootManage()) {
                $transfersStatus = 'all';
            } else {
                $transfersStatus = 'transfers';
            }
        } else {
            //$transfersStatus = $transfersStatus;
        }
        if ($transfersStatus == 'receive') {
            $dataTransfers = QcTransfers::where('receiveStaff_id', $staffLoginId)->where('transfersDate', 'like', "%$dateFilter%")->whereIn('company_id', $searchCompanyFilterId)->orderBy('transfersDate', 'DESC')->orderBy('transfers_id', 'DESC')->select('*')->paginate(30);
            $totalMoneyTransfers = QcTransfers::where('receiveStaff_id', $staffLoginId)->where('transfersDate', 'like', "%$dateFilter%")->whereIn('company_id', $searchCompanyFilterId)->sum('money');

        } elseif ($transfersStatus == 'transfers') {
            $dataTransfers = QcTransfers::where('transfersStaff_id', $staffLoginId)->where('transfersDate', 'like', "%$dateFilter%")->whereIn('company_id', $searchCompanyFilterId)->orderBy('transfersDate', 'DESC')->orderBy('transfers_id', 'DESC')->select('*')->paginate(30);
            $totalMoneyTransfers = QcTransfers::where('transfersStaff_id', $staffLoginId)->where('transfersDate', 'like', "%$dateFilter%")->whereIn('company_id', $searchCompanyFilterId)->sum('money');
        } elseif ($transfersStatus == 'all') {
            $dataTransfers = QcTransfers::where('transfersDate', 'like', "%$dateFilter%")->whereIn('company_id', $searchCompanyFilterId)->orderBy('transfersDate', 'DESC')->orderBy('transfers_id', 'DESC')->select('*')->paginate(30);
            $totalMoneyTransfers = QcTransfers::where('transfersDate', 'like', "%$dateFilter%")->whereIn('company_id', $searchCompanyFilterId)->sum('money');
        }
        return view('ad3d.finance.transfers.list', compact('modelStaff', 'dataCompany', 'dataAccess', 'dataTransfers', 'totalMoneyTransfers', 'companyFilterId', 'dayFilter', 'monthFilter', 'yearFilter', 'transfersStatus'));

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
