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
$orderId = $dataOrder->orderId();
$totalMoneyUnpaid = $dataOrder->totalMoneyUnpaid()
?>
@extends('components.container.container-6')
@section('qc_container_content')
    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 1px dashed brown;">
            <h3 style="color: red">THANH TOÁN</h3>
        </div>
        <div class="qc-padding-top-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <form id ="qc_frm_work_orders_payment" class="qc_frm_work_orders_payment form" name="qc_frm_work_orders_payment" role="form"
                  method="post" enctype="multipart/form-data"
                  action="{!! route('qc.work.orders.payment.post', $orderId) !!}">
                <div class="row">
                    <div class="qc_notify col-sx-12 col-sm-12 col-md-12 col-lg-12"></div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group form-group-sm">
                            <label class="control-label">Đơn hàng:</label>
                            <input class="form-control" type="text" readonly value="{!! $dataOrder->name() !!}">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group form-group-sm">
                            <label class="control-label">Tiền Còn lại:</label>
                            <input class="form-control" type="text" readonly
                                   value="{!! $hFunction->currencyFormat($totalMoneyUnpaid) !!}">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group form-group-sm" style="color: red;">
                            <label class="control-label">Số tiền:</label>
                            <input class="form-control" type="text" name="txtMoney" data-money="{!! $totalMoneyUnpaid !!}" value="{!! $hFunction->currencyFormat($totalMoneyUnpaid) !!}"
                                   onkeyup="qc_main.showFormatCurrency(this);"
                                   title="Nhập số tiền">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group form-group-sm">
                            <label class="control-label">Tên:</label>
                            <input class="form-control" type="text" name="txtName"
                                   value="{!! $dataOrder->customer->name() !!}">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group form-group-sm">
                            <label class="control-label">Số điện thoại:</label>
                            <input class="form-control" type="text" name="txtPhone"
                                   value="{!! $dataOrder->customer->phone() !!}">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group form-group-sm">
                            <label class="control-label">Ghi chú:</label>
                            <input class="form-control" type="text" name="txtNote" value="">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="text-center form-group form-group-sm">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <button type="button" class="qc_save btn btn-sm btn-primary">
                                THANH TOÁN
                            </button>
                            <button type="reset" class="btn btn-sm btn-default">
                                NHẬP LẠI
                            </button>
                            <button type="button" class="qc_container_close btn btn-sm btn-default">ĐÓNG</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
