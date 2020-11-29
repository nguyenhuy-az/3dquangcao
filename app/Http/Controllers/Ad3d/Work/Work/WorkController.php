<?php

namespace App\Http\Controllers\Ad3d\Work\Work;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\Salary\QcSalary;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\Timekeeping\QcTimekeeping;
use App\Models\Ad3d\Work\QcWork;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Input;
use File;
use DB;
use Request;

class WorkController extends Controller
{
    public function index($companyFilterId = 0, $monthFilter = 0, $yearFilter = 0, $staffFilterId = 0)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelWork = new QcWork();
        $modelCompany = new QcCompany();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $dataCompanyLogin = $modelStaff->companyLogin();
        $companyLoginId = $dataCompanyLogin->companyId();
        $companyFilterId = ($companyFilterId == 'null') ? null : $companyFilterId;
        if ($companyFilterId == 0) {
            $companyFilterId = $companyLoginId;
        }
        # lay thong tin cong ty cung he thong
        $dataCompany = $modelCompany->getInfoSameSystemOfCompany($companyLoginId);
        $dataAccess = [
            'accessObject' => 'work'
        ];
        $dateFilter = null;
        if ($monthFilter == 0 && $yearFilter == 0) {
            $monthFilter = date('m');
            $yearFilter = date('Y');
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        } else { //xem tất cả các ngày trong tháng
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        }

        if ($staffFilterId > 0) {
            $listCompanyStaffWorkId = $modelCompanyStaffWork->listIdOfListCompanyAndListStaff([$companyFilterId], [$staffFilterId]);
        } else {
            $listCompanyStaffWorkId = $modelCompanyStaffWork->listIdOfListCompanyAndListStaff([$companyFilterId], null);
        }
        $dataWork = $modelWork->selectInfoOfListCompanyStaffWorkAndDate($listCompanyStaffWorkId, $dateFilter)->paginate(30);
        //danh sach NV
        $dataStaffFilter = $modelCompany->staffInfoActivityOfListCompanyId([$companyFilterId]);
        return view('ad3d.work.work.list', compact('modelStaff','dataStaffFilter', 'dataCompany', 'dataAccess', 'dataWork', 'companyFilterId', 'monthFilter', 'yearFilter', 'staffFilterId'));

    }


    public function view($workId = null)
    {
        $modelStaff = new QcStaff();
        $modelWork = new QcWork();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $dataWork = $modelWork->getInfo($workId);
        if (count($dataWork) > 0) {
            $dataTimekeeping = $modelWork->infoTimekeeping($workId);
            return view('ad3d.work.work.view', compact('modelStaff', 'modelCompanyStaffWork', 'dataWork', 'dataTimekeeping'));
        }
    }

    public function viewOld($workId = null)
    {
        $modelStaff = new QcStaff();
        $modelWork = new QcWork();
        $dataWork = $modelWork->getInfo($workId);
        if (count($dataWork) > 0) {
            $dataTimekeeping = $modelWork->infoTimekeeping($workId);
            return view('ad3d.work.work.view-old', compact('modelStaff', 'dataWork', 'dataTimekeeping'));
        }
    }

    public function getMakeSalaryWork($workId = null)
    {
        $modelStaff = new QcStaff();
        $modelWork = new QcWork();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelTimekeeping = new QcTimekeeping();
        $dataWork = $modelWork->getInfo($workId);
        if (count($dataWork) > 0) {
            return view('ad3d.work.work.make-salary-work', compact('modelStaff', 'modelCompanyStaffWork', 'dataWork', 'modelTimekeeping'));
        }
    }

    public function postMakeSalaryWork($workId = null)
    {
        $modelWork = new QcWork();
        $modelSalary = new QcSalary();
        $workStatus = Request::input('txtWorkStatus');
        # tinh luong
        $modelWork->makeSalaryOfWork($workId, $modelSalary->getDefaultBenefitMoney(), $workStatus);
    }
}
