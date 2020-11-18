<?php

namespace App\Http\Controllers\Ad3d\Work\TimeKeeping;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\CompanyStaffWorkEnd\QcCompanyStaffWorkEnd;
use App\Models\Ad3d\LicenseOffWork\QcLicenseOffWork;
use App\Models\Ad3d\MinusMoney\QcMinusMoney;
use App\Models\Ad3d\PunishContent\QcPunishContent;
use App\Models\Ad3d\PunishType\QcPunishType;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\Timekeeping\QcTimekeeping;
use App\Models\Ad3d\TimekeepingImage\QcTimekeepingImage;
use App\Models\Ad3d\TimekeepingProvisional\QcTimekeepingProvisional;
use App\Models\Ad3d\Work\QcWork;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Faker\Provider\DateTime;
use Illuminate\Support\Facades\Session;
use Input;
use File;
use DB;
use Request;

class TimeKeepingController extends Controller
{
    public function index($companyFilterId = 0, $dayFilter = 0, $monthFilter = 0, $yearFilter = 0, $nameFiler = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelWork = new QcWork();
        $modelTimekeeping = new QcTimekeeping();
        $dataCompanyLogin = $modelStaff->companyLogin();
        $companyLoginId = $dataCompanyLogin->companyId();
        $companyFilterId = ($companyFilterId == 'null') ? null : $companyFilterId;
        if ($companyFilterId == 0) {
            $companyFilterId = $companyLoginId;
        }
        # lay thong tin cong ty cung he thong
        $dataCompany = $modelCompany->getInfoSameSystemOfCompany($companyLoginId);
        $dataAccess = [
            'accessObject' => 'timeKeeping'
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
        if ($monthFilter < 8 && $yearFilter <= 2019) { # du lieu phien ban cu
            if (!empty($nameFiler)) {
                $listStaffId = $modelStaff->listIdOfListCompanyAndName([$companyFilterId], $nameFiler);
            } else {
                $listStaffId = $modelStaff->listIdOfListCompany([$companyFilterId]);
            }
            $listWorkId = $modelWork->listIdOfListStaffId($listStaffId);
        } else {
            if (!empty($nameFiler)) {
                $listStaffId = $modelStaff->listStaffIdByName($nameFiler);
                $listCompanyStaffWorkId = $modelCompanyStaffWork->listIdOfListCompanyAndListStaff([$companyFilterId], $listStaffId);
            } else {
                $listCompanyStaffWorkId = $modelCompanyStaffWork->listIdOfListCompanyAndListStaff([$companyFilterId], null);
            }
            $listWorkId = $modelWork->listIdOfListCompanyStaffWork($listCompanyStaffWorkId);
        }
        $dataTimekeeping = $modelTimekeeping->selectInfoByListWorkAndDate($listWorkId, $dateFilter)->paginate(30);
        return view('ad3d.work.time-keeping.list', compact('modelStaff', 'dataCompany', 'dataAccess', 'dataTimekeeping', 'companyFilterId', 'dayFilter', 'monthFilter', 'yearFilter', 'nameFiler'));

    }

    public function viewImage($imageId)
    {
        $modelTimekeepingImage = new QcTimekeepingImage();
        $dataTimekeepingImage = $modelTimekeepingImage->getInfo($imageId);
        if (count($dataTimekeepingImage) > 0) {
            return view('ad3d.work.time-keeping.view-image', compact('dataTimekeepingImage'));
        }
    }

}
