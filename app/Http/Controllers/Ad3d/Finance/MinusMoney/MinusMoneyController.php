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
    public function index($companyFilterId = null, $dayFilter = null, $monthFilter = null, $yearFilter = null, $punishContentFilterId = null, $nameFiler = null)
    {
        $modelStaff = new QcStaff();
        $modelPunishContent = new QcPunishContent();
        $modelCompany = new QcCompany();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelWork = new QcWork();

        $dataPunishContent = $modelPunishContent->getInfo();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataAccess = [
            'accessObject' => 'penalize'
        ];
        if (empty($dayFilter) && empty($monthFilter) && empty($yearFilter)) {
            $dateFilter = date('Y-m');
            //$dayFilter = date('d');
            $monthFilter = date('m');
            $yearFilter = date('Y');
            //$dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        } elseif ($dayFilter == 0) { //xem tất cả các ngày trong tháng
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        } else {
            $dateFilter = date('Y-m-d', strtotime("$dayFilter-$monthFilter-$yearFilter"));
        }
        $dataCompany = $modelCompany->getInfo();
        if ($dataStaffLogin->checkRootManage()) {
            if (empty($companyFilterId)) {
                $searchCompanyFilterId = $modelCompany->listIdActivity();
            } else {
                $searchCompanyFilterId = [$companyFilterId];
            }
        } else {
            $searchCompanyFilterId = [$dataStaffLogin->companyId()];
            $companyFilterId = $dataStaffLogin->companyId();
        }

        if($monthFilter < 8 && $yearFilter < 2109){ # du lieu cu phien ban cu --  loc theo staff_id
            if (!empty($nameFiler)) {
                $listStaffId = $modelStaff->listIdOfListCompanyAndName($searchCompanyFilterId, $nameFiler);
            } else {
                $listStaffId = $modelStaff->listIdOfListCompany($searchCompanyFilterId);
            }
            $listWorkId = $modelWork->listIdOfListStaffInBeginDate($listStaffId, $dateFilter);
        }else{ # du lieu phien ban moi - loc theo thong tin lam viec tai cty (companyStaffWork)
            if (!empty($nameFiler)) {
                $listCompanyStaffWorkId = $modelCompanyStaffWork->listIdOfListCompanyAndListStaff($searchCompanyFilterId, $modelStaff->listStaffIdByName($nameFiler));
            } else {
                $listCompanyStaffWorkId = $modelCompanyStaffWork->listIdOfListCompanyAndListStaff($searchCompanyFilterId);
            }
            $listWorkId = $modelWork->listIdOfListCompanyStaffWorkBeginDate($listCompanyStaffWorkId, $dateFilter);
        }

        if(empty($punishContentFilterId)){
            $dataMinusMoney = QcMinusMoney::where('dateMinus', 'like', "%$dateFilter%")->whereIn('work_id', $listWorkId)->orderBy('dateMinus', 'DESC')->select('*')->paginate(30);
            $totalMinusMoney = QcMinusMoney::where('dateMinus', 'like', "%$dateFilter%")->whereIn('work_id', $listWorkId)->sum('money');
        }else{
            $dataMinusMoney = QcMinusMoney::where('punish_id',$punishContentFilterId)->where('dateMinus', 'like', "%$dateFilter%")->whereIn('work_id', $listWorkId)->orderBy('dateMinus', 'DESC')->select('*')->paginate(30);
            $totalMinusMoney = QcMinusMoney::where('punish_id',$punishContentFilterId)->where('dateMinus', 'like', "%$dateFilter%")->whereIn('work_id', $listWorkId)->sum('money');
        }

        return view('ad3d.finance.minus-money.list', compact('modelStaff', 'dataCompany', 'dataAccess','dataPunishContent', 'dataMinusMoney', 'totalMinusMoney', 'companyFilterId', 'dayFilter', 'monthFilter', 'yearFilter','punishContentFilterId', 'nameFiler'));

    }

    public function view($minusId)
    {
        $modelMinusMoney = new QcMinusMoney();
        $dataMinusMoney = $modelMinusMoney->getInfo($minusId);
        if (count($dataMinusMoney) > 0) {
            return view('ad3d.finance.minus-money.view', compact('dataMinusMoney'));
        }
    }

    public function getAdd($companyLoginId = null, $workId = null, $punishId = null)
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

    }

    public function getEdit($minusId)
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
    }

    public function deleteMinusMoney($minusId)
    {
        $modelMinus = new QcMinusMoney();
        $modelMinus->deleteInfo($minusId);
    }

}
