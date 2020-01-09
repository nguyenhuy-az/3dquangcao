<?php

namespace App\Http\Controllers\Work\Pay\Import;

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
    public function index($loginDay = null, $loginMonth = null, $loginYear = null, $loginPayStatus = 3)
    {
        $modelStaff = new QcStaff();
        if ($modelStaff->checkLogin()) {
            $dataAccess = [
                'object' => 'payImport',
                'subObjectLabel' =>'Mua vật tư'
            ];
            $dataStaff = $modelStaff->loginStaffInfo();
            $loginMonth = (empty($loginMonth)) ? date('m') : $loginMonth;
            $loginYear = (empty($loginYear)) ? date('Y') : $loginYear;
            return view('work.pay.import.import', compact('dataAccess', 'modelStaff', 'dataStaff', 'loginDay', 'loginMonth', 'loginYear', 'loginPayStatus'));
        } else {
            return view('work.login');
        }

    }

    public function viewImport($importId)
    {
        $modelStaff = new QcStaff();
        $modelImport = new QcImport();
        $dataAccess = [
            'object' => 'payImport',
            'subObjectLabel' =>'Chi tiết mua vật tư'
        ];
        $dataImport = $modelImport->getInfo($importId);
        return view('work.pay.import.import-detail', compact('dataAccess', 'modelStaff', 'dataImport'));
    }

    public function getAdd()
    {
        $modelStaff = new QcStaff();
        $modelSupplies = new QcSupplies();
        $modelTool = new QcTool();
        if ($modelStaff->checkLogin()) {
            $dataAccess = [
                'object' => 'payImport',
                'subObjectLabel' =>'Thông tin mua vật tư'
            ];
            $dataStaff = $modelStaff->loginStaffInfo();
            $dataSupplies = $modelSupplies->getInfoActivity();
            $dataTool = $modelTool->getInfoOrderByName();
            return view('work.pay.import.add-import', compact('dataAccess', 'modelStaff', 'dataStaff', 'dataSupplies', 'dataTool'));
        } else {
            return view('work.login');
        }
    }

    public function getAddImage()
    {
        return view('work.pay.import.add-image');
    }

    # them mua vat tu
    public function getAddSupplies()
    {
        $modelStaff = new QcStaff();
        $modelSupplies = new QcSupplies();
        $dataSupplies = $modelSupplies->getInfoActivity();
        return view('work.pay.import.add-supplies', compact('modelStaff', 'dataAccess', 'dataSupplies'));
    }

    # them mua dung cu
    public function getAddTool()
    {
        $modelTool = new QcTool();
        $dataTool = $modelTool->getInfoOrderByName();
        return view('work.pay.import.add-tool', compact('dataAccess', 'dataTool'));
    }

    public function getAddSuppliesTool()
    {
        $modelStaff = new QcStaff();
        return view('work.pay.import.add-supplies-tool-new', compact('modelStaff'));
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

        $cbImportDay = Request::input('cbImportDay');
        $cbImportMonth = Request::input('cbImportMonth');
        $cbImportYear = Request::input('cbImportYear');

        //hình ảnh
        $txtImportImage = Request::file('txtImportImage');
        //vật tư
        $supplies = Request::input('cbImportSupplies');
        $txtSuppliesAmount = Request::input('txtSuppliesAmount');
        $txtSuppliesMoney = Request::input('txtSuppliesMoney');
        $cbSuppliesProduct = Request::input('cbSuppliesProduct');

        //Dụng cụ
        $tool = Request::input('cbImportTool');
        $txtToolAmount = Request::input('txtToolAmount');
        $txtToolMoney = Request::input('txtToolMoney');

        //Dụng cụ vật tư mới
        $txtSuppliesToolNew = Request::input('txtSuppliesToolNew');
        $txtSuppliesToolNewAmount = Request::input('txtSuppliesToolNewAmount');
        $txtSuppliesToolNewMoney = Request::input('txtSuppliesToolNewMoney');
        $cbSuppliesToolNewProduct = Request::input('cbSuppliesToolNewProduct');

        //$insertStatus = false;
        //$imageStatus = false;
        $suppliesStatus = false;
        $toolStatus = false;
        $newStatus = false;
        if (count($supplies) > 0) {
            foreach ($supplies as $key => $value) {
                if ($value > 0) $suppliesStatus = true;
            }
        }
        if (count($tool) > 0) {
            foreach ($tool as $key => $value) {
                if ($value > 0) $toolStatus = true;
            }
        }
        if (count($txtSuppliesToolNew) > 0) {
            foreach ($txtSuppliesToolNew as $key => $value) {
                if (count($value) > 0) $newStatus = true;
            }
        }

        if ($suppliesStatus || $toolStatus || $newStatus) {
            $companyId = $modelStaff->companyId($staffLoginId);
            $importDate = $hFunction->convertStringToDatetime("$cbImportMonth/$cbImportDay/$cbImportYear 00:00:00");
            if ($modelImport->insert($importDate, $companyId, null, $staffLoginId)) {
                $importId = $modelImport->insertGetId();
                //ảnh hóa đơn
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

                // mua vật tư
                if ($suppliesStatus) {
                    foreach ($supplies as $key => $value) {
                        $suppliesId = $value;
                        $suppliesAmount = $txtSuppliesAmount[$key];
                        $suppliesMoney = $hFunction->convertCurrencyToInt($txtSuppliesMoney[$key]);
                        $suppliesProductId = $cbSuppliesProduct[$key];
                        $suppliesProductId = ($suppliesProductId == 0) ? null : $suppliesProductId;
                        if ($suppliesId > 0) {
                            $modelImportDetail->insert($suppliesMoney / $suppliesAmount, $suppliesAmount, $suppliesMoney, $importId, null, $suppliesId, null, $suppliesProductId);
                        }
                    }
                }
                // mua dụng cụ
                if ($toolStatus) {
                    foreach ($tool as $key => $value) {
                        $toolId = $value;
                        $toolAmount = $txtToolAmount[$key];
                        $toolMoney = $hFunction->convertCurrencyToInt($txtToolMoney[$key]);
                        if ($toolId > 0) {
                            $modelImportDetail->insert($toolMoney / $toolAmount, $toolAmount, $toolMoney, $importId, $toolId, null, null, null);
                        }
                    }
                }
                // vật liệu mới
                if ($newStatus) {
                    foreach ($txtSuppliesToolNew as $key => $value) {
                        $name = $value;
                        $suppliesToolNewAmount = $txtSuppliesToolNewAmount[$key];
                        $suppliesToolNewMoney = $hFunction->convertCurrencyToInt($txtSuppliesToolNewMoney[$key]);
                        $suppliesToolNewProductId = $cbSuppliesToolNewProduct[$key];
                        $suppliesToolNewProductId = ($suppliesToolNewProductId == 0) ? null : $suppliesToolNewProductId;
                        if (count($name) > 0) {
                            $modelImportDetail->insert($suppliesToolNewMoney / $suppliesToolNewAmount, $suppliesToolNewAmount, $suppliesToolNewMoney, $importId, null, null, $name, $suppliesToolNewProductId);
                        }
                    }
                }
            };
            Session::put('notifyAddImport', 'Thêm thành công');
        } else {
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

    //xóa
    public function deleteImport($importId)
    {
        $modelImport = new QcImport();
        $modelImport->deleteImport($importId);
    }
}
