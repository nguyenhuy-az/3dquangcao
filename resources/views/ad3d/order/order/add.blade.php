<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 *
 * dataProductType
 */
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
if (count($dataCustomer) > 0) {
    $customerName = $dataCustomer->name();
    $customerAddress = $dataCustomer->address();
    $customerPhone = $dataCustomer->phone();
    $customerZalo = $dataCustomer->zalo();
} else {
    $customerName = null;
    $customerAddress = null;
    $customerPhone = null;
    $customerZalo = null;
}
if (count($dataOrder) > 0) {
    $orderName = $dataOrder->name();
    $orderDiscount = $dataOrder->discount();
    $orderReceiveStaff = $dataOrder->staffReceiveId();
    $orderCode = $dataOrder->orderCode();
    $href = route('qc.ad3d.order.order.edit.addProduct.post',$dataOrder->orderId());
} else {
    $orderName = null;
    $orderDiscount = null;
    $orderReceiveStaff = null;
    $orderCode = null;
    $href = route('qc.ad3d.order.order.add.post');
}
?>
@extends('ad3d.order.order.index')
@section('titlePage')
    Tạo đơn hàng
@endsection
@section('qc_ad3d_order_order')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            @if (count($dataOrder) > 0)
                <h4>THÊM SẢN PHẨM</h4>
            @else
                <h4>TẠO ĐƠN ĐƠN HÀNG</h4>
            @endif
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <form id="frmAd3dOrderAdd" role="form" method="post" enctype="multipart/form-data"
                  action="{!! $href !!}">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        @if (Session::has('notifyAdd'))
                            <div class="form-group form-group-sm text-center qc-color-red">
                                {!! Session::get('notifyAdd') !!}
                                <?php
                                Session::forget('notifyAdd');
                                ?>
                            </div>
                        @endif
                    </div>
                </div>
                @if (count($dataOrder) > 0)
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 @if($mobileStatus)  qc-padding-none @endif">
                            <div class="form-group form-group-sm qc-margin-none">
                                <em>Đơn hàng:</em>
                                <b>{!! $dataOrder->name() !!}</b>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 @if($mobileStatus)  qc-padding-none @endif">
                            <div class="form-group form-group-sm qc-margin-none">
                                <em>Khách hàng:</em>
                                <b>{!! $dataOrder->customer->name() !!}</b>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 @if($mobileStatus)  qc-padding-none @endif">
                            <div class="form-group form-group-sm qc-margin-none">
                                <em>Điên thoại:</em>
                                <b>{!! $dataOrder->customer->phone() !!}</b>
                            </div>
                        </div>
                    </div>
                @else

                    {{-- thông tin khách hàng --}}
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                                 style="border-bottom: 1px solid grey;">
                                <i class="glyphicon glyphicon-pencil qc-color-green"></i>
                                <span class="qc-color-red qc-font-size-16">Khách Hàng</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 @if($mobileStatus)  qc-padding-none @endif">
                                <div class="form-group form-group-sm qc-margin-none">
                                    <label>Điện thoại:</label>
                                    <input type="number" class="txtPhone form-control" name="txtPhone"
                                           data-href-check="{!! route('qc.ad3d.order.order.customer.check.phone') !!}"
                                           data-href-replace="{!! route('qc.ad3d.order.order.add.get') !!}"
                                           placeholder="Số điện thoại" @if(count($dataCustomer) > 0) readonly="true"
                                           @endif
                                           style="height: 25px;" value="{!! $customerPhone !!}">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 @if($mobileStatus)  qc-padding-none @endif">
                                <div class="form-group form-group-sm qc-margin-none ">
                                    <label>Tên Khách hàng:
                                        <i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                    <input type="text" class="form-control" name="txtCustomerName" style="height: 25px;"
                                           placeholder="Nhập tên khách hàng"
                                           @if(count($dataCustomer) > 0) readonly="true"
                                           @endif value="{!! $customerName !!}">
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 @if($mobileStatus)  qc-padding-none @endif">
                                <div class="form-group form-group-sm qc-margin-none">
                                    <label>Zalo:</label>
                                    <input type="text" class="form-control" name="txtZalo" placeholder="Số zalo"
                                           style="height: 25px;" value="{!! $customerZalo !!}">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 @if($mobileStatus)  qc-padding-none @endif">
                                <div class="form-group form-group-sm qc-margin-none">
                                    <label>Địa chỉ:</label>
                                    <input type="text" class="form-control" name="txtAddress" placeholder="Địa chỉ"
                                           style="height: 25px;" value="{!! $customerAddress !!}">
                                </div>
                            </div>
                            @if(count($dataCustomer) > 0)
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 @if($mobileStatus)  qc-padding-none @endif">
                                    <div class="form-group form-group-sm qc-margin-none">
                                        <a class="qc-link-green" href="{!! route('qc.ad3d.order.order.add.get') !!}">
                                            <em>Nhập thông tin khách hàng mới</em>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- thông tin đơn hàng --}}
                    <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 qc-padding-none"
                                 style="border-bottom: 1px solid grey;">
                                <i class="glyphicon glyphicon-pencil qc-color-green"></i>
                                <span class="qc-color-red qc-font-size-16">Đơn hàng</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 @if($mobileStatus)  qc-padding-none @endif">
                                <div class="form-group form-group-sm qc-margin-none">
                                    <label>Tên đơn hàng:
                                        <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    </label>
                                    <input type="text" class="form-control" name="txtOrderName"
                                           placeholder="Nhập tên đơn hàng" style="height: 25px;"
                                           value="">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 @if($mobileStatus)  qc-padding-none @endif">
                                <div class="form-group form-group-sm qc-margin-none">
                                    <label>Đ/c Thi công:</label>
                                    <input type="text" class="form-control" name="txtConstructionAddress"
                                           placeholder="Địa chỉ thi công" style="height: 25px;"
                                           value="">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 @if($mobileStatus)  qc-padding-none @endif">
                                <div class="form-group form-group-sm qc-margin-none">
                                    <label>ĐT liên hệ:</label>
                                    <input type="number" class="form-control" name="txtConstructionPhone"
                                           placeholder="Điện thoại liên hệ" style="height: 25px;"
                                           value="">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 @if($mobileStatus)  qc-padding-none @endif">
                                <div class="form-group form-group-sm qc-margin-none">
                                    <label>Tên liên hệ:</label>
                                    <input type="text" class="form-control" name="txtConstructionContact"
                                           placeholder="Người liên hệ" style="height: 25px;"
                                           value="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 @if($mobileStatus)  qc-padding-none @endif">
                                <div class="form-group form-group-sm qc-margin-none">
                                    <label>Giảm giá <em class="qc-color-red">(%)</em></label>
                                    {{--<input type="number" class="form-control" name="txtDiscount" style="height: 25px;" value="0">--}}
                                    <select class="cbDiscount form-control" name="cbDiscount" style="height: 25px;">
                                        @for($i = 0; $i <= 10;$i++)
                                            <option value="{!! $i !!}"
                                                    @if(count($dataCustomer) > 0 && $i == 5) selected="selected" @endif >
                                                {!! $i !!}
                                            </option>
                                        @endfor
                                        @for($y = 15; $y <= 100;$y= $y+5)
                                            <option value="{!! $y !!}">{!! $y !!}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 @if($mobileStatus)  qc-padding-none @endif">
                                <div class="form-group form-group-sm qc-margin-none">
                                    <label>
                                        Cọc <em class="qc-color-red">(x1000)</em>:
                                        <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    </label>
                                    <input type="number" class="form-control" name="txtBeforePay"
                                           placeholder="Nhập tiền cọc" style="height: 25px;" value="">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 @if($mobileStatus)  qc-padding-none @endif">
                                <div class="form-group form-group-sm qc-margin-none">
                                    <label>VAT <em class="qc-color-red">(10%)</em>:</label>
                                    <select class="cbVat form-control" name="cbVat" style="height: 25px;">
                                        <option value="0">Không xuất VAT</option>
                                        <option value="1">Xuất VAT</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-2 col-lg-2 @if($mobileStatus)  qc-padding-none @endif">
                                <div class="form-group form-group-sm qc-margin-none">
                                    <label>Ngày Nhận:<i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                    <input id="txtDateReceive" type="text" name="txtDateReceive" class="form-control"
                                           value="" style="height: 25px;" placeholder="Ngày nhận">
                                    <script type="text/javascript">
                                        qc_main.setDatepicker('#txtDateReceive');
                                    </script>
                                </div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-2 col-lg-2 @if($mobileStatus)  qc-padding-none @endif">
                                <div class="form-group form-group-sm qc-margin-none">
                                    <label>Ngày giao:<i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                    <input id="txtDateDelivery" type="text" name="txtDateDelivery" class="form-control"
                                           value="" style="height: 25px;" placeholder="Ngày giao">
                                    <script type="text/javascript">
                                        qc_main.setDatepicker('#txtDateDelivery');
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                {{-- sản phẩm --}}
                <div class=" col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 qc-padding-none"
                             style="border-bottom: 1px solid grey;">
                            <i class="glyphicon glyphicon-pencil qc-color-green"></i>
                            <span class="qc-color-red qc-font-size-16">Sản phẩm</span>
                        </div>
                    </div>
                    <div id="qc_order_add_product_wrap" class="row">
                        @include('ad3d.order.order.add-product', compact('dataProductType'))
                    </div>
                </div>
                <div class="row">
                    <div class="qc-padding-top-20 qc-padding-bot-20  qc-border-none text-left col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <a class="qc_order_product_add qc-link-green"
                           data-href="{!! route('qc.ad3d.order.order.product.get') !!}">
                            <i class="glyphicon glyphicon-plus"></i>
                            Thêm sản phẩm
                        </a>
                    </div>
                </div>


                <div class="row">
                    <div class="qc-padding-top-20 qc-padding-bot-20  qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <button type="button" class="qc_save btn btn-primary btn-sm"> Đặt hàng</button>
                        <a class="btn btn-default btn-sm" type="button" href="{!! route('qc.ad3d.order.order.get') !!}">
                            Đóng
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
