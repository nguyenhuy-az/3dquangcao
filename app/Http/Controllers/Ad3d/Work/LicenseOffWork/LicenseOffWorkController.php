<?php

namespace App\Http\Controllers\Ad3d\Work\LicenseOffWork;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\LicenseOffWork\QcLicenseOffWork;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\Work\QcWork;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Faker\Provider\DateTime;
use Illuminate\Support\Facades\Session;
use Input;
use File;
use DB;
use Request;

class LicenseOffWorkController extends Controller
{
    public function index($companyFilterId = null, $dayFilter = 0, $monthFilter = 0, $yearFilter = 0, $nameFiler = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelLicenseOffWork = new QcLicenseOffWork();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataAccess = [
            'accessObject' => 'offWork'
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
        $selectLicenseOffWork = $modelLicenseOffWork->selectInfoOfListStaffIdAndDate($modelCompany->staffIdOfListCompanyId($searchCompanyFilterId),$dateFilter);
        $dataLicenseOffWork = $selectLicenseOffWork->paginate(30);
        return view('ad3d.work.license-off-work.list', compact('modelStaff', 'dataCompany', 'dataAccess', 'dataLicenseOffWork', 'companyFilterId', 'dayFilter', 'monthFilter', 'yearFilter', 'nameFiler'));

    }

    public function getConfirm($licenseId = null)
    {
        $modelLicenseOffWork = new QcLicenseOffWork();
        $dataLicenseOffWork = $modelLicenseOffWork->getInfo($licenseId);
        if (count($dataLicenseOffWork) > 0) {
            return view('ad3d.work.license-off-work.confirm', compact('dataLicenseOffWork'));
        }
    }

    public function postConfirm()
    {
        $modelStaff = new QcStaff();
        $modelLicenseOffWork = new QcLicenseOffWork();
        $staffLoginId = $modelStaff->loginStaffId();
        $licenseIdId = Request::input('txtLicense');
        $agreeStatus = Request::input('txtAgreeStatus');
        $confirmNote = Request::input('txtConfirmNote');
        if (!$modelLicenseOffWork->confirmOffWork($licenseIdId, $agreeStatus, $confirmNote, $staffLoginId)) {
            return 'Hệ thống đang bảo trì';
        }
    }

}
