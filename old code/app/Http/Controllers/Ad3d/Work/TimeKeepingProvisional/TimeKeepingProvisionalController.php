<?php

namespace App\Http\Controllers\Ad3d\Work\TimeKeepingProvisional;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\LicenseLateWork\QcLicenseLateWork;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\Timekeeping\QcTimekeeping;
use App\Models\Ad3d\TimekeepingProvisional\QcTimekeepingProvisional;
use App\Models\Ad3d\TimekeepingProvisionalImage\QcTimekeepingProvisionalImage;
use App\Models\Ad3d\Work\QcWork;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Faker\Provider\DateTime;
use Illuminate\Support\Facades\Session;
use Input;
use File;
use DB;
use Request;

class TimeKeepingProvisionalController extends Controller
{
    public function index($companyFilterId = null, $dayFilter = null, $monthFilter = null, $yearFilter = null, $nameFiler = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelWork = new QcWork();
        $modelTimekeepingProvisional = new QcTimekeepingProvisional();

        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataAccess = [
            'accessObject' => 'timeKeepingProvisional'
        ];
        if (empty($dayFilter) && empty($monthFilter) && empty($yearFilter)) {
            $dateFilter = null;// date('Y-m-d');
            //$dayFilter = date('d');
            //$monthFilter = date('m');
            //$yearFilter = date('Y');
        } elseif ($dayFilter == 0) { //xem tất cả các ngày trong tháng
            $dateFilter = null;// date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        } else {
            $dateFilter = null;// date('Y-m-d', strtotime("$dayFilter-$monthFilter-$yearFilter"));
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

        if (!empty($nameFiler)) {
            $listWorkId = $modelWork->listIdOfListCompanyStaffWork($modelCompanyStaffWork->listIdOfListCompanyAndListStaff($searchCompanyFilterId, $modelStaff->listStaffIdByName($nameFiler)));
        } else {

            $listWorkId = $modelWork->listIdOfListCompanyStaffWork($modelCompanyStaffWork->listIdOfListCompanyAndListStaff($searchCompanyFilterId));
        }
        $dataTimekeepingProvisional = $modelTimekeepingProvisional->selectInfoByListWorkAndDate($listWorkId, $dateFilter)->paginate(30);
        return view('ad3d.work.time-keeping-provisional.list', compact('modelStaff', 'dataCompany', 'dataAccess', 'dataTimekeepingProvisional', 'companyFilterId', 'dayFilter', 'monthFilter', 'yearFilter', 'nameFiler'));
    }

    public function indexOld($companyFilterId = null, $dayFilter = null, $monthFilter = null, $yearFilter = null, $nameFiler = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelWork = new QcWork();
        $modelTimekeepingProvisional = new QcTimekeepingProvisional();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataAccess = [
            'accessObject' => 'timeKeepingProvisional'
        ];
        if (empty($dayFilter) && empty($monthFilter) && empty($yearFilter)) {
            $dateFilter = null;// date('Y-m-d');
            //$dayFilter = date('d');
            //$monthFilter = date('m');
            //$yearFilter = date('Y');
        } elseif ($dayFilter == 0) { //xem tất cả các ngày trong tháng
            $dateFilter = null;// date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        } else {
            $dateFilter = null;// date('Y-m-d', strtotime("$dayFilter-$monthFilter-$yearFilter"));
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
        if (!empty($nameFiler)) {
            $listStaffId = $modelStaff->listIdOfListCompanyAndName($searchCompanyFilterId, $nameFiler);
        } else {
            $listStaffId = $modelStaff->listIdOfListCompany($searchCompanyFilterId);
        }
        $listWorkId = $modelWork->listIdOfListStaffId($listStaffId);
        $dataTimekeepingProvisional = $modelTimekeepingProvisional->selectInfoByListWorkAndDate($listWorkId, $dateFilter)->paginate(30);
        return view('ad3d.work.time-keeping-provisional.list-old', compact('modelStaff', 'dataCompany', 'dataAccess', 'dataTimekeepingProvisional', 'companyFilterId', 'dayFilter', 'monthFilter', 'yearFilter', 'nameFiler'));
    }

    # xem anh bao cao
    public function viewProvisionalImage($imageId)
    {
        $modelProvisionalImage = new QcTimekeepingProvisionalImage();
        $dataTimekeepingProvisionalImage = $modelProvisionalImage->getInfo($imageId);
        if (count($dataTimekeepingProvisionalImage) > 0) {
            return view('ad3d.work.time-keeping-provisional.view-provisional-image', compact('dataTimekeepingProvisionalImage'));
        }
    }

    public function getConfirm($timekeepingId = null)
    {
        $modelTimekeeping = new QcTimekeepingProvisional();
        $modelLicenseLateWork = new QcLicenseLateWork();
        $dataTimekeepingProvisional = $modelTimekeeping->getInfo($timekeepingId);
        if (count($dataTimekeepingProvisional) > 0) {
            return view('ad3d.work.time-keeping-provisional.confirm', compact('modelLicenseLateWork', 'dataTimekeepingProvisional'));
        }
    }

    public function postConfirm()
    {
        $modelStaff = new QcStaff();
        $modelTimekeepingProvisional = new QcTimekeepingProvisional();
        $staffLoginId = $modelStaff->loginStaffId();
        $timekeepingId = Request::input('txtTimekeeping');
        $permissionStatus = Request::input('txtPermissionLateStatus');
        $accuracyStatus = Request::input('txtAccuracyStatus');
        $confirmNote = Request::input('txtConfirmNote');
        $modelTimekeepingProvisional->confirmWork($timekeepingId, $staffLoginId, $accuracyStatus, $confirmNote, $permissionStatus);

    }

    // hủy
    public function cancelTimekeepingProvisional($timekeepingId)
    {
        $modelStaff = new QcStaff();
        $modelTimekeepingProvisional = new QcTimekeepingProvisional();
        $staffLoginId = $modelStaff->loginStaffId();
        $modelTimekeepingProvisional->cancelTimekeepingProvision($timekeepingId, $staffLoginId);
    }
}
