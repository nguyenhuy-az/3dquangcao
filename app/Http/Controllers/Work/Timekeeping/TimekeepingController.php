<?php

namespace App\Http\Controllers\Work\Timekeeping;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\Import\QcImport;
use App\Models\Ad3d\ImportPay\QcImportPay;
use App\Models\Ad3d\LicenseLateWork\QcLicenseLateWork;
use App\Models\Ad3d\LicenseOffWork\QcLicenseOffWork;
use App\Models\Ad3d\Salary\QcSalary;
use App\Models\Ad3d\SalaryBeforePay\QcSalaryBeforePay;
use App\Models\Ad3d\SalaryPay\QcSalaryPay;
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
        $modelCompany = new QcCompany();
        $modelStaff = new QcStaff();
        $dataAccess = [
            'object' => 'timekeeping'
        ];
        $dataStaff = $modelStaff->loginStaffInfo();
        $dateFilter = $hFunction->currentDate();
        return view('work.timekeeping.timekeeping.list', compact('modelCompany','dataAccess', 'modelStaff', 'dataStaff', 'dateFilter'));
    }

    # xem anh bao cao
    public function viewTimekeepingProvisionalImage($imageId)
    {
        $modelTimekeepingProvisionalImage = new QcTimekeepingProvisionalImage();
        $dataTimekeepingProvisionalImage = $modelTimekeepingProvisionalImage->getInfo($imageId);
        return view('work.timekeeping.timekeeping.view-provisional-image', compact('dataTimekeepingProvisionalImage'));

    }

    //==== ===== bao gio vao ======= ========
    public function getTimeBegin($workId)
    {
        $modelStaff = new QcStaff();
        $modelWork = new QcWork();
        if ($modelStaff->checkLogin()) {
            $dataStaff = $modelStaff->loginStaffInfo();
            $dataWork = $modelWork->getInfo($workId);
            return view('work.timekeeping.timekeeping.time-begin', compact('modelStaff', 'dataStaff', 'dataWork'));
        }
    }

    public function postTimeBegin()
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelTimekeeping = new QcTimekeepingProvisional();
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

            } else {
                return "Tháng $monthBegin không có ngày $dayBegin";
            }
        }

    }

    public function getEditTimeBegin($timekeepingProvisionalId)
    {
        $modelStaff = new QcStaff();
        $modelTimekeeping = new QcTimekeepingProvisional();
        if ($modelStaff->checkLogin()) {
            $dataStaff = $modelStaff->loginStaffInfo();
            $dataTimekeepingProvisional = $modelTimekeeping->getInfo($timekeepingProvisionalId);
            return view('work.timekeeping.timekeeping.edit-time-begin', compact('modelStaff', 'dataStaff', 'dataTimekeepingProvisional'));

        }
    }

    public function postEditTimeBegin($timekeepingProvisionalId)
    {
        $hFunction = new \Hfunction();
        $modelTimekeepingProvisional = new QcTimekeepingProvisional();
        $dayBegin = Request::input('cbDayBegin');
        $monthBegin = Request::input('cbMonthBegin');
        $yearBegin = Request::input('cbYearBegin');
        $hoursBegin = Request::input('cbHoursBegin');
        $minuteBegin = Request::input('cbMinuteBegin');
        $timeEnd = $modelTimekeepingProvisional->timeEnd($timekeepingProvisionalId);
        $timeBegin = $hFunction->convertStringToDatetime("$monthBegin/$dayBegin/$yearBegin $hoursBegin:$minuteBegin:00");
        if ($hFunction->checkEmpty($timeEnd)) {
            $checkTime = $hFunction->carbonNow();
        } else {
            $checkTime = $timeEnd;
        }
        if ($hFunction->formatDateToYMDHI($timeBegin)  > $hFunction->formatDateToYMDHI($checkTime)) {
            return "Giờ vào phải nhỏ hơn giờ ra";
        } else {
            if (!$modelTimekeepingProvisional->updateTimeBegin($timekeepingProvisionalId, $timeBegin)) {
                return "Tính năng đang cập nhật";
            }
        }
    }

    //==== ===== bao gio ra ========= ==========
    public function getTimeEnd($timekeepingId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelSalary = new QcSalary();
        $modelSalaryPay = new QcSalaryPay();
        $modelSalaryBeforePay = new QcSalaryBeforePay();
        $modelImport = new QcImport();
        $modelImportPay = new QcImportPay();
        $modelTimekeeping = new QcTimekeepingProvisional();
        $dataStaff = $modelStaff->loginStaffInfo();
        $dataCompanyStaffWorkLogin = $modelStaff->loginCompanyStaffWork();
        $loginStaffId = $dataStaff->staffId();
        $dataActivityWork = $dataCompanyStaffWorkLogin->workInfoActivity();
        //-------- ---------- lay thong tin chua xac nhan thanh toan luong ---------- ---------
        # danh sach ma cham cong
        $listWorkId = $modelStaff->allListWorkId($loginStaffId);
        # danh dach ma bang luong theo ma cham cong
        $listSalaryId = $modelSalary->listIdOfListWorkId($listWorkId);
        # thong tin thanh toan chua xac nhan
        $dataSalaryPay = $modelSalaryPay->getInfoUnConfirmOfListSalaryId($listSalaryId);
        //-------- ---------- lay thong tin chua xac nhan ưng toan luong ---------- ---------
        $dataSalaryBeforePay = $modelSalaryBeforePay->infoUnConfirmOfWork($dataActivityWork->workId());
        //-------- ---------- lay thong tin chua xac nhan thanh toan mua vat tu ---------- ---------
        # lay danh sach nhap vat tu da thanh toan
        $listImportId = $modelImport->listImportIdPaidOfStaffImport($loginStaffId);
        # lay thong tin thanh toan nhap vat tu chua duoc xac nhan theo danh sach don nhap
        $dataImportPay = $modelImportPay->infoUnConfirmOfListImportId($listImportId);
        # co thong tin chua xac nhan
        if ($hFunction->checkCount($dataSalaryPay) || $hFunction->checkCount($dataSalaryBeforePay) || $hFunction->checkCount($dataImportPay) || $dataCompanyStaffWorkLogin->existUnConfirmInRoundCompanyStoreCheck()) {
            return view('work.components.warning.warning-confirm', compact('modelStaff', 'dataSalaryPay', 'dataSalaryBeforePay', 'dataImportPay'));
        } else {
            $dataStaff = $modelStaff->loginStaffInfo();
            $dataTimekeepingProvisional = $modelTimekeeping->getInfo($timekeepingId);
            return view('work.timekeeping.timekeeping.time-end', compact('modelStaff', 'dataStaff', 'dataTimekeepingProvisional'));
        }
    }

    public function postTimeEnd()
    {
        $hFunction = new \Hfunction();
        $modelCompany = new QcCompany();
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

        $timeBegin = $modelTimekeepingProvisional->timeBegin($timekeepingProvisionalId);
        $timeEnd = $hFunction->convertStringToDatetime("$monthEnd/$dayEnd/$yearEnd $hoursEnd:$minuteEnd:00");
        if ($hFunction->formatDateToYMDHI($timeBegin) > $hFunction->formatDateToYMDHI($timeEnd)) {
            return 'GIỜ RA PHẢI LỚN HƠN GIỜ VÀO';
        } else {
            # kiem tra vuot gioi han bao gio ra
            if ($hFunction->formatDateToYMDHI($timeEnd) > $hFunction->formatDateToYMDHI($modelCompany->getReportLimitTimeEndOfDate($timeBegin))) {
                return 'GIỜ RA KHÔNG ĐÚNG';
            } else {
                # co anh bao cao
                if (empty($txtTimekeepingImage_1) && empty($txtTimekeepingImage_2) && empty($txtTimekeepingImage_3)) {
                    return "PHẢI CÓ ẢNH BÁO CÁO";
                } else {
                    # kiem tra da bao gio ra hay chu
                    if ($modelTimekeepingProvisional->checkReportedTimeEnd($timekeepingProvisionalId)) {
                        return "Ngày này đã báo giờ ra";
                    } else {
                        // echo "$timekeepingProvisionalId - $timeEnd - $afternoonStatus - $note";
                        if ($modelTimekeepingProvisional->updateTimeEnd($timekeepingProvisionalId, $timeEnd, $afternoonStatus, $note)) {
                            # anh bao cao 1
                            if (!empty($txtTimekeepingImage_1)) {
                                $newReportId_1 = null; # mặc định null
                                if ($cbWorkAllocation_1 > 0) { # anh bao cao thuoc cong trinh
                                    # them bao cao
                                    if ($modelWorkAllocationReport->insert($hFunction->carbonNow(), 'Báo cáo tiến độ', $cbWorkAllocation_1)) {
                                        $newReportId_1 = $modelWorkAllocationReport->insertGetId();
                                    }
                                }
                                # them anh bao cao trong ngay
                                $name_img_1 = stripslashes($_FILES['txtTimekeepingImage_1']['name']);
                                $name_img_1 = $hFunction->getTimeCode() . '.' . $hFunction->getTypeImg($name_img_1);
                                $source_img = $_FILES['txtTimekeepingImage_1']['tmp_name'];
                                if (!$modelTimekeepingProvisionalImage->checkExistName($name_img_1)) { # khong ton tai hinh anh
                                    if ($modelTimekeepingProvisionalImage->uploadImage($source_img, $name_img_1, 500)) {
                                        $modelTimekeepingProvisionalImage->insert($name_img_1, $timekeepingProvisionalId, $newReportId_1);
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
                                $name_img_2 = stripslashes($_FILES['txtTimekeepingImage_2']['name']);
                                $name_img_2 = $hFunction->getTimeCode() . '.' . $hFunction->getTypeImg($name_img_2);
                                $source_img = $_FILES['txtTimekeepingImage_2']['tmp_name'];
                                if (!$modelTimekeepingProvisionalImage->checkExistName($name_img_2)) {
                                    if ($modelTimekeepingProvisionalImage->uploadImage($source_img, $name_img_2, 500)) {
                                        $modelTimekeepingProvisionalImage->insert($name_img_2, $timekeepingProvisionalId, $newReportId_2);
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
                                $name_img_3 = stripslashes($_FILES['txtTimekeepingImage_3']['name']);
                                $name_img_3 = $hFunction->getTimeCode() . '.' . $hFunction->getTypeImg($name_img_3);
                                $source_img = $_FILES['txtTimekeepingImage_3']['tmp_name'];
                                if (!$modelTimekeepingProvisionalImage->checkExistName($name_img_3)) {
                                    if ($modelTimekeepingProvisionalImage->uploadImage($source_img, $name_img_3, 500)) {
                                        $modelTimekeepingProvisionalImage->insert($name_img_3, $timekeepingProvisionalId, $newReportId_3);
                                    }
                                }
                            }
                        } else {
                            return "Hệ thống đang bảo trì";
                        }
                    }
                }
            }

        }
    }

    //----- sua bao gio ra
    public function getEditTimeEnd($timekeepingProvisionalId)
    {
        $modelStaff = new QcStaff();
        $modelTimekeeping = new QcTimekeepingProvisional();
        if ($modelStaff->checkLogin()) {
            $dataStaff = $modelStaff->loginStaffInfo();
            $dataTimekeepingProvisional = $modelTimekeeping->getInfo($timekeepingProvisionalId);
            return view('work.timekeeping.timekeeping.edit-time-end', compact('modelStaff', 'dataStaff', 'dataTimekeepingProvisional'));

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
        $timeBegin = $hFunction->formatDateToYMDHI($timeBegin);
        $timeEnd = $hFunction->convertStringToDatetime("$monthEnd/$dayEnd/$yearEnd $hoursEnd:$minuteEnd:00");
        $timeEnd = $hFunction->formatDateToYMDHI($timeEnd);
        if ($timeBegin > $timeEnd) {
            return "Giờ phải lớn hơn giờ vào $timeBegin === $monthEnd/$dayEnd/$yearEnd $hoursEnd:$minuteEnd";
        } else {
            $modelTimekeepingProvisional->updateTimeEnd($timekeepingProvisionalId, $timeEnd, $afternoonStatus, $note);
        }
    }

    #==== ====== them anh bao cao cong viec
    // lấy form thêm ảnh xác nhận
    public function getTimekeepingProvisionalImage($timekeepingProvisionalId)
    {
        $modelStaff = new QcStaff();
        $modelTimekeepingProvisional = new QcTimekeepingProvisional();
        $dataTimekeepingProvisional = $modelTimekeepingProvisional->getInfo($timekeepingProvisionalId);
        return view('work.timekeeping.timekeeping.image-add', compact('modelStaff', 'dataTimekeepingProvisional'));
    }

    // lưu ảnh
    public function postTimekeepingProvisionalImage()
    {
        $hFunction = new \Hfunction();
        $modelWorkAllocationReport = new QcWorkAllocationReport();
        $modelTimekeepingProvisionalImage = new QcTimekeepingProvisionalImage();
        $timekeepingProvisionalId = Request::input('txtTimekeepingProvisional');
        $txtTimekeepingImage_1 = Request::file('txtTimekeepingImage_1');
        $cbWorkAllocation_1 = Request::input('cbWorkAllocation_1');
        $txtTimekeepingImage_2 = Request::file('txtTimekeepingImage_2');
        $cbWorkAllocation_2 = Request::input('cbWorkAllocation_2');
        $txtTimekeepingImage_3 = Request::file('txtTimekeepingImage_3');
        $cbWorkAllocation_3 = Request::input('cbWorkAllocation_3');
        if (empty($txtTimekeepingImage_1) && empty($txtTimekeepingImage_2) && empty($txtTimekeepingImage_3)) {
            return "PHẢI CÓ ẢNH BÁO CÁO";
        } else {
            //dd($txtTimekeepingImage_2);
            # anh bao cao 1
            if (!empty($txtTimekeepingImage_1)) {
                $newReportId_1 = null; # mặc định null
                if ($cbWorkAllocation_1 > 0) { # anh bao cao thuoc cong trinh
                    # them bao cao
                    if ($modelWorkAllocationReport->insert($hFunction->carbonNow(), 'Báo cáo tiến độ', $cbWorkAllocation_1)) {
                        $newReportId_1 = $modelWorkAllocationReport->insertGetId();
                    }
                }
                # them anh bao cao trong ngay
                $name_img_1 = stripslashes($_FILES['txtTimekeepingImage_1']['name']);
                $name_img_1 = $hFunction->getTimeCode() . '.' . $hFunction->getTypeImg($name_img_1);
                $source_img = $_FILES['txtTimekeepingImage_1']['tmp_name'];
                if (!$modelTimekeepingProvisionalImage->checkExistName($name_img_1)) {
                    if ($modelTimekeepingProvisionalImage->uploadImage($source_img, $name_img_1, 500)) {
                        $modelTimekeepingProvisionalImage->insert($name_img_1, $timekeepingProvisionalId, $newReportId_1);
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
                $name_img_2 = stripslashes($_FILES['txtTimekeepingImage_2']['name']);
                $name_img_2 = $hFunction->getTimeCode() . '.' . $hFunction->getTypeImg($name_img_2);
                $source_img = $_FILES['txtTimekeepingImage_2']['tmp_name'];
                if (!$modelTimekeepingProvisionalImage->checkExistName($name_img_2)) {
                    if ($modelTimekeepingProvisionalImage->uploadImage($source_img, $name_img_2, 500)) {
                        $modelTimekeepingProvisionalImage->insert($name_img_2, $timekeepingProvisionalId, $newReportId_2);
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
                $name_img_3 = stripslashes($_FILES['txtTimekeepingImage_3']['name']);
                $name_img_3 = $hFunction->getTimeCode() . '.' . $hFunction->getTypeImg($name_img_3);
                $source_img = $_FILES['txtTimekeepingImage_3']['tmp_name'];
                if (!$modelTimekeepingProvisionalImage->checkExistName($name_img_3)) {
                    if ($modelTimekeepingProvisionalImage->uploadImage($source_img, $name_img_3, 500)) {
                        $modelTimekeepingProvisionalImage->insert($name_img_3, $timekeepingProvisionalId, $newReportId_3);
                    }
                }
            }
        }
        /*if (!empty($txtTimekeepingImage)) {
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
        }*/
    }

    //xóa ảnh xác nhận
    public function deleteTimekeepingProvisionalImage($imageId)
    {
        $modelTimekeepingProvisionalImage = new QcTimekeepingProvisionalImage();
        $modelTimekeepingProvisionalImage->actionDelete($imageId);
    }

    //===== ===== xin nghi
    public function getOffWork($workId)
    {
        $modelStaff = new QcStaff();
        $modelWork = new QcWork();
        if ($modelStaff->checkLogin()) {
            $dataStaff = $modelStaff->loginStaffInfo();
            $dataWork = $modelWork->getInfo($workId);
            return view('work.timekeeping.timekeeping.off-work', compact('modelStaff', 'dataStaff', 'dataWork'));
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

    public function cancelOffWork($licenseId)
    {
        $modelLicenseOff = new QcLicenseOffWork();
        $modelLicenseOff->deleteInfo($licenseId);
    }

    //===== ===== xin di tre
    public function getLateWork($workId)
    {
        $modelStaff = new QcStaff();
        $modelWork = new QcWork();
        if ($modelStaff->checkLogin()) {
            $dataStaff = $modelStaff->loginStaffInfo();
            $dataWork = $modelWork->getInfo($workId);
            return view('work.timekeeping.timekeeping.late-work', compact('modelStaff', 'dataStaff', 'dataWork'));
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
                if ($modelLicenseLateWork->existDateOfStaff($loginStaffId, "$monthLate/$dayLate/$yearLate") || $modelLicenseOffWork->existDateOfStaff($loginStaffId, "$monthLate/$dayLate/$yearLate")) {
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

    //===== ==== hủy chấm công
    public function cancelTimekeeping($timekeepingProvisionalId)
    {
        $modelTimekeepingProvisional = new QcTimekeepingProvisional();
        $modelTimekeepingProvisional->deleteInfo($timekeepingProvisionalId);
    }

}
