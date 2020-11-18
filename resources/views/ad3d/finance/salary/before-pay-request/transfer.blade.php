<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 */
/*
 *dataCompany
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$requestId = $dataSalaryBeforePayRequest->requestId();
$moneyConfirm = $dataSalaryBeforePayRequest->moneyConfirm();
$dateRequest = $dataSalaryBeforePayRequest->dateRequest();
?>
@extends('ad3d.components.container.container-4')
@section('qc_ad3d_container_content')
    <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
        <h3>CHUYỂN TIỀN </h3>
    </div>
    <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <form class="qc_ad3d_salary_frm_transfer" role="form" method="post"
              action="{!! route('qc.ad3d.salary.before_pay_request.transfer.post') !!}">
            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group form-group-sm notifyConfirm text-center qc-color-red qc-padding-top-20"></div>
                </div>
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group form-group-sm">
                        <label>Nhân viên</label>
                        <input type="text" class="form-control" readonly="true"
                               value="{!! $dataSalaryBeforePayRequest->work->companyStaffWork->staff->fullName() !!}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group form-group-sm">
                        <label>Ngày:</label>
                        <select name="cbDay" style="margin-top: 5px; height: 30px;">
                            @for($i =0;$i<= 31; $i++)
                                <option value="{!! $i !!}"
                                        @if($i == $hFunction->getDayFromDate($dateRequest)) selected="selected" @endif>
                                    {!! $i !!}
                                </option>
                            @endfor
                        </select>
                        <span>/</span>
                        <select name="cbMonth" style="margin-top: 5px; height: 30px;">
                            <option value="{!! (int)$hFunction->getMonthFromDate($dateRequest) !!}">{!! (int)$hFunction->getMonthFromDate($dateRequest) !!}</option>
                        </select>
                        <span>/</span>
                        <select name="cbYear" style="margin-top: 5px; height: 30px;">
                            <option value="{!! $hFunction->getYearFromDate($dateRequest) !!}">{!! $hFunction->getYearFromDate($dateRequest) !!}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group form-group-sm qc-padding-none">
                        <label>Số tiền (VND):</label>
                        <input type="number" class="form-control" name="txtMoneyConfirm" readonly
                               value="{!! $moneyConfirm !!}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group qc-padding-none">
                        <label>Ghi chú: </label>
                        <input type="text" class="form-control" name="txtDescription" value="">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group form-group-sm">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <input type="hidden" name="txtRequest" value="{!! $requestId !!}">
                        <button type="button" class="qc_save btn btn-sm btn-primary">Chuyển</button>
                        <button type="button" class="qc_ad3d_container_close btn btn-sm btn-default">Đóng</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
