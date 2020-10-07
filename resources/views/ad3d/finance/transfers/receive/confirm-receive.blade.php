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
?>
@extends('ad3d.components.container.container-4')
@section('qc_ad3d_container_content')
    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
                <h3>XÁC NHẬN BÀN GIAO TIỀN  </h3>
            </div>
        </div>
        <div class="row">
            <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form id="frmAd3dTransferConfirmReceive" class="frmAd3dTransferConfirmReceive" role="form" method="post"
                      action="{!! route('qc.ad3d.finance.transfers.confirm.post', $dataTransfers->transfersId()) !!}">
                    <div class="row">
                        <div class="frm_notify qc-color-red text-center col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                             @if($mobileStatus) style="padding: 0 0;" @endif>
                            <div class="text-center form-group form-group-sm">
                                <span>Tôi đã nhận số tiền</span>
                                <b> {!! $hFunction->currencyFormat($dataTransfers->money()) !!}</b>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                             @if($mobileStatus) style="padding: 0 0;" @endif>
                            <div class="text-center form-group form-group-sm ">
                                <em class="qc-color-red">Sau khi xác nhận là sẽ không được thay đổi</em>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="text-center qc-padding-top-20 qc-padding-bot-20  qc-border-none col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <button type="button" class="qc_save btn btn-sm btn-primary">Xác nhận</button>
                            <button type="button" class="qc_ad3d_container_close btn btn-sm btn-default">Đóng</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
