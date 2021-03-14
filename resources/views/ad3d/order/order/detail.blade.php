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
# tong tien chua giam gia
$orderTotalPrice = $dataOrder->totalPrice();
# tong tien giam
$orderTotalDiscount = $dataOrder->totalMoneyDiscount();
# tong tien VAT
$orderTotalVat = $dataOrder->totalMoneyOfVat();
# thong tin san pham
$dataProduct = $dataOrder->productActivityOfOrder();
#anh thiet ke tong quat
$dataOrderImage = $dataOrder->orderImageInfoActivity();
?>
<div class="qc_order_detail col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <table class="table" style="border-left: 4px solid black;">
        <tr>
            <td>
                {{-- thông tin đơn hàng --}}
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                    <table class="table table-hover qc-margin-bot-none">
                        <tr>
                            <td style="padding-top: 2px; padding-bottom: 2px;">
                                <em class=" qc-color-grey">Mã ĐH:</em>
                                <b class="pull-right">{!! $dataOrder->orderCode() !!}</b>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 2px; padding-bottom: 2px;">
                                <em class="qc-color-grey">Người nhận :</em>
                                <b class="pull-right">{!! $dataOrder->staffReceive->fullName() !!}</b>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 2px; padding-bottom: 2px;">
                                <em class="qc-color-grey">Ngày nhận:</em>
                                <b class="pull-right">{!! $hFunction->convertDateDMYFromDatetime($dataOrder->receiveDate()) !!}</b>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 2px; padding-bottom: 2px;">
                                <em class="qc-color-grey">Ngày giao:</em>
                                <b class="pull-right">{!! $hFunction->convertDateDMYFromDatetime($dataOrder->deliveryDate()) !!}</b>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 2px; padding-bottom: 2px;">
                                <em class="qc-color-grey">Đ/c thi công:</em>
                                <span class="pull-right">{!! $dataOrder->constructionAddress() !!}
                                    - ĐT: {!! $dataOrder->constructionPhone() !!}
                                    - tên: {!! $dataOrder->constructionContact() !!}</span>
                            </td>
                        </tr>
                    </table>
                </div>

                {{-- thông tin khách hàng --}}
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                    <table class="table table-hover qc-margin-bot-none">
                        <tr style="background-color: whitesmoke;">
                            <th style="padding-top: 2px; padding-bottom: 2px;">
                                <i class="qc-font-size-16 glyphicon glyphicon-user"></i>
                                <b class="qc-color-red">KHÁCH HÀNG</b>
                            </th>
                        </tr>
                        <tr>
                            <td style="padding-top: 2px; padding-bottom: 2px;">
                                <em class="qc-color-grey">Tên:</em>
                                <b class="pull-right">{!! $dataOrder->customer->name() !!}</b>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 2px; padding-bottom: 2px;">
                                <em class="qc-color-grey">Điện thoại:</em>
                                <b class="pull-right">{!! $dataOrder->customer->phone() !!}</b>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 2px; padding-bottom: 2px;">
                                <em class="qc-color-grey">Zalo:</em>
                                <b class="pull-right">{!! $dataOrder->customer->zalo() !!}</b>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 2px; padding-bottom: 2px;">
                                <em class="qc-color-grey">ĐC\:</em>
                                <b class="pull-right">{!! $dataOrder->customer->address() !!}</b>
                            </td>
                        </tr>

                    </table>
                </div>

                {{-- thong tin san pham --}}
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 10px;">
                    <div id="qc_work_order_info_product_show" class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <table class="table table-bordered" style="margin-bottom: 0px;">
                                <tr style="background-color: black; color: yellow;">
                                    <th style="width: 300px; padding-top: 2px; padding-bottom: 2px;">SẢN PHẨM</th>
                                    <th style="padding-top: 2px; padding-bottom: 2px;">TK SP</th>
                                    <th style="padding-top: 2px; padding-bottom: 2px;">TK THI CÔNG</th>
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
                                                <b class="pull-right" style="color: red;">
                                                    $. {!! $hFunction->currencyFormat($product->price()) !!}
                                                </b>
                                                <br/>
                                                <em style="color: grey;">{!! $hFunction->convertDateDMYFromDatetime($product->createdAt()) !!}</em>
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
                                        <td class="text-center">
                                            Không có sản phẩm
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                </div>
                {{-- thong tin thanh toan --}}
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="margin-bottom: 10px;">
                    <div id="qc_work_order_info_payment_show" class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <table class="table table-bordered" style="margin-bottom: 0px;">
                                <tr style="background-color: black; color: yellow;">
                                    <th style="padding-top: 2px; padding-bottom: 2px;">THANH TOÁN</th>
                                    <th style="padding-top: 2px; padding-bottom: 2px;">Người thu</th>
                                    <th style="padding-top: 2px; padding-bottom: 2px;">Tên người nộp</th>
                                </tr>
                                <?php
                                $dataOrderPay = $dataOrder->infoOrderPayOfOrder();
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
                                                <b style="color: blue;">$.{!! $hFunction->currencyFormat($orderPay->money()) !!}</b>
                                                <br/>
                                                <em style="color: grey;">{!! $hFunction->convertDateDMYFromDatetime($orderPay->datePay())  !!}</em>
                                            </td>
                                            <td>
                                                <div class="media">
                                                    <a class="pull-left" href="#">
                                                        <img class="img-circle media-object"
                                                             style="max-width: 40px;height: 40px; border: 1px solid grey;"
                                                             src="{!! $dataReceiveStaff->pathAvatar($dataReceiveStaff->image()) !!}">
                                                    </a>

                                                    <div class="media-body">
                                                        <h5 class="media-heading">{!! $dataReceiveStaff->fullName() !!}</h5>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if(!$hFunction->checkEmpty($orderPay->payerName()))
                                                    <span>{!! $orderPay->payerName() !!}</span>
                                                    @if(!$hFunction->checkEmpty($orderPay->payerPhone()))
                                                        <br/>
                                                        <em style="color: grey;">ĐT:</em>
                                                        <span> {!! $orderPay->payerPhone() !!}</span>
                                                    @endif
                                                @else
                                                    <span>{!! $orderPay->order->customer->name() !!}</span>
                                                    @if($hFunction->checkEmpty($orderPay->payerPhone()))
                                                        <br/>
                                                        <em style="color: grey;">ĐT:</em>
                                                        <span>{!! $orderPay->order->customer->phone() !!}</span>
                                                    @endif
                                                @endif
                                                @if($hFunction->checkEmpty($payNote))
                                                    <br/>
                                                    <em style="color: red;">{!! $payNote  !!}</em>
                                                @endif

                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-center">
                                            Không có thông tin thanh toán
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="row">
                        <div class="qc-container-table-border-none col-xs-12 col-sm-12 col-dm-12 col-lg-12">
                            <table class="table">
                                <tr>
                                    <td style="padding-top: 2px; padding-bottom: 2px;">
                                        <a class=" qc-color-grey">Thành tiền:</a>
                                        <b class="pull-right">{!! $hFunction->currencyFormat($orderTotalPrice) !!}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-top: 2px; padding-bottom: 2px;">
                                        <a class="qc-color-grey">Giảm {!! $dataOrder->discount() !!}%:</a>
                                        <b class="pull-right">- {!! $hFunction->currencyFormat($orderTotalDiscount) !!}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-top: 2px; padding-bottom: 2px;">
                                        <a class=" qc-color-grey">Tổng tiền chưa VAT:</a>
                                        <b class="pull-right" style="color: red;">
                                            {!! $hFunction->currencyFormat($orderTotalPrice - $orderTotalDiscount) !!}
                                        </b>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-top: 2px; padding-bottom: 2px;">
                                        <a class="qc-color-grey">VAT {!! $dataOrder->vat() !!}%:</a>
                                        <b class="pull-right">+ {!! $hFunction->currencyFormat($orderTotalVat) !!}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-top: 2px; padding-bottom: 2px;">
                                        <a class="qc-color-grey">Tổng tiền có VAT:</a>
                                        <b class="pull-right qc-color-red">{!! $hFunction->currencyFormat($orderTotalPrice - $orderTotalDiscount + $orderTotalVat ) !!}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-top: 2px; padding-bottom: 2px;">
                                        <a class="qc-color-grey">Đã thanh toán:</a>
                                        <b class="pull-right">- {!! $hFunction->currencyFormat($dataOrder->totalPaid()) !!}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-top: 2px; padding-bottom: 2px; border-top: 1px solid grey !important;">
                                        <a class="qc-color-grey">Còn lại:</a>
                                        <b class="pull-right qc-color-red">{!! $hFunction->currencyFormat($dataOrder->totalMoneyUnpaid()) !!}</b>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                {{-- thiet ke tong the --}}
                <div class="qc-container-table-border-none col-xs-12 col-sm-12 col-dm-12 col-lg-12">
                    <table class="table table-bordered" style="margin-bottom: 0px;">
                        @if($hFunction->checkCount($dataOrderImage))
                            @foreach($dataOrderImage as $orderImage)
                                <tr>
                                    <td style="padding-top: 5px !important;">
                                        <a class="qc-link">
                                            <img style="width: 100%; margin-bottom: 5px;"
                                                 title="Thiết kế tổng quát"
                                                 src="{!! $orderImage->pathFullImage($orderImage->image()) !!}">
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td style="padding: 0; border: none;">
                <button class="form-control btn btn-primary qc-link-white-bold"
                        onclick="qc_main.hide('.qc_order_detail');">ĐÓNG
                </button>
            </td>
        </tr>
    </table>
</div>


