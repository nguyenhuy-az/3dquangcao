<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 *
 * dataProductType
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$dataStaffLogin = $modelStaff->loginStaffInfo();
$dataOrderConstruction = $dataOrder->orderAllocationActivity();

$orderId = $dataOrder->orderId();
$deliveryDate = $dataOrder->deliveryDate();
$deliveryDay = $hFunction->getDayFromDate($deliveryDate);
$deliveryMonth = $hFunction->getMonthFromDate($deliveryDate);
$deliveryYear = $hFunction->getYearFromDate($deliveryDate);
$deliveryHour = $hFunction->getHourFromDate($deliveryDate);
$orderFinishStatus = $dataOrder->checkFinishStatus();
# thong tin ban giao sau cung
$dataOrderAllocation = $dataOrder->orderAllocationLastInfo();
$currentDate = $hFunction->currentDay();
$currentDay = $hFunction->currentDay();
$currentMonth = $hFunction->currentMonth();
$currentYear = $hFunction->currentYear();
$currentHour = $hFunction->currentHour();
# thong tin san pham va thi cong
$dataProduct = $dataOrder->productActivityOfOrder();
?>
@extends('work.orders.orders.index')
@section('titlePage')
    Chi tiết thi công sản phẩm
@endsection
@section('qc_work_order_body')
    <div id="qc_order_order_construction_wrap" class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
         style="padding-bottom: 50px;">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <a class="btn btn-sm btn-primary" onclick="qc_main.page_back();">
                Về trang trước
            </a>
        </div>
        {{-- BÀN GIAO CÔNG TRÌNH --}}
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <h3 style="color: red;">THI CÔNG: {!! $dataOrder->name() !!}</h3>
            </div>
        </div>
        @if($orderFinishStatus)
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                     style="background-color: grey; padding-bottom: 10px; padding-top: 10px;">
                    <span style="color: yellow; ">ĐƠN HÀNG ĐÃ XONG</span>
                </div>
            </div>
        @endif
        {{-- thông tin đơn hàng --}}
        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4" style="border-left: 3px solid grey;">
                <em class=" qc-color-grey">Mã ĐH:</em>
                <b class="pull-right">{!! $dataOrder->orderCode() !!}</b>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4" style="border-left: 3px solid grey;">
                <em class="qc-color-grey">Ngày nhận:</em>
                <b>{!! $hFunction->convertDateDMYFromDatetime($dataOrder->receiveDate()) !!}</b>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4" style="border-left: 3px solid grey;">
                <em class="qc-color-grey">Ngày giao:</em>
                <b>{!! $hFunction->convertDateDMYFromDatetime($dataOrder->deliveryDate()) !!}</b>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4" style="border-left: 3px solid grey;">
                <em class="qc-color-grey">TT thi công:</em>
                <span class="pull-right">
                    {!! $dataOrder->constructionAddress() !!}
                </span>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4" style="border-left: 3px solid grey;">
                <em class="qc-color-grey">Liên hệ:</em>
                <span class="pull-right">
                    ĐT: {!! $dataOrder->constructionPhone() !!}
                    - tên: {!! $dataOrder->constructionContact() !!}
                </span>
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4" style="border-left: 3px solid grey;">
                <em class="qc-color-grey">Phụ trách thi công:</em>
                @if($hFunction->checkCount($dataOrderAllocation))
                    <span class="pull-right">{!! $dataOrderAllocation->receiveStaff->fullName() !!}</span>
                @else
                    <span class="pull-right">Không có</span>
                @endif
            </div>
        </div>
        {{-- THI CONG SAN PHAM --}}
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <label style="color: blue;font-size: 1.5em;">THI CÔNG SẢN PHẨM</label>
            </div>
        </div>
        @if($hFunction->checkCount($dataProduct))
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-top: 10px;">
                    @foreach($dataProduct as $product)
                        <?php
                        $productId = $product->productId();
                        $productWidth = $product->width();
                        $productHeight = $product->height();
                        $productAmount = $product->amount();
                        $productDescription = $product->description();
                        $dataWorkAllocation = $product->workAllocationInfoOfProduct();
                        # thiet ke dang ap dung
                        $dataProductDesign = $product->productDesignInfoApplyActivity();
                        # thiet ke san pham thi cong
                        $dataProductDesignConstruction = $product->productDesignInfoConstructionHasApply();
                        # san pham da ket thuc hay chua
                        $checkFinishStatus = $product->checkFinishStatus();
                        ?>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered" style="border: 1px solid grey;">
                                        <tr style="background-color: whitesmoke;">
                                            <td class="text-center" style="width: 100px;">
                                                <b style="color: red; font-size: 1.5em;">
                                                    SP {!! $sp_n_o = (isset($sp_n_o))?$sp_n_o+1:1 !!}
                                                </b>
                                                @if($hFunction->checkCount($dataProductDesign))
                                                    <br/>
                                                    <a class="qc-link qc_work_order_product_design_image_view"
                                                       data-href="{!! route('qc.work.orders.product_design.view.get', $dataProductDesign->designId()) !!}">
                                                        <img style="width: 100%; height: auto; border: 1px solid grey;"
                                                             title="Ảnh thiết kế"
                                                             src="{!! $dataProductDesign->pathSmallImage($dataProductDesign->image()) !!}">
                                                    </a>
                                                @else
                                                    <br/>
                                                    <em class="qc-color-grey">Không có thiết kế</em>
                                                @endif
                                            </td>
                                            <td style="width: 300px;">
                                                <label style="font-size: 1.5em;">{!! ucwords($product->productType->name()) !!}</label>
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
                                                @if(!$checkFinishStatus)
                                                    <span style="color: blue;">Đang làm</span>
                                                @else
                                                    <em style="color: blue;">Đã xong</em>
                                                    <em style="color: grey;">-</em>
                                                    @if($product->productRepairActivityOfProduct())
                                                        <span style="background-color: red; color: yellow; padding: 3px;">ĐANG SỬA CHỬA</span>
                                                    @else
                                                        <a class="qc_product_repair_get qc-link-red-bold"
                                                           data-href="{!! route('qc.work.orders.construction.detail.repair.get', $productId) !!}">
                                                            BÁO SỬA CHỮA
                                                        </a>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                @if($hFunction->checkCount($dataProductDesignConstruction))
                                                    @foreach($dataProductDesignConstruction as $productDesignConstruction)
                                                        <a class="qc_work_order_product_design_image_view qc-link"
                                                           data-href="{!! route('qc.work.orders.product_design.view.get', $productDesignConstruction->designId()) !!}">
                                                            <img style="width: 70px; height: auto; margin-bottom: 5px; border: 1px solid grey;"
                                                                 title="Ảnh thiết kế thi công"
                                                                 src="{!! $productDesignConstruction->pathSmallImage($productDesignConstruction->image()) !!}">
                                                        </a>
                                                    @endforeach
                                                @else
                                                    <span style="background-color: black; color: lime;">Không có TK thi công </span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" style="padding: 0;">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered" style="margin:0;">
                                                        @if($hFunction->checkCount($dataWorkAllocation))
                                                            @foreach($dataWorkAllocation as $workAllocation)
                                                                <?php
                                                                $allocationId = $workAllocation->allocationId();
                                                                $constructionNumber = $workAllocation->constructionNumber();
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
                                                                                     style="max-width: 40px;height: 40px; border: 1px solid #d7d7d7;border-radius: 10px;"
                                                                                     src="{!! $src !!}">
                                                                            </a>

                                                                            <div class="media-body">
                                                                                <h5 class="media-heading">{!! ucwords($dataStaffReceive->lastName()) !!}</h5>
                                                                                @if($workAllocation->checkRoleMain())
                                                                                    <em style="color: blue;">Làm
                                                                                        chính</em>
                                                                                @else
                                                                                    <em style="color: brown;">Làm
                                                                                        phụ</em>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td style="width: 100px;">
                                                                        @if($constructionNumber > 1)
                                                                            <span>
                                                                                Thi công lần {!! $constructionNumber !!}
                                                                                :
                                                                            </span>
                                                                            <br/>
                                                                            <em style="color: red">sửa chữa</em>
                                                                        @else
                                                                            <em>
                                                                                Thi công lần {!! $constructionNumber !!}:
                                                                            </em>
                                                                            <br/>
                                                                            <em style="color: red">Sản xuất</em>
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
                                                                                @foreach($dataWorkAllocationReportImage as $workAllocationReportImage)
                                                                                    <div style="position: relative; float: left; margin: 5px; width: 70px; height: 70px; border: 1px solid #d7d7d7;">
                                                                                        <a class="qc_work_order_allocation_construction_report_image_view qc-link"
                                                                                           title="Click xem chi tiết hình ảnh"
                                                                                           data-href="{!! route('qc.work.work_allocation.work_allocation.report_image_direct.view', $workAllocationReportImage->imageId()) !!}">
                                                                                            <img style="max-width: 100%; max-height: 100%;"
                                                                                                 src="{!! $workAllocationReportImage->pathSmallImage($workAllocationReportImage->name()) !!}">
                                                                                        </a>
                                                                                    </div>
                                                                                @endforeach
                                                                                @if($hFunction->checkCount($dataTimekeepingProvisionalImage))
                                                                                    <a class="pull-left qc_work_order_allocation_construction_report_image_view qc-link"
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
                                                                            @endforeach
                                                                        @else
                                                                            <em class="qc-color-grey">Không có báo
                                                                                cáo</em>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @else
                                                            <tr>
                                                                <td colspan="3" style="border-top: 0;">
                                                                    @if($product->checkFinishStatus())
                                                                        <em class="qc-color-grey">Đã kết thúc</em>
                                                                        <em> - KHÔNG CÓ THI CÔNG</em>
                                                                    @else
                                                                        <em class="qc-color-red"> CHƯA TRIỂN KHAI THI
                                                                            CÔNG</em>
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
                        </div>
                    @endforeach
                </div>
            </div>

        @else
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <em>Sản phẩm bị đã bị hủy hoặc khong có</em>
                </div>
            </div>
        @endif
    </div>

@endsection
