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
    <div id="qc_order_order_construction_wrap" class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
         style="padding-bottom: 50px;">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <a class="btn btn-sm btn-primary" onclick="qc_main.page_back();">
                Về trang trước
            </a>
        </div>
        {{-- BÀN GIAO CÔNG TRÌNH --}}
        <div class="row">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <h3 style="color: red;">THÔNG TIN THI CÔNG</h3>
            </div>
        </div>
        @if($orderFinishStatus)
            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
                     style="background-color: grey; padding-bottom: 10px; padding-top: 10px;">
                    <span style="color: yellow; ">ĐƠN HÀNG ĐÃ XONG</span>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    {{-- thông tin đơn hàng --}}
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-5">
                        <div class="table-responsive">
                            <table class="table table-hover qc-margin-bot-none">
                                <tr>
                                    <td colspan="2">
                                        <b style="font-size: 2em;">{!! $dataOrder->name() !!}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <em class=" qc-color-grey">Mã ĐH:</em>
                                    </td>
                                    <td class="text-right">
                                        <b>{!! $dataOrder->orderCode() !!}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <em class="qc-color-grey">Ngày nhận:</em>
                                    </td>
                                    <td class="text-right">
                                        <b>{!! $hFunction->convertDateDMYFromDatetime($dataOrder->receiveDate()) !!}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <em class="qc-color-grey">Ngày giao:</em>
                                    </td>
                                    <td class="text-right">
                                        <b>{!! $hFunction->convertDateDMYFromDatetime($dataOrder->deliveryDate()) !!}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <em class="qc-color-grey">Đ/c thi công:</em>
                                    </td>
                                    <td class="text-right">
                                        <span>{!! $dataOrder->constructionAddress() !!}</span>
                                        <span>- ĐT: {!! $dataOrder->constructionPhone() !!}</span>
                                        <span>- tên: {!! $dataOrder->constructionContact() !!}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <em class="qc-color-grey">Phụ trách thi công:</em>
                                    </td>
                                    <td class="text-right">
                                        @if($hFunction->checkCount($dataOrderAllocation))
                                            <em>{!! $dataOrderAllocation->receiveStaff->fullName() !!}</em>
                                        @else
                                            <em>Không có</em>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- THI CONG SAN PHAM --}}
        <div class="row">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <label style="color: blue;font-size: 1.5em;">THI CÔNG SẢN PHẨM</label>
            </div>
        </div>
        @if($hFunction->checkCount($dataProduct))
            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="padding-top: 10px;">
                    @foreach($dataProduct as $product)
                        <?php
                        $productId = $product->productId();
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
                                <table class="table table-bordered" style="border: 3px solid black;">
                                    <tr>
                                        <td class="text-center" style="width:50px; background-color: #d7d7d7 ;">
                                            <b style="color: red; font-size: 1.5em;">
                                                SP {!! $sp_n_o = (isset($sp_n_o))?$sp_n_o+1:1 !!}
                                            </b>
                                        </td>
                                        <td>
                                            <label style="font-size: 1.5em;">{!! ucwords($product->productType->name()) !!}</label>
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
                                        <td >
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
                                        <td class="text-left">
                                            <em>{!! $product->description() !!}</em>
                                        </td>
                                        <td>
                                            <em class="qc-color-grey">Thiết kế thi công</em>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" style="padding: 0;">
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
                                                                <td style="width: 50px; background-color: whitesmoke; border-top: none;" ></td>
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
                                                                                <em style="color: blue;">Làm chính</em>
                                                                            @else
                                                                                <em style="color: brown;">Làm phụ</em>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    @if($constructionNumber > 1)
                                                                        <span>Thi công lần {!! $constructionNumber !!} :</span>
                                                                        <em style="color: red">sửa chữa</em>
                                                                    @else
                                                                        <em>Thi công lần {!! $constructionNumber !!} :</em>
                                                                        <em style="color: red">Sản xuất</em>
                                                                    @endif
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
                                                                        @endforeach
                                                                    @else
                                                                        <em class="qc-color-grey">Không có báo cáo</em>
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
                                                                    <em class="qc-color-red"> CHƯA TRIỂN KHAI THI CÔNG</em>
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
            </div>

        @else
            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <em>Sản phẩm bị đã bị hủy hoặc khong có</em>
                </div>
            </div>
        @endif
    </div>

@endsection
