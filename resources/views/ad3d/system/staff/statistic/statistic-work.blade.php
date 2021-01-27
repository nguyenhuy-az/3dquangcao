<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 *
 * dataStaff
 */

$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$dataCompanyStaffWork = $dataWork->companyStaffWork;
$fromDate = $dataWork->fromDate();
//$dateFilter = date('Y-m', strtotime($fromDate));
$companyStaffWorkId = $dataCompanyStaffWork->workId();
$dataStaff = $dataCompanyStaffWork->staff;
$statisticStaffId = $dataStaff->staffId();
$dataCompany = $dataCompanyStaffWork->company;
# ngay co di lam
$dataTimekeepingHasWork = $dataWork->getInfoHasWorkTimekeeping();
# ngay khong co di lam
$dataTimekeepingNotWork = $dataWork->getInfoNotWorkTimekeeping();
/*
if ($workStatus == 'get-has-work') { # co lam viec
    $dataAllTimekeeping = $modelStatistical->getInfoHasWorkTimekeeping($statisticStaffId, $dateFilter);
    $title = 'TẤT CẢ NGÀY LÀM VIỆC';
} elseif ($workStatus == 'get-off-has-permission') { # nghi lam co phep
    $dataWorkAllocation = $modelStatistical->statisticGetWorkAllocationHasLate($statisticStaffId, $dateFilter);
    $title = 'NGÀY NGHỈ LÀM CÓ PHÉP';
} elseif ($workStatus == 'get-off-not-permission') { # nghi lam khong phep
    $dataWorkAllocation = $modelStatistical->statisticGetWorkAllocationHasFinish($statisticStaffId, $dateFilter);
    $title = 'NGÀY NGHỈ LÀM KHÔNG PHÉP';
}elseif ($workStatus == 'get-work-has-late') { # ngay lam tre
    $dataWorkAllocation = $modelStatistical->statisticGetWorkAllocationFinishNotLate($statisticStaffId, $dateFilter);
    $title = 'NGÀY ĐI LÀM TRỄ';
}
elseif ($workStatus == 'get-has-overtime') { # so ngay co tang ca
    $dataWorkAllocation = $modelStatistical->statisticGetWorkAllocationFinishHasLate($statisticStaffId, $dateFilter);
    $title = 'NGÀY LÀM CÓ TĂNG CA';
}else {
    $dataWorkAllocation = null;
    $title = 'KHÔNG TÌM THẤY THÔNG TIN';
}
 */
$dateOfMonth = 31;
?>
@extends('ad3d.system.staff.index')
@section('qc_ad3d_index_content')
    <div class="qc_ad3d_sys_staff_statistical_wrap qc-padding-bot-30 row">
        <div class="row" style="margin-bottom: 20px;">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <a class="qc-link-white-bold btn btn-sm btn-primary" onclick="qc_main.page_back_go();">
                    <i class="glyphicon glyphicon-backward"></i> Về trang trước
                </a>
            </div>
        </div>
        <div class="row" style="margin-bottom: 10px;">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <div class="media">
                    <a class="pull-left">
                        <img class="media-object"
                             style="background-color: white; width: 60px;height: 60px; border: 1px solid #d7d7d7;border-radius: 10px;"
                             src="{!! $dataStaff->pathAvatar($dataStaff->image()) !!}">
                    </a>

                    <div class="media-body">
                        <h5 class="media-heading">{!! $dataStaff->fullName() !!}</h5>
                        <em style="color: grey;">{!! $dataCompany->name() !!}</em>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <h3 style="color: red;">{!! date('m-Y', strtotime($fromDate)) !!}</h3>
            </div>
        </div>
        {{--Ngay di lam--}}
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <label style="color: red; font-size: 1.5em;">
                    NGÀY ĐI LÀM ĐI LÀM - {!! $hFunction->getCount($dataTimekeepingHasWork) !!}
                </label>
            </div>
            @if($hFunction->checkCount($dataTimekeepingHasWork))
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        @foreach($dataTimekeepingHasWork as $hasTimeKeeping)
                            <?php
                            $timeBegin = $hasTimeKeeping->timeBegin();
                            $day = $hFunction->getDayFromDate($timeBegin);
                            # thong tin yeu cau tang ca
                            $dataOverTimeRequest = $dataCompanyStaffWork->overTimeRequestGetInfoInDate($timeBegin);
                            ?>
                            <div class="col-xs-4 col-sm-3 col-md-2 col-lg-2">
                                <div class="panel panel-default" style="height: 110px; border: 1px solid blue;">
                                    <!-- Default panel contents -->
                                    <div class="panel-heading">
                                        <b style="font-size: 1.5em;">
                                            {!! $day !!}
                                        </b>
                                        @if($hasTimeKeeping->checkOvertime())
                                            <span class="badge pull-right">Có tăng ca</span>
                                        @endif
                                    </div>
                                    <div class="panel-body" style="padding-top: 5px;">
                                        <span style="color: blue;">Giờ vào:</span>
                                        <b>
                                            {!! date('H:i',strtotime($timeBegin)) !!}
                                        </b>
                                        @if($hasTimeKeeping->checkWorkLate())
                                            <em style="color: yellow; background-color: red; padding: 3px;">Trễ</em>
                                        @endif
                                        @if(!$hFunction->checkEmpty($hasTimeKeeping->timeEnd()))
                                            <br/>
                                            <span style="color: brown;">Giờ ra:</span>
                                            <b> {!! date('H:i',strtotime($hasTimeKeeping->timeEnd())) !!}</b>
                                            @if($hasTimeKeeping->checkAfternoonStatus())
                                                <em style="color: grey;"> - Có làm trưa</em>
                                            @endif
                                        @else
                                            <br/>
                                            <em style="color: grey;">Không có giờ ra</em>
                                        @endif
                                        @if($hFunction->checkCount($dataOverTimeRequest))
                                            <br/>
                                            <em style="color: red;">Có yêu cầu tăng ca</em>

                                        @endif
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
        {{--Ngay nghi--}}
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <label style="color: red; font-size: 1.5em;">
                    NGÀY NGHỈ - {!! $hFunction->getCount($dataTimekeepingNotWork) !!}
                </label>
            </div>
            @if($hFunction->checkCount($dataTimekeepingNotWork))
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        @foreach($dataTimekeepingNotWork as $notTimeKeeping)
                            <?php
                            $dateOff = $notTimeKeeping->dateOff();
                            $day = $hFunction->getDayFromDate($dateOff);
                            ?>
                            <div class="col-xs-4 col-sm-3 col-md-2 col-lg-2">
                                <div class="panel panel-default" style="height: 100px; border: 1px solid red;">
                                    <!-- Default panel contents -->
                                    <div class="panel-heading" style="background-color: black; color: yellow;">
                                        <b style="font-size: 1.5em;">
                                            {!! $day !!}
                                        </b>
                                    </div>

                                    <div class="panel-body">
                                        @if($notTimeKeeping->checkPermissionStatus())
                                            <em style="color: green;">Nghỉ có phép</em>
                                        @else
                                            <em style="color: red;">Nghỉ không phép</em>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
