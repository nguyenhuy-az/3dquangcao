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
    public function index($loginDay = null, $loginMonth = null, $loginYear = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelDepartment = new QcDepartment();
        $modelCompany = new QcCompany();
        $hFunction->dateDefaultHCM();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        if (count($dataStaffLogin) >0) {
            $dataAccess = [
                'object' => 'moneyReceive',
                'subObjectLabel' => 'Thu đơn hàng'
            ];
            $currentMonth = $hFunction->currentMonth();
            $currentYear = $hFunction->currentYear();
            $loginMonth = (empty($loginMonth)) ? $currentMonth : $loginMonth;
            $loginYear = (empty($loginYear)) ? $currentYear : $loginYear;
            $dataStaffReceiveTransfer = $modelStaff->infoActivityOfCompany($dataStaffLogin->companyId(), $modelDepartment->treasurerDepartmentId());
            return view('work.money.receive.receive', compact('dataAccess', 'modelStaff', 'dataStaffReceiveTransfer', 'loginDay', 'loginMonth', 'loginYear'));
        } else {
            return view('work.login');
        }

    }

    #chuyen tien
    public function postTransfer()
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelTransfer = new QcTransfers();
        $modelTransferDetail = new QcTransfersDetail();
        $listOrderPay = Request::input('txtOrderPay');
        $txtTotalMoney = Request::input('txtTotalMoney');
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

        $txtTotalMoney = $hFunction->convertCurrencyToInt($txtTotalMoney);
        if ($modelTransfer->insert($txtTotalMoney, $hFunction->carbonNow(), $txtNote, $name_img, $modelStaff->loginStaffId(), $txtStaffReceiveId, $modelStaff->companyId($txtStaffReceiveId))) {
            $newTransferId = $modelTransfer->insertGetId();
            foreach ($listOrderPay as $key => $value) {
                $modelTransferDetail->insert($newTransferId, $value);
            }
            return redirect()->route('qc.work.money.transfer.transfer.get');
        } else {
            return redirect()->back();
        }
    }

}
