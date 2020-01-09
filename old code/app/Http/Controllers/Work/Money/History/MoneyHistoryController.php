<?php

namespace App\Http\Controllers\Work\Money\History;

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

class MoneyHistoryController extends Controller
{
    public function historyReceive($loginDay = null, $loginMonth = null, $loginYear = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelDepartment = new QcDepartment();
        $modelCompany = new QcCompany();
        $hFunction->dateDefaultHCM();
        if ($modelStaff->checkLogin()) {
            $dataAccess = [
                'object' => 'moneyHistory',
                'subObjectLabel' => 'Lịch sử thu ĐH'
            ];
            $currentMonth = $hFunction->currentMonth();
            $currentYear = $hFunction->currentYear();
            $loginMonth = (empty($loginMonth)) ? $currentMonth : $loginMonth;
            $loginYear = (empty($loginYear)) ? $currentYear : $loginYear;
            return view('work.money.history.receive', compact('dataAccess', 'modelStaff', 'loginDay', 'loginMonth', 'loginYear'));
        } else {
            return view('work.login');
        }

    }

}
