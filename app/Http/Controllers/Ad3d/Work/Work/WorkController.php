<?php

namespace App\Http\Controllers\Ad3d\Work\Work;

use App\Http\Controllers\Ad3d\Staff\Staff\StaffController;
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
    public function index($companyFilterId = null, $monthFilter = null, $yearFilter = null, $nameFiler = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataAccess = [
            'accessObject' => 'work'
        ];
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

        if (empty($monthFilter) && empty($yearFilter)) {
            $dateFilter = date('Y-m');
            $monthFilter = date('m');
            $yearFilter = date('Y');
        } elseif ($monthFilter == 0) {
            $dateFilter = date('Y', strtotime("1-1-$yearFilter"));
        } else {
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        }
        if (!empty($nameFiler)) {
            $listCompanyStaffWorkId = $modelCompanyStaffWork->listIdOfListCompanyAndListStaff($searchCompanyFilterId, $modelStaff->listStaffIdByName($nameFiler));
        } else {
            $listCompanyStaffWorkId = $modelCompanyStaffWork->listIdOfListCompanyAndListStaff($searchCompanyFilterId, null);
        }
        $dataWork = QcWork::where('fromDate', 'like', "%$dateFilter%")->whereIn('companyStaffWork_id', $listCompanyStaffWorkId)->orderBy('fromDate', 'DESC')->select('*')->paginate(30);
        return view('ad3d.work.work.list', compact('modelStaff', 'dataCompany', 'dataAccess', 'dataWork', 'companyFilterId', 'monthFilter', 'yearFilter', 'nameFiler'));

    }

    # phien ban cu truoc 8/2019
    public function indexOld($companyFilterId = null, $monthFilter = null, $yearFilter = null, $nameFiler = null)
    { // bang cham cong thuoc truc tiep nv
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelWork = new QcWork();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataAccess = [
            'accessObject' => 'work'
        ];
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

        if (empty($monthFilter) && empty($yearFilter)) {
            $dateFilter = date('Y-m');
            $monthFilter = date('m');
            $yearFilter = date('Y');
        } elseif ($monthFilter == 0) {
            $dateFilter = date('Y', strtotime("1-1-$yearFilter"));
        } else {
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        }
        if (!empty($nameFiler)) {
            $listStaffId = $modelStaff->listIdOfListCompanyAndName($searchCompanyFilterId, $nameFiler);

        } else {
            $listStaffId = $modelStaff->listIdOfListCompany($searchCompanyFilterId);
        }
        $dataWork = QcWork::where('fromDate', 'like', "%$dateFilter%")->whereIn('staff_id', $listStaffId)->orderBy('fromDate', 'DESC')->select('*')->paginate(30);
        return view('ad3d.work.work.list-old', compact('modelStaff', 'dataCompany', 'dataAccess', 'dataWork', 'companyFilterId', 'monthFilter', 'yearFilter', 'nameFiler'));
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
            return view('ad3d.work.work.make-salary-work', compact('modelStaff','modelCompanyStaffWork', 'dataWork', 'modelTimekeeping'));
        }
    }

    public function postMakeSalaryWork($workId = null)
    {
        $modelStaff = new QcStaff();
        $modelWork = new QcWork();
        $txtBenefit = Request::input('txtBenefit');
        $workStatus = Request::input('txtWorkStatus');
        # tinh luong
        $modelWork->makeSalaryOfWork($workId, 0, $workStatus);
    }
}
