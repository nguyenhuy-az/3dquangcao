<?php

namespace App\Http\Controllers\Work\Tool\Allocation;


use App\Models\Ad3d\ImportImage\QcImportImage;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\Tool\QcTool;
use App\Models\Ad3d\ToolAllocation\QcToolAllocation;
use App\Models\Ad3d\ToolAllocationDetail\QcToolAllocationDetail;
//use Illuminate\Http\Request;
use App\Models\Ad3d\ToolReturn\QcToolReturn;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use File;
use Input;
use Request;

class ToolAllocationController extends Controller
{
    public function index($monthFilter = 0, $yearFilter = 0)
    {
        $modelStaff = new QcStaff();
        $modelTool = new QcTool();
        $modelToolAllocation = new QcToolAllocation();
        $modelToolAllocationDetail = new QcToolAllocationDetail();
        $dataAccess = [
            'object' => 'toolAllocation'
        ];
        $dataStaff = $modelStaff->loginStaffInfo();
        #thong tin lam viec tai 1 cty
        $dataCompanyStaffWork = $dataStaff->companyStaffWorkInfoActivity();
        $dataToolAllocationDetail = $modelToolAllocationDetail->infoOfListToolAllocation($modelToolAllocation->listIdOfWork($dataCompanyStaffWork->workId()));
        return view('work.tool.allocation.list', compact('dataAccess', 'modelStaff', 'dataToolAllocationDetail', 'monthFilter', 'yearFilter'));
    }

    #xac nhan da nhan do nghe
    public function getConfirmReceive($allocationId)
    {
        $modelToolAllocation = new QcToolAllocation();
        $modelToolAllocation->receiveConfirm($allocationId);
    }

    # ------- -------- tra lai do nghe  -------- --------
    public function getReturn($allocationId, $selectedDetailId = null)
    {
        //$modelCompanyStore = new QcCompanyStore();
        $modelStaff = new QcStaff();
        //$modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelToolAllocationDetail = new QcToolAllocationDetail();
        $dataAccess = [
            'object' => 'toolAllocation'
        ];
        //$dataStaffLogin = $modelStaff->loginStaffInfo();
        # danh sach ma kho
        //$listStoreId = $modelToolAllocationDetail->listStoreIdOfWork($modelCompanyStaffWork->workIdActivityOfStaff($dataStaffLogin->staffId()));
        # chi danh sach cong cu da ban giao trong kho
        //$dataCompanyStore = $modelCompanyStore->getInfoByListId($listStoreId);
        $dataToolAllocationDetail = $modelToolAllocationDetail->getInfoNotReturnOfAllocation($allocationId);
        return view('work.tool.allocation.return', compact('dataAccess', 'modelStaff', 'dataToolAllocationDetail', 'selectedDetailId'));
    }

    public function postReturn()
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelToolReturn = new QcToolReturn();
        $allocationDetail = Request::input('txtAllocationDetail');
        foreach ($allocationDetail as $detailId) {
            $txtReturnImage = Request::file('txtReturnImage_' . $detailId);
            if (!empty($txtReturnImage)) {
                $name_img = stripslashes($_FILES['txtReturnImage_' . $detailId]['name']);
                $name_img = $hFunction->getTimeCode() . '.' . $hFunction->getTypeImg($name_img);
                $source_img = $_FILES['txtReturnImage_' . $detailId]['tmp_name'];
                # up anh do nghe
                if ($modelToolReturn->uploadImage($source_img, $name_img, 500)) {
                    # chi tra khi co anh ban giao
                    $modelToolReturn->insert($detailId, $name_img);
                }
            }
        }
        return redirect()->route('qc.work.tool.allocation.get');

    }
    # xem anh nhap kho
    public function viewImportImage($imageId)
    {
        $modelImportImage = new QcImportImage();
        $dataImportImage = $modelImportImage->getInfo($imageId);
        return view('work.tool.allocation.view-import-image', compact('modelStaff', 'dataImportImage'));
    }
}
