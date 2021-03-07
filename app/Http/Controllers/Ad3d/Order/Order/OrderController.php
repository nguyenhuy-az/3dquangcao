<?php

namespace App\Http\Controllers\Ad3d\Order\Order;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\Customer\QcCustomer;
use App\Models\Ad3d\Order\QcOrder;
use App\Models\Ad3d\OrderAllocation\QcOrderAllocation;
use App\Models\Ad3d\ProductDesign\QcProductDesign;
use App\Models\Ad3d\Staff\QcStaff;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ad3d\TimekeepingProvisionalImage\QcTimekeepingProvisionalImage;
use App\Models\Ad3d\WorkAllocation\QcWorkAllocation;
use App\Models\Ad3d\WorkAllocationReportImage\QcWorkAllocationReportImage;
use File;
use Illuminate\Support\Facades\Session;
use Input;
use Request;

class OrderController extends Controller
{
    public function index($companyFilterId = null, $dayFilter = 0, $monthFilter = 0, $yearFilter = 0, $paymentStatus = 3, $orderFilterName = null, $orderCustomerFilterName = null, $staffFilterId = 0, $orderSelectedId = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCustomer = new QcCustomer();
        $modelCompany = new QcCompany();
        $modelOrder = new QcOrder();
        $currentMonth = $hFunction->currentMonth();
        $currentYear = $hFunction->currentYear();
        $dayFilter = (int)$dayFilter;
        /*$monthFilter = (int)$monthFilter;
        $yearFilter = (int)$yearFilter;*/
        $orderFilterName = ($orderFilterName == 'null') ? null : $orderFilterName;
        $companyFilterId = ($companyFilterId == 'null') ? null : $companyFilterId;
        $orderCustomerFilterName = ($orderCustomerFilterName == 'null') ? null : $orderCustomerFilterName;

        $dataCompanyLogin = $modelStaff->companyLogin();
        $companyLoginId = $dataCompanyLogin->companyId();
        $dataAccess = [
            'accessObject' => 'order'
        ];
        $dateFilter = null;
        # don hang duoc chon
        if ($hFunction->checkEmpty($orderSelectedId)) {
            $dataOrderSelected = $hFunction->getDefaultNull();
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
        } else {
            $dataOrderSelected = $modelOrder->getInfo($orderSelectedId);
            # uu tien theo thang nam cua don hang dc chon
            $orderReceiveDate = $dataOrderSelected->receiveDate();
            $monthFilter = (int)$hFunction->getMonthFromDate($orderReceiveDate);
            $yearFilter = (int)$hFunction->getYearFromDate($orderReceiveDate);
            $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
        }

        # lay thong tin cong ty cung he thong
        $dataCompany = $modelCompany->getInfoSameSystemOfCompany($companyLoginId);
        if ($hFunction->checkEmpty($companyFilterId) || $companyFilterId == 1000) $companyFilterId = $companyLoginId;
        if ($staffFilterId > 0) {
            $listStaffId = [$staffFilterId];
        } else {
            $listStaffId = $modelStaff->listIdOfCompany($companyFilterId);
        }

        if (!$hFunction->checkEmpty($orderCustomerFilterName)) {
            $dataOrderFilterStatistic = $modelOrder->selectInfoOfListCustomer($modelCustomer->listIdByKeywordName($orderCustomerFilterName), $dateFilter, $paymentStatus)->get();
        } else {
            $dataOrderFilterStatistic = $modelOrder->selectInfoByListStaffAndNameAndDateAndPayment($listStaffId, $orderFilterName, $dateFilter, $paymentStatus)->get();
            //dd($paymentStatus);
        }

        //$dataMoneyOrder = $dataOrderFilterStatistic->get();

        #$dataOrder = $dataOrderFilterStatistic->paginate(30);

        $totalMoneyOrder = $modelOrder->totalMoneyOfListOrder($dataOrderFilterStatistic); // tong tien
        $totalMoneyDiscountOrder = $modelOrder->totalMoneyDiscountOfListOrder($dataOrderFilterStatistic); // tong tien giam
        $totalMoneyPaidOrder = $modelOrder->totalMoneyPaidOfListOrder($dataOrderFilterStatistic); // tong tien da thanh toan
        $totalMoneyUnPaidOrder = $modelOrder->totalMoneyUnPaidOfListOrder($dataOrderFilterStatistic); // chua thanh toán
        $totalOrders = $hFunction->getCount($dataOrderFilterStatistic);

        //danh sach NV
        $dataStaff = $modelCompany->staffInfoActivityOfCompanyId([$companyFilterId]);
        return view('ad3d.order.order.list', compact('modelStaff', 'dataCompany','modelCustomer', 'modelOrder', 'dataStaff', 'dataAccess', 'totalOrders', 'totalMoneyOrder', 'totalMoneyDiscountOrder', 'totalMoneyPaidOrder', 'totalMoneyUnPaidOrder', 'companyFilterId', 'dayFilter', 'monthFilter', 'yearFilter', 'paymentStatus', 'orderFilterName', 'orderCustomerFilterName', 'staffFilterId', 'dataOrderSelected'));

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
    public function viewProductDesign($designId)
    {
        $hFunction = new \Hfunction();
        $modelProductDesign = new QcProductDesign();
        $dataProductDesign = $modelProductDesign->getInfo($designId);
        if ($hFunction->checkCount($dataProductDesign)) {
            return view('ad3d.order.order.view-product-design-image', compact('dataProductDesign'));
        }

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

    # xem anh bao cao
    public function viewWorkAllocationReportImage($imageId)
    {
        $modelImage = new QcWorkAllocationReportImage();
        $dataWorkAllocationReportImage = $modelImage->getInfo($imageId);
        return view('ad3d.order.order.view-report-image-direct', compact('dataWorkAllocationReportImage'));
    }

    public function viewWorkAllocationReportTimekeepingImage($imageId)
    {
        $modelImage = new QcTimekeepingProvisionalImage();
        $dataWorkAllocationReportTimekeepingImage = $modelImage->getInfo($imageId);
        return view('ad3d.order.order.view-report-timekeeping-image', compact('dataWorkAllocationReportTimekeepingImage'));
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

    #theo ten khach hang
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


}
