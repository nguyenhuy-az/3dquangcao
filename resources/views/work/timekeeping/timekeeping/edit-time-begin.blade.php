<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 *
 * dataCompany
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();

$timekeepingProvisionalId = $dataTimekeepingProvisional->timekeepingProvisionalId();
# gio vao
$timeBegin = $dataTimekeepingProvisional->timeBegin();
$yearBegin = (int)date('Y', strtotime($timeBegin));
$monthBegin = (int)date('m', strtotime($timeBegin));
$dayBegin = (int)date('d', strtotime($timeBegin));
$hourBegin = (int)date('H', strtotime($timeBegin));
$minuteBegin = (int)date('i', strtotime($timeBegin));

$currentYear = $hFunction->currentYear();
?>
@extends('components.container.container-6')
@section('qc_container_content')
    <div class="qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
                <h3 style="color: red;">SỬA GIỜ VÀO</h3>
            </div>
            <div class="qc-padding-top-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form class="qc_frm_time_end_edit" name="qc_frm_time_end_edit" role="form"
                      method="post" enctype="multipart/form-data"
                      action="{!! route('qc.work.timekeeping.timeBegin.edit.post',$timekeepingProvisionalId) !!}">
                    <div class="row">
                        <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="font-weight: bold;">
                            <div class="frm_notify qc-font-size-16 form-group form-group-sm qc-color-red"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <label>Giờ vào:</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" style="padding: 0;">
                                        <select class="form-control" name="cbHoursBegin" style="padding: 0;color: red;">
                                            @for($h =1;$h<= 24; $h++)
                                                <option value="{!! $h !!}"
                                                        @if($h == $hourBegin) selected="selected" @endif >
                                                    {!! $h !!} giờ
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" style="padding: 0;">
                                        <select class="form-control" name="cbMinuteBegin" style="padding: 0; color: red;">
                                            @for($i =0;$i<= 55; $i = $i+1)
                                                <option value="{!! $i !!}"
                                                        @if($i == $minuteBegin) selected="selected" @endif >
                                                    {!! $i !!} phút
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" style="padding: 0;">
                                        <select class="form-control" name="cbDayBegin">
                                            <option value="{!! $dayBegin !!}">
                                                {!! $dayBegin !!}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" style="padding: 0;">
                                        <select class="form-control" name="cbMonthBegin" style="padding: 0;">
                                            <option value="{!! $monthBegin !!}">
                                                {!! $monthBegin !!}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="padding: 0;">
                                        <select class="form-control" name="cbYearBegin" style="padding: 0;">
                                            <option value="{!! $yearBegin !!}">
                                                {!! $yearBegin !!}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="text-center form-group form-group-sm">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <button type="button" class="qc_save btn btn-sm btn-primary">
                                    XÁC NHẬN BÁO
                                </button>
                                <button type="button" class="qc_container_close btn btn-sm btn-default">
                                    ĐÓNG
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
