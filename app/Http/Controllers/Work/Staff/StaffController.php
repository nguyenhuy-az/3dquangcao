<?php

namespace App\Http\Controllers\Work\Staff;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\DepartmentWork\QcDepartmentWork;
use App\Models\Ad3d\Rank\QcRank;
use App\Models\Ad3d\Staff\QcStaff;
//use Illuminate\Http\Request;
use App\Models\Ad3d\Statistical\QcStatistical;
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

    //======== ======== THAY DOI TAI KHOAN ========= ======
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

    //======== ========= THONG KE ========= =========
    # thong tin thong ke
    public function getStatistical($monthFilter = null, $yearFilter = null)
    {
        $hFunction = new \Hfunction();
        $modelCompany = new QcCompany();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelStaff = new QcStaff();
        $modelStatistical = new QcStatistical();
        $staffLoginId = $modelStaff->loginStaffId();
        $dataCompanyLogin = $modelStaff->companyLogin();
        # lay gia tri mac dinh
        $allMonthFilter = $modelCompany->getDefaultValueAllMonth();
        $allYearFilter = $modelCompany->getDefaultValueAllYear();
        $dataCompanyStaffWork = $modelCompanyStaffWork->getLastInfoOfStaffInCompany($dataCompanyLogin->companyId(), $staffLoginId);
        $dataAccess = [
            'object' => 'staff',
            'subObject' => 'statisticInfo',
            'subObjectLabel' => 'Thống kê'
        ];
        if ($hFunction->checkEmpty($monthFilter) & $hFunction->checkEmpty($yearFilter)) { #mac dinh
            $dateFilter = date('Y-m');
            $monthFilter = date('m');
            $yearFilter = date('Y');
        } elseif ($monthFilter == $allMonthFilter && $yearFilter == $allYearFilter) { //xem  trong tháng
            $dateFilter = $hFunction->getDefaultNull();
        } elseif ($monthFilter == $allMonthFilter && $yearFilter != $allYearFilter) { //xem tất cả các ngày trong tháng
            $dateFilter = date('Y', strtotime("1-1-$yearFilter"));
        } elseif ($monthFilter != $allMonthFilter && $yearFilter == $allYearFilter) { //xem tất cả các ngày trong nam
            $yearFilter = date('Y');
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        } elseif ($monthFilter != $allMonthFilter && $yearFilter != $allYearFilter) { //xem tất cả các ngày trong tháng
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        } else {
            $dateFilter = date('Y-m');
            $monthFilter = date('m');
            $yearFilter = date('Y');
        }
        return view('work.staff.statistic.statistic', compact('modelCompany', 'modelStatistical', 'modelStaff', 'dataCompanyStaffWork', 'dataAccess', 'monthFilter', 'yearFilter', 'dateFilter'));
    }

    # chi tiet thong ke thong tin lam viec
    public function getStatisticalWork($workId)
    {
        $modelCompany = new QcCompany();
        $modelWork = new QcWork();
        $modelStaff = new QcStaff();
        $modelStatistical = new QcStatistical();
        $dataAccess = [
            'object' => 'staff',
            'subObject' => 'statisticInfo',
            'subObjectLabel' => 'Thống kê'
        ];
        $dataWork = $modelWork->getInfo($workId);
        return view('work.staff.statistic.statistic-work', compact('modelCompany', 'modelStatistical', 'modelStaff', 'dataAccess', 'dataWork'));
    }

    # thong tin thuong
    public function getStatisticalBonus($workId)
    {
        $modelCompany = new QcCompany();
        $modelWork = new QcWork();
        $modelStaff = new QcStaff();
        $modelStatistical = new QcStatistical();
        $dataAccess = [
            'object' => 'staff',
            'subObject' => 'statisticInfo',
            'subObjectLabel' => 'Thống kê'
        ];
        $dataWork = $modelWork->getInfo($workId);
        return view('work.staff.statistic.statistic-bonus', compact('modelCompany', 'modelStatistical', 'modelStaff', 'dataAccess', 'dataWork'));
    }

    # thong tin phat
    public function getStatisticalMinus($workId)
    {
        $modelCompany = new QcCompany();
        $modelWork = new QcWork();
        $modelStaff = new QcStaff();
        $modelStatistical = new QcStatistical();
        $dataAccess = [
            'object' => 'staff',
            'subObject' => 'statisticInfo',
            'subObjectLabel' => 'Thống kê'
        ];
        $dataWork = $modelWork->getInfo($workId);
        return view('work.staff.statistic.statistic-minus', compact('modelCompany', 'modelStatistical', 'modelStaff', 'dataAccess', 'dataWork'));
    }

    # chi tiet thong ke bo phan thi cong
    public function getStatisticalConstruction($workId, $constructionStatus = null)
    {
        $modelCompany = new QcCompany();
        $modelWork = new QcWork();
        $modelStaff = new QcStaff();
        $modelStatistical = new QcStatistical();
        $dataAccess = [
            'object' => 'staff',
            'subObject' => 'statisticInfo',
            'subObjectLabel' => 'Thống kê'
        ];
        $dataWork = $modelWork->getInfo($workId);
        return view('work.staff.statistic.statistic-construction', compact('modelCompany', 'modelStatistical', 'modelStaff', 'dataAccess', 'dataWork', 'constructionStatus'));
    }

    # thong ke bo phan kinh doanh
    public function getStatisticalBusiness($workId, $orderStatus = null)
    {
        $modelCompany = new QcCompany();
        $modelWork = new QcWork();
        $modelStaff = new QcStaff();
        $modelStatistical = new QcStatistical();
        $dataAccess = [
            'object' => 'staff',
            'subObject' => 'statisticInfo',
            'subObjectLabel' => 'Thống kê'
        ];
        $dataWork = $modelWork->getInfo($workId);
        return view('work.staff.statistic.statistic-business', compact('modelCompany', 'modelStatistical', 'modelStaff', 'dataAccess', 'dataWork', 'orderStatus'));
    }
}
