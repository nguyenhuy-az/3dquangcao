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
$hrefIndex = route('qc.work.orders.get');
?>
@extends('work.orders.index')
@section('titlePage')
    Danh sách đơn hàng
@endsection
@section('qc_work_order_body')
    <div id="qc_work_orders_wrap" class="row qc_work_orders_wrap">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed #C2C2C2;">
                    <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="active">
                                <a class="qc-link" href="{!! $hrefIndex !!}" style="background-color: whitesmoke;">
                                    <i class="qc-font-size-20 glyphicon glyphicon-refresh" style="color: red;"></i>
                                    <label class="qc-font-size-20">ĐƠN HÀNG & THANH TOÁN</label>
                                </a>
                            </li>
                            <li>
                                <a class="qc-link" href="{!! route('qc.work.orders.provisional.get') !!}">
                                    <label class="qc-font-size-20">BÁO GIÁ</label>
                                    <em class="qc-font-size-20"
                                        style="color: red;">({!! $hFunction->getCountFromData($dataOrdersProvisional) !!})</em>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="text-right col-sx-12 col-sm-12 col-md-6 col-lg-6">
                        <a class="qc_work_before_pay_request_action qc-link-green-bold"
                           href="{!! route('qc.work.orders.add.get') !!}">
                            THÊM ĐƠN HÀNG
                        </a>
                        &nbsp; | &nbsp;
                        <a class="qc-link-red-bold" title="Báo giá" href="{!! route('qc.work.orders.add.get',0) !!}">
                            BÁO GIÁ
                        </a>
                    </div>
                </div>
            </div>
            {{-- chi tiêt --}}
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="text-right qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <select class="qc_work_orders_login_month" style="height: 25px;"
                                data-href="{!! $hrefIndex !!}">
                            <option value="100" @if($monthFilter == 100) selected="selected" @endif>Tất cả</option>
                            @for($i = 1; $i <=12; $i++)
                                <option @if($monthFilter == $i) selected="selected" @endif>
                                    {!! $i !!}
                                </option>
                            @endfor
                        </select>
                        <span>/</span>
                        <select class="qc_work_orders_login_year" style="height: 25px;"
                                data-href="{!! $hrefIndex !!}">
                            <option value="100" @if($yearFilter == 100) selected="selected" @endif>Tất cả</option>
                            @for($i = 2017; $i <=2050; $i++)
                                <option @if($yearFilter == $i) selected="selected" @endif>
                                    {!! $i !!}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="qc_work_orders_list_content row" data-href-view-pay="{!! route('qc.work.orders.order_pay.view.get') !!}">
                    <div class="qc-container-table col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" style="margin-bottom: 100px;">
                                <tr style="background-color: black; color: yellow;">
                                    <th class="text-center"></th>
                                    <th>Mã ĐH</th>
                                    <th style="width: 200px;">Tên ĐH</th>
                                    <th style="width: 200px;">Khách hàng</th>
                                    <th class="text-center">
                                        Ngày nhận <br/>
                                        <em> - Hẹn giao</em>
                                    </th>
                                    <th class="text-center">Ngày Hoàn thành</th>
                                    <th class="text-center">Thi công</th>
                                    <th class="text-center" style="min-width: 70px;">Thu tiền</th>
                                    <th class="text-right">Tổng tiền ĐH</th>
                                    <th class="text-right">Giảm</th>
                                    <th class="text-right">
                                        Tổng Đã thu <br/>
                                        <em>(Của 1 ĐH)</em>
                                    </th>
                                    <th class="text-right">
                                        Còn lại<br/>
                                        <em>(Chưa thu)</em>
                                    </th>
                                    <th class="text-right">
                                        Doanh thu <br>
                                        @if($monthFilter == 100)
                                            <span class="qc-color-red">{!! $hFunction->getYearFromDate($dateFilter) !!}</span>
                                        @else
                                            <span class="qc-color-red">{!! $hFunction->getMonthYearFromDate($dateFilter) !!}</span>
                                        @endif
                                    </th>
                                </tr>
                                <tr>
                                    <td class="text-center qc-color-red">
                                        <b>{!! $hFunction->getCountFromData($dataOrders) !!}</b>
                                    </td>
                                    <td></td>
                                    <td style="padding: 0px;">
                                        <div class="input-group">
                                            <input type="text" class="txtOrderFilterKeyword form-control"
                                                   name="txtOrderFilterKeyword"
                                                   data-href-check-name="{!! route('qc.work.orders.filter.order.check.name') !!}"
                                                   placeholder="Tìm ĐƠN HÀNG" value="{!! $orderFilterName !!}">
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
                                    </td>
                                    <td style="padding: 0px;">
                                        <div class="input-group">
                                            <input type="text" class="txtOrderCustomerFilterKeyword form-control"
                                                   name="txtOrderCustomerFilterKeyword"
                                                   data-href-check-name="{!! route('qc.work.orders.filter.customer.check.name') !!}"
                                                   placeholder="Tìm KHÁCH HÀNG"
                                                   value="{!! $orderCustomerFilterName !!}">
                                              <span class="input-group-btn">
                                                    <button class="btOrderCustomerFilterKeyword btn btn-default"
                                                            type="button"
                                                            data-href="{!! $hrefIndex !!}">
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
                                    </td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-center" style="padding: 0px;"> {{--phat trien sau--}}
                                        <select class="qc_work_orders_login_payment_status form-control"
                                                style="display: none;"
                                                data-href="{!! $hrefIndex !!}">
                                            <option value="0" @if($paymentStatus == 0) selected="selected" @endif>
                                                Chưa thanh toán Xong
                                            </option>
                                            <option value="1" @if($paymentStatus == 1) selected="selected" @endif>
                                                Đã Thanh toán xong
                                            </option>
                                            <option value="3" @if($paymentStatus > 2) selected="selected" @endif>
                                                Tất cả
                                            </option>
                                        </select>
                                    </td>
                                    <td></td>
                                    <td class="text-right" style="color: blue;"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right qc-color-green"></td>
                                    <td class="text-right qc-color-green"></td>
                                </tr>
                                @if($hFunction->checkCount($dataOrders))
                                    <?php
                                    $n_o = 0;
                                    $sumOrderMoney = 0;
                                    $sumDiscountMoney = 0;
                                    $sumPaidMoney = 0;
                                    $sumUnPaidMoney = 0;
                                    $sumPaidMoneyInDate = 0;
                                    ?>
                                    @foreach($dataOrders as $orders)
                                        <?php
                                        $orderId = $orders->orderId();
                                        $customerId = $orders->customerId();
                                        $orderReceiveDate = $orders->receiveDate();
                                        $orderFinishDate = $orders->finishDate();
                                        $createdAt = $orders->createdAt();
                                        $totalPrice = $orders->totalPrice();
                                        $sumOrderMoney = $sumOrderMoney + $totalPrice;
                                        $totalDiscount = $orders->totalMoneyDiscount();
                                        $sumDiscountMoney = $sumDiscountMoney + $totalDiscount;
                                        $totalPaid = $orders->totalPaid();
                                        $sumPaidMoney = $sumPaidMoney + $totalPaid;
                                        //$totalUnPaid = $totalPrice - $totalDiscount - $totalPaid;
                                        $amountOrderPay = count($orders->infoOrderPayOfOrder($orderId));
                                        $totalUnPaid = $orders->totalMoneyUnpaid();
                                        $totalUnPaid = ($totalUnPaid < 0) ? 0 : $totalUnPaid;
                                        $sumUnPaidMoney = $sumUnPaidMoney + $totalUnPaid;
                                        $paidMoneyInDate = $orders->totalPaidInDate($orderId, $dateFilter); // thu tien trong thang cua don hang
                                        $sumPaidMoneyInDate = $sumPaidMoneyInDate + $paidMoneyInDate;
                                        //kiem tra don hang truoc
                                        $checkDateOfSort = $hFunction->firstDateOfMonthFromDate(date('Y/m/d', strtotime("1-$monthFilter-$yearFilter")));
                                        $finishStatus = $orders->checkFinishStatus();
                                        $cancelStatus = $orders->checkCancelStatus();
                                        $checkOrderOfReceiveStaff = ($loginStaffId == $orders->staffReceiveId()) ? true : false; // kiem tra don hang thuoc nguoi dang nhap
                                        # trien khai thi cong
                                        ?>
                                        <tr class="qc_work_list_content_object @if($checkDateOfSort > $orderReceiveDate) danger @elseif($n_o%2) info @endif"
                                            data-object="{!! $orderId !!}">
                                            <td class="text-center">
                                                {!! $n_o += 1 !!}
                                            </td>
                                            <td class="text-center">
                                                <span class="qc-color-grey">{!! $orders->orderCode() !!}</span><br/>
                                                @if($finishStatus)
                                                    <i class="qc-font-size-16 qc-color-green glyphicon glyphicon-ok"
                                                       title="Đang triển khai"></i>
                                                @else
                                                    <i class="qc-font-size-16 qc-color-red glyphicon glyphicon-ok"
                                                       title="Đã kết thúc"></i>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        <a class="qc-link" title="Click xem menu">
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
                                                            <a class="qc-link" title="Quản lý ĐH"
                                                               href="{!! route('qc.work.orders.info.get',$orderId) !!}">
                                                                <i class="qc-font-size-14 glyphicon glyphicon-pencil"></i>
                                                            </a>
                                                            &nbsp;&nbsp;
                                                        @endif
                                                        <a class="qc-link-green" title="Xem chi tiết Đơn hàng"
                                                           href="{!! route('qc.work.orders.view.get',$orderId) !!}">
                                                            <i class="qc-font-size-14 glyphicon glyphicon-eye-open"></i>
                                                        </a>
                                                        &nbsp;&nbsp;
                                                        <a class="qc-link" target="_blank" title="In đơn hàng"
                                                           href="{!! route('qc.work.orders.print.get', $orderId) !!}">
                                                            <i class="qc-font-size-14 fa fa-print"></i>
                                                        </a>
                                                        @if(!$cancelStatus)
                                                                @if($orders->checkConfirmStatus())
                                                                    &nbsp;&nbsp;
                                                                    <a class="qc-link-green" title="In nghiệm thu"
                                                                       href="{!! route('qc.work.orders.print_confirm.get', $orderId) !!}">
                                                                        <i class="qc-font-size-14 glyphicon glyphicon-list-alt"></i>
                                                                    </a>
                                                                @endif
                                                            @if(!$finishStatus)
                                                                @if($orders->checkStaffInput($dataStaffLogin->staffId()))
                                                                    &nbsp;&nbsp;
                                                                    <a class="qc_delete qc-link-red" data-href="{!! route('qc.work.orders.order.delete.get',$orderId) !!}">
                                                                        <i class="qc-font-size-16 fa fa-trash"></i>
                                                                    </a>
                                                                @endif
                                                            @endif
                                                        @endif

                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a class="qc_view_customer qc-link" title="Click xem chi tiết"
                                                   data-href="{!! route('qc.work.orders.view.customer.get',$customerId) !!}">
                                                    {!! $orders->customer->name() !!}
                                                </a>
                                                <br/>
                                                <a class="qc_view_customer qc-link-green"
                                                   title="Xem chi tiết Khách hàng"
                                                   data-href="{!! route('qc.work.orders.view.customer.get',$customerId) !!}">
                                                    <i class="qc-font-size-14 glyphicon glyphicon-eye-open"></i>
                                                </a>
                                                <span>|</span>
                                                <a class="qc-link-red"
                                                   href="{!! route('qc.work.orders.add.get',"1/$customerId/$orderId") !!}">
                                                    <i class="qc-font-size-14 glyphicon glyphicon-shopping-cart"
                                                       title="Đặt hàng"></i>
                                                </a>
                                                <span>|</span>
                                                <a class="qc-link-green"
                                                   href="{!! route('qc.work.orders.add.get',"0/$customerId/$orderId") !!}">
                                                    <i class="qc-font-size-14 glyphicon glyphicon-list-alt"
                                                       title="Báo giá"></i>
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <b>{!! $hFunction->convertDateDMYFromDatetime($orderReceiveDate) !!}</b>
                                                <br/>
                                                <em class="qc-color-grey">{!! $hFunction->convertDateDMYFromDatetime($orders->deliveryDate()) !!}</em>
                                            </td>
                                            <td class="text-center">
                                                @if($hFunction->checkEmpty($orderFinishDate))
                                                    <em class="qc-color-grey">Null</em>
                                                @else
                                                    {!! date('d/m/Y', strtotime($orderFinishDate)) !!}
                                                @endif

                                            </td>
                                            <td class="text-center">
                                                @if(!$cancelStatus)
                                                    @if($finishStatus)
                                                        <em>Đã kết thúc</em>
                                                    @else
                                                        {{--dang duoc phan cong--}}
                                                        @if($orders->existOrderAllocationActivityOfOrder($orderId))
                                                            <em style="color: brown;">Đang làm</em><br/>
                                                        @else
                                                            <em style="color: grey;">Chờ làm</em><br/>
                                                        @endif
                                                        <a class="qc_finish_report qc-link-green-bold"
                                                           data-href="{!! route('work.orders.order.report.finish.get',$orderId) !!}">
                                                            Báo hoàn thành
                                                        </a>
                                                    @endif
                                                @else
                                                    <em style="color: brown;">Đã hủy</em>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if(!$cancelStatus)
                                                    @if(!$orders->checkFinishPayment())
                                                        @if($checkOrderOfReceiveStaff)
                                                            <a class="qc-link-red" title="Thanh toán đơn hàng"
                                                               href="{!! route('qc.work.orders.payment.get', $orderId) !!} ">
                                                                Thanh toán <br/>
                                                                <img alt="icon"
                                                                     style="margin-bottom: 3px; border: 1px solid #d7d7d7; width: 30px; height: 30px;"
                                                                     src="{!! asset('public/images/icons/paymentIcon.jpg') !!}"/>
                                                            </a>
                                                        @else
                                                            <em class="qc-color-grey">Vô hiệu</em>
                                                        @endif
                                                    @else
                                                        <em class="qc-color-grey">Đã thanh toán xong</em>
                                                    @endif
                                                @else
                                                    <em style="color: brown;">Đã hủy</em>
                                                @endif

                                            </td>
                                            <td class="text-right">
                                                {!! $hFunction->currencyFormat($totalPrice) !!}
                                            </td>
                                            <td class="text-right" style="color: brown;">
                                                {!! $hFunction->currencyFormat($totalDiscount) !!}
                                            </td>
                                            <td class="text-right">
                                                <a class="qc_order_pay_view qc-link-bold" title="Click xem chi tiết TT"
                                                   data-amount="{!! $amountOrderPay !!}">
                                                    {!! $hFunction->currencyFormat($totalPaid) !!}
                                                    <br/>
                                                    ({!! $amountOrderPay !!})
                                                </a>
                                            </td>
                                            <td class="qc-color-green text-right">
                                                {!! $hFunction->currencyFormat($totalUnPaid) !!}
                                            </td>
                                            <td class="text-right" style="color: blue;">
                                                <a class="qc_order_pay_view qc-link-bold" title="Click xem chi tiết TT"
                                                   data-amount="{!! $amountOrderPay !!}">
                                                    {!! $hFunction->currencyFormat($paidMoneyInDate) !!}
                                                </a>
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
                                        <td class="text-right" style="color: red;">
                                            <b>{!! $hFunction->currencyFormat($sumPaidMoneyInDate) !!}</b>
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td class="text-center" colspan="13">
                                            <em class="qc-color-red">Không đơn hàng</em>
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                </div>
            </div>
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <a class="btn btn-sm btn-primary" onclick="qc_main.page_back();">
                    Về trang trước
                </a>
                <a class="btn btn-sm btn-default" href="{!! route('qc.work.home') !!}">
                    Về trang chủ
                </a>
            </div>
        </div>
    </div>
@endsection
