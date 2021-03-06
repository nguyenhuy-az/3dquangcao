<?php

namespace App\Http\Controllers\Work\Orders;

use App\Models\Ad3d\Customer\QcCustomer;
use App\Models\Ad3d\Department\QcDepartment;
use App\Models\Ad3d\Order\QcOrder;
use App\Models\Ad3d\OrderAllocation\QcOrderAllocation;
use App\Models\Ad3d\OrderImage\QcOrderImage;
use App\Models\Ad3d\OrderPay\QcOrderPay;
use App\Models\Ad3d\Product\QcProduct;
use App\Models\Ad3d\ProductDesign\QcProductDesign;
use App\Models\Ad3d\ProductRepair\QcProductRepair;
use App\Models\Ad3d\ProductType\QcProductType;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\StaffNotify\QcStaffNotify;
use App\Models\Ad3d\TimekeepingProvisionalImage\QcTimekeepingProvisionalImage;
use App\Models\Ad3d\WorkAllocationReportImage\QcWorkAllocationReportImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use File;
use Illuminate\Support\Facades\Session;
use Input;

class OrdersController extends Controller
{
    public function checkLogin()
    {
        $modelStaff = new QcStaff();
        if ($modelStaff->checkLogin()) {
            return true;
        } else {
            return redirect()->route('qc.work.login.get');
        }
    }

    public function index($finishStatus = 100, $monthFilter = 100, $yearFilter = 100, $paymentStatus = 3, $orderFilterName = null, $orderCustomerFilterName = null, $staffFilterId = 999999999)
    {
        # $paymentStatus = tat ca / 0 chua thanh toan xong / 1- da thanh toan xong
        $hFunction = new \Hfunction();
        $modelDepartment = new QcDepartment();
        $modelStaff = new QcStaff();
        $modelOrders = new QcOrder();
        $modelCustomer = new QcCustomer();
        $hFunction->dateDefaultHCM();
        $manageStatus = false;
        $orderFilterName = ($orderFilterName == 'null') ? null : $orderFilterName;
        $orderCustomerFilterName = ($orderCustomerFilterName == 'null') ? null : $orderCustomerFilterName;
        $dataAccess = [
            'object' => 'orders'
        ];
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $loginStaffId = $dataStaffLogin->staffId();
        $loginStaffCompanyId = $dataStaffLogin->companyId();
        # kiem tra la nguoi quan ly
        if ($dataStaffLogin->checkBusinessDepartmentAndManageRank()) $manageStatus = true;
        if ($monthFilter == 100 && $yearFilter == 100) {//xem tất cả đơn hang
            $yearFilter = date('Y');
            $dateFilter = date('Y', strtotime("1-1-$yearFilter"));
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
            $dateFilter = null;
        } else {
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        }
        # nhan vien cap quan ly
        if ($manageStatus) {
            #co tim theo ten don hang hoac khach hang
            if (!$hFunction->checkEmpty($orderFilterName) || !$hFunction->checkEmpty($orderCustomerFilterName)) {
                $staffFilterId = 0; # tat ca NV
                #tim tren tat ca ca don hang
                $staffSelectedId = $modelStaff->listIdOfListCompany([$loginStaffCompanyId]);
            } else {
                # mac dinh
                if ($staffFilterId == 999999999) {
                    $staffSelectedId = [$loginStaffId]; // nhan vien dang nhap
                    $staffFilterId = $loginStaffId;
                    //$staffFilterId = $modelStaff->listIdOfListCompany([$dataStaffLogin->companyId()]);
                } elseif ($staffFilterId == 0) { // chon tat ca nhan vien cua cty
                    $staffSelectedId = $modelStaff->listIdOfListCompany([$loginStaffCompanyId]);
                } else {
                    $staffSelectedId = [$staffFilterId];
                }
            }
            # danh sach nhav kinh doanh
            $dataStaffFilter = $modelStaff->infoOfCompany($loginStaffCompanyId, $modelDepartment->businessDepartmentId());
        } else {
            $staffSelectedId = [$loginStaffId]; // nhan vien dang nhap
            $dataStaffFilter = $modelStaff->getInfoByListStaffId([$loginStaffId]);
        }
        if (!$hFunction->checkEmpty($orderCustomerFilterName)) { # tim theo ten khach hang
            $dataOrderSelect = $modelOrders->selectInfoNoCancelOfListCustomer($modelCustomer->listIdByKeywordName($orderCustomerFilterName), $dateFilter, $paymentStatus, $finishStatus);
        } else {
            $dataOrderSelect = $dataStaffLogin->selectOrderNoCancelAndPayInfoOfListStaffReceive($staffSelectedId, $dateFilter, $paymentStatus, $finishStatus, $orderFilterName);
        }
        $dataOrders = $dataOrderSelect->paginate(50);
        $dataOrdersProvisional = $dataStaffLogin->orderProvisionNoCancelAndPayInfoOfStaffReceive($loginStaffId, $dateFilter, 0, null);
        return view('work.orders.orders.list', compact('modelOrders', 'dataAccess', 'modelStaff', 'dataOrders', 'dataOrdersProvisional', 'dataStaffFilter', 'staffFilterId', 'dateFilter', 'finishStatus', 'monthFilter', 'yearFilter', 'paymentStatus', 'orderFilterName', 'orderCustomerFilterName'));

    }

    # thong tin chi tiet
    public function viewOrders($orderId)
    {
        $hFunction = new \Hfunction();
        $modelOrder = new QcOrder();
        $dataAccess = [
            'object' => 'orders',
            'subObjectLabel' => 'Chi tiết đơn hàng'
        ];

        $dataOrders = $modelOrder->getInfo($orderId);
        if ($hFunction->checkCount($dataOrders)) {
            return view('work.orders.orders.view', compact('dataAccess', 'dataOrders'));
        }
    }

    # xem chi tiet thanh toan
    public function viewOrderPay($orderId)
    {
        $hFunction = new \Hfunction();
        $modelOrder = new QcOrder();
        $dataOrder = $modelOrder->getInfo($orderId);
        if ($hFunction->checkCount($dataOrder)) {
            return view('work.orders.orders.view-order-pay', compact('dataOrder'));
        }
    }

    #in hoa don
    public function printOrders($orderId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelOrder = new QcOrder();
        $dataAccess = [
            'object' => 'orders'
        ];
        $dataOrders = $modelOrder->getInfo($orderId);
        if ($hFunction->checkCount($dataOrders)) {
            return view('work.orders.orders.print', compact('modelStaff', 'dataAccess', 'dataOrders'));
        }
    }

    #in nghiem thu
    public function printOrderConfirm($orderId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelOrder = new QcOrder();
        $dataAccess = [
            'object' => 'orders'
        ];
        $dataOrder = $modelOrder->getInfo($orderId);
        if ($hFunction->checkCount($dataOrder)) {
            return view('work.orders.orders.print-confirm', compact('modelStaff', 'dataAccess', 'dataOrder'));
        }
    }

    # xem thong tin khach hang
    public function viewCustomer($customerId)
    {
        $hFunction = new \Hfunction();
        $modelCustomer = new QcCustomer();
        $dataCustomer = $modelCustomer->getInfo($customerId);
        if ($hFunction->checkCount($dataCustomer)) {
            $dataOrders = $modelCustomer->orderInfoAllOfCustomer($dataCustomer->customerId());
            return view('work.orders.customer.view', compact('dataCustomer', 'dataOrders'));
        }
    }
    // ================ LOC DON HÀNG DON HANG ===================
    // theo ten don hang
    public function filterCheckOrderName($name)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelOrder = new QcOrder();
        $dataOrder = $modelOrder->infoFromSuggestionNameOffReceiveStaff($modelStaff->loginStaffId(), $name);
        if ($hFunction->checkCount($dataOrder)) {
            $result = array(
                'status' => 'exist',
                'content' => $dataOrder
            );
        } else {
            $result = array(
                'status' => 'notExist',
                'content' => "null"
            );
        }
        die(json_encode($result));
    }

    // theo ten khach hang
    public function filterCheckCustomerName($name)
    {
        $hFunction = new \Hfunction();
        $modelCustomer = new QcCustomer();
        $dataCustomer = $modelCustomer->infoFromSuggestionName($name);
        if ($hFunction->checkCount($dataCustomer)) {
            $result = array(
                'status' => 'exist',
                'content' => $dataCustomer
            );
        } else {
            $result = array(
                'status' => 'notExist',
                'content' => "null"
            );
        }
        die(json_encode($result));
    }

    // ================ THEM DON HANG ===================
    #kiem tra khach hang qua sđt
    public function checkCustomerPhone($phone)
    {
        $hFunction = new \Hfunction();
        $modelCustomer = new QcCustomer();
        $dataCustomer = $modelCustomer->infoFromSuggestionPhone($phone);
        if ($hFunction->checkCount($dataCustomer)) {
            $result = array(
                'status' => 'exist',
                'content' => $dataCustomer
            );
        } else {
            $result = array(
                'status' => 'notExist',
                'customerId' => 'null'
            );
        }
        die(json_encode($result));
    }

    public function checkCustomerName($name)
    {
        $hFunction = new \Hfunction();
        $modelCustomer = new QcCustomer();
        $dataCustomer = $modelCustomer->infoFromSuggestionName($name);
        if ($hFunction->checkCount($dataCustomer)) {
            $result = array(
                'status' => 'exist',
                'content' => $dataCustomer
            );
        } else {
            $result = array(
                'status' => 'notExist',
                'content' => "null"
            );
        }
        die(json_encode($result));
    }

    // tim ten don hang
    public function checkOrderName($name)
    {
        $hFunction = new \Hfunction();
        $modelOrder = new QcOrder();
        $dataOrder = $modelOrder->infoFromSuggestionName($name);
        if ($hFunction->checkCount($dataOrder)) {
            $result = array(
                'status' => 'exist',
                'content' => $dataOrder
            );
        } else {
            $result = array(
                'status' => 'notExist',
                'content' => "null"
            );
        }
        die(json_encode($result));
        //return 'yes';
    }

    //loai san pham
    public function checkProductType($name)
    {
        $hFunction = new \Hfunction();
        $modelProductType = new QcProductType();
        $dataProductType = $modelProductType->infoFromSuggestionName($name);
        if ($hFunction->checkCount($dataProductType)) {
            $result = array(
                'status' => 'exist',
                'content' => $dataProductType
            );
        } else {
            $result = array(
                'status' => 'notExist',
                'content' => "null"
            );
        }
        die(json_encode($result));
    }

    //tao don hang
    # them order moi va them san pham
    public function getAdd(Request $request, $orderType = 1, $customerId = null, $orderId = null) // $type: 1 - don hang thuc / 2 - don hang bao gia
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelOrder = new QcOrder();
        $modelCustomer = new QcCustomer();
        //$dataCompanyLogin = $modelStaff->companyLogin();
        $dataAccess = [
            'object' => 'orders',
            'subObjectLabel' => 'Sản phảm'
        ];
        $dataCustomer = ($hFunction->checkEmpty($customerId)) ? $customerId : $modelCustomer->getInfo($customerId);
        $dataOrders = ($hFunction->checkEmpty($orderId)) ? $orderId : $modelOrder->getInfo($orderId);
        return view('work.orders.orders.add', compact('modelStaff', 'dataAccess', 'dataCustomer', 'orderType', 'dataOrders'));
    }

    public function addProduct(Request $request)
    {

        $modelProductType = new QcProductType();
        $dataProductType = $modelProductType->infoActivity();
        if (Session::has('listProductAdd')) {
            $rowsProductions = Session::get('listProductAdd');
            ///$rowsProductions= json_decode($rowsProductions);
        } else {
            $rowsProductions = [];
        }
        return view('work.orders.orders.add-product');
    }

    public function cancelAddProduct(Request $request, $key = null)
    {
        if (Session::has('listProductAdd')) {
            $rowsProductions = Session::get('listProductAdd');
            ///$rowsProductions= json_decode($rowsProductions);
            $numberRow = count($rowsProductions);
            if ($numberRow == 1) {
                $request->session()->forget('listProductAdd');// huy danh sanh
            } else {
                unset($rowsProductions[$key]); // xoa 1 dòng
                ///$request->session()->put('listProductAdd',json_encode($rowsProductions));
                $request->session()->put('listProductAdd', $rowsProductions);
            }
        }
    }

    # THEM DON HANG THUC
    public function postAdd(Request $request)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $hFunction = new \Hfunction();
        $modelProductType = new QcProductType();
        $modelCustomer = new QcCustomer();
        $modelOrder = new QcOrder();
        $modelProduct = new QcProduct();
        $modelStaffNotify = new QcStaffNotify();
        $modelOrderPay = new QcOrderPay();
        $modelOrderAllocation = new QcOrderAllocation();
        $modelStaff = new QcStaff();
        $dataStaff = $modelStaff->loginStaffInfo();
        $staffLoginId = $modelStaff->loginStaffId();
        //thong tin khach hang
        $txtCustomerName = $request->input('txtCustomerName');
        $txtPhone = $request->input('txtPhone');
        $txtZalo = $request->input('txtZalo');
        $txtAddress = $request->input('txtAddress');

        //thong tin san pham
        $productType = $request->input('txtProductType');
        $txtWidth = $request->input('txtWidth');
        $txtHeight = $request->input('txtHeight');
        //$txtDepth = Request::input('txtDepth');
        $txtUnit = $request->input('txtUnit');
        $txtWarrantyTime = $request->input('txtWarrantyTime');
        $txtAmount = $request->input('txtAmount');
        $txtPrice = $request->input('txtPrice');
        $txtDescription = $request->input('txtDescription');
        $txtWidth = ($hFunction->checkEmpty($txtWidth)) ? 0 : $txtWidth;
        $txtHeight = ($hFunction->checkEmpty($txtHeight)) ? 0 : $txtHeight;


        //thong tin don hang
        $txtOrderName = $request->input('txtOrderName');
        $txtConstructionAddress = $request->input('txtConstructionAddress');
        $txtConstructionPhone = $request->input('txtConstructionPhone');
        $txtConstructionContact = $request->input('txtConstructionContact');
        $txtBeforePay = $request->input('txtBeforePay');
        $txtBeforePay = $hFunction->convertCurrencyToInt($txtBeforePay);
        $txtDateReceive = $hFunction->carbonNow();//Request::input('txtDateReceive');
        $cbDiscount = $request->input('cbDiscount');
        $cbVat = $request->input('cbVat');
        # ngay  giao
        $cbHoursDelivery = $request->input('cbHoursDelivery');
        $cbMinuteDelivery = $request->input('cbMinuteDelivery');
        $cbDayDelivery = $request->input('cbDayDelivery');
        $cbMonthDelivery = $request->input('cbMonthDelivery');
        $cbYearDelivery = $request->input('cbYearDelivery');
        $txtDateDelivery = $hFunction->convertStringToDatetime("$cbMonthDelivery/$cbDayDelivery/$cbYearDelivery $cbHoursDelivery:$cbMinuteDelivery:00");
        # trang thai thi cong
        $cbConstructionStatus = $request->input('cbConstructionStatus');
        $oldCustomerId = $hFunction->getDefaultNull();
        $dataCustomer = $hFunction->getDefaultNull();
        if ($hFunction->formatDateToYMDHI($txtDateReceive) > $hFunction->formatDateToYMDHI($txtDateDelivery)) {
            return "Ngày giao phải lớn hơn ngày nhận <br/> <a style='color: red;' onclick='history.back();'>Quay lại</a>";
        } else {
            if (!$hFunction->checkEmpty($txtPhone)) {
                #lay thong tin khach hang tu so dien thoai di dong
                $dataCustomer = $modelCustomer->infoFromPhone($txtPhone);
            }
            if (!$hFunction->checkEmpty($txtZalo) && count($dataCustomer) <= 0) {
                # lay thong tin khach hang tu so zalo
                $dataCustomer = $modelCustomer->infoFromZalo($txtPhone);
            }
            # lay gia tri mac dinh khach hang
            $customerAddress = $modelCustomer->getDefaultAddress();
            $customerEmail = $modelCustomer->getDefaultEmail();

            if ($hFunction->checkCount($dataCustomer)) {
                # ton tai khach hang - khach hang cu
                $customerId = $dataCustomer->customerId();
                $customerName = $dataCustomer->name();
            } else {
                # khach hang moi
                if ($modelCustomer->insert($txtCustomerName, $customerEmail, $customerAddress, $txtAddress, $txtPhone, $txtZalo)) {
                    $customerId = $modelCustomer->insertGetId();
                    $customerName = $txtCustomerName;
                }
            }
            if (!$hFunction->checkEmpty($customerId) && !$hFunction->checkEmpty($staffLoginId)) {
                #cap nhat thong tin khach hang neu co thay doi dia chi
                $modelCustomer->updateInfo($customerId, $customerName, $customerEmail, $customerAddress, $txtAddress, $txtPhone, $txtZalo);
                # them don hang
                $txtConstructionAddress = ($hFunction->checkEmpty($txtConstructionAddress)) ? $txtAddress : $txtConstructionAddress;
                $txtConstructionPhone = ($hFunction->checkEmpty($txtConstructionPhone)) ? $txtPhone : $txtConstructionPhone;
                $txtConstructionContact = ($hFunction->checkEmpty($txtConstructionContact)) ? $txtCustomerName : $txtConstructionContact;
                # lay gia tri mac dinh
                # dơn hang thuc
                $provisionalStatus = $modelOrder->getDefaultNotProvisionalStatus();
                $staffKpiId = $modelOrder->getDefaultStaffKPIId();
                $confirmStatus = $modelOrder->getDefaultHasConfirm();
                $provisionalDate = $modelOrder->getDefaultProvisionalDate();
                $provisionalConfirm = $modelOrder->getDefaultHasProvisionalConfirm();
                if ($modelOrder->insert($txtOrderName, $cbDiscount, $cbVat, $txtDateReceive, $txtDateDelivery, $customerId, $staffLoginId, $staffKpiId, $confirmStatus, $txtConstructionAddress, $txtConstructionPhone, $txtConstructionContact, $provisionalStatus, $provisionalDate, $provisionalConfirm)) {
                    $orderId = $modelOrder->insertGetId();
                    # xet them vao ngan sach thuong
                    $modelOrder->checkAddBonusBudget($orderId);
                    # thong bao them don hang
                    $listStaffReceiveNotify = $modelStaff->infoStaffReceiveNotifyNewOrder($dataStaff->companyId());
                    if ($hFunction->checkCount($listStaffReceiveNotify)) {
                        # lay gia tri mac dinh
                        $orderAllocationId = $modelStaffNotify->getDefaultOrderAllocationId();
                        $workAllocationId = $modelStaffNotify->getDefaultWorkAllocationId();
                        $bonusId = $modelStaffNotify->getDefaultBonusId();
                        $minusMoneyId = $modelStaffNotify->getDefaultMinusId();
                        $orderAllocationFinishId = $modelStaffNotify->getDefaultOrderAllocationFinishId();
                        $notifyNote = 'Quản lý thi công trễ đơn hàng';
                        foreach ($listStaffReceiveNotify as $staff) {
                            $modelStaffNotify->insert($orderId, $staff->staffId(), $notifyNote, $orderAllocationId, $workAllocationId, $bonusId, $minusMoneyId, $orderAllocationFinishId);
                        }
                    }
                    # them san pham
                    if ($hFunction->checkCount($productType)) {
                        foreach ($productType as $key => $value) {
                            $warrantyTime = (int)$txtWarrantyTime[$key];
                            $dataProductType = $modelProductType->infoFromExactlyName($value);
                            # loai san pham da ton tai
                            if ($hFunction->checkCount($dataProductType)) {
                                $productTypeId = $dataProductType->typeId();
                            } else {
                                # chu ton tai - them moi
                                $unit = $txtUnit[$key];
                                # lay gia tri mac dinh
                                $productTypeDescription = $modelProductType->getDefaultDescription();
                                $productTypeConfirm = $modelProductType->getDefaultNotConfirm();
                                $productTypeApply = $modelProductType->getDefaultNotApply();
                                if ($modelProductType->insert($value, $productTypeDescription, $unit, $productTypeConfirm, $productTypeApply, $warrantyTime)) {
                                    $productTypeId = $modelProductType->insertGetId();
                                } else {
                                    $productTypeId = $hFunction->getDefaultNull();
                                }
                            }
                            if (!$hFunction->checkEmpty($productTypeId)) {
                                $width = $txtWidth[$key];
                                $height = $txtHeight[$key];
                                $amount = $txtAmount[$key];
                                $price = $hFunction->convertCurrencyToInt($txtPrice[$key]);
                                $description = $txtDescription[$key];
                                $constructionStatus = $cbConstructionStatus[$key];
                                # lay gia tri mac dinh
                                $depth = $modelProduct->getDefaultDepth();
                                $designImage = $modelProduct->getDefaultDesignImage();
                                if ($modelProduct->insert($width, $height, $depth, $price, $amount, $description, $productTypeId, $orderId, $designImage, $warrantyTime)) {
                                    $newProductId = $modelProduct->insertGetId();
                                    # co thi cong
                                    if ($constructionStatus == $modelProduct->getDefaultHasConstruction()) {
                                        # phan cong tu dong
                                        $modelProduct->autoAllocation($newProductId);
                                    }
                                }
                            }
                        }
                    }
                    # thanh toan
                    if ($txtBeforePay > 0) {
                        # thanh toan don hang
                        $modelOrderPay->insert($txtBeforePay, 'Thu nhận đơn hàng', $txtDateReceive, $orderId, $staffLoginId, $txtCustomerName, $txtPhone);
                    }
                    # bàn giao don hang = cong trinh
                    if ($modelStaff->checkConstructionDepartment($staffLoginId)) {
                        # neu thuoc bo thi cong -> ban giao quan ly thi cong
                        # lay gia tri mac dinh
                        $allocationStaffId = $modelOrderAllocation->getDefaultAllocationStaffId();
                        $allocationReceiveStatus = $modelOrderAllocation->getDefaultNotReceive();
                        $allocationNote = 'Bàn giao khi nhận đơn hàng';
                        $modelOrderAllocation->insert($txtDateReceive, $allocationReceiveStatus, $txtDateDelivery, $allocationNote, $orderId, $staffLoginId, $allocationStaffId);
                    }
                    return redirect()->route('qc.work.orders.print.get', $orderId);
                } else {
                    Session::put('notifyAdd', 'Thêm thất bại, hãy thử lại');
                    return redirect()->back();
                }
            } else {
                Session::put('notifyAdd', 'Thêm thất bại, hãy thử lại');
                return redirect()->back();
            }
        }
    }

    // them don hang tam
    public function postAddProvisional(Request $request)
    {
        $hFunction = new \Hfunction();
        $modelProductType = new QcProductType();
        $modelCustomer = new QcCustomer();
        $modelOrder = new QcOrder();
        $modelStaff = new QcStaff();
        $modelProduct = new QcProduct();
        $dataStaff = $modelStaff->loginStaffInfo();
        //thong tin khach hang
        $txtCustomerName = $request->input('txtCustomerName');
        $txtPhone = $request->input('txtPhone');
        $txtZalo = $request->input('txtZalo');
        $txtAddress = $request->input('txtAddress');

        //thong tin san pham
        $productType = $request->input('txtProductType');
        $txtWidth = $request->input('txtWidth');
        $txtHeight = $request->input('txtHeight');
        //$txtDepth = $request->input('txtDepth');
        $txtUnit = $request->input('txtUnit');
        $txtAmount = $request->input('txtAmount');
        $txtPrice = $request->input('txtPrice');
        $txtWarrantyTime = $request->input('txtWarrantyTime');
        $txtDescription = $request->input('txtDescription');
        $txtWidth = ($hFunction->checkEmpty($txtWidth)) ? 0 : $txtWidth;
        $txtHeight = ($hFunction->checkEmpty($txtHeight)) ? 0 : $txtHeight;
        //$txtDepth = ($hFunction->checkEmpty($txtDepth)) ? 0 : $txtDepth;

        //thong tin don hang
        $txtOrderName = $request->input('txtOrderName');
        $txtConstructionAddress = $request->input('txtConstructionAddress');
        $txtConstructionPhone = $request->input('txtConstructionPhone');
        $txtConstructionContact = $request->input('txtConstructionContact');
        $txtDateReceive = $hFunction->getDefaultNull();
        $txtDateDelivery = $hFunction->getDefaultNull();
        $cbDiscount = $request->input('cbDiscount');
        $cbVat = $request->input('cbVat');
        $oldCustomerId = $hFunction->getDefaultNull();
        $dataCustomer = $hFunction->getDefaultNull();
        $staffLoginId = $dataStaff->staffId();
        if (!$hFunction->checkEmpty($txtPhone)) {
            #lay thong tin khach hang tu so dien thoai di dong
            $dataCustomer = $modelCustomer->infoFromPhone($txtPhone);
        }
        if (!$hFunction->checkEmpty($txtZalo) && count($dataCustomer) <= 0) {
            # lay thong tin khach hang tu so zalo
            $dataCustomer = $modelCustomer->infoFromZalo($txtPhone);
        }

        if ($hFunction->checkCount($dataCustomer)) {
            # ton tai khach hang - khach hang cu
            $customerId = $dataCustomer->customerId();
            $customerName = $dataCustomer->name();
        } else {
            # khach hang moi
            # gia tri mac dinh
            $customerBirthday = $modelCustomer->getDefaultBirthday();
            $customerEmail = $modelCustomer->getDefaultEmail();
            if ($modelCustomer->insert($txtCustomerName, $customerBirthday, $customerEmail, $txtAddress, $txtPhone, $txtZalo)) {
                $customerId = $modelCustomer->insertGetId();
                $customerName = $txtCustomerName;
            }
        }
        if (!$hFunction->checkEmpty($customerId) && !$hFunction->checkEmpty($staffLoginId)) {
            #cap nhat thong tin khach hang neu co thay doi dia chi
            # gia tri mac dinh
            $customerBirthday = $modelCustomer->getDefaultBirthday();
            $customerEmail = $modelCustomer->getDefaultEmail();
            $modelCustomer->updateInfo($customerId, $customerName, $customerBirthday, $customerEmail, $txtAddress, $txtPhone, $txtZalo);
            # them don hang
            $txtConstructionAddress = ($hFunction->checkEmpty($txtConstructionAddress)) ? $txtAddress : $txtConstructionAddress;
            $txtConstructionPhone = ($hFunction->checkEmpty($txtConstructionPhone)) ? $txtPhone : $txtConstructionPhone;
            $txtConstructionContact = ($hFunction->checkEmpty($txtConstructionContact)) ? $txtCustomerName : $txtConstructionContact;
            # lay gia tri mac dinh
            $staffKpiId = $modelOrder->getDefaultStaffKPIId();
            $notConfirm = $modelOrder->getDefaultNotConfirm();
            $notProvisionalStatus = $modelOrder->getDefaultNotProvisionalStatus();
            $notProvisionalConfirm = $modelOrder->getDefaultNotProvisionalConfirm();
            if ($modelOrder->insert($txtOrderName, $cbDiscount, $cbVat, $txtDateReceive, $txtDateDelivery, $customerId, $staffLoginId, $staffKpiId, $notConfirm, $txtConstructionAddress, $txtConstructionPhone, $txtConstructionContact, $notProvisionalStatus, $hFunction->carbonNow(), $notProvisionalConfirm)) {
                $orderId = $modelOrder->insertGetId();
                if ($hFunction->checkCount($productType)) {
                    # them san pham
                    foreach ($productType as $key => $value) {
                        $warrantyTime = $txtWarrantyTime[$key];
                        $dataProductType = $modelProductType->infoFromExactlyName($value);
                        if ($hFunction->checkCount($dataProductType)) {
                            $productTypeId = $dataProductType->typeId();
                        } else {
                            $unit = $txtUnit[$key];
                            # lay gia tri mac dinh
                            $productTypeDescription = $modelProductType->getDefaultDescription();
                            $productTypeConfirm = $modelProductType->getDefaultNotConfirm();
                            $productTypeApply = $modelProductType->getDefaultNotApply();
                            if ($modelProductType->insert($value, $productTypeDescription, $unit, $productTypeConfirm, $productTypeApply, $warrantyTime)) {
                                $productTypeId = $modelProductType->insertGetId();
                            } else {
                                $productTypeId = $hFunction->getDefaultNull();
                            }
                        }
                        if (!$hFunction->checkEmpty($productTypeId)) {
                            $width = $txtWidth[$key];
                            $height = $txtHeight[$key];
                            $amount = $txtAmount[$key];
                            $price = $hFunction->convertCurrencyToInt($txtPrice[$key]);
                            $description = $txtDescription[$key];
                            # lay gia tri mac dinh
                            $depth = $modelProduct->getDefaultDepth();
                            $designImage = $modelProduct->getDefaultDesignImage();
                            $modelProduct->insert($width, $height, $depth, $price, $amount, $description, $productTypeId, $orderId, $designImage, $warrantyTime);
                        }
                    }
                }
                return redirect()->route('qc.work.orders.print.get', $orderId);
            } else {
                Session::put('notifyAdd', 'Thêm thất bại, hãy thử lại 2');
                return redirect()->back();
            }
        } else {
            Session::put('notifyAdd', 'Thêm thất bại, hãy thử lại 1');
            return redirect()->back();
        }

    }

    // ================ CAP NHAT DON HANG ===================
    //edit
    public function getEdit()
    {
        return view('ad3d.order.order.orders.edit');
    }

    public function getEditAddProduct($orderId)
    {
        $modelStaff = new QcStaff();
        $modelOrder = new QcOrder();
        $dataAccess = [
            'object' => 'orders',
            'subObjectLabel' => 'Sản phảm'
        ];
        $dataOrders = $modelOrder->getInfo($orderId);
        return view('work.orders.orders.edit-add-product', compact('modelStaff', 'dataAccess', 'dataOrders'));
    }

    public function editAddProduct()
    {
        return view('work.orders.orders.add-product');
    }

    public function postEditAddProduct(Request $request, $orderId)
    {
        $hFunction = new \Hfunction();
        $modelOrders = new QcOrder();
        $modelProductType = new QcProductType();
        //thong tin san pham
        $productType = $request->input('txtProductType');
        $txtWidth = $request->input('txtWidth');
        $txtHeight = $request->input('txtHeight');
        $txtDepth = $request->input('txtDepth');
        $txtAmount = $request->input('txtAmount');
        $txtPrice = $request->input('txtPrice');
        $txtWarrantyTime = $request->input('txtWarrantyTime');
        $txtDescription = $request->input('txtDescription');
        $txtWidth = ($hFunction->checkEmpty($txtWidth)) ? 0 : $txtWidth;
        $txtHeight = ($hFunction->checkEmpty($txtHeight)) ? 0 : $txtHeight;
        $txtDepth = ($hFunction->checkEmpty($txtDepth)) ? 0 : $txtDepth;
        # trang thai thi cong
        $cbConstructionStatus = $request->input('cbConstructionStatus');
        if ($hFunction->checkCount($productType)) {
            # them san pham
            foreach ($productType as $key => $value) {
                $warrantyTime = $txtWarrantyTime[$key];
                $dataProductType = $modelProductType->infoFromExactlyName($value);
                if ($hFunction->checkCount($dataProductType)) {
                    $productTypeId = $dataProductType->typeId();
                } else {
                    # lay gia tri mac dinh
                    $productTypeDescription = $modelProductType->getDefaultDescription();
                    $productTypeUnit = $modelProductType->getDefaultUnit();
                    $productTypeConfirm = $modelProductType->getDefaultNotConfirm();
                    $productTypeApply = $modelProductType->getDefaultNotApply();
                    if ($modelProductType->insert($value, $productTypeDescription, $productTypeUnit, $productTypeConfirm, $productTypeApply, $warrantyTime)) {
                        $productTypeId = $modelProductType->insertGetId();
                    } else {
                        $productTypeId = $hFunction->getDefaultNull();
                    }
                }
                if (!$hFunction->checkEmpty($productTypeId)) {
                    $width = $txtWidth[$key];
                    $height = $txtHeight[$key];
                    $depth = $txtDepth[$key];
                    $amount = $txtAmount[$key];
                    $constructionStatus = $cbConstructionStatus[$key];
                    $price = $hFunction->convertCurrencyToInt($txtPrice[$key]);
                    $description = $txtDescription[$key];
                    $modelProduct = new QcProduct();
                    if ($modelProduct->insert($width, $height, $depth, $price, $amount, $description, $productTypeId, $orderId)) {
                        $newProductId = $modelProduct->insertGetId();
                        # co thi cong
                        if ($constructionStatus == $modelProduct->getDefaultHasConstruction()) {
                            # phan cong tu dong
                            $modelProduct->autoAllocation($newProductId);
                        }
                    }
                }
            }
        }
        return redirect()->route('qc.work.orders.info.get', $orderId);
    }

    // ======== ======= KINH DOANH BAO CAO HOAN THANH ======= =======
    public function getReportFinish($orderId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelOrder = new QcOrder();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        if ($hFunction->checkCount($dataStaffLogin)) {
            $dataOrder = $modelOrder->getInfo($orderId);
            if ($hFunction->checkCount($dataOrder)) {
                return view('work.orders.orders.report-finish', compact('dataStaffLogin', 'dataOrder'));
            }
        }
    }

    public function postReportFinish($orderId)
    {
        $modelStaff = new QcStaff();
        $modelOrder = new QcOrder();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $staffLoginId = $dataStaffLogin->staffId();
        $modelOrder->confirmReportFinish($orderId, $modelOrder->getDefaultHasFinishStatus(), $staffLoginId);
    }

    // ======= ===== ==== THANH TOAN DON HANG ====== ======== =====
    // thanh toan don hang
    public function getPayment($orderId)
    {
        $hFunction = new \Hfunction();
        $modelOrders = new QcOrder();
        $dataAccess = [
            'object' => 'orders',
            'subObjectLabel' => 'Thanh toán'
        ];
        $dataOrder = $modelOrders->getInfo($orderId);
        if ($hFunction->checkCount($dataOrder)) {
            return view('work.orders.orders.payment', compact('dataAccess', 'dataOrder'));
        }

    }

    public function postPayment(Request $request, $orderId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelOrders = new QcOrder();
        $modelOrderPay = new QcOrderPay();
        $txtMoney = $request->input('txtMoney');
        $txtName = $request->input('txtName');
        $txtPhone = $request->input('txtPhone');
        $txtNote = $request->input('txtNote');
        $txtMoney = $hFunction->convertCurrencyToInt($txtMoney);
        $dataOrder = $modelOrders->getInfo($orderId);
        if ($hFunction->checkCount($txtMoney) && $hFunction->checkCount($dataOrder)) {
            $modelOrderPay->insert($txtMoney, $txtNote, $hFunction->carbonNow(), $orderId, $modelStaff->loginStaffId(), $txtName, $txtPhone);
        } else {
            return "Phải nhập số tiền thanh toán";
        }
    }

    // ================ HUY DON HANG ===================
    public function getOrderCancel($orderId = null)
    {
        $hFunction = new \Hfunction();
        $modelOrders = new QcOrder();
        $dataOrder = $modelOrders->getInfo($orderId);
        if ($hFunction->checkCount($dataOrder)) {
            return view('work.orders.orders.cancel', compact('dataOrder'));
        }
    }

    public function postOrderCancel(Request $request, $orderId = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelOrder = new QcOrder();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $txtPayment = $request->input('txtPayment');
        $txtReason = $request->input('txtReason');
        $txtPayment = $hFunction->convertCurrencyToInt($txtPayment);
        $dataOrder = $modelOrder->getInfo($orderId);
        if ($hFunction->checkCount($dataOrder)) {
            $modelOrder->cancelOrder($orderId, $txtPayment, $txtReason, $dataStaffLogin->staffId());
        }

    }

    //======= ======== ===== QUAN LY THONG TIN DƠN HANG ==== ======== ======
    # ban giao don hang - cong trinh
    public function getOrderConstruction($orderId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelOrders = new QcOrder();
        $dataAccess = [
            'object' => 'orders',
            'subObjectLabel' => 'Chi tiết thi công'
        ];
        $dataOrder = $modelOrders->getInfo($orderId);
        if ($hFunction->checkCount($dataOrder)) {
            # cap nhat da xem thong bao
            return view('work.orders.orders.detail-construction', compact('modelStaff', 'dataAccess', 'dataOrder'));
        } else {
            return redirect()->back();
        }
    }

    # bao sua chua san pham
    public function getRepairProduct($productId)
    {
        $modelProduct = new QcProduct();
        $dataProduct = $modelProduct->getInfo($productId);
        return view('work.orders.orders.add-repair-product', compact('dataProduct'));
    }

    public function postRepairProduct(Request $request, $productId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelProductRepair = new QcProductRepair();
        $txtImage = $request->file('txtImage');
        $txtNote = $request->input('txtNote');
        $loginStaffId = $modelStaff->loginStaffId();
        $name_img = $hFunction->getDefaultNull();
        if (!$hFunction->checkEmpty($txtImage)) {
            $name_img = stripslashes($_FILES['txtImage']['name']);
            $name_img = $hFunction->getTimeCode() . '.' . $hFunction->getTypeImg($name_img);
            $source_img = $_FILES['txtImage']['tmp_name'];
            if (!$modelProductRepair->uploadImage($source_img, $name_img)) {
                $name_img = $hFunction->getDefaultNull();
            }
        }
        if (!$modelProductRepair->insert($name_img, $txtNote, $productId, $loginStaffId)) {
            $modelProductRepair->dropImage($name_img);
            return "Tính năng đang cập nhật";
        }
    }

    //======= ======== ===== QUAN LY THONG TIN DƠN HANG ==== ======== ======
    public function ordersInfo($orderId, $notifyId = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelStaffNotify = new QcStaffNotify();
        $modelOrders = new QcOrder();
        $dataAccess = [
            'object' => 'orders',
            'subObjectLabel' => 'Quản lý đơn hàng'
        ];
        # cap nhat da xem thong tin thong bao
        if (!$hFunction->checkEmpty($notifyId)) $modelStaffNotify->updateViewed($notifyId);

        $dataOrder = $modelOrders->getInfo($orderId);
        if ($hFunction->checkCount($dataOrder)) {
            $pageBack = 1;
            return view('work.orders.orders.order-info', compact('modelStaff', 'dataAccess', 'dataOrder', 'pageBack'));
        }
    }

    // thay doi thong tin khach hang
    public function postEditInfoCustomer(Request $request, $customerId)
    {
        $modelCustomer = new QcCustomer();
        $txtCustomerName = $request->input('txtCustomerName');
        $txtCustomerPhone = $request->input('txtCustomerPhone');
        $txtCustomerZalo = $request->input('txtCustomerZalo');
        $txtCustomerAddress = $request->input('txtCustomerAddress');
        # gia tri mac dinh
        $customerBirthday = $modelCustomer->getDefaultBirthday();
        $customerEmail = $modelCustomer->getDefaultEmail();
        if (!$modelCustomer->updateInfo($customerId, $txtCustomerName, $customerBirthday, $customerEmail, $txtCustomerAddress, $txtCustomerPhone, $txtCustomerZalo)) {
            return "Tính năng đang bảo trì";
        }

    }

    // thay doi thong tin don hang
    public function postEditInfoOrder(Request $request, $orderId)
    {
        $hFunction = new \Hfunction();
        $modelOrder = new QcOrder();
        $txtOrderName = $request->input('txtOrderName');
        $cbDiscount = $request->input('cbDiscount');
        $cbVat = $request->input('cbVat');
        $txtConstructionAddress = $request->input('txtConstructionAddress');
        $txtConstructionPhone = $request->input('txtConstructionPhone');
        $txtConstructionContact = $request->input('txtConstructionContact');
        if ($modelOrder->checkProvisionUnConfirmed($orderId)) { // don hang dang bao gia
            $txtDateReceive = $hFunction->getDefaultNull();
            $txtDateDelivery = $hFunction->getDefaultNull();
        } else {
            $txtDateReceive = $request->input('txtDateReceive');
            $txtDateDelivery = $request->input('txtDateDelivery');
        }
        $staffReceiveId = $modelOrder->staffReceiveId($orderId);
        if (!$modelOrder->updateInfo($orderId, $txtOrderName, $cbDiscount, $cbVat, $txtDateReceive, $txtDateDelivery, $staffReceiveId, $txtConstructionAddress, $txtConstructionPhone, $txtConstructionContact)) {
            return "Tính năng đang bảo trì";
        }
    }

    //sua thong tin thanh toan
    public function getEditInfoPay($payId)
    {
        $hFunction = new \Hfunction();
        $modelOrderPay = new QcOrderPay();
        $dataOrderPay = $modelOrderPay->getInfo($payId);
        if ($hFunction->checkCount($dataOrderPay)) {
            return view('work.orders.orders.edit-order-info-pay', compact('dataAccess', 'dataOrderPay'));
        }
    }

    public function postEditInfoPay(Request $request, $payId)
    {
        $hFunction = new \Hfunction();
        $modelOrderPay = new QcOrderPay();
        $txtPayName = $request->input('txtPayName');
        $txtPayPhone = $request->input('txtPayPhone');
        $txtPayMoney = $request->input('txtPayMoney');
        $txtPayMoney = $hFunction->convertCurrencyToInt($txtPayMoney);
        if (!$modelOrderPay->updateInfo($payId, $txtPayMoney, $txtPayName, $txtPayPhone)) {
            return "Tính năng đang bảo trì";
        }
    }
    // them thiet ke tong the
    #-------- THEM ANH THIET KE -------
    # thiet ke tong the
    public function getAddDesign($orderId = null)
    {
        $hFunction = new \Hfunction();
        $modelOrder = new QcOrder();
        $dataOrder = $modelOrder->getInfo($orderId);
        if ($hFunction->checkCount($dataOrder)) {
            return view('work.orders.orders.add-design', compact('dataOrder'));
        }
    }

    public function postAddDesign(Request $request, $orderId = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelOrder = new QcOrder();
        $modelOrderImage = new QcOrderImage();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $txtDesignImage = $request->file('txtDesignImage');
        $loginStaffId = $dataStaffLogin->staffId();
        $dataOrder = $modelOrder->getInfo($orderId);
        if ($hFunction->getCount($txtDesignImage) > 0 & $hFunction->checkCount($dataOrder)) {
            $name_img = stripslashes($_FILES['txtDesignImage']['name']);
            $name_img = $hFunction->getTimeCode() . '.' . $hFunction->getTypeImg($name_img);
            $source_img = $_FILES['txtDesignImage']['tmp_name'];
            if ($modelOrderImage->uploadImage($source_img, $name_img)) {
                if (!$modelOrderImage->insert($name_img, $orderId, $loginStaffId)) {
                    $modelOrderImage->dropImage($name_img);
                    return "Tính năng đang cập nhật";
                }
            }
        } else {
            return "Chọn ảnh thiết kế";
        }
    }

    # huy thiet ke
    public function deleteDesign($imageId)
    {
        $modelOrderImage = new QcOrderImage();
        $modelOrderImage->actionDelete($imageId);
    }

    //======== ======= =====  QUAN LY SAN PHAM CUA DON HANG ======= ========= ========
    public function productList($orderId = null)
    {
        $hFunction = new \Hfunction();
        $modelOrders = new QcOrder();
        $dataAccess = [
            'object' => 'orders',
            'subObjectLabel' => 'Sản phẩm'
        ];
        $dataOrders = $modelOrders->getInfo($orderId);
        if ($hFunction->checkCount($dataOrders)) {
            return view('work.orders.product.product', compact('dataAccess', 'dataOrders'));
        }

    }

    # cap nhat hong tin SP
    public function getProductInfoEdit($productId)
    {
        $hFunction = new \Hfunction();
        $modelProduct = new QcProduct();
        $dataProduct = $modelProduct->getInfo($productId);
        if ($hFunction->checkCount($dataProduct)) {
            return view('work.orders.orders.product.edit-info', compact('dataProduct'));
        }
    }

    public function postProductInfoEdit(Request $request, $productId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelProduct = new QcProduct();
        $txtWidth = $request->input('txtWidth');
        $txtHeight = $request->input('txtHeight');
        $txtAmount = $request->input('txtAmount');
        $txtPrice = $request->input('txtPrice');
        $txtDescription = $request->input('txtDescription');
        $txtPrice = $hFunction->convertCurrencyToInt($txtPrice);
        $dataProduct = $modelProduct->getInfo($productId);
        if ($hFunction->checkCount($dataProduct)) {
            $modelProduct->updateInfoNotType($productId, $txtWidth, $txtHeight, 0, $txtPrice, $txtAmount, $txtDescription);
        } else {
            return "Tính năng đang cập nhật";
        }

    }

    # xac nhan don hang
    public function getProductConfirm($productId = null)
    {
        $hFunction = new \Hfunction();
        $modelProduct = new QcProduct();
        $dataProduct = $modelProduct->getInfo($productId);
        if ($hFunction->checkCount($dataProduct)) {
            return view('work.orders.orders.product.confirm-finish', compact('dataProduct'));
        }
    }

    public function postProductConfirm($productId = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelProduct = new QcProduct();
        $modelProduct->confirmFinish($modelStaff->loginStaffId(), $hFunction->carbonNow(), $productId);
    }

    #----- ------ huy san pham ----- ----
    public function getProductCancel($productId = null)
    {
        $hFunction = new \Hfunction();
        $modelProduct = new QcProduct();
        $dataProduct = $modelProduct->getInfo($productId);
        if ($hFunction->checkCount($dataProduct)) {
            return view('work.orders.orders.product.cancel', compact('dataProduct'));
        }
    }

    public function postProductCancel(Request $request, $productId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelProduct = new QcProduct();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $txtReason = $request->input('txtReason');
        $dataOrder = $modelProduct->getInfo($productId);
        if ($hFunction->checkCount($dataOrder)) {
            $modelProduct->cancelProduct($productId, $txtReason, $dataStaffLogin->staffId());
        }
    }

    #---- ---  THIET KE SAN PHAM ---- -----
    #chi tiet thiet ke san pham
    public function getDesign($productId)
    {
        $modelStaff = new QcStaff();
        $modelProduct = new QcProduct();
        $dataAccess = [
            'object' => 'orders'
        ];
        $dataProduct = $modelProduct->getInfo($productId);
        $dataProductDesign = $dataProduct->productDesignInfoAll();
        return view('work.orders.orders.product.design', compact('modelStaff', 'dataAccess', 'dataProduct', 'dataProductDesign'));
    }

    #chi tiet thiet ke san pham
    public function getDesignConstruction($productId)
    {
        $modelStaff = new QcStaff();
        $modelProduct = new QcProduct();
        $dataAccess = [
            'object' => 'orders'
        ];
        $dataProduct = $modelProduct->getInfo($productId);
        $dataProductDesign = $dataProduct->productDesignInfoConstruction();
        return view('work.orders.orders.product.design-construction', compact('modelStaff', 'dataAccess', 'dataProduct', 'dataProductDesign'));
    }

    #them anh thiet ke san pham
    public function getAddProductDesign($productId = null)
    {
        $hFunction = new \Hfunction();
        $modelProduct = new QcProduct();
        $modelProductDesign = new QcProductDesign();
        $dataProduct = $modelProduct->getInfo($productId);
        if ($hFunction->checkCount($dataProduct)) {
            $designType = $modelProductDesign->getDefaultDesignTypeProduct();
            return view('work.orders.orders.product.add-design', compact('modelProductDesign', 'dataProduct', 'designType'));
        }
    }

    # them anh thiet ke thi cong
    public function  getAddProductDesignConstruction($productId)
    {
        $hFunction = new \Hfunction();
        $modelProduct = new QcProduct();
        $modelProductDesign = new QcProductDesign();
        $dataProduct = $modelProduct->getInfo($productId);
        if ($hFunction->checkCount($dataProduct)) {
            $designType = $modelProductDesign->getDefaultDesignTypeConstruction();
            return view('work.orders.orders.product.add-design', compact('modelProductDesign', 'dataProduct', 'designType'));
        }
    }

    public function postAddProductDesign(Request $request, $productId = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelProduct = new QcProduct();
        $modelProductDesign = new QcProductDesign();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $txtDesignImage = $request->file('txtDesignImage');
        $txtDesignType = $request->input('txtDesignType');
        $loginStaffId = $dataStaffLogin->staffId();
        $dataProduct = $modelProduct->getInfo($productId);
        if ($hFunction->getCount($txtDesignImage) > 0) {
            $name_img = stripslashes($_FILES['txtDesignImage']['name']);
            $name_img = $hFunction->getTimeCode() . '.' . $hFunction->getTypeImg($name_img);
            $source_img = $_FILES['txtDesignImage']['tmp_name'];
            if ($modelProductDesign->uploadImage($source_img, $name_img)) {
                if ($modelProductDesign->insert($name_img, $modelProductDesign->getDefaultDescription(), $productId, $dataStaffLogin->staffId(), $txtDesignType)) {
                    $newId = $modelProductDesign->insertGetId();
                    # nguoi quan ly don hang up thiet ke ===> Ap dụng
                    if ($loginStaffId == $dataProduct->order->staffReceiveId()) {
                        $modelProductDesign->confirmApplyStatus($newId, $modelProductDesign->getDefaultHasApply(), $modelProductDesign->getDefaultHasConfirm(), $dataStaffLogin->staffId());
                    }

                } else {
                    $modelProduct->dropDesignImage($name_img);
                    return "Tính năng đang cập nhật";
                }
            }
        } else {
            return "Chọn ảnh thiết kế";
        }
    }

    #xac nhan su dung anh thiet ke
    public function getConfirmApplyDesign($designId, $applyStatus)
    {
        $modelProductDesign = new QcProductDesign();
        $dataProductDesign = $modelProductDesign->getInfo($designId);
        return view('work.orders.orders.product.confirm-apply-design', compact('dataProductDesign', 'applyStatus'));
    }

    public function postConfirmApplyDesign($designId, $applyStatus)
    {
        $modelStaff = new QcStaff();
        $modelProductDesign = new QcProductDesign();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $modelProductDesign->confirmApplyStatus($designId, $applyStatus, $modelProductDesign->getDefaultHasConfirm(), $dataStaffLogin->staffId());
    }

    #xem chi tiet hinh anh thiet ke
    public function viewProductDesign($designId)
    {
        $hFunction = new \Hfunction();
        $modelProductDesign = new QcProductDesign();
        $dataProductDesign = $modelProductDesign->getInfo($designId);
        if ($hFunction->checkCount($dataProductDesign)) {
            return view('work.orders.orders.product.view-design-image', compact('dataProductDesign'));
        }
    }

    # huy thiet ke san pham hoac thi cong
    public function cancelProductDesign($designId)
    {
        $modelProductDesign = new QcProductDesign();
        return $modelProductDesign->cancelProductDesign($designId);
    }

    # chi tiet thi cong
    public function viewWorkAllocation($allocationId)
    {
        $hFunction = new \Hfunction();
        $modelWorkAllocation = new QcWorkAllocation();
        $dataWorkAllocation = $modelWorkAllocation->getInfo($allocationId);
        if ($hFunction->checkCount($dataWorkAllocation)) {
            return view('work.orders.orders.product.view-report-work-allocation', compact('dataWorkAllocation'));
        }
    }

    #xem anh bao cao truc tiep
    public function viewReportImageDirect($imageId)
    {
        $modelWorkAllocationReportImage = new QcWorkAllocationReportImage();
        $dataWorkAllocationReportImage = $modelWorkAllocationReportImage->getInfo($imageId);
        return view('work.orders.orders.product.view-report-image-direct', compact('dataWorkAllocationReportImage'));
    }

    # xem anh bao cao qua cham cong
    public function viewReportImageTimekeeping($imageId)
    {
        $modelTimekeepingProvisionalImage = new QcTimekeepingProvisionalImage();
        $dataTimekeepingProvisionalImage = $modelTimekeepingProvisionalImage->getInfo($imageId);
        return view('work.orders.orders.product.view-report-timekeeping-image', compact('dataTimekeepingProvisionalImage'));
    }
}
