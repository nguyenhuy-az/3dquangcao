<?php

namespace App\Http\Controllers\Work\Salary\Salary;

use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\Rule\QcRules;
use App\Models\Ad3d\Salary\QcSalary;
use App\Models\Ad3d\SalaryBeforePayRequest\QcSalaryBeforePayRequest;
use App\Models\Ad3d\SalaryPay\QcSalaryPay;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\StaffWorkSalary\QcStaffWorkSalary;
use App\Models\Ad3d\Work\QcWork;
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
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelWork = new QcWork();
        $modelSalary = new QcSalary();
        $dataAccess = [
            'object' => 'salary'
        ];
        if (!$modelStaff->checkLogin()) {
            return redirect()->route('qc.work.login.get');
        } else {
            $dataStaff = $modelStaff->loginStaffInfo();
            $loginStaffId = $dataStaff->staffId();
            $oldListWorkId = $modelWork->listIdOfListStaffId([$loginStaffId])->toArray();//phien ban cu
            $listCompanyStaffWorkId = $modelCompanyStaffWork->listIdOfStaff($loginStaffId);
            $newListWorkId = $modelWork->listIdOfListCompanyStaffWork($listCompanyStaffWorkId)->toArray();//phien ban moi
            //echo "$oldListWorkId ===== $newListWorkId";
            $dataSalary = $modelSalary->infoOfListWorkId(array_merge($oldListWorkId, $newListWorkId));
            return view('work..salary.salary.list', compact('dataAccess', 'modelStaff', 'dataStaff', 'dataSalary'));
        }
    }

    //thanh toán lương
    public function salaryDetail($salaryId = null)
    {
        $modelStaff = new QcStaff();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelSalary = new QcSalary();

        if (empty($salaryId)) {
            return redirect()->route('qc.work.salary');
        } else {
            $dataAccess = [
                'object' => 'salary',
                'subObjectLabel' => 'Chi tiết lương'
            ];
            $dataStaff = $modelStaff->loginStaffInfo();
            if (count($dataStaff) > 0) {
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
