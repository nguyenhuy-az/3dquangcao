<?php

namespace App\Http\Controllers\Work\Bonus;

use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\Work\QcWork;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use File;
use Input;
use Request;

class BonusController extends Controller
{    //phạt
    public function index($monthFilter = null, $yearFilter = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelWork = new QcWork();
        $dataStaff = $modelStaff->loginStaffInfo();
        $dataAccess = [
            'object' => 'bonus'
        ];
        if ($hFunction->checkCount($dataStaff)) {
            $dateFilter = null;
            if ($monthFilter == 0 && $yearFilter == 0) { //khong chon thoi gian xem
                $monthFilter = date('m');
                $yearFilter = date('Y');
                $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
            } elseif ($monthFilter == 100 && $yearFilter == null) { //xam tat ca cac thang va khong chon nam
                $yearFilter = date('Y');
                $dateFilter = date('Y', strtotime("1-1-$yearFilter"));
            } elseif ($monthFilter > 0 && $monthFilter < 100 && $yearFilter == 100) { //co chon thang va khong chon nam
                $monthFilter = 100;
                $dateFilter = null;
            } elseif ($monthFilter > 0 && $monthFilter < 100 && $yearFilter > 100) { //co chon thang va chon nam
                $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
            } elseif ($monthFilter == 100 && $yearFilter == 100) { //xem tất cả
                $dateFilter = null;
            }elseif ($monthFilter == 100 && $yearFilter > 100) { //xem tất cả
                $dateFilter = date('Y', strtotime("1-1-$yearFilter"));
            } else {
                $dateFilter = date('Y-m');
                $monthFilter = date('m');
                $yearFilter = date('Y');
            }
            $dataWork = $modelWork->firstInfoOfStaff($dataStaff->staffId(), $dateFilter);
            if($hFunction->checkCount($dataWork)){
                $dataBonus = $dataWork->infoBonusOfWork();
                $totalBonusMoney = $dataWork->totalMoneyBonus();
            }else{
                $dataBonus = null;
                $totalBonusMoney = 0;
            }
            return view('work.bonus-minus.bonus.list', compact('dataAccess', 'modelStaff', 'dataBonus','totalBonusMoney', 'monthFilter', 'yearFilter'));
        } else {
            return redirect()->route('qc.work.login.get');
        }
    }

}
