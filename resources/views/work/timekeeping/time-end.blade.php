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
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
                <h3>GIỜ RA</h3>
            </div>
            @if($endCheck < $currentDateCheck )
                <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="padding: 30px 0 30px 0;">
                    <span class="qc-color-red">Hết hạn báo giờ ra</span>
                </div>
            @else
                <div class="qc-padding-top-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <form class="qc_frm_time_end_add form-horizontal" name="qc_frm_time_end_add" role="form"
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
                                <label>Giờ vào:</label>
                                <input type="text" disabled="disabled" value="{!! date('d-m-Y H:i', strtotime($timeBegin)) !!}" >
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label>Ngày ra:</label>
                                <select name="cbDayEnd" style="height: 30px;">
                                    @for($i = 1;$i<= 31; $i++)
                                        <option value="{!! $i !!}"
                                                @if($dayBegin == $i) selected="selected" @endif>
                                            {!! $i !!}
                                        </option>
                                    @endfor
                                </select>
                                <span>/</span>
                                <select name="cbMonthEnd" style="height: 30px;">
                                    @for($i = 1;$i<= 12; $i++)
                                        <option value="{!! $i !!}"
                                                @if($monthBegin == $i) selected="selected" @endif>
                                            {!! $i !!}
                                        </option>
                                    @endfor
                                </select>
                                <span>/</span>
                                <select name="cbYearEnd" style="height: 30px;">
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
                                &emsp;
                                <select name="cbHoursEnd" style="height: 30px;">
                                    <option value="">Giờ</option>
                                    @for($i =1;$i<= 24; $i++)
                                        <option value="{!! $i !!}"
                                                @if($i == 17) selected="selected" @endif >{!! $i !!}</option>
                                    @endfor
                                </select>
                                <span>:</span>
                                <select name="cbMinuteEnd" style="height: 30px;">
                                    @for($i =0;$i<= 55; $i = $i+5)
                                        <option value="{!! $i !!}"
                                                @if($i == 30) selected="selected" @endif >{!! $i !!}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="row" style="padding-top: 5px; margin-bottom: 10px; border-top: 1px solid #d7d7d7;">
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                <label>Ảnh xác nhận 1:</label>
                                <input type="file" class="txtTimekeepingImage" name="txtTimekeepingImage_1">
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                <label>Sản phẩm: </label><br/>
                                <select class="cbWorkAllocation_1" name="cbWorkAllocation_1">
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
                        <div class="row" style="padding-top: 5px; margin-bottom: 10px; border-top: 1px solid #d7d7d7;">
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                <label>Ảnh xác nhận 2:</label>
                                <input class="txtTimekeepingImage_2" type="file" name="txtTimekeepingImage_2">
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                <label>Sản phẩm: </label><br/>
                                <select class="cbWorkAllocation_2" name="cbWorkAllocation_2">
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
                        <div class="row" style="padding-top: 5px; margin-bottom: 10px; border-top: 1px solid #d7d7d7;">
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                <label>Ảnh xác nhận 3:</label>
                                <input type="file" class="txtTimekeepingImage_3" name="txtTimekeepingImage_3">
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                <label>Sản phẩm: </label><br/>
                                <select class="cbWorkAllocation_3" name="cbWorkAllocation_3">
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
                                    <button type="button" class="qc_save btn btn-sm btn-primary">Lưu</button>
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
