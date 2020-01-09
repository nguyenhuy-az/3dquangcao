<?php

namespace App\Http\Controllers\Ad3d\Order\ProductTypePrice;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\Order\QcOrder;
use App\Models\Ad3d\Product\QcProduct;
use App\Models\Ad3d\ProductType\QcProductType;
use App\Models\Ad3d\ProductTypePrice\QcProductTypePrice;
use App\Models\Ad3d\Staff\QcStaff;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ad3d\WorkAllocation\QcWorkAllocation;
use Illuminate\Support\Facades\Session;
use File;
use Request;
use Input;

class ProductTypePriceController extends Controller
{
    public function index($companyFilterId = null, $nameFilter = null)
    {
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelProductType = new QcProductType();
        $modelProductTypePrice = new QcProductTypePrice();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataAccess = [
            'accessObject' => 'productTypePrice'
        ];
        if (empty($companyFilterId)) {
            $companyFilterId = $dataStaffLogin->companyId();
        }
        if ($dataStaffLogin->checkRootManage()) {
            $dataCompany = $modelCompany->getInfo();
        } else {
            $dataCompany = $modelCompany->selectInfo($dataStaffLogin->companyId())->get();
        }

        if (!empty($nameFilter)) {
            $listProductTypeId = $modelProductType->listIdActivityByName($nameFilter);
        } else {
            $listProductTypeId = $modelProductType->listIdActivity();
        }
        $dataProductTypePrice = $modelProductTypePrice->selectInfoActivityOfListProductTypeAndCompany($listProductTypeId, $companyFilterId)->paginate(30);
        return view('ad3d.order.product-type-price.list', compact('modelStaff', 'dataCompany', 'dataAccess', 'dataProductTypePrice', 'companyFilterId', 'nameFilter'));

    }

    # them bang gia
    public function getAdd()
    {
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelProductType = new QcProductType();
        $dataAccess = [
            'accessObject' => 'productTypePrice'
        ];
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataCompany = $modelCompany->getInfo($dataStaffLogin->companyId());
        $dataProductType = $modelProductType->getInfoActivityToCreatedPriceList($dataCompany->companyId());
        return view('ad3d.order.product-type-price.add', compact('modelStaff', 'dataCompany', 'dataAccess', 'dataProductType'));
    }

    public function postAdd()
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelProductTypePrice = new QcProductTypePrice();
        $cbCompany = Request::input('cbCompany');
        $txtProductType = Request::input('txtProductType');
        $txtPrice = Request::input('txtPrice');
        $txtNote = Request::input('txtNote');
        $selectStatus = false;
        if (count($txtPrice) > 0) {
            foreach ($txtPrice as $key => $value) {
                if ($value > 0) $selectStatus = true;
            }
        }
        if ($selectStatus) {
            foreach ($txtPrice as $key => $value) {
                if ($value > 0) { # có nhap bang gia
                    $price = $hFunction->convertCurrencyToInt($value);
                    $productTypeId = $txtProductType[$key]; # ma loai SP
                    $note = $txtNote[$key];  # ghi chu
                    $modelProductTypePrice->insert($price, $note, $productTypeId, $cbCompany, $modelStaff->loginStaffId());
                }
            }
        } else {
            Session::put('notifyAdd', "Bạn phải nhập giá cho loại SP");
        }
        return redirect()->back();

    }

    # cap nhat thong tin
    public function getEdit($priceId)
    {
        $modelProductTypePrice = new QcProductTypePrice();
        $dataProductTypePrice = $modelProductTypePrice->getInfo($priceId);
        return view('ad3d.order.product-type-price.edit', compact('dataProductTypePrice'));
    }

    public function postEdit($priceId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelProductTypePrice = new QcProductTypePrice();
        $txtNewPrice = Request::input('txtNewPrice');
        $txtPrice = $hFunction->convertCurrencyToInt($txtNewPrice);
        $txtNote = Request::input('txtNote');
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataOldProductTypePrice = $modelProductTypePrice->getInfo($priceId);
        if ($txtPrice > 0) { # co thay doi gia moi
            if ($modelProductTypePrice->insert($txtPrice, $txtNote, $dataOldProductTypePrice->typeId(), $dataOldProductTypePrice->companyId(), $dataStaffLogin->staffId())) {
                $modelProductTypePrice->disablePrice($priceId); # vo hieu bang gia cu
            }
        } else { # cap nhat ghi chu thong tin cu
            $modelProductTypePrice->updateNote($priceId, $txtNote);
        }
    }

    public function deletePrice($priceId)
    {
        $modelProductPrice = new QcProductTypePrice();
        $modelProductPrice->disablePrice($priceId);
    }


}
