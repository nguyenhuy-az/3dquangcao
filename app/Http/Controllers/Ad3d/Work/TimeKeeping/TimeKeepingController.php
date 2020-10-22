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

    public function getConfirm($timekeepingId = null)
    {
        $modelTimekeeping = new QcTimekeeping();
        $dataTimekeeping = $modelTimekeeping->getInfo($timekeepingId);
        if (count($dataTimekeeping) > 0) {
            return view('ad3d.work.time-keeping.confirm', compact('dataTimekeeping'));
        }
    }

    public function postConfirm($timekeepingId)
    {
        $hFunction = new \Hfunction();
        $modelTimekeeping = new QcTimekeeping();
        $dayEnd = Request::input('cbDayEnd');
        $monthEnd = Request::input('cbMonthEnd');
        $yearEnd = Request::input('cbYearEnd');
        $hoursEnd = Request::input('cbHoursEnd');
        $minuteEnd = Request::input('cbMinuteEnd');
        $afternoonStatus = Request::input('txtAfternoonStatus');
        $txtNote = Request::input('txtNote');

        $dataTimekeeping = $modelTimekeeping->getInfo($timekeepingId);
        $dateBegin = $dataTimekeeping->timeBegin();
        $dayBegin = date('d', strtotime($dateBegin));
        $monthBegin = date('m', strtotime($dateBegin));
        $yearBegin = date('Y', strtotime($dateBegin));
        $defaultEnd = $hFunction->convertStringToDatetime("$monthBegin/$dayBegin/$yearBegin 17:30:00");
        $dateEnd = $hFunction->convertStringToDatetime("$monthEnd/$dayEnd/$yearEnd $hoursEnd:$minuteEnd:00");
        if ($dateBegin <= $dateEnd) {
            $plusMinute = 0;
            $diffLate = abs(strtotime($dateEnd) - strtotime($dateBegin));
            $totalWorkMinute = $diffLate / 60; //số phút làm việc
            if ($hFunction->checkDateIsSunday(date('Y-m-d', strtotime($dateBegin)))) {
                //chủ nhật tính tăng ca
                $maniMinute = 0;
                if (360 < $totalWorkMinute) { // làm hơn 6 tiếng từ sáng
                    $plusMinute = ($totalWorkMinute - 90); //(trừ 1h30 nghĩ trưa)
                } else {
                    $plusMinute = $totalWorkMinute;
                }

            } else {
                if ($defaultEnd < $dateEnd) { // tăng ca
                    $diffMain = abs(strtotime($defaultEnd) - strtotime($dateBegin));
                    $diffPlus = abs(strtotime($dateEnd) - strtotime($defaultEnd));
                    $plusMinute = $diffPlus / 60; //số phút tăng ca
                } else {
                    $diffMain = abs(strtotime($dateEnd) - strtotime($dateBegin));
                }
                $totalMainWorkMinute = $diffMain / 60; //giờ làm chính
                if ($totalMainWorkMinute > 330) {
                    $maniMinute = $totalMainWorkMinute - 90; // làm giờ hành chính trừ 1h3 nghĩ trưa
                } else {
                    $maniMinute = $totalMainWorkMinute;
                }

            }
            if ($afternoonStatus) {
                $plusMinute = $plusMinute + 60; // tăng ca trua
                $afternoonStatus = 1;
            } else {
                $afternoonStatus = 0;
            }
            $modelTimekeeping->confirmWork($timekeepingId, $dateEnd, $maniMinute, $plusMinute, $txtNote, $afternoonStatus);
        } else {
            return "Giờ ra phải lớn hơn giờ vào";
        }


    }

    public function getAdd($companyLoginId = null, $workId = null)
    {
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelWork = new QcWork();
        $dataAccess = [
            'accessObject' => 'timeKeeping'
        ];
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataCompany = $modelCompany->getInfo();
        if (empty($companyLoginId)) {
            $companyLoginId = $dataStaffLogin->companyId();
        }
        $dataWork = $modelWork->infoActivityOfListStaff($modelStaff->listIdOfCompany($companyLoginId));
        $dataWorkSelect = (empty($workId)) ? null : $modelWork->getInfo($workId);
        return view('ad3d.work.time-keeping.add', compact('modelStaff', 'dataAccess', 'dataCompany', 'companyLoginId', 'dataWork', 'dataWorkSelect'));
    }

    public function postAdd()
    {
        $hFunction = new \Hfunction();
        $modelPunishContent = new QcPunishContent();
        $modelMinusMoney = new QcMinusMoney();
        $modelTimekeeping = new QcTimekeeping();
        $modelTimekeepingProvisional = new QcTimekeepingProvisional();
        $modelLicenseOffWork = new QcLicenseOffWork();
        $modelStaff = new QcStaff();
        $staffLoginId = $modelStaff->loginStaffId();
        $workId = Request::input('cbWork');

        $dayBegin = Request::input('cbDayBegin');
        $monthBegin = Request::input('cbMonthBegin');
        $yearBegin = Request::input('cbYearBegin');
        $hoursBegin = Request::input('cbHoursBegin');
        $minuteBegin = Request::input('cbMinuteBegin');
        $permissionStatus = Request::input('cbPermissionStatus');
        $note = Request::input('txtNote');

        $mainMinute = 0;
        $plusMinute = 0;
        $minusMinute = 0;
        $lateStatus = 1; // mặc đinh đúng giờ
        $defaultBegin = $hFunction->convertStringToDatetime("$monthBegin/$dayBegin/$yearBegin 08:00:00");
        //$defaultEnd = $hFunction->convertStringToDatetime("$monthBegin/$dayBegin/$yearBegin 17:30:00");
        //$minuteDefault = 480;

        $timeBegin = $hFunction->convertStringToDatetime("$monthBegin/$dayBegin/$yearBegin $hoursBegin:$minuteBegin:00");
        if (!$hFunction->checkDateIsSunday(date('Y-m-d', strtotime($timeBegin)))) {
            if ($permissionStatus == 0 && $defaultBegin < $timeBegin) { // tính trừ thời gian trễ
                // phạt khi đi làm trễ
                $punishIdOfLateWork = $modelPunishContent->punishIdOfLateWork();
                if (!empty($punishIdOfLateWork)) {
                    $reason = $modelPunishContent->note($punishIdOfLateWork);
                    $modelMinusMoney->insert(date('Y-m-d 00:00:00', strtotime($timeBegin)), $reason[0], $workId, $staffLoginId, $punishIdOfLateWork[0]);
                }
                $lateStatus = 0;
            }
        }

        if ($modelTimekeeping->existDateOfWork($workId, "$monthBegin/$dayBegin/$yearBegin")) {
            return Session::put('notifyAdd', 'Ngày này đã tồn tại, chon ngày khác');
        } elseif ($modelTimekeepingProvisional->existDateOfWork($workId, $defaultBegin)) {
            return Session::put('notifyAdd', 'Ngày này nhân viên đã chấm công');
        } elseif ($modelLicenseOffWork->existDateOfWork($workId, $defaultBegin)) {
            return Session::put('notifyAdd', 'Ngày này nhân viên đã xin nghỉ');
        } else {
            $monthBegin = (int)$monthBegin;
            $dayBegin = (int)$dayBegin;
            $monthBegin = ($monthBegin < 10) ? "0$monthBegin" : $monthBegin;
            $dayBegin = ($dayBegin < 10) ? "0$dayBegin" : $dayBegin;
            if ($hFunction->checkValidDate("$yearBegin-$monthBegin-$dayBegin")) {
                if (date('Y-m-d', strtotime($timeBegin)) > date('Y-m-d')) {
                    return Session::put('notifyAdd', "Giờ vào làm phải nhỏ hơn giờ hiện tại");
                } else {
                    if ($modelTimekeeping->insert($timeBegin, null, null, 0, $mainMinute, $plusMinute, $minusMinute, $note, $lateStatus, $permissionStatus, 1, $staffLoginId, $workId)) {
                        return Session::put('notifyAdd', 'Thêm thành công, chọn thông tin và tiếp tục');
                    }
                }
            } else {
                return Session::put('notifyAdd', "Ngày không hộp lệ $yearBegin-$monthBegin-$dayBegin");
            }
        }
    }

    public function getAddOff($companyLoginId = null, $workId = null)
    {
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelWork = new QcWork();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataCompany = $modelCompany->getInfo();
        if (empty($companyLoginId)) {
            $companyLoginId = $dataStaffLogin->companyId();
        }
        //die();
        $listIdCompanyStaffWork = $modelCompanyStaffWork->listIdOfListCompanyAndListStaff([$companyLoginId], null);

        $dataWork = $modelWork->infoActivityOfListCompanyStaffWork($listIdCompanyStaffWork);
        $dataWorkSelect = (empty($workId)) ? null : $modelWork->getInfo($workId);
        $dataAccess = [
            'accessObject' => 'timeKeeping'
        ];
        return view('ad3d.work.time-keeping.add-off', compact('modelStaff', 'dataAccess', 'dataCompany', 'companyLoginId', 'dataWork', 'dataWorkSelect'));
    }

    public function postAddOff()
    {
        $hFunction = new \Hfunction();
        $modelPunishContent = new QcPunishContent();
        $modelMinusMoney = new QcMinusMoney();
        $modelTimekeeping = new QcTimekeeping();
        $modelStaff = new QcStaff();
        $staffLoginId = $modelStaff->loginStaffId();
        $workId = Request::input('cbWork');

        $dayOff = Request::input('cbDayOff');
        $monthOff = Request::input('cbMonthOff');
        $yearOff = Request::input('cbYearOff');
        $permissionStatus = Request::input('cbPermissionStatus');
        $note = Request::input('txtNote');

        $mainMinute = 0;
        $plusMinute = 0;
        $minusMinute = 0;
        $lateStatus = 0;
        $workStatus = 0;
        $dateOff = $hFunction->convertStringToDatetime("$monthOff/$dayOff/$yearOff 00:00:00");
        if ($permissionStatus == 0) {
            // phạt nghĩ không phép
            $punishIdOfOffWork = $modelPunishContent->punishIdOfOffWork();
            if (!empty($punishIdOfOffWork)) {
                $reason = $modelPunishContent->note($punishIdOfOffWork);
                $modelMinusMoney->insert(date('Y-m-d 00:00:00', strtotime($dateOff)), $reason[0], $workId, $staffLoginId, $punishIdOfOffWork[0]);
            }
        }
        if ($modelTimekeeping->existDateOfWork($workId, "$monthOff/$dayOff/$yearOff")) {
            return Session::put('notifyAdd', 'Ngày này đã tồn tại, chon ngày khác');
        } else {
            if ($modelTimekeeping->insert(null, null, $dateOff, 0, $mainMinute, $plusMinute, $minusMinute, $note, $lateStatus, $permissionStatus, $workStatus, $staffLoginId, $workId)) {
                return Session::put('notifyAdd', 'Thêm thành công, chọn thông tin và tiếp tục');
            } else {
                return Session::put('notifyAdd', 'Thêm thất bại, hãy thử lại');
            }

        }
    }

    //delete
    public function deleteTimekeeping($timekeepingId)
    {
        $modelTimekeeping = new QcTimekeeping();
        $modelTimekeeping->deleteInfo($timekeepingId);
    }
}
