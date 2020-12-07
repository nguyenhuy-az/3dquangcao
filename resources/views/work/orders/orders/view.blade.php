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
# thong tin san pham
$dataProduct = $dataOrders->productActivityOfOrder();
#anh thiet ke tong quat
$dataOrderImage = $dataOrders->orderImageInfoActivity();
?>
@extends('work.orders.index')
@section('titlePage')
    Chi tiết đơn hàng
@endsection
@section('qc_work_order_body')
    <div class="row">
        <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <a class="btn btn-sm btn-primary" onclick="qc_main.page_back();">
                Về trang trước
            </a>
        </div>
        <div class="qc-padding-top-20 qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <h3>{!! $dataOrders->name() !!}</h3>
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
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-6">
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

            {{-- thong tin san pham --}}
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 10px;">
                <div class="row">
                    <div class=" col-xs-12 col-sm-12 col-dm-12 col-lg-12">
                        <i class="qc-font-size-16 glyphicon glyphicon-shopping-cart"></i>
                        <label class="qc-color-red qc-font-size-16">SẢM PHẨM</label>
                        @if($dataOrders->checkCancelStatus())
                            <span class="qc-color-red pull-right">Đã hủy</span>
                        @endif
                    </div>
                </div>
                <div id="qc_work_order_info_product_show" class="row">
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-bordered" style="margin-bottom: 0px;">
                                <tr style="background-color: black; color: yellow;">
                                    <th style="width: 300px;">SẢN PHẨM</th>
                                    <th>
                                        GIÁ/SP(VNĐ)
                                    </th>
                                    <th>TK SP</th>
                                    <th>TK THI CÔNG</th>
                                </tr>
                                @if($hFunction->checkCount($dataProduct))
                                    <?php
                                    $n_o = 0;
                                    ?>
                                    @foreach($dataProduct as $product)
                                        <?php
                                        $productId = $product->productId();
                                        $productWidth = $product->width();
                                        $productHeight = $product->height();
                                        $description = $product->description();
                                        $productAmount = $product->amount();
                                        # thiet ke san pham dang ap dung dang ap dung
                                        $dataProductDesign = $product->productDesignInfoApplyActivity();
                                        # thiet ke san pham thi cong
                                        $dataProductDesignConstruction = $product->productDesignInfoConstructionHasApply();
                                        ?>
                                        <tr>
                                            <td>
                                                <b>{!! $product->productType->name()  !!}</b>
                                                <br/>
                                                <em>{!! $hFunction->convertDateDMYFromDatetime($product->createdAt()) !!}</em>
                                                <br/>
                                                <em>- Ngang: </em>
                                                <span> {!! $productWidth !!} mm</span>
                                                <em>- Cao: </em>
                                                <span>{!! $productHeight !!} mm</span>
                                                <em>- Số lượng: </em>
                                                <span style="color: red;">{!! $productAmount !!}</span>
                                                @if(!$hFunction->checkEmpty($description))
                                                    <br/>
                                                    <em>- Ghi chú: </em>
                                                    <em style="color: grey;">- {!! $description !!}</em>
                                                @endif
                                                @if(!$product->checkCancelStatus())
                                                    @if($product->checkFinishStatus())
                                                        <em style="color: grey;">Đã hoàn thành</em>
                                                    @endif
                                                @else
                                                    <em style="color: grey;">Đã hủy</em>
                                                @endif
                                            </td>
                                            <td>
                                                <b style="color: blue;">
                                                    {!! $hFunction->currencyFormat($product->price()) !!}
                                                </b>
                                            </td>
                                            <td style="padding-top: 5px !important;">
                                                @if($hFunction->checkCount($dataProductDesign))
                                                    @if($dataProductDesign->checkApplyStatus())
                                                        <a class="qc_work_order_product_design_image_view qc-link"
                                                           data-href="{!! route('qc.work.orders.product_design.view.get', $dataProductDesign->designId()) !!}">
                                                            <img style="width: 70px; height: auto; margin-bottom: 5px;"
                                                                 title="Đang áp dụng"
                                                                 src="{!! $dataProductDesign->pathSmallImage($dataProductDesign->image()) !!}">
                                                        </a>
                                                    @endif
                                                @else
                                                    <span style="background-color: black; color: lime;">Chưa có thiết kế</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($hFunction->checkCount($dataProductDesignConstruction))
                                                    @foreach($dataProductDesignConstruction as $productDesignConstruction)
                                                        <a class="qc_work_order_product_design_image_view qc-link"
                                                           data-href="{!! route('qc.work.orders.product_design.view.get', $productDesignConstruction->designId()) !!}">
                                                            <img style="width: 70px; height: auto; margin-bottom: 5px; border: 1px solid grey;"
                                                                 title="Đang áp dụng"
                                                                 src="{!! $productDesignConstruction->pathSmallImage($productDesignConstruction->image()) !!}">
                                                        </a>
                                                    @endforeach
                                                @else
                                                    <span style="background-color: black; color: lime;">Chưa có TK thi công </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-center" colspan="4">
                                            Không có sản phẩm
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            {{-- thong tin thanh toan --}}
            <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6" style="margin-bottom: 10px;">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-dm-12 col-lg-12">
                        <i class="qc-font-size-16 glyphicon glyphicon-credit-card"></i>
                        <label class="qc-color-red qc-font-size-16">THANH TOÁN</label>
                    </div>
                </div>
                <div id="qc_work_order_info_payment_show" class="row">
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-bordered" style="margin-bottom: 0px;">
                                <tr style="background-color: black; color: yellow;">
                                    <th>Ngày</th>
                                    <th>Số tiền</th>
                                    <th>Người thu</th>
                                    <th>Tên người nộp</th>
                                </tr>
                                <?php
                                $dataOrderPay = $dataOrders->infoOrderPayOfOrder();
                                ?>
                                @if($hFunction->checkCount($dataOrderPay))
                                    @foreach($dataOrderPay as $orderPay)
                                        <?php
                                        $payId = $orderPay->payId();
                                        $payNote = $orderPay->note();
                                        # Thong tin nhan vien nha
                                        $dataReceiveStaff = $orderPay->staff;
                                        ?>
                                        <tr>
                                            <td>
                                                {!! $hFunction->convertDateDMYFromDatetime($orderPay->datePay())  !!}
                                            </td>
                                            <td>
                                                <label style="color: blue;">{!! $hFunction->currencyFormat($orderPay->money()) !!}</label>
                                                @if($hFunction->checkEmpty($payNote))
                                                    <br/>
                                                    <em style="color: red;">{!! $payNote  !!}</em>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="media">
                                                    <a class="pull-left" href="#">
                                                        <img class="media-object"
                                                             style="max-width: 40px;height: 40px; border: 1px solid #d7d7d7;border-radius: 10px;"
                                                             src="{!! $dataReceiveStaff->pathAvatar($dataReceiveStaff->image()) !!}">
                                                    </a>

                                                    <div class="media-body">
                                                        <h5 class="media-heading">{!! $dataReceiveStaff->fullName() !!}</h5>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if(!empty($orderPay->payerName()))
                                                    <span>{!! $orderPay->payerName() !!}</span>
                                                    @if(!empty($orderPay->payerPhone()))
                                                        <br/>
                                                        <em style="color: grey;">ĐT:</em>
                                                        <span> {!! $orderPay->payerPhone() !!}</span>
                                                    @endif
                                                @else
                                                    <span>{!! $orderPay->order->customer->name() !!}</span>
                                                    @if(empty($orderPay->payerPhone()))
                                                        <br/>
                                                        <em style="color: grey;">ĐT:</em>
                                                        <span>{!! $orderPay->order->customer->phone() !!}</span>
                                                    @endif
                                                @endif

                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-center" colspan="4">
                                            Không có thông tin thanh toán
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-dm-12 col-lg-12">
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
                                        <b>- {!! $hFunction->currencyFormat($dataOrders->totalMoneyDiscount()) !!}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <em class="qc-color-grey">VAT {!! $dataOrders->vat() !!}%:</em>
                                    </td>
                                    <td class="text-right">
                                        <b>+ {!! $hFunction->currencyFormat($dataOrders->totalMoneyOfVat()) !!}</b>
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
                                        <em class="qc-color-grey">Đã thanh toán:</em>
                                    </td>
                                    <td class="text-right">
                                        <b>- {!! $hFunction->currencyFormat($dataOrders->totalPaid()) !!}</b>
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
            {{-- thiet ke tong the --}}
            <div class="row">
                <div class="qc-container-table-border-none col-xs-12 col-sm-12 col-dm-12 col-lg-12">
                    <div class="table-responsive" style="margin: 0;">
                        <table class="table table-bordered" style="margin-bottom: 0px;">
                            @if($hFunction->checkCount($dataOrderImage))
                                @foreach($dataOrderImage as $orderImage)
                                    <tr>
                                        <td style="padding-top: 5px !important;">
                                            <a class="qc-link">
                                                <img style="width: 100%; margin-bottom: 5px;" title="Thiết kế tổng quát"
                                                     src="{!! $orderImage->pathFullImage($orderImage->image()) !!}">
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
