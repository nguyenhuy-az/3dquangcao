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
?>
@extends('work.work-allocation.index')
@section('titlePage')
    Chi tiết đơn hàng
@endsection
@section('qc_work_allocation_body')
    <div id="qc_work_allocation_order_detail_wrap"
         class="qc-padding-top-20 qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="qc-border-none col-sx-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 0;">
                <a class="qc-font-size-20 qc-link-red" href="{!! $hFunction->getUrlReferer() !!}">
                    <i class="glyphicon glyphicon-backward"></i>
                    Trở lại
                </a>
            </div>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <h3 class="qc-color-green">{!! $dataOrder->name() !!}</h3>
        </div>
        {{-- thông tin đơn hàng --}}
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-6">
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
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-6">
            <div class="table-responsive">
                <table class="table table-hover qc-margin-bot-none">
                    <tr style="background-color: whitesmoke;">
                        <th colspan="2">
                            <i class="qc-font-size-16 glyphicon glyphicon-user"></i>
                            <label class="qc-font-size-14 qc-color-red">KHÁCH HÀNG</label>
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
        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row qc-padding-top-10">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" style="margin-bottom: 0;">
                            <tr style="background-color: whitesmoke;">
                                <th colspan="10">
                                    <i class="qc-font-size-16 glyphicon glyphicon-shopping-cart"></i>
                                    <label class="qc-font-size-14 qc-color-red">DANH SÁCH SẢN PHẨM</label>
                                </th>
                            </tr>
                            <tr style="background-color: black; color: yellow;">
                                <th class="text-center" style="width: 20px;">STT</th>
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
                            $dataProduct = $dataOrder->allProductOfOrder();
                            $n_o = 0;
                            ?>
                            @if($hFunction->checkCount($dataProduct))
                                @foreach($dataProduct as $product)
                                    <?php
                                    $productId = $product->productId();
                                    $designImage = $product->designImage();
                                    ?>
                                    <tr>
                                        <td class="text-center">
                                            {!! $n_o+=1  !!}
                                        </td>
                                        <td>
                                            {!! $product->productType->name()  !!}
                                        </td>
                                        <td>
                                            {!! $product->description()  !!}
                                        </td>
                                        <td>
                                            @if(!empty($designImage))
                                                <a title="Click xem chi tiết hình ảnh"
                                                   data-href="{!! route('qc.ad3d.order.order.product.design.view', $productId) !!}">
                                                    <img style="margin-right: 10px; max-width: 70px; max-height: 70px; padding: 5px 5px;"
                                                         src="{!! $product->pathSmallDesignImage($designImage) !!}">
                                                </a>
                                            @else
                                                <em class="qc-color-grey">Gửi thiết kế sau</em>
                                            @endif
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
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" style="margin-bottom: 0;">
                            <tr style="background-color: whitesmoke;">
                                <th colspan="5">
                                    <i class="qc-font-size-16 glyphicon glyphicon-credit-card"></i>
                                    <label class="qc-font-size-14 qc-color-red">CHI TIẾT THANH TOÁN</label>
                                </th>
                            </tr>
                            <tr style="background-color: black; color: yellow;">
                                <th class="text-center" style="width: 20px;">STT</th>
                                <th>Ngày</th>
                                <th>Tên</th>
                                <th>Điện thoại</th>
                                <th class="text-right">Số tiền</th>
                            </tr>
                            <?php
                            $dataOrderPay = $dataOrder->infoOrderPayOfOrder();
                            $n_o_pay = 0;
                            ?>
                            @if($hFunction->checkCount($dataOrderPay))
                                @foreach($dataOrderPay as $orderPay)
                                    <tr>
                                        <td class="text-center">
                                            {!! $n_o_pay +=1  !!}
                                        </td>
                                        <td>
                                            {!! date('d/m/Y',strtotime($orderPay->datePay()))  !!}
                                        </td>
                                        <td>
                                            @if(!$hFunction->checkEmpty($orderPay->payerName()))
                                                <span>{!! $orderPay->payerName() !!}</span>
                                            @else
                                                <span>{!! $orderPay->order->customer->name() !!}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(!$hFunction->checkEmpty($orderPay->payerPhone()))
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
                                        @if($dataOrder->discount()== 100)
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
                <div class="pull-right col-sx-12 col-sm-12 col-md-12 col-lg-6">
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
        <div class="qc-padding-top-10 text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <a class="btn btn-sm btn-primary" onclick="qc_main.page_back();">
                Về trang trước
            </a>
            <a class="btn btn-sm btn-default" href="{!! route('qc.work.work_allocation.manage.get') !!}">
                Về Danh mục ĐH
            </a>
        </div>
    </div>

@endsection
