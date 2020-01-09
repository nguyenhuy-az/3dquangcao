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
    public function index($companyFilterId = null, $monthFilter = null, $yearFilter = null)
    {
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelSystemDateOff = new QcSystemDateOff();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataAccess = [
            'accessObject' => 'systemDateOff'
        ];
        $dataCompany = $modelCompany->getInfo();
        if (empty($companyFilterId)) {
            $companyFilterId = $dataStaffLogin->companyId();
        }

        //$monthFilter = (empty($monthFilter)? 0:$monthFilter
        if (empty($yearFilter)) { // may mac dinh
            $dateFilter = date('Y');
            $monthFilter = 0;
            $yearFilter = date('Y');
        } elseif ($monthFilter == 0) {
            $dateFilter = date('Y', strtotime("1-1-$yearFilter"));
        } else {
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        }
        $dataSystemDateOff = $modelSystemDateOff->selectInfoOfCompanyAndDate($companyFilterId, $dateFilter)->paginate(30);
        return view('ad3d.system.system-date-off.list', compact('modelStaff', 'dataSystemDateOff', 'dataAccess', 'dataCompany', 'companyFilterId', 'monthFilter', 'yearFilter'));
    }

    //lay form them
    public function getAdd()
    {
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $dataAccess = [
            'accessObject' => 'systemDateOff'
        ];
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataCompany = $modelCompany->getInfo($dataStaffLogin->companyId());
        return view('ad3d.system.system-date-off.add', compact('modelStaff','dataAccess', 'dataCompany'));
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
        if (count($cbDay) > 0) {
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
    // xoa
    public function deleteDateOff($payListId)
    {
        $modelSystemDateOff = new QcSystemDateOff();
        $modelSystemDateOff->deleteDateOff($payListId);
    }
}
