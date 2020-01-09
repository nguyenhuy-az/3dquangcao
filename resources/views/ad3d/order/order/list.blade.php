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
?>
@extends('ad3d.order.order.index')
@section('qc_ad3d_order_order')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
             style="margin-bottom: 10px; padding-top : 10px;padding-bottom: 10px; border-bottom: 2px dashed brown;">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! $hrefIndex !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <label class="qc-font-size-20">ĐƠN HÀNG</label>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <select class="cbCompanyFilter" name="cbCompanyFilter" style="margin-top: 5px; height: 25px;"
                            data-href-filter="{!! $hrefIndex !!}">
                        @if($dataStaffLogin->checkRootManage())
                            <option value="1000">Tất cả</option>
                        @endif
                        @if(count($dataCompany)> 0)
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
            </div>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 2px 0 2px 0;">
                    <form name="" action="">
                        <div class="row">
                            <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <select class="cbDayFilter" style="height: 25px;"
                                        data-href="{!! $hrefIndex !!}">
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
                    </form>
                </div>
            </div>
            <div class="qc_ad3d_list_content qc-order-order-object row"
                 data-="{!! route('qc.ad3d.order.order.confirm.get') !!}"
                 data-href-view="{!! route('qc.ad3d.order.order.view.get') !!}"
                 data-href-view-customer="{!! route('qc.ad3d.order.order.view_customer.get') !!}"
                 data-href-del="{!! route('qc.ad3d.order.order.delete') !!}">

                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: whitesmoke;">
                            <th class="text-center"></th>
                            <th>Mã</th>
                            <th style="min-width: 200px;">Tên ĐH</th>
                            <th style="min-width: 100px;">Thiết kế</th>
                            <th style="min-width: 200px;">Khách hàng</th>
                            <th style="min-width: 100px;">NV nhận</th>
                            <th style="min-width: 150px;">NV & Thi công</th>
                            <th class="text-center" style="min-width: 100px;">Hình tiến độ</th>
                            <th class="text-center">TG nhận</th>
                            <th class="text-center">TG Giao</th>
                            <th class="text-center" style="min-width: 100px;">Thu tiền</th>
                            <th class="text-right">Tổng tiền</th>
                            <th class="text-right">Giảm</th>
                            <th class="text-right">Doanh thu</th>
                            <th class="text-right">
                                Còn lại <br/>
                                Phải thu
                            </th>
                        </tr>
                        <tr>
                            <td class="text-center qc-color-red">
                                <b>{!! $totalOrders !!}</b>
                            </td>
                            <td></td>
                            <td style="padding: 0px;">
                                <div class="input-group">
                                    <input type="text" class="txtOrderFilterKeyword form-control"
                                           name="txtOrderFilterKeyword"
                                           data-href-check-name="{!! route('qc.ad3d.work.orders.filter.order.check.name') !!}"
                                           placeholder="Tìm tên đơn hàng" value="{!! $orderFilterName !!}">
                                              <span class="input-group-btn">
                                                    <button class="btOrderFilterKeyword btn btn-default" type="button"
                                                            style="border: none;" data-href="{!! $hrefIndex !!}">
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
                            <td></td>
                            <td style="padding: 0px; min-width: 100px;">
                                <div class="input-group">
                                    <input type="text" class="txtOrderCustomerFilterKeyword form-control"
                                           name="txtOrderCustomerFilterKeyword"
                                           data-href-check-name="{!! route('qc.ad3d.work.orders.filter.customer.check.name') !!}"
                                           placeholder="Tìm tên khách hàng"
                                           value="{!! $orderCustomerFilterName !!}">
                                              <span class="input-group-btn">
                                                    <button class="btOrderCustomerFilterKeyword btn btn-default"
                                                            type="button" style="border: none;"
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
                            <td class="text-center" style="padding: 0px;">
                                <select class="cbStaffFilterId form-control"
                                        data-href="{!! route('qc.ad3d.order.order.get') !!}">
                                    <option value="0" @if($staffFilterId == 0) selected="selected" @endif>
                                        Tất cả
                                    </option>
                                    @if(count($dataStaff)> 0)
                                        @foreach($dataStaff as $staff)
                                            <option @if($staff->staffId() == $staffFilterId) selected="selected"
                                                    @endif  value="{!! $staff->staffId() !!}">{!! $staff->lastName() !!}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </td>
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
                            <td></td>
                            <td class="text-center">

                            </td>
                            <td class="text-center"></td>
                            <td style="padding: 0px;">
                                <select class="cbPaymentStatus form-control"
                                        data-href="{!! route('qc.ad3d.order.order.get') !!}">
                                    <option value="2" @if($paymentStatus == 2) selected="selected" @endif>Tất cả
                                    </option>
                                    <option value="1" @if($paymentStatus == 1) selected="selected" @endif>
                                        Đã thu xong
                                    </option>
                                    <option value="0" @if($paymentStatus == 0) selected="selected" @endif>
                                        Chưa thu xong
                                    </option>
                                </select>
                            </td>
                            <td class="text-right" style="color: blue;">
                                <b>{!! $hFunction->currencyFormat($totalMoneyOrder) !!}</b>
                            </td>
                            <td class="text-right">
                                <b>{!! $hFunction->currencyFormat($totalMoneyDiscountOrder) !!}</b>
                            </td>
                            <td class="text-right qc-color-green">
                                <b>{!! $hFunction->currencyFormat($totalMoneyPaidOrder) !!}</b>
                            </td>
                            <td class="text-right qc-color-red">
                                <b>{!! $hFunction->currencyFormat($totalMoneyUnPaidOrder) !!}</b>
                            </td>

                        </tr>
                        @if(count($dataOrder) > 0)
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
                                                <a class="qc-link" title="Click xem menu"
                                                   onclick="qc_main.toggle('#ad3d_order_menu_{!! $orderId !!}');">
                                                    {!! $order->name() !!}
                                                </a>
                                            </div>
                                        </div>
                                        <div id="ad3d_order_menu_{!! $orderId !!}" class="row qc-display-none">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <a class="qc_view qc-link-green">
                                                    <i class="qc-font-size-20 glyphicon glyphicon-eye-open"></i>
                                                </a>
                                                &nbsp;&nbsp;
                                                <a class="qc-link" title="In đơn hàng"
                                                   href="{!! route('qc.ad3d.order.order.print.get', $orderId) !!}">
                                                    <i class="qc-font-size-20 fa fa-print"></i>
                                                </a>
                                                @if(!$cancelStatus)
                                                    @if($order->checkConfirmStatus())
                                                        &nbsp;&nbsp;
                                                        <a class="qc-link-green" title="In nghiệm thu"
                                                           href="{!! route('qc.ad3d.order.order.confirm.print.get', $orderId) !!}">
                                                            <i class="qc-font-size-20 glyphicon glyphicon-list-alt"></i>
                                                        </a>
                                                    @endif
                                                @endif

                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center qc-color-red">
                                        <a class="qc_view qc-link-green">
                                            Xem thiết kế
                                        </a>
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
                                    <td class="text-center">
                                        @if(!$cancelStatus)
                                            <a class="qc-link-green" title="Click xem chi tiết thi công"
                                               href="{!! route('qc.ad3d.order.order.construction.get',$orderId) !!}">
                                                @if(!$finishStatus)
                                                    Bàn giao công trình
                                                @else
                                                    Đã kết thúc
                                                @endif
                                            </a>

                                            @if(count($orderWorkAllocationAllInfo)> 0)
                                                <br/>
                                                TC:
                                                @foreach($orderWorkAllocationAllInfo as $workAllocation)
                                                    <span>{!! $workAllocation->receiveStaff->lastName() !!}</span>,
                                                    &nbsp;
                                                @endforeach
                                            @endif
                                        @else
                                            <em style=" color: brown;">
                                                Đã hủy
                                            </em>
                                        @endif
                                        @if(count($dataProduct) >0)
                                            <br/>
                                        @endif
                                    </td>
                                    <td class="text-center qc-color-red">
                                        @if(count($orderWorkAllocationAllInfo)> 0)
                                            <?php
                                            $dataWorkAllocationReportImage = $order->workAllocationReportImage($orderId, 1);
                                            ?>
                                            @if(count($dataWorkAllocationReportImage)> 0)
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
                                                       href="{!! route('qc.ad3d.order.order.construction.get',$orderId) !!}">
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
                                        {!! date('d/m/Y', strtotime($order->receiveDate())) !!}
                                    </td>
                                    <td class="text-center qc-padding-top-none qc-padding-bot-none">
                                        {!! date('d/m/Y', strtotime($order->deliveryDate())) !!}
                                    </td>

                                    <td class="text-center">
                                        @if(!$cancelStatus)
                                            @if($checkFinishPayment)
                                                <em class="qc-color-grey">
                                                    Đã thanh toán
                                                </em>
                                            @else
                                                <a class="qc-link-red"
                                                   href="{!! route('qc.ad3d.order.order.payment.get',$orderId) !!}">
                                                    Thanh toán
                                                    <br/>
                                                    <img alt="icon"
                                                         style="margin-bottom: 3px; border: 1px solid #d7d7d7; width: 30px; height: 30px;"
                                                         src="{!! asset('public/images/icons/paymentIcon.jpg') !!}"/>
                                                </a>
                                            @endif
                                            @if(!$order->checkConfirmStatus())
                                                <span>|</span>
                                                <a class="qc_confirm qc-link-green">
                                                    Xác nhận
                                                </a>
                                            @endif
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
                                <td class="text-center" colspan="14">
                                    {!! $hFunction->page($dataOrder) !!}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="text-center" colspan="14">
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
