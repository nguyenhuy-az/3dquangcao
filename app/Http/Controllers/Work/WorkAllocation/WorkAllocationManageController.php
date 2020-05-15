<?php

namespace App\Http\Controllers\Work\WorkAllocation;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\Customer\QcCustomer;
use App\Models\Ad3d\Order\QcOrder;
use App\Models\Ad3d\OrderAllocation\QcOrderAllocation;
use App\Models\Ad3d\Product\QcProduct;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\StaffNotify\QcStaffNotify;
use App\Models\Ad3d\WorkAllocation\QcWorkAllocation;
use App\Models\Ad3d\WorkAllocationReportImage\QcWorkAllocationReportImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class WorkAllocationManageController extends Controller
{
    public function index($dayFilter = 0, $monthFilter = 0, $yearFilter = 0, $orderFilterName = null, $orderCustomerFilterName = null, $staffFilterId = 0)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelStaffNotify = new QcStaffNotify();
        $modelCustomer = new QcCustomer();
        $modelCompany = new QcCompany();
        $modelOrder = new QcOrder();
        $dataAccess = [
            'object' => 'workAllocationManage'
        ];
        $currentMonth = $hFunction->currentMonth();
        $currentYear = $hFunction->currentYear();
        $orderFilterName = ($orderFilterName == 'null') ? null : $orderFilterName;
        $orderCustomerFilterName = ($orderCustomerFilterName == 'null') ? null : $orderCustomerFilterName;
        //$staffFilterName = ($staffFilterId == 'null') ? null : $staffFilterName;
        $dataStaffLogin = $modelStaff->loginStaffInfo();

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
        $searchCompanyFilterId = [$dataStaffLogin->companyId()];
        if ($staffFilterId > 0) {
            $listStaffId = [$staffFilterId];
        } else {
            $listStaffId = $modelStaff->listIdOfListCompany($searchCompanyFilterId);
        }

        if (!empty($orderCustomerFilterName)) {
            $dataOrderSelect = $modelOrder->selectInfoOfListCustomer($modelCustomer->listIdByKeywordName($orderCustomerFilterName), $dateFilter, 2);
        } else {

            $dataOrderSelect = $modelOrder->selectInfoByListStaffAndNameAndDateAndPayment($listStaffId, $orderFilterName, $dateFilter, 2);
        }

        $dataOrder = $dataOrderSelect->paginate(50);
        //danh sach NV
        $dataStaff = $modelCompany->staffInfoActivityOfListCompanyId([$searchCompanyFilterId]);
        return view('work.work-allocation.orders.index', compact('modelStaff', 'dataStaff', 'dataAccess', 'dataOrder', 'dayFilter', 'monthFilter', 'yearFilter', 'orderFilterName', 'orderCustomerFilterName', 'staffFilterId'));

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
    public function viewOrder($orderId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelStaffNotify = new QcStaffNotify();
        $modelOrders = new QcOrder();
        $dataAccess = [
            'object' => 'workAllocationManage'
        ];
        # cap nhat da xem thong bao
        $modelStaffNotify->updateViewedOfStaffAndOrder($modelStaff->loginStaffId(), $orderId);
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
            'object' => 'workAllocationManage'
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
            'object' => 'workAllocationManage'
        ];
        $dataOrder = $modelOrders->getInfo($orderId);
        if ($hFunction->checkCount($dataOrder)) {
            return view('work.work-allocation.orders.print-confirm', compact('modelStaff', 'dataAccess', 'dataOrder'));
        } else {
            return redirect()->back();
        }
    }

    # ban giao don hang - cong trinh
    public function getOrderConstruction($orderId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelStaffNotify = new QcStaffNotify();
        $modelOrders = new QcOrder();
        $dataAccess = [
            'object' => 'workAllocationManage',
            'subObjectLabel' => 'Bàn giao đơn hàng'
        ];
        $dataOrder = $modelOrders->getInfo($orderId);
        $dataReceiveStaff = $modelStaff->infoActivityConstructionOfCompany($dataOrder->companyId()); //Lay NV cty so huu don hang
        if ($hFunction->checkCount($dataOrder)) {
            # cap nhat da xem thong bao
            $modelStaffNotify->updateViewedOfStaffAndOrder($modelStaff->loginStaffId(), $orderId);
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
        $dateAllocation = $hFunction->convertStringToDatetime("$cbAllocationMonth/$cbAllocationDay/$cbAllocationYear $cbAllocationHours:$cbAllocationMinute:00");
        $dateDeadline = $hFunction->convertStringToDatetime("$cbDeadlineMonth/$cbDeadlineDay/$cbDeadlineYear $cbDeadlineHours:$cbDeadlineMinute:00");
        if (empty($cbReceiveStaffId)) {
            Session::put('notifyAdd', "Chọn nhân viên bàn giao");
        } else {
            if ($dateDeadline > $dateAllocation) {
                # kiem tra nv co duoc ban giao cong trinh nay chua
                if (!$modelOrdersAllocation->checkStaffReceiveOrder($cbReceiveStaffId, $orderId)) {
                    if ($modelOrdersAllocation->insert($dateAllocation, 0, $dateDeadline, '', $orderId, $cbReceiveStaffId, $modelStaff->loginStaffId())) {
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

    #huy ban giao thi cong
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
            'object' => 'workAllocationManage',
            'subObjectLabel' => 'Triển khai thi công'
        ];
        $dataProduct = $modelProduct->getInfo($productId);
        $dataReceiveStaff = $modelStaff->infoActivityConstructionOfCompany($dataProduct->order->companyId()); # lay NV so hua don hang
        return view('work.work-allocation.orders.product.work-allocation', compact('modelStaff', 'dataAccess', 'dataProduct', 'dataReceiveStaff'));
    }

    public function getAddStaff($productId) # thêm nv làm sản phẩm
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
        $modelStaffNotify = new QcStaffNotify();
        $modelProduct = new QcProduct();
        $modelWorkAllocation = new QcWorkAllocation();

        $loginStaffId = $modelStaff->loginStaffId();
        # thong tin nhan vien nhan
        $cbReceiveStaff = $request->input('cbReceiveStaff');

        # thong tin phan viec
        $cbRole = $request->input('cbRole');
        $cbDayAllocation = $request->input('cbDayAllocation');
        $cbMonthAllocation = $request->input('cbMonthAllocation');
        $cbYearAllocation = $request->input('cbYearAllocation');
        $cbHoursAllocation = $request->input('cbHoursAllocation');
        $cbMinuteAllocation = $request->input('cbMinuteAllocation');

        $cbDayDeadline = $request->input('cbDayDeadline');
        $cbMonthDeadline = $request->input('cbMonthDeadline');
        $cbYearDeadline = $request->input('cbYearDeadline');
        $cbHoursDeadline = $request->input('cbHoursDeadline');
        $cbMinuteDeadline = $request->input('cbMinuteDeadline');
        $txtDescription = $request->input('txtDescription');

        $errorStatus = true;
        $errorContent = "Thông tin nhập bị lỗi: ";
        # co chon nv
        if ($hFunction->checkCount($cbReceiveStaff)) {
            foreach ($cbReceiveStaff as $key => $value) {
                $dateAllocation = $hFunction->convertStringToDatetime("$cbMonthAllocation[$key]/$cbDayAllocation[$key]/$cbYearAllocation[$key] $cbHoursAllocation[$key]:$cbMinuteAllocation[$key]:00");
                $dateDeadline = $hFunction->convertStringToDatetime("$cbMonthDeadline[$key]/$cbDayDeadline[$key]/$cbYearDeadline[$key] $cbHoursDeadline[$key]:$cbMinuteDeadline[$key]:00");
                if ($dateDeadline < $dateAllocation) {
                    $errorContent = $errorContent . "Thời gian kết thúc phải lớn hơn thời gian nhận <br/>";
                    $errorStatus = false;
                }
            }
        }

        //echo $errorContent;
        if ($errorStatus) {
            foreach ($cbReceiveStaff as $key => $value) {
                $receiveStaffId = $value;
                $dayAllocation = $cbDayAllocation[$key];
                $monthAllocation = $cbMonthAllocation[$key];
                $yearAllocation = $cbYearAllocation[$key];
                $hoursAllocation = $cbHoursAllocation[$key];
                $minuteAllocation = $cbMinuteAllocation[$key];
                $dayDeadline = $cbDayDeadline[$key];
                $monthDeadline = $cbMonthDeadline[$key];
                $yearDeadline = $cbYearDeadline[$key];
                $hoursDeadline = $cbHoursDeadline[$key];
                $minuteDeadline = $cbMinuteDeadline[$key];
                $role = $cbRole[$key];
                $description = $txtDescription[$key];
                $dateAllocation = $hFunction->convertStringToDatetime("$monthAllocation/$dayAllocation/$yearAllocation $hoursAllocation:$minuteAllocation:00");
                $dateDeadline = $hFunction->convertStringToDatetime("$monthDeadline/$dayDeadline/$yearDeadline $hoursDeadline:$minuteDeadline:00");
                if ($dateDeadline > $dateAllocation) {
                    # chua duoc phan cong
                    if (!$modelProduct->checkStaffReceiveProduct($receiveStaffId, $productId)) {
                        #them giao viec
                        if ($modelWorkAllocation->insert($dateAllocation, 0, $dateDeadline, 1, $hFunction->carbonNow(), $description, $productId, $loginStaffId, $receiveStaffId, $role)) {
                            $newWorkAllocationId = $modelWorkAllocation->insertGetId();
                            $modelStaffNotify->insert(null, $receiveStaffId, 'Giao thi công sản phẩm', null, $newWorkAllocationId);
                        }
                    }
                }
            }
        } else {
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

    #xem anh ban cao thi cong san pham
    public function viewReportImage($imageId)
    {
        $hFunction = new \Hfunction();
        $modelWorkAllocationReportImage = new QcWorkAllocationReportImage();
        $dataWorkAllocationReportImage = $modelWorkAllocationReportImage->getInfo($imageId);
        if ($hFunction->checkCount($dataWorkAllocationReportImage)) {
            //return view('ad3d.order.order.view-construction-report-image', compact('dataWorkAllocationReportImage'));
        }
    }
}
