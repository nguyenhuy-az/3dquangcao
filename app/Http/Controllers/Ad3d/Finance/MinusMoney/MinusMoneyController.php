<?php

namespace App\Http\Controllers\Ad3d\Finance\MinusMoney;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\MinusMoney\QcMinusMoney;
use App\Models\Ad3d\PunishContent\QcPunishContent;
use App\Models\Ad3d\SalaryBeforePay\QcSalaryBeforePay;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\Work\QcWork;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Input;
use File;
use DB;
use Request;

class MinusMoneyController extends Controller
{
    public function index($companyFilterId = null, $dayFilter = 0, $monthFilter = 0, $yearFilter = 0, $punishContentFilterId = 0, $nameFiler = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelPunishContent = new QcPunishContent();
        $modelCompany = new QcCompany();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelWork = new QcWork();
        $modelMinus = new QcMinusMoney();
        $currentMonth = $hFunction->currentMonth();
        $currentYear = $hFunction->currentYear();

        $dataPunishContent = $modelPunishContent->getInfo();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataAccess = [
            'accessObject' => 'penalize'
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
        $punishContentFilterId = ($punishContentFilterId == 0) ? null : $punishContentFilterId;
        $dataMinusMoney = $modelMinus->selectInfoHasFilter($listWorkId, $punishContentFilterId, $dateFilter)->paginate(30);
        $totalMinusMoney = $modelMinus->totalMoneyHasFilter($listWorkId, $punishContentFilterId, $dateFilter);
        //dd($searchCompanyFilterId);
        return view('ad3d.finance.minus-money.list', compact('modelStaff', 'dataCompany', 'dataAccess', 'dataPunishContent', 'dataMinusMoney', 'totalMinusMoney', 'companyFilterId', 'dayFilter', 'monthFilter', 'yearFilter', 'punishContentFilterId', 'nameFiler'));

    }
    public function cancelMinusMoney($minusId)
    {
        $modelMinus = new QcMinusMoney();
        $modelMinus->cancelMinus($minusId);
    }

   /* public function view($minusId)
    {
        $modelMinusMoney = new QcMinusMoney();
        $dataMinusMoney = $modelMinusMoney->getInfo($minusId);
        if (count($dataMinusMoney) > 0) {
            return view('ad3d.finance.minus-money.view', compact('dataMinusMoney'));
        }
    }*/

    /*public function getAdd($companyLoginId = null, $workId = null, $punishId = null)
    {
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelWork = new QcWork();
        $modelPunishContent = new QcPunishContent();
        $dataAccess = [
            'accessObject' => 'penalize'
        ];
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        if (empty($companyLoginId)) {
            $companyLoginId = $dataStaffLogin->companyId();
        }
        $dataCompany = $modelCompany->getInfo($companyLoginId);
        //dd($dataCompany);
        $listCompanyStaffWorkId = $modelCompanyStaffWork->listIdOfListCompanyAndListStaff([$companyLoginId], null);
        $dataWork = $modelWork->infoActivityOfListCompanyStaffWork($listCompanyStaffWorkId);
        //$dataWork = $modelWork->infoActivityOfListStaff($modelStaff->listIdOfCompany($companyLoginId));
        $dataWorkSelect = (empty($workId)) ? null : $modelWork->getInfo($workId);
        $dataPunishContent = $modelPunishContent->getInfoOrderName();
        $dataPunishContentSelected = (empty($punishId)) ? null : $modelPunishContent->getInfo($punishId);
        return view('ad3d.finance.minus-money.add', compact('modelStaff', 'dataPunishContent', 'dataAccess', 'dataCompany', 'companyLoginId', 'dataWork', 'dataWorkSelect', 'dataPunishContentSelected'));
    }

    public function postAdd()
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelMinusMoney = new QcMinusMoney();
        $modelPunishContent = new QcPunishContent();
        $workId = Request::input('cbWork');
        $cbDay = Request::input('cbDay');
        $cbMonth = Request::input('cbMonth');
        $cbYear = Request::input('cbYear');
        $cbPunishContentId = Request::input('cbPunishContent');
        $reason = Request::input('txtDescription');
        $staffId = $modelStaff->loginStaffId();
        $dateMinus = $hFunction->convertStringToDatetime("$cbMonth/$cbDay/$cbYear 00:00:00");
        if ($modelMinusMoney->insert($dateMinus, $reason, $workId, $staffId, $cbPunishContentId)) {
            return Session::put('notifyAdd', 'Thêm thành công, chọn thông tin và tiếp tục');
        } else {
            return Session::put('notifyAdd', 'Thêm thất bại, hãy thử lại');
        }

    }*/

    /*public function getEdit($minusId)
    {
        $modelMinusMoney = new QcMinusMoney();
        $dataMinusMoney = $modelMinusMoney->getInfo($minusId);
        $dataWorkSelect = $dataMinusMoney->work;
        return view('ad3d.finance.minus-money.edit', compact('dataWorkSelect', 'dataMinusMoney'));
    }

    public function postEdit($minusId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelMinusMoney = new QcMinusMoney();
        $cbDay = Request::input('cbDay');
        $cbMonth = Request::input('cbMonth');
        $cbYear = Request::input('cbYear');
        $txtMoney = Request::input('txtMoney');
        $txtReason = Request::input('txtReason');
        $dateMinus = $hFunction->convertStringToDatetime("$cbMonth/$cbDay/$cbYear 00:00:00");
        if (!$modelMinusMoney->updateInfo($minusId, $txtMoney, $dateMinus, $txtReason)) {
            return "Cập nhật thất bại";
        }
    }*/



}
