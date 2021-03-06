<?php

namespace App\Http\Controllers\Work\Salary\Salary;

use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\Salary\QcSalary;
use App\Models\Ad3d\SalaryPay\QcSalaryPay;
use App\Models\Ad3d\Staff\QcStaff;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use File;
use Input;
use Request;

class SalaryController extends Controller
{
    //bảng lương
    public function index()
    {
        $modelStaff = new QcStaff();
        $modelSalary = new QcSalary();
        $dataAccess = [
            'object' => 'salary'
        ];
        $dataStaff = $modelStaff->loginStaffInfo();
        $loginStaffId = $dataStaff->staffId();
        $listWorkId = $modelStaff->allListWorkId($loginStaffId);
        $dataSalary = $modelSalary->infoOfListWorkId($listWorkId);
        return view('work..salary.salary.list', compact('dataAccess', 'modelStaff', 'dataStaff', 'dataSalary'));
    }

    //thanh toán lương
    public function salaryDetail($salaryId = null)
    {
        $hFunction =new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelSalary = new QcSalary();
        $dataAccess = [
            'object' => 'salary',
            'subObjectLabel' => 'Chi tiết lương'
        ];
        if ($hFunction->checkEmpty($salaryId)) {
            return redirect()->route('qc.work.salary');
        } else {
            $dataStaff = $modelStaff->loginStaffInfo();
            if ($hFunction->checkCount($dataStaff)) {
                $dataSalary = $modelSalary->getInfo($salaryId);
                return view('work.salary.salary.detail', compact('dataAccess', 'modelCompanyStaffWork', 'modelStaff', 'dataStaff', 'dataSalary'));
            } else {
                $loginNotify = 'Nhập mã không đúng';
                return view('work.login-code', compact('loginHref', 'loginNotify'));
            }
        }
    }

    // xac nhan da nhan luong
    public function getConfirm($salaryId)
    {
        $modelSalaryPay = new QcSalaryPay();
        $dataSalaryPay = $modelSalaryPay->getInfoUnConfirmOfSalary($salaryId);
        return view('work.salary.salary.confirm', compact('dataSalaryPay', 'salaryId'));
    }

    public function postConfirm($salaryId)
    {
        $modelSalaryPay = new QcSalaryPay();
        if (!$modelSalaryPay->confirmReceiveOfSalary($salaryId)) {
            return "Tính năng đang bảo trì";
        }
    }


}
