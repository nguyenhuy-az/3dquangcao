<?php

namespace App\Http\Controllers\Work\Money\Transfer;

//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\Transfers\QcTransfers;
use App\Models\Ad3d\TransfersDetail\QcTransfersDetail;
use File;
use Illuminate\Support\Facades\Session;
use Input;
use Request;

class MoneyTransferController extends Controller
{
    #================================ chuyen tien ========================
    public function transferIndex($monthFilter = 0, $yearFilter = 0)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $dataAccess = [
            'object' => 'moneyTransfer',
            'subObjectLabel' => 'Giao tiền'
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
            $dataTransfer = $dataStaffLogin->transferOfTransferStaff($loginStaffId, $dateFilter);
            return view('work.money.transfer.transfer', compact('dataAccess', 'modelStaff', 'dataTransfer', 'dateFilter', 'monthFilter', 'yearFilter'));
        } else {
            return view('work.login');
        }

    }

    public function transferView($transferId)
    {
        $modelTransfer = new QcTransfers();
        $dataTransfer = $modelTransfer->getInfo($transferId);
        $dataTransferDetail = $dataTransfer->transfersDetailInfo();
        return view('work.money.transfer.transfer-view', compact('dataAccess', 'modelStaff', 'dataTransfer', 'dataTransferDetail'));
    }

    # thong tin chuyen
    public function transferInfo($transferId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelTransfer = new QcTransfers();
        $dataAccess = [
            'object' => 'moneyTransfer',
            'subObjectLabel' => 'Giao tiền'
        ];
        $dataTransfer = $modelTransfer->getInfo($transferId);
        if($hFunction->checkCount($dataTransfer)){
            $dataTransferDetail = $dataTransfer->transfersDetailInfo();
            return view('work.money.transfer.transfer-info', compact('modelStaff', 'dataAccess', 'modelStaff', 'dataTransfer', 'dataTransferDetail'));
        }else{
            return redirect()->back();
        }
    }

    # huy 1 giao dich
    public function transferCancel($transferId)
    {
        $modelTransfer = new QcTransfers();
        return $modelTransfer->deleteTransfers($transferId);
    }

    #huy chi tiet chuyen thu 1 don hang
    public function transferDetailDelete($detailId)
    {
        $modelTransferDetail = new QcTransfersDetail();
        return $modelTransferDetail->deleteDetail($detailId);
    }

    #================================ nhan tien ========================
    public function receiveIndex($monthFilter = 0, $yearFilter = 0)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $dataAccess = [
            'object' => 'moneyTransferReceive',
            'subObjectLabel' => 'Nhận tiền'
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
            $dataTransfer = $dataStaffLogin->transferOfReceiveStaff($loginStaffId, $dateFilter);
            return view('work.money.transfer.receive', compact('dataAccess', 'modelStaff', 'dataTransfer', 'dateFilter', 'monthFilter', 'yearFilter'));
        } else {
            return view('work.login');
        }

    }

    public function receiveConfirm($transfersId)
    {
        $modelTransfer = new QcTransfers();
        $modelTransfer->updateConfirmReceive($transfersId, 'Xác nhận đã nhận tiền', 1);
    }

    public function receiveView($transferId)
    {
        $modelTransfer = new QcTransfers();
        $dataTransfer = $modelTransfer->getInfo($transferId);
        return view('work.money.transfer.receive-view', compact('dataAccess', 'modelStaff', 'dataTransfer'));
    }
}
