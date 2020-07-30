<?php

namespace App\Http\Controllers\Work\Tool\CheckStore;


use App\Models\Ad3d\CompanyStore\QcCompanyStore;
use App\Models\Ad3d\CompanyStoreCheck\QcCompanyStoreCheck;
use App\Models\Ad3d\CompanyStoreCheckReport\QcCompanyStoreCheckReport;
use App\Models\Ad3d\ImportImage\QcImportImage;
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
    public function index($checkIdFilter = 0)
    {
        $modelStaff = new QcStaff();
        $modelCompanyStore = new QcCompanyStore();
        $modelCompanyStoreCheck = new QcCompanyStoreCheck();
        $dataAccess = [
            'object' => 'toolCheckStore'
        ];
        $dataStaff = $modelStaff->loginStaffInfo();
        $dataCompanyStaffWorkLogin = $modelStaff->loginCompanyStaffWork();
        $staffWorkId = $dataCompanyStaffWorkLogin->workId();
        # lay tat ca thong tin ban giao kiem tra
        $dataCompanyStoreCheck = $modelCompanyStoreCheck->getAllInfoOfWork($staffWorkId);
        # thong tin hien thi
        if ($checkIdFilter == 0) {
            # thong tin phan cong giao viec sau cung
            $dataCompanyStoreCheckSelected = $modelCompanyStoreCheck->lastInfoOfWork($staffWorkId);
        } else {
            $dataCompanyStoreCheckSelected = $modelCompanyStoreCheck->getInfo($checkIdFilter);
        }

        #do nghe dung chung cua he thong
        $dataCompanyStore = $modelCompanyStore->getPublicToolToCheckOfCompany($dataStaff->companyId());
        return view('work.tool.check-store.list', compact('dataAccess', 'modelStaff', 'dataCompanyStoreCheck', 'dataCompanyStoreCheckSelected', 'dataCompanyStore', 'checkIdFilter'));
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
                    # kiem tra them anh bao cao
                    $reportImage = null;
                    $txtReportImage = Request::file('txtReportImage_' . $companyStoreId);
                    if (!empty($txtReportImage)) {
                        $name_img = stripslashes($_FILES['txtReportImage_' . $companyStoreId]['name']);
                        $name_img = $companyStoreId . "_rp_" . $hFunction->getTimeCode() . '.' . $hFunction->getTypeImg($name_img);
                        $source_img = $_FILES['txtReportImage_' . $companyStoreId]['tmp_name'];
                        # up anh do nghe
                        if ($modelCompanyStoreCheckReport->uploadImage($source_img, $name_img, 500)) {
                            # chi tra khi co anh ban giao
                            $reportImage = $name_img;
                        }
                    }
                    //echo "$companyStoreId $reportImage <br/>";
                    //die();
                    if ($modelCompanyStoreCheckReport->insert($useStatus, $reportImage, $companyStoreId, $txtCompanyCheckId, $confirmStatus, $confirmNote, $confirmDate)) {
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
                                    $dataCompanyStaffWork = $dataCompanyStoreCheckReport->companyStoreCheck->companyStaffWork;
                                    $dataWork = $dataCompanyStaffWork->workInfoActivity();
                                    if ($hFunction->checkCount($dataWork)) {
                                        $workId = $dataWork->workId();
                                        if (!$modelMinusMoney->checkExistMinusMoneyLostPublicTool($minusMoneyReportId, $workId)) { # chua phat
                                            if ($modelMinusMoney->insert($hFunction->carbonNow(), 'Mất đồ nghề dùng chung', $workId, null, $punishId, 0, null, null, $minusMoneyReportId)) {
                                                $modelStaffNotify->insert(null, $dataCompanyStaffWork->staffId(), 'Mất kiểm tra mất đồ nghề dùng chung', null, null, null, $modelMinusMoney->insertGetId());
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

    # xem anh nhap kho
    public function viewImportImage($imageId)
    {
        $modelImportImage = new QcImportImage();
        $dataImportImage = $modelImportImage->getInfo($imageId);
        return view('work.tool.check-store.view-import-image', compact('modelStaff', 'dataImportImage'));
    }

    # xem anh bao cao do nghe
    public function viewReportImage($reportId)
    {
        $modelCompanyStoreCheckReport = new QcCompanyStoreCheckReport();
        $dataCompanyStoreCheckReport = $modelCompanyStoreCheckReport->getInfo($reportId);
        return view('work.tool.check-store.view-report-image', compact('modelStaff', 'dataCompanyStoreCheckReport'));
    }
}
