<?php

namespace App\Http\Controllers\Ad3d\Work\TimeKeepingProvisional;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\LicenseLateWork\QcLicenseLateWork;
use App\Models\Ad3d\OverTimeRequest\QcOverTimeRequest;
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
    public function index($companyFilterId = null, $nameFiler = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelWork = new QcWork();
        $modelTimekeepingProvisional = new QcTimekeepingProvisional();
        $dataCompanyLogin = $modelStaff->companyLogin();
        $companyLoginId = $dataCompanyLogin->companyId();
        $companyFilterId = ($companyFilterId == 'null') ? null : $companyFilterId;
        if ($companyFilterId == 0) {
            $companyFilterId = $companyLoginId;
        }
        # lay thong tin cong ty cung he thong
        $dataCompany = $modelCompany->getInfoSameSystemOfCompany($companyLoginId);
        $dataAccess = [
            'accessObject' => 'timeKeepingProvisional'
        ];
        # lay trong thang hien tai - THANG CU SE DUYET TU DONG
        $dateFilter = $hFunction->currentMonthYear();
        if (!empty($nameFiler)) {
            $listWorkId = $modelWork->listIdOfListCompanyStaffWork($modelCompanyStaffWork->listIdOfListCompanyAndListStaff([$companyFilterId], $modelStaff->listStaffIdByName($nameFiler)));
        } else {

            $listWorkId = $modelWork->listIdOfListCompanyStaffWork($modelCompanyStaffWork->listIdOfListCompanyAndListStaff([$companyFilterId]));
        }
        $dataTimekeepingProvisional = $modelTimekeepingProvisional->selectInfoUnconfirmedByListWorkAndDate($listWorkId, $dateFilter)->paginate(30);
        return view('ad3d.work.time-keeping-provisional.list', compact('modelStaff', 'dataCompany', 'dataAccess', 'dataTimekeepingProvisional', 'companyFilterId', 'nameFiler'));
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
        $hFunction = new \Hfunction();
        $modelTimekeeping = new QcTimekeepingProvisional();
        $modelLicenseLateWork = new QcLicenseLateWork();
        $dataTimekeepingProvisional = $modelTimekeeping->getInfo($timekeepingId);
        if ($hFunction->checkCount($dataTimekeepingProvisional)) {
            return view('ad3d.work.time-keeping-provisional.confirm', compact('modelLicenseLateWork', 'dataTimekeepingProvisional'));
        }
    }

    public function postConfirm()
    {
        $modelStaff = new QcStaff();
        $modelTimekeepingProvisional = new QcTimekeepingProvisional();
        $staffLoginId = $modelStaff->loginStaffId();
        $timekeepingId = Request::input('txtTimekeeping');
        $permissionLateStatus = Request::input('txtPermissionLateStatus');
        $accuracyStatus = Request::input('txtAccuracyStatus');
        $applyTimekeepingStatus = Request::input('txtApplyTimekeepingStatus');
        $applyRuleStatus = Request::input('txtApplyRuleStatus');
        $confirmNote = Request::input('txtConfirmNote');
        $permissionLateStatus = (empty($permissionLateStatus) ? 1 : 0); // 1 - co phep; 0 - khong phep
        $accuracyStatus = (empty($accuracyStatus) ? 1 : 0); // 1 - chinh xac; 0 - khong chinh xac
        $applyTimekeepingStatus = (empty($applyTimekeepingStatus) ? 1 : 0); // 1 - ap dung tinh cong; 0 - khong tin cong
        $applyRuleStatus = (empty($applyRuleStatus) ? 0 : 1); // 1 - a dung; 0 - khong ap dung
        $modelTimekeepingProvisional->confirmWork($timekeepingId, $staffLoginId, $confirmNote, $permissionLateStatus, $accuracyStatus, $applyTimekeepingStatus, $applyRuleStatus);

    }

    #------- yeu cau tang ca --------
    public function getOverTime($companyStaffWorkId)
    {
        $hFunction = new \Hfunction();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $dataCompanyStaffWork = $modelCompanyStaffWork->getInfo($companyStaffWorkId);
        if ($hFunction->checkCount($dataCompanyStaffWork)) {
            return view('ad3d.work.time-keeping-provisional.over-time', compact('dataCompanyStaffWork'));
        }
    }

    public function postOverTime($companyStaffWorkId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelOverTimeRequest = new QcOverTimeRequest();
        $txtNote = Request::input('txtNote');
        if (!$modelOverTimeRequest->insert($hFunction->carbonNow(), $txtNote, $companyStaffWorkId, $modelStaff->loginStaffId())) {
            return "Tính năng đang bảo trì";
        }
    }

    // huy yeu cau tang ca
    public function cancelOverTime($requestId)
    {
        $modelOverTimeRequest = new QcOverTimeRequest();
        return $modelOverTimeRequest->deleteInfo($requestId);
    }

    // huy cham cong
    public function cancelTimekeepingProvisional($timekeepingId)
    {
        $modelStaff = new QcStaff();
        $modelTimekeepingProvisional = new QcTimekeepingProvisional();
        $staffLoginId = $modelStaff->loginStaffId();
        $modelTimekeepingProvisional->cancelTimekeepingProvision($timekeepingId, $staffLoginId);
    }
}
