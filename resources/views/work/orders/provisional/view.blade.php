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
    Chi tiết báo giá
@endsection
@section('qc_work_body')
    <div class="qc-padding-top-20 qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-10">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <span class="qc-font-size-20 qc-color-red">BÁO GIÁ - </span>
            <label class="qc-font-size-20">{!! $dataOrders->name() !!}</label>

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
                            <em class="qc-color-grey">Ngày báo:</em>
                        </td>
                        <td class="text-right">
                            <b>{!! date('d/m/Y', strtotime($dataOrders->provisionalDate())) !!}</b>
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
        <div class="qc-container-table qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-6">
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
        {{-- chi tiết đơn hàng --}}
        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row qc-padding-top-10">
                <div class="qc-container-table col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" style="margin-bottom: 0;">
                            <tr style="background-color: whitesmoke;">
                                <th colspan="10">
                                    <i class="qc-font-size-16 glyphicon glyphicon-shopping-cart"></i>
                                    <b class="qc-color-red">DANH SÁCH SẢN PHẨM</b>
                                </th>
                            </tr>
                            <tr>
                                <th class="text-center">STT</th>
                                <th>Tên SP</th>
                                <th>Chú thích</th>
                                <th>Thiết kế</th>
                                <th class="text-center">Dài <br/> (m)</th>
                                <th class="text-center">Rộng <br/> (m)</th>
                                <th class="text-center">Đơn vị</th>
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
                                            @if(!$hFunction->checkEmpty($product->productType->unit()))
                                                {!! $product->productType->unit()  !!}
                                            @else
                                                <em class="qc-color-grey">...</em>
                                            @endif
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
        <div class="qc-padding-top-10 text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <a class="btn btn-sm btn-primary" href="{!! $hFunction->getUrlReferer() !!}">Đóng</a>
        </div>
    </div>

@endsection
