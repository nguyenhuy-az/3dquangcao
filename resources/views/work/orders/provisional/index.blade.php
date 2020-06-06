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
$loginStaffId = $dataStaffLogin->staffId();
$hrefIndex = route('qc.work.orders.provisional.get');
?>
@extends('work.orders.index')
@section('titlePage')
    Danh sách báo giá
@endsection
@section('qc_work_order_body')
    <div class="row qc_work_orders_provisional_wrap">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="padding-bottom: 5px;">
                <a class="btn btn-sm btn-primary" onclick="qc_main.page_back();">
                    Về trang trước
                </a>
                <a class="btn btn-sm btn-default" href="{!! route('qc.work.home') !!}">
                    Về trang chủ
                </a>
            </div>

            @include('work.orders.menu')
            {{-- chi tiêt --}}
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="qc_work_orders_provisional_list_content row" style="min-height: 3000px;"
                     data-href-cancel="{!! route('qc.work.orders.provisional.cancel.get') !!}"
                     data-href-confirm="{!! route('qc.work.orders.provisional.confirm.get') !!}">
                    <div class="qc-container-table col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" style="margin-bottom: 100px;">
                                <tr style="background-color: black;color: yellow;">
                                    <th class="text-center"></th>
                                    <th>Mã ĐH</th>
                                    <th>Tên ĐH</th>
                                    <th>Khách hàng</th>
                                    <th class="text-center">Ngày báo</th>
                                    <th class="text-center" style="min-width: 70px;">Đặt hàng</th>
                                    <th class="text-right">Tổng tiền ĐH<br/>VNĐ</th>
                                    <th class="text-right">Giảm <br/>VNĐ</th>
                                    <th class="text-right">VAT <br/>VNĐ</th>
                                    <th class="text-right">
                                        Tổng thanh toán <br/>VNĐ
                                    </th>
                                </tr>
                                <tr>
                                    <td class="text-center qc-color-red">
                                        <b>{!! count($dataOrders) !!}</b>
                                    </td>
                                    <td></td>
                                    <td style="padding: 0px;">
                                        <div class="input-group">
                                            <input type="text" class="txtOrderProvisionFilterKeyword form-control"
                                                   name="txtOrderProvisionFilterKeyword"
                                                   data-href-check-name="{!! route('qc.work.provisional.orders.filter.order.check.name') !!}"
                                                   placeholder="Tìm theo tên" value="{!! $orderFilterName !!}">
                                              <span class="input-group-btn">
                                                    <button class="btOrderProvisionalFilterKeyword btn btn-default"
                                                            type="button"
                                                            data-href="{!! $hrefIndex !!}">
                                                        <i class="glyphicon glyphicon-search"></i>
                                                    </button>
                                              </span>
                                        </div>
                                        <div id="qc_order_provisional_filter_order_name_suggestions_wrap"
                                             class="qc-display-none col-xs-12 col-sm-12 col-md-12 col-lg-12"
                                             style="border: 1px solid #d7d7d7;">
                                            <a class='pull-right qc-link-red'
                                               onclick="qc_main.hide('#qc_order_provisional_filter_order_name_suggestions_wrap');">X</a>

                                            <div class="row">
                                                <div id="qc_order_provisional_filter_order_name_suggestions_content"
                                                     class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="text"
                                                   class="txtOrderProvisionalCustomerFilterKeyword form-control"
                                                   name="txtOrderProvisionalCustomerFilterKeyword"
                                                   data-href-check-name="{!! route('qc.work.provisional.orders.filter.customer.check.name') !!}"
                                                   placeholder="Tìm tên khách hàng"
                                                   value="{!! $orderCustomerFilterName !!}">
                                              <span class="input-group-btn">
                                                    <button class="btOrderProvisionalCustomerFilterKeyword btn btn-default"
                                                            type="button"
                                                            data-href="{!! $hrefIndex !!}">
                                                        <i class="glyphicon glyphicon-search"></i>
                                                    </button>
                                              </span>
                                        </div>
                                        <div id="qc_order_provisional_filter_customer_name_suggestions_wrap"
                                             class="qc-display-none col-xs-12 col-sm-12 col-md-12 col-lg-12"
                                             style="border: 1px solid #d7d7d7;">
                                            <a class='pull-right qc-link-red'
                                               onclick="qc_main.hide('#qc_order_provisional_filter_customer_name_suggestions_wrap');">X</a>

                                            <div class="row">
                                                <div id="qc_order_provision_filter_customer_name_suggestions_content"
                                                     class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center" style="padding: 0; width: 120px;">
                                        <select class="qc_work_orders_provisional_login_month" style="height: 25px;"
                                                data-href="{!! $hrefIndex !!}">
                                            <option value="100" @if($monthFilter == 100) selected="selected" @endif>Tất cả</option>
                                            @for($m = 1; $m <=12; $m++)
                                                <option @if($monthFilter == $m) selected="selected" @endif>
                                                    {!! $m !!}
                                                </option>
                                            @endfor
                                        </select>
                                        <select class="qc_work_orders_provisional_login_year" style="height: 25px;"
                                                data-href="{!! $hrefIndex !!}">
                                            <option value="100" @if($yearFilter == 100) selected="selected" @endif>Tất cả</option>
                                            @for($y = 2017; $y <=2050; $y++)
                                                <option @if($yearFilter == $y) selected="selected" @endif>
                                                    {!! $y !!}
                                                </option>
                                            @endfor
                                        </select>
                                    </td>
                                    <td class="text-center" style="padding: 0px;"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                </tr>
                                @if(count($dataOrders) > 0)
                                    <?php
                                    $n_o = 0;
                                    $sumOrderMoney = 0;
                                    $sumDiscountMoney = 0;
                                    $sumVatMoney = 0;
                                    $sumOrderMoneyPay = 0;
                                    ?>
                                    @foreach($dataOrders as $orders)
                                        <?php
                                        $orderId = $orders->orderId();
                                        $customerId = $orders->customerId();
                                        $provisionalDate = $orders->provisionalDate();
                                        $createdAt = $orders->createdAt();
                                        $totalPrice = $orders->totalPrice(); // tong tien san pham
                                        $sumOrderMoney = $sumOrderMoney + $totalPrice;
                                        $totalVAT = $orders->totalMoneyOfVat(); // tien thue
                                        $sumVatMoney = $sumVatMoney + $totalVAT;
                                        $totalDiscount = $orders->totalMoneyDiscount();// giam gia
                                        $sumDiscountMoney = $sumDiscountMoney + $totalDiscount;
                                        $totalPay = $totalPrice - $totalDiscount + $totalVAT;
                                        $sumOrderMoneyPay = $sumOrderMoneyPay + $totalPay;
                                        //kiem tra don hang truoc
                                        $cancelStatus = $orders->checkCancelStatus();
                                        $checkProvisionConfirm = $orders->checkProvisionConfirm($orderId);
                                        $checkOrderOfReceiveStaff = ($loginStaffId == $orders->staffReceiveId()) ? true : false; // kiem tra don hang thuoc nguoi dang nhap
                                        ?>
                                        <tr class="qc_work_list_content_object @if($cancelStatus) danger @elseif($n_o%2) info @endif"
                                            data-object="{!! $orderId !!}">
                                            <td class="text-center">
                                                {!! $n_o += 1 !!}
                                            </td>
                                            <td class="text-center">
                                                <span class="qc-color-grey">{!! $orders->orderCode() !!}</span><br/>
                                                @if($checkProvisionConfirm)
                                                    <i class="qc-color-green glyphicon glyphicon-ok"></i>
                                                @else
                                                    <i class="qc-color-red glyphicon glyphicon-ok"></i>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        <a class="qc-link">
                                                            {!! $orders->name() !!}
                                                        </a>
                                                        @if(!$checkOrderOfReceiveStaff)
                                                            <br/>
                                                            <em class="qc-color-grey">Phụ
                                                                trách: {!! $orders->staffReceive->lastName() !!}</em>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        @if($checkOrderOfReceiveStaff)
                                                            <a class="qc-link"
                                                               href="{!! route('qc.work.orders.info.get',$orderId) !!}"
                                                               title="Quản lý ĐH">
                                                                <i class="qc-font-size-16 glyphicon glyphicon-pencil"></i>
                                                            </a>
                                                        @endif
                                                        &nbsp;&nbsp;
                                                        <a class="qc-link-green"
                                                           href="{!! route('qc.work.orders.provisional.view.get',$orderId) !!}">
                                                            <i class="qc-font-size-16 glyphicon glyphicon-eye-open"></i>
                                                        </a>
                                                        @if($checkOrderOfReceiveStaff)
                                                            &nbsp;&nbsp;
                                                            <a class="qc-link" target="_blank" title="In báo giá"
                                                               href="{!! route('qc.work.orders.provisional.print.get', $orderId) !!}">
                                                                <i class="qc-font-size-16 fa fa-print"></i>
                                                            </a>
                                                        @endif

                                                        @if(!$cancelStatus)
                                                            @if(!$checkProvisionConfirm && $checkOrderOfReceiveStaff)
                                                                <a class="qc_order_provisional_cancel qc-link-red">
                                                                    <i class="qc-font-size-16 fa fa-trash"></i>
                                                                </a>
                                                            @endif
                                                        @endif

                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a class="qc_view_customer qc-link" title="Click xem chi tiết"
                                                   data-href="{!! route('qc.work.orders.provisional.view.customer.get',$customerId) !!}">
                                                    {!! $orders->customer->name() !!}
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                {!! date('d/m/Y', strtotime($provisionalDate)) !!}
                                            </td>
                                            <td class="text-center">
                                                @if(!$orders->checkCancelStatus())
                                                    @if(!$checkProvisionConfirm)
                                                        <a class="qc_order_provisional_confirm qc-link-red"
                                                           data-href="{!! route('qc.work.orders.provisional.confirm.get', $orderId) !!} ">
                                                            Đặt hàng
                                                            <br/>
                                                            <i class="qc-font-size-16 glyphicon glyphicon-save"></i>
                                                        </a>
                                                    @else
                                                        <em class="qc-color-grey">Đã đặt hàng</em>
                                                    @endif
                                                @else
                                                    <em style="color: brown;">
                                                        Đã hủy
                                                    </em>
                                                @endif

                                            </td>
                                            <td class="text-right">
                                                {!! $hFunction->currencyFormat($totalPrice) !!}
                                            </td>
                                            <td class="text-right" style="color: brown;">
                                                {!! $hFunction->currencyFormat($totalDiscount) !!}
                                            </td>
                                            <td class="text-right" style="color: brown;">
                                                {!! $hFunction->currencyFormat($totalVAT) !!}
                                            </td>
                                            <td class="text-right">
                                                {!! $hFunction->currencyFormat($totalPay) !!}
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td class="text-right" colspan="6"
                                            style="background-color: whitesmoke;">
                                        </td>
                                        <td class="text-right qc-color-red">
                                            <b>{!! $hFunction->currencyFormat($sumOrderMoney)  !!}</b>
                                        </td>
                                        <td class="text-right" style="color: blue;">
                                            <b>{!! $hFunction->currencyFormat($sumDiscountMoney)  !!}</b>
                                        </td>
                                        <td class="text-right" style="color: blue;">
                                            <b>{!! $hFunction->currencyFormat($sumVatMoney)  !!}</b>
                                        </td>
                                        <td class="text-right qc-color-red">
                                            <b>{!! $hFunction->currencyFormat($sumOrderMoneyPay)  !!}</b>
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td class="text-center" colspan="10">
                                            <em class="qc-color-red">Không đơn hàng</em>
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
