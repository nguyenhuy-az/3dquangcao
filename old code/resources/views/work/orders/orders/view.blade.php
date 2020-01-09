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
@extends('work.orders.index')
@section('titlePage')
    Chi tiết đơn hàng
@endsection
@section('qc_work_order_body')
    <div class="qc-padding-top-20 qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-10">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <h3>{!! $dataOrders->name() !!}</h3>
        </div>
        {{-- thông tin đơn hàng --}}
        <div class="qc-container-table qc-container-table-border-none qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-6">
            <div class="table-responsive">
                <table class="table table-hover qc-margin-bot-none">
                    <tr>
                        <td>
                            <em class=" qc-color-grey">Mã ĐH:</em>
                        </td>
                        <td class="text-right">
                            <b>{!! $dataOrders->orderCode() !!}</b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <em class="qc-color-grey">Người nhận :</em>
                        </td>
                        <td class="text-right">
                            <b>{!! $dataOrders->staffReceive->fullName() !!}</b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <em class="qc-color-grey">Ngày nhận:</em>
                        </td>
                        <td class="text-right">
                            <b>{!! date('d/m/Y', strtotime($dataOrders->receiveDate())) !!}</b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <em class="qc-color-grey">Ngày giao:</em>
                        </td>
                        <td class="text-right">
                            <b>{!! date('d/m/Y', strtotime($dataOrders->deliveryDate())) !!}</b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <em class="qc-color-grey">Đ/c thi công:</em>
                        </td>
                        <td class="text-right">
                                    <span class="pull-right">{!! $dataOrders->constructionAddress() !!}
                                        - ĐT: {!! $dataOrders->constructionPhone() !!}
                                        - tên: {!! $dataOrders->constructionContact() !!}</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- thông tin khách hàng --}}
        <div class="qc-container-table qc-container-table-border-none qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-6">
            <div class="table-responsive">
                <table class="table table-hover qc-margin-bot-none">
                    <tr style="background-color: whitesmoke;">
                        <th colspan="2">
                            <i class="qc-font-size-16 glyphicon glyphicon-user"></i>
                            <b class="qc-color-red">KHÁCH HÀNG</b>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <em class="qc-color-grey">Tên:</em>
                        </td>
                        <td class="text-right">
                            <b>{!! $dataOrders->customer->name() !!}</b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <em class="qc-color-grey">Điện thoại:</em>
                        </td>
                        <td class="text-right">
                            <b>{!! $dataOrders->customer->phone() !!}</b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <em class="qc-color-grey">Zalo:</em>
                        </td>
                        <td class="text-right">
                            <b>{!! $dataOrders->customer->zalo() !!}</b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <em class="qc-color-grey">ĐC\:</em>
                        </td>
                        <td class="text-right">
                            <b>{!! $dataOrders->customer->address() !!}</b>
                        </td>
                    </tr>

                </table>
            </div>
        </div>

        {{-- chi tiết sản phẩm --}}
        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row qc-padding-top-10">
                <div class="qc-container-table col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" style="margin-bottom: 0;">
                            <tr style="background-color: whitesmoke;">
                                <th colspan="9">
                                    <i class="qc-font-size-16 glyphicon glyphicon-shopping-cart"></i>
                                    <b class="qc-color-red">DANH SÁCH SẢN PHẨM</b>
                                </th>
                            </tr>
                            <tr>
                                <th class="text-center">STT</th>
                                <th>Tên SP</th>
                                <th>Chú thích</th>
                                <th>Thiết kế</th>
                                <th class="text-center">Dài<br/>(m)</th>
                                <th class="text-center">Rộng<br/>(m)</th>
                                <th class="text-center">Diện tích, m2 <br/>m, cái, cây, bộ...</th>
                                <th class="text-center">Số lượng</th>
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
                                            @if(!empty($designImage))
                                                <div style="margin-right: 10px; max-width: 70px; max-height: 70px; padding: 5px 5px; ">
                                                    <img style="max-width: 100%; max-height: 100%;"
                                                         src="{!! $product->pathSmallDesignImage($designImage) !!}">
                                                </div>
                                            @else
                                                <em class="qc-color-grey">Gửi thiết kế sau</em>
                                            @endif
                                        </td>
                                        <td>
                                            {!! $product->description()  !!}
                                        </td>
                                        <td class="text-center">
                                            {!! $product->width()/1000 !!}
                                        </td>
                                        <td class="text-center">
                                            {!! $product->height()/1000 !!}
                                        </td>
                                        <td class="text-center">
                                            {!! ($product->width()/1000)*($product->height()/1000) !!}
                                        </td>
                                        <td class="text-center">
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
                                    <td class="qc-padding-top-10 text-center" colspan="10">
                                        <em class="qc-color-red">Không có sản phẩm</em>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- chi tiết Thanh toán --}}
        <div class="qc-padding-top-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row qc-padding-top-10">
                <div class="qc-container-table col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" style="margin-bottom: 0;">
                            <tr style="background-color: whitesmoke;">
                                <th colspan="5">
                                    <i class="qc-font-size-16 glyphicon glyphicon-credit-card"></i>
                                    <b class="qc-color-red">CHI TIẾT THANH TOÁN</b>
                                </th>
                            </tr>
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
                                            <em style="color: brown;">Chưa thanh toán</em>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Thông tin thanh toán --}}
        <div class="qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="qc-container-table qc-container-table-border-none pull-right qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-6">
                    <div class="table-responsive">
                        <table class="table table-hover qc-margin-bot-none">
                            <tr>
                                <td>
                                    <em class=" qc-color-grey">Tổng tiền:</em>
                                </td>
                                <td class="text-right">
                                    <b>{!! $hFunction->currencyFormat($dataOrders->totalPrice()) !!}</b>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <em class="qc-color-grey">Giảm {!! $dataOrders->discount() !!}%:</em>
                                </td>
                                <td class="text-right">
                                    <b>{!! $hFunction->currencyFormat($dataOrders->totalMoneyDiscount()) !!}</b>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <em class="qc-color-grey">VAT {!! $dataOrders->vat() !!}%:</em>
                                </td>
                                <td class="text-right">
                                    <b>{!! $hFunction->currencyFormat($dataOrders->totalMoneyOfVat()) !!}</b>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <em class="qc-color-grey">Tổng thanh toán:</em>
                                </td>
                                <td class="text-right">
                                    <b class="qc-color-red">{!! $hFunction->currencyFormat($dataOrders->totalMoneyPayment()) !!}</b>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <em class="qc-color-grey">Đã thanh tóa:</em>
                                </td>
                                <td class="text-right">
                                    <b>{!! $hFunction->currencyFormat($dataOrders->totalPaid()) !!}</b>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <em class="qc-color-grey">Còn lại:</em>
                                </td>
                                <td class="text-right">
                                    <b class="qc-color-red">{!! $hFunction->currencyFormat($dataOrders->totalMoneyUnpaid()) !!}</b>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="qc-padding-top-10 text-center col-xs-12 col-sm-12 col-md-12 col-lg-12" >
            <a class="btn btn-sm btn-primary" href="{!! $hFunction->getUrlReferer() !!}">Đóng</a>
        </div>
    </div>

@endsection
