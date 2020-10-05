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
$hrefIndex = route('qc.work.work_allocation.order_allocation.index');
$dataStaffLogin = $modelStaff->loginStaffInfo();
$loginStaffId = $dataStaffLogin->staffId();
?>
@extends('work.work-allocation.order-allocation.index')
@section('titlePage')
    Đơn hàng được bàn giao
@endsection
@section('qc_work_allocation_body')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="padding-bottom: 5px;">
            <a class="btn btn-sm btn-primary" onclick="qc_main.page_back();">
                Về trang trước
            </a>
        </div>
        <div class="qc_work_allocation_construction_wrap qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            @include('work.work-allocation.menu')

            {{-- chi tiêt --}}
            <div class="qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="qc_work_allocation_construction_list_content row">
                    <div class="table-responsive">
                        <table class="table table-bordered" style="border: none;">
                            <tr>
                                <th colspan="8" style="border-top: none;">
                                    <em style="color: deeppink;">(Danh sách đơn hàng được bàn giao)</em>
                                </th>
                            </tr>
                            <tr style="background-color: black;color: yellow;">
                                <th class="text-center" style="width: 20px;">STT</th>
                                <th>NGÀY NHẬN</th>
                                <th>ĐƠN HÀNG</th>
                                <th class="text-center">HẠN GIAO</th>
                                <th class="text-center">NGÀY HOÀN THÀNH</th>
                                <th class="text-right">
                                    THƯỞNG <br/>
                                    <em style="color: white;">(Đúng hạn)</em>
                                </th>
                                <th class="text-right">
                                    PHẠT <br/>
                                    <em style="color: white;">(Trễ hạn)</em>
                                </th>
                                <th class="text-center">BÁO CÁO</th>
                            </tr>
                            <tr>
                                <td class="text-center" style="width: 20px;"></td>
                                <td style="padding: 0px;">
                                    <select class="cbWorkAllocationConstructionMonthFilter col-sx-4 col-sm-4 col-md-4 col-lg-4"
                                            style="height: 34px; padding: 0;"
                                            data-href="{!! $hrefIndex !!}">
                                        <option value="100" @if((int)$monthFilter == 100) selected="selected" @endif >
                                            Tất cả tháng
                                        </option>
                                        @for($m =1;$m<= 12; $m++)
                                            <option value="{!! $m !!}"
                                                    @if((int)$monthFilter == $m) selected="selected" @endif>
                                                Tháng {!! $m !!}
                                            </option>
                                        @endfor
                                    </select>
                                    <select class="cbWorkAllocationConstructionYearFilter col-sx-8 col-sm-8 col-md-8 col-lg-8"
                                            style="height: 34px; padding: 0;"
                                            data-href="{!! $hrefIndex !!}">
                                        {{--<option value="100" @if((int)$yearFilter == 100) selected="selected" @endif >
                                            Tất cả năm
                                        </option>--}}
                                        @for($y =2017;$y<= 2050; $y++)
                                            <option value="{!! $y !!}"
                                                    @if($yearFilter == $y) selected="selected" @endif>
                                                {!! $y !!}
                                            </option>
                                        @endfor
                                    </select>
                                </td>
                                <td></td>
                                <td></td>
                                <td style="padding: 0;">
                                    <select class="cbWorkAllocationConstructionFinishStatus text-center form-control"
                                            data-href="{!! $hrefIndex !!}">
                                        <option value="100"
                                                @if($finishStatus == 100) selected="selected" @endif>
                                            Tất cả
                                        </option>
                                        <option value="0" @if($finishStatus == 0) selected="selected" @endif>
                                            Chưa Xong
                                        </option>
                                        <option value="1"
                                                @if($finishStatus == 1) selected="selected" @endif>
                                            Đã Xong
                                        </option>
                                    </select>
                                </td>
                                <td></td>
                                <td></td>
                                <td class="text-right"></td>
                            </tr>
                            @if($hFunction->checkCount($dataOrdersAllocation))
                                <?php $n_o = 0; ?>
                                @foreach($dataOrdersAllocation as $ordersAllocation)
                                    <?php
                                    $allocationId = $ordersAllocation->allocationId();
                                    $orders = $ordersAllocation->orders;
                                    $orderId = $orders->orderId();
                                    $customerId = $orders->customerId();
                                    $allocationFinishDate = $ordersAllocation->finishDate();
                                    ?>
                                    <tr class="qc_work_list_content_object @if($n_o%2) info @endif"
                                        data-object="{!! $orderId !!}">
                                        <td class="text-center">
                                            {!! $n_o+=1  !!}
                                        </td>
                                        <td>
                                            <b style="color: blue;">
                                                {!! date('d-m-Y', strtotime($ordersAllocation->allocationDate())) !!}
                                            </b>
                                            <span class="qc-font-bold" style="color: brown;">
                                                {!! date('H:i', strtotime($ordersAllocation->allocationDate())) !!}
                                            </span>
                                            <br/>
                                            <a class="qc-link-green-bold"
                                               href="{!! route('qc.work.work_allocation.order_allocation.product.get',$allocationId) !!}">
                                                <i class="glyphicon glyphicon-arrow-right"></i>
                                                CHI TIẾT
                                            </a>
                                            @if($ordersAllocation->checkViewedNewOrderAllocation($allocationId,$loginStaffId))
                                                <em style="color: grey;">- Đã xem</em>
                                            @else
                                                <em style="color: red;">- Chưa xem</em>
                                            @endif
                                        </td>
                                        <td>
                                            <b>{!! $orders->name() !!}</b>
                                            <br/>
                                            <em style="color: grey;">
                                                Kh.H: {!! $orders->customer->name() !!}
                                            </em>
                                        </td>
                                        <td class="text-center">
                                            <span>
                                                {!! date('d-m-Y', strtotime($ordersAllocation->receiveDeadline())) !!}
                                            </span>
                                            <span class="qc-font-bold" style="color: brown;">
                                                {!! date('H:i', strtotime($ordersAllocation->receiveDeadline())) !!}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($ordersAllocation->checkCancelAllocation())
                                                <em style="color: grey;">Đã hủy</em>
                                            @else
                                                @if($ordersAllocation->checkFinish())
                                                    {!! date('d-m-Y', strtotime($allocationFinishDate)) !!}
                                                    <span class="qc-font-bold" style="color: brown;">
                                                        {!! date('H:i', strtotime($allocationFinishDate)) !!}
                                                    </span>
                                                    {{--<i class="glyphicon glyphicon-ok" style="color: blue;"></i>--}}
                                                @else
                                                    <span style="padding: 3px; background-color: blue; color: white;">Đang thi công</span>
                                                @endif
                                                @if($ordersAllocation->checkLate($allocationId))
                                                    <br/>
                                                    <span style="color: white; padding: 3px; background-color: red;">TRỄ</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            {!! $hFunction->currencyFormat($orders->getBonusAndMinusMoneyOfStaffRank()) !!}
                                        </td>
                                        <td class="text-right">
                                            {!! $hFunction->currencyFormat($orders->getBonusAndMinusMoneyOfStaffRank()) !!}
                                        </td>
                                        <td class="text-center">
                                            @if($ordersAllocation->checkActivity())
                                                @if($ordersAllocation->checkFinish())
                                                    <em>---</em>
                                                @else
                                                    <a class="qc_report_finish_get qc-link-green"
                                                       data-href="{!! route('qc.work.work_allocation.order_allocation.report_finish.get', $allocationId) !!}">
                                                        BÁO HOÀN THÀNH
                                                    </a>
                                                @endif
                                            @else
                                                @if($ordersAllocation->checkCancelAllocation())
                                                    <em style="color: grey;">---</em>
                                                @else
                                                    @if($ordersAllocation->checkConfirm($allocationId))
                                                        @if($ordersAllocation->checkConfirmFinish($allocationId))
                                                            <em class="qc-color-grey">Đã hoàn thành</em>
                                                        @else
                                                            <em class="qc-color-grey">Không hoàn thành</em>
                                                        @endif
                                                    @else
                                                        <em style="background-color: green; color: white; padding: 3px;">
                                                            Chờ duyệt</em>
                                                    @endif

                                                @endif
                                                @if($ordersAllocation->checkPaymentStatus())
                                                    <br/>
                                                    <i class="glyphicon glyphicon-usd" style="color: blue;"></i>
                                                    <b style="color: red;">Có thu tiền hộ</b>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="text-center" colspan="8">
                                        {!! $hFunction->page($dataOrdersAllocation) !!}
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td class="text-center" colspan="8">
                                        <em class="qc-color-red">Không có công trình</em>
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