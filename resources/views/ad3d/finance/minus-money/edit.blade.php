<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 *
 * dataStaffSalaryBasic
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();

$fromDate = $dataWorkSelect->fromDate();
?>
@extends('ad3d.components.container.container-6')
@section('qc_ad3d_container_content')
    <div class="qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <h3>SỬA</h3>
            </div>
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form class="frmEdit" name="frmEdit" role="form" method="post"
                      action="{!! route('qc.ad3d.finance.minus-money.edit.post',$dataMinusMoney->minusId()) !!}">
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
                         @if($mobileStatus) style="padding: 0 0;" @endif>
                        <div class="frm_notify text-center form-group qc-color-red"></div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                             @if($mobileStatus) style="padding: 0 0;" @endif>
                            <div class="form-group qc-padding-none">
                                <label>Nhân Viên:</label>
                                <input class="form-control" readonly value="{!! $dataWorkSelect->staff->fullName() !!}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                             @if($mobileStatus) style="padding: 5px 0 5px 0;" @endif>
                            <label>Ngày: <i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                             @if($mobileStatus) style="padding: 5px 0 5px 0;" @endif>
                            <div class="form-group">
                                <select name="cbDay" style="margin-top: 5px; height: 30px;">
                                    @for($i = $hFunction->getDayFromDate($fromDate);$i<= 31; $i++)
                                        <option value="{!! $i !!}" @if($hFunction->getDayFromDate($dataMinusMoney->dateMinus()) == $i) selected="selected" @endif >{!! $i !!}</option>
                                    @endfor
                                </select>
                                /
                                <select name="cbMonth" style="margin-top: 5px; height: 30px;">
                                    {{--<option name="cbMonthIn" value="">Tháng</option>--}}
                                    <option value="{!! (int)$hFunction->getMonthFromDate($fromDate) !!}">{!! (int)$hFunction->getMonthFromDate($fromDate) !!}</option>
                                </select>
                                /
                                <select name="cbYear" style="margin-top: 5px; height: 30px;">
                                    {{--<option value="">Năm</option>--}}
                                    <option value="{!! $hFunction->getYearFromDate($fromDate) !!}">{!! $hFunction->getYearFromDate($fromDate) !!}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                             @if($mobileStatus) style="padding: 0 0;" @endif>
                            <div class="form-group qc-padding-none">
                                <label>Số tiền (VND):</label>
                                <input type="number" class="form-control" name="txtMoney" placeholder="Nhập số tiền"
                                       value="{!! $dataMinusMoney->money() !!}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                             @if($mobileStatus) style="padding: 0 0;" @endif>
                            <div class="form-group qc-padding-none">
                                <label>Ghi chú:</label>
                                <input type="text" class="form-control" name="txtReason"
                                       value="{!! $dataMinusMoney->reason() !!}">
                            </div>
                        </div>
                    </div>
                    <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <button type="button" class="qc_save btn btn-primary">Lưu</button>
                        <button type="button" class="qc_ad3d_container_close btn btn-default">Đóng</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
