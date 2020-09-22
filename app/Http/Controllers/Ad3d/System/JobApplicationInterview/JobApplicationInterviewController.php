<?php

namespace App\Http\Controllers\Ad3d\System\JobApplicationInterview;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\CompanyStaffWorkEnd\QcCompanyStaffWorkEnd;
use App\Models\Ad3d\Department\QcDepartment;
use App\Models\Ad3d\DepartmentStaff\QcDepartmentStaff;
use App\Models\Ad3d\JobApplication\QcJobApplication;
use App\Models\Ad3d\Rank\QcRank;
use App\Models\Ad3d\Staff\QcStaff;
//use Illuminate\Http\Request;
use App\Models\Ad3d\StaffSalaryBasic\QcStaffSalaryBasic;
use App\Models\Ad3d\StaffWorkDepartment\QcStaffWorkDepartment;
use App\Models\Ad3d\StaffWorkMethod\QcStaffWorkMethod;
use App\Models\Ad3d\StaffWorkSalary\QcStaffWorkSalary;
use App\Models\Ad3d\ToolPackage\QcToolPackage;
use App\Models\Ad3d\Work\QcWork;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Input;
use File;
use DB;
use Request;

class JobApplicationInterviewController extends Controller
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
            'subObject' => 'jobApplicationInterview'
        ];

        if ($companyFilterId == null || $companyFilterId == 0) {
            $companyFilterId = $dataStaffLogin->companyId();
        }
        $dataCompany = $modelCompany->getInfo();
        # danh sach ho so
        $selectJobApplication = $modelJobApplication->selectInfoByCompany($companyFilterId, $confirmStatusFilter);
        $dataJobApplication = $selectJobApplication->paginate(30);
        //return view('ad3d.system.recruitment.job-application.list', compact('modelStaff', 'dataJobApplication', 'dataCompany', 'dataAccess', 'companyFilterId', 'confirmStatusFilter'));

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
