<?php

namespace App\Http\Controllers\Work\Store\ToolPackageAllocationReturn;


use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\Staff\QcStaff;
//use Illuminate\Http\Request;
use App\Models\Ad3d\ToolPackageAllocation\QcToolPackageAllocation;
use App\Models\Ad3d\ToolPackageAllocationDetail\QcToolPackageAllocationDetail;
use App\Models\Ad3d\ToolPackageAllocationReturn\QcToolPackageAllocationReturn;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use File;
use Input;
use Request;

class ToolPackageAllocationReturnController extends Controller
{
    public function index($cbConfirmStatusFilter = 100, $staffFilterId = 0)
    {
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelToolPackageAllocation = new QcToolPackageAllocation();
        $modelToolPackageAllocationDetail = new QcToolPackageAllocationDetail();
        $modelToolPackageAllocationReturn = new QcToolPackageAllocationReturn();
        $dataAccess = [
            'object' => 'storeToolPackageAllocationReturn',
            'subObjectLabel' => 'Trả đồ nghề'
        ];
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $searchCompanyFilterId = [$dataStaffLogin->companyId()];
        if ($staffFilterId > 0) {
            $listStaffId = [$staffFilterId];
        } else {
            $listStaffId = $modelStaff->listIdOfListCompany($searchCompanyFilterId);
        }
        # danh sach thong tin lam viec
        $listWorkId = $modelCompanyStaffWork->listIdOfListStaffId($listStaffId);
        # thong tin bao tra dung cu
        //$dataToolReturn = $modelToolReturn->infoOfListWork($listWorkId, $cbConfirmStatusFilter);
        # danh sach ban giao tui do nghe
        $listAllocationId = $modelToolPackageAllocation->listIdOfListWork($listWorkId);
        # chi tiet giao do nghe
        $listDetailId = $modelToolPackageAllocationDetail->listIdOfListAllocationId($listAllocationId);
        $dataToolPackageAllocationReturn = $modelToolPackageAllocationReturn->infoOfListDetail($listDetailId, $cbConfirmStatusFilter);
        # danh sach NV
        $dataListStaff = $modelCompany->staffInfoActivityOfListCompanyId($searchCompanyFilterId);
        return view('work.store.tool-package-allocation-return.list', compact('dataAccess', 'modelStaff', 'dataListStaff', 'dataToolPackageAllocationReturn', 'staffFilterId', 'cbConfirmStatusFilter'));
    }

    #-------------- ------------- Xac nhan ban  giao ------------------ -------------
    public function getConfirm($allocationId)
    {
        $modelStaff = new QcStaff();
        $modelToolAllocation = new QcToolPackageAllocation();
        $dataToolPackageAllocation = $modelToolAllocation->getInfo($allocationId);
        return view('work.store.tool-package-allocation-return.confirm', compact('modelStaff', 'dataToolPackageAllocation'));
    }

    public function postConfirm()
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelToolReturn = new QcToolPackageAllocationReturn();
        $confirmReturnId = Request::input('txtToolPackageAllocationReturn');
        $acceptReturnId = Request::input('txtToolPackageAllocationReturnAccept');
        foreach ($confirmReturnId as $returnId) {
            $acceptStatus = 0;
            if (!empty($acceptReturnId)) {
                if ($hFunction->checkInArray($returnId, $acceptReturnId)) $acceptStatus = 1;
            }
            $modelToolReturn->confirmReturn($returnId, $acceptStatus, $modelStaff->loginStaffId());
        }
    }
    # xem anh nhap kho
    public function viewDetailImage($detailId)
    {
        $modelToolAllocationDetail = new QcToolPackageAllocationDetail();
        $dataToolAllocationDetail = $modelToolAllocationDetail->getInfo($detailId);
        return view('work.store.tool-package-allocation-return.view-detail-image', compact('modelStaff', 'dataToolAllocationDetail'));
    }
    # xem anh bao tra
    public function viewReturnImage($returnId)
    {
        $modelToolReturn = new QcToolPackageAllocationReturn();
        $dataToolPackageAllocationReturn = $modelToolReturn->getInfo($returnId);
        return view('work.store.tool-package-allocation-return.view-return-image', compact('modelStaff', 'dataToolPackageAllocationReturn'));
    }
}
