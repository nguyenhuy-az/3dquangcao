<?php

namespace App\Http\Controllers\Work\Recruitment\Register;

//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ad3d\Department\QcDepartment;
use Illuminate\Support\Facades\Session;
use Input;
use File;
use DB;
use Request;

class RegisterController extends Controller
{
    public function index()
    {
        $hFunction = new \Hfunction();
        $dataAccess = [
            'object' => null
        ];

    }

    # lay form dang ky
    public function getAdd($phoneNumber, $departmentSelectedId=null)
    {
        $hFunction = new \Hfunction();
        $modelDepartment = new QcDepartment();
        $dataDepartmentWork =null;
        if (empty($departmentSelectedId)) {
            # lay mac dinh la bo phan thi cong
            $dataDepartmentSelected = null;// $modelDepartment->getInfo($modelDepartment->constructionDepartmentId());
        } else {
            $dataDepartmentSelected = $modelDepartment->getInfo($departmentSelectedId);
        }
        # danh sach bo phan cua he thong
        $dataDepartment = $modelDepartment->selectInfoAllActivity()->get();
        if ($hFunction->checkCount($dataDepartmentSelected)) $dataDepartmentWork = $dataDepartmentSelected->departmentWorkGetInfo();
        return view('work.recruitment.register.add', compact('dataDepartment','dataDepartmentSelected', 'dataDepartmentWork', 'phoneNumber'));
    }

    public function postAdd()
    {
        $dataAccess = [
            'object' => null
        ];

    }
}
