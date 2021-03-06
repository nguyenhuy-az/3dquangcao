<?php

namespace App\Http\Controllers\Work\Money\Receive;

//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\Department\QcDepartment;
use App\Models\Ad3d\OrderPay\QcOrderPay;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\Transfers\QcTransfers;
use App\Models\Ad3d\TransfersDetail\QcTransfersDetail;
use File;
use Illuminate\Support\Facades\Session;
use Input;
use Request;

class MoneyReceiveController extends Controller
{
    public function index($monthFilter = 0, $yearFilter = 0)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelDepartment = new QcDepartment();
        $modelCompany = new QcCompany();
        $hFunction->dateDefaultHCM();
        $dataAccess = [
            'object' => 'moneyReceive',
            'subObjectLabel' => 'Thu đơn hàng'
        ];
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        if ($hFunction->checkCount($dataStaffLogin)) {
            $loginStaffId = $dataStaffLogin->staffId();
            $dateFilter = null;
            if ($monthFilter == 0 && $yearFilter == 0) { //xem  trong tháng
                $monthFilter = 100;
                $yearFilter = date('Y');
                $dateFilter = date('Y', strtotime("1-1-$yearFilter"));
            } elseif ($monthFilter == 100 && $yearFilter > 100) { //xem tất cả các ngày trong tháng
                $dateFilter = date('Y', strtotime("1-1-$yearFilter"));
            } elseif ($monthFilter > 0 && $monthFilter < 100 && $yearFilter == 100) { //xem tất cả các ngày trong tháng
                $yearFilter = date('Y');
                $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
            } elseif ($monthFilter > 0 && $monthFilter < 100 && $yearFilter > 100) { //xem tất cả các ngày trong tháng
                $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
            } elseif ($monthFilter == 100 && $yearFilter == 100) { //xem tất cả
                $dateFilter = null;
            } else {
                $dateFilter = date('Y-m');
                $monthFilter = date('m');
                $yearFilter = date('Y');
            }
            # nhan tien tu don hang
            $dataOrderPay = $dataStaffLogin->orderPayNoTransferOfStaff($loginStaffId, $dateFilter);
            # danh sach NV nhan tien la bo phan thu quy
            $dataStaffReceiveTransfer = $modelStaff->infoActivityOfCompany($dataStaffLogin->companyId(), $modelDepartment->treasurerDepartmentId());
            return view('work.money.receive.receive', compact('dataAccess', 'modelStaff', 'dataOrderPay', 'dataStaffReceiveTransfer', 'dateFilter', 'dayFilter', 'monthFilter', 'yearFilter'));
        } else {
            return view('work.login');
        }

    }

    # huy thanh toán
    public function deleteOrderPay($orderPayId)
    {
        $modelOrderPay = new QcOrderPay();
        return $modelOrderPay->deleteOrderPay($orderPayId);
    }
    #chuyen tien
    public function postTransfer()
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelTransfer = new QcTransfers();
        $modelTransferDetail = new QcTransfersDetail();
        $listOrderPay = Request::input('txtOrderPay');
        $txtTotalMoney = Request::input('txtTransferMoney');
        $txtStaffReceiveId = Request::input('cbStaffReceive');
        $txtTransferImage = Request::file('txtTransferImage');
        $txtNote = Request::input('txtNote');

        if (count($txtTransferImage) > 0) {
            $name_img = stripslashes($_FILES['txtTransferImage']['name']);
            $name_img = $hFunction->getTimeCode() . '.' . $hFunction->getTypeImg($name_img);
            $source_img = $_FILES['txtTransferImage']['tmp_name'];
            if (!$modelTransfer->uploadImage($source_img, $name_img, 500)) $name_img = null;
        } else {
            $name_img = null;
        }

        $txtTotalMoney = $hFunction->convertCurrencyToInt($txtTotalMoney); # doi kieu tien te sang kieu int
        if ($modelTransfer->insert($txtTotalMoney, $hFunction->carbonNow(), $txtNote, $name_img, $modelStaff->loginStaffId(), $txtStaffReceiveId, $modelStaff->companyId($txtStaffReceiveId))) {
            $newTransferId = $modelTransfer->insertGetId();
            # them chi tiet chuyen tien
            foreach ($listOrderPay as $key => $value) {
                $modelTransferDetail->insert($newTransferId, $value);
            }
            return redirect()->route('qc.work.money.transfer.transfer.get');
        } else {
            return redirect()->back();
        }
    }

}
