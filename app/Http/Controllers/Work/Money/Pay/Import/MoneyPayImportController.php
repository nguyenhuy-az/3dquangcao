<?php

namespace App\Http\Controllers\Work\Money\Pay\Import;

//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\Department\QcDepartment;
use App\Models\Ad3d\OrderPay\QcOrderPay;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\Transfers\QcTransfers;
use File;
use Illuminate\Support\Facades\Session;
use Input;
use Request;

class MoneyPayImportController extends Controller
{
    public function index($loginDay = null, $loginMonth = null, $loginYear = null, $loginPayStatus = 3)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $hFunction->dateDefaultHCM();
        if ($modelStaff->checkLogin()) {
            $dataAccess = [
                'object' => 'moneyPay',
                'subObjectLabel' => 'Mua vật tư'
            ];
            $currentMonth = $hFunction->currentMonth();
            $currentYear = $hFunction->currentYear();
            $loginMonth = (empty($loginMonth)) ? $currentMonth : $loginMonth;
            $loginYear = (empty($loginYear)) ? $currentYear : $loginYear;
            return view('work.money.pay.import.import', compact('dataAccess', 'modelStaff', 'loginDay', 'loginMonth', 'loginYear','loginPayStatus'));
        } else {
            return view('work.login');
        }

    }

}
