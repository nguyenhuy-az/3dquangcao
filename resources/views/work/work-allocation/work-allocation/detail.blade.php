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
$dataProduct = $dataWorkAllocation->product;
$dataOrder = $dataProduct->order;

$allocationId = $dataWorkAllocation->allocationId();
$allocationDate = $dataWorkAllocation->allocationDate();
$receiveDeadline = $dataWorkAllocation->receiveDeadline();
$allocationNote = $dataWorkAllocation->noted();
$dataWorkAllocationReport = $dataWorkAllocation->workAllocationReportInfo();
$dataProduct = $dataWorkAllocation->product;
///$productDesignImage = $dataProduct->designImage();
# thiet ke dang ap dung
$productDesignImage = $dataProduct->productDesignInfoApplyActivity();
# thong ket thuc phan viec
$dataWorkAllocationFinish = $dataWorkAllocation->workAllocationFinishInfo();
?>
@extends('work.work-allocation.work-allocation.index')
@section('titlePage')
    Chi tiết thi công
@endsection
@section('qc_work_allocation_body')
    <div class="row qc_work_allocation_work_allocation_detail_wrap">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <a class="btn btn-sm btn-primary" onclick="qc_main.page_back();">
                Về trang trước
            </a>
        </div>
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            @include('work.work-allocation.menu')

            <div class="row">
                <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-bordered qc-margin-bot-none" style="border: none;">
                            <tr>
                                <th colspan="6" style="border-top: none;">
                                    <label style="color: deeppink;">CÔNG VIỆC ĐƯỢC BÀN GIAO</label>
                                </th>
                            </tr>
                            <tr style="background-color: black; color: yellow;">
                                <th>Thi công sản phẩm</th>
                                <th class="text-center">Thiết Kế</th>
                                <th class="text-center">
                                    Thời gian được giao
                                    <br/>
                                    Thời hạn bàn giao
                                </th>
                                <th class="text-center">
                                    Ngày hoàn thành
                                </th>
                                {{--<th class="text-center">Báo cáo</th>--}}
                                <th class="text-center">Người giao</th>
                            </tr>
                            <tr>
                                <td>
                                    <b class="qc-color-red">{!! $dataProduct->productType->name() !!}</b>
                                    <br/>
                                    <em style="color:grey;">- Đơn hàng: </em>
                                    <pans>{!! $dataOrder->name() !!}</pans>
                                    <br/>
                                    <em style="color:grey;">- Vai trò: </em>
                                    @if($dataWorkAllocation->checkRoleMain())
                                        <span>Chính</span>
                                    @else
                                        <span>Phụ</span>
                                    @endif
                                    <br/>
                                    <em style="color:grey;">- Ghi chú: </em>
                                    <pans>{!! $allocationNote !!}</pans>
                                    <br/>
                                    <em style="color:grey;"> - Thông tin thi công:</em>
                                    <span>{!! $dataOrder->constructionAddress() !!}
                                        - ĐT: {!! $dataOrder->constructionPhone() !!}
                                        - tên: {!! $dataOrder->constructionContact() !!}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if(!$hFunction->checkEmpty($productDesignImage))
                                        <a class="qc_design_image_view qc-link"
                                           data-href="{!! route('qc.work.work_allocation.work_allocation.design_image.view', $productDesignImage->designId()) !!}">
                                            <img style="width: 70px; height: 70px;"
                                                 src="{!! $productDesignImage->pathSmallImage($productDesignImage->image()) !!}">
                                        </a>
                                    @else
                                        <span>Thiết kế cập nhật sau</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="qc-font-bold" style="color: grey;">
                                        {!! date('d-m-Y ', strtotime($allocationDate)) !!}
                                        </span>
                                        <span class="qc-font-bold">
                                            {!! date('H:i', strtotime($allocationDate)) !!}
                                    </span>
                                    <br/>
                                    <span class="qc-font-bold" style="color: brown;">
                                        {!! date('d-m-Y ', strtotime($receiveDeadline)) !!}
                                        </span>
                                        <span class="qc-font-bold">
                                            {!! date('H:i', strtotime($receiveDeadline)) !!}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($dataWorkAllocation->checkCancel())
                                        <em style="color: grey;">Đã hủy</em>
                                    @else
                                        @if($hFunction->checkCount($dataWorkAllocationFinish))
                                            <em style="color: grey;">Đã kết thúc</em>
                                        @else
                                            <em style="color: grey;">Đang thi công</em>
                                        @endif
                                        @if($dataWorkAllocation->checkLate($allocationId))
                                            <br/>
                                            <span style="color: white; padding: 3px; background-color: red;">TRỄ</span>
                                        @endif
                                    @endif
                                </td>
                                {{--<td class="text-center">
                                    <a class="qc_work_allocation_report_act qc-link-green-bold">
                                        Báo cáo công việc
                                    </a>
                                </td>--}}
                                <td class="text-center">
                                    {!! $dataWorkAllocation->allocationStaff->lastName() !!}
                                </td>
                            </tr>
                            <tr>
                                <th colspan="6" style="border-bottom: none;">
                                    <label style="color: deeppink;">CHI TIẾT BÁO CÁO</label>
                                </th>
                            </tr>
                            @if($hFunction->checkCount($dataWorkAllocationReport))
                                <tr>
                                    <td colspan="6" style="border-top: none;">
                                        <div class="table-responsive">
                                            <table class="table table-condensed">
                                                <tr style="background-color: black; color: white;">
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
                                                                           data-href="{!! route('qc.work.work_allocation.work_allocation.report_image_direct.view', $workAllocationReportImage->imageId()) !!}">
                                                                            <img style="max-width: 100%; max-height: 100%;"
                                                                                 src="{!! $workAllocationReportImage->pathSmallImage($workAllocationReportImage->name()) !!}">
                                                                        </a>
                                                                        {{--<a class="qc_report_image_delete qc-link"
                                                                           title="Xóa ảnh báo cáo"
                                                                           data-href="{!! route('qc.work.work_allocation.work_allocation.report.image.delete', $workAllocationReportImage->imageId()) !!}">
                                                                            <i style="position: absolute; font-weight: bold; padding: 0 3px; color: red; top: 3px; right: 3px; border: 1px solid #d7d7d7;">x</i>
                                                                        </a>--}}
                                                                    </div>
                                                                @endforeach
                                                            </td>
                                                            <td>
                                                                <em class="qc-color-grey">- {!! $workAllocationReport->content() !!}</em>
                                                            </td>
                                                            <td class="text-center">
                                                                <a class="qc_delete_report_action qc-link-red-bold qc-font-size-14"
                                                                   data-href="{!! route('qc.work.work_allocation.work_allocation.report.cancel.get', $workAllocationReport->reportId()) !!}">
                                                                    HỦY
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
                                                                           data-href="{!! route('qc.work.work_allocation.work_allocation.report_image_timekeeping.view', $timekeepingProvisionalImage->imageId()) !!}">
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
                                                                <a class="qc_delete_report_action qc-link-red-bold qc-font-size-14"
                                                                   data-href="{!! route('qc.work.work_allocation.work_allocation.report.cancel.get', $workAllocationReport->reportId()) !!}">
                                                                    HỦY
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="6">
                                        <span>Không có báo cáo</span>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
            {{-- chi tiêt --}}
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="qc_work_allocation_detail_container qc-container-table row">

                </div>
            </div>
        </div>
    </div>
@endsection
