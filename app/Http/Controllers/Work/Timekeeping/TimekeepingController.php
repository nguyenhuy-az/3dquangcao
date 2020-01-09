<?php

namespace App\Http\Controllers\Work\Timekeeping;

use App\Models\Ad3d\LicenseLateWork\QcLicenseLateWork;
use App\Models\Ad3d\LicenseOffWork\QcLicenseOffWork;
use App\Models\Ad3d\Rule\QcRules;
use App\Models\Ad3d\Salary\QcSalary;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\TimekeepingProvisional\QcTimekeepingProvisional;
use App\Models\Ad3d\TimekeepingProvisionalImage\QcTimekeepingProvisionalImage;
use App\Models\Ad3d\Work\QcWork;
//use Illuminate\Http\Request;
use App\Models\Ad3d\WorkAllocationReport\QcWorkAllocationReport;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use File;
use Input;
use Request;

class TimekeepingController extends Controller
{
    public function index()
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelWork = new QcWork();
        if ($modelStaff->checkLogin()) {
            $dataAccess = [
                'object' => 'timekeeping'
            ];
            $dataStaff = $modelStaff->loginStaffInfo();
            $modelWork->checkAutoTimekeepingOfActivityWork();
            $dateFilter = date('Y-m-d');
            return view('work.timekeeping.timekeeping', compact('dataAccess', 'modelStaff', 'dataStaff', 'dateFilter'));
        } else {
            return view('work.login');
        }

    }

    //xem anh bao cao
    public function viewTimekeepingProvisionalImage($imageId)
    {
        $modelTimekeepingProvisionalImage = new QcTimekeepingProvisionalImage();
        $dataTimekeepingProvisionalImage = $modelTimekeepingProvisionalImage->getInfo($imageId);
        return view('work.timekeeping.view-provisional-image', compact('dataTimekeepingProvisionalImage'));

    }

    // báo giờ làm
    public function getTimeBegin($workId)
    {
        $modelStaff = new QcStaff();
        $modelWork = new QcWork();
        if ($modelStaff->checkLogin()) {
            $dataStaff = $modelStaff->loginStaffInfo();
            $dataWork = $modelWork->getInfo($workId);
            return view('work.timekeeping.time-begin', compact('modelStaff', 'dataStaff', 'dataWork'));
        }
    }

    public function postTimeBegin()
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelTimekeeping = new QcTimekeepingProvisional();
        $modelLicenseOff = new QcLicenseOffWork();
        $workId = Request::input('txtWork');

        $dayBegin = Request::input('cbDayBegin');
        $monthBegin = Request::input('cbMonthBegin');
        $yearBegin = Request::input('cbYearBegin');
        $hoursBegin = Request::input('cbHoursBegin');
        $minuteBegin = Request::input('cbMinuteBegin');
        $note = Request::input('txtNote');
        $timeBegin = $hFunction->convertStringToDatetime("$monthBegin/$dayBegin/$yearBegin $hoursBegin:$minuteBegin:00");
        if ($modelStaff->checkLogin()) {
            $monthBegin = (int)$monthBegin;
            $dayBegin = (int)$dayBegin;
            $monthBegin = ($monthBegin < 10) ? "0$monthBegin" : $monthBegin;
            $dayBegin = ($dayBegin < 10) ? "0$dayBegin" : $dayBegin;
            if ($hFunction->checkValidDate("$yearBegin-$monthBegin-$dayBegin")) {
                if ($modelLicenseOff->existDateOfWork($workId, "$monthBegin/$dayBegin/$yearBegin")) {
                    return 'Ngày này đã xin nghỉ, chon ngày khác';
                } else {
                    if (date('Y-m-d', strtotime($timeBegin)) > date('Y-m-d')) {
                        return 'Giờ vào làm phải nhỏ hơn giờ hiện tại';
                    } else {
                        if ($modelTimekeeping->existDateOfWork($workId, "$monthBegin/$dayBegin/$yearBegin")) {
                            return 'Ngày này đã chấm công, chon ngày khác';
                        } else {
                            if (!$modelTimekeeping->insert($timeBegin, null, $note, 0, $workId, null)) {
                                return "Hệ thống đang bảo trì";
                            }
                        }
                    }
                }

            } else {
                return "Tháng $monthBegin không có ngày $dayBegin";
            }
        }

    }

    //báo giờ ra
    public function getTimeEnd($timekeepingId)
    {
        $modelStaff = new QcStaff();
        $modelTimekeeping = new QcTimekeepingProvisional();
        if ($modelStaff->checkLogin()) {
            $dataStaff = $modelStaff->loginStaffInfo();
            $dataTimekeepingProvisional = $modelTimekeeping->getInfo($timekeepingId);
            return view('work.timekeeping.time-end', compact('modelStaff', 'dataStaff', 'dataTimekeepingProvisional'));

        }
    }

    public function postTimeEnd()
    {
        $hFunction = new \Hfunction();
        $modelWorkAllocationReport = new QcWorkAllocationReport();
        $modelTimekeepingProvisional = new QcTimekeepingProvisional();
        $modelTimekeepingProvisionalImage = new QcTimekeepingProvisionalImage();
        $timekeepingProvisionalId = Request::input('txtTimekeepingProvisional');

        $dayEnd = Request::input('cbDayEnd');
        $monthEnd = Request::input('cbMonthEnd');
        $yearEnd = Request::input('cbYearEnd');
        $hoursEnd = Request::input('cbHoursEnd');
        $minuteEnd = Request::input('cbMinuteEnd');
        //$txtTimekeepingImage = Request::file('txtTimekeepingImage');
        $txtTimekeepingImage_1 = Request::file('txtTimekeepingImage_1');
        $cbWorkAllocation_1 = Request::input('cbWorkAllocation_1');
        $txtTimekeepingImage_2 = Request::file('txtTimekeepingImage_2');
        $cbWorkAllocation_2 = Request::input('cbWorkAllocation_2');
        $txtTimekeepingImage_3 = Request::file('txtTimekeepingImage_3');
        $cbWorkAllocation_3 = Request::input('cbWorkAllocation_3');
        $note = Request::input('txtNote');
        $afternoonStatus = Request::input('txtAfternoonStatus');
        $afternoonStatus = ($afternoonStatus) ? 1 : 0;

        $timeBegin = $modelTimekeepingProvisional->timeBegin($timekeepingProvisionalId)[0];
        $timeEnd = $hFunction->convertStringToDatetime("$monthEnd/$dayEnd/$yearEnd $hoursEnd:$minuteEnd:00");
        if (date('Y-m-d H:i', strtotime($timeBegin)) > date('Y-m-d H:i', strtotime($timeEnd))) {
            return 'Giờ ra phải lớn hơn giờ vào';
        } else {
            # co anh bao cao
            if (empty($txtTimekeepingImage_1) && empty($txtTimekeepingImage_2) && empty($txtTimekeepingImage_3)) {
                return "Phải có ảnh báo cáo";
            } else {
                if ($modelTimekeepingProvisional->updateTimeEnd($timekeepingProvisionalId, $timeEnd, $afternoonStatus, $note)) {
                    $n_o = 0;
                    # anh bao cao 1
                    if (!empty($txtTimekeepingImage_1)) {
                        $newReportId_1 = null; # mặc định null
                        if ($cbWorkAllocation_1 > 0) { # anh bao cao thuoc cong trinh
                            # them bao cao
                            if ($modelWorkAllocationReport->insert($hFunction->carbonNow(), 'Báo cáo tiến độ', $cbWorkAllocation_1)) {
                                $newReportId_1 = $modelWorkAllocationReport->insertGetId();
                            }
                        }
                        $name_img = stripslashes($_FILES['txtTimekeepingImage_1']['name']);
                        $name_img = $hFunction->getTimeCode() . '.' . $hFunction->getTypeImg($name_img);
                        $source_img = $_FILES['txtTimekeepingImage_1']['tmp_name'];
                        if ($modelTimekeepingProvisionalImage->uploadImage($source_img, $name_img, 500)) {
                            if (!$modelTimekeepingProvisionalImage->insert($name_img, $timekeepingProvisionalId, $newReportId_1)) {
                                $modelWorkAllocationReport->deleteReport($newReportId_1); # huy bao cao
                            }
                        }
                    }
                    # anh bao cao 2
                    if (!empty($txtTimekeepingImage_2)) {
                        $newReportId_2 = null; # mặc định null
                        if ($cbWorkAllocation_2 > 0) { # anh bao cao thuoc cong trinh
                            # them bao cao
                            if ($modelWorkAllocationReport->insert($hFunction->carbonNow(), 'Báo cáo tiến độ', $cbWorkAllocation_2)) {
                                $newReportId_2 = $modelWorkAllocationReport->insertGetId();
                            }
                        }
                        $name_img = stripslashes($_FILES['txtTimekeepingImage_2']['name']);
                        $name_img = $hFunction->getTimeCode() . '.' . $hFunction->getTypeImg($name_img);
                        $source_img = $_FILES['txtTimekeepingImage_2']['tmp_name'];
                        if ($modelTimekeepingProvisionalImage->uploadImage($source_img, $name_img, 500)) {
                            if (!$modelTimekeepingProvisionalImage->insert($name_img, $timekeepingProvisionalId, $newReportId_2)) {
                                $modelWorkAllocationReport->deleteReport($newReportId_2); # huy bao cao
                            }
                        }
                    }

                    # anh bao cao 3
                    if (!empty($txtTimekeepingImage_3)) {
                        $newReportId_3 = null; # mặc định null
                        if ($cbWorkAllocation_3 > 0) { # anh bao cao thuoc cong trinh
                            # them bao cao
                            if ($modelWorkAllocationReport->insert($hFunction->carbonNow(), 'Báo cáo tiến độ', $cbWorkAllocation_3)) {
                                $newReportId_3 = $modelWorkAllocationReport->insertGetId();
                            }
                        }
                        $name_img = stripslashes($_FILES['txtTimekeepingImage_3']['name']);
                        $name_img = $hFunction->getTimeCode() . '.' . $hFunction->getTypeImg($name_img);
                        $source_img = $_FILES['txtTimekeepingImage_3']['tmp_name'];
                        if ($modelTimekeepingProvisionalImage->uploadImage($source_img, $name_img, 500)) {
                            if (!$modelTimekeepingProvisionalImage->insert($name_img, $timekeepingProvisionalId, $newReportId_3)) {
                                $modelWorkAllocationReport->deleteReport($newReportId_3); # huy bao cao
                            }
                        }
                    }
                    # dang duyet mang
                    /*foreach ($_FILES['txtTimekeepingImage']['name'] as $name => $value) {
                        $name_img = stripslashes($_FILES['txtTimekeepingImage']['name'][$name]);
                        if (!empty($name_img)) {
                            $n_o = $n_o + 1;
                            $name_img = $hFunction->getTimeCode() . "_$n_o." . $hFunction->getTypeImg($name_img);
                            $source_img = $_FILES['txtTimekeepingImage']['tmp_name'][$name];
                            if ($modelTimekeepingProvisionalImage->uploadImage($source_img, $name_img, 500)) {
                                $modelTimekeepingProvisionalImage->insert($name_img, $timekeepingProvisionalId);
                            }
                        }
                    }*/
                } else {
                    return "Hệ thống đang bảo trì";
                }

            }
        }
    }

    //su gio cham cong
    public function getEditTimeEnd($timekeepingProvisionalId)
    {
        $modelStaff = new QcStaff();
        $modelTimekeeping = new QcTimekeepingProvisional();
        if ($modelStaff->checkLogin()) {
            $dataStaff = $modelStaff->loginStaffInfo();
            $dataTimekeepingProvisional = $modelTimekeeping->getInfo($timekeepingProvisionalId);
            return view('work.timekeeping.edit-time-end', compact('modelStaff', 'dataStaff', 'dataTimekeepingProvisional'));

        }
    }

    public function postEditTimeEnd($timekeepingProvisionalId)
    {
        $hFunction = new \Hfunction();
        $modelTimekeepingProvisional = new QcTimekeepingProvisional();
        $dayEnd = Request::input('cbDayEnd');
        $monthEnd = Request::input('cbMonthEnd');
        $yearEnd = Request::input('cbYearEnd');
        $hoursEnd = Request::input('cbHoursEnd');
        $minuteEnd = Request::input('cbMinuteEnd');
        $note = Request::input('txtNote');
        $afternoonStatus = Request::input('txtAfternoonStatus');
        $afternoonStatus = ($afternoonStatus) ? 1 : 0;

        $timeBegin = $modelTimekeepingProvisional->timeBegin($timekeepingProvisionalId)[0];
        $timeEnd = $hFunction->convertStringToDatetime("$monthEnd/$dayEnd/$yearEnd $hoursEnd:$minuteEnd:00");
        if (date('Y-m-d H:i', strtotime($timeBegin)) > date('Y-m-d H:i', strtotime($timeEnd))) {
            return 'Giờ phải lớn hơn giờ vào';
        } else {
            if (!$modelTimekeepingProvisional->updateTimeEnd($timekeepingProvisionalId, $timeEnd, $afternoonStatus, $note)) {
                return "Tính năng đang cập nhật";
            }
        }
    }

    //báo nghỉ
    public function getOffWork($workId)
    {
        $modelStaff = new QcStaff();
        $modelWork = new QcWork();
        if ($modelStaff->checkLogin()) {
            $dataStaff = $modelStaff->loginStaffInfo();
            $dataWork = $modelWork->getInfo($workId);
            return view('work.timekeeping.off-work', compact('modelStaff', 'dataStaff', 'dataWork'));
        }
    }

    public function postOffWork()
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelLicenseOffWork = new QcLicenseOffWork();
        $loginStaffId = $modelStaff->loginStaffId();
        $workId = Request::input('txtWork');

        $dayOff = Request::input('cbDayOff');
        $monthOff = Request::input('cbMonthOff');
        $yearOff = Request::input('cbYearOff');
        $numberHouse = Request::input('cbNumberOff');
        $note = Request::input('txtNote');
        $dateOff = $hFunction->convertStringToDatetime("$monthOff/$dayOff/$yearOff 00:00:00");
        if (!empty($loginStaffId)) {
            $monthOff = (int)$monthOff;
            $dayOff = (int)$dayOff;
            $monthOff = ($monthOff < 10) ? "0$monthOff" : $monthOff;
            $dayOff = ($dayOff < 10) ? "0$dayOff" : $dayOff;
            if ($hFunction->checkValidDate("$yearOff-$monthOff-$dayOff")) {
                if ($modelLicenseOffWork->existDateOfStaff($loginStaffId, "$monthOff/$dayOff/$yearOff")) {
                    return 'Ngày này đã xin nghỉ, chon ngày khác';
                } else {
                    if ($numberHouse > 1) {
                        for ($i = 0; $i < $numberHouse; $i++) {
                            $newDate = date('Y-m-d H:i:s', strtotime("+$i day", strtotime($dateOff)));
                            $modelLicenseOffWork->insert($newDate, $note, $loginStaffId, null);
                        }
                    } else {
                        $modelLicenseOffWork->insert($dateOff, $note, $loginStaffId, null);
                    }
                }
            } else {
                return "Tháng $monthOff không có ngày $dayOff";
            }

        }
    }

    // lấy form thêm ảnh xác nhận
    public function getTimekeepingProvisionalImage($timekeepingProvisionalId)
    {
        $modelStaff = new QcStaff();
        $modelTimekeepingProvisional = new QcTimekeepingProvisional();
        if ($modelStaff->checkLogin()) {
            $dataTimekeepingProvisional = $modelTimekeepingProvisional->getInfo($timekeepingProvisionalId);
            return view('work.timekeeping.image-add', compact('modelStaff', 'dataTimekeepingProvisional'));
        }
    }

    // lưu ảnh
    public function postTimekeepingProvisionalImage()
    {
        $hFunction = new \Hfunction();
        $modelTimekeepingProvisionalImage = new QcTimekeepingProvisionalImage();
        $timekeepingProvisionalId = Request::input('txtTimekeepingProvisional');
        $txtTimekeepingImage = Request::file('txtTimekeepingImage');
        if (!empty($txtTimekeepingImage)) {
            $n_o = 0;
            foreach ($_FILES['txtTimekeepingImage']['name'] as $name => $value) {
                $name_img = stripslashes($_FILES['txtTimekeepingImage']['name'][$name]);
                if (!empty($name_img)) {
                    $n_o = $n_o + 1;
                    $name_img = $hFunction->getTimeCode() . "_$n_o." . $hFunction->getTypeImg($name_img);
                    $source_img = $_FILES['txtTimekeepingImage']['tmp_name'][$name];
                    if ($modelTimekeepingProvisionalImage->uploadImage($source_img, $name_img, 500)) {
                        $modelTimekeepingProvisionalImage->insert($name_img, $timekeepingProvisionalId);
                    }
                }
            }
        }
    }

    //xóa ảnh xác nhận
    public function deleteTimekeepingProvisionalImage($imageId)
    {
        $modelTimekeepingProvisionalImage = new QcTimekeepingProvisionalImage();
        $modelTimekeepingProvisionalImage->actionDelete($imageId);
    }

    public function cancelOffWork($licenseId)
    {
        $modelLicenseOff = new QcLicenseOffWork();
        $modelLicenseOff->deleteInfo($licenseId);
    }

    //báo trễ
    public function getLateWork($workId)
    {
        $modelStaff = new QcStaff();
        $modelWork = new QcWork();
        if ($modelStaff->checkLogin()) {
            $dataStaff = $modelStaff->loginStaffInfo();
            $dataWork = $modelWork->getInfo($workId);
            return view('work.timekeeping.late-work', compact('modelStaff', 'dataStaff', 'dataWork'));
        }
    }

    public function postLateWork()
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelLicenseOffWork = new QcLicenseOffWork();
        $modelLicenseLateWork = new QcLicenseLateWork();
        $loginStaffId = $modelStaff->loginStaffId();
        $workId = Request::input('txtWork');

        $dayLate = Request::input('cbDayLate');
        $monthLate = Request::input('cbMonthLate');
        $yearLate = Request::input('cbYearLate');
        $note = Request::input('txtNote');
        $dateLate = $hFunction->convertStringToDatetime("$monthLate/$dayLate/$yearLate 00:00:00");
        if (!empty($loginStaffId)) {
            $monthLate = (int)$monthLate;
            $dayLate = (int)$dayLate;
            $monthLate = ($monthLate < 10) ? "0$monthLate" : $monthLate;
            $dayLate = ($dayLate < 10) ? "0$dayLate" : $dayLate;
            if ($hFunction->checkValidDate("$yearLate-$monthLate-$dayLate")) {
                if ($modelLicenseLateWork->existDateOfStaff($loginStaffId, "$monthLate/$dayLate/$yearLate") || $modelLicenseOffWork->existDateOfWork($workId, "$monthLate/$dayLate/$yearLate")) {
                    return 'Ngày này đã xin nghỉ / Đã chấm công, chon ngày khác';
                } else {
                    $modelLicenseLateWork->insert($dateLate, $note, $loginStaffId, null);
                }
            } else {
                return "Tháng $yearLate không có ngày $dayLate";
            }

        }
    }

    public function cancelLateWork($licenseId)
    {
        $modelLicenseLate = new QcLicenseLateWork();
        $modelLicenseLate->deleteInfo($licenseId);
    }

    //hủy chấm công
    public function cancelTimekeeping($timekeepingProvisionalId)
    {
        $modelTimekeepingProvisional = new QcTimekeepingProvisional();
        $modelTimekeepingProvisional->deleteInfo($timekeepingProvisionalId);
    }

}