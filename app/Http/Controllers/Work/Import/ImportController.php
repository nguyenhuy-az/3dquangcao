<?php

namespace App\Http\Controllers\Work\Import;

use App\Models\Ad3d\Import\QcImport;
use App\Models\Ad3d\ImportDetail\QcImportDetail;
use App\Models\Ad3d\ImportImage\QcImportImage;
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

class ImportController extends Controller
{
    public function index($dayFilter = null, $monthFilter = 0, $yearFilter = 0, $loginPayStatus = 3)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $dataStaff = $modelStaff->loginStaffInfo();
        $dataAccess = [
            'object' => 'import',
            'subObjectLabel' => 'Mua vật tư'
        ];
        $loginStaffId = $dataStaff->staffId();
        $dataStaff = $modelStaff->loginStaffInfo();
        $dateFilter = null;
        $currentMonth = $hFunction->currentMonth();
        $currentYear = $hFunction->currentYear();
        if ($dayFilter == 0 && $monthFilter == 0 && $yearFilter == 0) { //xem  trong tháng
            $dayFilter = 100;
            $monthFilter = date('m');
            $yearFilter = date('Y');
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        } elseif ($dayFilter == 100 && $monthFilter == 100 && $yearFilter > 100) { //xem tất cả các ngày trong tháng
            $dateFilter = date('Y', strtotime("1-1-$yearFilter"));
        } elseif ($dayFilter == 100 && $monthFilter > 0 && $monthFilter < 100 && $yearFilter > 100) { //xem tất cả các ngày trong tháng
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        } elseif ($dayFilter < 100 && $dayFilter > 0 && $monthFilter > 0 && $monthFilter < 100 && $yearFilter > 100) { //xem tất cả các ngày trong tháng
            $monthFilter = $currentMonth;
            $yearFilter = $currentYear;
            $dateFilter = date('Y-m-d', strtotime("$dayFilter-$currentMonth-$currentYear"));
        } elseif ($dayFilter == 100 && $monthFilter == 100 && $yearFilter == 100) { //xem tất cả
            $dateFilter = null;
        } else {
            $dateFilter = date('Y-m');
            $dayFilter = 100;
            $monthFilter = date('m');
            $yearFilter = date('Y');
        }
        $dataImport = $dataStaff->importInfoOfStaff($loginStaffId, $loginPayStatus, $dateFilter);
        return view('work.import.list', compact('dataAccess', 'modelStaff', 'dataImport', 'dayFilter', 'monthFilter', 'yearFilter', 'loginPayStatus'));

    }

    // --------- ----------- xem chi tiet -------------- -------------- -------
    public function viewImport($importId)
    {
        $modelStaff = new QcStaff();
        $modelImport = new QcImport();
        $dataAccess = [
            'object' => 'import',
            'subObjectLabel' => 'Chi tiết mua vật tư'
        ];
        $dataImport = $modelImport->getInfo($importId);
        return view('work.import.detail', compact('dataAccess', 'modelStaff', 'dataImport'));
    }

    // ---------- -------- them hoa don nhap moi  --------- ----------
    # lay form nhap vat tu
    public function getAdd()
    {
        $modelStaff = new QcStaff();
        $modelSupplies = new QcSupplies();
        $modelTool = new QcTool();
        $dataAccess = [
            'object' => 'Import',
            'subObjectLabel' => 'Thông tin mua vật tư'
        ];
        $dataStaff = $modelStaff->loginStaffInfo();
        $dataSupplies = $modelSupplies->getInfoActivity();
        $dataTool = $modelTool->getInfoOrderByName();
        return view('work.import.add', compact('dataAccess', 'modelStaff', 'dataStaff', 'dataSupplies', 'dataTool'));
    }

    # them anh hoa don
    public function getAddImage()
    {
        return view('work.import.add-image');
    }

    # ---------- ---------- kiem tra ten nhap --------- -----------
    public function checkImportName($name)
    {
        $hFunction = new \Hfunction();
        $modelSupplies = new QcSupplies();
        $modelTool = new QcTool();
        # thong tin vat tu - uu tien vat tu
        $dataSupplies = $modelSupplies->infoFromSuggestionName($name);
        if ($hFunction->checkCount($dataSupplies)) {
            $result = array(
                'status' => 'exist',
                'object' => 'tool',
                'content' => $dataSupplies
            );
        } else {
            # thong tin dung cu
            $dataTool = $modelTool->infoFromSuggestionName($name);
            if ($hFunction->checkCount($dataTool)) {
                $result = array(
                    'status' => 'exist',
                    'object' => 'supplies',
                    'content' => $dataTool
                );
            } else {
                $result = array(
                    'status' => 'notExist',
                    'object' => 'null',
                    'content' => "null"
                );
            }

        }
        die(json_encode($result));
    }

    # them mua vat tu
    public function getAddObject()
    {
        $modelStaff = new QcStaff();
        return view('work.import.add-object', compact('modelStaff'));
    }

    public function postAdd()
    {
        $modelStaff = new QcStaff();
        $hFunction = new \Hfunction();
        $modelImport = new QcImport();
        $modelImportDetail = new QcImportDetail();
        $modelImportImage = new QcImportImage();
        $modelSupplies = new QcSupplies();
        $modelTool = new QcTool();

        $staffLoginId = $modelStaff->loginStaffId();

        $txtImportDate = Request::input('txtImportDate');
        //hinh anh
        $txtImportImage = Request::file('txtImportImage');
        //thong tin mua
        $txtImportName = Request::input('txtImportName');
        $txtObjectAmount = Request::input('txtObjectAmount');
        $txtObjectUnit = Request::input('txtObjectUnit');
        $txtObjectMoney = Request::input('txtObjectMoney');
        $cbImportProduct = Request::input('cbImportProduct');
        $companyId = $modelStaff->companyId($staffLoginId);
        if ($modelImport->insert($txtImportDate, $companyId, null, $staffLoginId)) {
            $importId = $modelImport->insertGetId();
            // them anh hoa don
            if (count($txtImportImage) > 0) {
                $n_o = 0;
                foreach ($_FILES['txtImportImage']['name'] as $name => $value) {
                    $name_img = stripslashes($_FILES['txtImportImage']['name'][$name]);
                    if (!empty($name_img)) {
                        $n_o = $n_o + 1;
                        $name_img = $hFunction->getTimeCode() . "_$n_o." . $hFunction->getTypeImg($name_img);
                        $source_img = $_FILES['txtImportImage']['tmp_name'][$name];
                        if ($modelImportImage->uploadImage($source_img, $name_img, 500)) {
                            $modelImportImage->insert($name_img, $importId);
                        }
                    }
                }
            }
            // mua dụng cụ
            if ($txtImportName) {
                foreach ($txtImportName as $key => $importName) {
                    $name = $importName;
                    $amount = $txtObjectAmount[$key];
                    $unit = $txtObjectUnit[$key];
                    $money = $txtObjectMoney[$key];
                    $money = $hFunction->convertCurrencyToInt($money);
                    $productId = $cbImportProduct[$key];
                    $productId = ($productId == 0) ? null : $productId;
                    # gia / sp
                    $price = $money / $amount;
                    # lay thong tin vat tu mua the ten nhap
                    $dataSupplies = $modelSupplies->getInfoByName($name);
                    if ($hFunction->checkCount($dataSupplies)) { # vat tu dang co trong he thong
                        $suppliesId = $dataSupplies->suppliesId();
                        $modelImportDetail->insert($price, $amount, $money, $importId, null, $suppliesId, null, $unit, $productId);
                    } else {
                        # lay thong tin dung cu theo ten nhap
                        $dataTool = $modelTool->getInfoByName($name);
                        if ($hFunction->checkCount($dataTool)) { # dung cu dang co trong he thong
                            $toolId = $dataTool->toolId();
                            $modelImportDetail->insert($price, $amount, $money, $importId, $toolId, null, null, $unit, $productId);
                        } else {
                            # chua xac dinh la vat tu / dung cu
                            $modelImportDetail->insert($price, $amount, $money, $importId, null, null, $name, $unit, $productId);
                        }
                    }
                }
            }
            Session::put('notifyAddImport', 'Thêm thành công');
        }else{
            Session::put('notifyAddImport', 'Thêm thất bại, hãy thử lại');
        }
        return redirect()->back();
    }


    // xác nhận thanh toán
    public function getConfirmPay($importId)
    {
        $modelImport = new QcImport();
        $modelImport->updateConfirmPayOfImport($importId);
    }

    # huy thong tin nhap
    //xóa
    public function deleteImport($importId)
    {
        $modelImport = new QcImport();
        $modelImport->deleteImport($importId);
    }
}
