<?php

namespace App\Http\Controllers\Ad3d\Finance\Transfers\Receive;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\Department\QcDepartment;
use App\Models\Ad3d\Transfers\QcTransfers;
use App\Models\Ad3d\Staff\QcStaff;
use Illuminate\Support\Facades\Session;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Input;
use Request;

class ReceiveController extends Controller
{
    public function index($companyFilterId = 0, $dayFilter = 0, $monthFilter = 0, $yearFilter = 0, $transfersType = 0, $staffFilterId = 0)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelTransfers = new QcTransfers();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $currentMonth = $hFunction->currentMonth();
        $currentYear = $hFunction->currentYear();
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
            'subObject' => 'transferReceive'
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
            $monthFilter = $currentMonth;
            $yearFilter = $currentYear;
            $dateFilter = date('Y-m-d', strtotime("$dayFilter-$currentMonth-$currentYear"));
        } else {
            $dateFilter = date('Y-m');
            $dayFilter = 0;
            $monthFilter = date('m');
            $yearFilter = date('Y');
        }
        $selectTransfers = $modelTransfers->selectInfoByListTransfersStaffAndDate($listStaffId, $companyFilterId, $dateFilter, $transfersType);
        $dataTransfers = $selectTransfers->paginate(30);
        $totalMoneyTransfers = $modelTransfers->totalMoneyByListInfo($selectTransfers->get());
        return view('ad3d.finance.transfers.receive.list', compact('modelStaff', 'dataCompany', 'dataStaff', 'dataAccess', 'dataTransfers', 'totalMoneyTransfers', 'companyFilterId', 'dayFilter', 'monthFilter', 'yearFilter', 'transfersType', 'staffFilterId', 'totalMoneyTransfers'));

    }

    public function view($transfersId)
    {
        $modelTransfers = new QcTransfers();
        $dataTransfers = $modelTransfers->getInfo($transfersId);
        if (count($dataTransfers) > 0) {
            return view('ad3d.finance.transfers.view', compact('dataTransfers'));
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


}
