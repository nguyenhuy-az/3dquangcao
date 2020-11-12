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
$hrefIndex = route('qc.work.work_allocation.orders.index');
# lay thong tin NV cap quan ly bo phan thi cong
$dataStaffConstruction = $modelStaff->infoStaffConstructionRankManage($dataStaffLogin->companyId());
$actionStatus = false;
if ($dataStaffLogin->checkApplyRule()) $actionStatus = true; # quan ly co ap dung noi quy moi co quyen hanh dong tren trang
?>
@extends('work.work-allocation.orders.index')
@section('qc_work_allocation_body')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <a class="btn btn-sm btn-primary" onclick="qc_main.page_back();">
                Về trang trước
            </a>
        </div>
        <div class="qc_work_allocation_manage_wrap qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            @include('work.work-allocation.menu',compact('modelStaff'))

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <h4 style="background-color: red; color: white; margin: 0; padding: 5px;">
                        QUẢN LÝ THI CÔNG @if($actionStatus) "{!! $dataStaffLogin->lastName() !!}" @endif
                        PHẢI CHỊU TRÁCH NHIỆM THƯỞNG / PHẠT TRÊN TẤT CẢ ĐƠN HÀNG
                    </h4>
                </div>
            </div>
            <div class="qc_work_allocation_activity_contain col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="qc_work_allocation_manage_list_content  row">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr style="background-color: black; color: yellow;">
                                <th class="text-center" style="width:20px;">
                                    STT
                                </th>
                                <th>THI CÔNG</th>
                                <th style="min-width: 150px;">ĐƠN HÀNG</th>
                                <th class="text-center">NGÀY NHẬN/GIAO</th>
                                <th class="text-center">
                                    NGÀY BÀN GIAO <br/>
                                    <em style="color: white;">(Ngày xác nhận)</em>
                                </th>
                                <th>PHÂN VIỆC</th>
                                <th>ẢNH TIẾN ĐỘ</th>
                                <th class="text-right">
                                    NGÂN SÁCH THƯỞNG <br/>
                                    <em style="color: white;">(Đúng hẹn)</em>
                                </th>
                                <th class="text-right">
                                    PHẠT <br/>
                                    <em style="color: white;">(Trễ hẹn)</em>
                                </th>
                                <th>KHÁCH HÀNG</th>
                            </tr>
                            <tr>
                                <td class="text-center qc-color-red"></td>
                                <td class="text-center" style="padding: 0px;">
                                    <select class="cbAllocationManageFinishStatus form-control"
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
                                        <input type="text" class="txtAllocationManageKeywordFilter form-control"
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
                                <td class="text-center" style="padding: 0;">
                                    <select class="cbAllocationManageDayFilter text-right col-xs-3 col-sm-3 col-md-3 col-lg-3"
                                            style="height: 34px; padding: 0;"
                                            data-href="{!! $hrefIndex !!}">
                                        <option value="100" @if((int)$dayFilter == 100) selected="selected" @endif >
                                            Tất cả
                                        </option>
                                        @for($d =1;$d<= 31; $d++)
                                            <option value="{!! $d !!}"
                                                    @if((int)$dayFilter == $d) selected="selected" @endif >
                                                {!! $d !!}
                                            </option>
                                        @endfor
                                    </select>
                                    <select class="cbAllocationManageMonthFilter text-right col-xs-3 col-sm-3 col-md-3 col-lg-3"
                                            style="height: 34px; padding: 0;"
                                            data-href="{!! $hrefIndex !!}">
                                        <option value="100" @if((int)$monthFilter == 100) selected="selected" @endif >
                                            Tất cả
                                        </option>
                                        @for($m =1;$m<= 12; $m++)
                                            <option value="{!! $m !!}"
                                                    @if((int)$monthFilter == $m) selected="selected" @endif>
                                                {!! $m !!}
                                            </option>
                                        @endfor
                                    </select>
                                    <select class="cbAllocationManageYearFilter text-right col-xs-6 col-sm-6 col-md-6 col-lg-6"
                                            style="height: 34px; padding: 0;"
                                            data-href="{!! $hrefIndex !!}">
                                        <option value="100" @if((int)$yearFilter == 100) selected="selected" @endif >
                                            Tất cả
                                        </option>
                                        @for($y =2017;$y<= 2050; $y++)
                                            <option value="{!! $y !!}"
                                                    @if($yearFilter == $y) selected="selected" @endif>
                                                {!! $y !!}
                                            </option>
                                        @endfor
                                    </select>
                                </td>
                                <td></td>
                                <td style="padding: 0px;"></td>
                                <td></td>
                                <td></td>
                                <td></td>
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
                                    $orderFinishStatus = $order->checkFinishStatus();
                                    $orderConfirmDate = $order->confirmDate();
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
                                    # thong tin ban giao don hang
                                    $dataOrderAllocation = $order->orderAllocationActivity()->first();
                                    # thong tin thi cong da xac nhan hoan thanh
                                    $dataOrderAllocationFinish = $order->infoAllocationFinish($orderId);
                                    // san pham
                                    $dataProduct = $order->allProductOfOrder();

                                    // phan viec tren san pham
                                    $orderWorkAllocationAllInfo = $order->workAllocationOnProduct();

                                    # tien thuong va phat
                                    $totalMoneyBonusAndMinus = $order->getBonusAndMinusMoneyOfConstructionManage();
                                    ?>
                                    <tr class="qc_ad3d_list_object @if($n_o%2 == 1) info @endif"
                                        data-object="{!! $orderId !!}">
                                        <td class="text-center">
                                            <b>{!! $n_o += 1 !!}</b>
                                        </td>
                                        <td>
                                            @if(!$cancelStatus)
                                                <a class="qc-link-green" title="Click xem chi tiết thi công"
                                                   href="{!! route('qc.work.work_allocation.order.construction.get',$orderId) !!}">
                                                    QUẢN LÝ THI CÔNG
                                                </a>
                                                @if($order->checkWaitConfirmFinish())
                                                    <br/>
                                                    <span style="padding: 3px; background-color: green; color: yellow;">Chờ Xác nhận hoàn thành</span>
                                                @else
                                                    <br/>
                                                    @if($hFunction->checkCount($dataOrderAllocation))
                                                        <span style="padding: 3px; background-color: blue; color: white;">
                                                            Phụ trách: {!! $dataOrderAllocation->receiveStaff->lastName() !!}
                                                        </span>
                                                    @else
                                                        @if(!$orderFinishStatus)
                                                            {{--Da xac nhan hoan thanh thi cong--}}
                                                            @if($hFunction->checkCount($dataOrderAllocationFinish))
                                                                <span style="padding: 3px; background-color: blue; color: white;">
                                                                    Chờ bàn giao khách hàng
                                                                </span>
                                                            @else
                                                                <span style="padding: 3px; background-color: red; color: white;">Chưa bàn giao</span>
                                                            @endif
                                                        @else
                                                            <em>(Đã kết thúc)</em>
                                                        @endif
                                                    @endif
                                                @endif
                                            @else
                                                <em style="color: grey;">Đã hủy</em>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    <b>{!! $order->name() !!}</b>
                                                    @if(!$dataStaffLogin->checkViewNotifyNewOrder($dataStaffLogin->staffId(),$orderId))
                                                        <em style="color: red;"> - Chưa xem</em>
                                                    @endif
                                                    <br/>
                                                    <em class="qc-color-grey">Mã ĐH: {!! $order->orderCode() !!}</em>
                                                    {{--@if($orderFinishStatus)
                                                        <i class="qc-color-green glyphicon glyphicon-ok"></i>
                                                    @else
                                                        <i class="qc-color-red glyphicon glyphicon-ok"></i>
                                                    @endif--}}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    <a class="qc-link-green"
                                                       href="{!! route('qc.work.work_allocation.order.view', $orderId) !!}">
                                                        <i class="qc-font-size-14 glyphicon glyphicon-eye-open"></i>
                                                    </a>
                                                    &nbsp;&nbsp;
                                                    <a class="qc-link" title="In đơn hàng"
                                                       href="{!! route('qc.work.work_allocation.order.print.get', $orderId) !!}">
                                                        <i class="qc-font-size-14 fa fa-print"></i>
                                                    </a>
                                                    @if($order->checkConfirmStatus())
                                                        <span>&nbsp;&nbsp;</span>
                                                        <a class="qc-link-green" title="In nghiệm thu"
                                                           href="{!! route('qc.work.work_allocation.order.print_confirm.get', $orderId) !!}">
                                                            <i class="qc-font-size-16 glyphicon glyphicon-list-alt"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            {!! $hFunction->convertDateDMYFromDatetime($order->receiveDate()) !!}
                                            <br/>
                                            <b>{!! $hFunction->convertDateDMYFromDatetime($order->deliveryDate()) !!}</b>
                                        </td>
                                        <td class="text-center">
                                            {{--đã xác nhận hoàn thành thi công--}}
                                            @if($hFunction->checkCount($dataOrderAllocationFinish))
                                                {!! $hFunction->convertDateDMYFromDatetime($dataOrderAllocationFinish->confirmDate()) !!}
                                            @endif
                                            {{--Đơn hàng bị trễ--}}
                                            @if($order->checkLate($orderId))
                                                <br/>
                                                <b style="background-color: deeppink; color: white; padding: 3px 10px;">Trễ</b>
                                            @endif
                                        </td>
                                        <td class="text-left">
                                            @if(!$cancelStatus)
                                                @if($hFunction->checkCount($orderWorkAllocationAllInfo))
                                                    @foreach($orderWorkAllocationAllInfo as $workAllocation)
                                                        @if($workAllocation->checkActivity())
                                                            <span style="color: blue;">{!! $workAllocation->receiveStaff->lastName() !!}</span>
                                                            ,&nbsp;
                                                        @endif
                                                    @endforeach
                                                @else
                                                    @if(!$finishStatus)
                                                        <span style="background-color: red; color: yellow; padding: 3px;">Chưa phân việc</span>
                                                    @else
                                                        <em style="color: grey;">Không phân có phân việc</em>
                                                    @endif
                                                @endif
                                            @endif
                                        </td>
                                        <td class="qc-color-red">
                                            @if($hFunction->checkCount($orderWorkAllocationAllInfo))
                                                <?php
                                                $dataWorkAllocationReportImage = $order->workAllocationReportImage($orderId, 1);
                                                ?>
                                                @if($hFunction->checkCount($dataWorkAllocationReportImage))
                                                    @foreach($dataWorkAllocationReportImage as $workAllocationReportImage)
                                                        <a class="qc_work_allocation_report_image_view qc-link"
                                                           title="Click xem chi tiết "
                                                           href="{!! route('qc.work.work_allocation.order.construction.get',$orderId) !!}">
                                                            <img style="float: left; width: 70px; height: 70px; border: 1px solid #d7d7d7;"
                                                                 src="{!! $workAllocationReportImage->pathSmallImage($workAllocationReportImage->name()) !!}">
                                                        </a>
                                                    @endforeach
                                                @else
                                                    <em class="qc-color-grey">Không có ảnh BC</em>
                                                @endif
                                            @else
                                                <em class="qc-color-grey">Không có ảnh BC</em>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            <b style="color: blue;">
                                                {!! $hFunction->currencyFormat($totalMoneyBonusAndMinus) !!}
                                            </b>
                                        </td>
                                        <td class="text-right">
                                            <b style="color: red;">
                                                {!! $hFunction->currencyFormat($totalMoneyBonusAndMinus) !!}
                                            </b>
                                        </td>
                                        <td>
                                            {!! $order->customer->name() !!}
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="text-center" colspan="11">
                                        {!! $hFunction->page($dataOrder) !!}
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td class="text-center" colspan="11">
                                        <em class="qc-color-red">Không tìm thấy thông tin phù hợp</em>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
