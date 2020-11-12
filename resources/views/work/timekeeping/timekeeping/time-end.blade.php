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
$dataWorkAllocation = $dataStaff->workAllocationActivityOfStaffReceive();

$timekeepingProvisionalId = $dataTimekeepingProvisional->timekeepingProvisionalId();
$timeBegin = $dataTimekeepingProvisional->timeBegin();
$note = $dataTimekeepingProvisional->note();
$yearBegin = (int)date('Y', strtotime($timeBegin));
$monthBegin = (int)date('m', strtotime($timeBegin));
$dayBegin = (int)date('d', strtotime($timeBegin));

$currentDay = $hFunction->currentDay();

$beginCheckDate = date('Y-m-d 08:00:00', strtotime($timeBegin));
$endCheck = $hFunction->datetimePlusDay($beginCheckDate, 1);
$currentDateCheck = $hFunction->carbonNow();

?>
@extends('components.container.container-6')
@section('qc_container_content')
    <div class="qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <h3 style="color: red;">BÁO GIỜ RA</h3>
            </div>
            @if($endCheck < $currentDateCheck )
                <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="padding: 30px 0 30px 0;">
                    <span class="qc-color-red">Hết hạn báo giờ ra</span>
                </div>
            @else
                <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <form class="qc_frm_time_end_add form" name="qc_frm_time_end_add" role="form"
                          method="post"
                          enctype="multipart/form-data"
                          action="{!! route('qc.work.timekeeping.timeEnd.post') !!}">
                        <div class="row">
                            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="font-weight: bold;">
                                <div class="frm_notify qc-font-size-16 form-group form-group-sm qc-color-red"></div>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label>Giờ ra:</label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" style="padding: 0;">
                                    <select class="form-control" name="cbHoursEnd" style="padding: 0;color: red;">
                                        <option value="">Giờ</option>
                                        @for($h =1;$h<= 24; $h++)
                                            <option value="{!! $h !!}" @if($h == 17) selected="selected" @endif >
                                                {!! $h !!} giờ
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" style="padding: 0;">
                                    <select class="form-control" name="cbMinuteEnd" style="padding: 0; color: red;">
                                        @for($i =0;$i<= 55; $i = $i+5)
                                            <option value="{!! $i !!}" @if($i == 30) selected="selected" @endif >
                                                {!! $i !!} phút
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" style="padding: 0;">
                                    <select class="form-control" name="cbDayEnd">
                                        @for($i = 1;$i<= 31; $i++)
                                            <option value="{!! $i !!}"
                                                    @if($dayBegin == $i) selected="selected" @endif>
                                                {!! $i !!}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" style="padding: 0;">
                                    <select class="form-control" name="cbMonthEnd" style="padding: 0;">
                                        @for($i = 1;$i<= 12; $i++)
                                            <option value="{!! $i !!}"
                                                    @if($monthBegin == $i) selected="selected" @endif>
                                                {!! $i !!}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="padding: 0;">
                                    <select class="form-control" name="cbYearEnd" style="padding: 0;">
                                        <option value="">Năm</option>
                                        <option value="{!! $yearBegin -1 !!}">
                                            {!! $yearBegin-1 !!}
                                        </option>
                                        <option value="{!! $yearBegin !!}" selected="selected">
                                            {!! $yearBegin !!}
                                        </option>
                                        <option value="{!! $yearBegin +1 !!}">
                                            {!! $yearBegin+1 !!}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <span style="padding: 5px; background-color: red; color: yellow;">PHẢI CÓ ẢNH BÁO CÁO</span>
                            </div>
                        </div>
                        <div class="row" style="padding-top: 5px; padding-bottom: 5px; border-bottom: 2px solid blue;">
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                <label>Ảnh báo cáo 1:</label>
                                <input type="file" class="txtTimekeepingImage_1 form-control"
                                       name="txtTimekeepingImage_1">
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                <label>Sản phẩm: </label><br/>
                                <select class="cbWorkAllocation_1 form-control" name="cbWorkAllocation_1">
                                    @if(count($dataWorkAllocation) > 0)
                                        <option value="0">Chọn sản phẩm</option>
                                        @foreach($dataWorkAllocation as $workAllocation)
                                            <option value="{!! $workAllocation->allocationId() !!}">
                                                {!! $workAllocation->product->productType->name() !!} -
                                                ({!! $workAllocation->product->order->name() !!})
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="0">Không có sản phẩm</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="row" style="padding-top: 5px; padding-bottom: 5px; border-bottom: 2px solid blue;">
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                <label>Ảnh ảnh báo 2:</label>
                                <input class="txtTimekeepingImage_2 form-control" type="file"
                                       name="txtTimekeepingImage_2">
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                <label>Sản phẩm: </label><br/>
                                <select class="cbWorkAllocation_2 form-control" name="cbWorkAllocation_2"
                                        style="height: 34px;">
                                    @if(count($dataWorkAllocation) > 0)
                                        <option value="0">Chọn sản phẩm</option>
                                        @foreach($dataWorkAllocation as $workAllocation)
                                            <option value="{!! $workAllocation->allocationId() !!}">
                                                {!! $workAllocation->product->productType->name() !!} -
                                                ({!! $workAllocation->product->order->name() !!})
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="0">Không có sản phẩm</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="row" style="padding-top: 5px; padding-bottom: 5px; border-bottom: 2px solid blue;">
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                <label>Ảnh báo cáo 3:</label>
                                <input type="file" class="txtTimekeepingImage_3 form-control"
                                       name="txtTimekeepingImage_3">
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                <label>Sản phẩm: </label><br/>
                                <select class="cbWorkAllocation_3 form-control" name="cbWorkAllocation_3"
                                        style="height: 34px;">
                                    @if(count($dataWorkAllocation) > 0)
                                        <option value="0">Chọn sản phẩm</option>
                                        @foreach($dataWorkAllocation as $workAllocation)
                                            <option value="{!! $workAllocation->allocationId() !!}">
                                                {!! $workAllocation->product->productType->name() !!} -
                                                ({!! $workAllocation->product->order->name() !!})
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="0">Không có sản phẩm</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label>Ghi chú:</label>
                                <input class="form-control" type="text" name="txtNote" value="{!! $note !!}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="txtAfternoonStatus"> Có làm trưa
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-center form-group form-group-sm">
                                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                    <input type="hidden" name="txtTimekeepingProvisional"
                                           value="{!! $timekeepingProvisionalId !!}">
                                    <button type="button" class="qc_save btn btn-sm btn-primary">XÁC NHẬN</button>
                                    <button type="reset" class="btn btn-sm btn-default">Nhập lại</button>
                                    <button type="button" class="qc_container_close btn btn-sm btn-default">Đóng
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            @endif

        </div>
    </div>
@endsection
