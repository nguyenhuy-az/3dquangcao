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

$dataOrders = $dataOrdersAllocation->orders;
$orderId = $dataOrders->orderId();
$customerId = $dataOrders->customerId();
$dataProduct = $dataOrders->allProductOfOrder();
?>
@extends('work.work-allocation.order-allocation.index')
@section('titlePage')
    Thông tin thi công
@endsection
@section('qc_work_allocation_body')
    <div class="row qc_work_allocation_construction_product_wrap">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <a class="btn btn-sm btn-primary" onclick="qc_main.page_back();">
                Về trang trước
            </a>
        </div>
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            @include('work.work-allocation.menu')

            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <h4 style="color: red;">CHI TIẾT THI CÔNG</h4>
                    <span style="color:grey;">ĐƠN HÀNG: </span>
                    <label style="font-size: 20px;">{!! $dataOrders->name() !!}</label>
                    <br/>
                    <em class="qc-color-grey">- Thời gian được giao:</em>
                    <b class="qc-color-red">{!! date('d/m/Y', strtotime($dataOrdersAllocation->allocationDate())) !!}</b>
                    <br/>
                    <em class="qc-color-grey">- Thời hạn hoàn thành:</em>
                    <b class="qc-color-red">{!! date('d/m/Y', strtotime($dataOrdersAllocation->receiveDeadline())) !!}</b>
                    <br/>
                    <em style="color:grey;"> - Thông tin thi công:</em>
                    <span>{!! $dataOrders->constructionAddress() !!}
                        - ĐT: {!! $dataOrders->constructionPhone() !!}
                        - tên: {!! $dataOrders->constructionContact() !!}
                    </span>
                </div>
            </div>
            {{-- chi tiêt --}}
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="qc_work_allocation_construction_product_list row">
                    @if($hFunction->checkCount($dataProduct))
                        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="padding-top: 10px;">
                            @foreach($dataProduct as $product)
                                <?php
                                $productId = $product->productId();
                                $productWidth = $product->width();
                                $productHeight = $product->height();
                                $productAmount = $product->amount();
                                $productDescription = $product->description();
                                $dataWorkAllocation = $product->workAllocationInfoOfProduct();
                                $designImage = $product->designImage();
                                # thiet ke san pham
                                $dataProductDesign = $product->productDesignInfoApplyActivity();
                                # thiet ke san pham thi cong
                                $dataProductDesignConstruction = $product->productDesignInfoConstructionHasApply();
                                # san pham da ket thuc hay chua
                                $checkFinishStatus = $product->checkFinishStatus();
                                ?>
                                <div class="row">
                                    <div class="table-responsive">
                                        <table class="table" style="border: 1px solid grey;">
                                            <tr style="background-color: whitesmoke;">
                                                <td class="text-center" style="width:100px;">
                                                    <b style="color: blue; font-size: 1.5em;">
                                                        SP_{!! $sp_n_o = (isset($sp_n_o))?$sp_n_o+1:1 !!}
                                                    </b>
                                                    @if($hFunction->checkCount($dataProductDesign))
                                                        <br/>
                                                        <a class="qc-link qc_work_order_product_design_image_view"
                                                           data-href="{!! route('qc.work.work_allocation.order_allocation.product.design.view', $dataProductDesign->designId()) !!}">
                                                            <img style="width: 100%; height: auto; border: 1px solid grey;"
                                                                 title="Ảnh thiết kế"
                                                                 src="{!! $dataProductDesign->pathSmallImage($dataProductDesign->image()) !!}">
                                                        </a>
                                                    @else
                                                        <br/>
                                                        <em class="qc-color-grey">Không có thiết kế</em>
                                                    @endif
                                                </td>
                                                <td style="width: 300px; border: 1px solid #d7d7d7;">
                                                    <label style="font-size: 1.5em;">{!! ucwords($product->productType->name()) !!}</label>
                                                    <br/>
                                                    <em>{!! $hFunction->convertDateDMYFromDatetime($product->createdAt()) !!}</em>
                                                    <br/>
                                                    <em>- Ngang: </em>
                                                    <span> {!! $productWidth !!} mm</span>
                                                    <em>- Cao: </em>
                                                    <span>{!! $productHeight !!} mm</span>
                                                    <em>- Số lượng: </em>
                                                    <span style="color: red;">{!! $productAmount !!}</span>
                                                    @if(!$hFunction->checkEmpty($productDescription))
                                                        <br/>
                                                        <em>- Ghi chú: </em>
                                                        <em style="color: grey;">- {!! $productDescription !!}</em>
                                                    @endif
                                                    <br/>
                                                    @if(!$product->checkCancelStatus())
                                                        @if($product->checkFinishStatus())
                                                            <em style="color: blue;">Đã hoàn thành</em>
                                                        @else
                                                            @if($dataOrdersAllocation->checkActivity())
                                                                <a class="qc_confirm_finish_product_act qc-font-size-14 qc-link-red-bold"
                                                                   data-href="{!! route('qc.work.work_allocation.order_allocation.product.confirm.get',$productId) !!}">
                                                                    BÁO HOÀN THÀNH
                                                                </a>
                                                            @else
                                                                {{--khi huy ban giao don hang--}}
                                                                <em class="qc-color-grey">Đã kết thúc</em>
                                                            @endif
                                                        @endif
                                                    @endif
                                                </td>
                                                <td style="padding-bottom: 10px;">
                                                    @if($hFunction->checkCount($dataProductDesignConstruction))
                                                        @foreach($dataProductDesignConstruction as $productDesignConstruction)
                                                            <a class="qc-link qc_work_order_product_design_image_view"
                                                               data-href="{!! route('qc.work.work_allocation.order_allocation.product.design.view',$productDesignConstruction->designId()) !!}">
                                                                <img style="width: 70px; height: auto; margin-right: 5px; border: 1px solid grey;"
                                                                     title="Đang áp dụng"
                                                                     src="{!! $productDesignConstruction->pathSmallImage($productDesignConstruction->image()) !!}">
                                                            </a>
                                                        @endforeach
                                                    @else
                                                        <em class="qc-color-grey">Không có thiết kế thi công</em>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" style="padding: 0;">
                                                    <div class="table-responsive">
                                                        <table class="table" style="margin:0;">
                                                            @if($hFunction->checkCount($dataWorkAllocation))
                                                                @foreach($dataWorkAllocation as $workAllocation)
                                                                    <?php
                                                                    $allocationId = $workAllocation->allocationId();
                                                                    $workAllocationNote = $workAllocation->noted();
                                                                    $dataStaffReceive = $workAllocation->receiveStaff;
                                                                    # anh dai dien
                                                                    $image = $dataStaffReceive->image();
                                                                    if ($hFunction->checkEmpty($image)) {
                                                                        $src = $dataStaffReceive->pathDefaultImage();
                                                                    } else {
                                                                        $src = $dataStaffReceive->pathFullImage($image);
                                                                    }
                                                                    # bao cao tien do
                                                                    $dataWorkAllocationReport = $workAllocation->workAllocationReportInfo($allocationId, 1);
                                                                    ?>
                                                                    <tr>
                                                                        <td style="width: 170px;">
                                                                            <div class="media">
                                                                                <a class="pull-left" href="#">
                                                                                    <img class="media-object"
                                                                                         style="background-color: white; width: 40px;height: 40px; border: 1px solid #d7d7d7;border-radius: 10px;"
                                                                                         src="{!! $src !!}">
                                                                                </a>

                                                                                <div class="media-body">
                                                                                    <h5 class="media-heading">{!! ucwords($dataStaffReceive->lastName()) !!}</h5>
                                                                                    @if($workAllocation->checkRoleMain())
                                                                                        <em class="qc-color-red">Làm
                                                                                            chính</em>
                                                                                    @else
                                                                                        <em style="color: brown;">Làm
                                                                                            phụ</em>
                                                                                    @endif
                                                                                    {{--@if($workAllocation->checkActivity())
                                                                                        <span> | </span>
                                                                                        <a class="qc_cancel_allocation_product qc-link-red-bold"
                                                                                           title="Hủy giao việc"
                                                                                           data-href="{!! route('qc.work.work_allocation.order.product.work-allocation.cancel.get', $allocationId) !!}">
                                                                                            HỦY
                                                                                        </a>
                                                                                    @else
                                                                                        @if($workAllocation->checkCancel())
                                                                                            <span> | </span>
                                                                                            <em style="color: grey;">
                                                                                                Đã hủy</em>
                                                                                        @else
                                                                                            <span> | </span>
                                                                                            <em style="color: grey;">
                                                                                                Đã kết thúc</em>
                                                                                        @endif
                                                                                    @endif--}}
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td style="width: 200px;">
                                                                            <em>TG nhận:</em>
                                                                            <b>
                                                                                {!! $hFunction->convertDateDMYFromDatetime($workAllocation->allocationDate()) !!}
                                                                                &nbsp;
                                                                                {!! $hFunction->getTimeFromDate($workAllocation->allocationDate()) !!}
                                                                            </b>
                                                                            <br/>
                                                                            <em>TG giao:</em>
                                                                            <b>
                                                                                {!! $hFunction->convertDateDMYFromDatetime($workAllocation->receiveDeadline()) !!}
                                                                                &nbsp;
                                                                                {!! $hFunction->getTimeFromDate($workAllocation->receiveDeadline()) !!}
                                                                            </b>
                                                                            @if($hFunction->checkEmpty($workAllocationNote))
                                                                                <br/>
                                                                                <em style="color: grey;">{!! $workAllocationNote !!}</em>
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            @if($hFunction->checkCount($dataWorkAllocationReport))
                                                                                @foreach($dataWorkAllocationReport as $workAllocationReport)
                                                                                    <?php
                                                                                    $dataWorkAllocationReportImage = $workAllocationReport->workAllocationReportImageInfo();
                                                                                    #bao cao khi bao gio ra
                                                                                    $dataTimekeepingProvisionalImage = $workAllocationReport->timekeepingProvisionalImageLastInfo();
                                                                                    ?>
                                                                                    @if($hFunction->checkCount($dataWorkAllocationReportImage))
                                                                                        @foreach($dataWorkAllocationReportImage as $workAllocationReportImage)
                                                                                            <a class="qc_work_order_allocation_product_report_image_view qc-link"
                                                                                               title="Click xem chi tiết hình ảnh"
                                                                                               data-href="{!! route('qc.work.work_allocation.work_allocation.report_image_direct.view', $workAllocationReportImage->imageId()) !!}">
                                                                                                <img style="width: 70px; background-color: white; border: 1px solid grey;"
                                                                                                     src="{!! $workAllocationReportImage->pathSmallImage($workAllocationReportImage->name()) !!}">
                                                                                            </a>
                                                                                        @endforeach
                                                                                    @endif
                                                                                    @if($hFunction->checkCount($dataTimekeepingProvisionalImage))
                                                                                        <a class="pull-left qc_work_order_allocation_product_report_image_view qc-link"
                                                                                           title="Click xem chi tiết hình ảnh" style="margin-right: 5px;"
                                                                                           data-href="{!! route('qc.work.work_allocation.work_allocation.report_image_timekeeping.view', $dataTimekeepingProvisionalImage->imageId()) !!}">
                                                                                            <img class="media-object" style="width: 70px; border: 1px solid grey;"
                                                                                                 src="{!! $dataTimekeepingProvisionalImage->pathSmallImage($dataTimekeepingProvisionalImage->name()) !!}">
                                                                                        </a>
                                                                                    @endif
                                                                                    <i class="glyphicon glyphicon-calendar"></i>
                                                                                    &nbsp;
                                                                                    <b>{!! $hFunction->convertDateDMYHISFromDatetime($workAllocationReport->reportDate()) !!}</b>
                                                                                    <br/>
                                                                                    <em class="qc-color-grey">- {!! $workAllocationReport->content() !!}</em>
                                                                                        <br/>
                                                                                        <a class="qc_work_order_allocation_product_report_view qc-link-green-bold"
                                                                                           title="Click xem chi tiết thi công"
                                                                                           data-href="{!! route('qc.work.work_allocation.order.work_allocation.report.get',$allocationId) !!}">
                                                                                            XEM BÁO CÁO
                                                                                        </a>
                                                                                @endforeach
                                                                            @else
                                                                                <em class="qc-color-grey">Không có
                                                                                    báo cáo</em>
                                                                            @endif
                                                                        </td>
                                                                    </tr>

                                                                @endforeach
                                                            @else
                                                                <tr>
                                                                    <td colspan="5" style="border-top: 0;">
                                                                        @if($product->checkFinishStatus())
                                                                            <em class="qc-color-grey">Đã kết thúc</em>
                                                                        @else
                                                                            <em class="qc-color-grey">Chưa triển
                                                                                khai</em>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        </table>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    @else
                        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <em>Sản phẩm bị đã bị hủy hoặc khong có</em>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
