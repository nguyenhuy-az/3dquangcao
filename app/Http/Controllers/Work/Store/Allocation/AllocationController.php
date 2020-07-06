<?php

namespace App\Http\Controllers\Work\Store\Allocation;


use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\ToolAllocation\QcToolAllocation;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use File;
use Input;
use Request;

class AllocationController extends Controller
{
    public function index()
    {
        $modelStaff = new QcStaff();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelToolAllocation = new QcToolAllocation();
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
        $dataToolAllocation = $modelToolAllocation->infoActivityOfListWork($listWorkId);
        return view('work.store.allocation.list', compact('dataAccess', 'modelStaff', 'dataToolAllocation'));
    }

    #-------------- ------------- Xac nhan ban  giao ------------------ -------------
    public function checkInfo($allocationId)
    {
        $modelStaff = new QcStaff();
        $modelToolAllocation = new QcToolAllocation();
        $dataToolAllocation = $modelToolAllocation->getInfo($allocationId);
        return view('work.store.return.confirm', compact('modelStaff', 'dataToolAllocation'));
    }

}
