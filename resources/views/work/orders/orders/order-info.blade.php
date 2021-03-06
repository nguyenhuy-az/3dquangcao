\<?php
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
$dataStaffLogin = $modelStaff->loginStaffInfo();
$loginStaffId = $dataStaffLogin->staffId();
# thong tin don hang
$orderId = $dataOrder->orderId();
$customerId = $dataOrder->customerId();
$orderName = $dataOrder->name();
$orderReceiveDate = $dataOrder->receiveDate();
$orderDeliveryDate = $dataOrder->deliveryDate();
$orderDiscount = $dataOrder->discount();
$orderVat = $dataOrder->vat();
$checkHasVat = $dataOrder->checkHasVAT();
$constructionContact = $dataOrder->constructionContact();
$constructionPhone = $dataOrder->constructionPhone();
$constructionAddress = $dataOrder->constructionAddress();
$finishStatus = $dataOrder->checkFinishStatus();
$cancelStatus = $dataOrder->checkCancelStatus();
$createdAt = $dataOrder->createdAt();
$provisionalConfirm = $dataOrder->provisionalConfirm();
$expiredEditStatus = $dataOrder->checkExpiredEdit();
$checkProvisionUnConfirmed = $dataOrder->checkProvisionUnConfirmed($orderId); # don hang dang bao gia.
# tong tien chua giam gia
$orderTotalPrice = $dataOrder->totalPrice();
# tong tien giam
$orderTotalDiscount = $dataOrder->totalMoneyDiscount();
# tong tien VAT
$orderTotalVat = $dataOrder->totalMoneyOfVat();
# thong tin san pham
$dataProduct = $dataOrder->productActivityOfOrder();
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

#anh thiet ke tong quat
$dataOrderImage = $dataOrder->orderImageInfoActivity();


?>
@extends('work.orders.index')
@section('titlePage')
    Quản lý đơn hàng
@endsection
@section('qc_work_order_body')
    <div id="qc_work_orders_wrap" class="row qc_work_orders_wrap">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="padding-bottom: 5px;">
                <a class="btn btn-sm btn-primary" onclick="qc_main.page_back();">
                    Về trang trước
                </a>
            </div>
            {{-- thong tin don hang --}}
            <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6" style="margin-bottom: 10px;">
                <div class="row">
                    <div class="col-sx-8 col-sm-8 col-md-8 col-lg-8">
                        @if($checkProvisionUnConfirmed)
                            <label class="qc-color-red qc-font-size-20">BÁO GIÁ:</label>
                        @else
                            <label class="qc-color-red qc-font-size-20">ĐƠN HÀNG:</label>
                        @endif
                        <label class="qc-font-size-20">{!! $orderName !!}</label>
                    </div>
                    <div class="text-right col-sx-4 col-sm-4 col-md-4 col-lg-4">
                        @if($cancelStatus)
                            <em style="background-color: red; padding: 3px; color: yellow;"> ĐÃ HỦY</em>
                        @else
                            @if($finishStatus)
                                <em style="background-color: red; padding: 3px; color: yellow;">ĐÃ KẾT THÚC</em>
                            @else
                                @if($editInfoStatus)
                                    <a class="qc-link-red pull-right" title="Sửa thông tin"
                                       onclick="qc_main.toggle('#qc_work_order_info_order_show'); qc_main.toggle('#qc_work_order_frm_order_edit');">
                                        <i class=" glyphicon glyphicon-pencil"></i>
                                    </a>
                                @else
                                    <label>Hạn sửa: </label>
                                    <em class="pull-right qc-color-red">
                                        {!! date('d/m/Y H:i', strtotime($hFunction->datetimePlusHour($createdAt, 8))) !!}
                                    </em>
                                @endif

                            @endif
                        @endif
                    </div>
                </div>
                <div id="qc_work_order_info_order_show" class="row">
                    <div class="qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-6">
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
                                            <b>{!! $hFunction->convertDateDMYFromDatetime($dataOrder->receiveDate()) !!}</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <em class="qc-color-grey">Ngày giao:</em>
                                        </td>
                                        <td class="text-right">
                                            <b>{!! $hFunction->convertDateDMYFromDatetime($dataOrder->deliveryDate()) !!}</b>
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td>
                                            <em class="qc-color-grey">Ngày báo:</em>
                                        </td>
                                        <td class="text-right">
                                            <b>{!! $hFunction->convertDateDMYFromDatetime($dataOrder->provisionalDate()) !!}</b>
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                    <div class="qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-6">
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
                                        <b>
                                            @if($checkHasVat)
                                                Có
                                            @else
                                                Không
                                            @endif
                                        </b>
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
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
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
            <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6" style="margin-bottom: 10px;">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-dm-12 col-lg-12" style="border-bottom: 1px solid black;">
                        <i class="qc-font-size-16 glyphicon glyphicon-user"></i>
                        <label class="qc-color-red qc-font-size-16">KHÁCH HÀNG</label>
                        @if($editInfoStatus)
                            <a class="qc-link-red pull-right" title="Sửa thông tin"
                               onclick="qc_main.toggle('#qc_work_order_info_customer_show'); qc_main.toggle('#qc_work_order_frm_customer_edit');">
                                <i class=" glyphicon glyphicon-pencil"></i>
                            </a>
                        @endif
                    </div>
                </div>
                <div id="qc_work_order_info_customer_show" class="row">
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-6">
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
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-6">
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
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
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
                    <div class=" col-xs-12 col-sm-12 col-dm-12 col-lg-12">
                        <i class="qc-font-size-16 glyphicon glyphicon-shopping-cart"></i>
                        <label class="qc-color-red qc-font-size-16">SẢM PHẨM</label>
                        @if(!$dataOrder->checkCancelStatus())
                            @if(!$finishStatus)
                                <a class="qc-link-red-bold pull-right" title="Thêm sản phẩm"
                                   href="{!! route('work.orders.edit.addProduct.get',$orderId) !!}">
                                    <i class="glyphicon glyphicon-plus qc-font-size-16"></i>
                                    <span class="qc-font-size-16">THÊM SẢN PHẨM</span>
                                </a>
                            @endif
                        @else
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
                                        $productDescription = $product->description();
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
                                                <em style="color: red;">
                                                    {!! $hFunction->currencyFormat($product->price()) !!} đ
                                                </em>
                                                <br/>
                                                <em>{!! $hFunction->convertDateDMYFromDatetime($product->createdAt()) !!}</em>
                                                <br/>
                                                <em>- Ngang: </em>
                                                <span> {!! $productWidth !!} mm</span>
                                                <em>- Cao: </em>
                                                <span>{!! $productHeight !!} mm</span>
                                                <em>- Số lượng: </em>
                                                <span style="color: red;">{!! $productAmount !!}</span>
                                                @if(!$hFunction->checkEmpty($productDescription))
                                                    <br/>
                                                    <em>- Ghi chú: </em>
                                                    <em style="color: grey;">- {!! $productDescription !!}</em>
                                                @endif
                                                @if(!$product->checkCancelStatus())
                                                    @if($product->checkFinishStatus())
                                                        <em style="color: grey;">Đã hoàn thành</em>
                                                    @else
                                                        <br/>
                                                        <a class="qc_work_orders_product_edit_act qc-link-green-bold"
                                                           title="Sửa thông tin sản phẩm"
                                                           data-href="{!! route('qc.work.orders.orders.product.info.edit.get',$productId) !!}">
                                                            SỬA
                                                        </a>
                                                        <span>&nbsp;|&nbsp;</span>
                                                        <a class="qc_work_orders_product_cancel_act qc-link-red"
                                                           title="Hủy sản phẩm"
                                                           data-href="{!! route('qc.work.orders.orders.product_cancel.get',$productId) !!}">
                                                            HỦY
                                                        </a>
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
                                                <br/>
                                                @if(!$product->checkCancelStatus())
                                                    @if(!$product->checkFinishStatus())
                                                        <a class="qc_work_order_product_design_image_add qc-link-red qc-font-size-14"
                                                           data-href="{!! route('qc.work.orders.product.design.add.get',$productId) !!}">
                                                            THÊM
                                                        </a>
                                                        <span>&nbsp;|&nbsp;</span>
                                                    @endif
                                                @endif
                                                <a class="qc-link-green qc-font-size-14"
                                                   href="{!! route('qc.work.orders.product.design.get',$productId) !!}">
                                                    CHI TIẾT
                                                    <em title="Số mẫu thiết kế">
                                                        ({!! $product->totalProductDesign() !!})
                                                    </em>
                                                </a>
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
                                                <br/>
                                                @if(!$product->checkCancelStatus())
                                                    @if(!$product->checkFinishStatus())
                                                        <a class="qc_work_order_product_design_image_add qc-link-red qc-font-size-14"
                                                           data-href="{!! route('qc.work.orders.product.design_construction.add.get',$productId) !!}">
                                                            THÊM
                                                        </a>
                                                        &nbsp;|&nbsp;
                                                    @endif
                                                @endif
                                                <a class="qc-link-green qc-font-size-14"
                                                   href="{!! route('qc.work.orders.product.design_construction.get',$productId) !!}">
                                                    CHI TIẾT
                                                </a>
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

            @if($cancelStatus)
                @if($hFunction->checkCount($dataOrderCancel))
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
                                            <th>Số tiền</th>
                                            <th>Lý do hủy</th>
                                            <th class="text-right">Hoàn tiền</th>
                                        </tr>
                                        <tr>
                                            <td>
                                                {!! $hFunction->convertDateDMYFromDatetime($dataOrderCancel->cancelDate())  !!}
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
            @if(!$checkProvisionUnConfirmed)
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
                                        <th style="width: 120px;">Số tiền Ngày</th>
                                        <th>Người thu</th>
                                        <th>Tên người nộp</th>
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
                                                    <label style="color: red;">{!! $hFunction->currencyFormat($orderPay->money()) !!}</label>
                                                    <br/>
                                                    <em style="color: grey;">{!! $hFunction->convertDateDMYFromDatetime($orderPay->datePay())  !!}</em>
                                                    @if(!$cancelStatus && !$finishStatus)
                                                        @if($orderPay->checkOwnerStatusOfStaff($loginStaffId,$payId))
                                                            <br/>
                                                            <a class="qc_work_order_info_payment_edit_act qc-link-green qc-font-size-14"
                                                               data-href="{!! route('qc.work.orders.info.pay.edit.post',$payId) !!}"
                                                               title="Sửa thanh toán">
                                                                SỬA
                                                            </a>
                                                        @endif
                                                    @endif
                                                    @if($hFunction->checkEmpty($payNote))
                                                        <br/>
                                                        <em style="color: red;">{!! $payNote  !!}</em>
                                                    @endif
                                                </td>
                                                <td style="width: 170px;">
                                                    <div class="media">
                                                        <a class="pull-left" href="#">
                                                            <img class="media-object"
                                                                 style="max-width: 40px;height: 40px; border: 1px solid #d7d7d7;border-radius: 10px;"
                                                                 src="{!! $dataReceiveStaff->pathAvatar($dataReceiveStaff->image()) !!}">
                                                        </a>

                                                        <div class="media-body">
                                                            <h5 class="media-heading">{!! $dataReceiveStaff->lastName() !!}</h5>
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
                                            <td class="text-center" colspan="3">
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
            <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6">
                <div class="row">
                    <div class="qc-container-table-border-none col-xs-12 col-sm-12 col-dm-12 col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-hover qc-margin-bot-none">
                                <tr>
                                    <td>
                                        <em class=" qc-color-grey">Thành tiền:</em>
                                    </td>
                                    <td class="text-right">
                                        <b>{!! $hFunction->currencyFormat($orderTotalPrice) !!}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <em class="qc-color-grey">Giảm {!! $dataOrder->discount() !!}%:</em>
                                    </td>
                                    <td class="text-right">
                                        <b>- {!! $hFunction->currencyFormat($orderTotalDiscount) !!}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <em class="qc-color-grey">Tổng tiền chưa VAT:</em>
                                    </td>
                                    <td class="text-right">
                                        <b style="color: red;">
                                            {!! $hFunction->currencyFormat($orderTotalPrice - $orderTotalDiscount) !!}
                                        </b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <em class="qc-color-grey">VAT {!! $dataOrder->vat() !!}%:</em>
                                    </td>
                                    <td class="text-right">
                                        <b>+ {!! $hFunction->currencyFormat($orderTotalVat) !!}</b>
                                    </td>
                                </tr>
                                <tr style="border-top: 1px solid grey;">
                                    <td>
                                        <em class="qc-color-grey">Tổng tiền có VAT:</em>
                                    </td>
                                    <td class="text-right">
                                        <b class="qc-color-red">{!! $hFunction->currencyFormat($orderTotalPrice - $orderTotalDiscount + $orderTotalVat ) !!}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <em class="qc-color-grey">Đã thanh toán:</em>
                                    </td>
                                    <td class="text-right">
                                        <b>- {!! $hFunction->currencyFormat($dataOrder->totalPaid()) !!}</b>
                                    </td>
                                </tr>
                                <tr style="border-top: 1px solid grey;">
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
            {{-- thiet ke tong the --}}
            <div class="row">
                <div class="qc-container-table-border-none col-xs-12 col-sm-12 col-dm-12 col-lg-12">
                    <div class="table-responsive" style="margin: 0;">
                        <table class="table table-bordered" style="margin-bottom: 0px;">
                            @if(!$finishStatus)
                                <tr>
                                    <td>
                                        <a class="qc_work_order_design_image_add qc-link-green-bold"
                                           data-href="{!! route('qc.work.orders.info.design.add.get',$orderId) !!}">
                                            <i class="glyphicon glyphicon-plus"></i>
                                            <span class="qc-font-size-14">THÊM THIẾT KẾ TỔNG THỂ</span>
                                        </a>
                                    </td>
                                </tr>
                            @endif
                            @if($hFunction->checkCount($dataOrderImage))
                                @foreach($dataOrderImage as $orderImage)
                                    <tr>
                                        <td style="padding-top: 5px !important;">
                                            <a class="qc_work_order_design_image_delete qc-link-red-bold qc-font-size-14"
                                               data-href="{!! route('qc.work.orders.info.design.delete',$orderImage->imageId()) !!}">
                                                HỦY
                                            </a>
                                            <br/>
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
