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
                                # thiet ke dang ap dung
                                $dataProductDesign = $product->productDesignInfoApplyActivity();
                                if ($hFunction->getCountFromData($dataProductDesign) == 0) {
                                    # thiet ke sau cung
                                    $dataProductDesign = $product->productDesignInfoLast();
                                }
                                # san pham da ket thuc hay chua
                                $checkFinishStatus = $product->checkFinishStatus();
                                ?>
                                <div class="row">
                                    <div class="table-responsive">
                                        <table class="table" style="border: 2px solid black;">
                                            <tr>
                                                <td style="width:50px; background-color: #d7d7d7 ;">
                                                    <b style="color: red; font-size: 1.5em;">
                                                        SP_{!! $sp_n_o = (isset($sp_n_o))?$sp_n_o+1:1 !!}
                                                    </b>
                                                </td>
                                                <td style="border: 1px solid #d7d7d7;">
                                                    <label style="font-size: 1.5em;">{!! ucwords($product->productType->name()) !!}</label>
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
                                                <td class="text-left" colspan="2" style="border: 1px solid #d7d7d7;">
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
                                                </td>
                                                <td style="padding-bottom: 10px;">
                                                    @if($hFunction->checkCount($dataProductDesign))
                                                        @if($dataProductDesign->checkApplyStatus())
                                                            <img style="width: 70px; height: auto; margin-right: 5px;"
                                                                 title="Đang áp dụng"
                                                                 src="{!! $dataProductDesign->pathSmallImage($dataProductDesign->image()) !!}">
                                                        @else
                                                            <img style="width: 70px; height: 70px; margin-bottom: 5px;"
                                                                 title="Không được áp dụng"
                                                                 src="{!! $dataProductDesign->pathSmallImage($dataProductDesign->image()) !!}">
                                                        @endif
                                                        <br/>
                                                        <em class="qc-color-grey">Thiết kế SP</em>
                                                    @else
                                                        @if(!$hFunction->checkEmpty($designImage))
                                                            <img style="width: 70px; height: 70px; margin: 5px; "
                                                                 src="{!! $product->pathSmallDesignImage($designImage) !!}">
                                                        @else
                                                            <em class="qc-color-grey">Gửi thiết kế sau</em>
                                                        @endif
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
                                                                        <td>
                                                                            <div class="media">
                                                                                <a class="pull-left" href="#">
                                                                                    <img class="media-object"
                                                                                         style="max-width: 40px;height: 40px; border: 1px solid #d7d7d7;border-radius: 10px;"
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
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <em>{!! $workAllocation->noted() !!}</em>
                                                                        </td>
                                                                        <td>
                                                                            <em>TG nhận:</em>
                                                                            <b>
                                                                                {!! $hFunction->convertDateDMYFromDatetime($workAllocation->allocationDate()) !!}
                                                                                &nbsp;
                                                                                {!! $hFunction->getTimeFromDate($workAllocation->allocationDate()) !!}
                                                                            </b>
                                                                        </td>
                                                                        <td>
                                                                            <em>TG giao:</em>
                                                                            <b>
                                                                                {!! $hFunction->convertDateDMYFromDatetime($workAllocation->receiveDeadline()) !!}
                                                                                &nbsp;
                                                                                {!! $hFunction->getTimeFromDate($workAllocation->receiveDeadline()) !!}
                                                                            </b>
                                                                        </td>
                                                                        <td>
                                                                            @if($hFunction->checkCount($dataWorkAllocationReport))
                                                                                @foreach($dataWorkAllocationReport as $workAllocationReport)
                                                                                    <?php
                                                                                    $dataWorkAllocationReportImage = $workAllocationReport->workAllocationReportImageInfo();
                                                                                    #bao cao khi bao gio ra
                                                                                    $dataTimekeepingProvisionalImage = $workAllocationReport->timekeepingProvisionalImageInfo();
                                                                                    ?>
                                                                                    @foreach($dataWorkAllocationReportImage as $workAllocationReportImage)
                                                                                        <div style="position: relative; float: left; margin: 5px; width: 70px; height: 70px; border: 1px solid #d7d7d7;">
                                                                                            <a class="qc_work_allocation_report_image_view qc-link"
                                                                                               title="Click xem chi tiết hình ảnh"
                                                                                               data-href="{!! route('qc.work.work_allocation.order.allocation.report_image.get', $workAllocationReportImage->imageId()) !!}">
                                                                                                <img style="max-width: 100%; max-height: 100%;"
                                                                                                     src="{!! $workAllocationReportImage->pathSmallImage($workAllocationReportImage->name()) !!}">
                                                                                            </a>
                                                                                        </div>
                                                                                    @endforeach
                                                                                    <i class="glyphicon glyphicon-calendar"></i>
                                                                                    &nbsp;
                                                                                    <b>{!! $hFunction->convertDateDMYHISFromDatetime($workAllocationReport->reportDate()) !!}</b>
                                                                                    <br/>
                                                                                    <em class="qc-color-grey">- {!! $workAllocationReport->content() !!}</em>
                                                                                    {{--<br/>
                                                                                    <a class="qc_work_allocation_view qc-link-green-bold"
                                                                                       title="Click xem chi tiết thi công"
                                                                                       data-href="{!! route('qc.work.work_allocation.order.work_allocation.get',$allocationId) !!}">
                                                                                        XEM BÁO CÁO
                                                                                    </a>--}}
                                                                                @endforeach
                                                                            @else
                                                                                <em class="qc-color-grey">Không có
                                                                                    báocáo</em>
                                                                            @endif
                                                                        </td>
                                                                        <td class="text-right">
                                                                            @if($workAllocation->checkActivity())
                                                                                <em style="color: black;">Đang thi
                                                                                    công</em>
                                                                                <br/>
                                                                                <a class="qc_cancel_allocation_product qc-link-red-bold"
                                                                                   title="Hủy giao việc"
                                                                                   data-href="{!! route('qc.work.work_allocation.order.product.work-allocation.cancel.get', $allocationId) !!}">
                                                                                    HỦY
                                                                                </a>
                                                                            @else
                                                                                @if($workAllocation->checkCancel())
                                                                                    <em style="color: grey;">Đã hủy</em>
                                                                                @else
                                                                                    <em style="color: grey;">Đã kết
                                                                                        thúc</em>
                                                                                @endif
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
