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
    public function index($cbConfirmStatusFilter = 0)
    {
        $modelStaff = new QcStaff();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelToolReturn = new QcToolReturn();
        $dataAccess = [
            'object' => 'storeReturn',
            'subObjectLabel' => 'Trả đồ nghề'
        ];
        $dataStaff = $modelStaff->loginStaffInfo();
        # danh sach thong tin lam viec
        $listWorkId = $modelCompanyStaffWork->listIdOfCompany($dataStaff->companyId());
        # thong tin bao tra dung cu
        $dataToolReturn = $modelToolReturn->infoOfListWork($listWorkId, $cbConfirmStatusFilter);
        return view('work.store.return.list', compact('dataAccess', 'modelStaff', 'dataToolReturn', 'cbConfirmStatusFilter'));
    }

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
