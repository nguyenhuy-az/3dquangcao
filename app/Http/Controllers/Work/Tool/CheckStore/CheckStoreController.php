<?php

namespace App\Http\Controllers\Work\Tool\CheckStore;


use App\Models\Ad3d\CompanyStore\QcCompanyStore;
use App\Models\Ad3d\CompanyStoreCheck\QcCompanyStoreCheck;
use App\Models\Ad3d\CompanyStoreCheckReport\QcCompanyStoreCheckReport;
use App\Models\Ad3d\MinusMoney\QcMinusMoney;
use App\Models\Ad3d\PunishContent\QcPunishContent;
use App\Models\Ad3d\Staff\QcStaff;
//use Illuminate\Http\Request;
use App\Models\Ad3d\StaffNotify\QcStaffNotify;
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
        # thong tin phan cong giao viec sau cung
        $dataCompanyStoreCheck = $modelCompanyStoreCheck->lastInfoOfStaff($dataStaff->staffId());
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
        $modelStaffNotify = new QcStaffNotify();
        $modelCompanyStore = new QcCompanyStore();
        $modelCompanyStoreCheck = new QcCompanyStoreCheck();
        $modelCompanyStoreCheckReport = new QcCompanyStoreCheckReport();
        $txtCompanyCheckId = Request::input('txtCompanyCheck');
        $txtCompanyStore = Request::input('txtCompanyStore');
        if ($modelCompanyStoreCheck->confirmCheck($txtCompanyCheckId)) {
            foreach ($txtCompanyStore as $companyStoreId) {
                $useStatus = Request::input("cbUseStatus_$companyStoreId");
                if (!$modelCompanyStoreCheckReport->checkExistReportOfCompanyCheck($companyStoreId, $txtCompanyCheckId)) {
                    if ($useStatus == 1) { # su dung binh thuong he thong tu xac nhan
                        $confirmStatus = 1;
                        $confirmNote = 'Xác nhận mặc định';
                        $confirmDate = $hFunction->carbonNow();
                    } else {
                        $confirmStatus = 0;
                        $confirmNote = null;
                        $confirmDate = null;
                    }
                    if ($modelCompanyStoreCheckReport->insert($useStatus, $companyStoreId, $txtCompanyCheckId, $confirmStatus, $confirmNote, $confirmDate)) {
                        # cap nhat trang thai su dung neu thay doi
                        if ($useStatus == 2 || $useStatus == 3) $modelCompanyStore->updateUseStatus($companyStoreId, $useStatus);
                        # xac nhan bao cao bi mat
                        if ($useStatus == 3) {
                            # ma ap dung phat trong he thong - phat mat do nghe
                            $punishId = $modelPunishContent->getPunishIdLostPublicTool();
                            $punishId = (is_int($punishId)) ? $punishId : $punishId[0];
                            if ($punishId > 0) { # he thong co ap dung phat mat
                                # lay thong tin xac nhan cuoi cung cua dung cu - su dung binh thuong
                                $dataCompanyStoreCheckReport = $modelCompanyStoreCheckReport->lastInfoNormalUseOfCompanyStore($companyStoreId);
                                # neu ton tai
                                if ($hFunction->checkCount($dataCompanyStoreCheckReport)) {
                                    $minusMoneyReportId = $dataCompanyStoreCheckReport->reportId();
                                    # lay thong tin nguoi bi phat
                                    $dataStaffMinusMoney = $dataCompanyStoreCheckReport->companyStoreCheck->staff;
                                    $dataWork = $dataStaffMinusMoney->workInfoActivityOfStaff();
                                    if ($hFunction->checkCount($dataWork)) {
                                        $workId = $dataWork->workId();
                                        if (!$modelMinusMoney->checkExistMinusMoneyLostPublicTool($minusMoneyReportId, $workId)) { # chua phat
                                            if ($modelMinusMoney->insert($hFunction->carbonNow(), 'Mất đồ nghề dùng chung', $workId, null, $punishId, 0, null, null, $minusMoneyReportId)) {
                                                $modelStaffNotify->insert(null, $dataStaffMinusMoney->staffId(), 'Mất kiểm tra mất đồ nghề dùng chung', null, null, null, $modelMinusMoney->insertGetId());
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

    }
}
