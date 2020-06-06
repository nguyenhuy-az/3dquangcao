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
$hrefIndex = route('qc.work.work_allocation.construction.get');
$dataStaffLogin = $modelStaff->loginStaffInfo();
$loginStaffId = $dataStaffLogin->staffId();
?>
@extends('work.work-allocation.index')
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
                <div class="qc_work_allocation_construction_list_content qc-container-table row">
                    <div class="table-responsive">
                        <table class="table table-bordered" style="border: none;">
                            <tr>
                                <th colspan="8" style="border-top: none;">
                                    <em style="color: deeppink;">(Danh sách đơn hàng được bàn giao)</em>
                                </th>
                            </tr>
                            <tr style="background-color: black;color: yellow;">
                                <th class="text-center" style="width: 20px;">STT</th>
                                <th class="text-center">Thi công</th>
                                <th>Phụ trách Đơn hàng</th>
                                <th>Khách hàng</th>
                                <th class="text-center">Thời gian được giao</th>
                                <th class="text-center">Thời hạn bàn giao</th>
                                <th class="text-center">Ngày báo hoàn thành</th>
                                <th class="text-center">Thưởng <br/> đúng hạn</th>
                                <th class="text-center">Phạt <br/> trễ hạn</th>
                                <th>QL sản phẩm</th>
                                <th class="text-center">Báo cáo</th>
                            </tr>
                            <tr>
                                <td class="text-center" style="width: 20px;"></td>
                                <td style="padding: 0px;">
                                    <select class="cbWorkAllocationConstructionFinishStatus form-control"
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
                                <td class="text-center" style="padding: 0;">
                                    <select class="cbWorkAllocationConstructionMonthFilter" style="height: 25px;"
                                            data-href="{!! $hrefIndex !!}">
                                        <option value="100" @if((int)$monthFilter == 100) selected="selected" @endif >
                                            Tất cả tháng
                                        </option>
                                        @for($m =1;$m<= 12; $m++)
                                            <option value="{!! $m !!}" @if((int)$monthFilter == $m) selected="selected" @endif>
                                                Tháng {!! $m !!}
                                            </option>
                                        @endfor
                                    </select>
                                    <span>/</span>
                                    <select class="cbWorkAllocationConstructionYearFilter" style="height: 25px;"
                                            data-href="{!! $hrefIndex !!}">
                                        <option value="100" @if((int)$yearFilter == 100) selected="selected" @endif >
                                            Tất cả năm
                                        </option>
                                        @for($y =2017;$y<= 2050; $y++)
                                            <option value="{!! $y !!}" @if($yearFilter == $y) selected="selected" @endif>
                                                {!! $y !!}
                                            </option>
                                        @endfor
                                    </select>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
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
                                        <td class="text-center">
                                            @if($ordersAllocation->checkCancelAllocation())
                                                <em style="color: grey;">Đã hủy</em>
                                            @else
                                                @if($ordersAllocation->checkFinish())
                                                    <em style="color: grey;">Đã kết thúc</em>
                                                    <i class="glyphicon glyphicon-ok" style="color: blue;"></i>
                                                @else
                                                    <span style="padding: 3px; background-color: blue; color: white;">Đang thi công</span>
                                                @endif
                                                @if($ordersAllocation->checkLate($allocationId))
                                                    <br/>
                                                    <span style="color: white; padding: 3px; background-color: red;">TRỄ</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            {!! $orders->name() !!}
                                        </td>
                                        <td>
                                            {!! $orders->customer->name() !!}
                                        </td>
                                        <td class="text-center">
                                            {!! date('d-m-Y', strtotime($ordersAllocation->allocationDate())) !!}
                                            <span class="qc-font-bold"
                                                  style="color: brown;">{!! date('H:i', strtotime($ordersAllocation->allocationDate())) !!}</span>
                                        </td>
                                        <td class="text-center">
                                            {!! date('d-m-Y', strtotime($ordersAllocation->receiveDeadline())) !!}
                                            <span class="qc-font-bold"
                                                  style="color: brown;">{!! date('H:i', strtotime($ordersAllocation->receiveDeadline())) !!}</span>
                                        </td>
                                        <td class="text-center">
                                            @if($hFunction->checkEmpty($allocationFinishDate))
                                                <em style="color: grey;"> ---</em>
                                            @else
                                                {!! date('d-m-Y', strtotime($allocationFinishDate)) !!}
                                                <span class="qc-font-bold"
                                                      style="color: brown;">{!! date('H:i', strtotime($allocationFinishDate)) !!}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {!! $hFunction->currencyFormat($orders->getBonusByOrderAllocation()) !!}
                                        </td>
                                        <td class="text-center">
                                            {!! $hFunction->currencyFormat($orders->getMinusMoneyOrderAllocationLate()) !!}
                                        </td>
                                        <td>
                                            <a class="qc-link-green"
                                               href="{!! route('qc.work.work_allocation.construction.product.get',$allocationId) !!}">
                                                Quản lý Sản phẩm
                                            </a>
                                            <br/>
                                            @if($ordersAllocation->checkViewedNewOrderAllocation($allocationId,$loginStaffId))
                                                <em style="color: grey;"> Đã xem</em>
                                            @else
                                                <em style="color: red;"> Chưa xem</em>
                                            @endif

                                        </td>
                                        <td class="text-center">
                                            @if($ordersAllocation->checkActivity())
                                                @if($ordersAllocation->checkFinish())
                                                    <em>---</em>
                                                @else
                                                    <a class="qc_report_finish_get qc-link-green"
                                                       data-href="{!! route('qc.work.work_allocation.construction.report_finish.get', $allocationId) !!}">
                                                        Báo hoàn thành đơn hàng
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
                                                        <em style="background-color: green; color: white; padding: 3px;">Chờ
                                                            duyệt</em>
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
                                    <td class="text-center" colspan="11">
                                        {!! $hFunction->page($dataOrdersAllocation) !!}
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td class="text-center" colspan="11">
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
