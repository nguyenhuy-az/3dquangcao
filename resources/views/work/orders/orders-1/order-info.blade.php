<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 5/15/2018
 * Time: 10:12 AM
 *
 * dataStaff
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
# thong tin don hang
$orderId = $dataOrders->orderId();
$customerId = $dataOrders->customerId();
$orderName = $dataOrders->name();
$orderReceiveDate = $dataOrders->receiveDate();
$orderDeliveryDate = $dataOrders->deliveryDate();
$orderDiscount = $dataOrders->discount();
$orderVat = $dataOrders->vat();
$constructionContact = $dataOrders->constructionContact();
$constructionPhone = $dataOrders->constructionPhone();
$constructionAddress = $dataOrders->constructionAddress();
$createdAt = $dataOrders->createdAt();
$expiredEditStatus = $dataOrders->checkExpiredEdit();
# thong tin san pham
$dataProduct = $dataOrders->allProductOfOrder();
# thong tin khach hang
$dataCustomer = $dataOrders->customer;
$customerName = $dataCustomer->name();
$customerPhone = $dataCustomer->phone();
$customerZalo = $dataCustomer->zalo();
$customerAddress = $dataCustomer->address();
?>
@extends('work.index')
@section('titlePage')
    Sản phẩm
@endsection
@section('qc_work_body')
    <div class="row qc_work_orders_wrap">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <a class="qc-link-red" onclick="qc_main.page_back();">
                    <i class="glyphicon glyphicon-backward"></i> Trởlại
                </a>
            </div>
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <h4>THÔNG TIN ĐƠN HÀNG</h4>
                <em class="pull-right qc-color-red">Hạn
                    sửa: {!! date('d/m/Y H:i', strtotime($hFunction->datetimePlusHour($createdAt, 8))) !!}</em>
            </div>
            {{-- thong tin khach hang --}}
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 20px;">
                <div class="row">
                    <div class="qc-color-red col-xs-12 col-sm-12 col-dm-12 col-lg-12"
                         style="border-bottom: 2px solid black;background-color: whitesmoke;">
                        <i class="glyphicon glyphicon-record"></i>
                        <b>KHÁCH HÀNG</b>
                        @if($expiredEditStatus)
                            <a class="qc-link-red pull-right" title="Sửa thông tin"
                               onclick="qc_main.hide('#qc_work_order_info_customer_show'); qc_main.show('#qc_work_order_frm_customer_edit');">
                                <i class=" glyphicon glyphicon-pencil"></i>
                            </a>
                        @else
                            <em class="pull-right">---</em>
                        @endif
                    </div>
                </div>
                <div id="qc_work_order_info_customer_show" class="row">
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-3 col-lg-3">
                            <i class="glyphicon glyphicon-arrow-right" style="color: blue;"></i>
                            <em>Tên: </em>
                            <b class="pull-right">{!! $customerName !!}</b>
                        </div>
                        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-3 col-lg-3">
                            <i class="glyphicon glyphicon-arrow-right" style="color: blue;"></i>
                            <em>Điện thoại: </em>&nbsp;&nbsp;
                            <b class="pull-right">{!! $customerPhone !!}</b>
                        </div>
                        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-3 col-lg-3">
                            <i class="glyphicon glyphicon-arrow-right" style="color: blue;"></i>
                            <em>Zalo: </em> &nbsp;&nbsp;
                            <b class="pull-right">{!! $customerZalo !!}</b>
                        </div>
                        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-3 col-lg-3">
                            <i class="glyphicon glyphicon-arrow-right" style="color: blue;"></i>
                            <em>Đ/C: </em>&nbsp;&nbsp;
                            <b class="pull-right">{!! $customerAddress !!}</b>
                        </div>
                    </div>
                </div>
                <form id="qc_work_order_frm_customer_edit" class="qc-display-none"
                      name="qc_work_order_frm_customer_edit" role="form"
                      method="post" action="{!! route('qc.work.orders.info.customer.edit.post',$customerId) !!}">
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
                         @if($mobileStatus) style="padding: 0 0;" @endif>
                        <div class="frm_info_edit_notify text-center form-group form-group-sm qc-color-red"></div>
                    </div>
                    <div class="row">
                        <div class="col-sx-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>Tên <i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                <input type="text" class="form-control" name="txtCustomerName"
                                       placeholder="Nhập tên khách hàng"
                                       value="{!! $customerName !!}">
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>Điện thoại:</label>
                                <input type="text" class="form-control" name="txtCustomerPhone"
                                       placeholder="Số điện thoại" value="{!! $customerPhone !!}">
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>Zalo:</label>
                                <input type="text" class="form-control" name="txtCustomerZalo"
                                       placeholder="Zalo" value="{!! $customerZalo !!}">
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>Địa chỉ:</label>
                                <input type="text" class="form-control" name="txtCustomerAddress"
                                       placeholder="Địa chỉ" value="{!! $customerAddress !!}">
                            </div>
                        </div>
                    </div>
                    <div class="text-right qc-padding-top-10 qc-padding-bot-10 col-sx-12 col-sm-12 col-md-12 col-lg-12 ">
                        <div class="form-group form-group-sm" style="margin: 0;">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <button type="button" class="qc_save btn btn-sm btn-primary">Lưu thay đổi</button>
                            <button type="reset" class=" btn btn-sm btn-default">Nhập lại</button>
                            <button type="button" class="btn btn-sm btn-default"
                                    onclick="qc_main.hide('#qc_work_order_frm_customer_edit'); qc_main.show('#qc_work_order_info_customer_show');">
                                Đóng
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            {{-- thong tin don hang --}}
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 20px;">
                <div class="row">
                    <div class="qc-color-red col-xs-12 col-sm-12 col-dm-12 col-lg-12"
                         style="border-bottom: 2px solid black;background-color: whitesmoke;">
                        <i class="glyphicon glyphicon-record"></i>
                        <b>ĐƠN HÀNG</b>
                        @if($expiredEditStatus)
                            <a class="qc-link-red pull-right" title="Sửa thông tin"
                               onclick="qc_main.hide('#qc_work_order_info_order_show'); qc_main.show('#qc_work_order_frm_order_edit');">
                                <i class=" glyphicon glyphicon-pencil"></i>
                            </a>
                        @else
                            <em class="pull-right">---</em>
                        @endif
                    </div>
                </div>
                <div id="qc_work_order_info_order_show" class="row">
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-3 col-lg-3">
                            <i class="glyphicon glyphicon-arrow-right" style="color: blue;"></i>
                            <em>Tên: </em>
                            <b class="pull-right">{!! $orderName !!}</b>
                        </div>
                        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-3 col-lg-3">
                            <i class="glyphicon glyphicon-arrow-right" style="color: blue;"></i>
                            <em>T/g nhận: </em>&nbsp;&nbsp;
                            <b class="pull-right">{!! date('d/m/Y', strtotime($orderReceiveDate)) !!}</b>
                        </div>
                        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-3 col-lg-3">
                            <i class="glyphicon glyphicon-arrow-right" style="color: blue;"></i>
                            <em>T/g Giao: </em>&nbsp;&nbsp;
                            <b class="pull-right">{!! date('d/m/Y', strtotime($orderDeliveryDate)) !!}</b>
                        </div>
                        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-3 col-lg-3">
                            <i class="glyphicon glyphicon-arrow-right" style="color: blue;"></i>
                            <em>Giảm giá: </em> &nbsp;&nbsp;
                            <b class="pull-right">{!! $orderDiscount !!}</b>
                        </div>
                        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-3 col-lg-3">
                            <i class="glyphicon glyphicon-arrow-right" style="color: blue;"></i>
                            <em>Xuất VAT: </em> &nbsp;&nbsp;
                            <b class="pull-right">
                                @if(count($orderVat) > 0)
                                    Có
                                @else
                                    Không
                                @endif
                            </b>
                        </div>
                        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-3 col-lg-3">
                            <i class="glyphicon glyphicon-arrow-right" style="color: blue;"></i>
                            <em>Tên LH: </em>&nbsp;&nbsp;
                            <b class="pull-right">{!! $constructionContact !!}</b>
                        </div>
                        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-3 col-lg-3">
                            <i class="glyphicon glyphicon-arrow-right" style="color: blue;"></i>
                            <em>Điện thoại LH: </em>&nbsp;&nbsp;
                            <b class="pull-right">{!! $constructionPhone !!}</b>
                        </div>
                        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-3 col-lg-3">
                            <i class="glyphicon glyphicon-arrow-right" style="color: blue;"></i>
                            <em>Đ/C Liên hệ: </em>&nbsp;&nbsp;
                            <b class="pull-right">{!! $constructionAddress !!}</b>
                        </div>
                    </div>
                </div>
                <form id="qc_work_order_frm_order_edit" class="qc-display-none" name="qc_work_order_frm_customer_edit"
                      role="form" method="post" action="{!! route('qc.work.orders.info.order.edit.post', $orderId) !!}">
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
                         @if($mobileStatus) style="padding: 0 0;" @endif>
                        <div class="frm_info_edit_notify text-center form-group form-group-sm qc-color-red"></div>
                    </div>
                    <div class="row">
                        <div class="col-sx-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>Tên <i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                <input type="text" class="form-control" name="txtOrderName"
                                       placeholder="Nhập tên đơn hàng"
                                       value="{!! $orderName !!}">
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>Giảm giá <em class="qc-color-red">(%)</em></label>
                                <select class="cbDiscount form-control" name="cbDiscount" style="height: 25px;">
                                    @for($i = 0; $i <= 10;$i++)
                                        <option value="{!! $i !!}"
                                                @if($orderDiscount == $i) selected="selected" @endif >
                                            {!! $i !!}
                                        </option>
                                    @endfor
                                    @for($y = 15; $y <= 100;$y= $y+5)
                                        <option value="{!! $y !!}"
                                                @if($orderDiscount == $i) selected="selected" @endif>
                                            {!! $y !!}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>VAT <em class="qc-color-red">(10%)</em>:</label>
                                <select class="cbVat form-control" name="cbVat" style="height: 25px;">
                                    <option value="0" @if($orderVat == 0) selected="selected" @endif >Không xuất VAT
                                    </option>
                                    <option value="10" @if($orderVat == 10) selected="selected" @endif>Xuất VAT</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>Ngày Nhận:<i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                <input id="txtDateReceive" type="text" name="txtDateReceive" class="form-control"
                                       value="{!! $orderReceiveDate !!}" style="height: 25px;" placeholder="Ngày nhận">
                                <script type="text/javascript">
                                    qc_main.setDatepicker('#txtDateReceive');
                                </script>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sx-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>Ngày giao:<i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                <input id="txtDateDelivery" type="text" name="txtDateDelivery" class="form-control"
                                       value="{!! $orderDeliveryDate !!}" style="height: 25px;" placeholder="Ngày giao">
                                <script type="text/javascript">
                                    qc_main.setDatepicker('#txtDateDelivery');
                                </script>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 ">
                            <div class="form-group form-group-sm qc-margin-none">
                                <label>Đ/c Thi công:</label>
                                <input type="text" class="form-control" name="txtConstructionAddress"
                                       placeholder="Địa chỉ thi công" style="height: 25px;"
                                       value="{!! $constructionAddress !!}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 ">
                            <div class="form-group form-group-sm qc-margin-none">
                                <label>ĐT liên hệ:</label>
                                <input type="number" class="form-control" name="txtConstructionPhone"
                                       placeholder="Điện thoại liên hệ" style="height: 25px;"
                                       value="{!! $constructionContact !!}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group form-group-sm qc-margin-none">
                                <label>Tên liên hệ:</label>
                                <input type="text" class="form-control" name="txtConstructionContact"
                                       placeholder="Người liên hệ" style="height: 25px;"
                                       value="{!! $constructionAddress !!}">
                            </div>
                        </div>
                    </div>
                    <div class="text-right qc-padding-top-10 qc-padding-bot-10 col-sx-12 col-sm-12 col-md-12 col-lg-12 ">
                        <div class="form-group form-group-sm" style="margin: 0;">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <button type="button" class="qc_save btn btn-sm btn-primary">Lưu thay đổi</button>
                            <button type="reset" class="btn btn-sm btn-default">Nhập lại</button>
                            <button type="button" class="btn btn-sm btn-default"
                                    onclick="qc_main.hide('#qc_work_order_frm_order_edit'); qc_main.show('#qc_work_order_info_order_show');">
                                Đóng
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            {{-- thong tin san pham --}}
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 20px;">
                <div class="row">
                    <div class="qc-color-red col-xs-12 col-sm-12 col-dm-12 col-lg-12"
                         style="border-bottom: 2px solid black;background-color: whitesmoke;">
                        <i class="glyphicon glyphicon-record"></i>
                        <b>SẢM PHẨM</b>
                        @if(!$dataOrders->checkCancelStatus())
                            <a class="qc-link-red pull-right" title="Thêm sản phẩm"
                               href="{!! route('qc.work.orders.add.get',"$customerId/$orderId") !!}">
                                + Thêm SP
                            </a>
                        @else
                            <span class="qc-color-red pull-right">Đã hủy</span>
                        @endif
                    </div>
                </div>
                <div id="qc_work_order_info_product_show" class="row">
                    <div class="qc-container-table col-sx-12 col-sm-12 col-md-12 col-lg-12" style="padding-top: 20px;">
                        <div class="table-responsive">
                            <table class="table table-bordered" style="margin-bottom: 0px;">
                                <tr>
                                    <th class="text-center">STT</th>
                                    <th>Tên SP</th>
                                    <th>Kích thước</th>
                                    <th>Thiết kê</th>
                                    <th class="text-center">Chú thích</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-right">Giá/SP</th>
                                    <th class="text-center">Ngày tạo</th>
                                    <th class="text-right"></th>
                                </tr>
                                @if(count($dataProduct) > 0)
                                    <?php
                                    $n_o = 0;
                                    ?>
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
                                                {!! $product->width() !!} x {!! $product->height() !!}
                                                x {!! (empty($product->depth())?0:$product->depth()) !!} mm
                                            </td>
                                            <td>
                                                @if(!empty($designImage))
                                                    <div style="position: relative; margin-right: 10px; width: 70px; height: 70px; padding: 5px 5px; ">
                                                        <a class="qc_work_order_product_design_image_view qc-link"
                                                           data-href="{!! route('qc.work.orders.product.design.view.get', $productId) !!}">
                                                            <img style="max-width: 100%; max-height: 100%;"
                                                                 src="{!! $product->pathSmallDesignImage($designImage) !!}">
                                                        </a>
                                                        {{--<a class="qc_work_order_product_design_image_delete qc-link"
                                                           data-href="#">
                                                            <i style="position: absolute; font-weight: bold; padding: 0 3px; color: red; top: 3px; right: 3px; border: 1px solid #d7d7d7;">x</i>
                                                        </a>--}}
                                                    </div>
                                                @else
                                                    @if(!$product->checkCancelStatus())
                                                        @if($product->checkFinishStatus())
                                                            <em>---</em>
                                                        @else
                                                            <a class="qc_work_order_product_design_image_add qc-link-green"
                                                               data-href="{!! route('qc.work.orders.product.design.add.get',$productId) !!}">
                                                                Thêm thiết kế
                                                            </a>
                                                        @endif
                                                    @else
                                                        <em>---</em>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                {!! $product->description()  !!}
                                            </td>
                                            <td class="text-center">
                                                {!! $product->amount() !!}
                                            </td>

                                            <td class="text-right">
                                                {!! $hFunction->currencyFormat($product->price()) !!}
                                            </td>
                                            <td class="text-center">
                                                <b>{!! date('d/m/Y', strtotime($product->createdAt())) !!}</b>
                                            </td>
                                            <td class="text-right">
                                                @if(!$product->checkCancelStatus())
                                                    @if($product->checkFinishStatus())
                                                        <em>Đã hoàn thành</em>
                                                    @else
                                                        <a class="qc_work_orders_product_confirm_act qc-link-green"
                                                           data-href="{!! route('qc.work.orders.product.confirm.get',$productId) !!}">
                                                            Báo hoàn thành
                                                        </a>
                                                        @if(!$dataOrders->checkFinishPayment())
                                                            <span>|</span>
                                                            <a class="qc_work_orders_product_cancel_act qc-link-green"
                                                               data-href="{!! route('qc.work.orders.product.cancel.get',$productId) !!}">Hủy</a>
                                                        @endif
                                                    @endif
                                                @else
                                                    <em class="qc-color-red">Đã hủy</em>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-right" colspan="9">
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
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 20px;">
                <div class="row">
                    <div class="qc-color-red col-xs-12 col-sm-12 col-dm-12 col-lg-12"
                         style="border-bottom: 2px solid black;background-color: whitesmoke;">
                        <i class="glyphicon glyphicon-record"></i>
                        <b>THANH TOÁN</b>
                    </div>
                </div>
                <div id="qc_work_order_info_payment_show" class="row">
                    <div class="qc-container-table col-sx-12 col-sm-12 col-md-12 col-lg-12" style="padding-top: 20px;">
                        <div class="table-responsive">
                            <table class="table table-bordered" style="margin-bottom: 0px;">
                                <tr>
                                    <th class="text-center">STT</th>
                                    <th class="text-center">Ngày</th>
                                    <th>Tên</th>
                                    <th class="text-center">Điện thoại</th>
                                    <th class="text-right">Số tiền</th>
                                    <th></th>
                                </tr>
                                <?php
                                $dataOrdersPay = $dataOrders->infoOrderPayOfOrder();
                                ?>
                                @if(count($dataOrdersPay) > 0)
                                    @foreach($dataOrdersPay as $orderPay)
                                        <tr>
                                            <td class="text-center">
                                                {!! $n_o_pay =(isset($n_o_pay)?$n_o_pay+1: 1) !!}
                                            </td>
                                            <td class="text-center">
                                                {!! date('d/m/Y',strtotime($orderPay->datePay()))  !!}
                                            </td>
                                            <td>
                                                @if(!empty($orderPay->payerName()))
                                                    <span>{!! $orderPay->payerName() !!}</span>
                                                @else
                                                    <span>{!! $orderPay->order->customer->name() !!}</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if(!empty($orderPay->payerPhone()))
                                                    <span>{!! $orderPay->payerPhone() !!}</span>
                                                @else
                                                    <span>{!! $orderPay->order->customer->phone() !!}</span>
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                {!! $hFunction->dotNumber($orderPay->money()) !!}
                                            </td>
                                            <td class="text-center">
                                                @if($expiredEditStatus)
                                                    <a class="qc_work_order_info_payment_edit_act qc-link-red"
                                                       data-href="{!! route('qc.work.orders.info.pay.edit.post',$orderPay->payId()) !!}"
                                                       title="Sửa thanh toán">
                                                        <i class=" glyphicon glyphicon-pencil"></i>
                                                    </a>
                                                @else
                                                    <em class="qc-color-grey">---</em>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-center" colspan="6">
                                            Không có thông tin thanh toán
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-dm-12 col-lg-12">
                        <div class="row">
                            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-2 col-lg-2">
                                <i class="glyphicon glyphicon-arrow-right" style="color: blue;"></i>
                                <em>Tổng giá: </em>
                                <b class="qc-color-red pull-right">{!! $hFunction->currencyFormat($dataOrders->totalPrice()) !!}</b>
                            </div>
                            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-2 col-lg-2">
                                <i class="glyphicon glyphicon-arrow-right" style="color: blue;"></i>
                                <em>Giảm: </em>&nbsp;&nbsp;
                                <b class="qc-color-red pull-right">{!! $hFunction->currencyFormat($dataOrders->totalMoneyDiscount()) !!}</b>
                            </div>
                            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-2 col-lg-2">
                                <i class="glyphicon glyphicon-arrow-right" style="color: blue;"></i>
                                <em>VAT: </em>&nbsp;&nbsp;
                                <b class="qc-color-red pull-right">{!! $hFunction->currencyFormat($dataOrders->totalMoneyOfVat()) !!}</b>
                            </div>
                            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-2 col-lg-2">
                                <i class="glyphicon glyphicon-arrow-right" style="color: blue;"></i>
                                <em>Tổng TT: </em>
                                <b class="qc-color-red pull-right">{!! $hFunction->currencyFormat($dataOrders->totalMoneyPayment()) !!}</b>
                            </div>
                            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-2 col-lg-2">
                                <i class="glyphicon glyphicon-arrow-right" style="color: blue;"></i>
                                <em>Đã TT: </em> &nbsp;&nbsp;
                                <b class="qc-color-red pull-right">{!! $hFunction->currencyFormat($dataOrders->totalPaid()) !!}</b>
                            </div>
                            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-2 col-lg-2">
                                <i class="glyphicon glyphicon-arrow-right" style="color: blue;"></i>
                                <em>Còn lại: </em>&nbsp;&nbsp;
                                <b class="qc-color-red pull-right">{!! $hFunction->currencyFormat($dataOrders->totalMoneyUnpaid()) !!}</b>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="text-center qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <button type="button" class="btn btn-sm btn-primary" onclick="qc_main.page_back();">
                Đóng
            </button>
        </div>
    </div>
@endsection
