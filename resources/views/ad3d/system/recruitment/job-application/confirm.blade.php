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
$jobApplication = $dataJobApplication->jobApplicationId();
$currentDay = (int)date('d');
$currentMonth = (int)date('m');
$currentYear = (int)date('Y');
?>
@extends('ad3d.components.container.container-6')
@section('qc_ad3d_container_content')
    <div class="ad3dFrmConfirmJobApplication qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <h3 style="color: red;">DUYỆT HỒ SƠ TUYỂN DỤNG</h3>
            </div>
            <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form class="qc_frm_time_end_add form-horizontal" name="qc_frm_time_end_add" role="form"
                      method="post" enctype="multipart/form-data"
                      action="{!! route('qc.work.timekeeping.timeEnd.post') !!}">
                    <div class="row">
                        <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="font-weight: bold;">
                            <div class="frm_notify qc-font-size-16 form-group form-group-sm qc-color-red"></div>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label>Giờ vào:</label>
                            <input class="form-control" type="text" disabled="disabled" value="{!! date('d-m-Y H:i', strtotime($timeBegin)) !!}" >
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label>Ngày ra:</label>
                            <select name="cbDay" style="height: 34px;">
                                <option value="">Ngày</option>
                                @for($d = 1;$d<= 31; $d++)
                                    <option value="{!! $d !!}">
                                        {!! $d !!}
                                    </option>
                                @endfor
                            </select>
                            <select name="cbMonth" style="height: 34px;">
                                @for($m = 1;$m<= 12; $m++)
                                    <option value="{!! $m !!}"
                                            @if($currentMonth == $m) selected="selected" @endif>
                                        {!! $m !!}
                                    </option>
                                @endfor
                            </select>
                            <select name="cbYear" style="height: 34px;">
                                <option value="{!! $currentYear !!}" selected="selected">
                                    {!! $currentYear !!}
                                </option>
                                <option value="{!! $currentYear +1 !!}">
                                    {!! $currentYear+1 !!}
                                </option>
                            </select>
                            &emsp;
                            <select name="cbHours" style="height: 30px; color: red;">
                                <option value="">Giờ</option>
                                @for($h =1;$h<= 24; $h++)
                                    <option value="{!! $h !!}" @if($8 == 8) selected="selected" @endif >
                                        {!! $h !!}
                                    </option>
                                @endfor
                            </select>
                            <select name="cbMinuteEnd" style="height: 30px; color: red;">
                                <option value="0">00</option>
                                <option value="30">30</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="text-center form-group form-group-sm">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <input type="hidden" name="txtTimekeepingProvisional"
                                       value="{!! $jobApplicationId !!}">
                                <button type="button" class="qc_save btn btn-sm btn-primary">XÁC NHẬN</button>
                                <button type="button" class="qc_ad3d_container_close btn btn-sm btn-default">Đóng
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
