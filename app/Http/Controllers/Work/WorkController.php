<?php

namespace App\Http\Controllers\Work;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
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
    public function index($sysInfoObject=null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelOrderAllocation = new QcOrderAllocation();

        $dataStaffLogin = $modelStaff->loginStaffInfo();
        if ($hFunction->checkCount($dataStaffLogin)) {
            # kiem tra thong tin ban giao don hang
            $modelOrderAllocation->autoCheckMinusMoneyLateOrderAllocation();
            return view('work.control-panel', compact('modelCompany','modelStaff','sysInfoObject'));
        } else {
            return view('work.login');
        }

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
                return redirect()->route('qc.work.home');
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
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelRules = new QcRules();
        $dataAccess = [
            'object' => 'rules'
        ];
        if (!$modelStaff->checkLogin()) {
            return redirect()->route('qc.work.login.get');
        } else {
            $dataStaff = $modelStaff->loginStaffInfo();
            if ($hFunction->checkCount($dataStaff)) {
                $dataRule = $modelRules->getInfo();
                return view('work.rules.rules', compact('dataAccess', 'modelStaff', 'dataStaff', 'dataRule'));
            }
        }
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
