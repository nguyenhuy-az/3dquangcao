<?php

namespace App\Http\Controllers\Work\ProductTypePrice;

use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\ProductType\QcProductType;
use App\Models\Ad3d\ProductTypePrice\QcProductTypePrice;
use App\Models\Ad3d\Rule\QcRules;
use App\Models\Ad3d\Salary\QcSalary;
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

class ProductTypePriceController extends Controller
{
    //bang gia
    public function index($nameFilter=null)
    {
        $modelStaff = new QcStaff();
        $modelProductType = new QcProductType();
        $modelProductTypePrice = new QcProductTypePrice();
        $dataAccess = [
            'object' => 'productTypePrice'
        ];
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        if (count($dataStaffLogin)> 0) {
            if (!empty($nameFilter)) {
                $listProductTypeId = $modelProductType->listIdActivityByName($nameFilter);
            } else {
                $listProductTypeId = $modelProductType->listIdActivity();
            }
            $dataProductTypePrice = $modelProductTypePrice->selectInfoActivityOfListProductTypeAndCompany($listProductTypeId, $dataStaffLogin->companyId())->get();
            //$dataProductTypePrice = $modelProductTypePrice->selectInfoActivityOfCompany($dataStaffLogin->companyId())->get();
            return view('work.product-type-price.index', compact('dataAccess', 'modelStaff', 'dataStaffLogin','dataProductTypePrice','nameFilter'));
        } else {
            return redirect()->route('qc.work.login.get');
        }
    }
}
