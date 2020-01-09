<?php

namespace App\Http\Controllers\Ad3d\System\Department;

use App\Models\Ad3d\Department\QcDepartment;
use App\Models\Ad3d\Staff\QcStaff;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Input;
use File;
use DB;
use Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $modelStaff = new QcStaff();
        $modelDepartment = new QcDepartment();
        $dataAccess = [
            'accessObject' => 'department'
        ];
        $dataDepartment = QcDepartment::orderBy('name', 'ASC')->select('*')->paginate(30);
        return view('ad3d.system.department.list', compact('modelStaff', 'modelDepartment', 'dataDepartment', 'dataAccess'));
    }

    public function view($departmentId)
    {
        $modelStaff = new QcStaff();
        $modelDepartment = new QcDepartment();
        if (!empty($departmentId)) {
            $dataDepartment = $modelDepartment->getInfo($departmentId);
            return view('ad3d.system.department.view', compact('modelStaff','dataDepartment'));
        }
    }

    //add
    public function getAdd()
    {
        $modelStaff = new QcStaff();
        $dataAccess = [
            'accessObject' => 'department'
        ];
        return view('ad3d.system.department.add', compact('modelStaff','dataAccess'));
    }

    public function postAdd()
    {
        $modelDepartment = new QcDepartment();
        $name = Request::input('txtName');
        $txtDepartmentCode = Request::input('txtDepartmentCode');

        // check exist of name
        if ($modelDepartment->existName($name)) {
            Session::put('notifyAdd', "Thêm thất bại <b>'$name'</b> đã tồn tại.");
        } else {
            if ($modelDepartment->insert($txtDepartmentCode, $name)) {
                Session::put('notifyAdd', 'Thêm thành công, Nhập thông tin để tiếp tục');
            } else {
                Session::put('notifyAdd', 'Thêm thất bại, Nhập thông tin để tiếp tục');
            }
        }
    }

    //edit
    public function getEdit($departmentId)
    {
        $modelStaff = new QcStaff();
        $modelDepartment = new QcDepartment();
        $dataDepartment = $modelDepartment->getInfo($departmentId);
        if (count($dataDepartment) > 0) {
            return view('ad3d.system.department.edit', compact('modelStaff','dataDepartment'));
        }
    }

    public function postEdit($departmentId)
    {
        $modelDepartment = new QcDepartment();
        $name = Request::input('txtName');
        $departmentCode = Request::input('txtDepartmentCode');

        $notifyContent = null;
        if ($modelDepartment->existEditName($departmentId, $name)) {
            $notifyContent = "Tên <b>'$name'</b> đã tồn tại.";
        }
        if ($modelDepartment->existEditNameCode($departmentId, $departmentCode)) {
            if (empty($notifyContent)) {
                $notifyContent = "Mã bộ phận <b>'$departmentCode'</b> đã tồn tại.";
            } else {
                $notifyContent = $notifyContent . "<br/> Mã <b>'$departmentCode'</b> đã tồn tại.";
            }
        }
        if (!empty($notifyContent)) {
            return $notifyContent;
        } else {
            $modelDepartment->updateInfo($departmentId, $departmentCode, $name);
        }
    }

    //activity
    public function updateStatus($departmentId)
    {
        $modelDepartment = new QcDepartment();
        $modelDepartment->updateStatus($departmentId, ($modelDepartment->checkActivityStatus($departmentId)) ? 0 : 1);
    }

    // delete
    public function deleteDepartment($departmentId)
    {
        if (!empty($departmentId)) {
            $modeldepartment = new QcDepartment();
            $modeldepartment->actionDelete($departmentId);
        }
    }
}
