<?php

namespace App\Http\Controllers\Work\Store\Tool;


use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\CompanyStore\QcCompanyStore;
use App\Models\Ad3d\ImportImage\QcImportImage;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\Tool\QcTool;
//use Illuminate\Http\Request;
use App\Models\Ad3d\ToolReturn\QcToolReturn;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use File;
use Input;
use Request;

class CompanyStoreController extends Controller
{
    public function index($typeFilter = 2, $toolFilter = 0)
    {
        $modelCompany = new QcCompany();
        $modelStaff = new QcStaff();
        $modelTool = new QcTool();
        $modelCompanyStore = new QcCompanyStore();
        $dataAccess = [
            'object' => 'storeTool',
            'subObjectLabel' => 'Đồ nghề'
        ];
        $dataStaff = $modelStaff->loginStaffInfo();
        $companyLoginId = $dataStaff->companyId();
        # thong tin cty
        $dataCompany = $modelCompany->getInfo($dataStaff->companyId());
        if ($toolFilter == 0) { # lay tat ca ca dung
            $listToolSelectedId = $modelTool->listIdByType($typeFilter);
            $dataCompanyStore = $modelCompanyStore->getInfoOfListToolAndCompany($companyLoginId, $listToolSelectedId);
        } else {
            //$dataToolFilter = $modelTool->getInfo($toolFilter);
            //$typeFilter = $dataToolFilter->type();
            $dataCompanyStore = $modelCompanyStore->getInfoOfListToolAndCompany($companyLoginId, [$toolFilter]);
        }
        # lay danh sach cong cu cua he thong
        $dataTool = $modelTool->selectAllInfo($typeFilter)->get();
        //dd($dataCompanyStore);
        return view('work.store.tool.list', compact('dataAccess', 'modelStaff', 'dataCompanyStore', 'dataTool', 'typeFilter', 'toolFilter'));
    }

    # xem anh nhap kho
    public function viewImportImage($imageId)
    {
        $modelImportImage = new QcImportImage();
        $dataImportImage = $modelImportImage->getInfo($imageId);
        return view('work.store.tool.view-import-image', compact('modelStaff', 'dataImportImage'));
    }
    # xem anh bao tra
    public function viewReturnImage($returnId)
    {
        $modelToolReturn = new QcToolReturn();
        $dataToolReturn = $modelToolReturn->getInfo($returnId);
        return view('work.store.tool.view-return-image', compact('modelStaff', 'dataToolReturn'));
    }

}
