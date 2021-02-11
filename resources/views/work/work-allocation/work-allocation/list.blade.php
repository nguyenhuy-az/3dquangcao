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
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-bottom: 5px;">
            <a class="btn btn-sm btn-primary" onclick="qc_main.page_back();">
                Về trang trước
            </a>
        </div>
        <div class="qc_work_allocation_wrap qc-padding-bot-20 col-xs-12 col-sm-12 col-md-12 col-lg-12">
            @include('work.work-allocation.menu',compact('modelStaff'))

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <h4 style="background-color: red; margin: 0;padding: 3px 10px;">
                        <span style="color: white;">THƯỞNG</span>
                        <span style="color: yellow;">Khi hoàn thành đúng hạn</span>,
                        <span style="color: white;">PHẠT</span>
                        <span style="color: yellow;">Khi hoàn thành trễ hạn Theo nội quy của công ty.</span>
                    </h4>
                </div>
            </div>
            <div class="qc_work_allocation_contain qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-bordered qc-margin-bot-none">
                            <tr style="background-color: black;color: yellow;">
                                <th class="text-center" style="width: 100px;">
                                    HẠN GIAO - HOÀN THÀNH
                                </th>
                                <th style="width: 300px;">THI CÔNG SẢN PHẨM</th>
                                <th>TIẾN ĐỘ</th>
                                <th>NGÀY NHẬN</th>
                            </tr>
                            <tr>
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
                                <td></td>
                                <td></td>
                                <td style="padding:0;">
                                    <select class="cbWorkAllocationMonthFilter col-xs-4 col-sm-4 col-md-4 col-lg-4"
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
                                    <select class="cbWorkAllocationYearFilter col-xs-8 col-sm-8 col-md-8 col-lg-8"
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
                                    $cancelStatus = $workAllocation->checkCancel();
                                    //$dataWorkAllocationReport = $workAllocation->workAllocationReportInfo();
                                    # bao cao tien do
                                    $dataWorkAllocationReport = $workAllocation->workAllocationReportInfo($allocationId, 1);
                                    $dataProduct = $workAllocation->product;
                                    $productWidth = $dataProduct->width();
                                    $productHeight = $dataProduct->height();
                                    $productAmount = $dataProduct->amount();
                                    $productDescription = $dataProduct->description();
                                    # thiet ke san pham
                                    $dataProductDesign = $dataProduct->productDesignInfoApplyActivity();
                                    # thiet ke san pham thi cong
                                    $dataProductDesignConstruction = $dataProduct->productDesignInfoConstructionHasApply();

                                    # lay danh sach cung thi cong san pham lien quan khong bi huy
                                    $dataWorkAllocationRelation = $dataProduct->workAllocationInfoNotCancelOfProduct();
                                    $n_o = $n_o + 1;
                                    # thong ket thuc phan viec
                                    $dataWorkAllocationFinish = $workAllocation->workAllocationFinishInfo();
                                    ?>
                                    <tr class="qc_work_allocation_object @if($n_o%2 == 0) info @endif"
                                        data-work-allocation="{!! $allocationId !!}">
                                        <td class="text-center"
                                            style="padding: 0; @if($dataWorkAllocationFinish || $cancelStatus) background-color: pink; @endif">
                                             <span class="qc-font-bold" style="color: red;">
                                                {!! date('d-m-Y ', strtotime($receiveDeadline)) !!}
                                            </span>
                                            <span class="qc-font-bold">
                                                {!! date('H:i', strtotime($receiveDeadline)) !!}
                                            </span>
                                            @if($cancelStatus)
                                                <br/>
                                                <em style="color: blue;">Đã hủy</em>
                                            @else
                                                @if($hFunction->checkCount($dataWorkAllocationFinish))
                                                    <br/>
                                                    <em style="color: grey;">Đã kết thúc</em>
                                                    <br/>
                                                    <span class="qc-font-bold" style="color: blue;">
                                                        {!! date('d-m-Y ', strtotime($dataWorkAllocationFinish->finishDate())) !!}
                                                    </span>
                                                    <span class="qc-font-bold">
                                                        {!! date('H:i', strtotime($dataWorkAllocationFinish->finishDate())) !!}
                                                    </span>
                                                @else
                                                    <br/>
                                                    <a class="qc_work_allocation_report_act qc-link-green-bold"
                                                       data-href="{!! route('qc.work.work_allocation.work_allocation.report.get',$allocationId) !!}">
                                                        BÁO CÁO CÔNG VIỆC
                                                    </a>
                                                @endif
                                                @if($workAllocation->checkLate($allocationId))
                                                    <br/>
                                                    <span style="color: white; padding: 3px; background-color: red;">TRỄ</span>
                                                @endif
                                            @endif
                                            <br/>
                                            <em>
                                                - Thiết kế SP:
                                            </em>
                                            @if($hFunction->checkCount($dataProductDesign))
                                                <a class="qc-link qc_work_allocation_design_image_view"
                                                   data-href="{!! route('qc.work.work_allocation.work_allocation.design_image.view',$dataProductDesign->designId()) !!}">
                                                    <img style="width: 70px; height: auto; margin-right: 5px; border: 1px solid grey;"
                                                         title="Đang áp dụng"
                                                         src="{!! $dataProductDesign->pathSmallImage($dataProductDesign->image()) !!}">
                                                </a>
                                            @else
                                                <em class="qc-color-grey">Không có thiết kế</em>
                                            @endif
                                        </td>
                                        <td>
                                            <b style="background-color: black; color: lime; padding: 3px; font-size: 14px;">
                                                {!! $workAllocation->product->productType->name() !!}
                                            </b>
                                            <br/>
                                            <em style="color:grey;">
                                                - ĐH: {!! $workAllocation->product->order->name() !!}
                                            </em>
                                            <br/>
                                            <em>- Ngang: </em>
                                            <span> {!! $productWidth !!} mm</span>
                                            <em>- Cao: </em>
                                            <span>{!! $productHeight !!} mm</span>
                                            <em>- Số lượng: </em>
                                            <span style="color: red;">{!! $productAmount !!}</span>
                                            <br/>
                                            <em style="color: blue;">
                                                - Mô tả SP:
                                            </em>
                                            <span style="color: red;">
                                                @if($hFunction->checkEmpty($productDescription))
                                                    {!! $productDescription !!}
                                                @else
                                                    Không có
                                                @endif
                                            </span>

                                            <br/>
                                            <em>
                                                - Thiết kế Thi công:
                                            </em>
                                            @if($hFunction->checkCount($dataProductDesignConstruction))
                                                @foreach($dataProductDesignConstruction as $productDesignConstruction)
                                                    <a class="qc-link qc_work_allocation_design_image_view"
                                                       data-href="{!! route('qc.work.work_allocation.work_allocation.design_image.view',$productDesignConstruction->designId()) !!}">
                                                        <img style="width: 70px; height: auto; margin-right: 5px; border: 1px solid grey;"
                                                             title="Đang áp dụng"
                                                             src="{!! $productDesignConstruction->pathSmallImage($productDesignConstruction->image()) !!}">
                                                    </a>
                                                @endforeach
                                            @else
                                                <em class="qc-color-grey">Không có thiết kế</em>
                                            @endif
                                            <br/>
                                            <em style="color: blue;">
                                                - Vai trò:
                                            </em>
                                            @if($workAllocation->checkRoleMain())
                                                <span>Làm chính</span>
                                            @else
                                                <span>Làm phụ</span>
                                            @endif
                                            <br/>
                                            <em>
                                                - Ghi chú:
                                            </em>
                                            @if(!$hFunction->checkEmpty($allocationNote))
                                                {!! $allocationNote !!}
                                            @else
                                                <span>Làm theo phân công</span>
                                            @endif
                                            <br/>
                                            <em style="color: blue;">- Nhân sự:</em>
                                            @if($hFunction->checkCount($dataWorkAllocationRelation))
                                                @foreach($dataWorkAllocationRelation as $workAllocationRelation)
                                                    <b>{!! $workAllocationRelation->receiveStaff->lastName() !!}, </b>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            @if($hFunction->checkCount($dataWorkAllocationReport))
                                                @foreach($dataWorkAllocationReport as $workAllocationReport)
                                                    <?php
                                                    # bao cao truc tiep
                                                    $dataWorkAllocationReportImage = $workAllocationReport->workAllocationReportImageInfo();
                                                    #bao cao khi bao gio ra
                                                    $dataTimekeepingProvisionalImage = $workAllocationReport->timekeepingProvisionalImageInfo();
                                                    ?>
                                                    <div class="media">
                                                        @if($hFunction->checkCount($dataWorkAllocationReportImage))
                                                            @foreach($dataWorkAllocationReportImage as $workAllocationReportImage)
                                                                <a class="pull-left qc_work_allocation_report_image_view qc-link"
                                                                   title="Click xem chi tiết hình ảnh"
                                                                   data-href="{!! route('qc.work.work_allocation.work_allocation.report_image_direct.view', $workAllocationReportImage->imageId()) !!}">
                                                                    <img class="media-object"
                                                                         style="width: 70px; max-height: 100%; border: 1px solid grey;"
                                                                         src="{!! $workAllocationReportImage->pathSmallImage($workAllocationReportImage->name()) !!}">
                                                                </a>
                                                            @endforeach
                                                        @endif
                                                        @if($hFunction->checkCount($dataTimekeepingProvisionalImage))
                                                            @foreach($dataTimekeepingProvisionalImage as $timekeepingProvisionalImage)
                                                                <a class="pull-left qc_work_allocation_report_image_view qc-link"
                                                                   title="Click xem chi tiết hình ảnh"
                                                                   data-href="{!! route('qc.work.work_allocation.work_allocation.report_image_timekeeping.view', $timekeepingProvisionalImage->imageId()) !!}">
                                                                    <img class="media-object"
                                                                         style="width: 70px; max-height: 100%; border: 1px solid grey;"
                                                                         src="{!! $timekeepingProvisionalImage->pathSmallImage($timekeepingProvisionalImage->name()) !!}">
                                                                </a>
                                                            @endforeach
                                                        @endif
                                                        <div class="media-body">
                                                            <i class="glyphicon glyphicon-calendar"></i>
                                                            &nbsp;
                                                            <b>{!! $hFunction->convertDateDMYHISFromDatetime($workAllocationReport->reportDate()) !!}</b>
                                                            <br/>
                                                            <em class="qc-color-grey">- {!! $workAllocationReport->content() !!}</em>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <em class="qc-color-grey">Không có báo cáo</em>
                                            @endif
                                            <br/>
                                            <a class="qc-link-green-bold"
                                               href="{!! route('qc.work.work_allocation.work_allocation.detail.get', $allocationId) !!}">
                                                CHI TIẾT THI CÔNG
                                            </a>
                                        </td>
                                        <td>
                                            <span class="qc-font-bold" style="color: brown;">
                                                {!! date('d-m-Y  ', strtotime($allocationDate)) !!}
                                            </span>
                                            <span class="qc-font-bold">
                                                {!! date('H:i', strtotime($allocationDate)) !!}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="text-center" colspan="4">
                                        {!! $hFunction->page($dataWorkAllocation) !!}
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td class="text-center" colspan="4">
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
