<?php

namespace App\Http\Controllers\Ad3d\Login;

use App\Models\Ad3d\Staff\QcStaff;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Input;
use File;
use DB;
use Request;

class LoginController extends Controller
{
    public function getLogin()
    {
       return view('ad3d.login.login');
    }

    public function postLogin()
    {
        $modelStaff = new QcStaff();
        $account = Request::input('txtAccount');
        $pass = Request::input('txtPass');
        if(empty($account) || empty($pass)){
            Session::put('notifyLogin', "Tài khoản hoặc mật khẩu không đúng.");
            return redirect()->back();
        }else{
            if ($modelStaff->login($account, $pass)) {
               return redirect()->route('qc.ad3d');
            } else {
               Session::put('notifyLogin', "Tài khoản hoặc mật khẩu không đúng.");
                return redirect()->back();
            }
        }

    }

    //exit
    public function getExit()
    {
        $modelStaff = new QcStaff();
        $modelStaff->logout();
        return redirect()->route('qc.ad3d.login.get');
    }
}
