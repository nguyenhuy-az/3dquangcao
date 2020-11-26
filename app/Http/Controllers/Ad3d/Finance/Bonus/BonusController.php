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
    public function index($companyFilterId = null, $dayFilter = 0, $monthFilter = 0, $yearFilter = 0, $staffFilterId = 0)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelWork = new QcWork();
        $modelBonus = new QcBonus();
        $dataCompanyLogin = $modelStaff->companyLogin();
        $companyLoginId = $dataCompanyLogin->companyId();
        $companyFilterId = ($companyFilterId == 'null') ? null : $companyFilterId;
        if ($companyFilterId == null || $companyFilterId == 0) {
            $companyFilterId = $companyLoginId;
        }
        # lay thong tin cong ty cung he thong
        $dataCompany = $modelCompany->getInfoSameSystemOfCompany($companyLoginId);
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
            $dateFilter = date('Y-m-d', strtotime("$dayFilter-$monthFilter-$yearFilter"));
        } elseif ($dayFilter == 100 && $monthFilter == 100 && $yearFilter == 100) { //xem tất cả
            $dateFilter = null;
        } else {
            $dateFilter = date('Y-m');
            $dayFilter = 100;
            $monthFilter = date('m');
            $yearFilter = date('Y');
        }
        if ($monthFilter < 8 && $yearFilter <= 2019) { # du lieu phien ban cu
            if ($staffFilterId > 0) {
                $listStaffId = [$staffFilterId];
            } else {
                $listStaffId = $modelStaff->listIdOfListCompany([$companyFilterId]);
            }
            $listWorkId = $modelWork->listIdOfListStaffId($listStaffId);
        } else {
            if ($staffFilterId > 0) {
                $listStaffId = [$staffFilterId];
                $listCompanyStaffWorkId = $modelCompanyStaffWork->listIdOfListCompanyAndListStaff([$companyFilterId], $listStaffId);
            } else {
                $listCompanyStaffWorkId = $modelCompanyStaffWork->listIdOfListCompanyAndListStaff([$companyFilterId], null);
            }
            $listWorkId = $modelWork->listIdOfListCompanyStaffWork($listCompanyStaffWorkId);
        }
        $dataBonus = $modelBonus->selectInfoHasFilter($listWorkId, $dateFilter)->paginate(30);
        $totalBonusMoney = $modelBonus->totalMoneyHasFilter($listWorkId, $dateFilter);
        //danh sach NV
        $dataStaffFilter = $modelCompany->staffInfoActivityOfListCompanyId([$companyFilterId]);
        return view('ad3d.finance.bonus.list', compact('modelStaff','dataStaffFilter', 'dataCompany', 'dataAccess', 'dataBonus', 'totalBonusMoney', 'companyFilterId', 'dayFilter', 'monthFilter', 'yearFilter', 'staffFilterId'));

    }

    public function getCancelBonus($bonusId)
    {
        $modelBonus = new QcBonus();
        $dataBonus = $modelBonus->getInfo($bonusId);
        return view('ad3d.finance.bonus.cancel-bonus', compact('dataBonus'));
    }

    public function postCancelBonus($bonusId)
    {
        $hFunction = new \Hfunction();
        $modelBonus = new QcBonus();
        $txtNote = Request::input('txtCancelNote');
        $txtWarningImage = Request::file('txtCancelImage');
        $name_img = null; // mac dinh null
        if (!empty($txtWarningImage)) {
            $name_img = stripslashes($_FILES['txtCancelImage']['name']);
            $name_img = $hFunction->getTimeCode() . '.' . $hFunction->getTypeImg($name_img);
            $source_img = $_FILES['txtCancelImage']['tmp_name'];
            $modelBonus->uploadImage($source_img, $name_img);
        }
        $modelBonus->cancelBonus($bonusId, $txtNote, $name_img);
    }
}
