<?php

namespace App\Http\Controllers\Work\Pay\Import;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\CompanyStore\QcCompanyStore;
use App\Models\Ad3d\Import\QcImport;
use App\Models\Ad3d\ImportDetail\QcImportDetail;
use App\Models\Ad3d\ImportImage\QcImportImage;
use App\Models\Ad3d\ImportPay\QcImportPay;
use App\Models\Ad3d\Rule\QcRules;
use App\Models\Ad3d\Salary\QcSalary;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\Supplies\QcSupplies;
use App\Models\Ad3d\Tool\QcTool;
use App\Models\Ad3d\ToolAllocation\QcToolAllocation;
use App\Models\Ad3d\ToolAllocationDetail\QcToolAllocationDetail;
use App\Models\Ad3d\Work\QcWork;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use File;
use Input;
use Request;

class ImportController extends Controller
{
    public function index($monthFilter = 0, $yearFilter = 0, $payStatusFilter = 4, $staffFilterId = null)
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
        $searchCompanyFilterId = [$dataStaffLogin->companyId()];
        if ($staffFilterId > 0) {
            $listStaffId = [$staffFilterId];
        } else {
            $listStaffId = $modelStaff->listIdOfListCompany($searchCompanyFilterId);
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
        $dataImportAll = $modelImport->getInfoHaveFilter($listStaffId, $searchCompanyFilterId, $dateFilter, $payStatusFilter, 'DESC');
        $dataImport = $dataImportAll->paginate(30);
        $importTotalMoney = $modelImport->totalMoneyOfListImport($dataImportAll->get());
        //danh sach NV
        $dataListStaff = $modelCompany->staffInfoActivityOfListCompanyId($searchCompanyFilterId);
        return view('work.pay.import.list', compact('dataAccess', 'modelStaff', 'dataListStaff', 'dataImport', 'importTotalMoney', 'dayFilter', 'monthFilter', 'yearFilter', 'payStatusFilter', 'staffFilterId'));

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
        return view('work.pay.import.confirm', compact('modelStaff', 'dataImport'));
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
        $modelToolAllocation = new QcToolAllocation();
        $modelToolAllocationDetail = new QcToolAllocationDetail();
        $currentDate = $hFunction->carbonNow();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $loginStaffId = $dataStaffLogin->staffId();
        $importCompanyId = $modelImport->companyId($importId)[0];
        $importStaffId = $modelImport->importStaffId($importId)[0];
        # lay ma lam viec tai cty NV nhap hoa don
        $importStaffWorkId = $modelCompanyStaffWork->workIdActivityOfStaff($importStaffId);
        $importStaffWorkId = (is_int($importStaffWorkId)) ? $importStaffWorkId : $importStaffWorkId[0];

        $confirmDetailId = Request::input('txtDetail');
        $confirmNewSuppliesTool = Request::input('cbNewSuppliesTool');
        $unit = Request::input('txtUnit');
        $allocationStatus = Request::input('cbAllocationStatus');
        $confirmPayStatus = Request::input('cbPayStatus');
        $confirmNote = Request::input('txtConfirmNote');

        # thong tin dang lam viec
        $confirmPayStatus = (empty($confirmPayStatus)) ? 0 : $confirmPayStatus;
        if ($confirmPayStatus == 2) { # nhap khong dung
            $exactlyStatus = 0;  # ko chinh xac
            $confirmPayStatus = 0; # khong thanh toan
        } else {
            $exactlyStatus = 1; # nhan chinh xac
        }
        # chi tiet hoa don mua
        $dataImportDetail = $modelImportDetail->infoOfImport($importId);
        # xac nhan hoa don
        if ($modelImport->confirmImport($importId, $confirmPayStatus, $exactlyStatus, $loginStaffId, $confirmNote)) {
            # nhap chinh xac - dươc duỵet
            if ($modelImport->checkExactlyStatus($importId)) {
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
                                    $modelCompanyStore->insert($importAmount, $importCompanyId, null, $suppliesId, null, $importPrice);
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
                                    for ($i = 1; $i <= $importAmount; $i++) {
                                        $storeName = null;
                                        if ($modelCompanyStore->insert($importNewName, $importCompanyId, $toolId, null, $importId, $importPrice)) {
                                            $newStoreId = $modelCompanyStore->insertGetId();
                                            // cap phat dụng cụ cho nhân viên mua
                                            if ($modelTool->checkPrivateType($toolId)) {
                                                // phát luôn cho nhân viên
                                                if (!empty($importStaffWorkId)) {
                                                    # thong bo do nghe
                                                    $dataToolAllocation = $modelToolAllocation->infoActivityOfWork($importStaffWorkId);
                                                    # da co tao bo do nghe
                                                    if ($hFunction->checkCount($dataToolAllocation)) {
                                                        $allocationId = $dataToolAllocation->allocationId();
                                                    } else {
                                                        # tao moi
                                                        if ($modelToolAllocation->insert($currentDate, $loginStaffId, $importStaffWorkId)) {
                                                            $allocationId = $modelToolAllocation->insertGetId();
                                                        } else {
                                                            $allocationId = null;
                                                        }
                                                    }
                                                    if (!empty($allocationId)) {  //phieu cap phat dung cu
                                                        $modelToolAllocationDetail->insert($allocationId, $newStoreId); // chi tiet cap phat
                                                    }
                                                }
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
                                    $modelCompanyStore->insert($importAmount, $importCompanyId, null, $importSuppliesId, null, $importPrice);
                                }
                            }
                            if (!empty($importToolId)) { //dung cu
                                // thêm dụng cụ mới vào kho
                                for ($i = 1; $i <= $importAmount; $i++) {
                                    if ($modelCompanyStore->insert($modelTool->name($importToolId)[0], $importCompanyId, $importToolId, null, $importId, $importPrice)) {
                                        $newStoreId = $modelCompanyStore->insertGetId();
                                        // cap phat dụng cụ cho nhân viên mua
                                        if ($modelTool->checkPrivateType($importToolId)) {
                                            // phát luôn cho nhân viên
                                            if (!empty($importStaffWorkId)) {
                                                # thong bo do nghe
                                                $dataToolAllocation = $modelToolAllocation->infoActivityOfWork($importStaffWorkId);
                                                # da co tao bo do nghe
                                                if ($hFunction->checkCount($dataToolAllocation)) {
                                                    $allocationId = $dataToolAllocation->allocationId();
                                                } else {
                                                    # tao moi
                                                    if ($modelToolAllocation->insert($currentDate, $loginStaffId, $importStaffWorkId)) {
                                                        $allocationId = $modelToolAllocation->insertGetId();
                                                    } else {
                                                        $allocationId = null;
                                                    }
                                                }
                                                if (!empty($allocationId)) {  //phieu cap phat dung cu
                                                    $modelToolAllocationDetail->insert($allocationId, $newStoreId); // chi tiet cap phat
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

    # ======== ====== Thanh toan ========= ========
    public function getPay($importId)
    {
        $modelImport = new QcImport();
        $dataImport = $modelImport->getInfo($importId);
        //return view('ad3d.store.import.import.pay', compact('modelStaff', 'dataImport'));
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
