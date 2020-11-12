<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 *
 * dataCompany
 */
date_default_timezone_set('Asia/Ho_Chi_Minh');
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$fromDate = $dataWork->fromDate();

$currentDate = $hFunction->carbonNow();
$currentDay = (int)date('d');
$currentMonth = (int)date('m');
$currentYear = (int)date('Y');
$currentHour = (int)date('H');
$currentMinute = (int)date('i');
if ($currentHour < 8) {
    $currentHour = 8;
    $currentMinute = 0;
}
?>
@extends('components.container.container-6')
@section('qc_container_content')
    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12"
                 style="margin-bottom: 5px; border-bottom: 1px dashed brown;">
                <h3 style="color: red;">GIỜ VÀO</h3>
            </div>
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form class="frm_time_begin_add form" name="frm_time_begin_add" role="form" method="post"
                      action="{!! route('qc.work.timekeeping.timeBegin.post') !!}">

                    @if( $currentHour >= 8 && ($currentHour +$currentMinute) > 8)
                        <div class="form-group row">
                            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12 " style="color: blue;">
                                <span style="background-color: red; color: yellow; padding: 5px;">chấm công trễ</span>
                            </div>
                        </div>
                    @endif
                    <div class="form-group row">
                        <div class="frm_notify col-sx-12 col-sm-12 col-md-12 col-lg-12 text-center  qc-color-red"></div>
                    </div>
                    <div class="form-group row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label>Giờ vào:</label>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" style="padding: 0;">
                                <select class="form-control" name="cbHoursBegin" style="padding: 0; color: red;">
                                    <option value="{!! $currentHour !!}">
                                        {!! $currentHour !!} giờ
                                    </option>
                                </select>
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" style="padding: 0;">
                                <select class="form-control" name="cbMinuteBegin" style="padding: 0; color: red;">
                                    <option value="{!! $currentMinute !!}">{!! $currentMinute !!} phút</option>
                                </select>
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" style="padding: 0;">
                                <select class="form-control" name="cbDayBegin" style="padding: 0;">
                                    <option value="{!! $currentDay !!}">Ngày {!! $currentDay !!}</option>
                                </select>
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" style="padding: 0;">
                                <select class="form-control" name="cbMonthBegin" style="padding: 0;">
                                    <option value="{!! $currentMonth !!}">{!! $currentMonth !!}</option>
                                </select>
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="padding: 0;">
                                <select class="form-control" name="cbYearBegin" style="padding: 0;">
                                    <option value="{!! $currentYear !!}">{!! $currentYear !!}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label>Chi chú:</label>
                            <input class="form-control" type="text" name="txtNote" value="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="text-center form-group form-group-sm">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <input type="hidden" name="txtWork" value="{!! $dataWork->workId() !!}">
                                <button type="button" class="qc_save btn btn-sm btn-primary">Lưu</button>
                                <button type="button" class="qc_container_close btn btn-sm btn-default">Đóng</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
