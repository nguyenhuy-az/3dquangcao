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
    public function index($cbConfirmStatusFilter = 100,$staffFilterId = 0)
    {
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
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
        $dataToolReturn = $modelToolReturn->infoOfListWork($listWorkId, $cbConfirmStatusFilter);
        # danh sach NV
        $dataListStaff = $modelCompany->staffInfoActivityOfListCompanyId($searchCompanyFilterId);
        return view('work.store.return.list', compact('dataAccess', 'modelStaff','dataListStaff', 'dataToolReturn','staffFilterId', 'cbConfirmStatusFilter'));
    }

    public function getView($returnId)
    {
        $modelStaff = new QcStaff();
        $modelToolReturn = new QcToolReturn();
        $dataToolReturn = $modelToolReturn->getInfo($returnId);
        return view('work.store.return.view', compact('modelStaff', 'dataToolReturn'));
    }

    #-------------- ------------- Xac nhan ban  giao ------------------ -------------
    public function getConfirm($returnId)
    {
        $modelStaff = new QcStaff();
        $modelToolReturn = new QcToolReturn();
        $dataToolReturn = $modelToolReturn->getInfo($returnId);
        return view('work.store.return.confirm', compact('modelStaff', 'dataToolReturn'));
    }

    public function postConfirm($returnId)
    {
        $hFunction = new \Hfunction();
        $modelToolReturn = new QcToolReturn();
        $modelToolReturnDetail = new QcToolReturnDetail();
        $modelToolReturnConfirm = new QcToolReturnConfirm();
        $dataToolReturnDetail = $modelToolReturnDetail->getInfoOfReturn($returnId);
        if ($modelToolReturn->updateConfirm($returnId)) {
            if ($hFunction->checkCount($dataToolReturnDetail)) {
                foreach ($dataToolReturnDetail as $toolReturnDetail) {
                    $detailId = $toolReturnDetail->detailId();
                    $storeId = $toolReturnDetail->storeId();
                    # so luong bao tra
                    $returnAmount = $toolReturnDetail->amount();
                    # so luong duoc xac nhan
                    $confirmAmount = Request::input('txtReturnDetailAmount_' . $detailId);
                    # quan ly nguoi duyet nhap SL lon hon SL bao tra
                    $confirmAmount = ($confirmAmount > $returnAmount) ? $returnAmount : $confirmAmount;
                    $modelToolReturnConfirm->insert($confirmAmount, $storeId, $returnId);
                }
            }
        }
    }
}
