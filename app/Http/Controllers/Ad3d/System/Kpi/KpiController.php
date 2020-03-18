<?php

namespace App\Http\Controllers\Ad3d\System\Kpi;

use App\Models\Ad3d\Department\QcDepartment;
use App\Models\Ad3d\Kpi\QcKpi;
use App\Models\Ad3d\Staff\QcStaff;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Input;
use File;
use DB;
use Request;

class KpiController extends Controller
{
    public function index()
    {
        $modelStaff = new QcStaff();
        $modelKpi = new QcKpi();
        $dataAccess = [
            'accessObject' => 'activity',
            'accessSubObject' => 'kpi'
        ];
        $dataKpi = $modelKpi->selectActivityInfo()->paginate(30);
        return view('ad3d.system.kpi.list', compact('modelStaff', 'dataKpi', 'dataAccess'));

    }

    public function view($kpiId)
    {
        $modelKpi = new QcKpi();
        if (!empty($kpiId)) {
            $dataKpi = $modelKpi->getInfo($kpiId);
            return view('ad3d.system.kpi.view', compact('dataKpi'));
        }
    }

    //them Kpi
    public function getAdd($departmentId = null)
    {
        $modelStaff = new QcStaff();
        $modelKpi = new QcKpi();
        $modelDepartment = new QcDepartment();
        $dataAccess = [
            'accessObject' => 'activity',
            'accessSubObject' => 'kpi'
        ];

        $departmentId = (empty($departmentId)) ? $modelDepartment->businessDepartmentId() : $departmentId; # mac dinh bo phan kinh doanh
        $dataDepartment = $modelDepartment->getInfo($departmentId);
        $dataKpi = $modelKpi->getInfoOfDepartment($departmentId);
        return view('ad3d.system.kpi.add', compact('modelStaff', 'dataAccess', 'dataDepartment','dataKpi'));
    }

    public function postAdd()
    {
        $modelKpi = new QcKpi();
        $departmentId = Request::input('cbDepartment');
        $cbLimit = Request::input('cbLimit');
        $cbPlusPercent = Request::input('cbPlusPercent');
        $cbMinusPercent = Request::input('cbMinusPercent');
        $txtDescription = Request::input('txtDescription');
        if ($cbLimit > 0) {
            if ($modelKpi->existActivityOfDepartment($departmentId, $cbLimit, $cbPlusPercent, $cbMinusPercent)) {
                Session::put('notifyAdd', "Thông tin này đã tồn tại đã tồn tại.");
            } else {
                if ($modelKpi->insert($cbLimit, $cbPlusPercent, $cbMinusPercent, $txtDescription, $departmentId)) {
                    Session::put('notifyAdd', 'Thêm thành công, Nhập thông tin để tiếp tục');
                } else {
                    Session::put('notifyAdd', 'Hệ tính năng đang cập nhật');
                }
            }
        } else {
            Session::put('notifyAdd', 'Bạn phải chon hạn mức muốn thêm');
        }


    }

    //edit
    public function getEdit($kpiId)
    {
        $modelKpi = new QcKpi();
        $dataKpi = $modelKpi->getInfo($kpiId);
        if (count($dataKpi) > 0) {
            return view('ad3d.system.payment-type.edit', compact('dataPaymentType'));
        }
    }

    public function postEdit($kpiId)
    {
        $modelKpi = new QcKpi();
        $name = Request::input('txtName');
        /*if ($modelKpi->existEditName($kpiId, $name)) {
            return "Tên <b>'$name'</b> đã tồn tại.";
        } else {
            $modelKpi->updateInfo($kpiId, $name);
        }*/

    }

    // delete
    public function deleteKpi($kpiId)
    {
        /*
        if (!empty($kpiId)) {
            $modelKpi = new QcKpi();
            $modelKpi->actionDelete($kpiId);
        }
        */
    }
}
