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

$allocationId = $dataWorkAllocation->allocationId();
$allocationDate = $dataWorkAllocation->allocationDate();
$yearBegin = (int)date('Y', strtotime($allocationDate));
$monthBegin = (int)date('m', strtotime($allocationDate));
$dayBegin = (int)date('d', strtotime($allocationDate));

$currentDay = date('d');
$currentMonth = date('m');
$currentYear = date('Y');
$currentHours = date('H');
$currentMinute = date('i');

?>
@extends('components.container.container-6')
@section('qc_container_content')
    <div class="qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
                <h3 style="color: red;">BÁO CÁO CÔNG VIỆC</h3>
            </div>
            <div class="qc-padding-top-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form class="qc_frm_Work_allocation_report form-horizontal" name="qc_frm_time_end_add" role="form"
                      method="post" enctype="multipart/form-data"
                      action="{!! route('qc.work.work_allocation.work_allocation.report.post') !!}">
                    <div class="row">
                        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="text-center frm_notify form-group form-group-sm qc-color-red"></div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm">
                                <label class="col-sm-3 control-label">Ngày - Giờ:</label>

                                <div class="col-sm-9">
                                    <select name="cbHoursReport" class="text-right col-xs-2 col-sm-2 col-md-2 col-lg-2" style="height: 34px; padding: 0; color: red;">
                                        <option value="">Giờ</option>
                                        @for($h =1;$h<= 24; $h++)
                                            <option value="{!! $h !!}"
                                                    @if($currentHours == $h) selected="selected" @endif>
                                                {!! $h !!} Giờ
                                            </option>
                                        @endfor
                                    </select>
                                    <select name="cbMinuteReport" class="text-right col-xs-2 col-sm-2 col-md-2 col-lg-2" style="height: 34px; padding: 0; color: red;">
                                        @for($i =0;$i<= 55; $i = $i+5)
                                            <option value="{!! $i !!}"
                                                    @if($currentMinute == $i) selected="selected" @endif>
                                                {!! $i !!} Phút
                                            </option>
                                        @endfor
                                    </select>
                                    <select name="cbDayReport" class="text-right col-xs-2 col-sm-2 col-md-2 col-lg-2" style="height: 34px; padding: 0;">
                                        @for($i = 1;$i<= 31; $i++)
                                            <option value="{!! $i !!}"
                                                    @if($currentDay == $i) selected="selected" @endif>
                                                Ngày {!! $i !!}
                                            </option>
                                        @endfor
                                    </select>
                                    <select name="cbMonthReport" class="text-right col-xs-2 col-sm-2 col-md-2 col-lg-2" style="height: 34px; padding: 0;">
                                        @for($i = 1;$i<= 12; $i++)
                                            <option value="{!! $i !!}"
                                                    @if($currentMonth == $i) selected="selected" @endif>
                                                {!! $i !!}
                                            </option>
                                        @endfor
                                    </select>
                                    <select name="cbYearReport" class="text-right col-xs-4 col-sm-4 col-md-4 col-lg-4" style="height: 34px; padding: 0;">
                                        <option value="{!! $currentYear - 1 !!}">
                                            {!! $currentYear - 1 !!}
                                        </option>
                                        <option value="{!! $currentYear !!}" selected="selected">
                                            {!! $currentYear !!}
                                        </option>
                                        <option value="{!! $currentYear + 1 !!}">
                                            {!! $currentYear + 1 !!}
                                        </option>
                                    </select>

                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Trạng thái báo:</label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="cbReportStatus">
                                        <option value="2">Báo cáo tiến độ</option>
                                        <option value="1">Báo cáo hoàn thành</option>
                                        <option value="0">Báo cáo không hoàn thành</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm">
                                <label class="col-sm-3 control-label">Ảnh báo cáo 1:</label>

                                <div class="col-sm-9">
                                    <input type="file" class="txtReportImage" name="txtReportImage[]">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm">
                                <label class="col-sm-3 control-label">Ảnh báo cáo 2:</label>

                                <div class="col-sm-9">
                                    <input class="txtReportImage" type="file" name="txtReportImage[]">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm">
                                <label class="col-sm-3 control-label">Ảnh báo cáo 3:</label>

                                <div class="col-sm-9">
                                    <input type="file" class="txtReportImage" name="txtReportImage[]">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="text-center form-group form-group-sm">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <input type="hidden" name="txtWorkAllocation" value="{!! $allocationId !!}">
                                <button type="button" class="qc_save btn btn-sm btn-primary">Lưu</button>
                                <button type="reset" class="btn btn-sm btn-default">Nhập lại</button>
                                <button type="button" class="qc_container_close btn btn-sm btn-default">Đóng</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
