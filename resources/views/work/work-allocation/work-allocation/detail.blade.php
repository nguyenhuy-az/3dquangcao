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
?>
@extends('work.work-allocation.index')
@section('titlePage')
    Chi tiết thi công
@endsection
@section('qc_work_allocation_body')
    <div class="row qc_work_allocation_detail_wrap">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            @include('work.work-allocation.menu')

            <div class="row">
                <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-bordered qc-margin-bot-none" style="border: none;">
                            <tr>
                                <th colspan="6" style="border-top: none;">
                                    <em style="color: deeppink;">Công việc được bàn giao</em>
                                </th>
                            </tr>
                            <tr style="background-color: whitesmoke;">
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
                                <th class="text-center">Báo cáo</th>
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
                                           data-href="{!! route('qc.work.work_allocation.design_image.view', $productDesignImage->designId()) !!}">
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
                                <td class="text-center qc-color-grey">

                                </td>
                                <td class="text-center">
                                    <a class="qc_work_allocation_report_act qc-link-green-bold">
                                        Báo cáo công việc
                                    </a>
                                </td>
                                <td class="text-center">
                                    {!! $dataWorkAllocation->allocationStaff->lastName() !!}
                                </td>
                            </tr>
                            <tr>
                                <th colspan="6">
                                    <em style="color: deeppink;">Báo cáo công việc</em>
                                </th>
                            </tr>
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
        <div class="text-center qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <a class="btn btn-sm btn-primary" onclick="qc_main.page_back();">
                Về trang trước
            </a>
        </div>
    </div>
@endsection
