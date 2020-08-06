<?php

namespace App\Http\Controllers\Work\Store\Tool;


use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\CompanyStore\QcCompanyStore;
use App\Models\Ad3d\ImportImage\QcImportImage;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\Tool\QcTool;
//use Illuminate\Http\Request;
use App\Models\Ad3d\ToolAllocation\QcToolAllocation;
use App\Models\Ad3d\ToolAllocationDetail\QcToolAllocationDetail;
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
        //$dataCompany = $modelCompany->getInfo($dataStaff->companyId());
        if ($toolFilter == 0) { # lay tat ca ca dung
            $listToolSelectedId = $modelTool->listIdByType($typeFilter);
            $dataCompanyStore = $modelCompanyStore->getInfoOfListToolAndCompany($companyLoginId, $listToolSelectedId);
        } else {
            $dataCompanyStore = $modelCompanyStore->getInfoOfListToolAndCompany($companyLoginId, [$toolFilter]);
        }
        # lay danh sach cong cu cua he thong
        $dataTool = $modelTool->selectAllInfo($typeFilter)->get();
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

    #------- --------- giao do nghe theo bo ------- ---------
    public function getAddAllocationList($selectedCompanyStaffWorkId = 0)
    {
        $modelStaff = new QcStaff();
        $modelTool = new QcTool();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $dataCompanyStaffWorkLogin = $modelStaff->loginCompanyStaffWork();
        $companyId = $dataCompanyStaffWorkLogin->companyId();
        if (empty($staffId)) {
            $dataStaffSelected = null;
        } else {

        }
        # thong tin nguoi nha
        if ($selectedCompanyStaffWorkId == 0) {
            $selectCompanyStaffWork = null;
        } else {
            $selectCompanyStaffWork = $modelCompanyStaffWork->getInfo($selectedCompanyStaffWorkId);
        }
        # lay danh sach lam viec cua bo phan thi cong
        $dataCompanyStaffWork = $modelCompanyStaffWork->infoAllActivityConstructionOfCompany($companyId);

        # lay danh sach do nghe de cap phat
        $dataPrivateTool = $modelTool->selectAllInfo($modelTool->privateType())->get();
        return view('work.store.tool.allocation-list-add', compact('modelStaff', 'dataCompanyStaffWork', 'dataPrivateTool', 'selectCompanyStaffWork'));
    }

    public function postAddAllocationList()
    {
        $hFunction = new \Hfunction();
        //$modelCompanyStore = new QcCompanyStore();
        $modelToolAllocation = new QcToolAllocation();
        $modelToolAllocationDetail = new QcToolAllocationDetail();
        $companyStaffWorkId = Request::input('cbCompanyStaffWork');
        $listIdCompanyStore = Request::input('txtCompanyStore');
        $dataToolAllocation = $modelToolAllocation->infoActivityOfWork($companyStaffWorkId);
        # ton tai bo do nghe
        if ($hFunction->checkCount($dataToolAllocation)) {
            $allocationId = $dataToolAllocation->allocationId();
            foreach ($listIdCompanyStore as $storeId) {
                $modelToolAllocationDetail->insert($allocationId, $storeId);
            }
        } else {
            # tao bo do nghe
            if ($modelToolAllocation->insert($hFunction->carbonNow(), null, $companyStaffWorkId)) {
                $newAllocationId = $modelToolAllocation->insertGetId();
                foreach ($listIdCompanyStore as $storeId) {
                    $modelToolAllocationDetail->insert($newAllocationId, $storeId);
                }
            }
        }
    }
}
