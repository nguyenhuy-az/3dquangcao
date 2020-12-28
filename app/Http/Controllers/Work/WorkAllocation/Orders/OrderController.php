<?php

namespace App\Http\Controllers\Work\WorkAllocation\Orders;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\Customer\QcCustomer;
use App\Models\Ad3d\Order\QcOrder;
use App\Models\Ad3d\OrderAllocation\QcOrderAllocation;
use App\Models\Ad3d\Product\QcProduct;
use App\Models\Ad3d\ProductDesign\QcProductDesign;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\StaffNotify\QcStaffNotify;
use App\Models\Ad3d\TimekeepingProvisionalImage\QcTimekeepingProvisionalImage;
use App\Models\Ad3d\WorkAllocation\QcWorkAllocation;
use App\Models\Ad3d\WorkAllocationReportImage\QcWorkAllocationReportImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    public function index($dayFilter = 0, $monthFilter = 0, $yearFilter = 0, $orderFilterName = null, $orderCustomerFilterName = null, $finishStatus = 100)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelStaffNotify = new QcStaffNotify();
        $modelCustomer = new QcCustomer();
        $modelCompany = new QcCompany();
        $modelOrder = new QcOrder();
        $dataAccess = [
            'object' => 'workAllocationOrder'
        ];
        //$allocationFinishStatus - 100 - tat ca / 0 - da thi cong xong / 1 - chua xong
        $currentMonth = $hFunction->currentMonth();
        $currentYear = $hFunction->currentYear();
        $orderFilterName = ($orderFilterName == 'null') ? null : $orderFilterName;
        $orderCustomerFilterName = ($orderCustomerFilterName == 'null') ? null : $orderCustomerFilterName;
        # thong tin dang nhap
        $dataCompanyLogin = $modelStaff->companyLogin();
        $companyLoginId = $dataCompanyLogin->companyId();


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
            $yearFilter = $currentYear;
            $dateFilter = date('Y-m-d', strtotime("$dayFilter-$monthFilter-$currentYear"));
        } elseif ($dayFilter == 100 && $monthFilter == 100 && $yearFilter == 100) { //xem tất cả
            $dateFilter = null;
        } else {
            $dateFilter = date('Y-m');
            $dayFilter = 100;
            $monthFilter = date('m');
            $yearFilter = date('Y');
        }

        if (!empty($orderCustomerFilterName)) {
            $dataOrderSelect = $modelOrder->selectInfoOfListCustomerOfCompany($companyLoginId, $modelCustomer->listIdByKeywordName($orderCustomerFilterName), $dateFilter, 2);
        } else {
            $dataOrderSelect = $modelOrder->selectInfoManageConstructionOfCompany($companyLoginId, $orderFilterName, $dateFilter, $finishStatus);
        }
        $dataOrder = $dataOrderSelect->paginate(50);
        return view('work.work-allocation.orders.list', compact('modelStaff', 'dataAccess', 'dataOrder', 'dayFilter', 'monthFilter', 'yearFilter', 'orderFilterName', 'orderCustomerFilterName', 'finishStatus'));

    }

    // theo ten don hang
    public function filterCheckOrderName($name)
    {
        $hFunction = new \Hfunction();
        $modelOrder = new QcOrder();
        $dataOrder = $modelOrder->infoNoCancelFromSuggestionName($name);
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

    # xem thong tin don hang
    public function viewOrder($orderId, $notifyId = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelStaffNotify = new QcStaffNotify();
        $modelOrders = new QcOrder();
        $dataAccess = [
            'object' => 'workAllocationOrder'
        ];
        # cap nhat da xem thong bao
        if (!$hFunction->checkEmpty($notifyId)) $modelStaffNotify->updateViewed($notifyId);
        #lay thong tin don hang
        $dataOrder = $modelOrders->getInfo($orderId);
        if ($hFunction->checkCount($dataOrder)) {
            return view('work.work-allocation.orders.view', compact('modelStaff', 'dataAccess', 'dataOrder'));
        } else {
            return redirect()->back();
        }
    }

    # in don hang
    public function printOrder($orderId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelOrders = new QcOrder();
        $dataAccess = [
            'object' => 'workAllocationOrder'
        ];
        $dataOrder = $modelOrders->getInfo($orderId);
        if ($hFunction->checkCount($dataOrder)) {
            return view('work.work-allocation.orders.print', compact('modelStaff', 'dataAccess', 'dataOrder'));
        } else {
            return redirect()->back();
        }
    }

    # in don hang
    public function printConfirmOrder($orderId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelOrders = new QcOrder();
        $dataAccess = [
            'object' => 'workAllocationOrder'
        ];
        $dataOrder = $modelOrders->getInfo($orderId);
        if ($hFunction->checkCount($dataOrder)) {
            return view('work.work-allocation.orders.print-confirm', compact('modelStaff', 'dataAccess', 'dataOrder'));
        } else {
            return redirect()->back();
        }
    }

    # ban giao don hang - cong trinh
    public function getOrderConstruction($orderId, $notifyId = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelStaffNotify = new QcStaffNotify();
        $modelOrders = new QcOrder();
        $dataAccess = [
            'object' => 'workAllocationOrder',
            'subObjectLabel' => 'Bàn giao đơn hàng'
        ];
        $dataOrder = $modelOrders->getInfo($orderId);
        //Lay danh sach nv thuoc bo phan thi cong cua cty
        $dataReceiveStaff = $modelStaff->infoActivityConstructionOfCompany($dataOrder->companyId());
        if ($hFunction->checkCount($dataOrder)) {
            # cap nhat da xem thong bao
            if (!$hFunction->checkEmpty($notifyId)) $modelStaffNotify->updateViewed($notifyId);
            return view('work.work-allocation.orders.construction', compact('modelStaff', 'dataAccess', 'dataReceiveStaff', 'dataOrder'));
        } else {
            return redirect()->back();
        }
    }

    public function postOrderConstruction(Request $request, $orderId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelStaffNotify = new QcStaffNotify();
        $modelOrdersAllocation = new QcOrderAllocation();
        $cbReceiveStaffId = $request->input('cbReceiveStaff');
        # ngay giao
        $cbAllocationDay = $request->input('cbAllocationDay');
        $cbAllocationMonth = $request->input('cbAllocationMonth');
        $cbAllocationYear = $request->input('cbAllocationYear');
        $cbAllocationHours = $request->input('cbAllocationHours');
        $cbAllocationMinute = $request->input('cbAllocationMinute');
        # ngay het han
        $cbDeadlineDay = $request->input('cbDeadlineDay');
        $cbDeadlineMonth = $request->input('cbDeadlineMonth');
        $cbDeadlineYear = $request->input('cbDeadlineYear');
        $cbDeadlineHours = $request->input('cbDeadlineHours');
        $cbDeadlineMinute = $request->input('cbDeadlineMinute');
        $allocationDate = $hFunction->convertStringToDatetime("$cbAllocationMonth/$cbAllocationDay/$cbAllocationYear $cbAllocationHours:$cbAllocationMinute:00");
        $deadlineDate = $hFunction->convertStringToDatetime("$cbDeadlineMonth/$cbDeadlineDay/$cbDeadlineYear $cbDeadlineHours:$cbDeadlineMinute:00");
        if (empty($cbReceiveStaffId)) {
            Session::put('notifyAdd', "Chọn nhân viên bàn giao");
        } else {
            if ($deadlineDate > $allocationDate) {
                # kiem tra nv co duoc ban giao cong trinh nay chua
                if (!$modelOrdersAllocation->checkStaffReceiveOrder($cbReceiveStaffId, $orderId)) {
                    if ($modelOrdersAllocation->insert($allocationDate, 0, $deadlineDate, '', $orderId, $cbReceiveStaffId, $modelStaff->loginStaffId())) {
                        $newOrderAllocationId = $modelOrdersAllocation->insertGetId();
                        $modelStaffNotify->insert(null, $cbReceiveStaffId, 'Giao phụ trách đơn hàng', $newOrderAllocationId, null);
                    }
                } else {
                    Session::put('notifyAdd', "Nhân viện không được nhận công trình 2 lần");
                }
            } else {
                Session::put('notifyAdd', "Ngày giao phải lớn hơn ngày nhận");
            }
        }
        return redirect()->back();
    }

    # --------- XAC NHAN hoan thanh don hang ban giao thi cong ---------
    public function getConfirmFinishConstruction($allocationId)
    {
        $hFunction = new \Hfunction();
        $modelOrderAllocation = new QcOrderAllocation();
        $dataOrderAllocation = $modelOrderAllocation->getInfo($allocationId);
        if ($hFunction->checkCount($dataOrderAllocation)) {
            return view('work.work-allocation.orders.confirm-finish', compact('dataOrderAllocation'));
        }
    }

    public function postConfirmFinishConstruction(Request $request, $allocationId)
    {
        $modelStaff = new QcStaff();
        $modelOrderAllocation = new QcOrderAllocation();
        $confirmFinishStatus = $request->input('cbConfirmFinishStatus');
        $confirmNote = $request->input('txtConfirmNote');
        $modelOrderAllocation->confirmFinishAllocation($allocationId, $confirmFinishStatus, $modelStaff->staffId(), $confirmNote);

    }

    #--------- huy ban giao thi cong ---------
    public function deleteOrderConstruction($allocationId)
    {
        $modelOrderAllocation = new QcOrderAllocation();
        if ($modelOrderAllocation->cancel($allocationId)) {

        }
    }

    # ========= ======== phan cong tren san pham ========= =======
    // phan cong lam san pham
    public function getAddWorkAllocation($productId)
    {
        $modelStaff = new QcStaff();
        $modelProduct = new QcProduct();
        $dataAccess = [
            'object' => 'workAllocationOrder',
            'subObjectLabel' => 'Triển khai thi công'
        ];
        $dataProduct = $modelProduct->getInfo($productId);
        $dataReceiveStaff = $modelStaff->infoActivityConstructionOfCompany($dataProduct->order->companyId()); # lay NV so hua don hang
        return view('work.work-allocation.orders.product.work-allocation', compact('modelStaff', 'dataAccess', 'dataProduct', 'dataReceiveStaff'));
    }

    # thêm nv làm sản phẩm
    public function getAddStaff($productId)
    {
        $modelStaff = new QcStaff();
        $modelProduct = new QcProduct();
        $dataProduct = $modelProduct->getInfo($productId);
        $dataReceiveStaff = $modelStaff->infoActivityConstructionOfCompany($dataProduct->order->companyId()); # lay NV so hua don hang
        return view('work.work-allocation.orders.product.work-allocation-staff', compact('modelStaff', 'dataReceiveStaff', 'dataProduct'));
    }

    public function postAddWorkAllocation(Request $request, $productId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelProduct = new QcProduct();
        $modelWorkAllocation = new QcWorkAllocation();

        $loginStaffId = $modelStaff->loginStaffId();
        $errorContent = "Thông tin nhập bị lỗi: <br/> ";
        $dayAllocation = $request->input('cbDayAllocation');
        $monthAllocation = $request->input('cbMonthAllocation');
        $yearAllocation = $request->input('cbYearAllocation');
        $hoursAllocation = $request->input('cbHoursAllocation');
        $minuteAllocation = $request->input('cbMinuteAllocation');
        $dayDeadline = $request->input('cbDayDeadline');
        $monthDeadline = $request->input('cbMonthDeadline');
        $yearDeadline = $request->input('cbYearDeadline');
        $hoursDeadline = $request->input('cbHoursDeadline');
        $minuteDeadline = $request->input('cbMinuteDeadline');
        # thong tin nhan vien nhan
        $staffReceive = $request->input('staffReceive');
        $allocationDate = $hFunction->convertStringToDatetime("$monthAllocation/$dayAllocation/$yearAllocation $hoursAllocation:$minuteAllocation:00");
        $deadlineDate = $hFunction->convertStringToDatetime("$monthDeadline/$dayDeadline/$yearDeadline $hoursDeadline:$minuteDeadline:00");
        $errorStatus = false;
        if ($deadlineDate <= $allocationDate) {
            $errorContent = $errorContent . "- Thời gian kết thúc phải lớn hơn thời gian nhận <br/>";
            $errorStatus = true;
        } else {
            if ($hFunction->checkCount($staffReceive)) { # co chon nguoi phan viec
                foreach ($staffReceive as $key => $receiveStaffId) {
                    $role = $request->input('cbRole_' . $receiveStaffId);
                    $txtDescription = $request->input('txtDescription_' . $receiveStaffId);
                    # chua duoc phan cong
                    if (!$modelProduct->checkStaffReceiveProduct($receiveStaffId, $productId)) {
                        #them giao viec
                        $modelProduct->allocationForStaff($productId, $receiveStaffId, $allocationDate, $deadlineDate, $txtDescription, $loginStaffId, $role, $modelWorkAllocation->getDefaultFirstConstructionNumber(), $modelWorkAllocation->getDefaultProductRepairId());
                    }
                }
            } else {
                $errorContent = $errorContent . "- Không có thông tin người nhận việc <br/>";
                $errorStatus = true;
            }
        }
        if ($errorStatus) {
            Session::put('notifyAddAllocation', $errorContent);
        }
        return redirect()->back();
    }

    # huy phan viec tren san pham
    public function cancelWorkAllocationProduct($allocationId = null)
    {
        $hFunction = new \Hfunction();
        $modelWorkAllocation = new QcWorkAllocation();
        if (!$hFunction->checkEmpty($allocationId)) {
            $modelWorkAllocation->cancelAllocation($allocationId);
        }
    }

    # chi tiet thi cong
    public function viewWorkAllocation($allocationId)
    {
        $hFunction = new \Hfunction();
        $modelWorkAllocation = new QcWorkAllocation();
        $dataWorkAllocation = $modelWorkAllocation->getInfo($allocationId);
        if ($hFunction->checkCount($dataWorkAllocation)) {
            return view('work.work-allocation.orders.product.view-work-allocation', compact('dataWorkAllocation'));
        }
    }

    #xem chi tiet hinh anh thiet ke
    public function viewProductDesignImage($designId)
    {
        $hFunction = new \Hfunction();
        $modelProductDesign = new QcProductDesign();
        $dataProductDesign = $modelProductDesign->getInfo($designId);
        if ($hFunction->checkCount($dataProductDesign)) {
            return view('work.work-allocation.orders.product.view-design-image', compact('dataProductDesign'));
        }
    }

    #xem anh bao cao truc tiep
    public function viewReportImageDirect($imageId)
    {
        $modelWorkAllocationReportImage = new QcWorkAllocationReportImage();
        $dataWorkAllocationReportImage = $modelWorkAllocationReportImage->getInfo($imageId);
        return view('work.work-allocation.orders.product.view-report-image-direct', compact('dataWorkAllocationReportImage'));
    }

    # xem anh bao cao qua cham cong
    public function viewReportImageTimekeeping($imageId)
    {
        $modelTimekeepingProvisionalImage = new QcTimekeepingProvisionalImage();
        $dataTimekeepingProvisionalImage = $modelTimekeepingProvisionalImage->getInfo($imageId);
        return view('work.work-allocation.orders.product.view-report-image', compact('dataTimekeepingProvisionalImage'));
    }
}
