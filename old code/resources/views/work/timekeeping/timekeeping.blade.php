<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 5/15/2018
 * Time: 10:12 AM
 *
 * dataStaff
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$loginStaffId = $dataStaff->staffId();
$dataWork = $dataStaff->workInfoActivityOfStaff();
$workId = $dataWork->workId();
// chấm công
$dataTimekeepingProvisional = $dataWork->infoTimekeepingProvisional($workId, 'DESC');

// xin nghỉ
$dataLicenseOffWork = $dataStaff->timekeepingOffWorkOfStaff($loginStaffId, $dateFilter);

// xin trễ
$dataLicenseLateWork = $dataStaff->timekeepingLateWorkOfStaff($loginStaffId, $dateFilter);
?>
@extends('work.index')

@section('qc_work_body')
    <div class="row">
        <div class="qc_timekeeping_wrap qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12"
             data-work="{!! $workId !!}">
            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="active">
                            <a href="{!! route('qc.work.timekeeping.get') !!}">
                                <label>Chấm công</label>
                            </a>
                        </li>
                        <li>
                            <a href="{!! route('qc.work.work.get') !!}">
                                <label>Thông tin làm việc</label>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- thông tin khách hàng --}}
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <em>Tên:</em>
                        <span class="qc-font-bold">{!! $dataStaff->fullName() !!}</span>
                    </div>
                    <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <em class="qc-text-under">Từ:</em>
                        <b class="qc-color-red">&nbsp; {!! date('d-m-Y',strtotime($dataWork->fromDate())) !!}</b>
                        <span>&nbsp;&nbsp;</span>
                        <em class="qc-text-under">đến: </em>
                        <b class="qc-color-red">&nbsp; {!! date('d-m-Y',strtotime($dataWork->toDate())) !!}</b>
                    </div>
                    <div class="qc-padding-top-5 qc-padding-bot-5 text-right col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <a class="qc_time_begin_action qc-link-green-bold"
                           data-href="{!! route('qc.work.timekeeping.timeBegin.get') !!}">
                            Báo giờ vào
                        </a>
                        <span>&nbsp;||&nbsp;</span>
                        <a class="ac_off_work_action qc-link-green-bold"
                           data-href="{!! route('qc.work.timekeeping.offWork.get') !!}">
                            Xin nghỉ
                        </a>
                        <span>&nbsp;||&nbsp;</span>
                        <a class="ac_late_work_action qc-link-green-bold"
                           data-href="{!! route('qc.work.timekeeping.lateWork.get') !!}">
                            Xin trễ
                        </a>
                    </div>
                </div>
            </div>
            <div class="qc_timekeeping_contain qc-container-table qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12"
                 data-href-time-end="{!! route('qc.work.timekeeping.timeEnd.get') !!}"
                 data-href-image="{!! route('qc.work.timekeeping.timekeeping_provisional_image.add.get') !!}"
                 data-href-cancel="{!! route('qc.work.timekeeping.cancel.get') !!}">
                {{-- thong tin lam viec --}}
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: whitesmoke;">
                            <th class="text-center">STT</th>
                            <th class="text-center">Giờ vào</th>
                            <th class="text-center">Giờ ra</th>
                            <th class="text-center">Làm trưa</th>
                            <th>Ảnh BC</th>
                            <th class="text-center">Ghi chú</th>
                            <th></th>
                        </tr>
                        @if(count($dataTimekeepingProvisional) > 0)
                            @foreach($dataTimekeepingProvisional as $timekeepingProvisional)
                                <?php
                                $timekeepingProvisionalId = $timekeepingProvisional->timekeepingProvisionalId();
                                $timeBegin = $timekeepingProvisional->timeBegin();
                                $timeEnd = $timekeepingProvisional->timeEnd();
                                $timekeepingNote = $timekeepingProvisional->note();
                                $dataTimekeepingProvisionalImage = $timekeepingProvisional->imageOfTimekeepingProvisional($timekeepingProvisionalId);
                                $checkConfirmStatus = $timekeepingProvisional->checkConfirmStatus($timekeepingProvisionalId);

                                $beginCheckDate = date('Y-m-d 08:00:00', strtotime($timeBegin));
                                $endCheck = $hFunction->datetimePlusDay($beginCheckDate, 1);
                                $currentDateCheck = $hFunction->carbonNow();
                                if ($endCheck < $currentDateCheck) {
                                    $endCheckStatus = false;
                                } else {
                                    $endCheckStatus = true;
                                }
                                ?>
                                <tr class="qc_timekeeping_provisional_object"
                                    data-timekeeping-provisional="{!! $timekeepingProvisionalId !!}">
                                    <td class="text-center qc-padding-none">
                                        {!! $n_o = (isset($n_o)) ? $n_o + 1 : 1 !!}
                                    </td>
                                    <td class="text-center qc-padding-none">
                                        <span>
                                            {!! date('d-m-Y ', strtotime($timeBegin)) !!}
                                        </span>
                                        <span class="qc-font-bold" style="color: blue;">
                                            {!! date('H:i', strtotime($timeBegin)) !!}
                                        </span>
                                    </td>
                                    <td class="text-center qc-padding-none">
                                        @if(!empty($timeEnd))
                                            <span>
                                                {!! date('d-m-Y ', strtotime($timeEnd)) !!}
                                            </span>
                                            <span class="qc-font-bold" style="color: blue;">
                                                {!! date('H:i', strtotime($timeEnd)) !!}
                                            </span>
                                            @if(!$checkConfirmStatus)
                                                <br/>
                                                <a class="qc_time_end_edit_action qc-link-green"
                                                   data-href="{!! route('qc.work.timekeeping.timeEnd.edit.get',$timekeepingProvisionalId) !!}">
                                                    Sửa
                                                </a>
                                            @endif
                                        @else
                                            @if($endCheckStatus)
                                                <span>---</span>
                                            @else
                                                <em class="qc-color-red">---</em>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-center qc-padding-none">
                                        @if($timekeepingProvisional->checkAfternoonWork($timekeepingProvisionalId))
                                            <em class="qc-color-grey">Tăng ca làm trưa</em>
                                        @else
                                            <span>---</span>
                                        @endif
                                    </td>
                                    <td class="text-center qc-padding-none">
                                        @if(count($dataTimekeepingProvisionalImage) > 0)
                                            @foreach($dataTimekeepingProvisionalImage as $timekeepingProvisionalImage)
                                                <div style="position: relative; float: left; margin: 5px 10px 5px 10px; width: 70px; height: 70px; border: 1px solid #d7d7d7;">
                                                    <a class="qc_work_allocation_report_image_view qc-link"
                                                       data-href="{!! route('qc.work.timekeeping_provisional.image.get', $timekeepingProvisionalImage->imageId()) !!}">
                                                        <img style="max-width: 100%; max-height: 100%;"
                                                             src="{!! $timekeepingProvisionalImage->pathSmallImage($timekeepingProvisionalImage->name()) !!}">
                                                    </a>
                                                    @if(!$checkConfirmStatus)
                                                        <a class="ac_delete_image_action qc-link"
                                                           data-href="{!! route('qc.work.timekeeping.timekeeping_provisional_image.delete', $timekeepingProvisionalImage->imageId()) !!}">
                                                            <i style="position: absolute; font-weight: bold; padding: 0 3px; color: red; top: 3px; right: 3px; border: 1px solid #d7d7d7;">x</i>
                                                        </a>
                                                    @endif
                                                </div>
                                            @endforeach
                                        @else
                                            <span>---</span>
                                        @endif
                                    </td>
                                    <td class="text-center qc-padding-none">
                                        @if(!empty($timekeepingNote))
                                            <em class="qc-color-grey">{!! $timekeepingNote !!}</em>
                                        @else
                                            <span>---</span>
                                        @endif
                                    </td>
                                    <td class="text-right qc-padding-none">
                                        @if(empty($timeEnd))
                                            @if($endCheckStatus)
                                                <a class="qc_time_end_action qc-link-bold">Báo giờ ra</a>
                                                <span class="qc-color-grey"> &nbsp; | &nbsp; </span>
                                                <a class="qc_timekeeping_provisional_image_action qc-link-bold">Ảnh tiến
                                                    độ
                                                    CV</a>
                                                <span class="qc-color-grey"> &nbsp; | &nbsp; </span>
                                                <a class="qc_time_end_cancel qc-link-bold">Hủy</a>
                                            @else
                                                <em class="qc-color-red">Hết hạn báo</em>
                                            @endif
                                        @else
                                            @if($checkConfirmStatus)
                                                <span class="qc-color-grey">Đã duyệt</span>
                                            @else
                                                <a class="qc_timekeeping_provisional_image_action qc-link-bold">Ảnh tiến
                                                    độ
                                                    CV</a>
                                                <span class="qc-color-grey"> &nbsp; | &nbsp; </span>
                                                <a class="qc_time_end_cancel qc-link-bold">Hủy</a>
                                            @endif
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="text-center qc-padding-none" colspan="9">
                                    Không có thông tin
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
                {{-- xin di tre --}}
                <div class="row">
                    <div class="qc-color-red qc-padding-top-10 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <i class="glyphicon glyphicon-calendar"></i>
                        <span>Xin trễ</span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: whitesmoke;">
                            <th class="text-center">STT</th>
                            <th class="text-center">Ngày</th>
                            <th class="text-center">Ghi chú trễ</th>
                            <th class="text-center">Ghi chú Duyệt</th>
                            <th class="text-center">Duyệt</th>
                            <th class="text-right">Duyệt</th>
                        </tr>
                        @if(count($dataLicenseLateWork) > 0)
                            @foreach($dataLicenseLateWork as $licenseLateWork)
                                <?php
                                $licenseLateWorkId = $licenseLateWork->licenseId();
                                $dateLate = $licenseLateWork->dateLate();
                                $lateConfirmStatus = $licenseLateWork->confirmStatus();
                                $lateNote = $licenseLateWork->note();
                                $lateConfirmNote = $licenseLateWork->confirmNote();
                                ?>
                                <tr class="qc_timekeeping_late_work_object" data-late-work="{!! $licenseLateWorkId !!}"
                                    data-href-late-cancel="{!! route('qc.work.timekeeping.lateWork.cancel.get') !!}">
                                    <td class="text-center qc-padding-none">
                                        {!! $n_o_late = (isset($n_o_late)) ? $n_o_late + 1 : 1 !!}
                                    </td>
                                    <td class="text-center qc-padding-none">
                                        {!! date('d-m-Y ', strtotime($dateLate)) !!}
                                    </td>
                                    <td class="text-center qc-padding-none">
                                        @if(!empty($lateNote))
                                            <em class="qc-color-grey">{!! $lateNote !!}</em>
                                        @else
                                            <span>---</span>
                                        @endif
                                    </td>
                                    <td class="text-center qc-padding-none">
                                        @if(!empty($lateConfirmNote))
                                            <em class="qc-color-grey">{!! $lateConfirmNote !!}</em>
                                        @else
                                            <span>---</span>
                                        @endif
                                    </td>
                                    <td class="text-center qc-padding-none">
                                        @if(!$licenseLateWork->checkConfirmStatus())
                                            <em>Chờ duyệt</em>
                                        @else
                                            @if($licenseLateWork->checkAgreeStatus())
                                                <em>Đồng ý</em>
                                            @else
                                                <em>Không đồng ý</em>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-right qc-padding-none">
                                        @if(!$licenseLateWork->checkConfirmStatus())
                                            <a class="ac_late_work_cancel qc-link-red qc-font-bold">Hủy</a>
                                        @else
                                            <em>---</em>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="text-center qc-padding-none" colspan="9">
                                    Không có thông tin
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
                {{-- xin nghi --}}
                <div class="row ">
                    <div class="qc-color-red qc-padding-top-10 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <i class="glyphicon glyphicon-calendar"></i>
                        <span>Xin Nghỉ</span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: whitesmoke;">
                            <th class="text-center qc-padding-none">STT</th>
                            <th class="text-center qc-padding-none">Ngày</th>
                            <th class="text-center qc-padding-none">Ghi chú nghỉ</th>
                            <th class="text-center qc-padding-none">Ghi chú Duyệt</th>
                            <th class="text-center qc-padding-none">Duyệt</th>
                            <th class="text-right qc-padding-none">Duyệt</th>
                        </tr>
                        @if(count($dataLicenseOffWork) > 0)
                            @foreach($dataLicenseOffWork as $licenseOffWork)
                                <?php
                                $licenseOffWorkId = $licenseOffWork->licenseId();
                                $dateOff = $licenseOffWork->dateOff();
                                $offConfirmStatus = $licenseOffWork->confirmStatus();
                                $offNote = $licenseOffWork->note();
                                $confirmNote = $licenseOffWork->confirmNote();
                                ?>
                                <tr class="qc_timekeeping_off_work_object" data-off-work="{!! $licenseOffWorkId !!}"
                                    data-href-off-cancel="{!! route('qc.work.timekeeping.offWork.cancel.get') !!}">
                                    <td class="text-center qc-padding-none">
                                        {!! $n_o_off = (isset($n_o_off)) ? $n_o_off + 1 : 1 !!}
                                    </td>
                                    <td class="text-center qc-padding-none">
                                        {!! date('d-m-Y ', strtotime($dateOff)) !!}
                                    </td>

                                    <td class="text-center qc-padding-none">
                                        @if(!empty($offNote))
                                            <em class="qc-color-grey">{!! $offNote !!}</em>
                                        @else
                                            <span>---</span>
                                        @endif
                                    </td>
                                    <td class="text-center qc-padding-none">
                                        @if(!empty($confirmNote))
                                            <em class="qc-color-grey">{!! $confirmNote !!}</em>
                                        @else
                                            <span>---</span>
                                        @endif
                                    </td>
                                    <td class="text-center qc-padding-none">
                                        @if(!$licenseOffWork->checkConfirmStatus())
                                            <em>Chờ duyệt</em>
                                        @else
                                            @if($licenseOffWork->checkAgreeStatus())
                                                <em>Đồng ý</em>
                                            @else
                                                <em>Không đồng ý</em>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-right qc-padding-none">
                                        @if(!$licenseOffWork->checkConfirmStatus())
                                            <a class="ac_off_work_cancel qc-link-red qc-font-bold">Hủy</a>
                                        @else
                                            <em>---</em>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="text-center qc-padding-none" colspan="9">
                                    Không có thông tin
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
