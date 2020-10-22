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
    public function index($companyFilterId = 0, $dayFilter = 0, $monthFilter = 0, $yearFilter = 0, $nameFiler = null)
    {
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelLicenseLateWork = new QcLicenseLateWork();
        $dataCompanyLogin = $modelStaff->companyLogin();
        $companyLoginId = $dataCompanyLogin->companyId();
        $companyFilterId = ($companyFilterId == 'null') ? null : $companyFilterId;
        if ($companyFilterId == 0) {
            $companyFilterId = $companyLoginId;
        }
        # lay thong tin cong ty cung he thong
        $dataCompany = $modelCompany->getInfoSameSystemOfCompany($companyLoginId);
        $dataAccess = [
            'accessObject' => 'lateWork'
        ];
        $dateFilter = null;
        if ($dayFilter == 0 && $monthFilter == 0 && $yearFilter == 0) { //xem  trong tháng
            $yearFilter = date('Y');
            $dateFilter = date('Y', strtotime("1-1-$yearFilter"));
        } elseif ($dayFilter == 0 && $monthFilter == 0 && $yearFilter > 100) { //xem tất cả các ngày trong tháng
            $dateFilter = date('Y', strtotime("1-1-$yearFilter"));
        } elseif ($dayFilter == 0 && $monthFilter > 0 && $yearFilter > 100) { //xem tất cả các ngày trong tháng
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        } elseif ($dayFilter > 0 && $monthFilter > 0 && $yearFilter > 100) { //xem tất cả các ngày trong tháng
            $dateFilter = date('Y-m-d', strtotime("$dayFilter-$monthFilter-$yearFilter"));
        } else {
            $dateFilter = date('Y-m');
            $dayFilter = 0;
            $monthFilter = date('m');
            $yearFilter = date('Y');
        }
        $selectLicenseOffWork = $modelLicenseLateWork->selectInfoOfListStaffIdAndDate($modelCompany->staffIdOfListCompanyId([$companyFilterId]), $dateFilter);
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
