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
$hrefIndex = route('qc.work.work_allocation.work_allocation.index');
$dataWork = $dataStaff->workInfoActivityOfStaff();
$workId = $dataWork->workId();
?>
@extends('work.work-allocation.work-allocation.index')
@section('qc_work_allocation_body')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="padding-bottom: 5px;">
            <a class="btn btn-sm btn-primary" onclick="qc_main.page_back();">
                Về trang trước
            </a>
        </div>
        <div class="qc_work_allocation_wrap qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            @include('work.work-allocation.menu',compact('modelStaff'))

            <div class="qc_work_allocation_contain qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-bordered qc-margin-bot-none" style="border: none;">
                            {{--<tr>
                                <th colspan="7" style="border-top: none;">
                                    <em style="color: deeppink;">(CÔNG VIỆC ĐƯỢC BÀN GIAO)</em>
                                </th>
                            </tr>--}}
                            <tr style="background-color: black;color: yellow;">
                                <th style="width: 20px;"></th>
                                <th>NGÀY NHẬN</th>
                                <th>SẢN PHẨM</th>
                                <th>TIẾN ĐỘ</th>
                                <th class="text-center">HẠN GIAO</th>
                                <th class="text-center">NGÀY BÁO HOÀN THÀNH</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td style="padding:0;">
                                    <select class="cbWorkAllocationMonthFilter col-sx-4 col-sm-4 col-md-4 col-lg-4"
                                            style="height: 34px;"
                                            data-href="{!! $hrefIndex !!}">
                                        <option value="100" @if((int)$monthFilter == 100) selected="selected" @endif >
                                            Tất cả tháng
                                        </option>
                                        @for($m =1;$m<= 12; $m++)
                                            <option value="{!! $m !!}"
                                                    @if((int)$monthFilter == $m) selected="selected" @endif>
                                                Tháng {!! $m !!}
                                            </option>
                                        @endfor
                                    </select>
                                    <select class="cbWorkAllocationYearFilter col-sx-8 col-sm-8 col-md-8 col-lg-8"
                                            style="height: 34px;"
                                            data-href="{!! $hrefIndex !!}">
                                        <option value="100" @if((int)$yearFilter == 100) selected="selected" @endif >
                                            Tất cả năm
                                        </option>
                                        @for($y =2017;$y<= 2050; $y++)
                                            <option value="{!! $y !!}"
                                                    @if($yearFilter == $y) selected="selected" @endif>
                                                {!! $y !!}
                                            </option>
                                        @endfor
                                    </select>
                                </td>

                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="padding: 0;">
                                    <select class="cbWorkAllocationFinishStatus form-control"
                                            data-href="{!! $hrefIndex !!}">
                                        <option value="100"
                                                @if($finishStatus == 100) selected="selected" @endif>
                                            Tất cả
                                        </option>
                                        <option value="0" @if($finishStatus == 0) selected="selected" @endif>
                                            Chưa Xong
                                        </option>
                                        <option value="1"
                                                @if($finishStatus == 1) selected="selected" @endif>
                                            Đã Xong
                                        </option>
                                    </select>
                                </td>
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
                                    # thong ket thuc phan viec
                                    $dataWorkAllocationFinish = $workAllocation->workAllocationFinishInfo();
                                    ?>
                                    <tr class="qc_work_allocation_object"
                                        @if($n_o%2 == 0) style="background-color: whitesmoke;"
                                        @endif data-work-allocation="{!! $allocationId !!}">
                                        <td class="text-center">
                                            <b>{!! $n_o !!}</b>
                                        </td>
                                        <td>
                                            <span class="qc-font-bold" style="color: brown;">
                                                {!! date('d-m-Y ', strtotime($receiveDeadline)) !!}
                                                </span>
                                                <span class="qc-font-bold">
                                                    {!! date('H:i', strtotime($receiveDeadline)) !!}
                                            </span>
                                            <br/>
                                            <a class="qc-link-green-bold"
                                               href="{!! route('qc.work.work_allocation.work_allocation.detail.get', $allocationId) !!}">
                                                CHI TIẾT
                                            </a>
                                            @if($workAllocation->checkViewedNewWorkAllocation($allocationId,$workAllocation->receiveStaffId()))
                                                <em style="color: grey;"> - Đã xem</em>
                                            @else
                                                <em style="color: red;"> - Chưa xem</em>
                                            @endif
                                        </td>
                                        <td>
                                            <b class="qc-color-red">{!! $workAllocation->product->productType->name() !!}</b>
                                            <em style="color:grey;">
                                                - (ĐH: {!! $workAllocation->product->order->name() !!})
                                            </em>
                                            @if(!empty($allocationNote))
                                                <br/>
                                                {!! $allocationNote !!}
                                            @endif
                                        </td>
                                        <td>
                                            @if($workAllocation->checkCancel())
                                                <em style="color: blue;">Đã hủy</em>
                                            @else
                                                @if($hFunction->checkCount($dataWorkAllocationFinish))
                                                    <em style="color: grey;">Đã kết thúc</em>
                                                @else
                                                    <a class="qc_work_allocation_report_act qc-link-green-bold"
                                                       data-href="{!! route('qc.work.work_allocation.work_allocation.report.get',$allocationId) !!}">
                                                        BÁO CÁO CÔNG VIỆC
                                                    </a>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="qc-font-bold" style="color: grey;">
                                                {!! date('d-m-Y  ', strtotime($allocationDate)) !!}
                                                </span>
                                                <span class="qc-font-bold">
                                                    {!! date('H:i', strtotime($allocationDate)) !!}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($workAllocation->checkCancel())
                                                <em style="color: grey;">Đã hủy</em>
                                            @else
                                                @if($hFunction->checkCount($dataWorkAllocationFinish))
                                                    <span class="qc-font-bold" style="color: brown;">
                                                    {!! date('d-m-Y ', strtotime($dataWorkAllocationFinish->finishDate())) !!}
                                                </span>
                                                    <span class="qc-font-bold">
                                                    {!! date('H:i', strtotime($dataWorkAllocationFinish->finishDate())) !!}
                                                </span>
                                                @else
                                                    <em style="color: grey;">Đang thi công</em>
                                                @endif
                                                @if($workAllocation->checkLate($allocationId))
                                                    <br/>
                                                    <span style="color: white; padding: 3px; background-color: red;">TRỄ</span>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="text-center" colspan="6">
                                        {!! $hFunction->page($dataWorkAllocation) !!}
                                    </td>
                                </tr>
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
        </div>
    </div>
@endsection
