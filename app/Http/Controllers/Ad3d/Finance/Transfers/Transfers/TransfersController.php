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
    public function index($companyFilterId = 0, $dayFilter = 0, $monthFilter = 0, $yearFilter = 0, $transfersType = 100, $staffFilterId = 0)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelTransfers = new QcTransfers();
        $dataCompanyLogin = $modelStaff->companyLogin();
        $companyLoginId = $dataCompanyLogin->companyId();
        $companyFilterId = ($companyFilterId == 'null') ? null : $companyFilterId;
        if ($companyFilterId == 0) {
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
        } elseif ($dayFilter == 0 && $monthFilter > 0 && $yearFilter > 100) { //xem tất cả các ngày trong tháng
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        } elseif ($dayFilter > 0 && $monthFilter > 0 && $yearFilter > 100) { //xem tất cả các ngày trong tháng
            $dateFilter = date('Y-m-d', strtotime("$dayFilter-$monthFilter-$yearFilter"));
        } else {
            $dateFilter = date('Y-m');
            $dayFilter = 0;
            $monthFilter = date('m');
            $yearFilter = date('Y');
        }

        $selectTransfers = $modelTransfers->selectInfoByListTransfersStaffAndDate($listStaffId, $companyFilterId, $dateFilter, $transfersType);
        $dataTransfers = $selectTransfers->paginate(30);
        $totalMoneyTransfers = $modelTransfers->totalMoneyByListInfo($selectTransfers->get());
        return view('ad3d.finance.transfers.transfers.list', compact('modelStaff', 'modelTransfers', 'dataCompany', 'dataStaff', 'dataAccess', 'dataTransfers', 'totalMoneyTransfers', 'companyFilterId', 'dayFilter', 'monthFilter', 'yearFilter', 'transfersType', 'staffFilterId', 'totalMoneyTransfers'));

    }

    public function view($transfersId)
    {
        $hFunction = new \Hfunction();
        $modelTransfers = new QcTransfers();
        $dataTransfers = $modelTransfers->getInfo($transfersId);
        if ($hFunction->checkCount($dataTransfers)) {
            //return view('ad3d.finance.transfers.view', compact('dataTransfers'));
        }
    }

    # thuc hien chuyen tien
    public function getAdd()
    {
        $modelStaff = new QcStaff();
        $modelDepartment = new QcDepartment();
        $dataCompanyLogin = $modelStaff->companyLogin();
        $companyLoginId = $dataCompanyLogin->companyId();
        $dataAccess = [
            'accessObject' => 'transfers',
            'subObject' => 'transferTransfer'
        ];
        $dataStaffReceive = $dataCompanyLogin->staffInfoActivityOfTreasurerDepartment([$companyLoginId], $modelDepartment->treasurerDepartmentId());
        return view('ad3d.finance.transfers.transfers.add', compact('modelStaff', 'dataStaffReceive', 'dataAccess'));
    }

    public function postAdd()
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelTransfers = new QcTransfers();
        $cbReceiveStaffId = Request::input('cbReceiveStaff');
        $txtMoney = Request::input('txtMoney');
        $txtMoney = $hFunction->convertCurrencyToInt($txtMoney);
        $txtReason = Request::input('txtReason');
        $dataCompanyLogin = $modelStaff->companyLogin();
        $companyLoginId = $dataCompanyLogin->companyId();
        $staffId = $modelStaff->loginStaffId();
        # lay gia tri mac dinh
        $transferImage = $modelTransfers->getDefaultTransferImage();
        $TransferType = $modelTransfers->getDefaultTransferTypeOfInvestment();
        if ($modelTransfers->insert($txtMoney, $hFunction->carbonNow(), $txtReason, $transferImage, $staffId, $cbReceiveStaffId, $companyLoginId, $TransferType)) {
            return Session::put('notifyAdd', 'Thêm thành công, chọn thông tin và tiếp tục');
        } else {
            return Session::put('notifyAdd', 'Thêm thất bại, hãy thử lại');
        }
    }

    # cap nhat thong tin chuyen
    public function getEdit($transfersId)
    {
        $modelStaff = new QcStaff();
        $modelTransfers = new QcTransfers();
        $dataTransfers = $modelTransfers->getInfo($transfersId);
        return view('ad3d.finance.transfers.transfers.edit', compact('modelStaff', 'dataTransfers'));
    }

    public function postEdit($transfersId)
    {
        $hFunction = new \Hfunction();
        $modelTransfers = new QcTransfers();
        $txtMoney = Request::input('txtMoney');
        $txtMoney = $hFunction->convertCurrencyToInt($txtMoney);
        $txtReason = Request::input('txtReason');
        if (!$modelTransfers->updateInfo($transfersId, $txtMoney, $txtReason)) {
            return "Cập nhật thất bại";
        }

    }

    //xác nhận đã nhận tiền
    public function getConfirmReceive($transfersId)
    {
        $hFunction = new \Hfunction();
        $modelTransfers = new QcTransfers();
        $dataTransfers = $modelTransfers->getInfo($transfersId);
        if ($hFunction->checkCount($dataTransfers)) {
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
