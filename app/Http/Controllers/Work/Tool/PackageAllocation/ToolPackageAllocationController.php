<?php

namespace App\Http\Controllers\Work\Tool\PackageAllocation;


use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\Tool\QcTool;
//use Illuminate\Http\Request;
use App\Models\Ad3d\ToolPackageAllocation\QcToolPackageAllocation;
use App\Models\Ad3d\ToolPackageAllocationDetail\QcToolPackageAllocationDetail;
use App\Models\Ad3d\ToolPackageAllocationReturn\QcToolPackageAllocationReturn;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use File;
use Input;
use Request;

class ToolPackageAllocationController extends Controller
{
    public function index()
    {
        $modelStaff = new QcStaff();
        $modelTool = new QcTool();
        $dataAccess = [
            'object' => 'toolPackageAllocation',
            'subObjectLabel' => 'Danh sách đồ nghề được giao'
        ];
        $dataStaff = $modelStaff->loginStaffInfo();
        #thong tin lam viec tai 1 cty
        $dataCompanyStaffWork = $dataStaff->companyStaffWorkInfoActivity();
        # thong tin ban giao tui do nghe
        $dataToolPackageAllocation = $dataCompanyStaffWork->toolAllocationActivityOfWork();
        # lay danh sach cong cu dung de cap phat cho nv
        $dataTool = $modelTool->getInfoPrivate();
        return view('work.tool.tool-package-allocation.list', compact('dataAccess', 'modelStaff', 'dataTool', 'dataToolPackageAllocation'));
    }

    # bao cao do nghe bi hu hoac mat
    public function getReport($detailId)
    {

    }

    public function postReport($detailId)
    {

    }

    #xac nhan da nhan do nghe
    /*public function getConfirmReceive($allocationId)
    {
        $modelToolAllocation = new QcToolPackageAllocation();
        $modelToolAllocation->receiveConfirm($allocationId);
    }*/

    # ------- -------- tra lai do nghe  -------- --------
    public function getReturn($allocationId, $selectedDetailId = null)
    {
        $modelStaff = new QcStaff();
        $modelToolPackageAllocationDetail = new QcToolPackageAllocationDetail();
        $dataAccess = [
            'object' => 'toolPackageAllocation'
        ];
        $dataToolAllocationDetail = $modelToolPackageAllocationDetail->getInfoNotReturnOfAllocation($allocationId);
        return view('work.tool.tool-package-allocation.return', compact('dataAccess', 'modelStaff', 'dataToolAllocationDetail', 'selectedDetailId'));
    }

    public function postReturn()
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelToolReturn = new QcToolPackageAllocationReturn();
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
        return redirect()->route('qc.work.tool.package_allocation.get');

    }

    # xem anh ban giao
    public function viewImage($detailId)
    {
        $modelToolPackageAllocationDetail = new QcToolPackageAllocationDetail();
        $dataToolPackageAllocationDetail = $modelToolPackageAllocationDetail->getInfo($detailId);
        return view('work.tool.tool-package-allocation.view-image', compact('modelStaff', 'dataToolPackageAllocationDetail'));
    }

    # xem anh tra
    public function viewReturnImage($returnId)
    {
        $modelToolPackageAllocationReturn = new QcToolPackageAllocationReturn();
        $dataToolPackageAllocationReturn = $modelToolPackageAllocationReturn->getInfo($returnId);
        return view('work.tool.tool-package-allocation.view-return-image', compact('modelStaff', 'dataToolPackageAllocationReturn'));
    }
}
