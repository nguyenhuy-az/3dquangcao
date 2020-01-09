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
@extends('components.container.container-6')
@section('qc_container_content')
    <div class="qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
                <h3>SỬA THÔNG TIN THANH TOÁN </h3>
            </div>
        </div>
        <div class="row">
            <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form id="qc_work_order_frm_pay_edit" role="form" method="post"
                      action="{!! route('qc.work.orders.info.pay.edit.post', $dataOrderPay->payId()) !!}">
                    <div class="row">
                        <div class="frm_info_edit_notify qc-color-red text-center col-xs-12 col-sm-12 col-md-6 col-lg-6"></div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group qc-padding-none">
                                <label>Tên người Thanh toán:</label>
                                <input type="text" class="form-control" name="txtPayName"
                                       placeholder="Người liên hệ" style="height: 25px;"
                                       value="{!! $dataOrderPay->payerName() !!}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group qc-padding-none">
                                <label>Số điện thoại:</label>
                                <input type="text" class="form-control" name="txtPayPhone"
                                       placeholder="Người liên hệ" style="height: 25px;"
                                       value="{!! $dataOrderPay->payerPhone() !!}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group qc-padding-none">
                                <label>Số tiền:</label>
                                <input type="text" class="form-control" name="txtPayMoney"
                                       placeholder="Người liên hệ" style="height: 25px;"
                                       onkeyup="qc_main.showFormatCurrency(this);"
                                       value="{!! $hFunction->currencyFormat($dataOrderPay->money()) !!}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="text-center qc-padding-top-20 qc-padding-bot-20  qc-border-none col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <button type="button" class="qc_save btn btn-sm btn-primary">Xác nhận</button>
                            <button type="reset" class="btn btn-sm btn-default">NHập lại</button>
                            <button type="button" class="qc_container_close btn btn-sm btn-default">Đóng</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
