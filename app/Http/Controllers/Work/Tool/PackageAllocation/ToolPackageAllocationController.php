<?php

namespace App\Http\Controllers\Work\Tool\PackageAllocation;


use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\Tool\QcTool;
//use Illuminate\Http\Request;
use App\Models\Ad3d\ToolPackageAllocation\QcToolPackageAllocation;
use App\Models\Ad3d\ToolPackageAllocationDetail\QcToolPackageAllocationDetail;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use File;
use Input;
use Request;

class ToolPackageAllocationController extends Controller
{
    public function index($monthFilter = 0, $yearFilter = 0)
    {
        $modelStaff = new QcStaff();
        $modelToolAllocation = new QcToolPackageAllocation();
        $modelToolAllocationDetail = new QcToolPackageAllocationDetail();
        $dataAccess = [
            'object' => 'toolPackageAllocation'
        ];
        $dataStaff = $modelStaff->loginStaffInfo();
        #thong tin lam viec tai 1 cty
        $dataCompanyStaffWork = $dataStaff->companyStaffWorkInfoActivity();
        $dataToolPackageAllocationDetail = $modelToolAllocationDetail->infoOfListToolAllocation($modelToolAllocation->listIdOfWork($dataCompanyStaffWork->workId()));
        return view('work.tool.package-allocation.list', compact('dataAccess', 'modelStaff', 'dataToolPackageAllocationDetail', 'monthFilter', 'yearFilter'));
    }

    #xac nhan da nhan do nghe
    public function getConfirmReceive($allocationId)
    {
        $modelToolAllocation = new QcToolPackageAllocation();
        $modelToolAllocation->receiveConfirm($allocationId);
    }

    # ------- -------- tra lai do nghe  -------- --------
    public function getReturn($allocationId, $selectedDetailId = null)
    {
        $modelStaff = new QcStaff();
        $modelToolAllocationDetail = new QcToolAllocationDetail();
        $dataAccess = [
            'object' => 'toolAllocation'
        ];
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
                $name_img = $detailId . "_" . $hFunction->getTimeCode() . '.' . $hFunction->getTypeImg($name_img);
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

    # xem anh ban giao
    public function viewImage($detailId)
    {
        $modelToolAllocationDetail = new QcToolAllocationDetail();
        $dataToolAllocationDetail = $modelToolAllocationDetail->getInfo($detailId);
        return view('work.tool.allocation.view-image', compact('modelStaff', 'dataToolAllocationDetail'));
    }

    # xem anh tra
    public function viewReturnImage($returnId)
    {
        $modelToolReturn = new QcToolReturn();
        $dataToolReturn = $modelToolReturn->getInfo($returnId);
        return view('work.tool.allocation.view-return-image', compact('modelStaff', 'dataToolReturn'));
    }
}
