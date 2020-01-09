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
$moneyRequest = $dataSalaryBeforePayRequest->moneyRequest();
$dateRequest = $dataSalaryBeforePayRequest->dateRequest();
$checkDate = date('d-m-Y', strtotime($dateRequest));
$currentDate = date('d-m-Y');
?>
@extends('ad3d.components.container.container-8')
@section('qc_ad3d_container_content')
    <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
        <h3>XÁC ỨNG LƯƠNG </h3>
    </div>
    <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <form class="qc_ad3d_frm_confirm form-horizontal" name="qc_ad3d_frm_confirm" role="form" method="post"
              action="{!! route('qc.ad3d.salary.before_pay_request.confirm.post') !!}">
            <div class="row">
                <div class="notifyConfirm qc-color-red text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    @if($checkDate < $currentDate)
                        <span>Đã hết hạn duyệt</span>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group form-group-sm">
                        <label class="col-sm-3 control-label">Nhân viên:</label>

                        <div class="col-sm-9">
                            <input class="form-control" type="text" readonly="true"
                                   value="{!! $dataSalaryBeforePayRequest->work->companyStaffWork->staff->fullName() !!}"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group form-group-sm ">
                        <label class="col-sm-3 control-label">Ngày ứng:</label>

                        <div class="col-sm-9">
                            <input class="form-control" readonly="true"
                                   value="{!! date('d-m-Y', strtotime($dateRequest)) !!}"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group form-group-sm">
                        <label class="col-sm-3 control-label">Số tiền yêu cầu:</label>

                        <div class="col-sm-9">
                            <input class="form-control" type="text" readonly="true"
                                   value="{!! $hFunction->dotNumber($moneyRequest) !!}"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group form-group-sm">
                        <label class="col-sm-3 control-label">Số tiền chấp nhận:</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="text" name="txtMoneyConfirm" data-check="{!! $moneyRequest !!}"
                                   value="{!! $moneyRequest !!}"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group form-group-sm" style="border-top: 1px dotted grey;">
                <div class="col-sm-offset-3 col-sm-9">
                    @if($checkDate > $currentDate)
                        <div class="radio">
                            <label>
                                <input type="radio" name="txtAgreeStatus" value="1" checked>
                                Đồng ý
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="txtAgreeStatus" value="0">
                                Không đồng ý
                            </label>
                        </div>
                    @else
                        <div class="radio">
                            <label>
                                <input type="radio" name="txtAgreeStatusShow" value="0" checked disabled>
                                <input type="hidden" name="txtAgreeStatus" value="0">
                                Không đồng ý
                            </label>
                        </div>
                    @endif

                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group form-group-sm ">
                        <label class="col-sm-3 control-label">Ghi chú:</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="text" name="txtConfirmNote" value="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="qc-padding-top-20 qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="form-group form-group-sm text-center">
                    <div class="col-sm-12 col-sm-12 col-md-12 col-lg-12">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <input type="hidden" name="txtRequest" value="{!! $requestId !!}">
                        <button type="button" class="qc_save btn btn-primary btn-sm">Xác nhận</button>
                        <button type="button" class="qc_ad3d_container_close btn btn-default btn-sm">Đóng</button>
                    </div>
                </div>

            </div>
        </form>
    </div>
@endsection
