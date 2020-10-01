<?php

namespace App\Http\Controllers\Work\WorkAllocation\WorkAllocation;


use App\Models\Ad3d\ProductDesign\QcProductDesign;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\StaffNotify\QcStaffNotify;
use App\Models\Ad3d\TimekeepingProvisionalImage\QcTimekeepingProvisionalImage;
use App\Models\Ad3d\WorkAllocation\QcWorkAllocation;
use App\Models\Ad3d\WorkAllocationReport\QcWorkAllocationReport;
use App\Models\Ad3d\WorkAllocationReportImage\QcWorkAllocationReportImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WorkAllocationController extends Controller
{
    public function index($finishStatus = 100, $monthFilter = 100,$yearFilter = 100)
    {
        $modelStaff = new QcStaff();
        if ($modelStaff->checkLogin()) {
            $dataAccess = [
                'object' => 'workAllocation'
            ];
            $dateFilter = null;
            if ($monthFilter == 0 && $yearFilter == 0) { //khong chon thoi gian xem
                $monthFilter = 100;
                $yearFilter = 100;
                $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
            } elseif ($monthFilter == 100 && $yearFilter == 0) { //xam tat ca cac thang va khong chon nam
                $yearFilter = date('Y');
                $dateFilter = date('Y', strtotime("1-1-$yearFilter"));
            } elseif ($monthFilter > 0 && $monthFilter < 100 && $yearFilter == 100) { //co chon thang va khong chon nam
                $yearFilter = date('Y');
                $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
            } elseif ($monthFilter > 0 && $monthFilter < 100 && $yearFilter > 100) { //co chon thang va chon nam
                $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
            } elseif ($monthFilter == 100 && $yearFilter == 100) { //xem tất cả
                $dateFilter = null;
            }elseif ($monthFilter == 100 && $yearFilter > 100) { //xem tất cả
                $dateFilter = date('Y', strtotime("1-1-$yearFilter"));
            } else {
                $dateFilter = date('Y-m');
                $monthFilter = date('m');
                $yearFilter = date('Y');
            }
            $dataStaff = $modelStaff->loginStaffInfo();
            $dataWorkAllocation = $dataStaff->selectWorkAllocationOfStaffReceive($dataStaff->staffId(),$finishStatus,$dateFilter)->paginate(50);
            return view('work.work-allocation.work-allocation.list', compact('dataAccess', 'modelStaff', 'dataStaff', 'dataWorkAllocation','finishStatus','monthFilter','yearFilter'));
        } else {
            return view('work.login');
        }

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
            return view('work.work-allocation.work-allocation.report', compact('dataAccess', 'modelStaff', 'dataStaff', 'dataWorkAllocation'));
        } else {
            return view('work.login');
        }

    }
    public function postAllocationReport(Request $request)
    {
        $hFunction = new \Hfunction();
        $modelWorkAllocation = new QcWorkAllocation();
        $modelWorkAllocationReport = new QcWorkAllocationReport();
        $modelWorkAllocationReportImage = new QcWorkAllocationReportImage();
        $allocationId = $request->input('txtWorkAllocation');

        $dayReport = $request->input('cbDayReport');
        $monthReport = $request->input('cbMonthReport');
        $yearReport = $request->input('cbYearReport');
        $hourReport = $request->input('cbHoursReport');
        $minuteReport = $request->input('cbMinuteReport');
        $txtReportImage = $request->file('txtReportImage');
        //$txtReportContent = $request->input('txtReportContent');
        $cbReportStatus = $request->input('cbReportStatus');

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
    public function getDetailAllocation($allocationId){
        $modelStaff = new QcStaff();
        $modelStaffNotify = new QcStaffNotify();
        $modelWorkAllocation = new QcWorkAllocation();
        if ($modelStaff->checkLogin()) {
            $dataAccess = [
                'object' => 'workAllocation'
            ];
            $dataStaff = $modelStaff->loginStaffInfo();
            $dataWorkAllocation = $modelWorkAllocation->getInfo($allocationId);
            # cap nhat da xem thong bao
            $modelStaffNotify->updateViewedOfStaffAndWorkAllocation($dataStaff->staffId(), $allocationId);
            return view('work.work-allocation.work-allocation.detail', compact('dataAccess', 'modelStaff', 'dataStaff', 'dataWorkAllocation'));
        } else {
            return view('work.login');
        }
    }
    #xem ảnh thiet ke
    public function viewDesignImage($designId)
    {
        $hFunction = new \Hfunction();
        $modelProductDesign = new QcProductDesign();
        $dataProductDesign = $modelProductDesign->getInfo($designId);
        if ($hFunction->checkCount($dataProductDesign)) {
            return view('work.work-allocation.work-allocation.design-image-view', compact('dataProductDesign'));
        }
    }
    # huy bao cao
    public function cancelReport($reportId)
    {
        $modelWorkAllocationReport = New QcWorkAllocationReport();
        $modelWorkAllocationReport->deleteReport($reportId);
    }
    #xem anh bao cao truc tiep
    public function viewReportImageDirect($imageId)
    {
        $modelWorkAllocationReportImage = new QcWorkAllocationReportImage();
        $dataWorkAllocationReportImage = $modelWorkAllocationReportImage->getInfo($imageId);
        return view('work.work-allocation.work-allocation.report-image-direct-view', compact('dataWorkAllocationReportImage'));
    }
    # xem anh bao cao qua cham cong
    public function viewReportImage($imageId)
    {
        $modelTimekeepingProvisionalImage = new QcTimekeepingProvisionalImage();
        $dataTimekeepingProvisionalImage = $modelTimekeepingProvisionalImage->getInfo($imageId);
        return view('work.work-allocation.work-allocation.report-image-view', compact('dataTimekeepingProvisionalImage'));
    }
    #===========
    public function deleteReportImage($imageId)
    {
        $modeWorkAllocationReportImage = new QcWorkAllocationReportImage();
        return $modeWorkAllocationReportImage->deleteImage($imageId);
    }






}
