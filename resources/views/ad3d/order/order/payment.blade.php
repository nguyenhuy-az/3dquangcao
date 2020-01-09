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
$urlReferer = $hFunction->getUrlReferer();
$mobileStatus = $mobile->isMobile();
$orderId = $dataOrder->orderId();
?>
@extends('ad3d.order.order.index')
@section('qc_ad3d_order_order')
    <div class="qc-padding-top-20 qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
                <h3>THANH TOÁN</h3>
            </div>
            <div class="qc-padding-top-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form id="qc_frm_work_orders_payment form-horizontal" name="qc_frm_work_orders_payment" role="form"
                      method="post" enctype="multipart/form-data"
                      action="{!! route('qc.ad3d.order.order.payment.post', $orderId) !!}">
                    <div class="row">
                        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            @if (Session::has('notifyAdd'))
                                <div class="form-group text-center qc-color-red">
                                    {!! Session::get('notifyAdd') !!}
                                    <?php
                                    Session::forget('notifyAdd');
                                    ?>
                                </div>
                            @endif
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label">Đơn hàng:</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" readonly value="{!! $dataOrder->name() !!}">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label">Tiền Còn lại:</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" readonly value="{!! $hFunction->dotNumber($dataOrder->totalMoneyUnpaid()) !!}">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label">Số tiền:</label>

                                <div class="col-sm-10">
                                    <input class="form-control" type="number" name="txtMoney" value=""
                                           title="Nhập số tiền">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label">Tên:</label>

                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="txtName"
                                           value="{!! $dataOrder->customer->name() !!}">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label">Số điện thoại:</label>

                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="txtPhone"
                                           value="{!! $dataOrder->customer->phone() !!}">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label">Ghi chú:</label>

                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="txtNote" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="text-center form-group form-group-sm">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <button type="submit" class=" btn btn-sm btn-primary">
                                    Thanh toán
                                </button>
                                <button type="reset" class="btn btn-sm btn-default">
                                    Nhập lại
                                </button>
                                <a type="button" class="btn btn-sm btn-default" href="{!! $urlReferer !!}">Đóng</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection