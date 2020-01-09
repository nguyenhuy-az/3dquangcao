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
$orderId = $dataOrder->orderId();
$customerId = $dataOrder->customerId();
$orderName = $dataOrder->name();
$orderReceiveDate = $dataOrder->receiveDate();
$orderDeliveryDate = $dataOrder->deliveryDate();
$orderDiscount = $dataOrder->discount();
$orderVat = $dataOrder->vat();
$constructionContact = $dataOrder->constructionContact();
$constructionPhone = $dataOrder->constructionPhone();
$constructionAddress = $dataOrder->constructionAddress();
$finishStatus = $dataOrder->checkFinishStatus();
$cancelStatus = $dataOrder->checkCancelStatus();
$createdAt = $dataOrder->createdAt();
$provisionalConfirm = $dataOrder->provisionalConfirm();
$expiredEditStatus = $dataOrder->checkExpiredEdit();
$checkProvisionUnConfirmed = $dataOrder->checkProvisionUnConfirmed($orderId); # don hang dang bao gia
# thong tin san pham
$dataProduct = $dataOrder->allProductOfOrder();
# thong tin khach hang
$dataCustomer = $dataOrder->customer;
$customerName = $dataCustomer->name();
$customerPhone = $dataCustomer->phone();
$customerZalo = $dataCustomer->zalo();
$customerAddress = $dataCustomer->address();

# trang thai sua
$editInfoStatus = false;
if (!$cancelStatus) {
    if ($checkProvisionUnConfirmed) { # don hang dang bao gia
        $editInfoStatus = true;
    } else {
        if (!$finishStatus) { // chua ket thuc
            if ($expiredEditStatus) { // con han sua thong tin
                $editInfoStatus = true;
            }
        }
    }
} else {
    $dataOrderCancel = $dataOrder->orderCancelInfo();
}

?>
@extends('work.orders.index')
@section('titlePage')
    Sản phẩm
@endsection
@section('qc_work_order_body')
    <div class="row qc_work_orders_wrap">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-10">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <a class=" qc-link-red" onclick="qc_main.page_back_go({!! $pageBack !!});">
                    <i class="qc-font-size-14 glyphicon glyphicon-backward"></i>
                    <span class="qc-font-size-16" style="color: blue;">Trởlại</span>
                </a>
            </div>
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                @if($checkProvisionUnConfirmed)
                    <label class="qc-color-red qc-font-size-20">BÁO GIÁ:</label>
                @else
                    <label class="qc-color-red qc-font-size-20">ĐƠN HÀNG:</label>
                    <em class="pull-right qc-color-red">
                        Hạn sửa: {!! date('d/m/Y H:i', strtotime($hFunction->datetimePlusHour($createdAt, 8))) !!}
                    </em>
                @endif
                <label class="qc-font-size-20">{!! $orderName !!}</label>
                @if($cancelStatus)
                    <em class="qc-font-size-14" style="color: blue;"> - (Đã hủy)</em>
                @endif
            </div>
            {{-- thong tin don hang --}}
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 10px;">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-dm-12 col-lg-12"
                         style="border-bottom: 1px solid black;">
                        {{--<i class="qc-font-size-16 glyphicon glyphicon-list-alt"></i>--}}
                        {{--<label class="qc-color-red ">ĐƠN HÀNG</label>--}}
                        @if($editInfoStatus)
                            <a class="qc-link-red pull-right" title="Sửa thông tin"
                               onclick="qc_main.toggle('#qc_work_order_info_order_show'); qc_main.toggle('#qc_work_order_frm_order_edit');">
                                <i class=" glyphicon glyphicon-pencil"></i>
                            </a>
                        @else
                            <em class="pull-right">---</em>
                        @endif
                    </div>
                </div>
                <div id="qc_work_order_info_order_show" class="row">
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
                                @if(!$checkProvisionUnConfirmed)
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
                                @else
                                    <tr>
                                        <td>
                                            <em class="qc-color-grey">Ngày báo:</em>
                                        </td>
                                        <td class="text-right">
                                            <b>{!! date('d/m/Y', strtotime($dataOrder->provisionalDate())) !!}</b>
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                    <div class="qc-container-table qc-container-table-border-none qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-6">
                        <div class="table-responsive">
                            <table class="table table-hover qc-margin-bot-none">
                                <tr>
                                    <td>
                                        <em class=" qc-color-grey">Giảm %:</em>
                                    </td>
                                    <td class="text-right">
                                        <b>{!! $orderDiscount !!}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <em class="qc-color-grey">Xuất VAT:</em>
                                    </td>
                                    <td class="text-right">
                                        <b>@if(count($orderVat) > 0)
                                                Có
                                            @else
                                                Không
                                            @endif</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <em class="qc-color-grey">Đ/c thi công:</em>
                                    </td>
                                    <td class="text-right">
                                        <span class="pull-right">{!! $dataOrder->constructionAddress() !!}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <em class="qc-color-grey">Liên lạc:</em>
                                    </td>
                                    <td class="text-right">
                                        <span class="pull-right">
                                            - ĐT: {!! $dataOrder->constructionPhone() !!}
                                            - tên: {!! $dataOrder->constructionContact() !!}
                                        </span>
                                    </td>
                                </tr>
                            </table>
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
                        <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>Tên <i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                <input type="text" class="form-control" name="txtOrderName"
                                       placeholder="Nhập tên đơn hàng"
                                       value="{!! $orderName !!}">
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-2 col-lg-2">
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
                        <div class="col-sx-12 col-sm-12 col-md-2 col-lg-2">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>VAT <em class="qc-color-red">(10%)</em>:</label>
                                <select class="cbVat form-control" name="cbVat" style="height: 25px;">
                                    <option value="0" @if($orderVat == 0) selected="selected" @endif >Không xuất VAT
                                    </option>
                                    <option value="10" @if($orderVat == 10) selected="selected" @endif>Xuất VAT</option>
                                </select>
                            </div>
                        </div>
                        @if(!$checkProvisionUnConfirmed)
                            <div class="col-sx-12 col-sm-12 col-md-2 col-lg-2">
                                <div class="form-group form-group-sm" style="margin: 0;">
                                    <label>Ngày Nhận:<i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                    <input id="txtDateReceive" type="hidden" name="txtDateReceive" class="form-control"
                                           value="{!! $orderReceiveDate !!}" style="height: 25px;">
                                    <input type="text" name="txtDateReceiveShow" class="form-control"
                                           value="{!! $orderReceiveDate !!}" style="height: 25px;" disabled="disabled">
                                </div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-2 col-lg-2">
                                <div class="form-group form-group-sm" style="margin: 0;">
                                    <label>Ngày giao:<i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                    <input id="txtDateDelivery" type="text" name="txtDateDelivery" class="form-control"
                                           value="{!! $orderDeliveryDate !!}" style="height: 25px;"
                                           placeholder="Ngày giao">
                                    <script type="text/javascript">
                                        qc_main.setDatepicker('#txtDateDelivery');
                                    </script>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 ">
                            <div class="form-group form-group-sm qc-margin-none">
                                <label>Đ/c Thi công:</label>
                                <input type="text" class="form-control" name="txtConstructionAddress"
                                       placeholder="Địa chỉ thi công" style="height: 25px;"
                                       value="{!! $constructionAddress !!}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 ">
                            <div class="form-group form-group-sm qc-margin-none">
                                <label>ĐT liên hệ:</label>
                                <input type="number" class="form-control" name="txtConstructionPhone"
                                       placeholder="Điện thoại liên hệ" style="height: 25px;"
                                       value="{!! $constructionContact !!}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                            <div class="form-group form-group-sm qc-margin-none">
                                <label>Tên liên hệ:</label>
                                <input type="text" class="form-control" name="txtConstructionContact"
                                       placeholder="Người liên hệ" style="height: 25px;"
                                       value="{!! $constructionContact !!}">
                            </div>
                        </div>
                    </div>
                    <div class="text-right qc-padding-top-10 qc-padding-bot-10 col-sx-12 col-sm-12 col-md-12 col-lg-12 ">
                        <div class="form-group form-group-sm" style="margin: 0;">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <input type="hidden" name="txtProvisionalConfirm" value="{!! $provisionalConfirm !!}">
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
            {{-- thong tin khach hang --}}
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 10px;">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-dm-12 col-lg-12" style="border-bottom: 1px solid black;">
                        <i class="qc-font-size-16 glyphicon glyphicon-user"></i>
                        <label class="qc-color-red">KHÁCH HÀNG</label>
                        @if($editInfoStatus)
                            <a class="qc-link-red pull-right" title="Sửa thông tin"
                               onclick="qc_main.toggle('#qc_work_order_info_customer_show'); qc_main.toggle('#qc_work_order_frm_customer_edit');">
                                <i class=" glyphicon glyphicon-pencil"></i>
                            </a>
                        @else
                            <em class="pull-right">---</em>
                        @endif
                    </div>
                </div>
                <div id="qc_work_order_info_customer_show" class="row">
                    <div class="qc-container-table qc-container-table-border-none qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-6">
                        <div class="table-responsive">
                            <table class="table table-hover qc-margin-bot-none">
                                <tr>
                                    <td>
                                        <em class=" qc-color-grey">Tên:</em>
                                    </td>
                                    <td class="text-right">
                                        <b>{!! $customerName !!}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <em class="qc-color-grey">Địa chỉ:</em>
                                    </td>
                                    <td class="text-right">
                                        <b>{!! $customerAddress !!}</b>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="qc-container-table qc-container-table-border-none qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-6">
                        <div class="table-responsive">
                            <table class="table table-hover qc-margin-bot-none">
                                <tr>
                                    <td>
                                        <em class=" qc-color-grey">Điện thoại:</em>
                                    </td>
                                    <td class="text-right">
                                        <b>{!! $customerPhone !!}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <em class="qc-color-grey">Zalo:</em>
                                    </td>
                                    <td class="text-right">
                                        <b>{!! $customerZalo !!}</b>
                                    </td>
                                </tr>
                            </table>
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

            {{-- thong tin san pham --}}
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 10px;">
                <div class="row">
                    <div class=" col-xs-12 col-sm-12 col-dm-12 col-lg-12" style="border-bottom: 1px solid black;">
                        <i class="qc-font-size-16 glyphicon glyphicon-shopping-cart"></i>
                        <label class="qc-color-red">SẢM PHẨM</label>
                        @if(!$dataOrder->checkCancelStatus())
                            @if($finishStatus)
                                <em class="qc-color-grey pull-right">ĐH đã kết thúc</em>
                            @else
                                <a class="qc-link-red-bold pull-right" title="Thêm sản phẩm"
                                   href="{!! route('work.orders.edit.addProduct.get',$orderId) !!}">
                                    + Thêm SP
                                </a>
                            @endif
                        @else
                            <span class="qc-color-red pull-right">Đã hủy</span>
                        @endif
                    </div>
                </div>
                <div id="qc_work_order_info_product_show" class="row">
                    <div class="qc-container-table col-sx-12 col-sm-12 col-md-12 col-lg-12" style="padding-top: 10px;">
                        <div class="table-responsive">
                            <table class="table table-bordered" style="margin-bottom: 0px;">
                                <tr style="background-color: whitesmoke;">
                                    <th class="text-center" style="width: 20px;">STT</th>
                                    <th>Ngày đặt</th>
                                    <th>Tên SP</th>
                                    <th>Chú thích</th>
                                    <th>Thiết kê</th>
                                    <th class="text-right">Thi công</th>
                                    <th class="text-center">Dài<br/>(m)</th>
                                    <th class="text-center">Rộng<br/>(m)</th>
                                    <th class="text-center">Diện tích, m2 <br/>m, cái, cây, bộ...</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-right">
                                        Giá/SP(VNĐ)
                                    </th>
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
                                                <b>{!! date('d/m/Y', strtotime($product->createdAt())) !!}</b>
                                            </td>
                                            <td>
                                                {!! $product->productType->name()  !!}
                                            </td>
                                            <td>
                                                {!! $product->description()  !!}
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
                                            <td class="text-right">
                                                @if(!$product->checkCancelStatus())
                                                    @if($product->checkFinishStatus())
                                                        <em>Đã hoàn thành</em>
                                                    @else
                                                        <a class="qc_work_orders_product_confirm_act qc-link-green"
                                                           data-href="{!! route('qc.work.orders.product.confirm.get',$productId) !!}">
                                                            Báo hoàn thành
                                                        </a>
                                                        <br/>
                                                        <a class="qc_work_orders_product_edit_act qc-link"
                                                           title="Sửa thông tin sản phẩm"
                                                           data-href="{!! route('qc.work.orders.orders.product.info.edit.get',$productId) !!}">
                                                            <i class="qc-font-size-14 glyphicon glyphicon-pencil"></i>
                                                        </a>
                                                        <span>&nbsp;|&nbsp;</span>
                                                        <a class="qc_work_orders_product_cancel_act qc-link-red"
                                                           title="Hủy sản phẩm"
                                                           data-href="{!! route('qc.work.orders.orders.product_cancel.get',$productId) !!}">
                                                            <i class="qc-font-size-14 glyphicon glyphicon-trash"></i>
                                                        </a>
                                                    @endif
                                                @else
                                                    <em class="qc-color-red">Đã hủy</em>
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
                                                {!! $hFunction->currencyFormat($product->price()) !!}
                                            </td>

                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-center" colspan="11">
                                            Không có sản phẩm
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>

            </div>

            @if(!$checkProvisionUnConfirmed)
                {{-- thong tin thanh toan --}}
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 10px;">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-dm-12 col-lg-12" style="border-bottom: 1px solid black;">
                            <i class="qc-font-size-16 glyphicon glyphicon-credit-card"></i>
                            <label class="qc-color-red">THANH TOÁN</label>
                        </div>
                    </div>
                    <div id="qc_work_order_info_payment_show" class="row">
                        <div class="qc-container-table col-sx-12 col-sm-12 col-md-12 col-lg-12" style="padding-top: 10px;">
                            <div class="table-responsive">
                                <table class="table table-bordered" style="margin-bottom: 0px;">
                                    <tr style="background-color: whitesmoke;">
                                        <th class="text-center" style="width: 20px;">STT</th>
                                        <th>Ngày</th>
                                        <th>Tên</th>
                                        <th>Ghi chú</th>
                                        <th class="text-center">Điện thoại</th>
                                        <th class="text-right">Số tiền</th>
                                        <th></th>
                                    </tr>
                                    <?php
                                    $dataOrderPay = $dataOrder->infoOrderPayOfOrder();
                                    ?>
                                    @if(count($dataOrderPay) > 0)
                                        @foreach($dataOrderPay as $orderPay)
                                            <tr>
                                                <td class="text-center">
                                                    {!! $n_o_pay =(isset($n_o_pay)?$n_o_pay+1: 1) !!}
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
                                                    {!! $orderPay->note()  !!}
                                                </td>
                                                <td class="text-center">
                                                    @if(!empty($orderPay->payerPhone()))
                                                        <span>{!! $orderPay->payerPhone() !!}</span>
                                                    @else
                                                        <span>{!! $orderPay->order->customer->phone() !!}</span>
                                                    @endif
                                                </td>
                                                <td class="text-right">
                                                    {!! $hFunction->currencyFormat($orderPay->money()) !!}
                                                </td>
                                                <td class="text-center">
                                                    @if(!$cancelStatus)
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
                                            <td class="text-center" colspan="7">
                                                Không có thông tin thanh toán
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($cancelStatus)
                @if(count($dataOrderCancel) > 0)
                    {{-- thong tin huy --}}
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 10px;">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-dm-12 col-lg-12"
                                 style="border-bottom: 1px solid black;">
                                <i class="qc-font-size-16 glyphicon glyphicon-trash"></i>
                                <label class="qc-color-red">HỦY ĐƠN HÀNG</label>
                            </div>
                        </div>
                        <div id="qc_work_order_info_payment_show" class="row">
                            <div class="qc-container-table col-sx-12 col-sm-12 col-md-12 col-lg-12"
                                 style="padding-top: 20px;">
                                <div class="table-responsive">
                                    <table class="table table-bordered" style="margin-bottom: 0px;">
                                        <tr>
                                            <th>Ngày</th>
                                            <th>Lý do hủy</th>
                                            <th class="text-right">Hoàn tiền</th>
                                        </tr>
                                        <tr>
                                            <td>
                                                {!! date('d/m/Y',strtotime($dataOrderCancel->cancelDate()))  !!}
                                            </td>
                                            <td>
                                                {!! $dataOrderCancel->reason() !!}
                                            </td>
                                            <td class="text-right">
                                                {!! $hFunction->currencyFormat($dataOrderCancel->payment()) !!}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif


            <div class="qc-container-table qc-container-table-border-none pull-right col-xs-12 col-sm-12 col-dm-6 col-lg-4">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-dm-12 col-lg-12">
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

        </div>
    </div>
@endsection
