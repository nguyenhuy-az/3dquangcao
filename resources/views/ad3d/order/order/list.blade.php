<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:32 PM
 *
 * dataOrder
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$hrefIndex = route('qc.ad3d.order.order.get');
$dataStaffLogin = $modelStaff->loginStaffInfo();

$currentYear = $hFunction->currentYear();
$limitYear = 2017;

if ($staffFilterId > 0) {
    $listStaffId = [$staffFilterId];
} else {
    $listStaffId = $modelStaff->listIdOfCompany($companyFilterId);
}

if (!$hFunction->checkEmpty($orderCustomerFilterName)) {
    $listCustomerId = $modelCustomer->listIdByKeywordName($orderCustomerFilterName);
}
# thong tin don hang duoc chon
if ($hFunction->checkCount($dataOrderSelected)) {
    $orderSelectedId = $dataOrderSelected->orderId();
    $orderReceiveDate = $dataOrderSelected->receiveDate();
    $orderMonthSelected = (int)$hFunction->getMonthFromDate($orderReceiveDate);
} else {
    $orderSelectedId = $hFunction->getDefaultNull();
    $orderMonthSelected = $hFunction->getDefaultNull();
}
?>
@extends('ad3d.order.order.index')
@section('qc_ad3d_order_order')
    <style type="text/css">
        .qc_ad3d_order_selected {
            background-color: grey;
        }
    </style>
    <div class="row">
        {{-- tiêu đề --}}
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-12 col-lg-12"
                     style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! $hrefIndex !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <label class="qc-font-size-20">ĐƠN HÀNG</label>
                    {!! $yearFilter !!}
                </div>
            </div>
        </div>
        {{-- lọc thông tin --}}
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-xs-6 col-sm-6 col-md-2 col-lg-2" style="padding: 0;">
                    <select class="cbCompanyFilter form-control" name="cbCompanyFilter"
                            data-href-filter="{!! $hrefIndex !!}">
                        {{--@if($dataStaffLogin->checkRootManage())
                            <option value="1000">Tất cả</option>
                        @endif--}}
                        @if($hFunction->checkCount($dataCompany))
                            @foreach($dataCompany as $company)
                                @if($dataStaffLogin->checkRootManage())
                                    <option value="{!! $company->companyId() !!}"
                                            @if($companyFilterId == $company->companyId()) selected="selected" @endif >{!! $company->name() !!}</option>
                                @else
                                    @if($companyFilterId == $company->companyId())
                                        <option value="{!! $company->companyId() !!}">{!! $company->name() !!}</option>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="text-center col-xs-6 col-sm-6 col-md-2 col-lg-2" style="padding: 0;">
                    <div class="input-group">
                        <input type="text" class="txtOrderFilterKeyword form-control"
                               name="txtOrderFilterKeyword"
                               data-href-check-name="{!! route('qc.ad3d.work.orders.filter.order.check.name') !!}"
                               placeholder="Tên đơn hàng" value="{!! $orderFilterName !!}">
                          <span class="input-group-btn">
                                <button class="btOrderFilterKeyword btn btn-default" type="button"
                                        data-href="{!! $hrefIndex !!}">
                                    <i class="glyphicon glyphicon-search"></i>
                                </button>
                          </span>
                    </div>
                    <div id="qc_order_filter_order_name_suggestions_wrap"
                         class="qc-display-none col-xs-12 col-sm-12 col-md-12 col-lg-12"
                         style="border: 1px solid #d7d7d7;">
                        <a class='pull-right qc-link-red'
                           onclick="qc_main.hide('#qc_order_filter_order_name_suggestions_wrap');">X</a>

                        <div class="row">
                            <div id="qc_order_filter_order_name_suggestions_content"
                                 class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center col-xs-6 col-sm-6 col-md-2 col-lg-2" style="padding: 0;">
                    <div class="input-group">
                        <input type="text" class="txtOrderCustomerFilterKeyword form-control"
                               name="txtOrderCustomerFilterKeyword"
                               data-href-check-name="{!! route('qc.ad3d.work.orders.filter.customer.check.name') !!}"
                               placeholder="Tên khách hàng" value="{!! $orderCustomerFilterName !!}">
                          <span class="input-group-btn">
                            <button class="btOrderCustomerFilterKeyword btn btn-default"
                                    type="button" data-href="{!! $hrefIndex !!}">
                                <i class="glyphicon glyphicon-search"></i>
                            </button>
                          </span>
                    </div>
                    <div id="qc_order_filter_customer_name_suggestions_wrap"
                         class="qc-display-none col-xs-12 col-sm-12 col-md-12 col-lg-12"
                         style="border: 1px solid #d7d7d7;">
                        <a class='pull-right qc-link-red'
                           onclick="qc_main.hide('#qc_order_filter_customer_name_suggestions_wrap');">X</a>

                        <div class="row">
                            <div id="qc_order_filter_customer_name_suggestions_content"
                                 class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center col-xs-6 col-sm-6 col-md-2 col-lg-2" style="padding: 0;">
                    <select class="cbStaffFilterId form-control"
                            data-href="{!! $hrefIndex !!}">
                        <option value="0" @if($staffFilterId == 0) selected="selected" @endif>
                            Tất cả
                        </option>
                        @if($hFunction->checkCount($dataStaff))
                            @foreach($dataStaff as $staff)
                                <option @if($staff->staffId() == $staffFilterId) selected="selected"
                                        @endif  value="{!! $staff->staffId() !!}">{!! $staff->lastName() !!}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="text-center col-xs-6 col-sm-6 col-md-2 col-lg-2" style="padding: 0;">
                    <select class="cbDayFilter col-xs-4 col-sm-4 col-md-4 col-lg-4"
                            style="height: 34px; padding: 0;" data-href="{!! $hrefIndex !!}">
                        <option value="100" @if((int)$dayFilter == 100) selected="selected" @endif >
                            Tất cả
                        </option>
                        @for($i =1;$i<= 31; $i++)
                            <option value="{!! $i !!}"
                                    @if((int)$dayFilter == $i) selected="selected" @endif >{!! $i !!}</option>
                        @endfor
                    </select>
                    <select class="cbMonthFilter col-xs-4 col-sm-4 col-md-4 col-lg-4"
                            style="height: 34px; padding: 0;"
                            data-href="{!! $hrefIndex !!}">
                        <option value="100" @if((int)$monthFilter == 100) selected="selected" @endif >
                            Tất cả
                        </option>
                        @for($m =1;$m<= 12; $m++)
                            <option value="{!! $i !!}"
                                    @if((int)$monthFilter == $m) selected="selected" @endif>
                                {!! $m !!}
                            </option>
                        @endfor
                    </select>
                    <select class="cbYearFilter col-xs-4 col-sm-4 col-md-4 col-lg-4"
                            style="height: 34px; padding: 0;"
                            data-href="{!! $hrefIndex !!}">
                        <option value="100" @if((int)$yearFilter == 100) selected="selected" @endif >
                            Tất cả
                        </option>
                        @for($y =2017;$y<= 2050; $y++)
                            <option value="{!! $y !!}" @if($yearFilter == $y) selected="selected" @endif>
                                {!! $y !!}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="text-center col-xs-6 col-sm-6 col-md-2 col-lg-2" style="padding: 0;">
                    <select class="cbPaymentStatus form-control"
                            data-href="{!! route('qc.ad3d.order.order.get') !!}">
                        <option value="{!! $modelOrder->getDefaultAllPayment() !!}"
                                @if($paymentStatus == 2) selected="selected" @endif>Tất cả
                        </option>
                        <option value="{!! $modelOrder->getDefaultHasPayment() !!}"
                                @if($paymentStatus == 1) selected="selected" @endif>
                            Đã thu xong
                        </option>
                        <option value="{!! $modelOrder->getDefaultNotPayment() !!}"
                                @if($paymentStatus == 0) selected="selected" @endif>
                            Chưa thu xong
                        </option>
                    </select>
                </div>
            </div>
        </div>
        {{--thống kê--}}
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 10px;">
            <div class="row">
                <div class="text-center col-xs-6 col-sm-3 col-md-3 col-lg-3" style="padding: 0;">
                    <div class="panel panel-default" style="margin-bottom: 0; border:1px solid green;">
                        <div class="panel-body">
                            <em>
                                Tổng ĐH:
                            </em>
                            <b>{!! $totalOrders !!}</b>
                        </div>
                    </div>
                </div>
                <div class="text-center col-xs-6 col-sm-3 col-md-3 col-lg-3" style="padding: 0;">
                    <div class="panel panel-default" style="margin-bottom: 0;border:1px solid green;">
                        <div class="panel-body">
                            <em>
                                Tổng tiền:
                            </em>
                            <b>{!! $totalOrders !!}</b>
                        </div>
                    </div>
                </div>
                <div class="text-center col-xs-6 col-sm-3 col-md-3 col-lg-3" style="padding: 0;">
                    <div class="panel panel-default" style="border:1px solid green;margin-bottom: 0;">
                        <div class="panel-body">
                            <em>
                                Tiền đã thu:
                            </em>
                            <b>{!! $totalOrders !!}</b>
                        </div>
                    </div>
                </div>
                <div class="text-center col-xs-6 col-sm-3 col-md-3 col-lg-3" style="padding: 0;">
                    <div class="panel panel-default" style="border:1px solid green;margin-bottom: 0;">
                        <div class="panel-body">
                            <em>
                                Tiền chưa thu:
                            </em>
                            <b>{!! $totalOrders !!}</b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--Thông tin đơn hàng--}}
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="background-color: whitesmoke;">
            <div class="row">
                <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2" style="padding: 0;">
                    <ul class="list-group">
                        @for($y = $currentYear; $y >= $limitYear; $y = $y - 1)
                            @if($y == $yearFilter)
                                <li class="list-group-item" style="padding-right: 0; border-right: none;">
                                    <a data-href="#" class="qc-cursor-pointer" style="color: red;">
                                        <i class="glyphicon glyphicon-minus qc-font-size-12"></i>
                                        {!! $y !!}
                                    </a>
                                    <ul class="list-group" style="margin-bottom: 0;">
                                        @for($m = 1; $m <=12; $m++)
                                            <?php
                                            $dateFilter = date('Y-m', strtotime("1-$m-$y"));
                                            if (!$hFunction->checkEmpty($orderCustomerFilterName)) {
                                                $dataOrderInMonthYear = $modelOrder->selectInfoOfListCustomer($listCustomerId, $dateFilter, $paymentStatus)->get();
                                            } else {
                                                $dataOrderInMonthYear = $modelOrder->selectInfoNoCancelAndPayOfListStaffReceive($listStaffId, $dateFilter, $paymentStatus)->get();
                                            }
                                            ?>
                                            @if($hFunction->checkCount($dataOrderInMonthYear))
                                                <li class="list-group-item"
                                                    style="padding-right: 0; border-top: none; border-bottom: none;border-right: none;">
                                                    @if($m == $monthFilter)
                                                        <a data-href="#"
                                                           onclick="qc_main.toggle('#container_{!! $m.$y !!}');"
                                                           class="qc-cursor-pointer qc-link-red"
                                                           style="padding: 0; border-top: none; border-bottom: 0;">
                                                            <i class="glyphicon glyphicon-minus"></i>
                                                            Tháng {!! $m !!}
                                                        </a>
                                                    @else
                                                        <a data-href="#"
                                                           onclick="qc_main.toggle('#container_{!! $m.$y !!}');"
                                                           class="qc-cursor-pointer"
                                                           style="padding: 0; border-top: none; border-bottom: 0; color: black;">
                                                            <i class="glyphicon glyphicon-plus"></i>
                                                            Tháng {!! $m !!}
                                                        </a>
                                                    @endif
                                                    <span class="badge" style="background: none; color: green;">
                                                        {!! $hFunction->getCount($dataOrderInMonthYear) !!}
                                                    </span>
                                                    <ul id="container_{!! $m.$y !!}" class="list-group"
                                                        style=" margin-bottom: 0;
                                                        @if($orderMonthSelected != $m) display: none; @endif">
                                                        @foreach($dataOrderInMonthYear as $order)
                                                            <?php
                                                            $orderId = $order->orderId();
                                                            $orderReceiveDate = $order->receiveDate();
                                                            $finishStatus = $order->checkFinishStatus();
                                                            $orderMonth = (int)$hFunction->getMonthFromDate($orderReceiveDate);
                                                            $orderYear = (int)$hFunction->getYearFromDate($orderReceiveDate);
                                                            $orderHref = route('qc.ad3d.order.order.get', "null/0/$orderMonth/$orderYear/100/null/null/0/$orderId");
                                                            ?>
                                                            <li class="list-group-item"
                                                                style="padding-left: 0; padding-right: 0; border-bottom: none;border-right: none;">
                                                                &ensp;
                                                                <i class="glyphicon glyphicon-list-alt"></i>
                                                                <a class="qc-link" href="{!! $orderHref !!}">
                                                                    <span @if($orderSelectedId == $orderId) style="background-color: black;color: yellow; padding: 3px;" @endif>
                                                                        {!! $order->name() !!}
                                                                    </span>
                                                                    @if($finishStatus)
                                                                        <i class="glyphicon glyphicon-ok"
                                                                           style="color: green;"></i>
                                                                    @else
                                                                        <i class="glyphicon glyphicon-ok"
                                                                           style="color: red;"></i>
                                                                    @endif
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </li>

                                            @endif
                                        @endfor
                                    </ul>
                                </li>
                            @else
                                <li class="list-group-item" style="padding-right: 0; border-right: none;">
                                    <a data-href="#" class="qc-cursor-pointer" style="color: black;"
                                       onclick="qc_main.toggle('#container_{!! $y !!}');">
                                        <i class="glyphicon glyphicon-plus"></i>
                                        <span>
                                            {!! $y !!}
                                        </span>
                                    </a>
                                    <ul id="container_{!! $y !!}" class="list-group"
                                        style="display: none; margin-bottom: 0;">
                                        @for($m = 1; $m <=12; $m++)
                                            <?php
                                            $dateFilter = date('Y-m', strtotime("1-$m-$y"));
                                            if (!$hFunction->checkEmpty($orderCustomerFilterName)) {
                                                $dataOrderInMonthYear = $modelOrder->selectInfoOfListCustomer($listCustomerId, $dateFilter, $paymentStatus)->get();
                                            } else {
                                                $dataOrderInMonthYear = $modelOrder->selectInfoNoCancelAndPayOfListStaffReceive($listStaffId, $dateFilter, $paymentStatus)->get();
                                            }
                                            ?>
                                            @if($hFunction->checkCount($dataOrderInMonthYear))
                                                <li class="list-group-item"
                                                    style="padding-right: 0; border-top: none; border-bottom: none;border-right: none;">
                                                    <a onclick="qc_main.toggle('#container_{!! $m.$y !!}');"
                                                       class="qc-cursor-pointer"
                                                       style="padding: 0; border-top: none; border-bottom: none;">
                                                        <i class="glyphicon glyphicon-plus qc-font-size-10"></i>
                                                        Tháng {!! $m !!}
                                                        <span class="label pull-right"
                                                              style="background: none; color: green;">
                                                            {!! $hFunction->getCount($dataOrderInMonthYear) !!}
                                                        </span>
                                                    </a>

                                                    <ul id="container_{!! $m.$y !!}" class="list-group"
                                                        style="display: none; margin-bottom: 0;">
                                                        @foreach($dataOrderInMonthYear as $order)
                                                            <?php
                                                            $orderId = $order->orderId();
                                                            $orderReceiveDate = $order->receiveDate();
                                                            $finishStatus = $order->checkFinishStatus();
                                                            $orderMonth = (int)$hFunction->getMonthFromDate($orderReceiveDate);
                                                            $orderYear = (int)$hFunction->getYearFromDate($orderReceiveDate);
                                                            $orderHref = route('qc.ad3d.order.order.get', "null/0/$orderMonth/$orderYear/100/null/null/0/$orderId");
                                                            ?>
                                                            <li class="list-group-item"
                                                                style="padding-left: 0; padding-right: 0; border-bottom: none;border-right: none;">
                                                                <a class="qc-link" href="{!! $orderHref !!}"
                                                                   style="padding-top: 0;padding-bottom: 0; border-top: none; border-bottom: none;">
                                                                    <span>
                                                                        {!! $order->name() !!}
                                                                    </span>
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </li>
                                            @endif
                                        @endfor
                                    </ul>
                                </li>
                            @endif
                        @endfor
                    </ul>
                </div>
                <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10"
                     style="@if($mobileStatus) padding-left: 0; @endif padding-right: 0; border: 1px solid #d7d7d7; min-height: 500px;">
                    @if($hFunction->checkCount($dataOrderSelected))
                        <?php
                        $orderSelectedReceiveStaff = $dataOrderSelected->staffReceive; // thong tin NV nhận
                        # tong tien chua giam gia
                        $orderTotalPrice = $dataOrderSelected->totalPrice();
                        # tong tien giam
                        $orderTotalDiscount = $dataOrderSelected->totalMoneyDiscount();
                        # tong tien VAT
                        $orderTotalVat = $dataOrderSelected->totalMoneyOfVat();
                        # thong tin san pham
                        $dataProduct = $dataOrderSelected->productActivityOfOrder();
                        #anh thiet ke tong quat
                        $dataOrderImage = $dataOrderSelected->orderImageInfoActivity();

                        # thong tin khach hang
                        $dataCustomer = $dataOrderSelected->customer;
                        ?>
                        <div class="row" style="margin-bottom: 10px;">
                            {{--thông tin đơn hàng--}}
                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                <div class="panel panel-default" style="margin-bottom: 0;">
                                    <div class="panel-body" style="padding-top: 0;">
                                        <h3 style="color: blue;">{!! $dataOrderSelected->name() !!}</h3>
                                        <table class="table table-hover qc-margin-bot-none">
                                            <tr>
                                                <td>
                                                    <em class=" qc-color-grey">Mã ĐH:</em>
                                                </td>
                                                <td class="text-right">
                                                    <b>{!! $dataOrderSelected->orderCode() !!}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <em class="qc-color-grey">Người nhận :</em>
                                                </td>
                                                <td class="text-right">
                                                    <img class="media-object"
                                                         style="background-color: white; width: 30px;height: 30px; border: 1px solid #d7d7d7;border-radius: 15px;"
                                                         src="{!! $orderSelectedReceiveStaff->pathAvatar($orderSelectedReceiveStaff->image()) !!}">
                                                    <b>{!! $dataOrderSelected->staffReceive->lastName() !!}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <em class="qc-color-grey">Ngày nhận:</em>
                                                </td>
                                                <td class="text-right">
                                                    <b>{!! $hFunction->convertDateDMYFromDatetime($dataOrderSelected->receiveDate()) !!}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <em class="qc-color-grey">Ngày giao:</em>
                                                </td>
                                                <td class="text-right">
                                                    <b>{!! $hFunction->convertDateDMYFromDatetime($dataOrderSelected->deliveryDate()) !!}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <em class="qc-color-grey">Đ/c thi công:</em>
                                                </td>
                                                <td class="text-right">
                                            <span class="pull-right">{!! $dataOrderSelected->constructionAddress() !!}
                                                - ĐT: {!! $dataOrderSelected->constructionPhone() !!}
                                                - tên: {!! $dataOrderSelected->constructionContact() !!}
                                            </span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            {{--Thông tin thanh toán--}}
                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                <div class="panel panel-default" style="margin-bottom: 0;">
                                    <div class="panel-body" style="padding-top: 0;">
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
                                                    <em class="qc-color-grey">
                                                        Giảm {!! $dataOrderSelected->discount() !!}%:
                                                    </em>
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
                                                    <em class="qc-color-grey">VAT {!! $dataOrderSelected->vat() !!}
                                                        %:</em>
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
                                                    <b>- {!! $hFunction->currencyFormat($dataOrderSelected->totalPaid()) !!}</b>
                                                </td>
                                            </tr>
                                            <tr style="border-top: 1px solid grey;">
                                                <td>
                                                    <em class="qc-color-grey">Còn lại:</em>
                                                </td>
                                                <td class="text-right">
                                                    <b class="qc-color-red">{!! $hFunction->currencyFormat($dataOrderSelected->totalMoneyUnpaid()) !!}</b>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            {{--thông tin khách hàng--}}
                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                <div class="panel panel-default" style="margin-bottom: 0;">
                                    <div class="panel-body" style="padding-top: 0;">
                                        <table class="table table-hover qc-margin-bot-none">
                                            <tr>
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
                                                    <b>{!! $dataCustomer->name() !!}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <em class="qc-color-grey">Điện thoại:</em>
                                                </td>
                                                <td class="text-right">
                                                    <b>{!! $dataCustomer->phone() !!}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <em class="qc-color-grey">Zalo:</em>
                                                </td>
                                                <td class="text-right">
                                                    <b>{!! $dataCustomer->zalo() !!}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <em class="qc-color-grey">ĐC\:</em>
                                                </td>
                                                <td class="text-right">
                                                    <b>{!! $dataCustomer->address() !!}</b>
                                                </td>
                                            </tr>

                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                        {{--sản phẩm--}}
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                @foreach($dataProduct as $product)
                                    <?php
                                    $productId = $product->productId();
                                    $productWidth = $product->width();
                                    $productHeight = $product->height();
                                    $productAmount = $product->amount();
                                    $productDescription = $product->description();
                                    $dataWorkAllocation = $product->workAllocationInfoOfProduct();
                                    $designImage = $product->designImage();
                                    # thiet ke dang ap dung
                                    $dataProductDesign = $product->productDesignInfoApplyActivity();
                                    # thiet ke san pham thi cong
                                    $dataProductDesignConstruction = $product->productDesignInfoConstructionHasApply();
                                    # san pham da ket thuc hay chua
                                    $checkFinishStatus = $product->checkFinishStatus();
                                    ?>
                                    <div class="row">
                                        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                            <table class="table table-bordered"
                                                   style="border: 1px solid grey; background-color: white; ">
                                                <tr>
                                                    <td class="text-center" style="width: 100px;">
                                                        <b style="color: red; font-size: 1.5em;">
                                                            SP {!! $sp_n_o = (isset($sp_n_o))?$sp_n_o+1:1 !!}
                                                        </b>
                                                        @if($hFunction->checkCount($dataProductDesign))
                                                            <br/>
                                                            <a class="qc-link qc_work_order_product_design_image_view"
                                                               data-href="{!! route('qc.work.orders.product_design.view.get', $dataProductDesign->designId()) !!}">
                                                                <img style="width: 100%; height: auto; border: 1px solid grey;"
                                                                     title="Ảnh thiết kế"
                                                                     src="{!! $dataProductDesign->pathSmallImage($dataProductDesign->image()) !!}">
                                                            </a>
                                                        @else
                                                            <br/>
                                                            <em class="qc-color-grey">Không có thiết kế</em>
                                                        @endif
                                                    </td>
                                                    <td style="width: 300px;">
                                                        <label style="font-size: 1.5em;">{!! ucwords($product->productType->name()) !!}</label>
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
                                                        <br/>
                                                        @if(!$checkFinishStatus)
                                                            <span style="color: blue;">Đang làm</span>
                                                        @else
                                                            <em style="color: blue;">Đã xong</em>
                                                            <em style="color: grey;">-</em>
                                                            @if($product->productRepairActivityOfProduct())
                                                                <span style="background-color: red; color: yellow; padding: 3px;">ĐANG SỬA CHỬA</span>
                                                            @else
                                                                {{--<a class="qc_product_repair_get qc-link-red-bold"--}}
                                                                {{--data-href="{!! route('qc.work.orders.construction.detail.repair.get', $productId) !!}">--}}
                                                                {{--BÁO SỬA CHỮA--}}
                                                                {{--</a>--}}
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($hFunction->checkCount($dataProductDesignConstruction))
                                                            @foreach($dataProductDesignConstruction as $productDesignConstruction)
                                                                <a class="qc_work_order_product_design_image_view qc-link"
                                                                   data-href="{!! route('qc.work.orders.product_design.view.get', $productDesignConstruction->designId()) !!}">
                                                                    <img style="width: 70px; height: auto; margin-bottom: 5px; border: 1px solid grey;"
                                                                         title="Ảnh thiết kế thi công"
                                                                         src="{!! $productDesignConstruction->pathSmallImage($productDesignConstruction->image()) !!}">
                                                                </a>
                                                            @endforeach
                                                        @else
                                                            <span style="background-color: black; color: lime;">Không có TK thi công </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @if($hFunction->checkCount($dataWorkAllocation))
                                                    <tr>
                                                        <td colspan="3">
                                                            <ul class="list-group">
                                                                @foreach($dataWorkAllocation as $workAllocation)
                                                                    <li class="list-group-item"
                                                                        style="padding-top: 0;border-top: none; border-bottom: none;">
                                                                        sdfdsf
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td colspan="3" style="border-top: 0;">
                                                            @if($product->checkFinishStatus())
                                                                <em class="qc-color-grey">Đã kết thúc</em>
                                                                <em> - KHÔNG CÓ THI CÔNG</em>
                                                            @else
                                                                <em class="qc-color-red"> CHƯA TRIỂN KHAI THI
                                                                    CÔNG</em>
                                                            @endif
                                                        </td>
                                                    </tr>   
                                                @endif

                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="panel panel-default" style="margin-bottom: 0;">
                                    <span style="color: red;">
                                        KHÔNG CÓ ĐƠN HÀNG ĐƯỢC CHỌN
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>


        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="qc_ad3d_list_content qc-ad3d-table-container row">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: black; color: yellow;">
                            <th class="text-center" style="width:20px;"></th>
                            <th>Mã</th>
                            <th style="min-width: 150px;">ĐƠN HÀNG</th>
                            {{--<th>Thiết kế</th>--}}
                            <th style="min-width: 150px;">KHÁCH HÀNG</th>
                            <th style="min-width: 100px;">NHÂN VIÊN</th>
                            <th>THI CÔNG</th>
                            <th>TIẾN ĐỘ</th>
                            <th class="text-center">
                                TG NHẬN <br/>
                                GIAO
                            </th>
                            <th>THANH TOÁN</th>
                            <th class="text-right">Tổng tiền</th>
                            <th class="text-right">Giảm</th>
                            <th class="text-right">
                                ĐÃ THU
                            </th>
                            <th class="text-right">
                                CHƯA THU
                            </th>
                        </tr>
                        @if($hFunction->checkCount($dataOrder))
                            <?php
                            $perPage = $dataOrder->perPage();
                            $currentPage = $dataOrder->currentPage();
                            $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                            $sumOrderMoney = 0;
                            $sumDiscountMoney = 0;
                            $sumPaidMoney = 0;
                            $sumUnPaidMoney = 0;

                            ?>
                            @foreach($dataOrder as $order)
                                <?php
                                $orderId = $order->orderId();
                                $customerId = $order->customerId();
                                $orderReceiveDate = $order->receiveDate();
                                $checkFinishPayment = $order->checkFinishPayment();
                                $finishStatus = $order->checkFinishStatus();
                                $cancelStatus = $order->checkCancelStatus();
                                $totalPrice = $order->totalPrice();
                                $dataReceiveStaff = $order->staffReceive; // thong tin NV nhận
                                $sumOrderMoney = $sumOrderMoney + $totalPrice;
                                $totalDiscount = $order->totalMoneyDiscount();
                                $sumDiscountMoney = $sumDiscountMoney + $totalDiscount;
                                $totalPaid = $order->totalPaid();
                                $sumPaidMoney = $sumPaidMoney + $totalPaid;
                                $totalUnPaid = $totalPrice - $totalDiscount - $totalPaid;//$orders->totalMoneyUnpaid();
                                $sumUnPaidMoney = $sumUnPaidMoney + $totalUnPaid;
                                // san pham
                                $dataProduct = $order->allProductOfOrder();

                                // phan viec tren san pham
                                $orderWorkAllocationAllInfo = $order->workAllocationOnProduct();
                                ?>
                                <tr class="qc_ad3d_list_object @if($n_o%2 == 1) info @endif"
                                    data-object="{!! $orderId !!}">
                                    <td class="text-center">
                                        <b>{!! $n_o += 1 !!}</b>
                                    </td>
                                    <td class="text-center">
                                        <em class="qc-color-grey">{!! $order->orderCode() !!}</em><br/>
                                        @if($finishStatus)
                                            <i class="qc-color-green glyphicon glyphicon-ok"></i>
                                        @else
                                            <i class="qc-color-red glyphicon glyphicon-ok"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <a class="qc-link" title="Click xem menu">
                                                    {!! $order->name() !!}
                                                </a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <a class="qc_view qc-link-green">
                                                    <i class="qc-font-size-14 glyphicon glyphicon-eye-open"></i>
                                                </a>
                                                &nbsp;&nbsp;
                                                <a class="qc-link" title="In đơn hàng"
                                                   href="{!! route('qc.ad3d.order.order.print.get', $orderId) !!}">
                                                    <i class="qc-font-size-14 fa fa-print"></i>
                                                </a>
                                                @if(!$cancelStatus)
                                                    @if($order->checkConfirmStatus())
                                                        &nbsp;&nbsp;
                                                        <a class="qc-link-green" title="In nghiệm thu"
                                                           href="{!! route('qc.ad3d.order.order.confirm.print.get', $orderId) !!}">
                                                            <i class="qc-font-size-16 glyphicon glyphicon-list-alt"></i>
                                                        </a>
                                                    @endif
                                                @endif

                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a class="qc_view_customer qc-link"
                                           data-customer="{!! $order->customer->customerId() !!}"
                                           title="Xem thống kê khách hàng">
                                            {!! $order->customer->name() !!}
                                        </a>
                                    </td>
                                    <td class="qc-color-grey">
                                        <a class="qc-link" title="Click xem thống kê NV" target="_blank"
                                           href="{!! route('qc.ad3d.statistic.revenue.company.staff.get',$dataReceiveStaff->staffId().'/'.$orderReceiveDate) !!}">
                                            {!! $dataReceiveStaff->lastName() !!}
                                        </a>

                                    </td>
                                    <td>
                                        @if(!$cancelStatus)
                                            {{--<a class="qc-link-green" title="Click xem chi tiết thi công"
                                               href="{!! route('qc.ad3d.order.order.construction.get',$orderId) !!}">
                                                @if(!$finishStatus)
                                                    Bàn giao công trình
                                                @else
                                                    Đã kết thúc
                                                @endif
                                            </a>--}}
                                            @if(count($orderWorkAllocationAllInfo)> 0)
                                                <br/>
                                                TC:
                                                @foreach($orderWorkAllocationAllInfo as $workAllocation)
                                                    <span>{!! $workAllocation->receiveStaff->lastName() !!}</span>,
                                                    &nbsp;
                                                @endforeach
                                            @else
                                                <em>Không có triển khai thi công</em>
                                            @endif
                                        @else
                                            <em style=" color: brown;">
                                                Đã hủy
                                            </em>
                                        @endif
                                        @if($hFunction->checkCount($dataProduct))
                                            <br/>
                                        @endif
                                    </td>
                                    <td class="qc-color-red">
                                        @if($hFunction->checkCount($orderWorkAllocationAllInfo))
                                            <?php
                                            $dataWorkAllocationReportImage = $order->workAllocationReportImage($orderId, 1);
                                            ?>
                                            @if($hFunction->checkCount($dataWorkAllocationReportImage))
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    @foreach($dataWorkAllocationReportImage as $workAllocationReportImage)
                                                        <div style="position: relative; float: left; margin-right: 5px; width: 70px; height: 70px; border: 1px solid #d7d7d7;">
                                                            <a class="qc_work_allocation_report_image_view qc-link"
                                                               title="Click xem chi tiết hình ảnh"
                                                               data-href="{!! route('qc.ad3d.work.work_allocation_report.image.view',$workAllocationReportImage->imageId()) !!}">
                                                                <img style="max-width: 100%; max-height: 100%;"
                                                                     src="{!! $workAllocationReportImage->pathSmallImage($workAllocationReportImage->name()) !!}">
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    <a class="qc-link-green" title="Click xem chi tiết thi công"
                                                       href="{!! route('qc.ad3d.order.order.construction.get',$orderId) !!}">
                                                        CHI TIET TC
                                                    </a>
                                                </div>
                                            @else
                                                <em class="qc-color-grey">Không có ảnh BC</em>
                                            @endif
                                        @else
                                            <em class="qc-color-grey">Không có ảnh BC</em>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {!! $hFunction->convertDateDMYFromDatetime($order->receiveDate()) !!}
                                        <br/>
                                        <em class="qc-color-grey">{!! $hFunction->convertDateDMYFromDatetime($order->deliveryDate()) !!}</em>
                                    </td>
                                    <td>
                                        @if(!$cancelStatus)
                                            @if($checkFinishPayment)
                                                <em class="qc-color-grey">
                                                    Đã thanh toán
                                                </em>
                                            @else
                                                <em style="color: red;">
                                                    Chưa thanh toán xong
                                                </em>
                                                {{--<a class="qc-link-red"
                                                   href="{!! route('qc.ad3d.order.order.payment.get',$orderId) !!}">
                                                    THANH TOÁN
                                                    <br/>
                                                    <img alt="icon"
                                                         style="margin-bottom: 3px; border: 1px solid #d7d7d7; width: 30px; height: 30px;"
                                                         src="{!! asset('public/images/icons/paymentIcon.jpg') !!}"/>
                                                </a>--}}
                                            @endif
                                            {{--@if(!$order->checkConfirmStatus())
                                                <span>|</span>
                                                <a class="qc_confirm qc-link-green">
                                                    Xác nhận
                                                </a>
                                            @endif--}}
                                        @else
                                            <em style="color: brown;">
                                                Đã hủy
                                            </em>
                                        @endif
                                    </td>
                                    <td class="text-right" style="color: blue;">
                                        {!! $hFunction->currencyFormat($order->totalPrice()) !!}
                                    </td>
                                    <td class="text-right qc-color-grey">
                                        {!! $hFunction->currencyFormat($totalDiscount) !!}
                                    </td>
                                    <td class="text-right qc-color-green">
                                        <a class="qc_view qc-link-green" title="Click xem chi tiết" href="#">
                                            {!! $hFunction->currencyFormat($totalPaid) !!}
                                        </a>
                                    </td>
                                    <td class="text-right qc-color-red">
                                        {!! $hFunction->currencyFormat($totalUnPaid) !!}
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center" colspan="12">
                                    {!! $hFunction->page($dataOrder) !!}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="text-center" colspan="12">
                                    <em class="qc-color-red">Không tìm thấy thông tin phù hợp</em>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection()
