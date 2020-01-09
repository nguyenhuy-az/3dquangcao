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
#$totalMoneyOrder = $modelOrders->totalMoneyOfListOrder($dataOrders);
?>
@extends('work.index')
@section('titlePage')
    Danh sách đơn hàng
@endsection
@section('qc_work_body')
    <div class="row qc_work_orders_wrap">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed #C2C2C2;">
                    <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6">
                        <a class="qc-link-green-bold" href="{!! route('qc.work.orders.get') !!}">
                            <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                        </a>
                        <label class="qc-font-size-20">ĐƠN HÀNG</label>
                    </div>
                    <div class="text-right col-sx-12 col-sm-12 col-md-6 col-lg-6">
                        <a class="qc_work_before_pay_request_action qc-link-green"
                           href="{!! route('qc.work.orders.add.get') !!}">
                            <em>+ Thêm đơn hàng</em>
                        </a>
                    </div>
                </div>
            </div>
            {{-- chi tiêt --}}
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="text-right qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <select class="qc_work_orders_login_month" style="height: 25px;"
                                data-href="{!! route('qc.work.orders.get') !!}">
                            <option value="100" @if($monthFilter == 100) selected="selected" @endif>Tất cả</option>
                            @for($i = 1; $i <=12; $i++)
                                <option @if($monthFilter == $i) selected="selected" @endif>
                                    {!! $i !!}
                                </option>
                            @endfor
                        </select>
                        <span>/</span>
                        <select class="qc_work_orders_login_year" style="height: 25px;"
                                data-href="{!! route('qc.work.orders.get') !!}">
                            <option value="100" @if($yearFilter == 100) selected="selected" @endif>Tất cả</option>
                            @for($i = 2017; $i <=2050; $i++)
                                <option @if($yearFilter == $i) selected="selected" @endif>
                                    {!! $i !!}
                                </option>
                            @endfor
                        </select>
                        <span>/</span>
                        <select class="qc_work_orders_login_confirm" style="height: 25px;"
                                data-href="{!! route('qc.work.orders.get') !!}">
                            <option value="0" @if($salesConfirm == 0) selected="selected" @endif>Chưa xác nhận</option>
                            <option value="1" @if($salesConfirm == 1) selected="selected" @endif>Đã xác nhận</option>
                            <option value="3" @if($salesConfirm > 2) selected="selected" @endif>Tất cả</option>
                        </select>
                    </div>
                </div>
                <div class="qc_work_orders_list_content row"
                     data-href-del="{!! route('qc.work.orders.delete.get') !!}">
                    <div class="qc-container-table col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <tr style="background-color: whitesmoke;">
                                    <th class="text-center"></th>
                                    <th>Mã ĐH</th>
                                    <th>Tên ĐH</th>
                                    <th class="text-center">Khách hàng</th>
                                    <th class="text-center">Ngày nhận</th>
                                    <th class="text-center">Ngày giao</th>
                                    <th></th>
                                    <th></th>
                                    <th class="text-right">Tổng tiền</th>
                                    <th class="text-right">Giảm</th>
                                    <th class="text-right">Đã TT</th>
                                    <th class="text-right">Còn lại</th>
                                </tr>
                                <tr>
                                    <td class="text-center qc-color-red">
                                        <b>{!! count($dataOrders) !!}</b>
                                    </td>
                                    <td></td>
                                    <td class="text-center" style="padding: 0px;">
                                        <div class="input-group">
                                            <input type="text" class="txtOrderFilterKeyword form-control"
                                                   name="txtOrderFilterKeyword"
                                                   placeholder="Tìm theo tên" value="{!! $keyWork !!}">
                                              <span class="input-group-btn">
                                                    <button class="btOrderFilterKeyword btn btn-default" type="button"
                                                            data-href="{!! route('qc.work.orders.get') !!}">
                                                        <i class="glyphicon glyphicon-search"></i>
                                                    </button>
                                              </span>
                                        </div>
                                        {{--<div id="qc_order_name_suggestions_wrap" class="qc-display-none col-xs-12 col-sm-12 col-md-12 col-lg-12" style="border: 1px solid #d7d7d7;" >
                                            <a class='pull-right qc-link-red' onclick="qc_main.hide('#qc_order_name_suggestions_wrap');">X</a>
                                            <div class="row">
                                                <div id="qc_order_name_suggestions_content" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                                                </div>
                                            </div>
                                        </div>--}}
                                    </td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-center" style="padding: 0px;">
                                        {{--<select class="cbFinishStatus form-control"
                                                data-href="{!! route('qc.ad3d.order.order.get') !!}">
                                            <option value="2" @if($paymentStatus == 100) selected="selected" @endif>Tất cả
                                            </option>
                                            <option value="1" @if($paymentStatus == 1) selected="selected" @endif>
                                                Đã kết thút
                                            </option>
                                            <option value="0" @if($paymentStatus == 0) selected="selected" @endif>
                                                Đang triển khai
                                            </option>
                                        </select>--}}
                                    </td>
                                    <td style="padding: 0px;"></td>
                                    <td></td>
                                    <td class="text-right" style="color: blue;"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right qc-color-green"></td>
                                </tr>
                                @if(count($dataOrders) > 0)
                                    <?php
                                    $n_o = 0;
                                    $sumOrderMoney = 0;
                                    $sumDiscountMoney = 0;
                                    $sumPaidMoney = 0;
                                    $sumUnPaidMoney = 0;
                                    ?>
                                    @foreach($dataOrders as $orders)
                                        <?php
                                        $orderId = $orders->orderId();
                                        $customerId = $orders->customerId();
                                        $totalPrice = $orders->totalPrice();
                                        $sumOrderMoney = $sumOrderMoney + $totalPrice;
                                        $totalDiscount = $orders->totalMoneyDiscount();
                                        $sumDiscountMoney = $sumDiscountMoney + $totalDiscount;
                                        $totalPaid = $orders->totalPaid();
                                        $sumPaidMoney = $sumPaidMoney + $totalPaid;
                                        $totalUnPaid = $totalPrice - $totalDiscount - $totalPaid;//$orders->totalMoneyUnpaid();
                                        $sumUnPaidMoney = $sumUnPaidMoney + $totalUnPaid;
                                        ?>
                                        <tr data-object="{!! $orderId !!}">
                                            <td class="text-center">
                                                {!! $n_o += 1 !!}
                                            </td>
                                            <td>
                                                <span class="qc-color-grey">{!! $orders->orderCode() !!}</span>
                                            </td>
                                            <td>
                                                <span>{!! $orders->name() !!}</span>
                                                &nbsp;
                                                @if($orders->checkConfirmStatus())
                                                    <i class="qc-color-green glyphicon glyphicon-ok"
                                                       title="Đã xác nhận"></i>
                                                @else
                                                    <i class="qc-color-red glyphicon glyphicon-ok"
                                                       title="Chưa xác nhận"></i>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                {!! $orders->customer->name() !!}
                                            </td>
                                            <td class="text-center">
                                                {!! date('d/m/Y', strtotime($orders->receiveDate())) !!}
                                            </td>
                                            <td class="text-center">
                                                {!! date('d/m/Y', strtotime($orders->deliveryDate())) !!}
                                            </td>
                                            <td class="text-center">
                                                @if(!$orders->checkCancelStatus())
                                                    @if(!$orders->checkFinishPayment())
                                                        <a class="qc-link-green"
                                                           href="{!! route('qc.work.orders.payment.get', $orderId) !!} ">
                                                            Thanh toán
                                                        </a>
                                                        <span>|</span>
                                                    @else
                                                        <em class="qc-color-grey">Đã thanh toán</em>
                                                        <span>|</span>
                                                    @endif
                                                @else
                                                    <em style="color: red;">
                                                        Đã hủy
                                                    </em>
                                                    <span>|</span>
                                                @endif
                                                <a class="qc-link-green"
                                                   href="{!! route('qc.work.orders.info.get',$orderId) !!}">
                                                    Quản lý ĐH
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <a class="qc_view qc-link-green"
                                                   href="{!! route('qc.work.orders.view.get',$orderId) !!}">
                                                    Chi tiết
                                                </a>
                                                @if(!$orders->checkCancelStatus())
                                                    <span>|</span>
                                                    <a class="qc-link-green" target="_blank"
                                                       href="{!! route('qc.work.orders.print.get', $orderId) !!}">
                                                        In
                                                    </a>
                                                    @if(!$orders->checkConfirmStatus())
                                                        @if(!$orders->checkFinishPayment())
                                                            @if($orders->checkStaffInput($dataStaffLogin->staffId()))
                                                                <span>|</span>
                                                                <a class="qc_delete qc-link-green">Hủy</a>
                                                            @endif
                                                        @endif
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                {!! $hFunction->currencyFormat($totalPrice) !!}
                                            </td>
                                            <td class="text-right" style="color: brown;">
                                                {!! $hFunction->currencyFormat($totalDiscount) !!}
                                            </td>
                                            <td class="text-right">
                                                {!! $hFunction->currencyFormat($totalPaid) !!}
                                            </td>
                                            <td class="qc-color-green text-right">
                                                {!! $hFunction->currencyFormat($totalUnPaid) !!}
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td class="text-right" colspan="8"
                                            style="background-color: whitesmoke;">
                                        </td>
                                        <td class="text-right qc-color-red">
                                            <b>{!! $hFunction->currencyFormat($sumOrderMoney)  !!}</b>
                                        </td>
                                        <td class="text-right" style="color: blue;">
                                            <b>{!! $hFunction->currencyFormat($sumDiscountMoney)  !!}</b>
                                        </td>
                                        <td class="text-right qc-color-red">
                                            <b>{!! $hFunction->currencyFormat($sumPaidMoney)  !!}</b>
                                        </td>
                                        <td class="text-right" style="color: blue;">
                                            <b>{!! $hFunction->currencyFormat($sumUnPaidMoney) !!}</b>
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td class="text-center" colspan="12">
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
