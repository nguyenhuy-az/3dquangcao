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
                <h3>SỬA GIỜ RA</h3>
            </div>
            <div class="qc-padding-top-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form class="qc_frm_time_end_edit form-horizontal" name="qc_frm_time_end_edit" role="form"
                      method="post" enctype="multipart/form-data"
                      action="{!! route('qc.work.timekeeping.timeEnd.edit.post',$timekeepingProvisionalId) !!}">
                    <div class="row">
                        <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="font-weight: bold;">
                            <div class="frm_notify qc-font-size-16 form-group form-group-sm qc-color-red"></div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm">
                                <label class="col-sm-3 control-label">Ngày ra:</label>
                                <div class="col-sm-9">
                                    <select name="cbDayEnd" style="height: 30px;">
                                        @for($i = 1;$i<= 31; $i++)
                                            <option value="{!! $i !!}" @if($dayEnd == $i) selected="selected" @endif>
                                                {!! $i !!}
                                            </option>
                                        @endfor
                                    </select>
                                    <span>/</span>
                                    <select name="cbMonthEnd" style="height: 30px;">
                                        @for($i = 1;$i<= 12; $i++)
                                            <option value="{!! $i !!}" @if($monthEnd == $i) selected="selected" @endif>
                                                {!! $i !!}
                                            </option>
                                        @endfor
                                    </select>
                                    <span>/</span>
                                    <select name="cbYearEnd" style="height: 30px;">
                                        <option value="{!! $currentYear -1 !!}" @if($currentYear-1 == $yearEnd) selected="selected" @endif>
                                            {!! $currentYear-1 !!}
                                        </option>
                                        <option value="{!! $currentYear !!}" @if($currentYear == $yearEnd) selected="selected" @endif>
                                            {!! $currentYear !!}
                                        </option>
                                        <option value="{!! $currentYear +1 !!}" @if($currentYear +1 == $yearEnd) selected="selected" @endif>
                                            {!! $currentYear+1 !!}
                                        </option>
                                    </select>
                                    &emsp;
                                    <select name="cbHoursEnd" style="height: 30px;">
                                        @for($i =1;$i<= 24; $i++)
                                            <option value="{!! $i !!}" @if($i == $hourEnd) selected="selected" @endif >
                                                {!! $i !!}
                                            </option>
                                        @endfor
                                    </select>
                                    <span>:</span>
                                    <select name="cbMinuteEnd" style="height: 30px;">
                                        @for($i =0;$i<= 55; $i = $i+5)
                                            <option value="{!! $i !!}" @if($i == $minuteEnd) selected="selected" @endif >
                                                {!! $i !!}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="txtAfternoonStatus" @if($dataTimekeepingProvisional->checkAfternoonWork($timekeepingProvisionalId)) checked="checked" @endif> Có làm trưa
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label">Ghi chú:</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="txtNote" value="{!! $note !!}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="text-center form-group form-group-sm">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <button type="button" class="qc_save btn btn-sm btn-primary">Lưu</button>
                                <button type="reset" class="btn btn-sm btn-default">Nhập lại</button>
                                <button type="button" class="qc_container_close btn btn-sm btn-default">Đóng
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
