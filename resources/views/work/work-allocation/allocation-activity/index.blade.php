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
                            <tr style="background-color: black; color: yellow;">
                                <th style="width: 20px;"></th>
                                <th>Sản phẩm</th>
                                <th class="text-center">Thiết Kế</th>
                                <th class="text-center">TG Nhận/Giao</th>
                                <th class="text-center">Vai trò</th>
                                <th class="text-center"></th>
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
                                            <b class="qc-color-red">{!! $workAllocation->product->productType->name() !!}</b>
                                            <em style="color:brown;">
                                                - {!! $workAllocation->product->order->orderCode().' / '.$workAllocation->product->order->name() !!}</em>

                                            <p style="margin-bottom: 0;">
                                                <span>- KT: dài {!! $dataProduct->width() !!}
                                                    mm, Rộng {!! $dataProduct->height() !!}
                                                    mm  SL: {!! $dataProduct->amount() !!} </span>
                                            </p>

                                            <p style="margin-bottom: 0;">
                                                <em class="qc-color-green">- Chi chú:</em>
                                                <span>{!! $dataProduct->description() !!}</span>
                                                @if(!$hFunction->checkEmpty($allocationNote))
                                                    <br/>
                                                    <span class="qc-color-grey">{!! $allocationNote !!}</span>
                                                @endif
                                            </p>

                                            <p style="margin-bottom: 0;">
                                                <span>- Thi Công Đ/c: {!! $dataProduct->order->constructionAddress() !!}
                                                    - ĐT: {!! $dataProduct->order->constructionPhone() !!}
                                                    - tên: {!! $dataProduct->order->constructionContact() !!}</span>
                                            </p>
                                        </td>
                                        <td class="text-center">
                                            @if(!$hFunction->checkEmpty($productDesignImage))
                                                <a class="qc_design_image_view qc-link"
                                                   data-href="{!! route('qc.work.work_allocation.activity.design_image.view', $productDesignImage->designId()) !!}">
                                                    <img style="width: 70px; height: 70px;"
                                                         src="{!! $productDesignImage->pathSmallImage($productDesignImage->image()) !!}">
                                                </a>
                                            @else
                                                <span>Thiết kế cập nhật sau</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <p style="margin-bottom: 0;">
                                                <span class="qc-font-bold" style="color: grey;">
                                                {!! date('d-m-Y ', strtotime($allocationDate)) !!}
                                                </span>
                                                <span class="qc-font-bold">
                                                    {!! date('H:i', strtotime($allocationDate)) !!}
                                                </span>
                                            </p>

                                            <p style="margin-bottom: 0;">
                                                <span class="qc-font-bold" style="color: brown;">
                                                {!! date('d-m-Y ', strtotime($receiveDeadline)) !!}
                                                </span>
                                                <span class="qc-font-bold">
                                                    {!! date('H:i', strtotime($receiveDeadline)) !!}
                                                </span>
                                            </p>
                                        </td>
                                        <td class="text-center qc-color-grey">
                                            @if($workAllocation->checkRoleMain())
                                                <b>Chính</b>
                                            @else
                                                <b>Phụ</b>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($workAllocation->checkConfirm())
                                                <a class="qc_work_allocation_activity_report_act qc-link-green-bold">Báo
                                                    cáo</a>
                                            @else
                                                <a class="qc-link-bold">Xác nhận</a>
                                            @endif
                                        </td>
                                    </tr>
                                    @if($hFunction->checkCount($dataWorkAllocationReport))
                                        <tr @if($n_o%2 == 0) style="background-color: whitesmoke;" @endif>
                                            <td style="border-top: none;"></td>
                                            <td colspan="5">
                                                <div class="table-responsive">
                                                    <table class="table table-condensed"
                                                           @if($n_o%2 == 0) style="background-color: whitesmoke;" @endif>
                                                        <tr>
                                                            <th style="border-top: none; " colspan="3">
                                                                <label style="color: brown;">BÁO CÁO THI CÔNG:</label>
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th>
                                                                Ngày
                                                            </th>
                                                            <th>
                                                                Ảnh báo cáo
                                                            </th>
                                                            <th>
                                                                Ghi chú
                                                            </th>
                                                            <th></th>
                                                        </tr>
                                                        @foreach($dataWorkAllocationReport as $workAllocationReport)
                                                            <?php
                                                            $dataWorkAllocationReportImage = $workAllocationReport->workAllocationReportImageInfo();
                                                            #bao cao khi bao gio ra
                                                            $dataTimekeepingProvisionalImage = $workAllocationReport->timekeepingProvisionalImageInfo();
                                                            ?>
                                                            @if($hFunction->checkCount($dataWorkAllocationReportImage))
                                                                <tr>
                                                                    <td>
                                                                        <span>{!! date('d/m/Y H:j', strtotime($workAllocationReport->reportDate())) !!}</span><br/>
                                                                        <em class="qc-color-grey">Báo cáo trực tiếp</em>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        @foreach($dataWorkAllocationReportImage as $workAllocationReportImage)
                                                                            <div style="position: relative; float: left; margin: 5px; width: 70px; height: 70px; border: 1px solid #d7d7d7;">
                                                                                <a class="qc_image_view qc-link"
                                                                                   data-href="{!! route('qc.work.work_allocation.activity.report_image_direct.view', $workAllocationReportImage->imageId()) !!}">
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
                                                                    </td>
                                                                    <td>
                                                                        <em class="qc-color-grey">- {!! $workAllocationReport->content() !!}</em>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <a class="qc_delete_report_action qc-link-red-bold"
                                                                           data-href="{!! route('qc.work.work_allocation.activity.report.cancel.get', $workAllocationReport->reportId()) !!}">
                                                                            <i class="glyphicon glyphicon-trash qc-font-size-14"
                                                                               title="Xóa báo cáo"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                            @if($hFunction->checkCount($dataTimekeepingProvisionalImage))
                                                                <tr>
                                                                    <td>
                                                                        <span>{!! date('d/m/Y H:j', strtotime($workAllocationReport->reportDate())) !!}</span><br/>
                                                                        <em class="qc-color-grey">Báo qua chấm công</em>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        @foreach($dataTimekeepingProvisionalImage as $timekeepingProvisionalImage)
                                                                            <div style="position: relative; float: left; margin: 5px; width: 70px; height: 70px; border: 1px solid #d7d7d7;">
                                                                                <a class="qc_image_view qc-link"
                                                                                   data-href="{!! route('qc.work.work_allocation.activity.report_image.view', $timekeepingProvisionalImage->imageId()) !!}">
                                                                                    <img style="max-width: 100%; max-height: 100%;"
                                                                                         src="{!! $timekeepingProvisionalImage->pathSmallImage($timekeepingProvisionalImage->name()) !!}">
                                                                                </a>
                                                                            </div>
                                                                        @endforeach
                                                                    </td>
                                                                    <td>
                                                                        <em class="qc-color-grey">- {!! $workAllocationReport->content() !!}</em>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <a class="qc_delete_report_action qc-link-red-bold"
                                                                           data-href="{!! route('qc.work.work_allocation.activity.report.cancel.get', $workAllocationReport->reportId()) !!}">
                                                                            <i class="glyphicon glyphicon-trash qc-font-size-14"
                                                                               title="Xóa báo cáo"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
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
