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
@extends('work.index')
@section('qc_work_body')
    <div class="row">
        <div class="qc_work_allocation_wrap qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            @include('work.work-allocation.work-allocation-menu')

            <div class="qc_work_allocation_contain qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12"
                 data-href-confirm="#"
                 data-href-image="#"
                 data-href-report="{!! route('qc.work.work_allocation.activity.report.post') !!}">
                @if(count($dataWorkAllocation) > 0)
                    @foreach($dataWorkAllocation as $workAllocation)
                        <?php
                        $allocationId = $workAllocation->allocationId();
                        $allocationDate = $workAllocation->allocationDate();
                        $receiveDeadline = $workAllocation->receiveDeadline();
                        $allocationNote = $workAllocation->noted();
                        $dataWorkAllocationReport = $workAllocation->workAllocationReportInfo();
                        $dataProduct = $workAllocation->product;
                        $productDesignImage = $dataProduct->designImage();
                        ?>
                        <div class="row qc_work_allocation_object"
                             data-work-allocation="{!! $allocationId !!}"
                             style="background-color: whitesmoke; margin-top: 20px; border: 1px solid #d7d7d7;">
                            <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                        <b class="qc-color-red">{!! $workAllocation->product->productType->name() !!}</b>
                                        <em style="color:brown;">
                                            - {!! $workAllocation->product->order->orderCode().'-'.$workAllocation->product->order->name() !!}</em>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                        @if(!empty($productDesignImage))
                                            <div style="position: relative; margin-right: 10px; width: 70px; height: 70px; padding: 5px 5px; ">
                                                <a class="qc_work_allocation_product_design_image_view qc-link"
                                                   data-href="{!! route('qc.work.work_allocation.product_design_image.get', $dataProduct->productId()) !!}">
                                                    <img style="max-width: 100%; max-height: 100%;"
                                                         src="{!! $dataProduct->pathSmallDesignImage($productDesignImage) !!}">
                                                </a>
                                            </div>
                                        @else
                                            <span>Thiết kế cập nhật</span>
                                        @endif
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                        <em>Ghời gian:</em>
                                        <span class="qc-font-bold" style="color: brown;">
                                            {!! date('d-m-Y ', strtotime($allocationDate)) !!}
                                        </span>
                                        <span class="qc-font-bold">
                                            {!! date('H:i', strtotime($allocationDate)) !!}
                                        </span>
                                        <span class="glyphicon glyphicon-arrow-right"></span>
                                        <span class="qc-font-bold" style="color: brown;">
                                            {!! date('d-m-Y ', strtotime($receiveDeadline)) !!}
                                        </span>
                                        <span class="qc-font-bold">
                                            {!! date('H:i', strtotime($receiveDeadline)) !!}
                                        </span>
                                    </div>

                                    <div class="text-right col-xs-12 col-sm-12 col-md-2 col-lg-2">
                                        <span>Vị trí:</span>
                                        @if($workAllocation->checkRoleMain())
                                            <b>Chính</b>
                                        @else
                                            <b>Phụ</b>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if(!empty($allocationNote))
                                <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <em class="qc-color-grey">{!! $allocationNote !!}</em>
                                </div>
                            @endif
                            <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <em class="qc-color-grey">Thi công:</em>
                                <span>{!! $dataProduct->order->constructionAddress() !!}
                                    - ĐT: {!! $dataProduct->order->constructionPhone() !!}
                                    - tên: {!! $dataProduct->order->constructionContact() !!}</span>
                            </div>
                            <div class="qc-padding-top-5 qc-padding-bot-5 text-right col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                @if($workAllocation->checkConfirm())
                                    <a class="qc_work_allocation_report_action qc-link-green-bold">Báo cáo</a>
                                @else
                                    <a class="qc-link-bold">Xác nhận</a>
                                @endif
                            </div>
                            @if(count($dataWorkAllocationReport)> 0)
                                @foreach($dataWorkAllocationReport as $workAllocationReport)
                                    <?php
                                    $dataWorkAllocationReportImage = $workAllocationReport->workAllocationReportImageInfo();
                                    #bao cao khi bao gio ra
                                    $dataTimekeepingProvisionalImage = $workAllocationReport->timekeepingProvisionalImageInfo();
                                    ?>
                                    @if(count($dataWorkAllocationReportImage)> 0)
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <em class="qc-color-grey">Báo cáo trực tiếp</em>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                                                 style="border-top: 1px dotted brown; "></div>
                                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="margin-top: 5px;">
                                                <a class="qc_delete_report_action qc-link-red" title="Xóa báo cáo"
                                                   data-href="{!! route('qc.work.work_allocation.activity.report.cancel.get', $workAllocationReport->reportId()) !!}">
                                                    <i class="glyphicon glyphicon-trash"></i>
                                                </a>&nbsp;
                                                <b>{!! date('d/m/Y H:j', strtotime($workAllocationReport->reportDate())) !!}</b>
                                                <br/>
                                                <em class="qc-color-grey">- {!! $workAllocationReport->content() !!}</em>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                @foreach($dataWorkAllocationReportImage as $workAllocationReportImage)
                                                    <div style="position: relative; float: left; margin: 5px; width: 70px; height: 70px; border: 1px solid #d7d7d7;">
                                                        <a class="qc_work_allocation_report_image_view qc-link"
                                                           data-href="{!! route('qc.work.work_allocation.report_image.get', $workAllocationReportImage->imageId()) !!}">
                                                            <img style="max-width: 100%; max-height: 100%;"
                                                                 src="{!! $workAllocationReportImage->pathSmallImage($workAllocationReportImage->name()) !!}">
                                                        </a>
                                                        <a class="qc_delete_image_action qc-link"
                                                           title="Xóa ảnh báo cáo"
                                                           data-href="{!! route('qc.work.work_allocation.activity.report_image.delete', $workAllocationReportImage->imageId()) !!}">
                                                            <i style="position: absolute; font-weight: bold; padding: 0 3px; color: red; top: 3px; right: 3px; border: 1px solid #d7d7d7;">x</i>
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>

                                        </div>
                                    @endif

                                    @if(count($dataTimekeepingProvisionalImage)> 0)
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <em class="qc-color-grey">Báo qua chấm công</em>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                                                 style="border-top: 1px dotted brown; "></div>
                                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="margin-top: 5px;">
                                                <b>{!! date('d/m/Y H:j', strtotime($workAllocationReport->reportDate())) !!}</b>
                                                <br/>
                                                <em class="qc-color-grey">- {!! $workAllocationReport->content() !!}</em>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                @foreach($dataTimekeepingProvisionalImage as $timekeepingProvisionalImage)
                                                    <div style="position: relative; float: left; margin: 5px; width: 70px; height: 70px; border: 1px solid #d7d7d7;">
                                                        <a class="qc_work_allocation_report_image_view qc-link"
                                                           data-href="{!! route('qc.work.work_allocation.timekeeping_provisional_image.get', $timekeepingProvisionalImage->imageId()) !!}">
                                                            <img style="max-width: 100%; max-height: 100%;"
                                                                 src="{!! $timekeepingProvisionalImage->pathSmallImage($timekeepingProvisionalImage->name()) !!}">
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    @endforeach
                @endif

            </div>
            <div class="row">
                <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <a href="{!! route('qc.work.home') !!}">
                        <button type="button" class="btn btn-primary">
                            Đóng
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
