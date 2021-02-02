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

$fromDate = $dataWork->fromDate();
$workMonth = (int)$hFunction->getMonthFromDate($fromDate);
$workYear = (int)$hFunction->getYearFromDate($fromDate);

$dayCurrent = (int)date('d');
?>
@extends('components.container.container-6')
@section('qc_container_content')
    <div class="qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
                <h3>XIN NGHỈ</h3>
            </div>
            <div class="qc-padding-top-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form class="frm_off_work_add form-horizontal" name="frm_off_work_add" role="form" method="post"
                      action="{!! route('qc.work.timekeeping.offWork.post') !!}">
                    <div class="row">
                        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="frm_notify text-center form-group qc-color-red"></div>
                        </div>
                        <div class="form-group form-group-sm col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label class="col-sm-2 control-label">Ngày: </label>
                            <div class="col-sm-10">
                                <select name="cbDayOff" style="margin-top: 5px; height: 30px;">
                                    @for($i = $hFunction->getDayFromDate($fromDate);$i<= 31; $i++)
                                        <option value="{!! $i !!}"
                                                @if(($dayCurrent + 1) == $i) selected="selected" @endif>
                                            {!! $i !!}
                                        </option>
                                    @endfor
                                </select>
                                <span>/</span>
                                <select name="cbMonthOff" style="margin-top: 5px; height: 30px;">
                                    @for($i=1; $i <=12; $i++)
                                        <option @if($workMonth == $i) selected="selected" @endif value="{!! $i !!}">
                                            {!! $i !!}
                                        </option>
                                    @endfor
                                </select>
                                <span>/</span>
                                <select name="cbYearOff" style="margin-top: 5px; height: 30px;">
                                    <option selected="selected" value="{!! $workYear !!}">{!! $workYear !!}</option>
                                    <option value="{!! $workYear + 1 !!}">{!! $workYear + 1 !!}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group form-group-sm col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label class="col-sm-2 control-label">Số ngày:</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="cbNumberOff" style="margin-top: 5px; height: 30px;">
                                    @for($i=1; $i <=30; $i++)
                                        <option value="{!! $i !!}">
                                            {!! $i !!}
                                        </option>
                                    @endfor
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
                                <button type="button" class="qc_save btn btn-sm btn-primary">GỬI</button>
                                <button type="button" class="qc_container_close btn btn-sm btn-default">Đóng</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
