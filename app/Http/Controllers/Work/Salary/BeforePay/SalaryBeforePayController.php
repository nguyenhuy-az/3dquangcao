<?php

namespace App\Http\Controllers\Work\Salary\BeforePay;

use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\Rule\QcRules;
use App\Models\Ad3d\Salary\QcSalary;
use App\Models\Ad3d\SalaryBeforePay\QcSalaryBeforePay;
use App\Models\Ad3d\SalaryBeforePayRequest\QcSalaryBeforePayRequest;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\StaffWorkSalary\QcStaffWorkSalary;
use App\Models\Ad3d\Work\QcWork;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use File;
use Input;
use Request;

class SalaryBeforePayController extends Controller
{
    //ứng lương
    public function index($monthFilter = null, $yearFilter = null)
    {
        $modelStaff = new QcStaff();
        $modelWork = new QcWork();
        $modelStaffWorkSalary = new QcStaffWorkSalary();
        $dataStaff = $modelStaff->loginStaffInfo();
        $dataAccess = [
            'object' => 'beforePay',
            'subObjectLabel' => 'Ứng lương'
        ];
        if (count($dataStaff) > 0) {
            if (count($dataStaff) > 0) {
                if ($monthFilter == null && $yearFilter == null) { //xem tất cả các ngày trong tháng
                    $dateFilter = date('Y-m');
                    $monthFilter = date('m');
                    $yearFilter = date('Y');
                } else {
                    $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
                }
                $dataWork = $modelWork->firstInfoOfStaff($dataStaff->staffId(), $dateFilter);
                return view('work.salary.before-pay.list', compact('dataAccess', 'modelStaff', 'modelStaffWorkSalary', 'dataWork', 'monthFilter', 'yearFilter'));
            }
        } else {
            return redirect()->route('qc.work.login.get');
        }
    }

    //đề suất ứng
    public function getBeforePayRequest()
    {
        $modelStaff = new QcStaff();
        $modelWork = new QcWork();
        if (!$modelStaff->checkLogin()) {
            return redirect()->route('qc.work.login.get');
        } else {
            $dataStaff = $modelStaff->loginStaffInfo();
            if (count($dataStaff) > 0) {
                $dataWork = $modelWork->infoActivityOfStaff($dataStaff->staffId(), null);
                return view('work.salary.before-pay.request', compact('dataAccess', 'modelStaff', 'dataWork'));
            }
        }

    }

    public function postBeforePayRequest()
    {
        $modelStaff = new QcStaff();
        $modelSalaryBeforePayRequest = new QcSalaryBeforePayRequest();
        $workId = Request::input('txtWork');
        $txtMoneyRequest = Request::input('txtMoneyRequest');
        $modelSalaryBeforePayRequest->insert($txtMoneyRequest, $workId);

    }

    # xac nhan dan tien ung
    public function confirmReceiveBeforePay($payId)
    {
        $modelSalaryBeforePay = new QcSalaryBeforePay();
        $modelSalaryBeforePay->updateConfirmStatus($payId);
    }

    # huy y/c ung luong
    public function deleteBeforePayRequest($requestId)
    {
        $modelSalaryBeforePayRequest = new QcSalaryBeforePayRequest();
        $modelSalaryBeforePayRequest->deleteInfo($requestId);
    }
}
