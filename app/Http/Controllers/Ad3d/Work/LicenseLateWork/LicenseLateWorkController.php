<?php

namespace App\Http\Controllers\Ad3d\Work\LicenseLateWork;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\LicenseLateWork\QcLicenseLateWork;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\Timekeeping\QcTimekeeping;
use App\Models\Ad3d\Work\QcWork;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Faker\Provider\DateTime;
use Illuminate\Support\Facades\Session;
use Input;
use File;
use DB;
use Request;

class LicenseLateWorkController extends Controller
{
    public function index($companyFilterId = null, $dayFilter = 0, $monthFilter = 0, $yearFilter = 0, $nameFiler = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelLicenseLateWork = new QcLicenseLateWork();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataAccess = [
            'accessObject' => 'lateWork'
        ];
        $currentMonth = $hFunction->currentMonth();
        $currentYear = $hFunction->currentYear();
        $dateFilter = null;
        if ($yearFilter == 100) { # lay tat ca thong tin
            $dayFilter = null;
            $dayFilter = 100;
            $monthFilter = 100;
        } elseif ($dayFilter == 0 && $monthFilter == 0 && $yearFilter == 0) { //xem  trong tháng
            $dayFilter = 100;
            $monthFilter = date('m');
            $yearFilter = date('Y');
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        } elseif ($dayFilter == 100 && $monthFilter == 100 && $yearFilter > 100) { //xem tất cả các ngày trong tháng
            $dateFilter = date('Y', strtotime("1-1-$yearFilter"));
        } elseif ($dayFilter == 100 && $monthFilter > 0 && $monthFilter < 100 && $yearFilter > 100) { //xem tất cả các ngày trong tháng
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        } elseif ($dayFilter < 100 && $dayFilter > 0 && $monthFilter > 0 && $monthFilter < 100 && $yearFilter > 100) { //xem tất cả các ngày trong tháng
            $monthFilter = $currentMonth;
            $yearFilter = $currentYear;
            $dateFilter = date('Y-m-d', strtotime("$dayFilter-$currentMonth-$currentYear"));
        } elseif ($dayFilter == 100 && $monthFilter == 100 && $yearFilter == 100) { //xem tất cả
            $dateFilter = null;
        } else {
            $dateFilter = date('Y-m');
            $dayFilter = 100;
            $monthFilter = date('m');
            $yearFilter = date('Y');
        }

        $dataCompany = $modelCompany->getInfo();
        if (empty($companyFilterId)) {
            if (!$dataStaffLogin->checkRootManage()) {
                $searchCompanyFilterId = [$dataStaffLogin->companyId()];
                $companyFilterId = $dataStaffLogin->companyId();
            } else {
                $searchCompanyFilterId = $modelCompany->listIdActivity();
            }
        } else {
            $searchCompanyFilterId = [$companyFilterId];
        }
        $selectLicenseOffWork = $modelLicenseLateWork->selectInfoOfListStaffIdAndDate($modelCompany->staffIdOfListCompanyId($searchCompanyFilterId), $dateFilter);
        $dataLicenseLateWork = $selectLicenseOffWork->paginate(30);

        return view('ad3d.work.license-late-work.list', compact('modelStaff', 'dataCompany', 'dataAccess', 'dataLicenseLateWork', 'companyFilterId', 'dayFilter', 'monthFilter', 'yearFilter', 'nameFiler'));

    }

    public function getConfirm($licenseId = null)
    {
        $modelLicenseLateWork = new QcLicenseLateWork();
        $dataLicenseLateWork = $modelLicenseLateWork->getInfo($licenseId);
        if (count($dataLicenseLateWork) > 0) {
            return view('ad3d.work.license-late-work.confirm', compact('dataLicenseLateWork'));
        }
    }

    public function postConfirm()
    {
        $modelStaff = new QcStaff();
        $modelLicenseLateWork = new QcLicenseLateWork();
        $staffLoginId = $modelStaff->loginStaffId();
        $licenseIdId = Request::input('txtLicense');
        $agreeStatus = Request::input('txtAgreeStatus');
        $confirmNote = Request::input('txtConfirmNote');
        if (!$modelLicenseLateWork->confirmLateWork($licenseIdId, $agreeStatus, $confirmNote, $staffLoginId)) {
            return 'Hệ thống đang bảo trì';
        }
    }

}
