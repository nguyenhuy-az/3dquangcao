<?php

namespace App\Http\Controllers\Work\Staff;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\CompanyStaffWorkEnd\QcCompanyStaffWorkEnd;
use App\Models\Ad3d\Department\QcDepartment;
use App\Models\Ad3d\DepartmentWork\QcDepartmentWork;
use App\Models\Ad3d\Rank\QcRank;
use App\Models\Ad3d\Staff\QcStaff;
//use Illuminate\Http\Request;
use App\Models\Ad3d\StaffWorkDepartment\QcStaffWorkDepartment;
use App\Models\Ad3d\StaffWorkMethod\QcStaffWorkMethod;
use App\Models\Ad3d\StaffWorkSalary\QcStaffWorkSalary;
use App\Models\Ad3d\Statistical\QcStatistical;
use App\Models\Ad3d\ToolPackage\QcToolPackage;
use App\Models\Ad3d\Work\QcWork;
use App\Models\Ad3d\WorkSkill\QcWorkSkill;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Input;
use File;
use DB;
use Request;

class StaffController extends Controller
{
    # quan ly thong tin nhan vien
    public function index($staffId = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelRank = new QcRank();
        $dataAccess = [
            'object' => 'staff',
            'subObject' => 'basicInfo',
            'subObjectLabel' => 'Thông tin cơ bán'
        ];
        $dataCompany = $modelCompany->getInfo();
        $dataStaff = $modelStaff->getInfo($staffId);
        $dataRank = $modelRank->getInfo();
        if ($hFunction->checkCount($dataStaff)) {
            return view('work.staff.info.index', compact('modelStaff', 'modelRank', 'dataStaff', 'dataCompany', 'dataRank', 'dataAccess'));
        }
    }

    # cap nhat ky nang
    public function getUpdateSkill($companyStaffWorkId, $departmentWorkId)
    {
        $modelStaff = new QcStaff();
        $modelWorkSkill = new QcWorkSkill();
        $modelDepartmentWork = new QcDepartmentWork();
        $dataDepartmentWork = $modelDepartmentWork->getInfo($departmentWorkId);
        # lay ky nang hien tai neu co
        $dataWorkSkill = $modelWorkSkill->getInfoLastOfCompanyStaffWorkAndWork($companyStaffWorkId, $departmentWorkId);
        return view('work.staff.info.update-work-skill', compact('modelStaff', 'modelWorkSkill', 'dataWorkSkill', 'companyStaffWorkId', 'dataDepartmentWork'));
    }

    public function postUpdateSkill($companyStaffWorkId, $departmentWorkId)
    {
        $hFunction = new \Hfunction();
        $modelWorkSkill = new QcWorkSkill();
        $skillLevel = Request::input('chkSkillLevel');
        # lay thong tin đang hoat dong
        $dataWorkSkill = $modelWorkSkill->getInfoLastOfCompanyStaffWorkAndWork($companyStaffWorkId, $departmentWorkId);
        if ($hFunction->checkCount($dataWorkSkill)) { # ton tai 1 ky nang
            if ($skillLevel != $dataWorkSkill->level()) { # khong trung ky nang
                if (!$modelWorkSkill->insert($skillLevel, $departmentWorkId, $companyStaffWorkId)) {
                    return "Tính năng đang cập nhật";
                } else {
                    # vo hieu hoa cai cu
                    $dataWorkSkill->disableInfo();
                }
            }
        } else {
            # them lan dau
            if (!$modelWorkSkill->insert($skillLevel, $departmentWorkId, $companyStaffWorkId)) {
                return "Tính năng đang cập nhật";
            }
        }

    }

    //======== ======== THAY DOI MAT KHAU ========= ======
    public function getUpdateAccount()
    {
        $modelStaff = new QcStaff();
        return view('work.staff.info.update-account', compact('modelStaff'));
    }

    public function postUpdateAccount()
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
                return view('work.staff.info.update-account', compact('modelStaff', 'changeAccountNotify'));
            }
        } else {
            $changeAccountNotify = [
                'status' => false,
                'content' => 'Tài khoản không tồn tại'
            ];
            return view('work.staff.info.update-account', compact('modelStaff', 'changeAccountNotify'));
        }

    }
}
