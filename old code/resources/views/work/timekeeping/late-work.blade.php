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
                <h3>XIN TRỄ</h3>
            </div>
            <div class="qc-padding-top-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form class="frm_late_work_add form-horizontal" name="frm_late_work_add" role="form" method="post"
                      action="{!! route('qc.work.timekeeping.lateWork.post') !!}">
                    <div class="row">
                        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="frm_notify text-center form-group qc-color-red"></div>
                        </div>
                        <div class="form-group form-group-sm col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label class="col-sm-2 control-label">Ngày: </label>
                            <div class="col-sm-10">
                                <select name="cbDayLate" style="height: 30px;">
                                    @for($i = 1;$i<= 31; $i++)
                                        <option value="{!! $i !!}"
                                                @if(($dayCurrent + 1) == $i) selected="selected" @endif>
                                            {!! $i !!}
                                        </option>
                                    @endfor
                                </select>
                                <span>/</span>
                                <select name="cbMonthLate" style="height: 30px;">
                                    @for($i=1; $i <=12; $i++)
                                        <option @if($workMonth == $i) selected="selected" @endif value="{!! $i !!}">
                                            {!! $i !!}
                                        </option>
                                    @endfor
                                </select>
                                <span>/</span>
                                <select name="cbYearLate" style="height: 30px;">
                                    <option selected="selected" value="{!! $workYear !!}">{!! $workYear !!}</option>
                                    <option value="{!! $workYear + 1 !!}">{!! $workYear + 1 !!}</option>
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
