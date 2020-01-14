<?php

namespace App\Http\Controllers\Ad3d\Order\Allocation;

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
use File;
use Illuminate\Support\Facades\Session;
use Input;
use Request;

class OrderAllocationController extends Controller
{
    public function index($companyFilterId = null, $dayFilter = 0, $monthFilter = 0, $yearFilter = 0, $finishStatus = 2, $nameFiler = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelOrder = new QcOrder();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataAccess = [
            'accessObject' => 'orderAllocation'
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
        $dataCompany = $modelCompany->getInfo();
        if ($dataStaffLogin->checkRootManage()) {
            if (empty($companyFilterId)) {
                $searchCompanyFilterId = $modelCompany->listIdActivity();
            } else {
                $searchCompanyFilterId = [$companyFilterId];
            }
        } else {
            $searchCompanyFilterId = [$dataStaffLogin->companyId()];
            $companyFilterId = $dataStaffLogin->companyId();
        }
        $listStaffId = $modelStaff->listIdOfListCompany($searchCompanyFilterId);

        $dataOrderAllocation = null;
        if ($finishStatus == 2) {
            if (empty($nameFiler)) {
                if (empty($dateFilter)) {
                    $dataOrderAllocation = QcOrderAllocation::whereIn('receiveStaff_id', $listStaffId)->orderBy('allocationDate', 'DESC')->select('*')->paginate(30);
                } else {
                    $dataOrderAllocation = QcOrderAllocation::whereIn('receiveStaff_id', $listStaffId)->where('allocationDate', 'like', "%$dateFilter%")->orderBy('allocationDate', 'DESC')->select('*')->paginate(30);
                }

            } else {
                if (empty($dateFilter)) {
                    #$dataOrderAllocation = QcOrderAllocation::where('name', 'like', "%$nameFiler%")->whereIn('staff_id', $listStaffId)->orderBy('receiveDate', 'DESC')->orderBy('orderCode', 'DESC')->select('*')->paginate(30);
                } else {
                    #$dataOrderAllocation = QcOrderAllocation::where('name', 'like', "%$nameFiler%")->whereIn('staff_id', $listStaffId)->where('receiveDate', 'like', "%$dateFilter%")->orderBy('receiveDate', 'DESC')->orderBy('orderCode', 'DESC')->select('*')->paginate(30);
                }
            }

        } else {
            if (empty($nameFiler)) {
                $dataOrderAllocation = QcOrderAllocation::where('finishStatus', $finishStatus)->whereIn('receiveStaff_id', $listStaffId)->where('allocationDate', 'like', "%$dateFilter%")->orderBy('allocationDate', 'DESC')->select('*')->paginate(30);
            } else {
                #$dataOrderAllocation = QcOrderAllocation::where('name', 'like', "%$nameFiler%")->where('paymentStatus', $finishStatus)->whereIn('staff_id', $listStaffId)->where('allocationDate', 'like', "%$dateFilter%")->orderBy('allocationDate', 'DESC')->orderBy('orderCode', 'DESC')->select('*')->paginate(30);
            }

        }
        return view('ad3d.order.allocation.list', compact('modelStaff', 'dataCompany', 'dataAccess', 'dataOrderAllocation', 'companyFilterId', 'dayFilter', 'monthFilter', 'yearFilter', 'finishStatus', 'nameFiler'));

    }

    #xem anh chi tiet
    public function viewReportImage($imageId)
    {

    }

    public function getConfirm($allocationId)
    {
        $modelOrderAllocation = new QcOrderAllocation();
        $dataAccess = [
            'accessObject' => 'order'
        ];
        $dataOrderAllocation = $modelOrderAllocation->getInfo($allocationId);
        if (count($dataOrderAllocation) > 0) {
            return view('ad3d.order.allocation.confirm-finish', compact('dataAccess', 'dataOrderAllocation'));
        }
    }

    public function postConfirm($allocationId)
    {
        $modelStaff = new QcStaff();
        $modelOrderAllocation = new QcOrderAllocation();
        $confirmAgree = Request::input('cbConfirmAgree');
        $staffLoginId = $modelStaff->loginStaffId();
        $dataOrderAllocation = $modelOrderAllocation->getInfo($allocationId);
        if (!empty($staffLoginId) && count($dataOrderAllocation) > 0) {
            $modelOrderAllocation->confirmFinishAllocation($allocationId, $confirmAgree, $staffLoginId);
        }

    }

    public function cancel($allocationId)
    {
        $modelOrderAllocation = new QcOrderAllocation();
        $modelOrderAllocation->cancel($allocationId);
    }
}
