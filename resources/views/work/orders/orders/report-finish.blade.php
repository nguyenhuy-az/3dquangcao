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
$orderId = $dataOrder->orderId();

?>
@extends('components.container.container-6')
@section('qc_container_content')
    <div class="qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
                <h3 style="color: red;">XÁC NHẬN HOÀN THÀNH BÀN GIAO KHÁCH HÀNG </h3>
                <h4 style="color: blue;">
                    "{!! $dataOrder->name() !!}"
                </h4>
            </div>
        </div>
        @if($dataOrder->checkFinishPayment())
            <div class="row">
                <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <form class="frmOrdersOrderReportFinish" role="form" method="post"
                          action="{!! route('work.orders.order.report.finish.post', $orderId) !!}">
                        <div class="row">
                            <div class="notifyConfirm qc-font-size-20 qc-color-red text-center col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>
                        </div>
                        <div class="row">
                            <div class="text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group">
                                    <label class="qc-font-size-14">{!! $dataStaffLogin->fullName() !!}</label>
                                    <b style="color:brown;">Chịu trách nhiệm khi báo hoàn thành công trình này</b>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="text-left col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group">
                                    <label style="text-decoration: underline; background-color: red; padding: 3px; color: yellow;">Lưu ý:</label>
                                    <br/>
                                    <b class="qc-color-red">TẤT CẢ CÁC SẢN PHẨM SẼ KẾT THÚC THEO CÔNG TRÌNH NẾU CÓ</b>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="text-center qc-padding-top-20 qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <button type="button" class="qc_save btn btn-sm btn-primary">Đồng ý</button>
                                <button type="button" class="qc_container_close btn btn-sm btn-default">Đóng</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="margin-top: 10px;">
                <label style="background-color: red; color: yellow; padding: 10px;">CHỈ ĐƯỢC BÁO HOÀN THÀNH KHI ĐƠN HÀNG ĐÃ THANH TOÁN
                    XONG</label>
                <br/>
                <button type="button" class="qc_container_close btn btn-sm btn-primary">Đóng</button>
            </div>
        @endif
    </div>
@endsection
