<?php

namespace App\Http\Controllers\Ad3d\Work\TimeKeepingProvisional;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\LicenseLateWork\QcLicenseLateWork;
use App\Models\Ad3d\OverTimeRequest\QcOverTimeRequest;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\TimekeepingProvisional\QcTimekeepingProvisional;
use App\Models\Ad3d\TimekeepingProvisionalImage\QcTimekeepingProvisionalImage;
use App\Models\Ad3d\TimekeepingProvisionalWarning\QcTimekeepingProvisionalWarning;
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
        if (!$hFunction->checkEmpty($nameFiler)) {
            $listWorkId = $modelWork->listIdOfListCompanyStaffWork($modelCompanyStaffWork->listIdOfListCompanyAndListStaff([$companyFilterId], $modelStaff->listStaffIdByName($nameFiler)));
        } else {

            $listWorkId = $modelWork->listIdOfListCompanyStaffWork($modelCompanyStaffWork->listIdOfListCompanyAndListStaff([$companyFilterId]));
        }
        $dataTimekeepingProvisional = $modelTimekeepingProvisional->selectInfoUnconfirmedByListWorkAndDate($listWorkId, $dateFilter)->paginate(30);
        return view('ad3d.work.time-keeping-provisional.list', compact('modelStaff', 'dataCompany', 'dataAccess', 'dataTimekeepingProvisional', 'companyFilterId', 'nameFiler'));
    }


    # xem anh bao cao
    public function viewProvisionalImage($imageId)
    {
        $hFunction = new \Hfunction();
        $modelProvisionalImage = new QcTimekeepingProvisionalImage();
        $dataTimekeepingProvisionalImage = $modelProvisionalImage->getInfo($imageId);
        if ($hFunction->checkCount($dataTimekeepingProvisionalImage)) {
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
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelTimekeepingProvisional = new QcTimekeepingProvisional();
        $staffLoginId = $modelStaff->loginStaffId();
        $timekeepingId = Request::input('txtTimekeeping');
        $permissionLateStatus = Request::input('txtPermissionLateStatus');
        $accuracyStatus = Request::input('txtAccuracyStatus');
        $applyTimekeepingStatus = Request::input('txtApplyTimekeepingStatus');
        $applyRuleStatus = Request::input('txtApplyRuleStatus');
        $confirmNote = Request::input('txtConfirmNote');
        $permissionLateStatus = ($hFunction->checkEmpty($permissionLateStatus) ? $modelTimekeepingProvisional->getDefaultHasPermissionStatus() : $modelTimekeepingProvisional->getDefaultNotPermissionStatus()); // 1 - co phep; 0 - khong phep
        $accuracyStatus = ($hFunction->checkEmpty($accuracyStatus) ? $modelTimekeepingProvisional->getDefaultHasAccuracyStatus() : $modelTimekeepingProvisional->getDefaultNotAccuracyStatus()); // 1 - chinh xac; 0 - khong chinh xac
        $applyTimekeepingStatus = ($hFunction->checkEmpty($applyTimekeepingStatus) ? $modelTimekeepingProvisional->getDefaultHasTimekeeping() : $modelTimekeepingProvisional->getDefaultNotTimekeeping()); // 1 - ap dung tinh cong; 0 - khong tin cong
        $applyRuleStatus = ($hFunction->checkEmpty($applyRuleStatus) ? $modelTimekeepingProvisional->getDefaultNotApplyRule() : $modelTimekeepingProvisional->getDefaultHasApplyRule()); // 1 - a dung; 0 - khong ap dung
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

    #===== canh bao gio vao===== ===
    # lay form canh bao
    public function getWarningBegin($timekeepingId)
    {
        $hFunction = new \Hfunction();
        $modelTimekeeping = new QcTimekeepingProvisional();
        $dataTimekeepingProvisional = $modelTimekeeping->getInfo($timekeepingId);
        if ($hFunction->checkCount($dataTimekeepingProvisional)) {
            return view('ad3d.work.time-keeping-provisional.warning-time-begin', compact('dataTimekeepingProvisional'));
        }
    }
    # them canh bao
    public function postWarningBegin($timekeepingId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelWarning = new QcTimekeepingProvisionalWarning();
        $txtNote = Request::input('txtWarningNote');
        $txtWarningImage = Request::file('txtWarningImage');
        $name_img = $hFunction->getDefaultNull(); // mac dinh null
        if (!$hFunction->checkEmpty($txtWarningImage)) {
            $name_img = stripslashes($_FILES['txtWarningImage']['name']);
            $name_img = $hFunction->getTimeCode() . '.' . $hFunction->getTypeImg($name_img);
            $source_img = $_FILES['txtWarningImage']['tmp_name'];
            $modelWarning->uploadImage($source_img, $name_img);
        }
        $modelWarning->insert($txtNote, $name_img, $modelWarning->getDefaultWarningTypeTimeBegin(), $timekeepingId, $modelStaff->loginStaffId());
    }

    #===== canh bao gio ra =======
    # lay form canh bao
    public function getWarningEnd($timekeepingId)
    {
        $hFunction = new \Hfunction();
        $modelTimekeeping = new QcTimekeepingProvisional();
        $dataTimekeepingProvisional = $modelTimekeeping->getInfo($timekeepingId);
        if ($hFunction->checkCount($dataTimekeepingProvisional)) {
            return view('ad3d.work.time-keeping-provisional.warning-time-end', compact('dataTimekeepingProvisional'));
        }
    }

    # them canh bao ra
    public function postWarningEnd($timekeepingId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelWarning = new QcTimekeepingProvisionalWarning();
        $txtNote = Request::input('txtWarningNote');
        $txtWarningImage = Request::file('txtWarningImage');
        $name_img = $hFunction->getDefaultNull(); // mac dinh null
        if (!$hFunction->checkEmpty($txtWarningImage)) {
            $name_img = stripslashes($_FILES['txtWarningImage']['name']);
            $name_img = $hFunction->getTimeCode() . '.' . $hFunction->getTypeImg($name_img);
            $source_img = $_FILES['txtWarningImage']['tmp_name'];
            $modelWarning->uploadImage($source_img, $name_img);
        }
        $modelWarning->insert($txtNote, $name_img, $modelWarning->getDefaultWarningTypeTimeEnd(), $timekeepingId, $modelStaff->loginStaffId());
    }

    # huy canh bao cham cong
    public function cancelWarningTimekeeping($warningId)
    {
        $modelWarning = new QcTimekeepingProvisionalWarning();
        return $modelWarning->deleteWarning($warningId);
    }

    // huy yeu cau tang ca
    public function cancelOverTime($requestId)
    {
        $modelOverTimeRequest = new QcOverTimeRequest();
        return $modelOverTimeRequest->deleteInfo($requestId);
    }
}
