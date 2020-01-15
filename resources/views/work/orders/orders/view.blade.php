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
                            <b>{!! $hFunction->convertDateDMYFromDatetime($dataOrders->receiveDate()) !!}</b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <em class="qc-color-grey">Ngày giao:</em>
                        </td>
                        <td class="text-right">
                            <b>{!! $hFunction->convertDateDMYFromDatetime($dataOrders->deliveryDate()) !!}</b>
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
                                <th colspan="10">
                                    <i class="qc-font-size-16 glyphicon glyphicon-shopping-cart"></i>
                                    <b class="qc-color-red">DANH SÁCH SẢN PHẨM</b>
                                </th>
                            </tr>
                            <tr>
                                <th class="text-center" style="width: 20px;">STT</th>
                                <th>Tên SP</th>
                                <th>Thiết kế</th>
                                <th>Chú thích</th>
                                <th class="text-center">Dài<br/>(m)</th>
                                <th class="text-center">Rộng<br/>(m)</th>
                                <th class="text-center">Đơn vị</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-right">Giá/SP</th>
                                <th class="text-right">Thành tiền</th>
                            </tr>
                            <?php
                            $dataProduct = $dataOrders->allProductOfOrder();
                            $n_o = 0;
                            ?>
                            @if($hFunction->checkCount($dataProduct))
                                @foreach($dataProduct as $product)
                                    <?php
                                    $productId = $product->productId();
                                    $designImage = $product->designImage();
                                    # thiet ke dang ap dung
                                    $dataProductDesign = $product->productDesignInfoApplyActivity();
                                    if ($hFunction->getCountFromData($dataProductDesign) == 0) {
                                        # thiet ke sau cung
                                        $dataProductDesign = $product->productDesignInfoLast();
                                    }
                                    ?>
                                    <tr>
                                        <td class="text-center">
                                            {!! $n_o+=1  !!}
                                        </td>
                                        <td>
                                            {!! $product->productType->name()  !!}
                                        </td>
                                        <td>
                                            @if($hFunction->checkCount($dataProductDesign))
                                                @if($dataProductDesign->checkApplyStatus())
                                                    <a class="qc_work_order_product_design_image_view qc-link"
                                                       data-href="{!! route('qc.work.orders.product_design.view.get', $dataProductDesign->designId()) !!}">
                                                        <img style="width: 70px; height: auto; margin-bottom: 5px; border: 2px solid green;"
                                                             title="Đang áp dụng"
                                                             src="{!! $dataProductDesign->pathSmallImage($dataProductDesign->image()) !!}">
                                                    </a>
                                                    <br/>
                                                @else
                                                    <a class="qc_work_order_product_design_image_view qc-link"
                                                       data-href="{!! route('qc.work.orders.product_design.view.get', $dataProductDesign->designId()) !!}">
                                                        <img style="width: 70px; height: 70px; margin-bottom: 5px; border: 2px solid red;"
                                                             title="Không được áp dụng"
                                                             src="{!! $dataProductDesign->pathSmallImage($dataProductDesign->image()) !!}">
                                                    </a>
                                                    <br/>
                                                @endif
                                            @else
                                                @if(!$hFunction->checkEmpty($designImage))
                                                    <a title="HÌNH ẢNH TỪ PHIÊN BẢNG CŨ - KHÔNG XEM FULL">
                                                        <img style="width: 70px; height: 70px; margin-bottom: 5px; "
                                                             src="{!! $product->pathSmallDesignImage($designImage) !!}">
                                                    </a>
                                                    <br/>
                                                @else
                                                    <em class="qc-color-grey">Gửi thiết kế sau</em>
                                                @endif
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
                                            {!! $hFunction->currencyFormat($product->price()) !!}
                                        </td>
                                        <td class="text-right">
                                            {!! $hFunction->currencyFormat($product->price()*$product->amount()) !!}
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
                                <th class="text-center" style="width: 20px;">STT</th>
                                <th>Ngày</th>
                                <th>Tên</th>
                                <th>Điện thoại</th>
                                <th class="text-right">Số tiền</th>
                            </tr>
                            <?php
                            $dataOrdersPay = $dataOrders->infoOrderPayOfOrder();
                            $n_o = 0;
                            ?>
                            @if($hFunction->checkCount($dataOrdersPay))
                                @foreach($dataOrdersPay as $orderPay)
                                    <tr>
                                        <td class="text-center">
                                            {!! $n_o+=1  !!}
                                        </td>
                                        <td>
                                            {!! $hFunction->convertDateDMYFromDatetime($orderPay->datePay())  !!}
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
                                            {!! $hFunction->currencyFormat($orderPay->money()) !!}
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
        <div class="qc-padding-top-10 text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <a class="btn btn-sm btn-primary" href="{!! $hFunction->getUrlReferer() !!}">Đóng</a>
        </div>
    </div>

@endsection
