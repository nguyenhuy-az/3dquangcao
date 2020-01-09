<?php

namespace App\Http\Controllers\Ad3d\Store\Tool\Allocation;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\CompanyStore\QcCompanyStore;
use App\Models\Ad3d\Import\QcImport;
use App\Models\Ad3d\Staff\QcStaff;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ad3d\Tool\QcTool;
use App\Models\Ad3d\ToolAllocation\QcToolAllocation;
use App\Models\Ad3d\ToolAllocationDetail\QcToolAllocationDetail;
use Illuminate\Support\Facades\Session;
use Input;
use File;
use DB;
use Request;


class AllocationController extends Controller
{
    public function index($companyFilterId = null, $dayFilter = 0, $monthFilter = 0, $yearFilter = 0, $nameFiler = null)
    {
        $hFunction = new \Hfunction();
        $hFunction->defaultTimezone();
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelToolAllocation = new QcToolAllocation();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataAccess = [
            'accessObject' => 'tool'
        ];
        if ($dayFilter == 0 && $monthFilter == 0 && $yearFilter == 0) {
            $yearFilter = date('Y');
            $dateFilter = date('Y');
        } elseif ($dayFilter == 0 && $monthFilter > 0) { //xe tat ca cac ngay trong thang
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        } elseif ($dayFilter == 0 && $monthFilter == 0 && $yearFilter > 0) { //xe tat ca trong nam
            $dateFilter = date('Y', strtotime("1-1-$yearFilter"));
        } else {
            $dateFilter = date('Y-m-d', strtotime("$dayFilter-$monthFilter-$yearFilter"));
        }

        $dataCompany = $modelCompany->getInfo();
        if (empty($companyFilterId)) {
            if (!$dataStaffLogin->checkRootManage()) {
                $searchCompanyFilterId = [$dataStaffLogin->companyId()];
                $companyFilterId = $dataStaffLogin->companyId();
            } else {
                $searchCompanyFilterId = $modelCompany->listIdActivity();
            }
        } else {
            $searchCompanyFilterId = [$companyFilterId];
        }
        if (!empty($nameFiler)) {
            $listStaffId = $modelCompanyStaffWork->staffIdOfListCompany($searchCompanyFilterId)->toArray();
            $listStaffIdByName = $modelStaff->listStaffIdByName($nameFiler)->toArray();
            $listStaffId = $hFunction->getSubArrayFromTwoArray($listStaffIdByName, $listStaffId);
        } else {
            $listStaffId = $modelCompanyStaffWork->staffIdOfListCompany($searchCompanyFilterId);
        }

        $dataToolAllocation = $modelToolAllocation->selectInfoOfListReceiveStaffAndDate($listStaffId, $dateFilter)->paginate(30);
        return view('ad3d.store.tool.allocation.list', compact('modelStaff', 'dataCompany', 'dataAccess', 'dataToolAllocation', 'companyFilterId', 'dayFilter', 'monthFilter', 'yearFilter', 'nameFiler'));


    }

    public function viewAllocation($allocationId)
    {
        $modelStaff = new QcStaff();
        $modelToolAllocation = new QcToolAllocation();
        $dataToolAllocation = $modelToolAllocation->getInfo($allocationId);
        $dataAccess = [
            'accessObject' => 'tool'
        ];
        return view('ad3d.store.tool.allocation.allocation-detail', compact('modelStaff', 'dataAccess', 'dataToolAllocation'));
    }

    public function getAdd($selectCompanyId = 0)
    {
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelTool = new QcTool();
        $modelCompanyStore = new QcCompanyStore();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataTool = $modelTool->getInfoOrderByName();
        $dataAccess = [
            'accessObject' => 'tool'
        ];
        if (empty($selectCompanyId)) { //chua chon cty
            $selectCompanyId = $dataStaffLogin->companyId();
            if ($dataStaffLogin->checkRootStatus()) { //tai khoan cao nhat
                $dataCompany = $modelCompany->infoActivity();//lay tat ca cac cac  cong ty
            } else {
                $dataCompany = $modelCompany->infoByListId([$selectCompanyId]); # chi hien cty cua ng??i dang nhao
            }
        } else {
            # da chon cong ty
            if ($dataStaffLogin->checkRootStatus()) { //tai khoan cao nhat
                $dataCompany = $modelCompany->infoActivity();//lay tat ca cac cac  cong ty
            } else {
                $dataCompany = $modelCompany->getInfo($selectCompanyId); # chi hien cty cua ng??i dang nhao
            }
        }
        $listStaffWorkId = $modelCompanyStaffWork->staffIdActivityOfCompany($selectCompanyId);
        $dataReceiveStaff = $modelStaff->getInfoByListStaffId($listStaffWorkId); # danh sach Nv nhan do nghe
        $dataCompanyStore = $modelCompanyStore->selectInfoToolOfListCompanyAndListToolAnd([$selectCompanyId], null)->get();
        return view('ad3d.store.tool.allocation.allocation-add', compact('modelStaff', 'dataCompany', 'dataAccess', 'dataCompanyStore', 'dataReceiveStaff', 'selectCompanyId'));
    }

    public function postAdd()
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompanyStore = new QcCompanyStore();
        $modelToolAllocation = New QcToolAllocation();
        $modelToolAllocationDetail = new QcToolAllocationDetail();
        $staffLoginId = $modelStaff->loginStaffId();

        $receiveStaffId = Request::input('cbReceiveStaff');
        $txtStore = Request::input('txtStore');
        $txtAllocationAmount = Request::input('txtAllocationAmount');
        $cbNewStatus = Request::input('cbNewStatus');
        $storeStatus = false;
        if (count($txtStore) > 0) {
            foreach ($txtStore as $key => $value) {
                if ($value > 0) $storeStatus = true;
            }
        }
        if ($storeStatus) { // co chon dung cu
            if ($modelToolAllocation->insert($hFunction->createdAt(), $staffLoginId, $receiveStaffId)) {
                $newAllocationId = $modelToolAllocation->insertGetId();
                foreach ($txtStore as $key => $value) {
                    $dataCompanyStore = $modelCompanyStore->getInfo($value);
                    $modelToolAllocationDetail->insert($txtAllocationAmount[$key], $cbNewStatus[$key], $dataCompanyStore->toolId(), $newAllocationId, $dataCompanyStore->companyId(), $value);
                }
            }
        }
        return redirect()->route('qc.ad3d.store.tool.allocation.get');
    }
}
