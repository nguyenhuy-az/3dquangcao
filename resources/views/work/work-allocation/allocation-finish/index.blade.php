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
        <div class="qc_work_allocation_finish_wrap qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            @include('work.work-allocation.menu')

            <div class="qc_work_allocation_finish_contain qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-bordered qc-margin-bot-none">
                            <tr style="background-color: black; color: yellow;">
                                <th style="width: 20px;"></th>
                                <th>Sản phẩm</th>
                                <th class="text-center">Thiết Kế</th>
                                <th class="text-center">TG Nhận/Giao</th>
                                <th class="text-center">Vai trò</th>
                                <th class="text-center">Hoàn thành</th>
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
                                    # noi dung bao cao
                                    $dataWorkAllocationReport = $workAllocation->workAllocationReportInfo();
                                    # ket thuc cong viec
                                    $dataWorkAllocationFinish = $workAllocation->workAllocationFinishInfo();
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
                                                   data-href="{!! route('qc.work.work_allocation.finish.design_image.view', $productDesignImage->designId()) !!}">
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
                                            @if($hFunction->checkCount($dataWorkAllocationFinish))
                                                <em>Đã hoàn thành</em>
                                                <br/>
                                                @if($dataWorkAllocationFinish->checkFinishSoon())
                                                    <span class="qc-color-grey">Trước hẹn</span>
                                                @elseif($dataWorkAllocationFinish->checkFinishLate())
                                                    <span class="qc-color-grey">Trễ hẹn</span>
                                                @else
                                                    <span class="qc-color-grey">Đúng hẹn</span>
                                                @endif
                                            @else
                                                <em class="qc-color-red">Không hoàn thành</em>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a class="qc-link-green-bold"
                                               onclick="qc_main.toggle({!! "'#qc_work_allocation_report_wrap_$allocationId'" !!});">
                                                Xem báo cáo
                                            </a>
                                        </td>
                                    </tr>
                                    @if($hFunction->checkCount($dataWorkAllocationReport))
                                        <tr id="qc_work_allocation_report_wrap_{!! $allocationId !!}"
                                            class="qc-display-none"
                                            @if($n_o%2 == 0) style="background-color: whitesmoke;" @endif>
                                            <td style="border-top: none;"></td>
                                            <td colspan="6">

                                                <div class="table-responsive">
                                                    <table class="table table-condensed"
                                                           @if($n_o%2 == 0) style="background-color: whitesmoke;" @endif>
                                                        <tr>
                                                            <th style="border-top: none; " colspan="3">
                                                                <label style="color: brown;">Thông tin báo cáo:</label>
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
                                                                                   data-href="{!! route('qc.work.work_allocation.finish.report_image_direct.view', $workAllocationReportImage->imageId()) !!}">
                                                                                    <img style="max-width: 100%; max-height: 100%;"
                                                                                         src="{!! $workAllocationReportImage->pathSmallImage($workAllocationReportImage->name()) !!}">
                                                                                </a>
                                                                            </div>
                                                                        @endforeach
                                                                    </td>
                                                                    <td>
                                                                        <em class="qc-color-grey">- {!! $workAllocationReport->content() !!}</em>
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
                                                                                <a class="qc_work_allocation_report_image_view qc-link"
                                                                                   data-href="{!! route('qc.work.work_allocation.finish.report_image.view', $timekeepingProvisionalImage->imageId()) !!}">
                                                                                    <img style="max-width: 100%; max-height: 100%;"
                                                                                         src="{!! $timekeepingProvisionalImage->pathSmallImage($timekeepingProvisionalImage->name()) !!}">
                                                                                </a>
                                                                            </div>
                                                                        @endforeach
                                                                    </td>
                                                                    <td>
                                                                        <em class="qc-color-grey">- {!! $workAllocationReport->content() !!}</em>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    </table>
                                                </div>

                                                <div class="text-right col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                                    <a class="qc-link-red"
                                                       onclick="qc_main.hide({!! "'#qc_work_allocation_report_wrap_$allocationId'" !!});">
                                                        Ẩn báo có
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif

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
                    <a href="{!! route('qc.work.home') !!}">
                        <button type="button" class="qc_ad3d_container_close btn btn-primary">
                            Đóng
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
