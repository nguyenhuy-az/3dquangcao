<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();

$dataWorkAllocationReport = $dataWorkAllocation->workAllocationReportInfo();
?>
@extends('components.container.container-10')
@section('qc_container_content')
    <div class="qc-padding-top-20 qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
            <h3>{!! $dataWorkAllocation->product->productType->name() !!}</h3>
        </div>
        <div class="row">
            {{-- thông tin đơn hàng --}}
            <div class="qc-container-table qc-container-table-border-none qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-5">
                <div class="table-responsive">
                    <table class="table table-hover qc-margin-bot-none">
                        <tr>
                            <td>
                                <em class=" qc-color-grey">Người nhận</em>
                            </td>
                            <td class="text-right">
                                <b>{!! $dataWorkAllocation->receiveStaff->fullName() !!}</b>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <em class=" qc-color-grey">Người giao:</em>
                            </td>
                            <td class="text-right">
                                <b>{!! $dataWorkAllocation->allocationStaff->fullName() !!}</b>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <em class="qc-color-grey">Ngày nhận:</em>
                            </td>
                            <td class="text-right">
                                <b>{!! date('d/m/Y H:i', strtotime($dataWorkAllocation->allocationDate())) !!}</b>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <em class="qc-color-grey">Ngày giao:</em>
                            </td>
                            <td class="text-right">
                                <b>{!! date('d/m/Y H:i', strtotime($dataWorkAllocation->receiveDeadline())) !!}</b>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <em class="qc-color-grey">Vai trò thi công:</em>
                            </td>
                            <td class="text-right">
                                @if($dataWorkAllocation->checkRoleMain())
                                    <b>Chính</b>
                                @else
                                    <b>Phụ</b>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <em class="qc-color-grey">Ghi chú:</em>
                            </td>
                            <td class="text-right">
                                <b>{!! $dataWorkAllocation->noted() !!}</b>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- thong tin giao --}}
        @if(count($dataWorkAllocationReport)> 0)
            @foreach($dataWorkAllocationReport as $workAllocationReport)
                <?php
                $dataWorkAllocationReportImage = $workAllocationReport->workAllocationReportImageInfo();
                #bao cao khi bao gio ra
                $dataTimekeepingProvisionalImage = $workAllocationReport->timekeepingProvisionalImageInfo();
                ?>
                @if(count($dataWorkAllocationReportImage)> 0)
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <em class="qc-color-red">Báo cáo trực tiếp</em>
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
                            @foreach($dataWorkAllocationReportImage as $workAllocationReportImage)
                                <div style="position: relative; float: left; margin: 5px; width: 70px; height: 70px; border: 1px solid #d7d7d7;">
                                    <a class="qc_work_allocation_report_image_view qc-link"
                                       data-href="{!! route('qc.work.work_allocation.order.allocation.report_image.get', $workAllocationReportImage->imageId()) !!}">
                                        <img style="max-width: 100%; max-height: 100%;"
                                             src="{!! $workAllocationReportImage->pathSmallImage($workAllocationReportImage->name()) !!}">
                                    </a>
                                </div>
                            @endforeach
                        </div>

                    </div>
                @endif

                @if(count($dataTimekeepingProvisionalImage)> 0)
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <em class="qc-color-red">Báo qua chấm công</em>
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
                                       data-href="{!! route('qc.work.work_allocation.order.allocation.report_image.get', $timekeepingProvisionalImage->imageId()) !!}">
                                        <img style="max-width: 100%; max-height: 100%;"
                                             src="{!! $timekeepingProvisionalImage->pathSmallImage($timekeepingProvisionalImage->name()) !!}">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        @else
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <span class="qc-color-red">Chưa có Báo cáo</span>
            </div>
        @endif
        <div class="row">
            <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <a class="qc_container_close btn btn-primary">
                    Đóng
                </a>
            </div>
        </div>
    </div>

@endsection
