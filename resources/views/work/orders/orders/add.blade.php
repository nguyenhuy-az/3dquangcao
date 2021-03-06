<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 *
 * dataProductType
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$currentDate = $hFunction->carbonNow();
$currentMonth = $hFunction->currentMonth();
$currentYear = $hFunction->currentYear();
$mobileStatus = $mobile->isMobile();
if ($hFunction->checkCount($dataCustomer)) {
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
if ($hFunction->checkCount($dataOrders)) {
    $orderName = $dataOrders->name();
    $orderConstructionAddress = $dataOrders->constructionAddress();
    $orderConstructionPhone = $dataOrders->constructionPhone();
    $orderConstructionContact = $dataOrders->constructionContact();
} else {
    $orderName = null;
    $orderConstructionAddress = null;
    $orderConstructionPhone = null;
    $orderConstructionContact = null;
}

$returnHref = $hFunction->getUrlReferer();// route('qc.work.orders.get'); # them don hang moi -> ve danh sach don hang
$provisionalStatus = ($orderType == 1) ? false : true;
if ($provisionalStatus) {
    $hrefPost = route('work.orders.add.provisional.post');
} else {
    $hrefPost = route('work.orders.add.post');
}
?>
@extends('work.orders.index')
@section('titlePage')
    Tạo đơn hàng
@endsection
@section('qc_work_order_body')
    {{--<script type="text/javascript">
        window.onbeforeunload = function () {
            return "Dude, are you sure you want to leave? Think of the kittens!";
        }
    </script>--}}
    <div class="row qc_work_orders_wrap">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-10">
            @if (!$provisionalStatus)
                <label class="qc-font-size-20" style="color: red;">THÊM ĐƠN HÀNG</label>
                {{--<span class="qc-font-size-20 qc-color-red"><i class="glyphicon glyphicon-minus"></i> THỰC</span>--}}
            @else
                <label class="qc-font-size-20" style="color: red;">ĐƠN HÀNG BÁO GIÁ</label>
                {{--<span class="qc-font-size-20 qc-color-red"><i class="glyphicon glyphicon-minus"></i> BÁO GIÁ</span>--}}
            @endif

        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-10">
            <form id="frmWorkOrdersAdd" role="form" method="post" enctype="multipart/form-data"
                  action="{!! $hrefPost !!}">
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
                {{-- thông tin khách hàng --}}
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                             style="border-bottom: 1px solid grey;">
                            <i class="glyphicon glyphicon-pencil qc-color-green"></i>
                            <span class="qc-font-size-16" style="color: blue;">Khách Hàng</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                            <div class="form-group form-group-sm qc-margin-none">
                                <label>Điện thoại:</label>
                                <input type="number" class="txtPhone form-control" name="txtPhone"
                                       data-href-check="{!! route('qc.work.orders.add.customer.check.phone') !!}"
                                       data-href-replace="{!! route('qc.work.orders.add.get',$orderType) !!}"
                                       placeholder="Số điện thoại"
                                       @if($hFunction->checkCount($dataCustomer)) readonly="true"
                                       @endif value="{!! $customerPhone !!}">
                            </div>
                            <div id="qc_customer_phone_suggestions_wrap"
                                 class="qc-display-none col-xs-12 col-sm-12 col-md-12 col-lg-12"
                                 style="border: 1px solid #d7d7d7;">
                                <a class='pull-right qc-link-red'
                                   onclick="qc_main.hide('#qc_customer_phone_suggestions_wrap');">X</a>

                                <div class="row">
                                    <div id="qc_customer_phone_suggestions_content"
                                         class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="form-group form-group-sm qc-margin-none ">
                                <label>Tên Khách hàng:
                                    <i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                <input type="text" class="txtCustomerName form-control" name="txtCustomerName"
                                       placeholder="Nhập tên khách hàng"
                                       data-href-check-name="{!! route('qc.work.orders.add.customer.check.name') !!}"
                                       data-href-replace="{!! route('qc.work.orders.add.get',$orderType) !!}"
                                       @if($hFunction->checkCount($dataCustomer)) readonly="true"
                                       @endif value="{!! $customerName !!}">
                            </div>
                            <div id="qc_customer_name_suggestions_wrap"
                                 class="qc-display-none col-xs-12 col-sm-12 col-md-12 col-lg-12"
                                 style="border: 1px solid #d7d7d7;">
                                <a class='pull-right qc-link-red'
                                   onclick="qc_main.hide('#qc_customer_name_suggestions_wrap');">X</a>

                                <div class="row">
                                    <div id="qc_customer_name_suggestions_content"
                                         class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                            <div class="form-group form-group-sm qc-margin-none">
                                <label>Zalo:</label>
                                <input type="text" class="form-control" name="txtZalo" placeholder="Số zalo"
                                       value="{!! $customerZalo !!}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="form-group form-group-sm qc-margin-none">
                                <label>Địa chỉ:</label>
                                <input type="text" class="form-control" name="txtAddress" placeholder="Địa chỉ"
                                       value="{!! $customerAddress !!}">
                            </div>
                        </div>
                        @if($hFunction->checkCount($dataCustomer))
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group form-group-sm qc-margin-none">
                                    <a class="qc-link-green" href="{!! route('qc.work.orders.add.get',$orderType) !!}">
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
                            <span class="qc-font-size-16" style="color: blue;">Đơn hàng</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="form-group form-group-sm qc-margin-none">
                                <label>Tên ĐH (Tên thương hiệu):
                                    <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                </label>
                                <input type="text" class="txtOrderName form-control" name="txtOrderName"
                                       data-href-check-name="{!! route('qc.work.orders.add.order.check.name') !!}"
                                       placeholder="VD: Phở tân, Bom, Thảo tây ...." value="{!! $orderName !!}">
                            </div>
                            <div id="qc_order_add_name_suggestions_wrap"
                                 class="qc-display-none col-xs-12 col-sm-12 col-md-12 col-lg-12"
                                 style="border: 1px solid #d7d7d7;">
                                <a class='pull-right qc-link-red'
                                   onclick="qc_main.hide('#qc_order_add_name_suggestions_wrap');">X</a>

                                <div class="row">
                                    <div id="qc_order_name_suggestions_content"
                                         class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="form-group form-group-sm qc-margin-none">
                                <label>Đ/c Thi công:</label>
                                <input type="text" class="txtConstructionAddress form-control"
                                       name="txtConstructionAddress"
                                       placeholder="Địa chỉ thi công" value="{!! $orderConstructionAddress !!}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                            <div class="form-group form-group-sm qc-margin-none">
                                <label>ĐT liên hệ:</label>
                                <input type="number" class="txtConstructionPhone form-control"
                                       name="txtConstructionPhone"
                                       placeholder="Điện thoại liên hệ" value="{!! $orderConstructionPhone !!}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                            <div class="form-group form-group-sm qc-margin-none">
                                <label>Tên liên hệ:</label>
                                <input type="text" class="txtConstructionContact form-control"
                                       name="txtConstructionContact"
                                       placeholder="Người liên hệ" value="{!! $orderConstructionContact !!}">
                            </div>
                        </div>
                    </div>
                    @if($provisionalStatus)
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-2">
                                <div class="form-group form-group-sm qc-margin-none">
                                    <label>Tổng tiền:</label>
                                    <input type="text" class="form-control" name="txtTotalPriceShow" disabled="disabled" value="0">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                                <div class="form-group form-group-sm qc-margin-none">
                                    <label>Giảm giá <em class="qc-color-red">(%)</em></label>
                                    <select class="cbDiscount form-control" name="cbDiscount">
                                        @for($i = 0; $i <= 10;$i++)
                                            <option value="{!! $i !!}"
                                                    @if($hFunction->checkCount($dataCustomer) > 0 && $i == 5) selected="selected" @endif >
                                                {!! $i !!}
                                            </option>
                                        @endfor
                                        @for($y = 15; $y <= 100;$y= $y+5)
                                            <option value="{!! $y !!}">{!! $y !!}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                                <div class="form-group form-group-sm qc-margin-none">
                                    <label>VAT <em class="qc-color-red">(10%)</em>:</label>
                                    <select class="cbVat form-control" name="cbVat">
                                        <option value="0">Không xuất VAT</option>
                                        <option value="10">Xuất VAT</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                                <div class="form-group form-group-sm qc-margin-none">
                                    <label>
                                        Tổng tiền thanh toán:
                                    </label>
                                    <input type="txtTotalPricePay" class="form-control" name="txtTotalPricePay"
                                           disabled="disabled" value="0">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                                <div class="form-group qc-margin-none">
                                    <label>
                                        Cọc: <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    </label>
                                    <input type="text" class="txtBeforePay form-control" name="txtBeforePay"
                                           @if($provisionalStatus) disabled="disabled"
                                           @endif placeholder="Nhập tiền cọc" value="0">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-2">
                                <div class="form-group form-group-sm qc-margin-none">
                                    <label>
                                        Còn lại:
                                    </label>
                                    <input type="txtTotalPriceDebt" class="form-control" name="txtTotalPriceDebt"
                                           disabled="disabled" value="0">
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="form-group form-group-sm qc-margin-none">
                                    <label>Ngày Nhận:</label>
                                    <input id="txtDateReceive" type="text" name="txtDateReceive" class="form-control"
                                           disabled="disabled"
                                           value="{!! date('Y-m-d', strtotime($currentDate)) !!}"
                                           placeholder="Ngày nhận">
                                </div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <label>Ngày giao:</label>
                                        <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group-sm col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" style="padding: 0;">
                                            <select class="form-control" name="cbHoursDelivery"
                                                    style="padding: 0;color: red;">
                                                @for($h =1;$h<= 24; $h++)
                                                    <option value="{!! $h !!}"
                                                            @if($h == 17) selected="selected" @endif >
                                                        {!! $h !!} giờ
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" style="padding: 0;">
                                            <select class="form-control" name="cbMinuteDelivery"
                                                    style="padding: 0; color: red;">
                                                @for($i =0;$i<= 55; $i = $i+5)
                                                    <option value="{!! $i !!}"
                                                            @if($i == 30) selected="selected" @endif >
                                                        {!! $i !!} phút
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" style="padding: 0;">
                                            <select class="form-control" name="cbDayDelivery" style="color: blue; padding: 0;">
                                                <option value="">Ngày</option>
                                                @for($d = 1;$d<= 31; $d++)
                                                    <option value="{!! $d !!}">
                                                        {!! $d !!}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2" style="padding: 0;">
                                            <select class="form-control" name="cbMonthDelivery" style="padding: 0;">
                                                @for($m = 1;$m<= 12; $m++)
                                                    <option value="{!! $m !!}"
                                                            @if($m == $currentMonth) selected="selected" @endif>
                                                        {!! $m !!}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="padding: 0;">
                                            <select class="form-control" name="cbYearDelivery" style="padding: 0;">
                                                <option value="{!! $currentYear !!}">
                                                    {!! $currentYear !!}
                                                </option>
                                                <option value="{!! $currentYear +1 !!}">
                                                    {!! $currentYear+1 !!}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="form-group form-group-sm qc-margin-none">
                                    <label>Tổng tiền:</label>
                                    <input type="text" class="form-control" name="txtTotalPriceShow"
                                           disabled="disabled" value="0">
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                                <div class="form-group form-group-sm qc-margin-none">
                                    <label>Giảm giá <em class="qc-color-red">(%)</em></label>
                                    <select class="cbDiscount form-control" name="cbDiscount">
                                        @for($i = 0; $i <= 10;$i++)
                                            <option value="{!! $i !!}"
                                                    @if($hFunction->checkCount($dataCustomer) && $i == 5) selected="selected" @endif >
                                                {!! $i !!}
                                            </option>
                                        @endfor
                                        @for($y = 15; $y <= 100;$y= $y+5)
                                            <option value="{!! $y !!}">{!! $y !!}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                                <div class="form-group form-group-sm qc-margin-none">
                                    <label>VAT <em class="qc-color-red">(10%)</em>:</label>
                                    <select class="cbVat form-control" name="cbVat">
                                        <option value="0">Không xuất VAT</option>
                                        <option value="10">Xuất VAT</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                                <div class="form-group form-group-sm qc-margin-none">
                                    <label>
                                        Tổng tiền thanh toán:
                                    </label>
                                    <input type="txtTotalPricePay" class="form-control" name="txtTotalPricePay"
                                           disabled="disabled" value="0">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                                <div class="form-group form-group-sm qc-margin-none">
                                    <label>
                                        Cọc: <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    </label>
                                    <input type="text" class="txtBeforePay form-control" name="txtBeforePay"
                                           placeholder="Nhập tiền cọc" value="0">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="form-group form-group-sm qc-margin-none">
                                    <label>
                                        Còn lại:
                                    </label>
                                    <input type="txtTotalPriceDebt" class="form-control" name="txtTotalPriceDebt"
                                           disabled="disabled" value="0">
                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- sản phẩm --}}
                    <div class=" col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                                 style="border-bottom: 1px solid grey;">
                                <i class="glyphicon glyphicon-pencil qc-color-green"></i>
                                <span class="qc-font-size-16" style="color: blue;">Sản phẩm</span>
                            </div>
                        </div>
                        <div id="qc_work_orders_add_product_wrap">
                            @if (Session::has('listProductAdd'))
                                <?php
                                $listProduct = Session::get('listProductAdd');
                                $listProduct = json_decode($listProduct);
                                dd($listProduct);
                                ?>
                                @if($hFunction->checkCount($listProduct))
                                    @foreach($listProduct as $key => $value )
                                        <table class="table" style="margin: 0 0 5px 0; border: 1px solid #d7d7d7;">
                                            <tr>
                                                <td class="text-center"
                                                    style="background-color: whitesmoke;vertical-align: middle; padding: 0;">
                                                    <label class="qc_show_row qc-font-size-20">{!! $key + 1 !!}</label>
                                                </td>
                                                <td>
                                                    {!! $value !!}
                                                </td>
                                                <td class="text-center"
                                                    style="background-color: whitesmoke;vertical-align: middle; padding: 0;">
                                                    <a class="qc_delete qc-link-red"
                                                       data-href="{!! route('qc.work.orders.add.product.cancel.get', $key) !!}">
                                                        Xóa
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>

                                    @endforeach
                                @endif

                            @endif
                            @include('work.orders.orders.add-product')
                        </div>
                    </div>
                    <div class="row">
                        <div class="qc-padding-top-20 qc-padding-bot-20  qc-border-none text-left col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <a class="qc_orders_product_add_act qc-link-green"
                               data-href="{!! route('qc.work.orders.add.product.get') !!}">
                                <i class="glyphicon glyphicon-plus"></i>
                                THÊM SẢN PHẨM
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="qc-padding-top-20 qc-padding-bot-20  qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <input type="hidden" name="orderType" value="{!! $orderType !!}">
                            <button type="button" class="qc_save btn btn-primary btn-sm"> ĐẶT HÀNG</button>
                            <a class="btn btn-default btn-sm" type="button" href="{!! $returnHref !!}">
                                Đóng
                            </a>
                        </div>
                    </div>
            </form>
        </div>
    </div>
@endsection
