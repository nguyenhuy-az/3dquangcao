<?php

namespace App\Http\Controllers\Work\Store\ToolPackageAllocation;


use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\MinusMoney\QcMinusMoney;
use App\Models\Ad3d\Order\QcOrder;
use App\Models\Ad3d\PunishContent\QcPunishContent;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\StaffNotify\QcStaffNotify;
//use Illuminate\Http\Request;
use App\Models\Ad3d\ToolPackageAllocation\QcToolPackageAllocation;
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
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelToolPackageAllocation = new QcToolPackageAllocation();
        $dataAccess = [
            'object' => 'storeAllocation',
            'subObjectLabel' => 'Trả đồ nghề'
        ];
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $searchCompanyFilterId = [$dataStaffLogin->companyId()];
        $listStaffId = $modelStaff->listIdOfListCompany($searchCompanyFilterId);
        # danh sach thong tin lam viec
        $listWorkId = $modelCompanyStaffWork->listIdOfListStaffId($listStaffId);
        # danh sach bo do nghe
        $dataToolPackageAllocation = $modelToolPackageAllocation->infoActivityOfListWork($listWorkId);
        return view('work.store.tool-package-allocation.list', compact('dataAccess', 'modelStaff', 'dataToolPackageAllocation'));
    }

    #-------------- ------------- Xac nhan ban  giao ------------------ -------------
    public function checkInfo($allocationId)
    {
        $modelStaff = new QcStaff();
        $modelToolPackageAllocation = new QcToolPackageAllocation();
        $dataToolPackageAllocation = $modelToolPackageAllocation->getInfo($allocationId);
        $dataToolPackageAllocationDetail = $modelToolPackageAllocation->toolAllocationDetailOfAllocation($allocationId);
        return view('work.store.tool-package-allocation.check-info', compact('modelStaff', 'dataToolPackageAllocation', 'dataToolPackageAllocationDetail'));
    }

    # xem anh ban giao
    public function viewImage($detailId)
    {
        $modelToolAllocationDetail = new QcToolAllocationDetail();
        $dataToolPackageAllocationDetail = $modelToolAllocationDetail->getInfo($detailId);
        return view('work.store.allocation.view-image', compact('modelStaff', 'dataToolAllocationDetail'));
    }

    #xem anh tra
    public function viewReturnImage($returnId){
        $modelToolReturn = new QcToolReturn();
        $dataToolReturn = $modelToolReturn->getInfo($returnId);
        return view('work.store.allocation.view-return-image', compact('modelStaff', 'dataToolReturn'));
    }

    # ap dung phat theo noi qui
    public function getMinusMoney($detailId)
    {
        $modelCompany = new QcCompany();
        $modelPunishContent = new QcPunishContent();
        $modelStaff = new QcStaff();
        $modelToolAllocationDetail = new QcToolAllocationDetail();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        # thong tin phat

        $dataPunishContent = $modelPunishContent->getInfoById($modelPunishContent->getPunishIdNotBringTool());
        # dung cu khong mang theo
        $dataToolPackageAllocationDetail = $modelToolAllocationDetail->getInfo($detailId);
        # cong trinh dang thi cong
        $dataOrder = $modelCompany->orderInfoNotFinish($dataStaffLogin->companyId());
        return view('work.store.allocation.minus-money', compact('modelStaff', 'dataPunishContent', 'dataToolAllocationDetail', 'dataOrder'));
    }

    public function postMinusMoney($detailId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelOrder = new QcOrder();
        $modelMinusMoney = new QcMinusMoney();
        $modelStaffNotify = new QcStaffNotify();
        $modelToolAllocationDetail = new QcToolAllocationDetail();
        $currentDate = $hFunction->carbonNow();
        $punishId = Request::input('cbPunishContent');
        $cbOrderId = Request::input('cbOrder');
        $txtMinusMoneyNote = Request::input('txtMinusMoneyNote');
        $dataToolPackageAllocationDetail = $modelToolAllocationDetail->getInfo($detailId);
        $companyStoreName = $dataToolPackageAllocationDetail->companyStore->name();
        $dataStaffApply = $dataToolPackageAllocationDetail->toolAllocation->companyStaffWork->staff;
        $dataWork = $dataStaffApply->workInfoActivityOfStaff();
        if ($hFunction->checkCount($dataWork)) {
            $workId = $dataWork->workId();
            $reason = "Không đem đồ nghề '$companyStoreName' thi công đơn hàng " . $modelOrder->name($cbOrderId)[0] . ". $txtMinusMoneyNote";
            if ($modelMinusMoney->insert($currentDate, $reason, $workId, $modelStaff->loginStaffId(), $punishId, 0, null, null)) {
                $modelStaffNotify->insert(null, $dataStaffApply->staffId(), 'Không đem đồ nghề thi công', null, null, null, $modelMinusMoney->insertGetId());
            }
        }
    }

}
