<?php

namespace App\Http\Controllers\Work\Staff;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\CompanyStaffWorkEnd\QcCompanyStaffWorkEnd;
use App\Models\Ad3d\Department\QcDepartment;
use App\Models\Ad3d\Rank\QcRank;
use App\Models\Ad3d\Staff\QcStaff;
//use Illuminate\Http\Request;
use App\Models\Ad3d\StaffWorkDepartment\QcStaffWorkDepartment;
use App\Models\Ad3d\StaffWorkMethod\QcStaffWorkMethod;
use App\Models\Ad3d\StaffWorkSalary\QcStaffWorkSalary;
use App\Models\Ad3d\Statistical\QcStatistical;
use App\Models\Ad3d\ToolPackage\QcToolPackage;
use App\Models\Ad3d\Work\QcWork;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Input;
use File;
use DB;
use Request;

class StaffController extends Controller
{
    //quan ly thong tin nhan vien
    public function index($staffId = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $dataAccess = [
            'object' => 'staff',
            'subObject' => 'basicInfo',
            'subObjectLabel' => 'Thông tin cơ bán'
        ];
        $dataCompany = $modelCompany->getInfo();
        $dataDepartment = $modelDepartment->getInfo();
        $dataStaff = $modelStaff->getInfo($staffId);
        $dataRank = $modelRank->getInfo();
        if ($hFunction->checkCount($dataStaff)) {
            return view('work.staff.info.index', compact('modelStaff', 'modelRank', 'dataStaff', 'dataCompany', 'dataDepartment', 'dataRank','dataAccess'));
        }
    }
}
