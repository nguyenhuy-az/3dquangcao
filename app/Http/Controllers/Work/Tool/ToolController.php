<?php

namespace App\Http\Controllers\Work\Tool;


use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\Tool\QcTool;
use App\Models\Ad3d\ToolAllocation\QcToolAllocation;
use App\Models\Ad3d\ToolAllocationDetail\QcToolAllocationDetail;
//use Illuminate\Http\Request;
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
        $modelTool = new QcTool();
        $modelToolAllocation = new QcToolAllocation();
        $modelToolAllocationDetail = new QcToolAllocationDetail();
        $dataAccess = [
            'object' => 'toolPrivate'
        ];
        $dataStaff = $modelStaff->loginStaffInfo();
        # danh sach dung cu cua he thong
        $type = 2; // chi lay dung cu cap phat (1 - dùng chung / 2 - cap phat)
        $dataTool = $modelTool->selectAllInfo($type)->get();
        return view('work.tool.private.list', compact('dataAccess', 'modelStaff', 'dataTool', 'monthFilter', 'yearFilter'));
    }

    #xac nhan da nhan do nghe
    public function getConfirmReceive($allocationId)
    {
        $modelToolAllocation = new QcToolAllocation();
        $modelToolAllocation->receiveConfirm($allocationId);
    }

    # ------- -------- tra lai do nghe  -------- --------
    public function getReturn($selectedToolId = null)
    {
        $modelStaff = new QcStaff();
        $modelTool = new QcTool();
        $modelToolAllocation = new QcToolAllocation();
        $modelToolAllocationDetail = new QcToolAllocationDetail();
        $dataAccess = [
            'object' => 'toolPrivate'
        ];
        # chi danh sach cong cu da ban giao
        $listToolId = $modelToolAllocationDetail->listToolIdOfStaff($modelStaff->loginStaffId());
        $dataTool = $modelTool->getInfoByListId($listToolId);
        return view('work.tool.private.return', compact('dataAccess', 'modelStaff', 'dataTool', 'selectedToolId'));
    }

    public function postReturn()
    {
        $modelStaff = new QcStaff();
        $modelToolAllocation = new QcToolAllocation();
        $returnTool = Request::input('txtReturnTool');
        //$returnAmount = Request::input('txtReturnAmount');
        foreach($returnTool as $tool){
            $amount = Request::input('txtReturnAmount_'.$tool);
        }
        die();
    }
}
