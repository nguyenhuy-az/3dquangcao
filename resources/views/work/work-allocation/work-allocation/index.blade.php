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
$hrefIndex = route('qc.work.work_allocation.get');
$dataWork = $dataStaff->workInfoActivityOfStaff();
$workId = $dataWork->workId();
?>
@extends('work.work-allocation.index')
@section('qc_work_allocation_body')
    <div class="row">
        <div class="qc_work_allocation_wrap qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            @include('work.work-allocation.menu',compact('modelStaff'))

            <div class="qc_work_allocation_contain qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12"
                 data-href-report="{!! route('qc.work.work_allocation.report.get') !!}">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-bordered qc-margin-bot-none" style="border: none;">
                            <tr>
                                <th colspan="7" style="border-top: none;">
                                    <em style="color: deeppink;">(Công việc được bàn giao)</em>
                                </th>
                            </tr>
                            <tr style="background-color: whitesmoke;">
                                <th style="width: 20px;"></th>
                                <th></th>
                                <th>Thi công sản phẩm</th>
                                <th class="text-center">Thời gian được giao</th>
                                <th class="text-center">Thời hạn bàn giao</th>
                                <th class="text-center">Báo cáo</th>
                                <th class="text-center">Người giao</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-center" style="padding:0;">
                                    <select class="cbWorkAllocationMonthFilter" style="height: 25px;"
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
                                    <select class="cbWorkAllocationYearFilter" style="height: 25px;"
                                            data-href="{!! $hrefIndex !!}">
                                        <option value="100" @if((int)$yearFilter == 100) selected="selected" @endif >
                                            Tất cả năm
                                        </option>
                                        @for($i =2017;$i<= 2050; $i++)
                                            <option value="{!! $i !!}"
                                                    @if($yearFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                                        @endfor
                                    </select>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @if($hFunction->checkCount($dataWorkAllocation))
                                <?php
                                $n_o = 0;
                                ?>
                                @foreach($dataWorkAllocation as $workAllocation)
                                    <?php
                                    $allocationId = $workAllocation->allocationId();
                                    $allocationDate = $workAllocation->allocationDate();
                                    $receiveDeadline = $workAllocation->receiveDeadline();
                                    $allocationNote = $workAllocation->noted();
                                    $dataWorkAllocationReport = $workAllocation->workAllocationReportInfo();
                                    $dataProduct = $workAllocation->product;
                                    ///$productDesignImage = $dataProduct->designImage();
                                    # thiet ke dang ap dung
                                    $productDesignImage = $dataProduct->productDesignInfoApplyActivity();
                                    $n_o = $n_o + 1;
                                    ?>
                                    <tr class="qc_work_allocation_object"
                                        @if($n_o%2 == 0) style="background-color: whitesmoke;"
                                        @endif data-work-allocation="{!! $allocationId !!}">
                                        <td class="text-center">
                                            <b>{!! $n_o !!}</b>
                                        </td>
                                        <td>
                                            <a class="qc-link-green-bold"
                                               href="{!! route('qc.work.work_allocation.detail.get', $allocationId) !!}">
                                                Xem chi tiết
                                            </a>
                                            @if($workAllocation->checkViewedNewWorkAllocation($allocationId,$workAllocation->receiveStaffId()))
                                                <em style="color: grey;"> - Đã xem</em>
                                            @else
                                                <em style="color: red;"> - Chưa xem</em>
                                            @endif
                                        </td>
                                        <td>
                                            <b class="qc-color-red">{!! $workAllocation->product->productType->name() !!}</b>
                                            <em style="color:grey;">- Đơn
                                                hàng: {!! $workAllocation->product->order->name() !!}</em>
                                        </td>
                                        <td class="text-center">
                                            <span class="qc-font-bold" style="color: grey;">
                                                {!! date('d-m-Y ', strtotime($allocationDate)) !!}
                                                </span>
                                                <span class="qc-font-bold">
                                                    {!! date('H:i', strtotime($allocationDate)) !!}
                                            </span>
                                        </td>
                                        <td class="text-center qc-color-grey">
                                            <span class="qc-font-bold" style="color: brown;">
                                                {!! date('d-m-Y ', strtotime($receiveDeadline)) !!}
                                                </span>
                                                <span class="qc-font-bold">
                                                    {!! date('H:i', strtotime($receiveDeadline)) !!}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a class="qc_work_allocation_report_act qc-link-green-bold">
                                                Báo cáo công việc
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            {!! $workAllocation->allocationStaff->lastName() !!}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="7">
                                        <span class="qc-color-red">Không có công việc</span>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <a class="btn btn-primary" href="{!! route('qc.work.home') !!}">
                        Về Trang chủ
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
