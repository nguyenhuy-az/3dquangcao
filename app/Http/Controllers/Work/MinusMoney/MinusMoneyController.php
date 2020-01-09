<?php

namespace App\Http\Controllers\Work\MinusMoney;

use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\ProductTypePrice\QcProductTypePrice;
use App\Models\Ad3d\Rule\QcRules;
use App\Models\Ad3d\Salary\QcSalary;
use App\Models\Ad3d\SalaryBeforePayRequest\QcSalaryBeforePayRequest;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\StaffWorkSalary\QcStaffWorkSalary;
use App\Models\Ad3d\TimekeepingProvisional\QcTimekeepingProvisional;
use App\Models\Ad3d\Work\QcWork;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use File;
use Input;
use Request;

class MinusMoneyController extends Controller
{    //phạt
    public function index($monthFilter = null, $yearFilter = null)
    {
        $modelStaff = new QcStaff();
        $modelWork = new QcWork();
        $dataStaff = $modelStaff->loginStaffInfo();
        $dataAccess = [
            'object' => 'minusMoney'
        ];
        if (count($dataStaff) > 0) {
            if ($monthFilter == null && $yearFilter == null) { //xem tất cả các ngày trong tháng
                $dateFilter = date('Y-m');
                $monthFilter = date('m');
                $yearFilter = date('Y');
            } else {
                $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
            }
            $dataWork = $modelWork->firstInfoOfStaff($dataStaff->staffId(), $dateFilter);
            return view('work.minus-money.minus-money', compact('dataAccess', 'modelStaff', 'dataWork', 'monthFilter', 'yearFilter'));
        } else {
            return redirect()->route('qc.work.login.get');
        }
    }

}
