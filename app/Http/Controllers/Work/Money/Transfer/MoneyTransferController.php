<?php

namespace App\Http\Controllers\Work\Money\Transfer;

//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\Transfers\QcTransfers;
use File;
use Illuminate\Support\Facades\Session;
use Input;
use Request;

class MoneyTransferController extends Controller
{
    public function transferIndex($loginDay = null, $loginMonth = null, $loginYear = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $hFunction->dateDefaultHCM();
        if ($modelStaff->checkLogin()) {
            $dataAccess = [
                'object' => 'moneyTransfer',
                'subObjectLabel' => 'Giao tiền'
            ];
            $currentMonth = $hFunction->currentMonth();
            $currentYear = $hFunction->currentYear();
            $loginMonth = (empty($loginMonth)) ? $currentMonth : $loginMonth;
            $loginYear = (empty($loginYear)) ? $currentYear : $loginYear;
            return view('work.money.transfer.transfer', compact('dataAccess', 'modelStaff', 'loginDay', 'loginMonth', 'loginYear'));
        } else {
            return view('work.login');
        }

    }

    public function receiveIndex($loginDay = null, $loginMonth = null, $loginYear = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $hFunction->dateDefaultHCM();
        if ($modelStaff->checkLogin()) {
            $dataAccess = [
                'object' => 'moneyTransferReceive',
                'subObjectLabel' => 'Nhận tiền'
            ];
            $currentMonth = $hFunction->currentMonth();
            $currentYear = $hFunction->currentYear();
            $loginMonth = (empty($loginMonth)) ? $currentMonth : $loginMonth;
            $loginYear = (empty($loginYear)) ? $currentYear : $loginYear;
            return view('work.money.transfer.receive', compact('dataAccess', 'modelStaff', 'loginDay', 'loginMonth', 'loginYear'));
        } else {
            return view('work.login');
        }

    }

    public function receiveConfirm($transfersId)
    {
        $modelTransfer = new QcTransfers();
        $modelTransfer->updateConfirmReceive($transfersId);
    }
}
