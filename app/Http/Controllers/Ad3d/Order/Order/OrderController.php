<?php

namespace App\Http\Controllers\Ad3d\Order\Order;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\Customer\QcCustomer;
use App\Models\Ad3d\Order\QcOrder;
use App\Models\Ad3d\OrderAllocation\QcOrderAllocation;
use App\Models\Ad3d\OrderPay\QcOrderPay;
use App\Models\Ad3d\Product\QcProduct;
use App\Models\Ad3d\ProductType\QcProductType;
use App\Models\Ad3d\Staff\QcStaff;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ad3d\WorkAllocation\QcWorkAllocation;
use App\Models\Ad3d\WorkAllocationReportImage\QcWorkAllocationReportImage;
use File;
use Illuminate\Support\Facades\Session;
use Input;
use Request;

class OrderController extends Controller
{
    public function index($companyFilterId = null, $dayFilter = 0, $monthFilter = 0, $yearFilter = 0, $paymentStatus = 3, $orderFilterName = null, $orderCustomerFilterName = null, $staffFilterId = 0)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCustomer = new QcCustomer();
        $modelCompany = new QcCompany();
        $modelOrder = new QcOrder();
        $currentMonth = $hFunction->currentMonth();
        $currentYear = $hFunction->currentYear();
        $orderFilterName = ($orderFilterName == 'null') ? null : $orderFilterName;
        $orderCustomerFilterName = ($orderCustomerFilterName == 'null') ? null : $orderCustomerFilterName;

        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataCompanyLogin = $modelStaff->companyLogin();
        $companyLoginId = $dataCompanyLogin->companyId();
        //dd($dataCompanyLogin);

        $dataAccess = [
            'accessObject' => 'order'
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
        # lay thong tin cong ty cung he thong
        $dataCompany = $modelCompany->getInfoSameSystemOfCompany($companyLoginId);
        if ($hFunction->checkEmpty($companyFilterId) || $companyFilterId == 1000)  $companyFilterId = $companyLoginId;
        if ($staffFilterId > 0) {
            $listStaffId = [$staffFilterId];
        } else {
            $listStaffId = $modelStaff->listIdOfCompany($companyFilterId);
        }

        if (!$hFunction->checkEmpty($orderCustomerFilterName)) {
            $dataOrderSelect = $modelOrder->selectInfoOfListCustomer($modelCustomer->listIdByKeywordName($orderCustomerFilterName), $dateFilter, $paymentStatus);
        } else {
            $dataOrderSelect = $modelOrder->selectInfoByListStaffAndNameAndDateAndPayment($listStaffId, $orderFilterName, $dateFilter, $paymentStatus);
        }

        $dataMoneyOrder = $dataOrderSelect->get();

        $dataOrder = $dataOrderSelect->paginate(30);

        $totalMoneyOrder = $modelOrder->totalMoneyOfListOrder($dataMoneyOrder); // tong tien
        $totalMoneyDiscountOrder = $modelOrder->totalMoneyDiscountOfListOrder($dataMoneyOrder); // tong tien giam
        $totalMoneyPaidOrder = $modelOrder->totalMoneyPaidOfListOrder($dataMoneyOrder); // tong tien da thanh toan
        $totalMoneyUnPaidOrder = $modelOrder->totalMoneyUnPaidOfListOrder($dataMoneyOrder); // chua thanh toán
        $totalOrders = count($dataMoneyOrder);

        //danh sach NV
        $dataStaff = $modelCompany->staffInfoActivityOfCompanyId([$companyFilterId]);
        return view('ad3d.order.order.list', compact('modelStaff', 'dataCompany','modelOrder', 'dataStaff', 'dataAccess', 'dataOrder', 'totalOrders', 'totalMoneyOrder', 'totalMoneyDiscountOrder', 'totalMoneyPaidOrder', 'totalMoneyUnPaidOrder', 'companyFilterId','dayFilter', 'monthFilter', 'yearFilter', 'paymentStatus', 'orderFilterName', 'orderCustomerFilterName', 'staffFilterId'));

    }

    # thong tin chi tiet don hang
    public function view($orderId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelOrder = new QcOrder();
        $dataAccess = [
            'accessObject' => 'order'
        ];
        $dataOrder = $modelOrder->getInfo($orderId);
        if ($hFunction->checkCount($dataOrder)) {
            return view('ad3d.order.order.view', compact('modelStaff', 'dataAccess', 'dataOrder'));
        }

    }

    # xem anh thiet ke san pham
    public function viewProductDesign($productId)
    {

    }

    # xem thong tin khach hang
    public function viewCustomer($customerId)
    {
        $modelCustomer = new QcCustomer();
        $dataCustomer = $modelCustomer->getInfo($customerId);
        if (count($dataCustomer) > 0) {
            $dataOrders = $modelCustomer->orderInfoAllOfCustomer($dataCustomer->customerId());
            return view('ad3d.order.order.view-customer', compact('dataCustomer', 'dataOrders'));
        }
    }

    // ================ LOC DON HÀNG DON HANG ===================
    // theo ten don hang
    public function filterCheckOrderName($name)
    {
        $modelStaff = new QcStaff();
        $modelOrder = new QcOrder();
        $dataOrder = $modelOrder->infoFromSuggestionNameOffReceiveStaff($modelStaff->loginStaffId(), $name);
        if (count($dataOrder) > 0) {
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
        $modelCustomer = new QcCustomer();
        $dataCustomer = $modelCustomer->infoFromSuggestionName($name);
        if (count($dataCustomer) > 0) {
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

    #in hoa don
    public function printOrder($orderId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelOrder = new QcOrder();
        $dataAccess = [
            'accessObject' => 'order'
        ];
        $dataOrder = $modelOrder->getInfo($orderId);
        if ($hFunction->checkCount($dataOrder)) {
            return view('ad3d.order.order.print', compact('modelStaff', 'dataAccess', 'dataOrder'));
        }

    }

    #in phieu nghiem thu
    public function printConfirmOrder($orderId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelOrder = new QcOrder();
        $dataAccess = [
            'accessObject' => 'order'
        ];
        $dataOrder = $modelOrder->getInfo($orderId);
        if ($hFunction->checkCount($dataOrder)) {
            return view('ad3d.order.order.print-confirm', compact('modelStaff', 'dataAccess', 'dataOrder'));
        }

    }

    // ================ TRIEN KHAI THI CONG ===================
    # chi tiet thi cong
    public function viewWorkAllocation($allocationId)
    {
        $hFunction = new \Hfunction();
        $modelWorkAllocation = new QcWorkAllocation();
        $dataWorkAllocation = $modelWorkAllocation->getInfo($allocationId);
        if ($hFunction->checkCount($dataWorkAllocation)) {
            return view('ad3d.order.order.view-work-allocation', compact('dataWorkAllocation'));
        }
    }

    #xem anh ban cao thi cong san pham
    public function viewReportImage($imageId)
    {
        $modelWorkAllocationReportImage = new QcWorkAllocationReportImage();
        $dataWorkAllocationReportImage = $modelWorkAllocationReportImage->getInfo($imageId);
        if (count($dataWorkAllocationReportImage) > 0) {
            return view('ad3d.order.order.view-construction-report-image', compact('dataWorkAllocationReportImage'));
        }
    }

    # ban giao don hang - cong trinh
    public function getOrderConstruction($orderId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelOrders = new QcOrder();
        $dataAccess = [
            'accessObject' => 'order'
        ];
        $dataOrder = $modelOrders->getInfo($orderId);
        $dataReceiveStaff = $modelStaff->infoActivityOfCompany($dataOrder->companyId()); //Lay NV cty so huu don hang
        if ($hFunction->checkCount($dataOrder)) {
            return view('ad3d.order.order.construction', compact('modelStaff', 'dataAccess', 'dataReceiveStaff', 'dataOrder'));
        } else {
            return redirect()->back();
        }
    }

    public function postOrderConstruction($orderId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelOrdersAllocation = new QcOrderAllocation();
        $cbReceiveStaffId = Request::input('cbReceiveStaff');
        $cbAllocationDay = Request::input('cbAllocationDay');
        $cbAllocationMonth = Request::input('cbAllocationMonth');
        $cbAllocationYear = Request::input('cbAllocationYear');
        $cbAllocationHours = Request::input('cbAllocationHours');

        $cbDeadlineDay = Request::input('cbDeadlineDay');
        $cbDeadlineMonth = Request::input('cbDeadlineMonth');
        $cbDeadlineYear = Request::input('cbDeadlineYear');
        $cbDeadlineMinute = Request::input('cbDeadlineMinute');
        $dateAllocation = $hFunction->convertStringToDatetime("$cbAllocationMonth/$cbAllocationDay/$cbAllocationYear $cbAllocationHours:00:00");
        $dateDeadline = $hFunction->convertStringToDatetime("$cbDeadlineMonth/$cbDeadlineDay/$cbDeadlineYear $cbDeadlineMinute:00:00");
        if ($modelStaff->checkLogin()) {
            if ($hFunction->checkEmpty($cbReceiveStaffId)) {
                Session::put('notifyAdd', "Chọn nhân viên bàn giao");
            } else {
                if ($dateDeadline > $dateAllocation) {
                    if (!$modelOrdersAllocation->checkStaffReceiveOrder($cbReceiveStaffId, $orderId)) {
                        $modelOrdersAllocation->insert($dateAllocation, 0, $dateDeadline, '', $orderId, $cbReceiveStaffId, $modelStaff->loginStaffId());
                    } else {
                        Session::put('notifyAdd', "Nhân viện không được nhận công trình 2 lần");
                    }

                } else {
                    Session::put('notifyAdd', "Ngày giao phải lớn hơn ngày nhận");
                }
            }
        }
        return redirect()->back();
    }

    #huy ban giao thi cong
    public function deleteOrderConstruction($allocationId)
    {
        $modelOrderAllocation = new QcOrderAllocation();
        $modelOrderAllocation->cancel($allocationId);
    }
    // ------------------ THONG TIN CŨ
    #kiem tra khach hang qua sđt
    public function checkPhoneCustomer($phone)
    {
        $modelCustomer = new QcCustomer();
        $dataCustomer = $modelCustomer->infoFromPhone($phone);
        if (count($dataCustomer) > 0) {
            $result = array(
                'status' => 'exist',
                'customerId' => $dataCustomer->customerId()
            );
        } else {
            $result = array(
                'status' => 'notExist',
                'customerId' => 'null'
            );
        }
        die(json_encode($result));
    }

    //tao don hang
    public function addProduct()
    {
        $modelProductType = new QcProductType();
        $dataProductType = $modelProductType->infoActivity();
        return view('ad3d.order.order.add-product', compact('dataProductType'));
    }

    # them order moi va them san pham
    public function getAdd($customerId = null, $orderId = null)
    {
        $modelStaff = new QcStaff();
        $modelOrder = new QcOrder();
        $modelCustomer = new QcCustomer();
        $modelProductType = new QcProductType();
        $dataAccess = [
            'accessObject' => 'order'
        ];
        $dataCustomer = (empty($customerId)) ? $customerId : $modelCustomer->getInfo($customerId);
        $dataOrder = (empty($orderId)) ? $orderId : $modelOrder->getInfo($orderId);
        $dataProductType = $modelProductType->infoActivity();
        return view('ad3d.order.order.add', compact('modelStaff', 'dataAccess', 'dataProductType', 'dataCustomer', 'dataOrder'));
    }

    public function postAdd()
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $modelCustomer = new QcCustomer();
        $modelOrder = new QcOrder();
        $modelOrderPay = new QcOrderPay();

        $modelStaff = new QcStaff();

        $staffLoginId = $modelStaff->loginStaffId();
        //thong tin khach hang
        $txtCustomerName = Request::input('txtCustomerName');
        $txtPhone = Request::input('txtPhone');
        $txtZalo = Request::input('txtZalo');
        $txtAddress = Request::input('txtAddress');
        //thong tin san pham
        $productType = Request::input('cbProductType');
        $txtWidth = Request::input('txtWidth');
        $txtHeight = Request::input('txtHeight');
        $txtDepth = Request::input('txtDepth');
        $txtAmount = Request::input('txtAmount');
        $txtPrice = Request::input('txtPrice');
        $txtDescription = Request::input('txtDescription');
        $txtWidth = (empty($txtWidth)) ? 0 : $txtWidth;
        $txtHeight = (empty($txtHeight)) ? 0 : $txtHeight;
        $txtDepth = (empty($txtDepth)) ? 0 : $txtDepth;


        //thong tin don hang
        $txtOrderName = Request::input('txtOrderName');
        $txtConstructionAddress = Request::input('txtConstructionAddress');
        $txtConstructionPhone = Request::input('txtConstructionPhone');
        $txtConstructionContact = Request::input('txtConstructionContact');
        $txtBeforePay = Request::input('txtBeforePay');
        $txtDateReceive = Request::input('txtDateReceive');
        $txtDateDelivery = Request::input('txtDateDelivery');
        $cbDiscount = Request::input('cbDiscount');
        $cbVat = Request::input('cbVat');
        $oldCustomerId = null;
        $dataCustomer = null;
        $txtBeforePay = $txtBeforePay * 1000;


        if (!empty($txtPhone)) {
            #lay thong tin khach hang tu so dien thoai di dong
            $dataCustomer = $modelCustomer->infoFromPhone($txtPhone);
        }
        if (!empty($txtZalo) && count($dataCustomer) <= 0) {
            # lay thong tin khach hang tu so zalo
            $dataCustomer = $modelCustomer->infoFromZalo($txtPhone);
        }

        if (count($dataCustomer) > 0) {
            # ton tai khach hang - khach hang cu
            $customerId = $dataCustomer->customerId();
            $customerName = $dataCustomer->name();
        } else {
            # khach hang moi
            if ($modelCustomer->insert($txtCustomerName, null, null, $txtAddress, $txtPhone, $txtZalo)) {
                $customerId = $modelCustomer->insertGetId();
                $customerName = $txtCustomerName;
            }
        }
        if (!empty($customerId)) {
            #cap nhat thong tin khach hang ne co thay doi dia chi
            $modelCustomer->updateInfo($customerId, $customerName, null, null, $txtAddress, $txtPhone, $txtZalo);
            # them don hang
            $txtConstructionAddress = (empty($txtConstructionAddress)) ? $txtAddress : $txtConstructionAddress;
            $txtConstructionPhone = (empty($txtConstructionPhone)) ? $txtPhone : $txtConstructionPhone;
            $txtConstructionContact = (empty($txtConstructionContact)) ? $txtCustomerName : $txtConstructionContact;
            if ($modelOrder->insert($txtOrderName, $cbDiscount, $cbVat, $txtDateReceive, $txtDateDelivery, $customerId, $staffLoginId, $staffLoginId, null, 1, $txtConstructionAddress, $txtConstructionPhone, $txtConstructionContact)) {
                $orderId = $modelOrder->insertGetId();
                if (count($productType) > 0) {
                    # them san pham
                    foreach ($productType as $key => $value) {
                        $typeId = $value;
                        $width = $txtWidth[$key];
                        $height = $txtHeight[$key];
                        $depth = $txtDepth[$key];
                        $amount = $txtAmount[$key];
                        $price = $txtPrice[$key] * 1000;
                        $description = $txtDescription[$key];
                        $modelProduct = new QcProduct();
                        $modelProduct->insert($width, $height, $depth, $price, $amount, $description, $typeId, $orderId);
                    }
                }

                # thanh toan
                if ($txtBeforePay > 0) {
                    # thanh toan don hang
                    $modelOrderPay->insert($txtBeforePay, null, $txtDateReceive, $orderId, $staffLoginId);
                }
                # cap nhat thong tin thanh toan don hang
                $modelOrder->updateFinishPayment($orderId);
                return redirect()->route('qc.ad3d.order.order.print.get', $orderId);
            } else {
                Session::put('notifyAdd', 'Thêm thất bại, hãy thử lại');
                return redirect()->back();
            }
        }

    }

    public function postEditAddProduct($orderId)
    {
        //thong tin san pham
        $productType = Request::input('cbProductType');
        $txtWidth = Request::input('txtWidth');
        $txtHeight = Request::input('txtHeight');
        $txtDepth = Request::input('txtDepth');
        $txtAmount = Request::input('txtAmount');
        $txtPrice = Request::input('txtPrice');
        $txtDescription = Request::input('txtDescription');
        $txtWidth = (empty($txtWidth)) ? 0 : $txtWidth;
        $txtHeight = (empty($txtHeight)) ? 0 : $txtHeight;
        $txtDepth = (empty($txtDepth)) ? 0 : $txtDepth;
        if (count($productType) > 0) {
            # them san pham
            foreach ($productType as $key => $value) {
                $typeId = $value;
                $width = $txtWidth[$key];
                $height = $txtHeight[$key];
                $depth = $txtDepth[$key];
                $amount = $txtAmount[$key];
                $price = $txtPrice[$key];
                $description = $txtDescription[$key];
                $modelProduct = new QcProduct();
                $modelProduct->insert($width, $height, $depth, $price, $amount, $description, $typeId, $orderId);
            }
        }
        return redirect()->route('qc.ad3d.order.order.print.get', $orderId);
    }

    # xac nhan don hang
    public function getConfirm($orderId)
    {
        $modelOrder = new QcOrder();
        $dataAccess = [
            'accessObject' => 'order'
        ];
        $dataOrder = $modelOrder->getInfo($orderId);
        if (count($dataOrder) > 0) {
            return view('ad3d.order.order.confirm', compact('dataAccess', 'dataOrder'));
        }
    }

    public function postConfirm($orderId)
    {
        $modelStaff = new QcStaff();
        $modelOrder = new QcOrder();
        $confirmAgree = Request::input('cbConfirmAgree');
        $staffLoginId = $modelStaff->loginStaffId();
        $dataOrder = $modelOrder->getInfo($orderId);
        if (!empty($staffLoginId) && count($dataOrder) > 0) {
            $modelOrder->confirm($orderId, $confirmAgree, $staffLoginId);
        }

    }

    // thanh toan don hang
    public function getPayment($orderId)
    {
        $modelStaff = new QcStaff();
        $modelOrders = new QcOrder();
        $dataAccess = [
            'accessObject' => 'order'
        ];
        $dataOrder = $modelOrders->getInfo($orderId);
        if (count($dataOrder) > 0) {
            return view('ad3d.order.order.payment', compact('modelStaff', 'dataAccess', 'dataOrder'));
        } else {
            return redirect()->back();
        }

    }

    public function postPayment($orderId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelOrders = new QcOrder();
        $modelOrderPay = new QcOrderPay();
        $txtMoney = Request::input('txtMoney');
        $txtName = Request::input('txtName');
        $txtPhone = Request::input('txtPhone');
        $txtNote = Request::input('txtNote');
        $dataOrder = $modelOrders->getInfo($orderId);
        if (count($txtMoney) > 0 && count($dataOrder) > 0) {
            if ($modelOrderPay->insert($txtMoney, $txtNote, $hFunction->carbonNow(), $orderId, $modelStaff->loginStaffId(), $txtName, $txtPhone)) {
                return redirect()->route('qc.ad3d.order.order.get');
            }
        } else {
            Session::put('notifyAdd', "Phải nhập số tiền thanh toán");
            return redirect()->back();
        }
    }

}
