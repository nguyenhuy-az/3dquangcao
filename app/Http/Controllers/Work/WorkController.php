<?php

namespace App\Http\Controllers\Work;

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

class WorkController extends Controller
{
    public function index()
    {
        $modelStaff = new QcStaff();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelWork = new QcWork();
        $modelTimekeepingProvisional = new QcTimekeepingProvisional();

        $dataStaffLogin = $modelStaff->loginStaffInfo();
        if (count($dataStaffLogin) > 0) {
            $staffCompanyId = $dataStaffLogin->companyId();
            $dateFilter = date('Y-m-d');
            //$listStaffId = $modelCompanyStaffWork->listStaffIdActivityHasFilter($staffCompanyId);
            //$dataStaffActivity = $modelStaff->getInfoByListStaffId($listStaffId);
            $listWorkId = $modelWork->listIdOfListCompanyStaffWork($modelCompanyStaffWork->listIdOfListCompanyAndListStaff([$staffCompanyId]));
            $dataTimekeepingProvisional = $modelTimekeepingProvisional->selectInfoByListWorkAndDate($listWorkId, $dateFilter)->get();
            return view('work.control-panel', compact('modelStaff','dataTimekeepingProvisional'));
            //return redirect()->route('qc.work.timekeeping.get', compact('loginMonthYear'));
        } else {
            return view('work.login');
        }

    }

    //làm việc
    public function work($loginMonth = null, $loginYear = null)
    {
        $modelStaff = new QcStaff();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $dataAccess = [
            'object' => 'work'
        ];
        if ($modelStaff->checkLogin()) {
            $dataStaff = $modelStaff->loginStaffInfo();
            $loginMonth = (empty($loginMonth)) ? date('m') : $loginMonth;
            $loginYear = (empty($loginYear)) ? date('Y') : $loginYear;
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
        $modelStaff = new QcStaff();
        $account = Request::input('txtAccount');
        $pass = Request::input('txtPass');
        if (empty($account) || empty($pass)) {
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
        $modelStaff = new QcStaff();
        $modelRules = new QcRules();
        $dataAccess = [
            'object' => 'rules'
        ];
        if (!$modelStaff->checkLogin()) {
            return redirect()->route('qc.work.login.get');
        } else {
            $dataStaff = $modelStaff->loginStaffInfo();
            if (count($dataStaff) > 0) {
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
        $modelStaff = new QcStaff();
        $txtOldAccount = Request::input('txtOldAccount');
        $txtNewAccount = Request::input('txtNewAccount');
        $dataStaffLogin = $modelStaff->infoFromAccount($txtOldAccount);
        if (count($dataStaffLogin) > 0) {
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
