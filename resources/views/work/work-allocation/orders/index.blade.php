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
$hrefIndex = route('qc.work.work_allocation.manage.get');
?>
@extends('work.work-allocation.index')
@section('qc_work_allocation_body')
    <div class="row">
        <div class="qc_work_allocation_manage_wrap qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            @include('work.work-allocation.menu',compact('modelStaff'))

            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
                 style="margin-bottom: 10px; padding-top : 10px;padding-bottom: 10px; border-bottom: 2px dashed brown;">
                <div class="row">
                    <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6"
                         style="padding-left: 0;padding-right: 0;">
                        <a class="qc-link-green-bold" href="{!! $hrefIndex !!}">
                            <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                        </a>
                        <label class="qc-font-size-20">ĐƠN HÀNG</label>
                    </div>
                </div>
            </div>
            <div class="qc_work_allocation_activity_contain col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 2px 0 2px 0;">
                        <div class="row">
                            <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <select class="cbDayFilter" style="height: 25px;" data-href="{!! $hrefIndex !!}">
                                    <option value="100" @if((int)$dayFilter == 100) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($i =1;$i<= 31; $i++)
                                        <option value="{!! $i !!}"
                                                @if((int)$dayFilter == $i) selected="selected" @endif >{!! $i !!}</option>
                                    @endfor
                                </select>
                                <span>/</span>
                                <select class="cbMonthFilter" style="height: 25px;"
                                        data-href="{!! $hrefIndex !!}">
                                    <option value="100" @if((int)$monthFilter == 100) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($i =1;$i<= 12; $i++)
                                        <option value="{!! $i !!}"
                                                @if((int)$monthFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                                    @endfor
                                </select>
                                <span>/</span>
                                <select class="cbYearFilter" style="height: 25px;"
                                        data-href="{!! $hrefIndex !!}">
                                    <option value="100" @if((int)$yearFilter == 100) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($i =2017;$i<= 2050; $i++)
                                        <option value="{!! $i !!}"
                                                @if($yearFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="qc_work_allocation_manage_list_content  row">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr style="background-color: whitesmoke;">
                                <th class="text-center" style="width:20px;"></th>
                                <th>Mã</th>
                                <th style="min-width: 150px;">Tên ĐH</th>
                                {{--<th>Thiết kế</th>--}}
                                <th style="min-width: 150px;">Khách hàng</th>
                                <th style="min-width: 100px;">NV nhận</th>
                                <th>NV & Thi công</th>
                                <th class="text-center">Hình tiến độ</th>
                                <th class="text-center">Thời gian</th>
                            </tr>
                            <tr>
                                <td class="text-center qc-color-red">

                                </td>
                                <td></td>
                                <td style="padding: 0px;">
                                    <div class="input-group">
                                        <input type="text" class="txtOrderFilterKeyword form-control"
                                               name="txtOrderFilterKeyword"
                                               data-href-check-name="{!! route('qc.work.work_allocation.manage.filter.order.check.name') !!}"
                                               placeholder="Tên đơn hàng" value="{!! $orderFilterName !!}">
                                              <span class="input-group-btn">
                                                    <button class="btOrderFilterKeyword btn btn-default" type="button"
                                                            style="border: none;" data-href="{!! $hrefIndex !!}">
                                                        <i class="glyphicon glyphicon-search"></i>
                                                    </button>
                                              </span>
                                    </div>
                                    <div id="qc_work_allocation_filter_order_name_suggestions_wrap"
                                         class="qc-display-none col-xs-12 col-sm-12 col-md-12 col-lg-12"
                                         style="border: 1px solid #d7d7d7;">
                                        <a class='pull-right qc-link-red'
                                           onclick="qc_main.hide('#qc_work_allocation_filter_order_name_suggestions_wrap');">X</a>

                                        <div class="row">
                                            <div id="qc_work_allocation_filter_order_name_suggestions_content"
                                                 class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 0px; min-width: 100px;">
                                    <div class="input-group">
                                        <input type="text" class="txtOrderCustomerFilterKeyword form-control"
                                               name="txtOrderCustomerFilterKeyword"
                                               data-href-check-name="{!! route('qc.work.work_allocation.manage.filter.customer.check.name') !!}"
                                               placeholder="Tên khách hàng"
                                               value="{!! $orderCustomerFilterName !!}">
                                              <span class="input-group-btn">
                                                    <button class="btOrderCustomerFilterKeyword btn btn-default"
                                                            type="button" style="border: none;"
                                                            data-href="{!! $hrefIndex !!}">
                                                        <i class="glyphicon glyphicon-search"></i>
                                                    </button>
                                              </span>
                                    </div>
                                    <div id="qc_work_allocation_filter_customer_name_suggestions_wrap"
                                         class="qc-display-none col-xs-12 col-sm-12 col-md-12 col-lg-12"
                                         style="border: 1px solid #d7d7d7;">
                                        <a class='pull-right qc-link-red'
                                           onclick="qc_main.hide('#qc_work_allocation_filter_customer_name_suggestions_wrap');">X</a>

                                        <div class="row">
                                            <div id="qc_work_allocation_filter_customer_name_suggestions_content"
                                                 class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center" style="padding: 0px;">
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
                                </td>
                                <td class="text-center" style="padding: 0px;">

                                </td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
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
                                                    {!! $order->name() !!}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    <a class="qc-link-green"
                                                       href="{!! route('qc.work.work_allocation.manage.order.view', $orderId) !!}">
                                                        <i class="qc-font-size-14 glyphicon glyphicon-eye-open"></i>
                                                    </a>
                                                    &nbsp;&nbsp;
                                                    <a class="qc-link" title="In đơn hàng"
                                                       href="{!! route('qc.work.work_allocation.manage.order.print.get', $orderId) !!}">
                                                        <i class="qc-font-size-14 fa fa-print"></i>
                                                    </a>
                                                    @if($order->checkConfirmStatus())
                                                        <span>&nbsp;&nbsp;</span>
                                                        <a class="qc-link-green" title="In nghiệm thu"
                                                           href="{!! route('qc.work.work_allocation.manage.order.print_confirm.get', $orderId) !!}">
                                                            <i class="qc-font-size-16 glyphicon glyphicon-list-alt"></i>
                                                        </a>
                                                    @endif

                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            {!! $order->customer->name() !!}
                                        </td>
                                        <td class="qc-color-grey">
                                            {!! $dataReceiveStaff->lastName() !!}
                                        </td>
                                        <td class="text-center">
                                            @if(!$cancelStatus)
                                                <a class="qc-link-green" title="Click xem chi tiết thi công"
                                                   href="{!! route('qc.work.work_allocation.manage.order.construction.get',$orderId) !!}">
                                                    @if(!$finishStatus)
                                                        Bàn giao công trình
                                                    @else
                                                        Đã kết thúc
                                                    @endif
                                                </a>

                                                @if($hFunction->checkCount($orderWorkAllocationAllInfo))
                                                    <br/>
                                                    Thi công:
                                                    @foreach($orderWorkAllocationAllInfo as $workAllocation)
                                                        @if($workAllocation->checkActivity())
                                                            <span>{!! $workAllocation->receiveStaff->lastName() !!}</span>
                                                            ,
                                                            &nbsp;
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-center qc-color-red">
                                            @if($hFunction->checkCount($orderWorkAllocationAllInfo))
                                                <?php
                                                $dataWorkAllocationReportImage = $order->workAllocationReportImage($orderId, 1);
                                                ?>
                                                @if($hFunction->checkCount($dataWorkAllocationReportImage))
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        @foreach($dataWorkAllocationReportImage as $workAllocationReportImage)
                                                            <div style="position: relative; float: left; margin: 5px; width: 70px; height: 70px; border: 1px solid #d7d7d7;">
                                                                <a class="qc_work_allocation_report_image_view qc-link"
                                                                   title="Click xem chi tiết hình ảnh"
                                                                   data-href="{!! route('qc.ad3d.work.work_allocation_report.image.view',$workAllocationReportImage->imageId()) !!}">
                                                                    <img style="max-width: 100%; max-height: 100%;"
                                                                         src="{!! $workAllocationReportImage->pathSmallImage($workAllocationReportImage->name()) !!}">
                                                                </a>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <div class="text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        <a class="qc-link-green" title="Click xem chi tiết thi công"
                                                           href="{!! route('qc.work.work_allocation.manage.order.construction.get',$orderId) !!}">
                                                            Chi tiết...
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
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="text-center" colspan="8">
                                        {!! $hFunction->page($dataOrder) !!}
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td class="text-center" colspan="8">
                                        <em class="qc-color-red">Không tìm thấy thông tin phù hợp</em>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
            <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <a href="{!! route('qc.work.home') !!}">
                    <button type="button" class="btn btn-primary">
                        Đóng
                    </button>
                </a>
            </div>
        </div>
    </div>
@endsection
