<?php

namespace App\Http\Controllers\Ad3d\System\SystemDateOff;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\Staff\QcStaff;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ad3d\SystemDateOff\QcSystemDateOff;
use Illuminate\Support\Facades\Session;
use Input;
use File;
use DB;
use Request;

class SystemDateOffController extends Controller
{
    public function index($companyFilterId = null, $monthFilter = 0, $yearFilter = 0)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelSystemDateOff = new QcSystemDateOff();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataCompanyLogin = $modelStaff->companyLogin();
        $companyLoginId = $dataCompanyLogin->companyId();
        $dataAccess = [
            'accessObject' => 'systemDateOff'
        ];
        $dataCompany = $modelCompany->getInfoSameSystemOfCompany($companyLoginId);
        if ($hFunction->checkEmpty($companyFilterId)) {
            $companyFilterId = $dataStaffLogin->companyId();
        }
        $dateFilter = null;
        if ($monthFilter == 0 && $yearFilter == 0) { //xem  trong tháng
            $monthFilter = 100;
            $yearFilter = date('Y');
            $dateFilter = date('Y');
        } elseif ($monthFilter == 100 && $yearFilter > 100) { //xem tất cả các ngày trong tháng
            $dateFilter = date('Y', strtotime("1-1-$yearFilter"));
        } elseif ($monthFilter > 0 && $monthFilter < 100 && $yearFilter > 100) { //xem tất cả các ngày trong tháng
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        } elseif ($monthFilter == 100 && $yearFilter == 100) { //xem tất cả
            $dateFilter = null;
            $monthFilter = 100;
            $yearFilter = 100;
        } else {
            $dateFilter = date('Y-m');
            $monthFilter = date('m');
            $yearFilter = date('Y');
        }
        $dataSystemDateOff = $modelSystemDateOff->selectInfoOfCompanyAndDate($companyFilterId, $dateFilter)->paginate(30);
        return view('ad3d.system.system-date-off.list', compact('modelStaff', 'dataSystemDateOff', 'dataAccess', 'dataCompany', 'companyFilterId', 'monthFilter', 'yearFilter'));
    }

    #------- -------  them ------- ------
    public function getAdd()
    {
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelSystemDateOff = new QcSystemDateOff();
        $dataAccess = [
            'accessObject' => 'systemDateOff'
        ];
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataCompany = $modelCompany->getInfo($dataStaffLogin->companyId());
        return view('ad3d.system.system-date-off.add', compact('modelStaff','modelSystemDateOff', 'dataAccess', 'dataCompany'));
    }

    public function getAddDate()
    {
        return view('ad3d.system.system-date-off.add-date', compact('dataAccess'));
    }

    public function postAdd()
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelSystemDateOff = new QcSystemDateOff();
        $cbCompany = Request::input('cbCompany');
        $cbDay = Request::input('cbDay');
        $cbMonth = Request::input('cbMonth');
        $cbYear = Request::input('cbYear');
        $cbType = Request::input('cbType');
        $txtDescription = Request::input('txtDescription');
        $loginStaffId = $modelStaff->loginStaffId();
        if ($hFunction->checkCount($cbDay)) {
            foreach ($cbDay as $key => $value) {
                $day = $value;
                $month = $cbMonth[$key];
                $year = $cbYear[$key];
                $type = $cbType[$key];
                $description = $txtDescription[$key];
                $dateOff = $hFunction->convertStringToDatetime("$day-$month-$year 00:00:00");
                if (!$modelSystemDateOff->checkExistsDateOfCompany($cbCompany, $dateOff)) {
                    $modelSystemDateOff->insert($dateOff, $description, $type, $loginStaffId, $cbCompany);
                }

            }
            Session::put('notifyAdd', 'THÊM THÀNH CÔNG, NHẬP THÔNG TIN ĐỂ TIẾP TỤC');
            return redirect()->back();
        } else {
            Session::put('notifyAdd', 'Thêm thất bại, Phải chọn ngày nghỉ');
        }

        // check exist of name
        /*if ($modelPayActivityList->existName($name)) {
            Session::put('notifyAdd', "Thêm thất bại <b>'$name'</b> đã tồn tại.");
        } else {
            if ($modelPayActivityList->insert($name, $txtDescription, $cbType)) {
                Session::put('notifyAdd', 'Thêm thành công, Nhập thông tin để tiếp tục');
            } else {
                Session::put('notifyAdd', 'Thêm thất bại, Nhập thông tin để tiếp tục');
            }
        }*/
    }

    //------- ------- Sua ------- --------
    public function getEdit($dateOffId)
    {
        $modelSystemDateOff = new QcSystemDateOff();
        $dataSystemDateOff = $modelSystemDateOff->getInfo($dateOffId);
        return view('ad3d.system.system-date-off.edit', compact('dataSystemDateOff'));
    }

    public function postEdit($dateOffId)
    {
        $modelSystemDateOff = new QcSystemDateOff();
        $cbType = Request::input('cbType');
        $txtDescription = Request::input('txtDescription');
        $modelSystemDateOff->updateInfo($dateOffId, $cbType, $txtDescription);
    }

    #------- ------- Sao chep ngay nghỉ ------- --------
    public function getCopyDateOff($companySelectedId = null, $yearSelected = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $dataCompanyLogin = $modelStaff->companyLogin();
        $companyLoginId = $dataCompanyLogin->companyId();
        $dataAccess = [
            'accessObject' => 'systemDateOff'
        ];
        $yearSelected = ($hFunction->checkEmpty($yearSelected)) ? $hFunction->currentYear() : $yearSelected;
        if ($hFunction->checkEmpty($companySelectedId)) {
            $dataCompanySelected = $hFunction->setNull();
        } else {
            $dataCompanySelected = $modelCompany->getInfo($companySelectedId);
        }
        $dataCompany = $modelCompany->getInfoSameSystemOfCompany($companyLoginId);
        return view('ad3d.system.system-date-off.copy-date-off', compact('modelStaff', 'dataCompany', 'dataAccess', 'dataCompanySelected', 'yearSelected'));
    }

    public function postCopyDateOff()
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelSystemDateOff = new QcSystemDateOff();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $companyLoginId = $dataStaffLogin->companyId();
        $companyId = Request::input('cbCompanyCopy');
        $cbYearCopy = Request::input('cbYearCopy');
        $insertStatus = false;
        # ngay nghi co dinh
        $dataSystemDateOffObligatory = $modelSystemDateOff->infoDateObligatoryOfCompanyAndDate($companyId, $cbYearCopy);
        if ($hFunction->checkCount($dataSystemDateOffObligatory)) {
            foreach ($dataSystemDateOffObligatory as $systemDateOffObligatory) {
                $dateOff = $systemDateOffObligatory->dateOff();
                $type = $systemDateOffObligatory->type();
                $description = $systemDateOffObligatory->description();
                if (!$modelSystemDateOff->checkExistsDateOfCompany($companyLoginId, $dateOff)) {
                    if ($modelSystemDateOff->insert($dateOff, $description, $type, $dataStaffLogin->staffId(), $companyLoginId)) {
                        $insertStatus = true;
                    }
                }
            }
        }

        # ngay nghi khong co dinh
        $dataSystemDateOffOptional = $modelSystemDateOff->infoDateOptionalOfCompanyAndDate($companyId, $cbYearCopy);
        if ($hFunction->checkCount($dataSystemDateOffOptional)) {
            foreach ($dataSystemDateOffOptional as $systemDateOffOptional) {
                $dateOff = $systemDateOffOptional->dateOff();
                $type = $systemDateOffOptional->type();
                $description = $systemDateOffOptional->description();
                if (!$modelSystemDateOff->checkExistsDateOfCompany($companyLoginId, $dateOff)) {
                    if ($modelSystemDateOff->insert($dateOff, $description, $type, $dataStaffLogin->staffId(), $companyLoginId)) {
                        $insertStatus = true;
                    }
                }
            }
        }
        if ($insertStatus) {
            Session::put('notifyAdd', "Đã sao chép  thành công");
        } else {
            Session::put('notifyAdd', "Tính năng đang cập nhật");
        }

    }

    # xoa
    public function deleteDateOff($dateOffId)
    {
        $modelSystemDateOff = new QcSystemDateOff();
        $modelSystemDateOff->deleteDateOff($dateOffId);
    }
}
