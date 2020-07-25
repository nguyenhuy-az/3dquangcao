<?php

namespace App\Http\Controllers\Work\Tool\CheckStore;


use App\Models\Ad3d\CompanyStore\QcCompanyStore;
use App\Models\Ad3d\CompanyStoreCheck\QcCompanyStoreCheck;
use App\Models\Ad3d\CompanyStoreCheckReport\QcCompanyStoreCheckReport;
use App\Models\Ad3d\MinusMoney\QcMinusMoney;
use App\Models\Ad3d\PunishContent\QcPunishContent;
use App\Models\Ad3d\Staff\QcStaff;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use File;
use Input;
use Request;

class CheckStoreController extends Controller
{
    public function index($monthFilter = 0, $yearFilter = 0)
    {
        $modelStaff = new QcStaff();
        $modelCompanyStore = new QcCompanyStore();
        $modelCompanyStoreCheck = new QcCompanyStoreCheck();
        $dataAccess = [
            'object' => 'toolCheckStore'
        ];
        $dataStaff = $modelStaff->loginStaffInfo();
        #thong tin lam viec tai 1 cty
        //$dataCompanyStaffWork = $dataStaff->companyStaffWorkInfoActivity();
        $dataCompanyStoreCheck = $modelCompanyStoreCheck->infoReceiveStatusOfStaff($dataStaff->staffId());
        #do nghe dung chung cua he thong
        $dataCompanyStore = $modelCompanyStore->getPublicToolToCheckOfCompany($dataStaff->companyId());
        return view('work.tool.check-store.list', compact('dataAccess', 'modelStaff', 'dataCompanyStoreCheck', 'dataCompanyStore', 'monthFilter', 'yearFilter'));
    }

    // xac nhan kiem tra
    public function postConfirm()
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelPunishContent = new QcPunishContent();
        $modelMinusMoney = new QcMinusMoney();
        $modelCompanyStoreCheckReport = new QcCompanyStoreCheckReport();
        $txtCompanyCheckId = Request::input('txtCompanyCheck');
        $txtCompanyStore = Request::input('txtCompanyStore');
        foreach ($txtCompanyStore as $companyStoreId) {
            $useStatus = Request::input("cbUseStatus_$companyStoreId");
            if ($modelCompanyStoreCheckReport->insert($useStatus, $companyStoreId, $txtCompanyCheckId)) {
                $newReportId = $modelCompanyStoreCheckReport->insertGetId();
                # xac nhan bao mat
                if ($useStatus == 3) {
                    # ma ap dung phat trong he thong - phat mat do nghe
                    $punishId = $modelPunishContent->getPunishIdOfOrderConstructionLate();
                    $punishId = (is_int($punishId)) ? $punishId : $punishId[0];
                    if($punishId > 0){ # he thong co ap dung phat mat
                        # lay thong tin xac nhan cuoi cung cua dung cu - su dung binh thuong
                        $dataCompanyStoreCheckReport = $modelCompanyStoreCheckReport->lastInfoNormalUseOfCompanyStore($companyStoreId);
                        # neu ton tai
                        if ($hFunction->checkCount($dataCompanyStoreCheckReport)) {
                            # thong tin dung cu
                            $dataCompanyStore = $dataCompanyStoreCheckReport->companyStore;
                            $importPrice = $dataCompanyStore->importPrice();
                            $minusMoneyReportId = $dataCompanyStoreCheckReport->reportId();
                            # lay thong tin nguoi bi phat
                            $dataStaffMinusMoney = $dataCompanyStoreCheckReport->companyStoreCheck->staff;
                            $dataWork = $dataStaffMinusMoney->workInfoActivityOfStaff();
                            if ($hFunction->checkCount($dataWork)) {
                                $workId = $dataWork->workId();
                                /*if (!$modelMinusMoney->checkExistMinusMoneyOrderConstructionLate($orderId, $workId)) { # chua phat
                                    $punishId = (is_int($punishId)) ? $punishId : $punishId[0];
                                    if ($modelMinusMoney->insert($checkDate, 'Quản lý thi công trễ đơn hàng', $workId, null, $punishId, 0, null, $orderId)) {
                                        $modelStaffNotify->insert(null, $staffMinusMoneyId, 'Quản lý thi công trễ đơn hàng', null, null, null, $modelMinusMoney->insertGetId());
                                    }
                                }*/
                            }
                        }  
                    }
                }
            }
        }
        dd($txtCompanyStore);
    }
}
