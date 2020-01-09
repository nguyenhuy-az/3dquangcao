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
$dataStaffLogin = $modelStaff->loginStaffInfo();
$dataCompany = $dataOrder->company;
?>
@extends('ad3d.order.order.index')
@section('titlePage')
    In đơn hàng
@endsection
@section('qc_ad3d_order_order')
    <div id="qc_ad3d_order_order_print_wrap" class="row">
        <div class="qc-margin-bot-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div ID="qc_work_order_print_wrap" class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    @if(!empty($dataCompany->logo()))
                        <img alt="..." src="{!! $dataCompany->pathSmallImage($dataCompany->logo()) !!}"
                             style="width: 100px; height: auto;">
                    @endif
                    <br/>
                    <span>{!! $dataCompany->nameCode().':'.$dataCompany->name() !!}</span>
                    <br/>
                    <em>Đc/:{!! $dataCompany->address() !!}</em>
                </div>
                <div class="text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <b class="qc-font-size-20">HÓA ĐƠN</b>
                </div>
            </div>

        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <h3>{!! $dataOrder->name() !!}</h3>
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
                            <b>{!! $dataOrder->orderCode() !!}</b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <em class="qc-color-grey">Người nhận :</em>
                        </td>
                        <td class="text-right">
                            <b>{!! $dataOrder->staffReceive->fullName() !!}</b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <em class="qc-color-grey">Ngày nhận:</em>
                        </td>
                        <td class="text-right">
                            <b>{!! date('d/m/Y', strtotime($dataOrder->receiveDate())) !!}</b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <em class="qc-color-grey">Ngày giao:</em>
                        </td>
                        <td class="text-right">
                            <b>{!! date('d/m/Y', strtotime($dataOrder->deliveryDate())) !!}</b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <em class="qc-color-grey">Đ/c thi công:</em>
                        </td>
                        <td class="text-right">
                                    <span class="pull-right">{!! $dataOrder->constructionAddress() !!}
                                        - ĐT: {!! $dataOrder->constructionPhone() !!}
                                        - tên: {!! $dataOrder->constructionContact() !!}</span>
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
                            <b class="qc-font-size-16">KHÁCH HÀNG</b>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <em class="qc-color-grey">Tên:</em>
                        </td>
                        <td class="text-right">
                            <b>{!! $dataOrder->customer->name() !!}</b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <em class="qc-color-grey">Điện thoại:</em>
                        </td>
                        <td class="text-right">
                            <b>{!! $dataOrder->customer->phone() !!}</b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <em class="qc-color-grey">Zalo:</em>
                        </td>
                        <td class="text-right">
                            <b>{!! $dataOrder->customer->zalo() !!}</b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <em class="qc-color-grey">ĐC\:</em>
                        </td>
                        <td class="text-right">
                            <b>{!! $dataOrder->customer->address() !!}</b>
                        </td>
                    </tr>

                </table>
            </div>
        </div>
        {{-- chi tiết sản phẩm --}}
        <div class="qc-margin-bot-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <i class="qc-font-size-16 glyphicon glyphicon-shopping-cart"></i>
                    <b class="qc-font-size-16 ">SẢN PHẨM</b>
                </div>
            </div>
            <div class="row">
                <div class="qc-container-table col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" style="margin-bottom: 0;">
                            <tr style="background-color: whitesmoke;">
                                <th class="text-center">STT</th>
                                <th>Tên SP</th>
                                <th>Thiết kế</th>
                                <th>Ghi chú</th>
                                <th class="text-center">Dài(m)</th>
                                <th class="text-center">Rộng (m)</th>
                                <th class="text-center">Diện tích, m2 <br/>m, cái, cây, bộ...</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-right">Giá/SP</th>
                                <th class="text-right">Thành tiền</th>
                            </tr>
                            <?php
                            $dataProduct = $dataOrder->allProductOfOrder();
                            $n_o = 0;
                            ?>
                            @if(count($dataProduct) > 0)
                                @foreach($dataProduct as $product)
                                    <?php
                                    $designImage = $product->designImage();
                                    ?>
                                    <tr>
                                        <td class="text-center">
                                            {!! $n_o+=1  !!}
                                        </td>
                                        <td>
                                            {!! $product->productType->name()  !!}
                                        </td>
                                        <td style="padding-bottom: 10px;">
                                            @if(!empty($designImage))
                                                <img style="margin-right: 10px; max-width: 70px; max-height: 70px; padding: 5px 5px;"
                                                     src="{!! $product->pathSmallDesignImage($designImage) !!}">
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
                                            {!! $hFunction->currencyFormat($product->price()) !!}
                                        </td>
                                        <td class="text-right">
                                            {!! $hFunction->currencyFormat($product->price()*$product->amount()) !!}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="10">
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
        <div class="qc-margin-bot-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <i class="qc-font-size-16 glyphicon glyphicon-credit-card"></i>
                    <b class="qc-font-size-16">CHI TIẾT THANH TOÁN</b>
                </div>
            </div>
            <div class="row">
                <?php
                $dataOrderPay = $dataOrder->infoOrderPayOfOrder();
                $n_o = 0;
                ?>
                @if(count($dataOrderPay) > 0)
                    <div class="qc-container-table col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-bordered" style="margin-bottom: 0;">
                                <tr style="background-color: whitesmoke;">
                                    <th class="text-center">STT</th>
                                    <th>Ngày</th>
                                    <th>Tên</th>
                                    <th>Điện thoại</th>
                                    <th class="text-right">Số tiền</th>
                                </tr>
                                @foreach($dataOrderPay as $orderPay)
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
                            </table>
                        </div>
                    </div>
                @else
                    <div class="text-left qc-padding-top-10 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <em class="qc-color-red">Chưa thanh toán</em>
                    </div>
                @endif
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
                                    <b>{!! $hFunction->currencyFormat($dataOrder->totalPrice()) !!}</b>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <em class="qc-color-grey">Giảm {!! $dataOrder->discount() !!}%:</em>
                                </td>
                                <td class="text-right">
                                    <b>{!! $hFunction->currencyFormat($dataOrder->totalMoneyDiscount()) !!}</b>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <em class="qc-color-grey">VAT {!! $dataOrder->vat() !!}%:</em>
                                </td>
                                <td class="text-right">
                                    <b>{!! $hFunction->currencyFormat($dataOrder->totalMoneyOfVat()) !!}</b>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <em class="qc-color-grey">Tổng thanh toán:</em>
                                </td>
                                <td class="text-right">
                                    <b class="qc-color-red">{!! $hFunction->currencyFormat($dataOrder->totalMoneyPayment()) !!}</b>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <em class="qc-color-grey">Đã thanh tóa:</em>
                                </td>
                                <td class="text-right">
                                    <b>{!! $hFunction->currencyFormat($dataOrder->totalPaid()) !!}</b>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <em class="qc-color-grey">Còn lại:</em>
                                </td>
                                <td class="text-right">
                                    <b class="qc-color-red">{!! $hFunction->currencyFormat($dataOrder->totalMoneyUnpaid()) !!}</b>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row text-center">
                <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <b>Khách hàng</b>
                    <br/>
                    <em class="qc-color-grey">(Ký tên và ghi rõ họ tên)</em>
                </div>

                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <b>Lập hóa đơn</b>
                    <br/>
                    <em class="qc-color-grey">(Ký tên và ghi rõ họ tên)</em>

                </div>
            </div>

        </div>

        <div class="row">
            <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <a class="qc_ad3d_order_order_print btn btn-sm btn-primary" onclick="window.print();">
                    In
                </a>
                <a class="btn btn-sm btn-default"
                   onclick="window.location.replace('{!! route('qc.ad3d.order.order.get') !!}');">
                    Đóng
                </a>
            </div>
        </div>
    </div>
@endsection
