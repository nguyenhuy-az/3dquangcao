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

$dataOrdersAllocation = null;
$dataOrdersAllocation = $dataStaffLogin->orderAllocationInfoOfReceiveStaff($loginStaffId, $dateFilter);


?>
@extends('work.work-allocation.index')
@section('titlePage')
    Đơn hàng được bàn giao
@endsection
@section('qc_work_allocation_body')
    <div class="row">
        <div class="qc_work_allocation_construction_wrap qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            @include('work.work-allocation.menu')

            {{-- chi tiêt --}}
            <div class="qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="qc_work_allocation_construction_list_content qc-container-table row">
                    <div class="table-responsive">
                        <table class="table table-bordered" style="border: none;">
                            <tr>
                                <th colspan="8" style="border-top: none;" >
                                    <em style="color: deeppink;">(Danh sách đơn hàng được bàn giao)</em>
                                </th>
                            </tr>
                            <tr style="background-color: whitesmoke;">
                                <th class="text-center" style="width: 20px;">STT</th>
                                <th>Mã ĐH</th>
                                <th>Phụ trách Đơn hàng</th>
                                <th>Khách hàng</th>
                                <th class="text-center">Thời gian được giao</th>
                                <th class="text-center">Thời hạn bàn giao</th>
                                <th>QL sản phẩm</th>
                                <th class="text-right">Trạng thái</th>
                            </tr>
                            <tr>
                                <th class="text-center" style="width: 20px;"></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th class="text-center" style="padding: 0;">
                                    <select class="cbWorkAllocationConstructionMonthFilter" style="height: 25px;"
                                            data-href="{!! $hrefIndex !!}">
                                        <option value="100" @if((int)$monthFilter == 100) selected="selected" @endif >
                                            Tất cả tháng
                                        </option>
                                        @for($i =1;$i<= 12; $i++)
                                            <option value="{!! $i !!}"
                                                    @if((int)$monthFilter == $i) selected="selected" @endif>
                                                Tháng {!! $i !!}
                                            </option>
                                        @endfor
                                    </select>
                                    <span>/</span>
                                    <select class="cbWorkAllocationConstructionYearFilter" style="height: 25px;"
                                            data-href="{!! $hrefIndex !!}">
                                        <option value="100" @if((int)$yearFilter == 100) selected="selected" @endif >
                                            Tất cả năm
                                        </option>
                                        @for($i =2017;$i<= 2050; $i++)
                                            <option value="{!! $i !!}"
                                                    @if($yearFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                                        @endfor
                                    </select>
                                </th>
                                <th></th>
                                <th></th>
                                <th class="text-right"></th>
                            </tr>
                            @if($hFunction->checkCount($dataOrdersAllocation))
                                <?php $n_o = 0; ?>
                                @foreach($dataOrdersAllocation as $ordersAllocation)
                                    <?php
                                    $allocationId = $ordersAllocation->allocationId();
                                    $orders = $ordersAllocation->orders;
                                    $orderId = $orders->orderId();
                                    $customerId = $orders->customerId();
                                    ?>
                                    <tr class="qc_work_list_content_object @if($n_o%2) info @endif" data-object="{!! $orderId !!}">
                                        <td class="text-center">
                                            {!! $n_o+=1  !!}
                                        </td>
                                        <td>
                                            {!! $orders->orderCode() !!}
                                        </td>
                                        <td>
                                            {!! $orders->name() !!}
                                        </td>
                                        <td>
                                            {!! $orders->customer->name() !!}
                                        </td>
                                        <td class="text-center">
                                            {!! date('d-m-Y', strtotime($orders->receiveDate())) !!}
                                        </td>
                                        <td class="text-center">
                                            {!! date('d-m-Y', strtotime($orders->deliveryDate())) !!}
                                        </td>
                                        <td>
                                            <a class="qc-link-green"
                                               href="{!! route('qc.work.work_allocation.construction.product.get',$allocationId) !!}">
                                                Quản lý Sản phẩm
                                            </a>
                                            @if($ordersAllocation->checkViewedNewOrderAllocation($allocationId,$loginStaffId))
                                                <em style="color: grey;"> - Đã xem</em>
                                            @else
                                                <em style="color: red;"> - Chưa xem</em>
                                            @endif

                                        </td>
                                        <td class="text-right">
                                            @if($ordersAllocation->checkActivity())
                                                @if($ordersAllocation->checkFinish())
                                                    @if($ordersAllocation->checkConfirm())
                                                        @if($ordersAllocation->checkConfirmFinish())
                                                            <em>Xong</em>
                                                        @else
                                                            <em>Không hoàn thành</em>
                                                        @endif
                                                    @else
                                                        <em>Chờ duyệt</em>
                                                    @endif
                                                @else
                                                    <a class="qc_confirm_act qc-link-green"
                                                       data-href="{!! route('qc.work.work_allocation.construction.confirm.get', $allocationId) !!}">
                                                        Báo hoàn thành đơn hàng
                                                    </a>
                                                @endif
                                            @else
                                                @if($ordersAllocation->checkFinish())
                                                    <em>Xong</em> |
                                                    @if($ordersAllocation->checkConfirmFinish())
                                                        <em class="qc-color-grey">Hoàn thành</em>
                                                    @else
                                                        <em class="qc-color-grey">Không hoàn thành</em>
                                                    @endif
                                                @else
                                                    <em style=" color: red;">
                                                        Đã hủy
                                                    </em>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
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
