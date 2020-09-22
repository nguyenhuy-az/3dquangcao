<?php

namespace App\Http\Controllers\Work\Recruitment\Recruitment;

//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ad3d\JobApplication\QcJobApplication;
use Illuminate\Support\Facades\Session;
use Input;
use File;
use DB;
use Request;


class RecruitmentController extends Controller
{
    public function index($jobApplicationId = null)
    {
        $hFunction = new \Hfunction();
        $modelJobApplication = new QcJobApplication();
        if (empty($jobApplicationId)) {
            $dataJobApplication = $modelJobApplication->loginJobApplicationInfo();
        } else {
            $dataJobApplication = $modelJobApplication->getInfo($jobApplicationId);
        }

        #co thong tin ho so
        if ($hFunction->checkCount($dataJobApplication)) {
            return view('work.recruitment.info.index', compact('dataJobApplication'));
        } else {
            return redirect()->route('qc.work.recruitment.login.get');
        }

    }

    # dang
    public function getLogin($companyId = null)
    {
        return view('work.recruitment.index', compact('companyId'));
    }

    # dang nhap
    public function postLogin($companyId)
    {
        $hFunction = new \Hfunction();
        $modelJobApplication = new QcJobApplication();
        $phoneNumber = Request::input('txtPhoneNumber');
        # lay thong tin ho so theo cong ty va so dien thoai
        $dataJobApplication = $modelJobApplication->infoByPhoneAndCompany($phoneNumber, $companyId);
        if (empty($phoneNumber)) {
            Session::put('notifyRecruitmentLogin', "Bạn phải nhập số điện thoại");
            return redirect()->back();
        } else {
            if ($hFunction->checkCount($dataJobApplication)) { // da ton tai ho so
                return view('work.recruitment.info.index', compact('dataJobApplication'));
            } else {
                return redirect()->route('qc.work.recruitment.register.add.get', "$companyId/$phoneNumber");
            }
        }
    }
}
