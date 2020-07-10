<?php

namespace App\Http\Controllers\Work\Timekeeping;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\Order\QcOrder;
use App\Models\Ad3d\OrderAllocation\QcOrderAllocation;

use App\Models\Ad3d\Rule\QcRules;
use App\Models\Ad3d\Staff\QcStaff;

//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use File;
use Input;
use Request;

class WorkController extends Controller
{
    //làm việc
    public function index($loginMonth = null, $loginYear = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $dataAccess = [
            'object' => 'timekeepingWork'
        ];
        if ($modelStaff->checkLogin()) {
            $dataStaff = $modelStaff->loginStaffInfo();
            $loginMonth = ($hFunction->checkNull($loginMonth)) ? date('m') : $loginMonth;
            $loginYear = ($hFunction->checkNull($loginYear)) ? date('Y') : $loginYear;
            if ($loginYear <= 2019 && $loginMonth < 8) { #du lieu cu
                return view('work.timekeeping.work.work-old', compact('dataAccess', 'modelStaff', 'dataStaff', 'loginMonth', 'loginYear'));
            } else { # du lieu moi
                return view('work.timekeeping.work.list', compact('dataAccess', 'modelStaff', 'modelCompanyStaffWork', 'dataStaff', 'loginMonth', 'loginYear'));
            }
        } else {
            return view('work.login');
        }
    }
}
