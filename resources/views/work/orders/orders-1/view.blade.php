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
$orderId = $dataOrders->orderId();
?>
@extends('work.index')
@section('titlePage')
    Chi tiết đơn hàng
@endsection
@section('qc_work_body')
    <div class="qc-padding-top-20 qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
            <h3>{!! $dataOrders->name() !!}</h3>

            <div class="row">
                <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-4 col-sm-6 col-md-6 col-lg-6">
                    <em>{!! $dataOrders->orderCode() !!}</em>
                </div>
                <div class="text-right qc-padding-top-5 qc-padding-bot-5 col-xs-8 col-sm-6 col-md-6 col-lg-6">
                    <em class="qc-color-grey">Người nhận :</em>
                    <em>{!! $dataOrders->staffReceive->fullName() !!}</em>
                </div>
            </div>
        </div>

        {{-- thông tin đơn hàng --}}
        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                    <em class=" qc-color-grey">Giá:</em>
                    <b class="pull-right">{!! $hFunction->currencyFormat($dataOrders->totalPrice()) !!}</b>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                    <em class="qc-color-grey">Giảm:</em>
                    <b class="pull-right">{!! $hFunction->currencyFormat($dataOrders->totalMoneyDiscount()) !!}</b>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                    <em class="qc-color-grey">VAT:</em>
                    <b class="pull-right">{!! $hFunction->currencyFormat($dataOrders->totalMoneyOfVat()) !!}</b>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                    <em class="qc-color-grey">Tổng TT:</em>
                    <b class="pull-right qc-color-red">{!! $hFunction->currencyFormat($dataOrders->totalMoneyPayment()) !!}</b>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                    <em class="qc-color-grey">Đã thanh tóa:</em>
                    <b class="pull-right">{!! $hFunction->currencyFormat($dataOrders->totalPaid()) !!}</b>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                    <em class="qc-color-grey">Còn lại:</em>
                    <b class="pull-right qc-color-red">{!! $hFunction->currencyFormat($dataOrders->totalMoneyUnpaid()) !!}</b>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                    <em class="qc-color-grey">Ngày nhận:</em>
                    <b class="pull-right" style="color: brown;">{!! $dataOrders->receiveDate() !!}</b>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                    <em class="qc-color-grey">Ngày giao:</em>
                    <b class="pull-right" style="color: brown;">{!! $dataOrders->deliveryDate() !!}</b>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                    <em class="qc-color-grey">Xử lý:</em>
                    @if($dataOrders->checkConfirmStatus())
                        <b class="pull-right">Đã duyệt</b>
                    @else
                        <b class="pull-right">Chờ duyệt</b>
                    @endif
                </div>
                <div class="col-xs-12 col-sm-6 col-md-12 col-lg-6">
                    <em class="qc-color-grey">Thi công:</em>
                    <span class="pull-right">{!! $dataOrders->constructionAddress() !!}
                        - ĐT: {!! $dataOrders->constructionPhone() !!}
                        - tên: {!! $dataOrders->constructionContact() !!}</span>
                </div>
            </div>
        </div>

        {{-- thông tin khách hàng --}}
        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row qc-margin-bot-10">
                <div class="qc-color-red col-xs-12 col-sm-12 col-dm-12 col-lg-12"
                     style="border-bottom: 2px solid black;background-color: whitesmoke;">
                    <i class="glyphicon glyphicon-record"></i>
                    <b>KHÁCH HÀNG</b>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                    <em class="qc-color-grey">Tên:</em>
                    <b>{!! $dataOrders->customer->name() !!}</b>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                    <em class="qc-color-grey">Điện thoại:</em>
                    <b>{!! $dataOrders->customer->phone() !!}</b>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                    <em class="qc-color-grey">Zalo:</em>
                    <b>{!! $dataOrders->customer->zalo() !!}</b>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                    <em class="qc-color-grey">ĐC\:</em>
                    <b>{!! $dataOrders->customer->address() !!}</b>
                </div>
            </div>
        </div>

        {{-- chi tiết Thanh toán --}}
        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="qc-color-red col-xs-12 col-sm-12 col-dm-12 col-lg-12"
                     style="border-bottom: 2px solid black;background-color: whitesmoke;">
                    <i class="glyphicon glyphicon-record"></i>
                    <b>CHI TIẾT THANH TOÁN</b>
                </div>
            </div>
            <div class="row qc-padding-top-10">
                <div class="qc-container-table col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th class="text-center">STT</th>
                                <th>Ngày</th>
                                <th>Tên</th>
                                <th>Điện thoại</th>
                                <th class="text-right">Số tiền</th>
                            </tr>
                            <?php
                            $dataOrdersPay = $dataOrders->infoOrderPayOfOrder();
                            $n_o = 0;
                            ?>
                            @if(count($dataOrdersPay) > 0)
                                @foreach($dataOrdersPay as $orderPay)
                                    <tr>
                                        <td class="text-center">
                                            {!! $n_o+=1  !!}
                                        </td>
                                        <td>
                                            {!! date('d/m/Y',strtotime($orderPay->datePay()))  !!}
                                        </td>
                                        <td>
                                            @if(!empty($orderPay->payerName()))
                                                <span>{!! $orderPay->payerName() !!}</span>
                                            @else
                                                <span>{!! $orderPay->order->customer->name() !!}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(!empty($orderPay->payerPhone()))
                                                <span>{!! $orderPay->payerPhone() !!}</span>
                                            @else
                                                <span>{!! $orderPay->order->customer->phone() !!}</span>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            {!! $hFunction->dotNumber($orderPay->money()) !!}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="qc-padding-top-10" colspan="5">
                                        @if($dataOrders->discount()== 100)
                                            <em class="qc-color-red">Giảm 100%</em>
                                        @else
                                            <em class="qc-color-red">Chưa thanh toán</em>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- chi tiết đơn hàng --}}
        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="qc-color-red col-xs-12 col-sm-12 col-dm-12 col-lg-12"
                     style="border-bottom: 2px solid black;background-color: whitesmoke;">
                    <i class="glyphicon glyphicon-record"></i>
                    <b>SẢN PHẨM</b>
                </div>
            </div>
            <div class="row qc-padding-top-10">
                <div class="qc-container-table col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th class="text-center">STT</th>
                                <th>Tên SP</th>
                                <th>Kích thước</th>
                                <th>Chú thích</th>
                                <th class="text-right">Số lượng</th>
                                <th class="text-right">Giá/SP</th>
                                <th class="text-right">Thành tiền</th>
                            </tr>
                            <?php
                            $dataProduct = $dataOrders->allProductOfOrder();
                            $n_o = 0;
                            ?>
                            @if(count($dataProduct) > 0)
                                @foreach($dataProduct as $product)
                                    <tr>
                                        <td class="text-center">
                                            {!! $n_o+=1  !!}
                                        </td>
                                        <td>
                                            {!! $product->productType->name()  !!}
                                        </td>
                                        <td>
                                            {!! $product->width() !!} x {!! $product->height() !!}
                                            x {!! (empty($product->depth())?0:$product->depth()) !!} mm
                                        </td>
                                        <td>
                                            {!! $product->description()  !!}
                                        </td>
                                        <td class="text-right">
                                            {!! $product->amount() !!}
                                        </td>
                                        <td class="text-right">
                                            {!! $hFunction->dotNumber($product->price()) !!}
                                        </td>
                                        <td class="text-right">
                                            {!! $hFunction->dotNumber($product->price()*$product->amount()) !!}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="qc-padding-top-10 text-center" colspan="7">
                                        <em class="qc-color-red">Không có sản phẩm</em>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="qc-padding-top-10 text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <a class="btn btn-sm btn-primary" href="{!! route('qc.work.orders.get') !!}">Đóng</a>
        </div>
    </div>

@endsection
