<?php

namespace App\Http\Controllers\Ad3d\Store\Import;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\CompanyStore\QcCompanyStore;
use App\Models\Ad3d\Import\QcImport;
use App\Models\Ad3d\ImportDetail\QcImportDetail;
use App\Models\Ad3d\ImportPay\QcImportPay;
use App\Models\Ad3d\Staff\QcStaff;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ad3d\Supplies\QcSupplies;
use App\Models\Ad3d\Tool\QcTool;
use App\Models\Ad3d\ToolAllocation\QcToolAllocation;
use App\Models\Ad3d\ToolAllocationDetail\QcToolAllocationDetail;
use Illuminate\Support\Facades\Session;
use Input;
use File;
use DB;
use Request;


class ImportController extends Controller
{
    public function index($companyFilterId = null, $dayFilter = null, $monthFilter = null, $yearFilter = null, $payStatusFilter = 4, $staffFilterId = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelImport = new QcImport();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataAccess = [
            'accessObject' => 'import'
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

        if ($staffFilterId > 0) {
            $listStaffId = [$staffFilterId];//$modelStaff->listIdOfListCompanyAndName($searchCompanyFilterId, $staffFilterName);
        } else {
            $listStaffId = $modelStaff->listIdOfListCompany($searchCompanyFilterId);
        }

        if (empty($dayFilter) && empty($monthFilter) && empty($yearFilter)) {
            //$dateFilter = date('Y-m-d');
            //$dayFilter = date('d');
            $monthFilter = date('m');
            $yearFilter = date('Y');
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        } elseif (empty($dayFilter) && empty($monthFilter) && !empty($yearFilter)) {
            $dateFilter = date('Y', strtotime("1-1-$yearFilter"));
        } elseif ($dayFilter == 0) { //xem t?t c? các ngày trong tháng
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        } else {
            $dateFilter = date('Y-m-d', strtotime("$dayFilter-$monthFilter-$yearFilter"));
        }
        $dataImportAll = $modelImport->getInfoHaveFilter($listStaffId, $searchCompanyFilterId, $dateFilter, $payStatusFilter, 'DESC');
        $dataImport = $dataImportAll->paginate(30);
        $importTotalMoney = $modelImport->totalMoneyOfListImport($dataImportAll->get());
        //danh sach NV
        if (empty($companyFilterId)) {
            $dataListStaff = $modelStaff->getInfoActivityByListStaffId($listStaffId);
        } else {
            $dataListStaff = $modelCompany->staffInfoActivityOfListCompanyId([$companyFilterId]);
        }
        return view('ad3d.store.import.import.list', compact('modelStaff', 'dataCompany', 'dataAccess', 'dataListStaff', 'dataImport', 'importTotalMoney', 'companyFilterId', 'dayFilter', 'monthFilter', 'yearFilter', 'payStatusFilter', 'staffFilterId'));

    }

    public function viewImport($importId)
    {
        $modelStaff = new QcStaff();
        $modelImport = new QcImport();
        $dataImport = $modelImport->getInfo($importId);
        $dataAccess = [
            'accessObject' => 'import'
        ];
        return view('ad3d.store.import.import.import-detail', compact('modelStaff', 'dataAccess', 'dataImport'));
    }

    public function getConfirm($importId)
    {
        $modelStaff = new QcStaff();
        $modelImport = new QcImport();
        $dataImport = $modelImport->getInfo($importId);
        $dataAccess = [
            'accessObject' => 'import'
        ];
        return view('ad3d.store.import.import.import-confirm', compact('modelStaff', 'dataAccess', 'dataImport'));
    }

    public function postConfirm($importId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelSupplies = new QcSupplies();
        $modelTool = new QcTool();
        $modelImport = new QcImport();
        $modelImportDetail = new QcImportDetail();
        $modelImportPay = new QcImportPay();
        $modelCompanyStore = new QcCompanyStore();
        $modelToolAllocation = new QcToolAllocation();
        $modelToolAllocationDetail = new QcToolAllocationDetail();
        $loginStaffId = $modelStaff->loginStaffId();
        $importCompanyId = $modelImport->companyId($importId)[0];
        $importStaffId = $modelImport->importStaffId($importId)[0];

        $confirmDetailId = Request::input('txtDetail');
        $confirmNewSuppliesTool = Request::input('cbNewSuppliesTool');
        $unit = Request::input('txtUnit');
        $allocationStatus = Request::input('cbAllocationStatus');
        $confirmPayStatus = Request::input('cbPayStatus');
        $confirmNote = Request::input('txtConfirmNote');
        $confirmPayStatus = (empty($confirmPayStatus)) ? 0 : $confirmPayStatus;
        if ($confirmPayStatus == 2) {
            $exactlyStatus = 0;
            $confirmPayStatus = 0;
        } else {
            $exactlyStatus = 1;
        }
        //$exactlyStatus = ($confirmPayStatus == 2) ? 0 : 1;  //$confirmPayStatus = nhap khong chinh xac
        $dataImportDetail = $modelImportDetail->infoOfImport($importId);
        if ($modelImport->confirmImport($importId, $confirmPayStatus, $exactlyStatus, $loginStaffId, $confirmNote)) {
            if ($confirmPayStatus < 2) { // nhap chinh xac
                //thanh toan cho nguoi mua
                if ($confirmPayStatus == 1) {
                    $modelImportPay->insert($modelImport->totalMoneyOfImport($importId), $importId, $loginStaffId);
                }
                //cap nhat chi tiet
                foreach ($dataImportDetail as $key => $importDetail) {
                    $detailId = $importDetail->detailId();
                    $importPrice = $importDetail->price();
                    $importAmount = $importDetail->amount();
                    $importTotalMoney = $importDetail->totalMoney();
                    $importToolId = $importDetail->toolId();
                    $importSuppliesId = $importDetail->suppliesId();
                    $importNewName = $importDetail->newName();
                    if ($detailId == $confirmDetailId[$key]) {
                        if (!empty($importNewName)) {
                            // vat lieu moi
                            if ($confirmNewSuppliesTool[$key] == 1) {
                                // 1 - phan loai vat tu
                                $dataCheckSupplies = $modelSupplies->getInfoByName($importNewName);
                                #kiem tra vat đa ton tai theo ten
                                if (count($dataCheckSupplies) > 0) {
                                    $suppliesId = $dataCheckSupplies->suppliesId();
                                } else {
                                    //thêm dụng cụ mới vào hệ thống
                                    if ($modelSupplies->insert($importNewName, $unit[$key])) {
                                        $suppliesId = $modelSupplies->insertGetId();
                                    } else {
                                        $suppliesId = null;
                                    }
                                }
                                #thêm vật tư mới vào hệ thống
                                if (!empty($suppliesId)) {
                                    // cập nhật chi tiết nhập
                                    $modelImportDetail->updateInfo($detailId, $importPrice, $importAmount, $importTotalMoney, $importToolId, $suppliesId, $importNewName);
                                    // thêm vậy tư vào kho
                                    $modelCompanyStore->insert($importAmount, $importCompanyId, null, $suppliesId);
                                }
                            } elseif ($confirmNewSuppliesTool[$key] == 2) {
                                // phan loai dung cu
                                $dataCheckTool = $modelTool->getInfoByName($importNewName);
                                #kiem tra cong cu đa ton tai theo ten
                                if (count($dataCheckTool) > 0) {
                                    $toolId = $dataCheckTool->toolId();
                                } else {
                                    //thêm dụng cụ mới vào hệ thống
                                    if ($modelTool->insert($importNewName, $unit[$key], '')) {
                                        $toolId = $modelTool->insertGetId();
                                    } else {
                                        $toolId = null;
                                    }
                                }
                                if (!empty($toolId)) {
                                    // cập nhật lại chi tiết nhập
                                    $modelImportDetail->updateInfo($detailId, $importPrice, $importAmount, $importTotalMoney, $toolId, $importSuppliesId, $importNewName);

                                    // thêm dụng cụ mới vào kho
                                    if ($modelCompanyStore->insert($importAmount, $importCompanyId, $toolId, null)) {
                                        $newStoreId = $modelCompanyStore->insertGetId();
                                        // cap phat dụng cụ cho nhân viên mua
                                        if ($allocationStatus[$key] == 1) { // phát luôn cho nhân viên
                                            if ($modelToolAllocation->insert($hFunction->carbonNow(), $loginStaffId, $importStaffId)) {  //phieu cap phat dung cu
                                                $newAllocationId = $modelToolAllocation->insertGetId();
                                                $modelToolAllocationDetail->insert($importAmount, 1, $toolId, $newAllocationId, $importCompanyId, $newStoreId); // chi tiet cap phat
                                            }
                                        }
                                    }

                                }
                            }
                        } else {
                            // cap nhat kho
                            if (!empty($importSuppliesId)) { // vat tu
                                if ($modelCompanyStore->existOfSuppliesAndCompany($importSuppliesId, $importCompanyId)) { //da ton tai trong kho -> cap nhat so luong
                                    $modelCompanyStore->updateInfoByToolOrSupplies($importCompanyId, $importAmount, null, $importSuppliesId);
                                } else { // thêm vat tu vào kho
                                    $modelCompanyStore->insert($importAmount, $importCompanyId, null, $importSuppliesId);
                                }
                            }
                            if (!empty($importToolId)) { //dung cu
                                $dataCompanyStore = $modelCompanyStore->infoOfToolAndCompany($importToolId, $importCompanyId);
                                if (count($dataCompanyStore) > 0) { // da ton tai trong kho -> cap nhat so luong
                                    $modelCompanyStore->updateInfoByToolOrSupplies($importCompanyId, $importAmount, $importToolId, null);
                                    $storeId = $dataCompanyStore->storeId();
                                } else { // then dung cu vao kho
                                    $modelCompanyStore->insert($importAmount, $importCompanyId, $importToolId, null);
                                    $storeId = $modelCompanyStore->insertGetId();
                                }
                                // cap phat dụng cu cho nhân viên mua
                                if ($allocationStatus[$key] == 1) { // phát luôn cho nhân viên
                                    if ($modelToolAllocation->insert($hFunction->carbonNow(), $loginStaffId, $importStaffId)) {  //phieu cap dung cu
                                        $newAllocationId = $modelToolAllocation->insertGetId();
                                        $modelToolAllocationDetail->insert($importAmount, 1, $importToolId, $newAllocationId, $importCompanyId, $storeId); // chi tiet cap
                                    }
                                }
                            }
                        }
                    }
                }
            }

        }
       // return redirect()->back();// route('qc.ad3d.store.import.get');
    }

    public function getPay($importId)
    {
        $modelImport = new QcImport();
        $dataImport = $modelImport->getInfo($importId);
        return view('ad3d.store.import.import.pay', compact('modelStaff', 'dataImport'));
    }

    public function postPay($importId)
    {
        $modelStaff = new QcStaff();
        $modelImport = new QcImport();
        $modelImportPay = new QcImportPay();
        $dataImport = $modelImport->getInfo($importId);
        if (count($dataImport) > 0) {
            $totalPay = $dataImport->totalMoneyOfImport();
            if ($modelImportPay->insert($totalPay, $importId, $modelStaff->loginStaffId())) {
                $modelImport->confirmPaid($importId);
            }
        }
    }
}
