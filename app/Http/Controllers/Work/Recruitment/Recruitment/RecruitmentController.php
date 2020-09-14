<?php

namespace App\Http\Controllers\Work\Recruitment\Recruitment;

//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Input;
use File;
use DB;
use Request;


class RecruitmentController extends Controller
{
    public function index($phoneNumber = null)
    {
        $hFunction = new \Hfunction();
        $dataAccess = [
            'object' => null
        ];
        if (!empty($phoneNumber)) {

        } else {

        }
        return view('work.recruitment.info.index', compact('dataAccess'));
    }

    # dang
    public function getLogin()
    {
        return view('work.recruitment.index', compact('dataAccess'));
    }

    # dang nhap
    public function postLogin()
    {
        $phoneNumber = Request::input('txtPhoneNumber');
        if (empty($phoneNumber)) {
            Session::put('notifyRecruitmentLogin', "Bạn phải nhập số điện thoại");
            return redirect()->back();
        } else {
            $exist = false;
            if ($exist) {
                return view('work.recruitment.info.index', compact('dataAccess'));
            } else {
                return redirect()->route('qc.work.recruitment.register.add.get', $phoneNumber);
            }
        }
    }
}
