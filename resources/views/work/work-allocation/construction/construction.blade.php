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

$dataOrdersAllocation = null;
$dataOrdersAllocation = $dataStaffLogin->orderAllocationInfoOfReceiveStaff($loginStaffId, date('Y-m', strtotime("$loginYear-$loginMonth")));
//$totalMoneyOrder = $modelOrders->totalMoneyOfListOrder($dataOrders);
?>
@extends('work.index')
@section('titlePage')
    Công trình
@endsection
<style type="text/css">
    .qc_work_list_content_object {
        border-bottom: 1px solid #d7d7d7;
    }

    .qc_work_list_content_object:hover {
        background-color: whitesmoke;
    }
</style>
@section('qc_work_body')
    <div class="row">
        <div class="qc_work_allocation_construction_wrap qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            @include('work.work-allocation.work-allocation-menu')

            {{-- chi tiêt --}}
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="text-right qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <select class="qc_work_orders_allocation_login_month" style="height: 25px;"
                                data-href="{!! route('qc.work.work_allocation.construction.get') !!}">
                            @for($i = 1; $i <=12; $i++)
                                <option @if($loginMonth == $i) selected="selected" @endif>
                                    {!! $i !!}
                                </option>
                            @endfor
                        </select>
                        <span>/</span>
                        <select class="qc_work_orders_allocation_login_year" style="height: 25px;"
                                data-href="{!! route('qc.work.work_allocation.construction.get') !!}">
                            @for($i = 2017; $i <=2050; $i++)
                                <option @if($loginYear == $i) selected="selected" @endif>
                                    {!! $i !!}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="qc_work_allocation_construction_list_content qc-container-table row">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr style="background-color: whitesmoke;">
                                <th class="text-center">STT</th>
                                <th >Mã ĐH</th>
                                <th>Đơn hàng</th>
                                <th>Khách hàng</th>
                                <th class="text-center">Ngày nhận</th>
                                <th class="text-center">Ngày giao</th>
                                <th class="text-right">QL sản phẩm</th>
                                <th class="text-right">Trạng thái</th>
                            </tr>
                            @if(count($dataOrdersAllocation) > 0)
                                <?php $n_o = 0; ?>
                                @foreach($dataOrdersAllocation as $ordersAllocation)
                                    <?php
                                    $allocationId = $ordersAllocation->allocationId();
                                    $orders = $ordersAllocation->orders;
                                    $orderId = $orders->orderId();
                                    $customerId = $orders->customerId();
                                    ?>
                                    <tr class="qc_work_list_content_object" data-object="{!! $orderId !!}">
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
                                            {!! date('d/m/Y', strtotime($orders->receiveDate())) !!}
                                        </td>
                                        <td class="text-center">
                                            {!! date('d/m/Y', strtotime($orders->deliveryDate())) !!}
                                        </td>
                                        <td class="text-right">
                                            <a class="qc-link-green"
                                               href="{!! route('qc.work.work_allocation.construction.product.get',$allocationId) !!}">
                                                Sản phẩm
                                            </a>
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
                                                        Báo hoàn thành
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
