<?php

namespace App\Http\Controllers\Work\WorkAllocation;

use App\Models\Ad3d\Order\QcOrder;
use App\Models\Ad3d\OrderAllocation\QcOrderAllocation;
use App\Models\Ad3d\Product\QcProduct;
use App\Models\Ad3d\Rule\QcRules;
use App\Models\Ad3d\Salary\QcSalary;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\TimekeepingProvisionalImage\QcTimekeepingProvisionalImage;
use App\Models\Ad3d\Work\QcWork;
//use Illuminate\Http\Request;
use App\Models\Ad3d\WorkAllocation\QcWorkAllocation;
use App\Models\Ad3d\WorkAllocationReport\QcWorkAllocationReport;
use App\Models\Ad3d\WorkAllocationReportImage\QcWorkAllocationReportImage;
use App\Models\Ad3d\WorkAllocationReportImage\QcWorkAllocationReportImageImage;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use File;
use Input;
use Request;

class WorkAllocationController extends Controller
{
    public function activityIndex()
    {
        $modelStaff = new QcStaff();
        if ($modelStaff->checkLogin()) {
            $dataAccess = [
                'object' => 'workAllocationActivity'
            ];
            $dataStaff = $modelStaff->loginStaffInfo();
            $dataWorkAllocation = $dataStaff->workAllocationActivityOfStaffReceive();
            return view('work.work-allocation.work-allocation-activity', compact('dataAccess', 'modelStaff', 'dataStaff', 'dataWorkAllocation'));
        } else {
            return view('work.login');
        }

    }

    #xem ảnh báo cáo
    public function viewDesignImage($productId)
    {
        $modelProduct = new QcProduct();
        $dataProduct = $modelProduct->getInfo($productId);
        if (count($dataProduct) > 0) {
            return view('work.work-allocation.view-design-image', compact('dataProduct'));
        }
    }

    #xem ảnh báo cáo
    public function viewReportImage($imageId)
    {
        $modelReportImage = new QcWorkAllocationReportImage();
        $dataWorkAllocationReportImage = $modelReportImage->getInfo($imageId);
        if (count($dataWorkAllocationReportImage) > 0) {
            return view('work.work-allocation.view-image', compact('dataWorkAllocationReportImage'));
        }
    }

    public function viewTimekeepingProvisionalImage($imageId)
    {
        $modelTimekeepingProvisionalImage = new QcTimekeepingProvisionalImage();
        $dataTimekeepingProvisionalImage = $modelTimekeepingProvisionalImage->getInfo($imageId);
        return view('work.work-allocation.view-timekeeping-provisional-image', compact('dataTimekeepingProvisionalImage'));
    }

    public function getAllocationReport($allocationId)
    {
        $modelStaff = new QcStaff();
        $modelWorkAllocation = new QcWorkAllocation();
        if ($modelStaff->checkLogin()) {
            $dataAccess = [
                'object' => 'workAllocationActivity'
            ];
            $dataStaff = $modelStaff->loginStaffInfo();
            $dataWorkAllocation = $modelWorkAllocation->getInfo($allocationId);
            return view('work.work-allocation.work-allocation-report', compact('dataAccess', 'modelStaff', 'dataStaff', 'dataWorkAllocation'));
        } else {
            return view('work.login');
        }

    }

    public function postAllocationReport()
    {
        $hFunction = new \Hfunction();
        $modelWorkAllocation = new QcWorkAllocation();
        $modelWorkAllocationReport = new QcWorkAllocationReport();
        $modelWorkAllocationReportImage = new QcWorkAllocationReportImage();
        $allocationId = Request::input('txtWorkAllocation');

        $dayReport = Request::input('cbDayReport');
        $monthReport = Request::input('cbMonthReport');
        $yearReport = Request::input('cbYearReport');
        $hourReport = Request::input('cbHoursReport');
        $minuteReport = Request::input('cbMinuteReport');
        $txtReportImage = Request::file('txtReportImage');
        //$txtReportContent = Request::input('txtReportContent');
        $cbReportStatus = Request::input('cbReportStatus');

        $allocationDate = $modelWorkAllocation->allocationDate($allocationId)[0];
        $timeReport = $hFunction->convertStringToDatetime("$monthReport/$dayReport/$yearReport $hourReport:$minuteReport:00");
        if (date('Y-m-d H:i', strtotime($allocationDate)) > date('Y-m-d H:i', strtotime($timeReport))) {
            return 'Giờ báo cáo phải lớn hơn giờ giao';
        } else {
            if (count($txtReportImage) > 0) {
                $reportContent = ($cbReportStatus == 2) ? 'Báo cáo tiến độ' : 'Báo cáo kết thúc công việc';
                if ($modelWorkAllocationReport->insert($hFunction->carbonNow(), $reportContent, $allocationId)) {
                    $reportId = $modelWorkAllocationReport->insertGetId();
                    $n_o = 0;
                    foreach ($_FILES['txtReportImage']['name'] as $name => $value) {
                        $name_img = stripslashes($_FILES['txtReportImage']['name'][$name]);
                        if (!empty($name_img)) {
                            $n_o = $n_o + 1;
                            $name_img = $hFunction->getTimeCode() . "_$n_o." . $hFunction->getTypeImg($name_img);
                            $source_img = $_FILES['txtReportImage']['tmp_name'][$name];
                            if ($modelWorkAllocationReportImage->uploadImage($source_img, $name_img, 500)) {
                                $modelWorkAllocationReportImage->insert($name_img, $reportId);
                            }
                        }
                    }
                }
                # bao cao hoan thanh cong viec
                if ($cbReportStatus == 0 || $cbReportStatus == 1) {
                    $modelWorkAllocation->confirmFinish($allocationId, $timeReport, $cbReportStatus, 0);
                }

            } else {
                return 'Phải có ảnh báo cáo';
            }

        }
    }

    public function deleteReportImage($imageId)
    {
        $modeWorkAllocationReportImage = new QcWorkAllocationReportImage();
        return $modeWorkAllocationReportImage->deleteImage($imageId);
    }

    public function cancelReport($reportId)
    {
        $modelWorkAllocationReport = New QcWorkAllocationReport();
        return $modelWorkAllocationReport->deleteReport($reportId);
    }

    //cong viec da ket thuc
    public function finishIndex()
    {
        $modelStaff = new QcStaff();
        if ($modelStaff->checkLogin()) {
            $dataAccess = [
                'object' => 'workAllocationFinish',
            ];
            $dataStaff = $modelStaff->loginStaffInfo();
            $dataWorkAllocation = $dataStaff->workAllocationFinishOfStaffReceive();
            return view('work.work-allocation.work-allocation-finish', compact('dataAccess', 'modelStaff', 'dataStaff', 'dataWorkAllocation'));
        } else {
            return view('work.login');
        }
    }

    // danh sach cong trinh dc ban giao
    public function constructionIndex($loginMonth = null, $loginYear = null)
    {
        $modelStaff = new QcStaff();
        if ($modelStaff->checkLogin()) {
            $dataAccess = [
                'object' => 'workAllocationConstruction'
            ];
            $loginMonth = (empty($loginMonth)) ? date('m') : $loginMonth;
            $loginYear = (empty($loginYear)) ? date('Y') : $loginYear;
            return view('work.work-allocation.construction.construction', compact('dataAccess', 'modelStaff', 'loginMonth', 'loginYear'));
        } else {
            return view('work.login');
        }
    }

    # xac nhan cong trinh
    public function getConstructionConfirm($allocationId = null)
    {
        $modelOrderAllocation = new QcOrderAllocation();
        $dataOrderAllocation = $modelOrderAllocation->getInfo($allocationId);
        if (count($dataOrderAllocation) > 0) {
            return view('work.work-allocation.construction.confirm-finish', compact('dataOrderAllocation'));
        }
    }

    public function postConstructionConfirm($allocationId = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelOrderAllocation = new QcOrderAllocation();
        if ($modelStaff->checkLogin()) {
            $modelOrderAllocation->reportFinishAllocation($allocationId, $hFunction->carbonNow());
        }
    }

    //quan ly san pham
    public function constructionProduct($allocationId)
    {
        $modelStaff = new QcStaff();
        $modelOrderAllocation = new QcOrderAllocation();
        if ($modelStaff->checkLogin()) {
            $dataAccess = [
                'object' => 'workAllocationConstruction',
                'subObjectLabel' => 'Sản phẩm'
            ];
            $dataOrdersAllocation = $modelOrderAllocation->getInfo($allocationId);
            return view('work.work-allocation.construction.product.product', compact('dataAccess', 'modelStaff', 'dataOrdersAllocation'));
        } else {
            return view('work.login');
        }
    }

    # xac nhan san pham
    public function getConstructionProductConfirm($productId = null)
    {
        $modelProduct = new QcProduct();
        $dataProduct = $modelProduct->getInfo($productId);
        if (count($dataProduct) > 0) {
            return view('work.work-allocation.construction.product.confirm-finish', compact('dataProduct'));
        }
    }

    public function postConstructionProductConfirm($productId = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelProduct = new QcProduct();
        if ($modelStaff->checkLogin()) {
            $modelProduct->confirmFinish($modelStaff->loginStaffId(), $hFunction->carbonNow(), $productId);
        }
    }
}
