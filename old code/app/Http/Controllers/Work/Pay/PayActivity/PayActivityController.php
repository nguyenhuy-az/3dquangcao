<?php

namespace App\Http\Controllers\Work\Pay\PayActivity;

use App\Models\Ad3d\Import\QcImport;
use App\Models\Ad3d\ImportDetail\QcImportDetail;
use App\Models\Ad3d\ImportImage\QcImportImage;
use App\Models\Ad3d\PayActivityDetail\QcPayActivityDetail;
use App\Models\Ad3d\PayActivityList\QcPayActivityList;
use App\Models\Ad3d\Rule\QcRules;
use App\Models\Ad3d\Salary\QcSalary;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\Supplies\QcSupplies;
use App\Models\Ad3d\Tool\QcTool;
use App\Models\Ad3d\Work\QcWork;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use File;
use Input;
use Request;

class PayActivityController extends Controller
{
    public function index($loginDay = null, $loginMonth = null, $loginYear = null, $confirmStatus = 3)
    {
        $modelStaff = new QcStaff();
        if ($modelStaff->checkLogin()) {
            $dataAccess = [
                'object' => 'payActivity',
                'subObjectLabel' => 'Hoạt động cty'
            ];
            $dataStaff = $modelStaff->loginStaffInfo();
            $loginMonth = (empty($loginMonth)) ? date('m') : $loginMonth;
            $loginYear = (empty($loginYear)) ? date('Y') : $loginYear;

            return view('work.pay.pay-activity.index', compact('dataAccess', 'modelStaff', 'dataStaff', 'loginDay', 'loginMonth', 'loginYear', 'confirmStatus'));
        } else {
            return view('work.login');
        }

    }

    /*public function viewImport($importId)
    {
        $modelStaff = new QcStaff();
        $modelImport = new QcImport();
        $dataAccess = [
            'object' => 'payImport',
            'subObjectLabel' =>'Chi tiết mua vật tư'
        ];
        $dataImport = $modelImport->getInfo($importId);
        return view('work.pay.import.import-detail', compact('dataAccess', 'modelStaff', 'dataImport'));
    }*/

    public function getAdd()
    {
        $modelStaff = new QcStaff();
        $modelPayActivityList = new QcPayActivityList();
        if ($modelStaff->checkLogin()) {
            $dataAccess = [
                'object' => 'payActivity',
                'subObjectLabel' => 'Thông tin chi hoạt động'
            ];
            $dataPayActivityList = $modelPayActivityList->selectInfo()->get();
            return view('work.pay.pay-activity.add', compact('dataAccess', 'modelStaff', 'dataPayActivityList', 'dataStaff'));
        } else {
            return view('work.login');
        }
    }

    public function postAdd()
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelPayActivityDetail = new QcPayActivityDetail();
        $dataLoginStaff = $modelStaff->loginStaffInfo();
        $cbPayActivityList = Request::input('cbPayActivityList');
        $cbDay = Request::input('cbPayActivityDay');
        $cbMonth = Request::input('cbPayActivityMonth');
        $cbYear = Request::input('cbPayActivityYear');
        $txtMoney = Request::input('txtMoney');
        $txtMoney = $hFunction->convertCurrencyToInt($txtMoney);
        $txtNote = Request::input('txtNote');
        $txtPayImage = Request::file('txtPayImage');
        $staffId = $modelStaff->loginStaffId();
        $datePay = $hFunction->convertStringToDatetime("$cbMonth/$cbDay/$cbYear 00:00:00");
        $cbDay = ($cbDay < 10) ? "0$cbDay" : $cbDay;
        $cbMonth = ($cbMonth < 10) ? "0$cbMonth" : $cbMonth;
        if ($hFunction->checkValidDate("$cbYear-$cbMonth-$cbDay")) {
            if (count($txtPayImage) > 0) {
                $name_img = stripslashes($_FILES['txtPayImage']['name']);
                $name_img = $hFunction->getTimeCode() . '.' . $hFunction->getTypeImg($name_img);
                $source_img = $_FILES['txtPayImage']['tmp_name'];
                $modelPayActivityDetail->uploadImage($source_img, $name_img);
            } else {
                $name_img = null;
            }
            if ($modelPayActivityDetail->insert($txtMoney, $datePay, $name_img, $txtNote, $cbPayActivityList, $staffId, $dataLoginStaff->companyId())) {
                return Session::put('notifyAddPayActivity', 'Thêm thành công, chọn thông tin và tiếp tục');
            } else {
                return Session::put('notifyAddPayActivity', 'Thêm thất bại, hãy thử lại');
            }
        } else {
            return Session::put('notifyAddPayActivity', "Ngày '$cbYear-$cbMonth-$cbDay' không hộp lệ ");
        }
    }

    // xác nhận thanh toán
    /*public function getConfirmPay($importId)
    {
        $modelImport = new QcImport();
        $modelImport->updateConfirmPayOfImport($importId);
    }*/

    //xóa
    public function deletePayActivity($payId)
    {
        $modelPayActivityDetail = new QcPayActivityDetail();
        $modelPayActivityDetail->deletePay($payId);
    }
}
