<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 *
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
if (empty($selectOrderId)) {
    $dataOrderSelected = null;
} else {
    $dataOrderSelected = $modelOrder->getInfo($selectOrderId);
}
?>
@extends('ad3d.finance.order-payment.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <h3>THANH TOÁN HÓA ĐƠN</h3>
        </div>
        @if(count($dataOrder) > 0)
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form id="frmAd3dOrderPayAdd" role="form" method="post" enctype="multipart/form-data"
                      action="{!! route('qc.ad3d.finance.order-payment.add.post') !!}">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            @if (Session::has('notifyAdd'))
                                <div class="form-group text-center qc-color-red">
                                    {!! Session::get('notifyAdd') !!}
                                    <?php
                                    Session::forget('notifyAdd');
                                    ?>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                                     style="border-bottom: 1px solid #C2C2C2;">
                                    <i class="glyphicon glyphicon-pencil qc-color-green"></i>
                                    <span class="qc-font-size-16 qc-color-green">Đơn hàng</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6"
                                     @if($mobileStatus) style="padding: 0 0;" @endif>
                                    <div class="form-group form-group-sm">
                                        <label>Đơn hàng: <i
                                                    class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                        <select class="cbOrder form-control" name="cbOrder" data-href="{!! route('qc.ad3d.finance.order-payment.add.get') !!}">
                                            <option value="">Chọn đơn hàng</option>
                                            @if(count($dataOrder)>0)
                                                @foreach($dataOrder as $order)
                                                    <option value="{!! $order->orderId() !!}"
                                                            @if($order->orderId()==$selectOrderId) selected="selected" @endif>
                                                        {!! $order->orderCode().' - '.$order->name() !!}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            @if(count($dataOrderSelected)>0)
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2"
                                         @if($mobileStatus) style="padding: 0 0;" @endif>
                                        <div class="form-group form-group-sm">
                                            <label>Tổng tiền: </label>
                                            <input type="text" class="form-control" name="txtTotalPrice" readonly
                                                   value="{!! $hFunction->currencyFormat($dataOrderSelected->totalMoneyPayment()) !!}">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2"
                                         @if($mobileStatus) style="padding: 0 0;" @endif>
                                        <div class="form-group form-group-sm">
                                            <label>Đã thanh toán:</label>
                                            <input type="text" class="form-control" name="txtTotalPaid" readonly
                                                   value="{!! $hFunction->currencyFormat($dataOrderSelected->totalPaid()) !!}">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2"
                                         @if($mobileStatus) style="padding: 0 0;" @endif>
                                        <div class="form-group form-group-sm">
                                            <label>Còn lại:</label>
                                            <input type="text" class="txtTotalUnpaid form-control" name="txtTotalUnpaid" data-unpaid="{!! $dataOrderSelected->totalUnpaid() !!}" readonly
                                                   value="{!! $hFunction->currencyFormat($dataOrderSelected->totalUnpaid()) !!}">
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        @if(count($dataOrderSelected) > 0)
                            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                                         style="border-bottom: 1px solid #C2C2C2;">
                                        <i class="glyphicon glyphicon-pencil qc-color-green"></i>
                                        <span class="qc-font-size-16 qc-color-green">Thanh toán</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4"
                                         @if($mobileStatus) style="padding: 0 0;" @endif>
                                        <div class="form-group form-group-sm qc-padding-none">
                                            <label>Số tiền: <i
                                                        class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                            <input type="text" class="form-control" name="txtMoneyPay" onkeyup="qc_main.showFormatCurrency(this);"
                                                   placeholder="Nhập số tiền" value="">
                                        </div>
                                    </div>
                                    <div class="col-sx-12 col-sm-12 col-md-2 col-lg-2"
                                         @if($mobileStatus) style="padding: 0 0;" @endif>
                                        <div class="form-group form-group-sm">
                                            <label>Ngày:<i
                                                        class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                            <input id="txtDatePay" type="text" name="txtDatePay" class="form-control"
                                                   value=""
                                                   placeholder="Ngày thanh toán">
                                            <script type="text/javascript">
                                                qc_main.setDatepicker('#txtDatePay');
                                            </script>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="row">
                        <div class="qc-padding-top-20 qc-padding-bot-20  qc-border-none col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            @if(count($dataOrderSelected) > 0)
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <button type="button" class="qc_save btn btn-primary btn-sm">Thanh toán</button>
                            @endif
                            <a class="btn btn-default btn-sm" type="button"
                               href="{!! route('qc.ad3d.finance.order-payment.get') !!}">
                                Đóng
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        @else
            <div class="qc-color col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <em class="qc-color-red">Không có đơn hàng nào</em>
            </div>
            <div class="qc-color col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <a class="btn btn-primary btn-sm" type="button"
                   href="{!! route('qc.ad3d.finance.order-payment.get') !!}">
                    Đóng
                </a>
            </div>
        @endif
    </div>
@endsection
