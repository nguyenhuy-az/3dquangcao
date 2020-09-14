<?php

namespace App\Http\Controllers\Ad3d\System\DepartmentWork;

use App\Models\Ad3d\Department\QcDepartment;
use App\Models\Ad3d\DepartmentWork\QcDepartmentWork;
use App\Models\Ad3d\Staff\QcStaff;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Input;
use File;
use DB;
use Request;

class DepartmentWorkController extends Controller
{
    public function index($departmentSelectedId = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelDepartment = new QcDepartment();
        $dataDepartmentWork = null;
        $dataAccess = [
            'accessObject' => 'departmentWork'
        ];
        if (empty($departmentSelectedId)) {
            # lay mac dinh la bo phan thi cong
            $dataDepartmentSelected = $modelDepartment->getInfo($modelDepartment->constructionDepartmentId());
        } else {
            $dataDepartmentSelected = $modelDepartment->getInfo($departmentSelectedId);
        }
        # danh sach bo phan cua he thong
        $dataDepartmentAll = $modelDepartment->selectInfoAllActivity()->get();
        if($hFunction->checkCount($dataDepartmentSelected)) $dataDepartmentWork = $dataDepartmentSelected->departmentWorkGetInfo();
        return view('ad3d.system.department-work.list', compact('modelStaff', 'dataDepartmentAll', 'dataDepartmentSelected', 'dataDepartmentWork', 'dataAccess'));
    }

    /*public function view($departmentId)
    {
        $modelStaff = new QcStaff();
        $modelDepartment = new QcDepartment();
        if (!empty($departmentId)) {
            $dataDepartment = $modelDepartment->getInfo($departmentId);
            return view('ad3d.system.department.view', compact('modelStaff','dataDepartment'));
        }
    }*/

    //add
    /*public function getAdd()
    {
        $modelStaff = new QcStaff();
        $dataAccess = [
            'accessObject' => 'department'
        ];
        return view('ad3d.system.department.add', compact('modelStaff','dataAccess'));
    }*/

    public function postAdd()
    {
        $modelDepartmentWork = new QcDepartmentWork();
        $departmentId = Request::input('cbDepartment');
        $name = Request::input('txtName');
        $txtDepartment = Request::input('txtDescription');
        if (empty($name)) {
            Session::put('notifyAdd', "Bạn phải nhập tên công việc");
        } else {
            // kiem tra ten co ton tai hay khong
            if ($modelDepartmentWork->existName($name)) {
                Session::put('notifyAdd', "Thêm thất bại <b>'$name'</b> đã tồn tại.");
            } else {
                if ($modelDepartmentWork->insert($name, $txtDepartment, $departmentId)) {
                    Session::put('notifyAdd', 'Thêm thành công, Nhập thông tin để tiếp tục');
                } else {
                    Session::put('notifyAdd', 'Thêm thất bại, Nhập thông tin để thử lại.');
                }
            }
        }
        return redirect()->back();
    }

    //sua thong tin
    public function getEdit($workId)
    {
        $modelStaff = new QcStaff();
        $modelDepartmentWork = new QcDepartmentWork();
        $dataDepartmentWork = $modelDepartmentWork->getInfo($workId);
        return view('ad3d.system.department-work.edit', compact('modelStaff', 'dataDepartmentWork'));
    }

    public function postEdit($workId)
    {
        $modelDepartmentWork = new QcDepartmentWork();
        $name = Request::input('txtName');
        $txtDescription = Request::input('txtDescription');

        $notifyContent = null;
        if ($modelDepartmentWork->existEditName($workId, $name)) {
            $notifyContent = "Tên <b>'$name'</b> đã tồn tại.";
        }
        if (!empty($notifyContent)) {
            return $notifyContent;
        } else {
            $modelDepartmentWork->updateInfo($workId, $name, $txtDescription);
        }
    }



    # xoa thong tin
    public function deleteInfo($workId)
    {
        if (!empty($workId)) {
            $modelDepartmentWork = new QcDepartmentWork();
            $modelDepartmentWork->deleteInfo($workId);
        }
    }
}
