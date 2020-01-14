<?php

namespace App\Http\Controllers\Ad3d\Order\Product;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\Order\QcOrder;
use App\Models\Ad3d\Product\QcProduct;
use App\Models\Ad3d\Staff\QcStaff;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ad3d\WorkAllocation\QcWorkAllocation;
use Illuminate\Support\Facades\Session;
use File;
use Request;
use Input;

class ProductController extends Controller
{
    public function index($companyFilterId = null, $dayFilter = 0, $monthFilter = 0, $yearFilter = 0, $finishStatus = 2, $keywordFiler = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelOrder = new QcOrder();
        $dataStaffLogin = $modelStaff->loginStaffInfo();

        $dataAccess = [
            'accessObject' => 'product'
        ];

        $currentMonth = $hFunction->currentMonth();
        $currentYear = $hFunction->currentYear();
        $dateFilter = null;
        if ($yearFilter == 100) { # lay tat ca thong tin
            $dayFilter = null;
            $dayFilter = 100;
            $monthFilter = 100;
        } elseif ($dayFilter == 0 && $monthFilter == 0 && $yearFilter == 0) { //xem  trong tháng
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
        if (empty($companyFilterId)) {
            $companyFilterId = $dataStaffLogin->companyId();
        }
        $searchCompanyFilterId = [$companyFilterId];
        $dataCompany = $modelCompany->getInfo($companyFilterId);

        $listOrderId = $modelOrder->listIdOfListCompanyAndReceiveDateName($searchCompanyFilterId, $dateFilter, $keywordFiler);
        if ($finishStatus == 2) {
            $dataProduct = QcProduct::whereIn('order_id', $listOrderId)->orderBy('product_id', 'DESC')->select('*')->paginate(30);
            $totalPriceProduct = QcProduct::whereIn('order_id', $listOrderId)->sum('price');
            $totalProduct = QcProduct::whereIn('order_id', $listOrderId)->count();

        } else {
            $dataProduct = QcProduct::whereIn('order_id', $listOrderId)->where('finishStatus', $finishStatus)->orderBy('product_id', 'DESC')->select('*')->paginate(30);
            $totalPriceProduct = QcProduct::whereIn('order_id', $listOrderId)->where('finishStatus', $finishStatus)->sum('price');
            $totalProduct = QcProduct::whereIn('order_id', $listOrderId)->where('finishStatus', $finishStatus)->count();
        }
        return view('ad3d.order.product.list', compact('modelStaff', 'dataCompany', 'dataAccess', 'dataProduct', 'totalPriceProduct', 'totalProduct', 'companyFilterId', 'dayFilter', 'monthFilter', 'yearFilter', 'finishStatus', 'keywordFiler'));

    }

    public function view($productId)
    {
        $modelProduct = new QcProduct();
        $dataProduct = $modelProduct->getInfo($productId);
        if (count($dataProduct) > 0) {
            return view('ad3d.order.product.view', compact('dataProduct'));
        }
    }

    // phan cong lam san pham
    public function getAddWorkAllocation($productId)
    {
        $modelStaff = new QcStaff();
        $modelProduct = new QcProduct();
        $dataAccess = [
            'accessObject' => 'product'
        ];
        $dataProduct = $modelProduct->getInfo($productId);
        $dataReceiveStaff = $modelStaff->infoActivityOfCompany($dataProduct->order->companyId()); # lay NV so hua don hang
        return view('ad3d.order.product.work-allocation', compact('modelStaff', 'dataAccess', 'dataProduct', 'dataReceiveStaff'));
    }

    public function getAddStaff($productId) # thêm nv làm sản phẩm
    {
        $modelStaff = new QcStaff();
        $modelProduct = new QcProduct();
        $dataProduct = $modelProduct->getInfo($productId);
        $dataReceiveStaff = $modelStaff->infoActivityOfCompany($dataProduct->order->companyId()); # lay NV so hua don hang
        return view('ad3d.order.product.work-allocation-staff', compact('modelStaff', 'dataReceiveStaff', 'dataProduct'));
    }

    public function postAddWorkAllocation($productId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelProduct = new QcProduct();
        $modelWorkAllocation = new QcWorkAllocation();

        $loginStaffId = $modelStaff->loginStaffId();
        # thong tin nhan vien nhan
        $cbReceiveStaff = Request::input('cbReceiveStaff');

        # thong tin phan viec
        $cbRole = Request::input('cbRole');
        $cbDayAllocation = Request::input('cbDayAllocation');
        $cbMonthAllocation = Request::input('cbMonthAllocation');
        $cbYearAllocation = Request::input('cbYearAllocation');
        $cbHoursAllocation = Request::input('cbHoursAllocation');
        $cbMinuteAllocation = Request::input('cbMinuteAllocation');

        $cbDayDeadline = Request::input('cbDayDeadline');
        $cbMonthDeadline = Request::input('cbMonthDeadline');
        $cbYearDeadline = Request::input('cbYearDeadline');
        $cbHoursDeadline = Request::input('cbHoursDeadline');
        $cbMinuteDeadline = Request::input('cbMinuteDeadline');
        $txtDescription = Request::input('txtDescription');

        $errorStatus = true;
        $errorContent = "Thông tin nhập bị lỗi: ";
        # co chon nv
        if (count($cbReceiveStaff) > 0) {
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
                        $modelWorkAllocation->insert($dateAllocation, 0, $dateDeadline, 1, $hFunction->carbonNow(), $description, $productId, $loginStaffId, $receiveStaffId, $role);
                    }
                }
            }
        } else {
            Session::put('notifyAddAllocation', $errorContent);
        }
        return redirect()->back();
    }

    /*//them san pham vao don hang
    public function getAdd()
    {
        $modelStaff = new QcStaff();
        $dataAccess = [
            'accessObject' => 'product'
        ];
        return view('ad3d.order.product.add', compact('modelStaff','dataAccess'));
    }

    public function postAdd()
    {


    }*/

    //xác nhận sản phẩm
    public function getConfirm($productId)
    {
        $modelProduct = new QcProduct();
        $dataProduct = $modelProduct->getInfo($productId);
        if (count($dataProduct) > 0) {
            return view('ad3d.order.product.confirm', compact('dataProduct'));
        }

    }

    public function postConfirm($productId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelProduct = new QcProduct();
        $day = Request::input('cbDay');
        $month = Request::input('cbMonth');
        $year = Request::input('cbYear');
        $finishDate = $hFunction->convertStringToDatetime("$month/$day/$year H:i:00");
        return $modelProduct->confirmFinish($modelStaff->loginStaffId(), $finishDate, $productId);
    }

    public function cancelProduct($productId)
    {
        $modelProduct = new QcProduct();
        return $modelProduct->cancelProduct($productId);
    }
}
