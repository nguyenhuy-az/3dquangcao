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
if($currentHour < 8){
    $currentHour = 8;
    $currentMinute = 0;
}
?>
@extends('components.container.container-6')
@section('qc_container_content')
    <div class="qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
                <h3>GIỜ VÀO</h3>
            </div>
            <div class="qc-padding-top-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form class="frm_time_begin_add form-horizontal" name="frm_time_begin_add" role="form" method="post"
                      action="{!! route('qc.work.timekeeping.timeBegin.post') !!}">
                    <div class="row">
                        @if( $currentHour >= 8 && ($currentHour +$currentMinute) > 8)
                            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-center form-group qc-color-red">
                                    <span>Chấm công trễ</span>
                                </div>
                            </div>
                        @endif
                        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="frm_notify text-center form-group qc-color-red"></div>
                        </div>
                        <div class="form-group form-group-sm col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label class="col-sm-2 control-label">Giờ vào:</label>
                            <div class="col-sm-10">
                                <select name="cbDayBegin" style="height: 25px;">
                                    <option value="{!! $currentDay !!}">{!! $currentDay !!}</option>
                                    {{--
                                    @for($i = $hFunction->getDayFromDate($fromDate);$i<= 31; $i++)
                                        <option value="{!! $i !!}" @if($i == $currentDay) selected="selected" @endif>
                                            {!! $i !!}
                                        </option>
                                    @endfor
                                    --}}
                                </select>
                                <span>/</span>
                                <select name="cbMonthBegin" style="height: 25px;">
                                    <option value="{!! $currentMonth !!}">{!! $currentMonth !!}</option>
                                    {{--<option value="{!! (int)$hFunction->getMonthFromDate($fromDate) !!}">{!! (int)$hFunction->getMonthFromDate($fromDate) !!}</option>--}}
                                </select>
                                <span>/</span>
                                <select name="cbYearBegin" style="height: 25px;">
                                    <option value="{!! $currentYear !!}">{!! $currentYear !!}</option>
                                    {{--<option value="{!! $hFunction->getYearFromDate($fromDate) !!}">{!! $hFunction->getYearFromDate($fromDate) !!}</option>--}}
                                </select>
                                &emsp;
                                <select name="cbHoursBegin" style="height: 25px;">
                                    <option value="{!! $currentHour !!}">{!! $currentHour !!}</option>
                                    {{--<option value="">Chọn giờ</option>
                                    @for($i =1;$i<= 24; $i++)
                                        <option value="{!! $i !!}" @if($i == $currentHour) selected="selected" @endif>
                                            {!! $i !!}
                                        </option>
                                    @endfor--}}
                                </select>
                                <span>:</span>
                                <select name="cbMinuteBegin" style="height: 25px;">
                                    <option value="{!! $currentMinute !!}">{!! $currentMinute !!}</option>
                                    {{--@for($i =0;$i<= 55; $i = $i+5)
                                        <option value="{!! $i !!}">{!! $i !!}</option>
                                    @endfor--}}
                                </select>
                            </div>
                        </div>
                        <div class="form-group form-group-sm col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label class="col-sm-2 control-label">Chi chú:</label>

                            <div class="col-sm-10">
                                <input class="form-control" type="text" name="txtNote" value="">
                            </div>
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
