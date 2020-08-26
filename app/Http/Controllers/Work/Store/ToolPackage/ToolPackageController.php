<?php

namespace App\Http\Controllers\Work\Store\ToolPackage;


use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\MinusMoney\QcMinusMoney;
use App\Models\Ad3d\Order\QcOrder;
use App\Models\Ad3d\PunishContent\QcPunishContent;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\StaffNotify\QcStaffNotify;
//use Illuminate\Http\Request;
use App\Models\Ad3d\ToolPackage\QcToolPackage;
use App\Models\Ad3d\ToolPackageAllocation\QcToolPackageAllocation;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use File;
use Input;
use Request;

class ToolPackageController extends Controller
{
    public function index()
    {
        $modelStaff = new QcStaff();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelToolPackage = new QcToolPackage();
        $dataAccess = [
            'object' => 'storeToolPackage',
            'subObjectLabel' => 'Túi đồ nghề'
        ];
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        // danh sach tui do nghe
        $dataToolPackage = $modelToolPackage->getInfoOfCompany($dataStaffLogin->companyId());
        return view('work.store.tool-package.list', compact('dataAccess', 'modelStaff', 'dataToolPackage'));
    }

    # giao do nghe tu dong
    public function addAutoAllocationPackage()
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelToolPackage = new QcToolPackage();
        $dataCompanyStaffWorkLogin = $modelStaff->loginCompanyStaffWork();
        # lay dang sach lam viec cua nhan vien thi cong chua duoc giao do nghe cua 1 cty
        $dataCompanyStaffWork = $modelCompanyStaffWork->getInfoForToolPackageAllocationOfCompany($dataCompanyStaffWorkLogin->companyId());
        //dd($dataCompanyStaffWork);
        if ($hFunction->checkCount($dataCompanyStaffWork)) {
            foreach($dataCompanyStaffWork as $companyStaffWork){
                //$workId = $companyStaffWork->workId();
                //echo "$workId<br/>";
                # giao do nghe
                $modelToolPackage->allocationForCompanyStaffWork($companyStaffWork->workId());
            }

        }
    }
}
