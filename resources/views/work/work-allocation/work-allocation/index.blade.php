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
$dataWork = $dataStaff->workInfoActivityOfStaff();
$workId = $dataWork->workId();
?>
@extends('work.work-allocation.index')
@section('qc_work_allocation_body')
    <div class="row">
        <div class="qc_work_allocation_wrap qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            @include('work.work-allocation.menu',compact('modelStaff'))

            <div class="qc_work_allocation_activity_contain qc-padding-top-20 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12"
                 data-href-report="{!! route('qc.work.work_allocation.activity.report.post') !!}">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-bordered qc-margin-bot-none">
                            <tr style="background-color: whitesmoke;">
                                <th style="width: 20px;"></th>
                                <th></th>
                                <th>Thi công sản phẩm</th>
                                <th class="text-center">Thời gian nhận nhận</th>
                                <th class="text-center">Thời gian giao</th>
                                <th class="text-center">Báo cáo</th>
                                <th class="text-center">Người được giao</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
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
                                    <tr class="qc_work_allocation_activity_object"
                                        @if($n_o%2 == 0) style="background-color: whitesmoke;"
                                        @endif data-work-allocation="{!! $allocationId !!}">
                                        <td class="text-center">
                                            <b>{!! $n_o !!}</b>
                                        </td>
                                        <td>
                                            <a class="qc-link-green-bold">Xem chi tiết</a>
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
                                            <a class="qc_work_allocation_activity_report_act qc-link-green-bold">
                                                Báo cáo công việc
                                            </a>
                                            <br/>
                                            <a class="qc_work_allocation_activity_report_act qc-link-bold">
                                                Xem Báo cáo
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            {!! $workAllocation->allocationStaff->lastName() !!}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="6">
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
