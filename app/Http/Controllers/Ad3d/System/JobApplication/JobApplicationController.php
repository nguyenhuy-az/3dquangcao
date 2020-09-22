<?php

namespace App\Http\Controllers\Ad3d\System\JobApplication;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\Department\QcDepartment;
use App\Models\Ad3d\JobApplication\QcJobApplication;
use App\Models\Ad3d\Staff\QcStaff;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Input;
use File;
use DB;
use Request;

class JobApplicationController extends Controller
{
    public function index($companyFilterId = null, $confirmStatusFilter = 100)
    {
        $modelStaff = new QcStaff();
        $modelJobApplication = new QcJobApplication();
        $modelCompany = new QcCompany();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelDepartment = new QcDepartment();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $companyFilterId = ($companyFilterId == 'null') ? null : $companyFilterId;
        $dataAccess = [
            'accessObject' => 'recruitment',
            'subObject' => 'jobApplication'
        ];

        if ($companyFilterId == null || $companyFilterId == 0) {
            $companyFilterId = $dataStaffLogin->companyId();
        }
        $dataCompany = $modelCompany->getInfo();
        # danh sach ho so
        $selectJobApplication = $modelJobApplication->selectInfoByCompany($companyFilterId, $confirmStatusFilter);
        $dataJobApplication = $selectJobApplication->paginate(30);
        return view('ad3d.system.recruitment.job-application.list', compact('modelStaff', 'dataJobApplication', 'dataCompany', 'dataAccess', 'companyFilterId', 'confirmStatusFilter'));

    }

    # thong tin ho so
    public function getInfo($jobApplicationId)
    {
        $modelStaff = new QcStaff();
        $modelJobApplication = new QcJobApplication();
        $dataAccess = [
            'accessObject' => 'recruitment',
            'subObject' => 'jobApplication'
        ];
        $dataJobApplication = $modelJobApplication->getInfo($jobApplicationId);
        return view('ad3d.system.recruitment.job-application.info', compact('modelStaff', 'dataJobApplication', 'dataAccess'));
    }

    # xac nhan ho so
    public function postConfirm($jobApplicationId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelJobApplication = new QcJobApplication();
        $agreeStatus = Request::input('cbAgreeStatus');
        if ($agreeStatus == 1) { # dong y
            # lay ngay hen phong van
            $cbDay = Request::input('cbDay');
            $cbMonth = Request::input('cbMonth');
            $cbYear = Request::input('cbYear');
            $cbHours = Request::input('cbHours');
            $cbMinute = Request::input('cbMinute');
            $currentDate = date('Y-m-d H:j');
            $interviewDate = $hFunction->convertStringToDatetime("$cbMonth/$cbDay/$cbYear $cbHours:$cbMinute");
            if ($interviewDate < $currentDate) {
                Session::put('confirmJobApplicationNotify', 'Ngày hẹn phỏng vấn phải lớn hơn hàng hiện tại');
            } else {
                # xac nhan dong y
                if (!$modelJobApplication->confirmAgreeInterview($jobApplicationId, $modelStaff->loginStaffId(), $interviewDate)) {
                    Session::put('confirmJobApplicationNotify', 'Tính năng đang bảo trì, Hãy quay lại sau');
                }
            }
        } else {
            # xac nhan khong dong y
            if (!$modelJobApplication->confirmDisagree($jobApplicationId, $modelStaff->loginStaffId())) {
                Session::put('confirmJobApplicationNotify', 'Tính năng đang bảo trì, Hãy quay lại sau');
            }
        }

    }

    //xóa
    /*public function deleteStaff($staffId = null)
    {
        $modelStaff = new QcStaff();
        if (!empty($staffId)) {
            return $modelStaff->actionDelete($staffId);
        }
    }*/
}
