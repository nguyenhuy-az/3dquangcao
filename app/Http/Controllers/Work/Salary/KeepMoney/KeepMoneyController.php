<?php

namespace App\Http\Controllers\Work\Salary\KeepMoney;

use App\Models\Ad3d\KeepMoney\QcKeepMoney;
use App\Models\Ad3d\Salary\QcSalary;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\StaffWorkSalary\QcStaffWorkSalary;
use App\Models\Ad3d\Work\QcWork;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use File;
use Input;
use Request;

class KeepMoneyController extends Controller
{
    //ứng lương
    public function index($monthFilter = 0, $yearFilter = 0, $payStatus = 0)
    {
        $modelStaff = new QcStaff();
        $modelSalary = new QcSalary();
        $modelKeepMoney = new QcKeepMoney();
        $modelWork = new QcWork();
        $modelStaffWorkSalary = new QcStaffWorkSalary();
        $dataStaff = $modelStaff->loginStaffInfo();
        $loginStaffId = $dataStaff->staffId();
        $dataAccess = [
            'object' => 'keepMoney',
            'subObjectLabel' => 'Giữ lương'
        ];
        $dateFilter = null;
        if ($monthFilter == 0 && $yearFilter == 0) { //khong chon thoi gian xem
            $monthFilter = 100;// date('m');
            $yearFilter = date('Y');
            $dateFilter = date('Y', strtotime("1-1-$yearFilter"));
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
        } elseif ($monthFilter == 100 && $yearFilter > 100) { //xem tất cả
            $dateFilter = date('Y', strtotime("1-1-$yearFilter"));
        } else {
            $dateFilter = date('Y-m');
            $monthFilter = date('m');
            $yearFilter = date('Y');
        }
        $listSalaryId = $modelSalary->listIdOfListWorkId($modelStaff->allListWorkId($loginStaffId));
        $dataKeepMoneySelect = $modelKeepMoney->selectInfoOfListSalary($listSalaryId, $dateFilter, $payStatus);
        $dataKeepMoney = $dataKeepMoneySelect->paginate(30);
        return view('work.salary.keep-money.list', compact('dataAccess', 'modelStaff', 'dataKeepMoney', 'monthFilter', 'yearFilter', 'payStatus'));
    }

}
