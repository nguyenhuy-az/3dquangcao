<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
?>
@extends('ad3d.components.container.container-6')
@section('qc_ad3d_container_content')
    <div class="qc-padding-top-20 qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
            <h3>CHI TIẾT THANH TOÁN</h3>
        </div>

        <div class="row">
            <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <em>Đơn hàng:</em>
                <b>{!! $dataOrderPay->order->name() !!}</b>
            </div>
            <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <em>Ngày :</em>
                <b>{!! $dataOrderPay->datePay() !!}</b>
            </div>
            <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <em>Số tiền :</em>
                <b>{!! $hFunction->dotNumber($dataOrderPay->money()) !!}</b>
            </div>
            <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <em>Người thu:</em>
                <b>{!! $dataOrderPay->staff->fullName() !!}</b>
            </div>
        </div>
        <div class="row">
            <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <a class="qc_ad3d_container_close btn btn-primary">
                    Đóng
                </a>
            </div>
        </div>
    </div>

@endsection
