<?php

namespace App\Http\Controllers\Work\Pay\Import;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\CompanyStore\QcCompanyStore;
use App\Models\Ad3d\Import\QcImport;
use App\Models\Ad3d\ImportDetail\QcImportDetail;
use App\Models\Ad3d\ImportPay\QcImportPay;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\Supplies\QcSupplies;
use App\Models\Ad3d\Tool\QcTool;
use App\Models\Ad3d\ToolPackageAllocationDetail\QcToolPackageAllocationDetail;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use File;
use Input;
use Request;

class ImportController extends Controller
{
    public function index($monthFilter = 0, $yearFilter = 0, $payStatusFilter = 3, $staffFilterId = null)
    {
        $hFunction = new \Hfunction();
        $modelCompany = new QcCompany();
        $modelStaff = new QcStaff();
        $modelImport = new QcImport();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataAccess = [
            'object' => 'payImport',
            'subObjectLabel' => 'Mua vật tư'
        ];
        $companyId = $dataStaffLogin->companyId();
        if ($staffFilterId > 0) {
            $listStaffId = [$staffFilterId];
        } else {
            $listStaffId = $modelStaff->listIdOfCompany($companyId);//listIdOfListCompany($searchCompanyFilterId);
        }

        if ($monthFilter == 100 && $yearFilter == 100) {//xem tất cả đơn hang
            $dateFilter = null;
        } elseif ($monthFilter < 100 && $yearFilter == 100) {
            $dateFilter = date('Y');
            $yearFilter = date('Y');
        } elseif ($monthFilter == 100 && $yearFilter != 100) {
            if ($yearFilter == 0) $yearFilter = date('Y');// else $yearFilter =
            $dateFilter = date('Y', strtotime("1-1-$yearFilter"));
        } elseif ($monthFilter < 100 && $yearFilter == 100) {
            $yearFilter = $hFunction->currentYear();
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        } elseif ($monthFilter == 0 && $yearFilter == 0) {
            $yearFilter = date('Y');
            $dateFilter = date('Y');
        } else {
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        }
        if ($payStatusFilter == 3) { # chon tat ca
            $dataImportAll = $modelImport->selectAllInfoOfListStaffId($companyId, $listStaffId, $dateFilter);
        } else { # chon theo trang thai thanh toan
            $dataImportAll = $modelImport->selectInfoOfListStaffIdAndPayStatus($companyId, $listStaffId, $payStatusFilter, $dateFilter);
        }
        $dataImport = $dataImportAll->paginate(30);
        $importTotalMoney = $modelImport->totalMoneyOfListImport($dataImportAll->get());
        //danh sach NV
        $dataListStaff = $modelCompany->staffInfoActivityOfCompanyId($companyId);//staffInfoActivityOfListCompanyId([$companyId]);
        return view('work.pay.import.list', compact('dataAccess', 'modelStaff', 'modelImport', 'dataListStaff', 'dataImport', 'importTotalMoney', 'dayFilter', 'monthFilter', 'yearFilter', 'payStatusFilter', 'staffFilterId'));

    }

    # ======== ====== Xem chi tiet ========= ========
    public function viewImport($importId)
    {
        $modelStaff = new QcStaff();
        $modelImport = new QcImport();
        $dataImport = $modelImport->getInfo($importId);
        return view('work.pay.import.view', compact('modelStaff', 'dataImport'));
    }

    # ======== ====== xac nhan hoa don mua ========= ========
    public function getConfirm($importId)
    {
        $modelStaff = new QcStaff();
        $modelImport = new QcImport();
        $dataImport = $modelImport->getInfo($importId);
        return view('work.pay.import.confirm', compact('modelStaff','modelImport', 'dataImport'));
    }

    public function postConfirm($importId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelSupplies = new QcSupplies();
        $modelTool = new QcTool();
        $modelImport = new QcImport();
        $modelImportDetail = new QcImportDetail();
        $modelImportPay = new QcImportPay();
        $modelCompanyStore = new QcCompanyStore();
        $modelToolPackageAllocationDetail = new QcToolPackageAllocationDetail();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $loginStaffId = $dataStaffLogin->staffId();
        $importCompanyId = $modelImport->companyId($importId);
        $importStaffId = $modelImport->importStaffId($importId);
        # lay thong tin dang lam viec tai cong ty cua 1 nhan vien
        $dataCompanyStaffWork = $modelCompanyStaffWork->infoActivityOfStaff($importStaffId);

        # lay trang thai thanh toan mac dinh
        $getDefaultNotPay = $modelImport->getDefaultNotPay(); // chua thanh toan
        $getDefaultHasPay = $modelImport->getDefaultHasPay(); // da thanh toan
        $confirmDetailId = Request::input('txtDetail');
        $confirmNewSuppliesTool = Request::input('cbNewSuppliesTool');
        $unit = Request::input('txtUnit');
        $allocationStatus = Request::input('cbAllocationStatus');
        $confirmPayStatus = Request::input('cbPayStatus');
        $confirmNote = Request::input('txtConfirmNote');
        if ($confirmPayStatus == $getDefaultNotPay || $confirmPayStatus == $getDefaultHasPay) { # duyet va chua thanh toan |duyet va thanh toan
            # nhap chinh xac mac dinh
            $exactlyStatus = $modelImport->getDefaultHasExactly();
        } else {
            # khong duyet - nhap khong chính xac
            $exactlyStatus = $modelImport->getDefaultNotExactly();
        }
        # chi tiet hoa don mua
        $dataImportDetail = $modelImportDetail->infoOfImport($importId);
        # xac nhan hoa don
        if ($modelImport->confirmImport($importId, $confirmPayStatus, $exactlyStatus, $loginStaffId, $confirmNote)) {
            # nhap chinh xac - dươc duỵet
            if ($modelImport->checkHasExactlyStatus($importId)) {
                //co thanh toan cho nguoi mua
                if ($confirmPayStatus == 1) {
                    $modelImportPay->insert($modelImport->totalMoneyOfImport($importId), $importId, $loginStaffId);
                }
                //cap nhat chi tiet
                foreach ($dataImportDetail as $key => $importDetail) {
                    $detailId = $importDetail->detailId();
                    $importPriceAmount = $importDetail->price();
                    $importAmount = $importDetail->amount();
                    $importTotalMoney = $importDetail->totalMoney();
                    $importToolId = $importDetail->toolId();
                    $importSuppliesId = $importDetail->suppliesId();
                    $importNewName = $importDetail->newName();
                    # gia tren 1 don vi
                    $importPrice = (int)($importPriceAmount);
                    //$importNewUnit = $importDetail->newUnit();
                    if ($detailId == $confirmDetailId[$key]) {
                        $toolType = $allocationStatus[$key];
                        if (!empty($importNewName)) { # vat lieu moi
                            if ($confirmNewSuppliesTool[$key] == 1) {
                                // 1 - phan loai la vat tu
                                $dataCheckSupplies = $modelSupplies->getInfoByName($importNewName);
                                #kiem tra vat đa ton tai theo ten
                                if ($hFunction->checkCount($dataCheckSupplies)) {
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
                                    #cập nhật chi tiết nhập
                                    $modelImportDetail->updateInfo($detailId, $importPriceAmount, $importAmount, $importTotalMoney, $importToolId, $suppliesId, $importNewName);
                                    #thêm vậy tư vào kho
                                    $modelCompanyStore->insert($importNewName, $importCompanyId, null, $suppliesId, null, $importPrice, null);
                                }
                            } elseif ($confirmNewSuppliesTool[$key] == 2) {
                                // phan loai la dung cu
                                $dataCheckTool = $modelTool->getInfoByName($importNewName);
                                #kiem tra cong cu đa ton tai theo ten
                                if (count($dataCheckTool) > 0) {
                                    $toolId = $dataCheckTool->toolId();
                                } else {
                                    //thêm dụng cụ mới vào hệ thống
                                    if ($modelTool->insert($importNewName, $unit[$key], '', $toolType)) {
                                        $toolId = $modelTool->insertGetId();
                                    } else {
                                        $toolId = null;
                                    }
                                }
                                if (!empty($toolId)) {
                                    // cập nhật lại chi tiết nhập
                                    $modelImportDetail->updateInfo($detailId, $importPriceAmount, $importAmount, $importTotalMoney, $toolId, $importSuppliesId, $importNewName);

                                    // thêm dụng cụ mới vào kho
                                    # la dung cu dung phat cho nv
                                    if ($modelTool->checkPrivateType($toolId)) {
                                        # thong tin tui do nghe dc giao
                                        $dataToolPackageAllocation = $dataCompanyStaffWork->toolAllocationActivityOfWork();
                                        if ($hFunction->checkCount($dataToolPackageAllocation)) { # da co tui do nghe
                                            $allocationId = $dataToolPackageAllocation->allocationId();
                                            $packageId = $dataToolPackageAllocation->packageId();
                                        } else {
                                            $allocationId = null;
                                            $packageId = null;
                                        }
                                    } else {
                                        $allocationId = null;
                                        $packageId = null;
                                    }
                                    for ($i = 1; $i <= $importAmount; $i++) {
                                        $storeName = "$importNewName - " . $hFunction->getTimeCode() . " - $i";
                                        if ($modelCompanyStore->insert($storeName, $importCompanyId, $toolId, null, $importId, $importPrice, $packageId)) {
                                            $newStoreId = $modelCompanyStore->insertGetId();
                                            // cap phat dụng cụ cho nhân viên mua
                                            if (!empty($allocationId)) {  //phieu cap phat dung cu
                                                $modelToolPackageAllocationDetail->insert($allocationId, $newStoreId); // chi tiet cap phat
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            // cap nhat kho
                            if (!empty($importSuppliesId)) { // vat tu
                                $storeName = $modelSupplies->name($importSuppliesId);
                                $modelCompanyStore->insert($storeName, $importCompanyId, null, $importSuppliesId, $importId, $importPrice, null);
                            }
                            if (!empty($importToolId)) { //dung cu
                                // cap phat dụng cụ cho nhân viên mua - dung cu ca nha
                                if ($modelTool->checkPrivateType($importToolId)) {
                                    # thong tin tui do nghe dc giao
                                    $dataToolPackageAllocation = $dataCompanyStaffWork->toolAllocationActivityOfWork();
                                    if ($hFunction->checkCount($dataToolPackageAllocation)) { # da co tui do nghe
                                        $allocationId = $dataToolPackageAllocation->allocationId();
                                        $packageId = $dataToolPackageAllocation->packageId();
                                    } else {
                                        $allocationId = null;
                                        $packageId = null;
                                    }
                                } else {
                                    $allocationId = null;
                                    $packageId = null;
                                }

                                // thêm dụng cụ mới vào kho

                                for ($i = 1; $i <= $importAmount; $i++) {
                                    $storeName = $modelTool->name($importToolId)[0] . " - " . $hFunction->getTimeCode() . " - $i";
                                    if ($modelCompanyStore->insert($storeName, $importCompanyId, $importToolId, null, $importId, $importPrice, $packageId)) {
                                        $newStoreId = $modelCompanyStore->insertGetId();
                                        // cap phat dụng cụ cho nhân viên mua
                                        if (!empty($allocationId)) {  //phieu cap phat dung cu
                                            $modelToolPackageAllocationDetail->insert($allocationId, $newStoreId); // chi tiet cap phat
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

    # ======== ====== Thanh toan ========= ========
    public function getPay($importId)
    {
        $modelImport = new QcImport();
        $dataImport = $modelImport->getInfo($importId);
        return view('work.pay.import.pay', compact('modelStaff', 'dataImport'));
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
