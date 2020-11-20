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
$timekeepingNote = $dataTimekeepingProvisional->note();
# gio vao
$timeBegin = $dataTimekeepingProvisional->timeBegin();
#gio ra
$timeEnd = $dataTimekeepingProvisional->timeEnd();
$note = $dataTimekeepingProvisional->note();
$yearEnd = (int)date('Y', strtotime($timeEnd));
$monthEnd = (int)date('m', strtotime($timeEnd));
$dayEnd = (int)date('d', strtotime($timeEnd));
$hourEnd = (int)date('H', strtotime($timeEnd));
$minuteEnd = (int)date('i', strtotime($timeEnd));

$currentYear = $hFunction->currentYear();
?>
@extends('components.container.container-6')
@section('qc_container_content')
    <div class="qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
                <h3 style="color: red;">SỬA GIỜ RA</h3>
            </div>
            <div class="qc-padding-top-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form class="qc_frm_time_end_edit" name="qc_frm_time_end_edit" role="form"
                      method="post" enctype="multipart/form-data"
                      action="{!! route('qc.work.timekeeping.timeEnd.edit.post',$timekeepingProvisionalId) !!}">
                    <div class="row">
                        <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="font-weight: bold;">
                            <div class="frm_notify qc-font-size-16 form-group form-group-sm qc-color-red"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <label>Giờ ra:</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" style="padding: 0;">
                                        <select class="form-control" name="cbHoursEnd" style="padding: 0;color: red;">
                                            @for($h =1;$h<= 24; $h++)
                                                <option value="{!! $h !!}" @if($h == $hourEnd) selected="selected" @endif >
                                                    {!! $h !!} giờ
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" style="padding: 0;">
                                        <select class="form-control" name="cbMinuteEnd" style="padding: 0; color: red;">
                                            @for($i =0;$i<= 55; $i = $i+5)
                                                <option value="{!! $i !!}"
                                                        @if($i == $minuteEnd) selected="selected" @endif >
                                                    {!! $i !!} phút
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" style="padding: 0;">
                                        <select class="form-control" name="cbDayEnd">
                                            @for($d = 1;$d<= 31; $d++)
                                                <option value="{!! $d !!}"
                                                        @if($dayEnd == $d) selected="selected" @endif>
                                                    {!! $d !!}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" style="padding: 0;">
                                        <select class="form-control" name="cbMonthEnd" style="padding: 0;">
                                            @for($m = 1;$m<= 12; $m++)
                                                <option value="{!! $m !!}"
                                                        @if($monthEnd == $m) selected="selected" @endif>
                                                    {!! $m !!}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="padding: 0;">
                                        <select class="form-control" name="cbYearEnd" style="padding: 0;">
                                            <option value="{!! $currentYear -1 !!}"
                                                    @if($currentYear-1 == $yearEnd) selected="selected" @endif>
                                                {!! $currentYear-1 !!}
                                            </option>
                                            <option value="{!! $currentYear !!}"
                                                    @if($currentYear == $yearEnd) selected="selected" @endif>
                                                {!! $currentYear !!}
                                            </option>
                                            <option value="{!! $currentYear +1 !!}"
                                                    @if($currentYear +1 == $yearEnd) selected="selected" @endif>
                                                {!! $currentYear+1 !!}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm">
                                <label>Ghi chú:</label>
                                <input class="form-control" type="text" name="txtNote" value="{!! $note !!}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="txtAfternoonStatus"
                                           @if($dataTimekeepingProvisional->checkAfternoonWork($timekeepingProvisionalId)) checked="checked" @endif>
                                    Có làm trưa
                                </label>
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
                                <button type="reset" class="btn btn-sm btn-default">
                                    NHẬP LẠI
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
