<?php

namespace App\Http\Controllers\Work;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;

use App\Models\Ad3d\Rule\QcRules;
use App\Models\Ad3d\Staff\QcStaff;

//use Illuminate\Http\Request;
use App\Models\Ad3d\Work\QcWork;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use File;
use Input;
use Request;

class WorkController extends Controller
{
    public function index($sysInfoObject = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        if ($hFunction->checkCount($dataStaffLogin)) {
            return view('work.control-panel', compact('modelCompany', 'modelStaff', 'sysInfoObject'));
        } else {
            return view('work.login');
        }

    }

    public function notify($monthFilter = 0, $yearFilter = 0)
    {
        $modelStaff = new QcStaff();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $staffCompanyId = $dataStaffLogin->companyId();
        $sysInfoObject = 'home_system_notify';
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
        $dataStaffNotify = $dataStaffLogin->infoStaffNotifyOfStaff($dataStaffLogin->staffId(),$dateFilter);
        return view('work.news.notify.index', compact('modelCompany', 'modelStaff', 'sysInfoObject','dataStaffNotify','monthFilter','yearFilter'));
    }

    public function dateOff($yearFilter = 0){
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $sysInfoObject = 'home_systemDateOff';
        $dateFilter = null;
        if ($yearFilter == 0)  $yearFilter = date('Y');
        $dataSystemDateOff = $modelCompany->systemDateOfFOfCompanyAndDate($dataStaffLogin->companyId(), $yearFilter);
        return view('work.news.date-off.index', compact('modelCompany', 'modelStaff', 'sysInfoObject','dataSystemDateOff','yearFilter'));
    }

    //làm việc
    public function work($loginMonth = null, $loginYear = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $dataAccess = [
            'object' => 'work'
        ];
        if ($modelStaff->checkLogin()) {
            $dataStaff = $modelStaff->loginStaffInfo();
            $loginMonth = ($hFunction->checkNull($loginMonth)) ? date('m') : $loginMonth;
            $loginYear = ($hFunction->checkNull($loginYear)) ? date('Y') : $loginYear;
            if ($loginYear <= 2019 && $loginMonth < 8) { #du lieu cu
                return view('work.work.work-old', compact('dataAccess', 'modelStaff', 'dataStaff', 'loginMonth', 'loginYear'));
            } else { # du lieu moi
                return view('work.work.work', compact('dataAccess', 'modelStaff', 'modelCompanyStaffWork', 'dataStaff', 'loginMonth', 'loginYear'));
            }
        } else {
            return view('work.login');
        }
    }

    public function getLogin()
    {
        return view('work.login');
    }

    public function login()
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $account = Request::input('txtAccount');
        $pass = Request::input('txtPass');
        if ($hFunction->checkEmpty($account) || $hFunction->checkEmpty($pass)) {
            Session::put('notifyLogin', "Tài khoản hoặc mật khẩu không đúng.");
            return redirect()->back();
        } else {
            if ($modelStaff->login($account, $pass)) {
                return redirect()->route('qc.work.work_allocation.work_allocation.index');
                //return redirect()->route('qc.work.home');
            } else {
                Session::put('notifyLogin', "Tài khoản hoặc mật khẩu không đúng.");
                return redirect()->back();
            }
        }
    }

    //thoát
    public function logout()
    {
        $modelStaff = new QcStaff();
        if ($modelStaff->logout()) {
            return redirect()->route('qc.work.login.get');
        } else {
            return redirect()->back();
        }
    }

    //nội qui
    public function rules($loginCode = null)
    {
        $modelRules = new QcRules();
        $dataRule = $modelRules->getInfo();
        return view('work.rules.rules', compact('dataRule'));
    }

    //change account
    public function getChangeAccount()
    {
        return view('work.account.change-account');
    }

    public function postChangeAccount()
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $txtOldAccount = Request::input('txtOldAccount');
        $txtNewAccount = Request::input('txtNewAccount');
        $dataStaffLogin = $modelStaff->infoFromAccount($txtOldAccount);
        if ($hFunction->checkCount($dataStaffLogin)) {
            if ($txtOldAccount == $txtNewAccount) {
                return redirect()->route('qc.work.home');
            } else {
                if ($modelStaff->existAccount($txtNewAccount)) {
                    $changeAccountNotify = [
                        'status' => false,
                        'content' => 'Tài khoản này đã được sử dụng'
                    ];

                } else {
                    if ($modelStaff->updateAccount($dataStaffLogin->staffId(), $txtNewAccount)) {
                        $changeAccountNotify = [
                            'status' => true,
                            'content' => 'Đã được cập nhật'
                        ];
                    } else {
                        $changeAccountNotify = [
                            'status' => false,
                            'content' => 'Tính năng đang bảo trì'
                        ];
                    }

                }
                return view('work.account.change-account', compact('changeAccountNotify'));
            }
        } else {
            $changeAccountNotify = [
                'status' => false,
                'content' => 'Tài khoản không tồn tại'
            ];
            return view('work.account.change-account', compact('changeAccountNotify'));
        }

    }

}
