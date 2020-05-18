<?php

namespace App\Http\Controllers\Ad3d\Finance\Bonus;

use App\Models\Ad3d\Bonus\QcBonus;
use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\Work\QcWork;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Input;
use File;
use DB;
use Request;

class BonusController extends Controller
{
    public function index($companyFilterId = null, $dayFilter = 0, $monthFilter = 0, $yearFilter = 0, $nameFiler = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelWork = new QcWork();
        $modelBonus = new QcBonus();
        $currentMonth = $hFunction->currentMonth();
        $currentYear = $hFunction->currentYear();

        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataAccess = [
            'accessObject' => 'bonus'
        ];
        $dateFilter = null;
        if ($dayFilter == 0 && $monthFilter == 0 && $yearFilter == 0) { //xem  trong tháng
            $dayFilter = 100;
            $monthFilter = date('m');
            $yearFilter = date('Y');
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        } elseif ($dayFilter == 100 && $monthFilter == 100 && $yearFilter > 100) { //xem tất cả các ngày trong tháng
            $dateFilter = date('Y', strtotime("1-1-$yearFilter"));
        } elseif ($dayFilter == 100 && $monthFilter > 0 && $monthFilter < 100 && $yearFilter > 100) { //xem tất cả các ngày trong tháng
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        } elseif ($dayFilter < 100 && $dayFilter > 0 && $monthFilter > 0 && $monthFilter < 100 && $yearFilter > 100) { //xem tất cả các ngày trong tháng
            $monthFilter = $currentMonth;
            $yearFilter = $currentYear;
            $dateFilter = date('Y-m-d', strtotime("$dayFilter-$currentMonth-$currentYear"));
        } elseif ($dayFilter == 100 && $monthFilter == 100 && $yearFilter == 100) { //xem tất cả
            $dateFilter = null;
        } else {
            $dateFilter = date('Y-m');
            $dayFilter = 100;
            $monthFilter = date('m');
            $yearFilter = date('Y');
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
            if($companyFilterId == 1000){
                $searchCompanyFilterId = $modelCompany->listIdActivity();
            }else{
                $searchCompanyFilterId = [$companyFilterId];
            }
        }

        if ($monthFilter < 8 && $yearFilter <= 2019) { # du lieu phien ban cu
            if (!empty($nameFiler)) {
                $listStaffId = $modelStaff->listIdOfListCompanyAndName($searchCompanyFilterId, $nameFiler);
            } else {
                $listStaffId = $modelStaff->listIdOfListCompany($searchCompanyFilterId);
            }
            $listWorkId = $modelWork->listIdOfListStaffId($listStaffId);
        } else {
            if (!empty($nameFiler)) {
                $listStaffId = $modelStaff->listStaffIdByName($nameFiler);
                $listCompanyStaffWorkId = $modelCompanyStaffWork->listIdOfListCompanyAndListStaff($searchCompanyFilterId, $listStaffId);
            } else {
                $listCompanyStaffWorkId = $modelCompanyStaffWork->listIdOfListCompanyAndListStaff($searchCompanyFilterId, null);
            }
            $listWorkId = $modelWork->listIdOfListCompanyStaffWork($listCompanyStaffWorkId);
        }
        $dataBonus = $modelBonus->selectInfoHasFilter($listWorkId, $dateFilter)->paginate(30);
        $totalBonusMoney = $modelBonus->totalMoneyHasFilter($listWorkId, $dateFilter);
        return view('ad3d.finance.bonus.list', compact('modelStaff', 'dataCompany', 'dataAccess', 'dataBonus', 'totalBonusMoney', 'companyFilterId', 'dayFilter', 'monthFilter', 'yearFilter', 'nameFiler'));

    }
    public function cancelBonus($minusId)
    {
        $modelBonus = new QcBonus();
        $modelBonus->cancelBonus($minusId);
    }


}
