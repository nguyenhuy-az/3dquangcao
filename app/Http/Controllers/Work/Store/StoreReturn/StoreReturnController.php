<?php

namespace App\Http\Controllers\Work\Store\StoreReturn;


use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\CompanyStore\QcCompanyStore;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\Tool\QcTool;
use App\Models\Ad3d\ToolAllocation\QcToolAllocation;
use App\Models\Ad3d\ToolAllocationDetail\QcToolAllocationDetail;
//use Illuminate\Http\Request;
use App\Models\Ad3d\ToolReturn\QcToolReturn;
use App\Models\Ad3d\ToolReturnConfirm\QcToolReturnConfirm;
use App\Models\Ad3d\ToolReturnDetail\QcToolReturnDetail;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use File;
use Input;
use Request;

class StoreReturnController extends Controller
{
    public function index($cbConfirmStatusFilter = 100, $staffFilterId = 0)
    {
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelToolAllocation = new QcToolAllocation();
        $modelToolAllocationDetail = new QcToolAllocationDetail();
        $modelToolReturn = new QcToolReturn();
        $dataAccess = [
            'object' => 'storeReturn',
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
        # danh sach bo do nghe
        $listAllocationId = $modelToolAllocation->listIdOfListWork($listWorkId);
        # chi giao do nghe
        $listDetailId = $modelToolAllocationDetail->listIdOfListAllocationId($listAllocationId);
        $dataToolReturn = $modelToolReturn->infoOfListDetail($listDetailId, $cbConfirmStatusFilter);
        # danh sach NV
        $dataListStaff = $modelCompany->staffInfoActivityOfListCompanyId($searchCompanyFilterId);
        return view('work.store.return.list', compact('dataAccess', 'modelStaff', 'dataListStaff', 'dataToolReturn', 'staffFilterId', 'cbConfirmStatusFilter'));
    }

    #-------------- ------------- Xac nhan ban  giao ------------------ -------------
    public function getConfirm($allocationId)
    {
        $modelStaff = new QcStaff();
        $modelToolAllocation = new QcToolAllocation();
        $dataToolAllocation = $modelToolAllocation->getInfo($allocationId);
        return view('work.store.return.confirm', compact('modelStaff', 'dataToolAllocation'));
    }

    public function postConfirm($allocationId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelToolReturn = new QcToolReturn();//txtToolReturn
        $confirmReturnId = Request::input('txtToolReturn');
        $acceptReturnId = Request::input('txtToolReturnAccept');
        foreach ($confirmReturnId as $returnId) {
            $acceptStatus = 0;
            if (!empty($acceptReturnId)) {
                if ($hFunction->checkInArray($returnId, $acceptReturnId)) $acceptStatus = 1;
            }
            $modelToolReturn->confirmReturn($returnId, $acceptStatus, $modelStaff->loginStaffId());
        }
    }
}
