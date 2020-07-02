<?php

namespace App\Http\Controllers\Work\Tool;


use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\CompanyStore\QcCompanyStore;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\Tool\QcTool;
use App\Models\Ad3d\ToolAllocation\QcToolAllocation;
use App\Models\Ad3d\ToolAllocationDetail\QcToolAllocationDetail;
//use Illuminate\Http\Request;
use App\Models\Ad3d\ToolReturn\QcToolReturn;
use App\Models\Ad3d\ToolReturnDetail\QcToolReturnDetail;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use File;
use Input;
use Request;

class ToolController extends Controller
{
    public function index($monthFilter = 0, $yearFilter = 0)
    {
        $modelStaff = new QcStaff();
        $modelCompanyStore = new QcCompanyStore();
        $modelTool = new QcTool();
        $modelToolAllocation = new QcToolAllocation();
        $modelToolAllocationDetail = new QcToolAllocationDetail();
        $dataAccess = [
            'object' => 'toolPrivate'
        ];
        $dataStaff = $modelStaff->loginStaffInfo();
        # danh sach dung cu dung de phat cho ca nha
        $listToolId = $modelTool->privateListId();
        # danh dach dung cua trong kho cua 1 cty
        $dataCompanyStore = $modelCompanyStore->getInfoByListToolAndCompany($listToolId, $dataStaff->companyId());
        return view('work.tool.private.list', compact('dataAccess', 'modelStaff', 'dataCompanyStore', 'monthFilter', 'yearFilter'));
    }

    #xac nhan da nhan do nghe
    public function getConfirmReceive($allocationId)
    {
        $modelToolAllocation = new QcToolAllocation();
        $modelToolAllocation->receiveConfirm($allocationId);
    }

    # ------- -------- tra lai do nghe  -------- --------
    public function getReturn($selectedStoreId = null)
    {
        $modelCompanyStore = new QcCompanyStore();
        $modelStaff = new QcStaff();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelTool = new QcTool();
        $modelToolAllocationDetail = new QcToolAllocationDetail();
        $dataAccess = [
            'object' => 'toolPrivate'
        ];
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        # danh sach ma kho
        $listStoreId = $modelToolAllocationDetail->listStoreIdOfWork($modelCompanyStaffWork->workIdActivityOfStaff($dataStaffLogin->staffId()));
        # chi danh sach cong cu da ban giao trong kho
        $dataCompanyStore = $modelCompanyStore->getInfoByListId($listStoreId);
        return view('work.tool.private.return', compact('dataAccess', 'modelStaff', 'dataCompanyStore', 'selectedStoreId'));
    }

    public function postReturn()
    {
        $hFunction = new \Hfunction();

        $modelStaff = new QcStaff();
        $modelToolReturn = new QcToolReturn();
        $modelToolReturnDetail = new QcToolReturnDetail();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $returnStore = Request::input('txtReturnStore');
        $workId = $modelCompanyStaffWork->workIdActivityOfStaff($modelStaff->loginStaffId());
        $workId = (is_int($workId))?$workId:$workId[0];
        if (!$hFunction->checkEmpty($workId)) {
            if ($modelToolReturn->insert($workId)) {
                $returnId = $modelToolReturn->insertGetId();
                foreach ($returnStore as $storeId) {
                    $amount = Request::input('txtReturnAmount_' . $storeId);
                    $modelToolReturnDetail->insert($amount, $storeId, $returnId);
                }
            }
        }
        return redirect()->route('qc.work.tool.private.get');
        //dd($amount);
        //die();
    }
}
