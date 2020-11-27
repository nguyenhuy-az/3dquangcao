<?php

namespace App\Http\Controllers\Ad3d\Finance\MinusMoney;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\CompanyStore\QcCompanyStore;
use App\Models\Ad3d\CompanyStoreCheckReport\QcCompanyStoreCheckReport;
use App\Models\Ad3d\MinusMoney\QcMinusMoney;
use App\Models\Ad3d\MinusMoneyFeedback\QcMinusMoneyFeedback;
use App\Models\Ad3d\PunishContent\QcPunishContent;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\StaffNotify\QcStaffNotify;
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
    public function index($companyFilterId = null, $dayFilter = 0, $monthFilter = 0, $yearFilter = 0, $punishContentFilterId = 0, $staffFilterId = 0)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelPunishContent = new QcPunishContent();
        $modelCompany = new QcCompany();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelWork = new QcWork();
        $modelMinus = new QcMinusMoney();
        $dataCompanyLogin = $modelStaff->companyLogin();
        $companyLoginId = $dataCompanyLogin->companyId();
        $companyFilterId = ($companyFilterId == 'null') ? null : $companyFilterId;
        if ($companyFilterId == null || $companyFilterId == 0) {
            $companyFilterId = $companyLoginId;
        }
        # lay thong tin cong ty cung he thong
        $dataCompany = $modelCompany->getInfoSameSystemOfCompany($companyLoginId);
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
        $punishContentFilterId = ($punishContentFilterId == 0) ? null : $punishContentFilterId;
        $dataMinusMoney = $modelMinus->selectInfoHasFilter($listWorkId, $punishContentFilterId, $dateFilter)->paginate(30);
        $totalMinusMoney = $modelMinus->totalMoneyHasFilter($listWorkId, $punishContentFilterId, $dateFilter);
        # danh muc phat cua cong ty
        $dataPunishContent = $modelPunishContent->getInfo();
        //danh sach NV
        $dataStaffFilter = $modelCompany->staffInfoActivityOfListCompanyId([$companyFilterId]);
        return view('ad3d.finance.minus-money.list', compact('modelStaff', 'dataStaffFilter', 'dataCompany', 'dataAccess', 'dataPunishContent', 'dataMinusMoney', 'totalMinusMoney', 'companyFilterId', 'dayFilter', 'monthFilter', 'yearFilter', 'punishContentFilterId', 'staffFilterId'));

    }

    # huy phat
    public function cancelMinusMoney($minusId)
    {
        $modelMinus = new QcMinusMoney();
        $modelMinus->cancelMinus($minusId);
    }

    # xem anh phat
    public function viewImage($minusId){
        $modelMinus = new QcMinusMoney();
        $dataMinusMoney = $modelMinus->getInfo($minusId);
        return view('ad3d.finance.minus-money.view-image', compact('dataMinusMoney'));
    }

    # xem anh phan hoi
    public function viewFeedbackImage($feedbackId)
    {
        $modelMinusMoneyFeedback = new QcMinusMoneyFeedback();
        $dataMinusMoneyFeedback = $modelMinusMoneyFeedback->getInfo($feedbackId);
        return view('ad3d.finance.minus-money.view-feedback-image', compact('dataMinusMoneyFeedback'));
    }

    # Xac nhan phan hoi
    public function getConfirmFeedback($feedbackId)
    {
        $modelMinusMoneyFeedback = new QcMinusMoneyFeedback();
        $dataMinusMoneyFeedback = $modelMinusMoneyFeedback->getInfo($feedbackId);
        return view('ad3d.finance.minus-money.confirm-feedback', compact('dataMinusMoneyFeedback'));
    }

    public function postConfirmFeedback($feedbackId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelStaffNotify = new QcStaffNotify();
        $modelPunishContent = new QcPunishContent();
        $modelMinusMoney = new QcMinusMoney();
        $modelCompanyStore = new QcCompanyStore();
        $modelCompanyStoreCheckReport = new QcCompanyStoreCheckReport();
        $modelMinusMoneyFeedback = new QcMinusMoneyFeedback();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $confirmAccept = Request::input('cbConfirmAccept');
        if ($modelMinusMoneyFeedback->confirmFeedback($feedbackId, $confirmAccept, $dataStaffLogin->staffId())) {
            if ($confirmAccept == 1) { # dong y
                $dataMinusMoneyFeedback = $modelMinusMoneyFeedback->getInfo($feedbackId);
                # lay thong tin phat bao cao mat do nghe
                $dataMinusMoney = $dataMinusMoneyFeedback->minusMoney;
                $minusId = $dataMinusMoney->minusId();
                $minusReportId = $dataMinusMoney->companyStoreCheckReportId();
                # huy thong tin phat
                $modelMinusMoney->cancelMinus($minusId);
                # cap nhat lai trang thai su dung do nghe trong kho
                $modelCompanyStore->updateNormalUseStatus($modelCompanyStoreCheckReport->storeId($minusReportId));
                # ma ap dung phat trong he thong - phat bao lam mat do nghe khong dung
                $punishId = $modelPunishContent->getPunishIdWrongReportLostTool();
                $punishId = (is_int($punishId)) ? $punishId : $punishId[0];
                # da co ap dung phat
                if ($punishId > 0) {
                    # lay thong tin bao mat do nghe
                    $dataCompanyStoreCheckReport = $modelCompanyStoreCheckReport->infoReportLostOfReport($minusReportId);
                    if ($hFunction->checkCount($dataCompanyStoreCheckReport)) {
                        $punishReportId = $dataCompanyStoreCheckReport->reportId();
                        $dataCompanyStaffWork = $dataCompanyStoreCheckReport->companyStoreCheck->companyStaffWork;
                        $dataWork = $dataCompanyStaffWork->workInfoActivity();
                        $workId = $dataWork->workId();
                        if (!$modelMinusMoney->checkExistMinusMoneyReportWrongLostTool($punishReportId, $workId)) { # chua phat
                            if ($modelMinusMoney->insert($hFunction->carbonNow(), 'Báo cáo mất đồ nghề không đúng', $workId, null, $punishId, 0, null, null, $punishReportId)) {
                                $modelStaffNotify->insert(null, $dataCompanyStaffWork->staffId(), 'Báo cáo mất đồ nghề không đúng', null, null, null, $modelMinusMoney->insertGetId());
                            }
                        }

                    }
                }

            }
        }
    }

    # phat truc tiep
    public function getAdd($companyLoginId, $workId = null, $punishId = null)
    {
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelWork = new QcWork();
        $modelPunishContent = new QcPunishContent();
        $dataCompanyLogin = $modelStaff->companyLogin();
        $companyLoginId = $dataCompanyLogin->companyId();
        $dataAccess = [
            'accessObject' => 'penalize'
        ];
        /*$dataStaffLogin = $modelStaff->loginStaffInfo();
        if (empty($companyLoginId)) {
            $companyLoginId = $dataStaffLogin->companyId();
        }
        $dataCompany = $modelCompany->getInfo($companyLoginId);*/
        $listCompanyStaffWorkId = $modelCompanyStaffWork->listIdActivityOfListCompanyAndListStaff([$companyLoginId], null);
        $dataWork = $modelWork->infoActivityOfListCompanyStaffWork($listCompanyStaffWorkId);
        //$dataWork = $modelWork->infoActivityOfListStaff($modelStaff->listIdOfCompany($companyLoginId));
        $dataWorkSelect = (empty($workId)) ? null : $modelWork->getInfo($workId);
        // danh muc phat co muc phat truc tiep
        $dataPunishContent = $modelPunishContent->getInfoForDirectMinusMoney();
        $dataPunishContentSelected = (empty($punishId)) ? null : $modelPunishContent->getInfo($punishId);
        return view('ad3d.finance.minus-money.add', compact('modelStaff', 'dataPunishContent', 'dataAccess', 'dataCompanyLogin', 'dataWork', 'dataWorkSelect', 'dataPunishContentSelected'));
    }

    public function postAdd()
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelMinusMoney = new QcMinusMoney();
        $workId = Request::input('cbWork');
        $cbPunishContentId = Request::input('cbPunishContent');
        $reason = Request::input('txtDescription');
        $txtReasonImage = Request::file('txtReasonImage');
        $name_img = null; // mac dinh null
        if (!empty($txtReasonImage)) {
            $name_img = stripslashes($_FILES['txtReasonImage']['name']);
            $name_img = $hFunction->getTimeCode() . '.' . $hFunction->getTypeImg($name_img);
            $source_img = $_FILES['txtReasonImage']['tmp_name'];
            $modelMinusMoney->uploadImage($source_img, $name_img);
        }
        if ($modelMinusMoney->insert($hFunction->carbonNow(), $reason, $workId, $modelStaff->loginStaffId(), $cbPunishContentId, $modelMinusMoney->getDefaultHasApplyStatus(), null, null, null, null, 0, $name_img)) {
            return Session::put('notifyAdd', 'Thêm thành công, chọn thông tin và tiếp tục');
        } else {
            return Session::put('notifyAdd', 'Thêm thất bại, hãy thử lại');
        }

    }

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
