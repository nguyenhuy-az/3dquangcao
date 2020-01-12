<?php

namespace App\Http\Controllers\Work\Orders;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\Customer\QcCustomer;
use App\Models\Ad3d\Order\QcOrder;
use App\Models\Ad3d\OrderAllocation\QcOrderAllocation;
use App\Models\Ad3d\OrderCancel\QcOrderCancel;
use App\Models\Ad3d\OrderPay\QcOrderPay;
use App\Models\Ad3d\Product\QcProduct;
use App\Models\Ad3d\ProductDesign\QcProductDesign;
use App\Models\Ad3d\ProductType\QcProductType;
use App\Models\Ad3d\Staff\QcStaff;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use File;
use Illuminate\Support\Facades\Session;
use Input;
use Request;

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

    public function index($monthFilter = 0, $yearFilter = 0, $paymentStatus = 3, $orderFilterName = null, $orderCustomerFilterName = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelOrders = new QcOrder();
        $modelCustomer = new QcCustomer();
        $hFunction->dateDefaultHCM();
        $orderFilterName = ($orderFilterName == 'null') ? null : $orderFilterName;
        $orderCustomerFilterName = ($orderCustomerFilterName == 'null') ? null : $orderCustomerFilterName;
        if ($modelStaff->checkLogin()) {
            $dataAccess = [
                'object' => 'orders'
            ];
            $dataStaffLogin = $modelStaff->loginStaffInfo();
            $loginStaffId = $dataStaffLogin->staffId();
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
                $dateFilter = date('Y-m');
                $monthFilter = date('m');
                $yearFilter = date('Y');

            } else {
                $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
            }
            if (!empty($orderCustomerFilterName)) {
                $dataOrders = $modelOrders->infoOfListCustomer($modelCustomer->listIdByKeywordName($orderCustomerFilterName), $dateFilter, $paymentStatus);
            } else {
                $dataOrders = $dataStaffLogin->orderAndPayInfoOfStaffReceive($loginStaffId, $dateFilter, $paymentStatus, $orderFilterName);
            }
            $dataOrdersProvisional = $dataStaffLogin->orderProvisionAndPayInfoOfStaffReceive($loginStaffId, $dateFilter, 0, null);
            return view('work.orders.orders.index', compact('modelOrders', 'dataAccess', 'modelStaff', 'dataOrders', 'dataOrdersProvisional', 'dataStaffLogin', 'dateFilter', 'monthFilter', 'yearFilter', 'paymentStatus', 'orderFilterName', 'orderCustomerFilterName'));
        } else {
            return redirect()->route('qc.work.login.get');
        }

    }

    //quan ly don hang
    public function index_1($monthFilter = 0, $yearFilter = 0, $salesConfirm = 3, $keyWork = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelOrders = new QcOrder();
        $hFunction->dateDefaultHCM();
        if ($modelStaff->checkLogin()) {
            $dataAccess = [
                'object' => 'orders'
            ];
            $dataStaffLogin = $modelStaff->loginStaffInfo();
            $loginStaffId = $dataStaffLogin->staffId();
            //$loginMonth = (empty($loginMonth)) ? date('m') : $loginMonth;
            //$loginYear = (empty($loginYear)) ? date('Y') : $loginYear;
            if ($monthFilter == 100 && $yearFilter == 100) {//xem tất cả đơn hang
                $dateFilter = null;
            }
            if ($monthFilter < 100 && $yearFilter == 100) { //xem tất cả đơn hang
                $dateFilter = null;
                $monthFilter = 100;
            } elseif ($monthFilter == 100 && $yearFilter != 100) {
                $dateFilter = date('Y', strtotime("1-1-$yearFilter"));
            } elseif ($monthFilter < 100 && $yearFilter == 100) {
                $yearFilter = $hFunction->currentYear();
                $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
            } elseif ($monthFilter == 0 && $yearFilter == 0) {
                $dateFilter = date('Y-m');
                $monthFilter = date('m');
                $yearFilter = date('Y');

            } else {
                $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
            }
            $dataOrders = $dataStaffLogin->orderInfoOfStaffReceive($loginStaffId, $dateFilter, $salesConfirm, $keyWork);
            return view('work.orders.orders.index', compact('modelOrders', 'dataAccess', 'modelStaff', 'dataOrders', 'dataStaffLogin', 'monthFilter', 'yearFilter', 'salesConfirm', 'keyWork'));
        } else {
            return view('work.login');
        }

    }

    # thong tin chi tiet
    public function viewOrders($orderId)
    {
        $modelOrder = new QcOrder();
        $dataAccess = [
            'object' => 'orders',
            'subObjectLabel' => 'Chi tiết đơn hàng'
        ];

        $dataOrders = $modelOrder->getInfo($orderId);
        if (count($dataOrders) > 0) {
            return view('work.orders.orders.view', compact('dataAccess', 'dataOrders'));
        }
    }

    # xem chi tiet thanh toan
    public function viewOrderPay($orderId)
    {
        $modelOrder = new QcOrder();
        $dataOrder = $modelOrder->getInfo($orderId);
        if (count($dataOrder) > 0) {
            return view('work.orders.orders.view-order-pay', compact('dataOrder'));
        }
    }

    #in hoa don
    public function printOrders($orderId)
    {
        $modelStaff = new QcStaff();
        $modelOrder = new QcOrder();
        $dataAccess = [
            'object' => 'orders'
        ];
        if ($modelStaff->checkLogin()) {
            $dataOrders = $modelOrder->getInfo($orderId);
            if (count($dataOrders) > 0) {
                return view('work.orders.orders.print', compact('modelStaff', 'dataAccess', 'dataOrders'));
            }
        } else {
            return view('work.login');
        }

    }

    # xem thong tin khach hang
    public function viewCustomer($customerId)
    {
        $modelCustomer = new QcCustomer();
        $dataCustomer = $modelCustomer->getInfo($customerId);
        if (count($dataCustomer) > 0) {
            $dataOrders = $modelCustomer->orderInfoAllOfCustomer($dataCustomer->customerId());
            return view('work.orders.customer.view', compact('dataCustomer', 'dataOrders'));
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

    // ================ THEM DON HANG ===================
    #kiem tra khach hang qua sđt
    public function checkCustomerPhone($phone)
    {
        $modelCustomer = new QcCustomer();
        $dataCustomer = $modelCustomer->infoFromSuggestionPhone($phone);
        if (count($dataCustomer) > 0) {
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

    // tim ten don hang
    public function checkOrderName($name)
    {
        $modelOrder = new QcOrder();
        $dataOrder = $modelOrder->infoFromSuggestionName($name);
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
        //return 'yes';
    }

    //loai san pham
    public function checkProductType($name)
    {
        $modelProductType = new QcProductType();
        $dataProductType = $modelProductType->infoFromSuggestionName($name);
        if (count($dataProductType) > 0) {
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
    public function addProduct()
    {
        $modelProductType = new QcProductType();
        $dataProductType = $modelProductType->infoActivity();
        return view('work.orders.orders.add-product', compact('dataProductType'));
    }

    # them order moi va them san pham
    public function getAdd($orderType = 1, $customerId = null, $orderId = null) // $type: 1 - don hang thuc / 2 - don hang bao gia
    {
        $modelStaff = new QcStaff();
        $modelOrder = new QcOrder();
        $modelCustomer = new QcCustomer();
        $dataAccess = [
            'object' => 'orders',
            'subObjectLabel' => 'Sản phảm'
        ];
        $dataCustomer = (empty($customerId)) ? $customerId : $modelCustomer->getInfo($customerId);
        $dataOrders = (empty($orderId)) ? $orderId : $modelOrder->getInfo($orderId);
        return view('work.orders.orders.add', compact('modelStaff', 'dataAccess', 'dataCustomer', 'orderType', 'dataOrders'));
    }

    // them don hang thuc
    public function postAdd()
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $hFunction = new \Hfunction();
        $modelProductType = new QcProductType();
        $modelCustomer = new QcCustomer();
        $modelOrder = new QcOrder();
        $modelOrderPay = new QcOrderPay();
        $modelOrderAllocation = new QcOrderAllocation();
        $modelStaff = new QcStaff();

        $dataStaff = $modelStaff->loginStaffInfo();
        $staffLoginId = $modelStaff->loginStaffId();
        //thong tin khach hang
        $txtCustomerName = Request::input('txtCustomerName');
        $txtPhone = Request::input('txtPhone');
        $txtZalo = Request::input('txtZalo');
        $txtAddress = Request::input('txtAddress');

        //thong tin san pham
        $productType = Request::input('txtProductType');
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
        $txtBeforePay = $hFunction->convertCurrencyToInt($txtBeforePay);
        $txtDateReceive = $hFunction->carbonNow();//Request::input('txtDateReceive');
        $txtDateDelivery = Request::input('txtDateDelivery');
        $cbDiscount = Request::input('cbDiscount');
        $cbVat = Request::input('cbVat');
        $oldCustomerId = null;
        $dataCustomer = null;
        if (count($dataStaff) > 0) {
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
            if (!empty($customerId) && !empty($staffLoginId)) {
                #cap nhat thong tin khach hang neu co thay doi dia chi
                $modelCustomer->updateInfo($customerId, $customerName, null, null, $txtAddress, $txtPhone, $txtZalo);
                # them don hang
                $txtConstructionAddress = (empty($txtConstructionAddress)) ? $txtAddress : $txtConstructionAddress;
                $txtConstructionPhone = (empty($txtConstructionPhone)) ? $txtPhone : $txtConstructionPhone;
                $txtConstructionContact = (empty($txtConstructionContact)) ? $txtCustomerName : $txtConstructionContact;
                if ($modelOrder->insert($txtOrderName, $cbDiscount, $cbVat, $txtDateReceive, $txtDateDelivery, $customerId, $staffLoginId, $staffLoginId, null, 1, $txtConstructionAddress, $txtConstructionPhone, $txtConstructionContact, 1, null, 1)) {
                    $orderId = $modelOrder->insertGetId();
                    if (count($productType) > 0) {
                        # them san pham
                        foreach ($productType as $key => $value) {
                            $dataProductType = $modelProductType->infoFromExactlyName($value);
                            if (count($dataProductType) > 0) {
                                $productTypeId = $dataProductType->typeId();
                            } else {
                                if ($modelProductType->insert(null, $value, null, null, 0, 0)) {
                                    $productTypeId = $modelProductType->insertGetId();
                                } else {
                                    $productTypeId = null;
                                }
                            }
                            if (!empty($productTypeId)) {
                                $width = $txtWidth[$key];
                                $height = $txtHeight[$key];
                                $depth = $txtDepth[$key];
                                $amount = $txtAmount[$key];
                                $price = $hFunction->convertCurrencyToInt($txtPrice[$key]);
                                $description = $txtDescription[$key];
                                $modelProduct = new QcProduct();
                                $modelProduct->insert($width, $height, $depth, $price, $amount, $description, $productTypeId, $orderId);
                            }
                        }
                    }
                    # thanh toan
                    if ($txtBeforePay > 0) {
                        # thanh toan don hang
                        $modelOrderPay->insert($txtBeforePay, null, $txtDateReceive, $orderId, $staffLoginId, $txtCustomerName, $txtPhone);
                    }
                    # cap nhat thong tin thanh toan don hang
                    $modelOrder->updateFinishPayment($orderId);

                    # bàn giao don hang = cong trinh
                    $modelOrderAllocation->insert($txtDateReceive, 0, $txtDateDelivery, 'Bàn giao khi nhận đơn hàng', $orderId, $staffLoginId, null);

                    return redirect()->route('qc.work.orders.print.get', $orderId);
                } else {
                    Session::put('notifyAdd', 'Thêm thất bại, hãy thử lại');
                    return redirect()->back();
                }
            } else {
                Session::put('notifyAdd', 'Thêm thất bại, hãy thử lại');
                return redirect()->back();
            }
        } else {
            return redirect()->route('qc.work.login.get');
        }

    }

    // them don hang tam
    public function postAddProvisional()
    {
        $hFunction = new \Hfunction();
        $modelProductType = new QcProductType();
        $modelCustomer = new QcCustomer();
        $modelOrder = new QcOrder();
        $modelStaff = new QcStaff();

        $dataStaff = $modelStaff->loginStaffInfo();
        //thong tin khach hang
        $txtCustomerName = Request::input('txtCustomerName');
        $txtPhone = Request::input('txtPhone');
        $txtZalo = Request::input('txtZalo');
        $txtAddress = Request::input('txtAddress');

        //thong tin san pham
        $productType = Request::input('txtProductType');
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
        $txtDateReceive = null;
        $txtDateDelivery = null;
        $cbDiscount = Request::input('cbDiscount');
        $cbVat = Request::input('cbVat');
        $oldCustomerId = null;
        $dataCustomer = null;
        if (count($dataStaff) > 0) {
            $staffLoginId = $dataStaff->staffId();
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
            if (!empty($customerId) && !empty($staffLoginId)) {
                #cap nhat thong tin khach hang neu co thay doi dia chi
                $modelCustomer->updateInfo($customerId, $customerName, null, null, $txtAddress, $txtPhone, $txtZalo);
                # them don hang
                $txtConstructionAddress = (empty($txtConstructionAddress)) ? $txtAddress : $txtConstructionAddress;
                $txtConstructionPhone = (empty($txtConstructionPhone)) ? $txtPhone : $txtConstructionPhone;
                $txtConstructionContact = (empty($txtConstructionContact)) ? $txtCustomerName : $txtConstructionContact;
                if ($modelOrder->insert($txtOrderName, $cbDiscount, $cbVat, $txtDateReceive, $txtDateDelivery, $customerId, $staffLoginId, $staffLoginId, null, 0, $txtConstructionAddress, $txtConstructionPhone, $txtConstructionContact, 0, $hFunction->carbonNow(), 0)) {
                    $orderId = $modelOrder->insertGetId();
                    if (count($productType) > 0) {
                        # them san pham
                        foreach ($productType as $key => $value) {
                            $dataProductType = $modelProductType->infoFromExactlyName($value);
                            if (count($dataProductType) > 0) {
                                $productTypeId = $dataProductType->typeId();
                            } else {
                                if ($modelProductType->insert(null, $value, null, null, 0, 0)) {
                                    $productTypeId = $modelProductType->insertGetId();
                                } else {
                                    $productTypeId = null;
                                }
                            }
                            if (!empty($productTypeId)) {
                                $width = $txtWidth[$key];
                                $height = $txtHeight[$key];
                                $depth = $txtDepth[$key];
                                $amount = $txtAmount[$key];
                                $price = $hFunction->convertCurrencyToInt($txtPrice[$key]);
                                $description = $txtDescription[$key];
                                $modelProduct = new QcProduct();
                                $modelProduct->insert($width, $height, $depth, $price, $amount, $description, $productTypeId, $orderId);
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
        } else {
            return view('work.login');
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
        $dataOrders = (empty($orderId)) ? $orderId : $modelOrder->getInfo($orderId);
        return view('work.orders.orders.edit-add-product', compact('modelStaff', 'dataAccess', 'dataOrders'));
    }

    public function editAddProduct()
    {
        return view('work.orders.orders.add-product');
    }

    public function postEditAddProduct($orderId)
    {
        $hFunction = new \Hfunction();
        $modelOrders = new QcOrder();
        $modelProductType = new QcProductType();
        //thong tin san pham
        $productType = Request::input('txtProductType');
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
                $dataProductType = $modelProductType->infoFromExactlyName($value);
                if (count($dataProductType) > 0) {
                    $productTypeId = $dataProductType->typeId();
                } else {
                    if ($modelProductType->insert(null, $value, null, null, 0, 0)) {
                        $productTypeId = $modelProductType->insertGetId();
                    } else {
                        $productTypeId = null;
                    }
                }
                if (!empty($productTypeId)) {
                    $width = $txtWidth[$key];
                    $height = $txtHeight[$key];
                    $depth = $txtDepth[$key];
                    $amount = $txtAmount[$key];
                    $price = $hFunction->convertCurrencyToInt($txtPrice[$key]);
                    $description = $txtDescription[$key];
                    $modelProduct = new QcProduct();
                    $modelProduct->insert($width, $height, $depth, $price, $amount, $description, $productTypeId, $orderId);
                }
            }
        }
        //$dataOrder = $modelOrders->getInfo($orderId);
        //$pageBack = 2;
        return redirect()->route('qc.work.orders.info.get', $orderId);
        //return view('work.orders.orders.order-info', compact('dataAccess', 'dataOrder', 'pageBack'));
    }

    // ======== ======= BAO CAO HOAN THANH ======= =======
    public function getReportFinish($orderId)
    {
        $modelStaff = new QcStaff();
        $modelOrder = new QcOrder();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        if (count($dataStaffLogin) > 0) {
            $dataOrder = $modelOrder->getInfo($orderId);
            if (count($dataOrder) > 0) {
                return view('work.orders.orders.confirm-finish', compact('dataStaffLogin', 'dataOrder'));
            }
        }
    }

    public function postReportFinish($orderId)
    {
        $modelStaff = new QcStaff();
        $modelOrder = new QcOrder();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        if (count($dataStaffLogin) > 0) {
            $staffLoginId = $dataStaffLogin->staffId();
            $modelOrder->confirmReportFinish($orderId, 1, $staffLoginId);
        }
    }

    // ======= ===== ==== THANH TOAN DON HANG ====== ======== =====
    // thanh toan don hang
    public function getPayment($orderId)
    {
        $modelOrders = new QcOrder();
        $dataAccess = [
            'object' => 'orders',
            'subObjectLabel' => 'Thanh toán'
        ];
        $dataOrder = $modelOrders->getInfo($orderId);
        if (count($dataOrder) > 0) {
            return view('work.orders.orders.payment', compact('dataAccess', 'dataOrder'));
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
        $txtMoney = $hFunction->convertCurrencyToInt($txtMoney);
        $dataOrder = $modelOrders->getInfo($orderId);
        if (count($txtMoney) > 0 && count($dataOrder) > 0) {
            if ($modelOrderPay->insert($txtMoney, $txtNote, $hFunction->carbonNow(), $orderId, $modelStaff->loginStaffId(), $txtName, $txtPhone)) {
                # cap nhat thong tin thanh toan don hang
                $modelOrders->updateFinishPayment($orderId);
                return redirect()->route('qc.work.orders.print.get', $orderId);
            }
        } else {
            Session::put('notifyAdd', "Phải nhập số tiền thanh toán");
            return redirect()->back();
        }
    }

    // ================ HUY DON HANG ===================
    public function getOrderCancel($orderId = null)
    {
        $modelOrders = new QcOrder();
        $dataOrder = $modelOrders->getInfo($orderId);
        if (count($dataOrder) > 0) {
            return view('work.orders.orders.cancel', compact('dataOrder'));
        }
    }

    public function postOrderCancel($orderId = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelOrder = new QcOrder();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $txtPayment = Request::input('txtPayment');
        $txtReason = Request::input('txtReason');
        $txtPayment = $hFunction->convertCurrencyToInt($txtPayment);

        if (count($dataStaffLogin) > 0) {
            $dataOrder = $modelOrder->getInfo($orderId);
            if (count($dataOrder) > 0) {
                $modelOrder->cancelOrder($orderId, $txtPayment, $txtReason, $dataStaffLogin->staffId());
            }
        } else {
            return view('work.login');
        }

    }

    //======= ======== ===== QUAN LY THONG TIN DƠN HANG ==== ======== ======
    public function ordersInfo($orderId)
    {
        $modelOrders = new QcOrder();
        $dataAccess = [
            'object' => 'orders',
            'subObjectLabel' => 'Quản lý đơn hàng'
        ];
        $dataOrder = $modelOrders->getInfo($orderId);
        if (count($dataOrder) > 0) {
            $pageBack = 1;
            return view('work.orders.orders.order-info', compact('dataAccess', 'dataOrder', 'pageBack'));
        }
    }

    // thay doi thong tin khach hang
    public function postEditInfoCustomer($customerId)
    {
        $modelCustomer = new QcCustomer();
        $txtCustomerName = Request::input('txtCustomerName');
        $txtCustomerPhone = Request::input('txtCustomerPhone');
        $txtCustomerZalo = Request::input('txtCustomerZalo');
        $txtCustomerAddress = Request::input('txtCustomerAddress');
        if (!$modelCustomer->updateInfo($customerId, $txtCustomerName, null, null, $txtCustomerAddress, $txtCustomerPhone, $txtCustomerZalo)) {
            return "Tính năng đang bảo trì";
        }

    }

    // thay doi thong tin don hang
    public function postEditInfoOrder($orderId)
    {
        $modelOrder = new QcOrder();
        $txtOrderName = Request::input('txtOrderName');
        $cbDiscount = Request::input('cbDiscount');
        $cbVat = Request::input('cbVat');
        $txtConstructionAddress = Request::input('txtConstructionAddress');
        $txtConstructionPhone = Request::input('txtConstructionPhone');
        $txtConstructionContact = Request::input('txtConstructionContact');
        if ($modelOrder->checkProvisionUnConfirmed($orderId)) { // don hang dang bao gia
            $txtDateReceive = null;
            $txtDateDelivery = null;
        } else {
            $txtDateReceive = Request::input('txtDateReceive');
            $txtDateDelivery = Request::input('txtDateDelivery');
        }
        $staffReceiveId = $modelOrder->staffReceiveId($orderId)[0];
        if (!$modelOrder->updateInfo($orderId, $txtOrderName, $cbDiscount, $cbVat, $txtDateReceive, $txtDateDelivery, $staffReceiveId, $txtConstructionAddress, $txtConstructionPhone, $txtConstructionContact)) {
            return "Tính năng đang bảo trì";
        }
    }

    //sua thong tin thanh toan
    public function getEditInfoPay($payId)
    {
        $modelOrderPay = new QcOrderPay();
        $dataOrderPay = $modelOrderPay->getInfo($payId);
        if (count($dataOrderPay) > 0) {
            return view('work.orders.orders.edit-order-info-pay', compact('dataAccess', 'dataOrderPay'));
        }
    }

    public function postEditInfoPay($payId)
    {
        $hFunction = new \Hfunction();
        $modelOrderPay = new QcOrderPay();
        $txtPayName = Request::input('txtPayName');
        $txtPayPhone = Request::input('txtPayPhone');
        $txtPayMoney = Request::input('txtPayMoney');
        $txtPayMoney = $hFunction->convertCurrencyToInt($txtPayMoney);
        if (!$modelOrderPay->updateInfo($payId, $txtPayMoney, $txtPayName, $txtPayPhone)) {
            return "Tính năng đang bảo trì";
        }
    }

    //======== ======= =====  QUAN LY SAN PHAM CUA DON HANG ======= ========= ========
    public function productList($orderId = null)
    {
        $modelOrders = new QcOrder();
        $dataAccess = [
            'object' => 'orders',
            'subObjectLabel' => 'Sản phẩm'
        ];
        $dataOrders = $modelOrders->getInfo($orderId);
        if (count($dataOrders) > 0) {
            return view('work.orders.product.product', compact('dataAccess', 'dataOrders'));
        }

    }

    # cap nhat hong tin SP
    public function getProductInfoEdit($productId)
    {
        $modelProduct = new QcProduct();
        $dataProduct = $modelProduct->getInfo($productId);
        if (count($dataProduct) > 0) {
            return view('work.orders.orders.product.edit-info', compact('dataProduct'));
        }
    }

    public function postProductInfoEdit($productId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelProduct = new QcProduct();
        $txtWidth = Request::input('txtWidth');
        $txtHeight = Request::input('txtHeight');
        $txtAmount = Request::input('txtAmount');
        $txtPrice = Request::input('txtPrice');
        $txtDescription = Request::input('txtDescription');
        $txtPrice = $hFunction->convertCurrencyToInt($txtPrice);
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        if (count($dataStaffLogin) > 0) {
            $dataProduct = $modelProduct->getInfo($productId);
            if (count($dataProduct) > 0) {
                $modelProduct->updateInfoNotType($productId, $txtWidth, $txtHeight, 0, $txtPrice, $txtAmount, $txtDescription);
            } else {
                return "Tính năng đang cập nhật";
            }
        } else {
            return redirect()->route('qc.work.login.get');
        }

    }

    # xac nhan don hang
    public function getProductConfirm($productId = null)
    {
        $modelProduct = new QcProduct();
        $dataProduct = $modelProduct->getInfo($productId);
        if (count($dataProduct) > 0) {
            return view('work.orders.orders.product.confirm-finish', compact('dataProduct'));
        }
    }

    public function postProductConfirm($productId = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelProduct = new QcProduct();
        if ($modelStaff->checkLogin()) {
            $modelProduct->confirmFinish($modelStaff->loginStaffId(), $hFunction->carbonNow(), $productId);
        }
    }

    #----- ------ huy san pham ----- ----
    public function getProductCancel($productId = null)
    {
        $modelProduct = new QcProduct();
        $dataProduct = $modelProduct->getInfo($productId);
        if (count($dataProduct) > 0) {
            return view('work.orders.orders.product.cancel', compact('dataProduct'));
        }
    }

    public function postProductCancel($productId)
    {
        $modelStaff = new QcStaff();
        $modelProduct = new QcProduct();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $txtReason = Request::input('txtReason');
        if (count($dataStaffLogin) > 0) {
            $dataOrder = $modelProduct->getInfo($productId);
            if (count($dataOrder) > 0) {
                $modelProduct->cancelProduct($productId, $txtReason, $dataStaffLogin->staffId());
            }
        } else {
            return view('work.login');
        }
    }

    #---- ---  THIET KE SAN PHAM ---- -----
    #chi tiet thiet ke
    public function getDesign($productId)
    {
        $modelStaff = new QcStaff();
        $modelProduct = new QcProduct();
        $dataProduct = $modelProduct->getInfo($productId);
        return view('work.orders.orders.product.design', compact('modelStaff','dataProduct'));
    }

    #them anh thiet ke
    public function getProductDesign($productId = null)
    {
        $modelProduct = new QcProduct();
        $dataProduct = $modelProduct->getInfo($productId);
        if (count($dataProduct) > 0) {
            return view('work.orders.orders.product.add-design', compact('dataProduct'));
        }
    }

    public function postProductDesign($productId = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelProduct = new QcProduct();
        $modelProductDesign = new QcProductDesign();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $txtDesignImage = Request::file('txtDesignImage');
        if (count($dataStaffLogin) > 0) {
            $loginStaffId = $dataStaffLogin->staffId();
            $dataProduct = $modelProduct->getInfo($productId);
            if (count($txtDesignImage) > 0) {
                $name_img = stripslashes($_FILES['txtDesignImage']['name']);
                $name_img = $hFunction->getTimeCode() . '.' . $hFunction->getTypeImg($name_img);
                $source_img = $_FILES['txtDesignImage']['tmp_name'];
                if ($modelProductDesign->uploadImage($source_img, $name_img)) {
                    if ($modelProductDesign->insert($name_img, null, $productId, $dataStaffLogin->staffId())) {
                        $newId = $modelProductDesign->insertGetId();
                        # nguoi quan ly don hang up thiet ke ===> Ap dụng
                        if ($loginStaffId == $dataProduct->order->staffReceiveId()) {
                            $modelProductDesign->confirmApplyStatus($newId, 1, 1, $dataStaffLogin->staffId());
                        }

                    } else {
                        $modelProduct->dropDesignImage($name_img);
                        return "Tính năng đang cập nhật";
                    }
                }
            } else {
                return "Chọn ảnh thiết kế";
            }
        } else {
            return redirect()->route('qc.work.login.get');
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
        if (count($dataStaffLogin) > 0) {
            $modelProductDesign->confirmApplyStatus($designId, $applyStatus, 1, $dataStaffLogin->staffId());
        } else {
            return redirect()->route('qc.work.login.get');
        }
    }

    #xem chi tiet hinh anh thiet ke
    public function viewProductDesign($designId)
    {
        $modelProductDesign = new QcProductDesign();
        $dataProductDesign = $modelProductDesign->getInfo($designId);
        if (count($dataProductDesign) > 0) {
            return view('work.orders.orders.product.view-design-image', compact('dataProductDesign'));
        }
    }

}
