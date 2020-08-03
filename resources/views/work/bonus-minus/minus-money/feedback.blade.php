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
$currentDateCheck = $hFunction->carbonNow();
?>
@extends('components.container.container-6')
@section('qc_container_content')
    <div class="qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
                <h3 style="color: red;">GỬI PHẢN HỒI PHẠT</h3>
            </div>
            <div class="qc-padding-top-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form id="qcFrmMinusMoneyFeedback" class="form-horizontal" name="qcFrmMinusMoneyFeedback" role="form"
                      method="post" enctype="multipart/form-data"
                      action="{!! route('qc.work.minus_money.feedback.post') !!}">
                    <div class="row">
                        <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="font-weight: bold;">
                            <div class="frm_notify qc-font-size-16 form-group form-group-sm qc-color-red"></div>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label>Lý do:</label>
                            <input type="text" class="form-control" disabled="disabled"
                                   value="{!! $dataMinusMoney->punishContent->name() !!}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label>Nội dung:</label>
                            <i class="glyphicon glyphicon-star" style="color: red;"></i>
                            <input class="txtFeedbackContent form-control" type="text" name="txtFeedbackContent"
                                   value="">
                        </div>
                    </div>
                    <div class="row" style="padding-top: 5px; margin-bottom: 10px; border-top: 1px solid #d7d7d7;">
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <label>Ảnh xác nhận:</label>
                            <input type="file" class="txtFeedbackImage" name="txtFeedbackImage">
                        </div>
                    </div>
                    <div class="row">
                        <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="text-center form-group form-group-sm">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <input type="hidden" name="txtMinusId" value="{!! $dataMinusMoney->minusId() !!}">
                                <button type="button" class="qc_save btn btn-sm btn-primary">GỬI</button>
                                <button type="reset" class="btn btn-sm btn-default">Nhập lại</button>
                                <button type="button" class="qc_container_close btn btn-sm btn-default">Đóng
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
