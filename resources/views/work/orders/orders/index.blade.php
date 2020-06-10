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
$dataStaffLogin = $modelStaff->loginStaffInfo();
$loginStaffId = $dataStaffLogin->staffId();
#$totalMoneyOrder = $modelOrders->totalMoneyOfListOrder($dataOrders);
$hrefIndex = route('qc.work.orders.get');
$manageStatus = false;
if ($dataStaffLogin->checkBusinessDepartmentAndManageRank()) $manageStatus = true;
?>
@extends('work.orders.index')
@section('titlePage')
    Danh sách đơn hàng
@endsection
@section('qc_work_order_body')
    <div id="qc_work_orders_wrap" class="row qc_work_orders_wrap">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="padding-bottom: 5px;">
                <a class="btn btn-sm btn-primary" onclick="qc_main.page_back();">
                    Về trang trước
                </a>
            </div>
            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-9 col-lg-9">
                    @include('work.orders.menu')
                </div>
                <div class="col-sx-12 col-sm-12 col-md-3 col-lg-3">
                    <select class="qcWorkOrdersStaffFilterId form-control" data-href="{!! $hrefIndex !!}">
                        @if($manageStatus)
                            <option value="0" @if($staffFilterId == 0) selected="selected" @endif>
                                Tất cả nhân viên
                            </option>
                        @endif
                        @if($hFunction->checkCount($dataStaffFilter))
                            @foreach($dataStaffFilter as $staff)
                                <option @if($staff->staffId() == $staffFilterId) selected="selected"
                                        @endif  value="{!! $staff->staffId() !!}">{!! $staff->lastName() !!}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            {{-- chi tiêt --}}
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="qc_work_orders_list_content row"
                     data-href-view-pay="{!! route('qc.work.orders.order_pay.view.get') !!}">
                    <div class="qc-container-table col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" style="margin-bottom: 100px;">
                                <tr style="background-color: black; color: yellow;">
                                    <th class="text-center"></th>
                                    <th>Mã ĐH</th>
                                    <th class="text-center">Thi công</th>
                                    <th style="width: 200px;">Tên ĐH</th>
                                    <th style="width: 200px;">Khách hàng</th>
                                    <th class="text-center">
                                        Ngày nhận
                                    </th>
                                    <th class="text-center">
                                        Hẹn giao
                                    </th>
                                    <th class="text-center">
                                        Ngày Giao khách
                                    </th>
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
                                        Doanh thu
                                    </th>
                                </tr>
                                <tr>
                                    <td class="text-center qc-color-red"></td>
                                    <td></td>
                                    <td class="text-center" style="padding: 0;">
                                        <select class="qcWorkOrdersFinishStatusFilter form-control"
                                                data-href="{!! $hrefIndex !!}">
                                            <option value="100"
                                                    @if($finishStatus == 100) selected="selected" @endif>
                                                Tất cả đơn hàng
                                            </option>
                                            <option value="0" @if($finishStatus == 0) selected="selected" @endif>
                                                Chưa hoàn thành
                                            </option>
                                            <option value="1"
                                                    @if($finishStatus == 1) selected="selected" @endif>
                                                Đã hoàn thành
                                            </option>
                                        </select>
                                    </td>
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
                                    <td class="text-center" style="width: 120px; padding: 0;">
                                        <select class="qcWorkOrderMonthFilter" style="height: 25px;"
                                                data-href="{!! $hrefIndex !!}">
                                            <option value="100" @if($monthFilter == 100) selected="selected" @endif>
                                                Tất cả
                                            </option>
                                            @for($m = 1; $m <=12; $m++)
                                                <option value="{!! $m !!}"
                                                        @if($monthFilter == $m) selected="selected" @endif>
                                                    {!! $m !!}
                                                </option>
                                            @endfor
                                        </select>
                                        <select class="qcWorkOrderYearFilter" style="height: 25px;"
                                                data-href="{!! $hrefIndex !!}">
                                            <option value="100" @if($yearFilter == 100) selected="selected" @endif>
                                                Tất cả
                                            </option>
                                            @for($y = 2017; $y <=2050; $y++)
                                                <option value="{!! $yearFilter !!}"
                                                        @if($yearFilter == $y) selected="selected" @endif>
                                                    {!! $y !!}
                                                </option>
                                            @endfor
                                        </select>
                                    </td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-center" style="padding: 0px;"> {{--phat trien sau--}}
                                        <select class="qcWorkOrderPaymentStatusFilter form-control"
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
                                    $perPage = $dataOrders->perPage();
                                    $currentPage = $dataOrders->currentPage();
                                    $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage;
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
                                        $orderDeliveryDate = $orders->deliveryDate();
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
                                        # trien khai thi cong

                                        # don cu nguoi dang nhap
                                        $ownerStatus = $orders->checkOwnerStatus($loginStaffId,$orderId);

                                        ?>
                                        <tr class="qc_work_list_content_object @if($checkDateOfSort > $orderReceiveDate) danger @elseif($n_o%2) info @endif"
                                            data-object="{!! $orderId !!}">
                                            <td class="text-center">
                                                {!! $n_o += 1 !!}
                                            </td>
                                            <td class="text-center">
                                                <span class="qc-color-grey">{!! $orders->orderCode() !!}</span><br/>
                                            </td>
                                            <td class="text-center">
                                                @if(!$cancelStatus)
                                                    @if($finishStatus)
                                                        <em>Đã kết thúc</em>
                                                        <i class="qc-font-size-16 glyphicon glyphicon-ok"
                                                           style="color: blue;"></i>
                                                    @else
                                                        {{--dang duoc phan cong--}}
                                                        @if($orders->existOrderAllocationOfOrder($orderId))
                                                            @if($orders->existOrderAllocationFinishOfOrder($orderId))
                                                                <em style="padding: 3px; background-color: blue; color: white;">
                                                                    Đã thi công xong
                                                                </em><br/>
                                                            @else
                                                                <em style="padding: 3px; background-color: green; color: yellow;">
                                                                    Đang làm
                                                                </em><br/>
                                                            @endif
                                                        @else
                                                            <em style="padding: 3px; background-color: red; color: white;">
                                                                Chờ làm
                                                            </em><br/>
                                                        @endif
                                                        {{--nguoi so huu hoac là nguoi quan ly--}}
                                                        @if($ownerStatus ||$manageStatus)
                                                            <a class="qc_finish_report qc-link-green-bold"
                                                               data-href="{!! route('work.orders.order.report.finish.get',$orderId) !!}">
                                                                Báo hoàn thành
                                                            </a>
                                                        @endif
                                                    @endif
                                                @else
                                                    <em style="color: brown;">Đã hủy</em>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        <a class="qc-link" title="Click xem menu">
                                                            {!! $orders->name() !!}
                                                        </a>
                                                        @if(!$ownerStatus)
                                                            <br/>
                                                            <em class="qc-color-grey">
                                                                Phụ trách: {!! $orders->staffReceive->lastName() !!}
                                                            </em>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        @if($ownerStatus)
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
                                                                @if($ownerStatus)
                                                                    &nbsp;&nbsp;
                                                                    <a class="qc_delete qc-link-red"
                                                                       data-href="{!! route('qc.work.orders.order.delete.get',$orderId) !!}">
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
                                                       title="Tạo đơn hàng mới"></i>
                                                </a>
                                                <span>|</span>
                                                <a class="qc-link-green"
                                                   href="{!! route('qc.work.orders.add.get',"0/$customerId/$orderId") !!}">
                                                    <i class="qc-font-size-14 glyphicon glyphicon-list-alt"
                                                       title="Báo giá"></i>
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <span>{!! date('d/m', strtotime($orderReceiveDate)) !!}</span>
                                            </td>
                                            <td class="text-center">
                                                <b>{!! date('d/m', strtotime($orderDeliveryDate)) !!}</b>
                                            </td>
                                            <td class="text-center">
                                                @if($hFunction->checkEmpty($orderFinishDate))
                                                    <em class="qc-color-grey">---</em>
                                                @else
                                                    {!! date('d/m/Y', strtotime($orderFinishDate)) !!}
                                                @endif
                                                @if($orders->checkLate($orderId))
                                                    <br/>
                                                    <b style="background-color: red; color: yellow; padding: 3px;">Trễ</b>
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                @if(!$cancelStatus)
                                                    @if(!$orders->checkFinishPayment())
                                                        {{--chu so hua hoac quan ly--}}
                                                        @if($ownerStatus || $manageStatus)
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
                                                    <?php
                                                    $dataOrderAllocationHasPayment = $orders->infoOrderAllocationPaymentStatus($orderId);
                                                    ?>
                                                    @if($hFunction->checkCount($dataOrderAllocationHasPayment))
                                                        @foreach($dataOrderAllocationHasPayment as $orderAllocation)
                                                            <br/>
                                                            <b style="background-color: blue; color: white; padding: 3px;">
                                                                Thu
                                                                hộ: {!! $orderAllocation->receiveStaff->lastName() !!}
                                                            </b>
                                                        @endforeach
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
                                    {{--<tr>
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
                                    </tr>--}}
                                    <tr>
                                        <td class="text-center" colspan="14">
                                            {!! $hFunction->page($dataOrders) !!}
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td class="text-center" colspan="14">
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
