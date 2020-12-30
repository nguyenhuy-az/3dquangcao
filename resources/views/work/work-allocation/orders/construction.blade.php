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
# thong tin ban giao
$dataOrderAllocation = $dataOrder->orderAllocationInfoAll();
$addAllocationStatus = true; // trang thai duoc bang giao hoac khong
if ($dataOrder->existOrderAllocationFinishOfOrder()) $addAllocationStatus = false;//co xac nhan hoan thanh thi cong
$currentDate = $hFunction->currentDay();
$currentDay = $hFunction->currentDay();
$currentMonth = $hFunction->currentMonth();
$currentYear = $hFunction->currentYear();
$currentHour = $hFunction->currentHour();

# thong tin san pham va thi cong
$dataProduct = $dataOrder->productActivityOfOrder();

?>
@extends('work.work-allocation.orders.index')
@section('titlePage')
    Bàn giao công trình và triển khai thi công
@endsection
@section('qc_work_allocation_body')
    <div id="qc_work_allocation_order_construction_wrap" class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
         style="padding-bottom: 50px;">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <a class="btn btn-sm btn-primary" onclick="qc_main.page_back();">
                Về trang trước
            </a>
        </div>
        {{-- BÀN GIAO CÔNG TRÌNH --}}
        <div class="row">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <h3 style="color: red;">TRIỂN KHAI THI CÔNG ĐƠN HÀNG</h3>
            </div>
        </div>
        @if($orderFinishStatus)
            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
                     style="background-color: red; padding-bottom: 10px; padding-top: 10px;">
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
                                    <span class="pull-right">{!! $dataOrder->constructionAddress() !!}
                                        - ĐT: {!! $dataOrder->constructionPhone() !!}
                                        - tên: {!! $dataOrder->constructionContact() !!}</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    {{-- nguoi phu trach đơn hàng --}}
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-7">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <tr>
                                    <th colspan="6">
                                        <i class="qc-font-size-16 glyphicon glyphicon-user"></i>
                                        <b style="color: blue; font-size: 1.5em;">PHỤ TRÁCH ĐƠN HÀNG</b>
                                    </th>
                                </tr>
                                <tr style="background-color: black; color: yellow;">
                                    <th class="text-center">
                                        STT
                                    </th>
                                    <th>
                                        Nhân viên
                                    </th>
                                    <th>
                                        Ngày giao
                                    </th>
                                    <th>
                                        Hạn giao
                                    </th>
                                    <th>
                                        Ngày hoàn thành
                                    </th>
                                    <th class="text-center">
                                        Thi công
                                    </th>
                                </tr>
                                @if($hFunction->checkCount($dataOrderAllocation))
                                    @foreach($dataOrderAllocation as $ordersAllocation)
                                        <?php
                                        $ordersAllocationId = $ordersAllocation->allocationId();
                                        $receiveDeadlineDate = $ordersAllocation->receiveDeadline();
                                        $allocationDate = $ordersAllocation->allocationDate();
                                        $finishDate = $ordersAllocation->finishDate();
                                        $orderId = $ordersAllocation->orderId();
                                        $allocationActivityStatus = $ordersAllocation->checkActivity();
                                        if ($allocationActivityStatus || $ordersAllocation->checkWaitConfirmFinishOfOrder($orderId)) $addAllocationStatus = false;
                                        $dataStaffReceiveManage = $ordersAllocation->receiveStaff;
                                        # anh dai dien
                                        $image = $dataStaffReceiveManage->image();
                                        if ($hFunction->checkEmpty($image)) {
                                            $src = $dataStaffReceiveManage->pathDefaultImage();
                                        } else {
                                            $src = $dataStaffReceiveManage->pathFullImage($image);
                                        }
                                        ?>
                                        <tr>
                                            <td class="text-center">
                                                {!! $n_o = (isset($n_o))?$n_o+1:1 !!}
                                            </td>
                                            <td>
                                                <div class="media">
                                                    <a class="pull-left" href="#">
                                                        <img class="media-object"
                                                             style="max-width: 40px;height: 40px; border: 1px solid #d7d7d7; border-radius: 10px;"
                                                             src="{!! $src !!}">
                                                    </a>

                                                    <div class="media-body">
                                                        <h5 class="media-heading">{!! ucwords($dataStaffReceiveManage->lastName()) !!}</h5>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                {!! date('d/m/Y H:i', strtotime($allocationDate)) !!}
                                            </td>
                                            <td>
                                                {!! date('d/m/Y H:i', strtotime($receiveDeadlineDate)) !!}
                                            </td>
                                            <td>
                                                @if(empty($finishDate))
                                                    <span>---</span>
                                                @else
                                                    {!! date('d/m/Y H:i', strtotime($finishDate)) !!}
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($ordersAllocation->checkWaitConfirmFinish($ordersAllocationId))
                                                    <a class="qc_confirm_finish_get qc-link-red"
                                                       data-href="{!! route('qc.work.work_allocation.order.construction_confirm_finish.get',$ordersAllocationId) !!}">
                                                        XÁC NHẬN
                                                    </a>
                                                    <br/>
                                                    <span style="padding: 3px; background-color: red; color: yellow;">
                                                        Xác nhận thi công
                                                    </span>
                                                @else
                                                    @if(!$allocationActivityStatus)
                                                        @if($ordersAllocation->checkConfirm())
                                                            @if($ordersAllocation->checkConfirmFinish())
                                                                <em class="qc-color-grey">Hoàn thành</em>
                                                            @else
                                                                <em class="qc-color-grey">Không hoàn thành</em>
                                                            @endif
                                                        @else
                                                            @if($ordersAllocation->checkCancelAllocation())
                                                                <span>Đã hủy</span>
                                                            @else
                                                                <a class="qc_confirm_finish qc-link-green">
                                                                    Đã kết thúc
                                                                </a>
                                                            @endif
                                                        @endif
                                                    @else
                                                        <em>Đang thi công</em>
                                                        {{--<br/>
                                                        <a class="qc_construction_cancel qc-link-red"
                                                           data-href="{!! route('qc.work.work_allocation.order.construction.delete',$ordersAllocationId) !!}">
                                                            HỦY BÀN GIAO
                                                        </a>--}}
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">
                                            <span style="padding: 3px; background-color: red; color: white;">Chưa có người phụ trách</span>
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- THI CONG SAN PHAM --}}
        <div class="row">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <label style="color: blue;font-size: 1.5em;">PHÂN VIỆC THI CÔNG SẢN PHẨM</label>
            </div>
        </div>
        @if($hFunction->checkCount($dataProduct))
            <div class="row">
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
                        # thiet ke san pham thi cong
                        $dataProductDesignConstruction = $product->productDesignInfoConstructionHasApply();
                        # san pham da ket thuc hay chua
                        $checkFinishStatus = $product->checkFinishStatus();
                        ?>
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table" style="border: 2px solid #d7d7d7; background-color: whitesmoke;">
                                    <tr>
                                        <td class="text-center" style="width: 150px; background-color: #d7d7d7 ;">
                                            <b style="color: blue; font-size: 1.5em;">
                                                SP_{!! $sp_n_o = (isset($sp_n_o))?$sp_n_o+1:1 !!}
                                            </b>
                                            @if($hFunction->checkCount($dataProductDesign))
                                                <br/>
                                                <a class="qc-link qc_work_order_allocation_product_design_image_view"
                                                   data-href="{!! route('qc.work.work_allocation.order.allocation.product.design_image.view', $dataProductDesign->designId()) !!}">
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
                                            @if(!$checkFinishStatus)
                                                <a class="qc-link-red" title="Triển khai thi công"
                                                   href="{!! route('qc.work.work_allocation.order.product.work-allocation.add.get',$productId) !!}">
                                                    <i class="qc-font-size-16 glyphicon glyphicon-wrench"></i>
                                                    <span class="qc-font-size-14">PHÂN VIỆC</span>
                                                </a>
                                            @else
                                                <i class="glyphicon glyphicon-ok qc-font-size-14"
                                                   style="color: green;"></i>
                                                <span>Đã xong</span>
                                            @endif
                                        </td>
                                        <td style="padding-bottom: 10px;">
                                            @if($hFunction->checkCount($dataProductDesignConstruction))
                                                @foreach($dataProductDesignConstruction as $productDesignConstruction)
                                                    <a class="qc-link qc_work_order_allocation_product_design_image_view"
                                                       data-href="{!! route('qc.work.work_allocation.order.allocation.product.design_image.view',$productDesignConstruction->designId()) !!}">
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
                                        <td colspan="3" style="padding: 0;">
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
                                                                <td style="width: 170px; padding: 3px;">
                                                                    <div class="media">
                                                                        <a class="pull-left" href="#">
                                                                            <img class="media-object"
                                                                                 style="background-color: white; width: 30px;height: 30px; border: 1px solid #d7d7d7;border-radius: 5px;"
                                                                                 src="{!! $src !!}">
                                                                        </a>
                                                                        <div class="media-body">
                                                                            <h5 class="media-heading">{!! ucwords($dataStaffReceive->lastName()) !!}</h5>
                                                                           {{-- @if($workAllocation->checkRoleMain())
                                                                                <em class="qc-color-red">Làm chính</em>
                                                                            @else
                                                                                <em style="color: grey;">Làm phụ</em>
                                                                            @endif--}}
                                                                            @if($workAllocation->checkActivity())
                                                                                <a class="qc_cancel_allocation_product qc-link-red-bold"
                                                                                   title="Hủy giao việc"
                                                                                   data-href="{!! route('qc.work.work_allocation.order.product.work-allocation.cancel.get', $allocationId) !!}">
                                                                                    HỦY
                                                                                </a>
                                                                            @else
                                                                                @if($workAllocation->checkCancel())
                                                                                    <span> | </span>
                                                                                    <em style="color: grey;">Đã hủy</em>
                                                                                @else
                                                                                    <span> | </span>
                                                                                    <em style="color: grey;">Đã kết thúc</em>
                                                                                @endif
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td style="width: 200px; padding: 0;">
                                                                    <em>TG nhận:</em>
                                                                    <span style="color: blue;">
                                                                        {!! $hFunction->convertDateDMYFromDatetime($workAllocation->allocationDate()) !!}
                                                                    </span>
                                                                    &nbsp;
                                                                    <span style="color: blue;">
                                                                        {!! $hFunction->getTimeFromDate($workAllocation->allocationDate()) !!}
                                                                    </span>
                                                                    <br/>
                                                                    <em>Hạn giao:</em>
                                                                    <span style="color: brown;">
                                                                        {!! $hFunction->convertDateDMYFromDatetime($workAllocation->receiveDeadline()) !!}
                                                                    </span>
                                                                    &nbsp;
                                                                    <b>
                                                                        {!! $hFunction->getTimeFromDate($workAllocation->receiveDeadline()) !!}
                                                                    </b>
                                                                    @if($hFunction->checkEmpty($workAllocationNote))
                                                                        <br/>
                                                                        <em style="color: grey;">{!! $workAllocationNote !!}</em>
                                                                    @endif
                                                                </td>
                                                                <td style="padding: 0;">
                                                                    @if($hFunction->checkCount($dataWorkAllocationReport))
                                                                        @foreach($dataWorkAllocationReport as $workAllocationReport)
                                                                            <?php
                                                                            $dataWorkAllocationReportImage = $workAllocationReport->workAllocationReportImageInfo();
                                                                            #bao cao khi bao gio ra
                                                                            $dataTimekeepingProvisionalImage = $workAllocationReport->timekeepingProvisionalImageLastInfo();
                                                                            ?>
                                                                            @foreach($dataWorkAllocationReportImage as $workAllocationReportImage)
                                                                                <a class="qc_work_order_allocation_product_report_image_view qc-link"
                                                                                   title="Click xem chi tiết hình ảnh"
                                                                                   data-href="{!! route('qc.work.work_allocation.order_allocation.product.report_image_direct.view', $workAllocationReportImage->imageId()) !!}">
                                                                                    <img style="margin: 5px; width: 70px; height: 70px; border: 1px solid grey;"
                                                                                         src="{!! $workAllocationReportImage->pathSmallImage($workAllocationReportImage->name()) !!}">
                                                                                </a>
                                                                            @endforeach
                                                                            @if($hFunction->checkCount($dataTimekeepingProvisionalImage))
                                                                                <a class="pull-left qc_work_order_allocation_product_report_image_view qc-link"
                                                                                   title="Click xem chi tiết hình ảnh"
                                                                                   style="margin-right: 5px;"
                                                                                   data-href="{!! route('qc.work.work_allocation.order_allocation.product.report_image_timekeeping.view', $dataTimekeepingProvisionalImage->imageId()) !!}">
                                                                                    <img class="media-object"
                                                                                         style="background-color: white; width: 70px; border: 1px solid grey;"
                                                                                         src="{!! $dataTimekeepingProvisionalImage->pathSmallImage($dataTimekeepingProvisionalImage->name()) !!}">
                                                                                </a>
                                                                            @endif
                                                                            <i class="glyphicon glyphicon-calendar"></i>
                                                                            &nbsp;
                                                                            <b>{!! $hFunction->convertDateDMYHISFromDatetime($workAllocationReport->reportDate()) !!}</b>
                                                                            <br/>
                                                                            <em class="qc-color-grey">- {!! $workAllocationReport->content() !!}</em>
                                                                            <br/>
                                                                            <a class="qc_work_allocation_view qc-link-green-bold"
                                                                               title="Click xem chi tiết thi công"
                                                                               data-href="{!! route('qc.work.work_allocation.order.report.get',$allocationId) !!}">
                                                                                XEM BÁO CÁO
                                                                            </a>
                                                                        @endforeach
                                                                    @else
                                                                        <em class="qc-color-grey">Không có báo cáo</em>
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
                                                                    <em class="qc-color-grey">Chưa triển khai</em>
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
