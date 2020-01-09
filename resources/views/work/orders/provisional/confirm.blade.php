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
$orderId = $dataOrder->orderId();
$totalPrice = $dataOrder->totalPrice();
$totalVAT = $dataOrder->totalMoneyOfVat();
$totalDiscount = $dataOrder->totalMoneyDiscount();
$totalPay = $totalPrice - $totalDiscount +$totalVAT;
?>
@extends('components.container.container-8')
@section('qc_container_content')
    <div class="qc_frm_work_orders_provision_confirm_wrap qc-padding-top-20 qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
                <h3>XÁC NHẬN ĐẶT HÀNG</h3>
            </div>
            <div class="qc-padding-top-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form class ="qc_frm_work_orders_provision_confirm form-horizontal" name="qc_frm_work_orders_provision_confirm" role="form"
                      method="post" enctype="multipart/form-data"
                      action="{!! route('qc.work.orders.provisional.confirm.post', $orderId) !!}">
                    <div class="row">
                        <div class="qc_notify_confirm text-center ol-sx-12 col-sm-12 col-md-12 col-lg-12">

                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label">Đơn hàng:</label>
                                <div class="col-sm-10">
                                    {!! $dataOrder->name() !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label">Tổng thanh toán:</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="txtTotalPay" readonly
                                           value="{!! $hFunction->currencyFormat($totalPay) !!}">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label">Cọc:</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="txtMoney" data-check-money="{!! $totalPay !!}" value=""
                                           onkeyup="qc_main.showFormatCurrency(this);"
                                           title="Nhập số tiền">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label">Ngày nhận:</label>
                                <div class="col-sm-10">
                                    <input id="txtDateReceive" type="hidden" name="txtDateReceive" class="form-control"
                                           value="{!! date('Y-m-d',strtotime($hFunction->carbonNow())) !!}">
                                    <input type="text" name="txtDateReceiveShow" class="form-control"
                                           disabled="disabled"
                                           value="{!! date('Y-m-d',strtotime($hFunction->carbonNow())) !!}" style="height: 25px;"
                                           placeholder="Ngày nhận">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm">
                                <label class="col-sm-2 control-label">Ngày giao:</label>
                                <div class="col-sm-10">
                                    <input id="txtDateDelivery" type="text" name="txtDateDelivery" class="form-control"
                                           value="" style="height: 25px;" placeholder="Ngày giao">
                                    <script type="text/javascript">
                                        qc_main.setDatepicker('#txtDateDelivery');
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="text-center form-group form-group-sm">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <button type="button" class="qc_save btn btn-sm btn-primary">
                                    Đặt hàng
                                </button>
                                <button type="reset" class="btn btn-sm btn-default">
                                    Nhập lại
                                </button>
                                <a class="qc_container_close btn btn-sm btn-default">
                                    Đóng
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
