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
$dataOrderConstruction = $dataOrder->orderAllocationActivity();

$orderId = $dataOrder->orderId();
$deliveryDate = $dataOrder->deliveryDate();
$deliveryDay = $hFunction->getDayFromDate($deliveryDate);
$deliveryMonth = $hFunction->getMonthFromDate($deliveryDate);
$deliveryYear = $hFunction->getYearFromDate($deliveryDate);
$deliveryHour = $hFunction->getHourFromDate($deliveryDate);
# thong tin ban giao
$dataOrderAllocation = $dataOrder->orderAllocationInfoAll();
$addAllocationStatus = true; // trang thai duoc bang giao hoac khong

$currentDate = $hFunction->currentDay();
$currentDay = $hFunction->currentDay();
$currentMonth = $hFunction->currentMonth();
$currentYear = $hFunction->currentYear();
$currentHour = $hFunction->currentHour();

# thong tin san pham va thi cong
$dataProduct = $dataOrder->productActivityOfOrder();

?>
@extends('work.work-allocation.index')
@section('titlePage')
    Bàn giao công trình và triển khai thi công
@endsection
@section('qc_work_allocation_body')
    <div id="qc_work_allocation_manage_order_construction_wrap" class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
         style="padding-bottom: 50px;">
        <div class="row">
            <div class="qc-border-none col-sx-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 0;">
                <a class="qc-font-size-20 qc-link-red" href="{!! route('qc.work.work_allocation.manage.get') !!}">
                    <i class="glyphicon glyphicon-backward"></i>
                    Trở lại
                </a>
            </div>
        </div>
        {{-- BÀN GIAO CÔNG TRÌNH --}}
        <div class="row">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <h3 style="color: red;">TRIỂN KHAI THI CÔNG</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    {{-- thông tin đơn hàng --}}
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-5">
                        <div class="table-responsive">
                            <table class="table table-hover qc-margin-bot-none">
                                <tr>
                                    <td>
                                        <em class=" qc-color-grey">Đơn hàng</em>
                                    </td>
                                    <td class="text-right">
                                        <b>{!! $dataOrder->name() !!}</b>
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
                            <table class="table table-hover qc-margin-bot-none">
                                <tr style="background-color: whitesmoke;">
                                    <th colspan="6">
                                        <i class="qc-font-size-16 glyphicon glyphicon-user"></i>
                                        <b class="qc-color-red">PHỤ TRÁCH ĐƠN HÀNG</b>
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
                                        Ngày nhận
                                    </th>
                                    <th>
                                        Ngày giao
                                    </th>
                                    <th class="text-center">
                                        Thi công
                                    </th>
                                    <th class="text-center">
                                        Hoạt động
                                    </th>
                                </tr>
                                @if($hFunction->checkCount($dataOrderAllocation))
                                    @foreach($dataOrderAllocation as $ordersAllocation)
                                        <?php
                                        $ordersAllocationId = $ordersAllocation->allocationId();
                                        $allocationActivityStatus = $ordersAllocation->checkActivity();
                                        if ($allocationActivityStatus) $addAllocationStatus = false;
                                        ?>
                                        <tr>
                                            <td class="text-center">
                                                {!! $n_o = (isset($n_o))?$n_o+1:1 !!}
                                            </td>
                                            <td>
                                                {!! $ordersAllocation->receiveStaff->fullname() !!}
                                            </td>
                                            <td>
                                                {!! $hFunction->convertDateDMYHISFromDatetime($ordersAllocation->allocationDate()) !!}
                                            </td>
                                            <td>
                                                {!! $hFunction->convertDateDMYHISFromDatetime($ordersAllocation->receiveDeadline()) !!}
                                            </td>
                                            <td class="text-center">
                                                @if(!$allocationActivityStatus)
                                                    @if($ordersAllocation->checkConfirm())
                                                        @if($ordersAllocation->checkConfirmFinish())
                                                            <em class="qc-color-grey">Đã kết thúc</em>
                                                        @else
                                                            <em class="qc-color-grey">Không hoàn thành</em>
                                                        @endif
                                                    @else
                                                        <a class="qc_confirm_finish qc-link-green">
                                                            Đã kết thúc
                                                        </a>
                                                    @endif
                                                @else
                                                    <em>Đang thi công</em>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($allocationActivityStatus)
                                                    <a class="qc_delete_construction qc-link-red"
                                                       data-href="{!! route('qc.work.work_allocation.manage.order.construction.delete',$ordersAllocationId) !!}">
                                                        Hủy bàn giao
                                                    </a>
                                                @else
                                                    <em class="qc-color-grey">Đã hủy bàn giao</em>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-center" colspan="5">
                                            <em class="qc-color-red">Chưa có người phụ trách</em>
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if($dataOrder->checkFinishStatus())
            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-top: 1px dotted #d7d7d7;">
                    <span style="padding: 3px; background-color: red; color: yellow; ">Đơn hàng đã kết thúc</span>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="background-color: black;color: yellow;">
                    <h5>BÀN GIAO CHO NHÂN VIÊN PHỤ TRÁCH THI CÔNG:</h5>
                </div>
            </div>
            @if($addAllocationStatus)
                <div class="row">
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-top: 1px dotted #d7d7d7;">
                        <form id="frmWorlAllocationOrderConstructionAdd" role="form" method="post"
                              enctype="multipart/form-data"
                              action="{!! route('qc.work.work_allocation.manage.order.construction.add.post', $orderId) !!}">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    @if (Session::has('notifyAdd'))
                                        <div class="form-group form-group-sm text-center qc-color-red">
                                            <span class="qc-font-size-16">{!! Session::get('notifyAdd') !!}</span>
                                            <?php
                                            Session::forget('notifyAdd');
                                            ?>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <label>Nhân viên phụ trách</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <select class="cbReceiveStaff form-control" name="cbReceiveStaff"
                                                style="height: 30px;">
                                            <option value="">Chọn nhân viên</option>
                                            @if($hFunction->checkCount($dataReceiveStaff))
                                                @foreach($dataReceiveStaff as $receiveStaff)
                                                    @if($receiveStaff->checkWorkStatus())
                                                        <option value="{!! $receiveStaff->staffId() !!}">{!! $receiveStaff->fullName() !!}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <label>Thời gian nhận: </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <select class="cbAllocationDay" name="cbAllocationDay"
                                                style="margin-top: 5px; height: 25px;">
                                            <option value="">Ngày</option>
                                            @for($i = 1;$i<= 31; $i++)
                                                <option value="{!! $i !!}"
                                                        @if($i == $currentDay) selected="selected" @endif >
                                                    {!! $i !!}
                                                </option>
                                            @endfor
                                        </select>
                                        <span>/</span>
                                        <select class="cbMonthAllocation" name="cbAllocationMonth"
                                                style="margin-top: 5px; height: 25px;">
                                            <option value="">Tháng</option>
                                            @for($m = 1;$m<= 12; $m++)
                                                <option value="{!! $m !!}"
                                                        @if($m == $currentMonth) selected="selected" @endif>{!! $m !!}</option>
                                            @endfor
                                        </select>
                                        <span>/</span>
                                        <select class="cbAllocationYear" name="cbAllocationYear"
                                                style="margin-top: 5px; height: 25px;">
                                            <?php
                                            $currentYear = (int)date('Y');
                                            ?>
                                            <option value="{!! $currentYear !!}">{!! $currentYear !!}</option>
                                            <option value="{!! $currentYear + 1 !!}">{!! $currentYear + 1 !!}</option>
                                        </select>
                                        <select class="cbAllocationHours" name="cbAllocationHours"
                                                style="margin-top: 5px; height: 25px;">
                                            <option value="">Giờ</option>
                                            @for($i =1;$i<= 24; $i++)
                                                <?php
                                                $currentHour = ($currentHour < 8) ? 8 : $currentHour;
                                                ?>
                                                <option value="{!! $i !!}"
                                                        @if($i == $currentHour) selected="selected" @endif>
                                                    {!! $i !!}
                                                </option>
                                            @endfor
                                        </select>
                                        <span>:</span>
                                        <select class="cbAllocationMinute" name="cbAllocationMinute"
                                                style="margin-top: 5px; height: 25px;">
                                            @for($i =0;$i<= 50; $i = $i+10)
                                                <option value="{!! $i !!}">{!! $i !!}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <label>Thời gian giao: </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <select class="cbDeadlineDay" name="cbDeadlineDay"
                                                style="margin-top: 5px; height: 25px;">
                                            <option value="">Ngày</option>
                                            @for($i = 1;$i<= 31; $i++)
                                                <option value="{!! $i !!}"
                                                        @if($i == $currentDay) selected="selected" @endif>
                                                    {!! $i !!}
                                                </option>
                                            @endfor
                                        </select>
                                        <span>/</span>
                                        <select class="cbDeadlineMonth" name="cbDeadlineMonth"
                                                style="margin-top: 5px; height: 25px;">
                                            <option value="">Tháng</option>
                                            @for($i = 1;$i<= 12; $i++)
                                                <option value="{!! $i !!}"
                                                        @if($i == $currentMonth) selected="selected" @endif>
                                                    {!! $i !!}
                                                </option>
                                            @endfor
                                        </select>
                                        <span>/</span>
                                        <select class="cbDeadlineYear" name="cbDeadlineYear"
                                                style="margin-top: 5px; height: 25px;">
                                            <option value="{!! $currentYear !!}">{!! $currentYear !!}</option>
                                            <option value="{!! $currentYear + 1 !!}">{!! $currentYear + 1 !!}</option>
                                        </select>
                                        <select class="cbDeadlineHours" name="cbDeadlineHours"
                                                style="margin-top: 5px; height: 25px;">
                                            <option value="">Giờ</option>
                                            @for($i =1;$i<= 24; $i++)
                                                <?php
                                                $currentHour = ($currentHour < 8) ? 8 : $currentHour;
                                                ?>
                                                <option value="{!! $i !!}"
                                                        @if($i == $currentHour) selected="selected" @endif>
                                                    {!! $i !!}
                                                </option>
                                            @endfor
                                        </select>
                                        <span>:</span>
                                        <select class="cbDeadlineMinute" name="cbDeadlineMinute"
                                                style="margin-top: 5px; height: 25px;">
                                            @for($i =0;$i<= 55; $i = $i+5)
                                                <option value="{!! $i !!}">{!! $i !!}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="qc-padding-top-20 qc-padding-bot-20  qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                    <button type="submit" class="qc_save btn btn-primary btn-sm"> Giao</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <i class="qc-color-green glyphicon glyphicon-bullhorn"></i>&nbsp;&nbsp;
                        <span> Công trình đang được bàn giao</span> <em>(Chỉ được bàn giao khi công trình đang không có
                            người phụ trách)</em>
                    </div>
                </div>
            @endif
        @endif

        {{-- THI CONG SAN PHAM --}}
        <div class="row">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="background-color: black;color: yellow;">
                <h5>PHÂN VIỆC THI CÔNG SẢN PHẨM</h5>
            </div>
        </div>
        @if($hFunction->checkCount($dataProduct))
            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-hover qc-margin-bot-none">
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
                                    ?>
                                    <tr style="color: brown;">
                                        <td style="width:50px; border: 1px solid #d7d7d7;">
                                            SP_{!! $sp_n_o = (isset($sp_n_o))?$sp_n_o+1:1 !!}
                                        </td>
                                        <td style="border: 1px solid #d7d7d7;">
                                            {!! ucwords($product->productType->name()) !!}
                                        </td>
                                        <td class="text-center" style="padding-bottom: 10px;">
                                            @if($hFunction->checkCount($dataProductDesign))
                                                <em class="qc-color-grey">Thiết kế SP</em> <br/>
                                                @if($dataProductDesign->checkApplyStatus())
                                                    <img style="width: 70px; height: auto; margin: 5px;"
                                                         title="Đang áp dụng"
                                                         src="{!! $dataProductDesign->pathSmallImage($dataProductDesign->image()) !!}">
                                                @else
                                                    <img style="width: 70px; height: 70px; margin-bottom: 5px;"
                                                         title="Không được áp dụng"
                                                         src="{!! $dataProductDesign->pathSmallImage($dataProductDesign->image()) !!}">
                                                @endif
                                            @else
                                                @if(!$hFunction->checkEmpty($designImage))
                                                    <img style="width: 70px; height: 70px; margin: 5px; "
                                                         src="{!! $product->pathSmallDesignImage($designImage) !!}">
                                                @else
                                                    <em class="qc-color-grey">Gửi thiết kế sau</em>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-left" style="border: 1px solid #d7d7d7;">
                                            <em>{!! $product->description() !!}</em>
                                        </td>
                                        <td class="text-center">
                                            <em class="qc-color-grey">Thiết kế thi công</em>
                                        </td>
                                        <td class="text-right" style="border: 1px solid #d7d7d7;">
                                            <a class="qc-link-red" title="Triển khai thi công"
                                               href="{!! route('qc.work.work_allocation.manage.order.product.work-allocation.add.get',$productId) !!}">
                                                <i class="qc-font-size-16 glyphicon glyphicon-wrench"></i>
                                                <span class="qc-font-size-14">PHÂN VIỆC</span>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: grey;"></td>
                                        <td colspan="5">
                                            <div class="table-responsive">
                                                <table class="table" style="margin-bottom: 0;">
                                                    @if($hFunction->checkCount($dataWorkAllocation))
                                                        @foreach($dataWorkAllocation as $workAllocation)
                                                            <?php
                                                            $allocationId = $workAllocation->allocationId();
                                                            # bao cao tien do
                                                            $dataWorkAllocationReport = $workAllocation->workAllocationReportInfo($allocationId, 1);
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    <i class="qc-color-grey qc-font-size-14 glyphicon glyphicon-user"></i>&nbsp;
                                                                    {!! ucwords($workAllocation->receiveStaff->lastName()) !!}
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
                                                                <td class="text-right">
                                                                    @if($workAllocation->checkActivity())
                                                                        <em style="color: black;">Đang thi công</em>
                                                                        <span>&nbsp;|&nbsp;</span>
                                                                        <a class="qc_cancel_allocation_product qc-link-red-bold"
                                                                           title="Hủy giao việc"
                                                                           data-href="{!! route('qc.work.work_allocation.manage.order.product.work-allocation.cancel.get', $allocationId) !!}">
                                                                            Xóa
                                                                        </a>
                                                                    @else
                                                                        @if($workAllocation->checkCancel())
                                                                            <em style="color: grey;">Đã hủy</em>
                                                                        @else
                                                                            <em style="color: grey;">Đã kết thúc</em>
                                                                        @endif
                                                                    @endif
                                                                    <span>&nbsp;|&nbsp;</span>
                                                                    <a class="qc_work_allocation_view qc-link-green-bold"
                                                                       title="Click xem chi tiết thi công"
                                                                       data-href="{!! route('qc.work.work_allocation.manage.order.work_allocation.get',$allocationId) !!}">
                                                                        Xem chi tiết
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                            @if($hFunction->checkCount($dataWorkAllocationReport))
                                                                @foreach($dataWorkAllocationReport as $workAllocationReport)
                                                                    <?php
                                                                    $dataWorkAllocationReportImage = $workAllocationReport->workAllocationReportImageInfo();
                                                                    #bao cao khi bao gio ra
                                                                    $dataTimekeepingProvisionalImage = $workAllocationReport->timekeepingProvisionalImageInfo();
                                                                    ?>
                                                                    <tr>
                                                                        <td style="background-color: whitesmoke;"></td>
                                                                        <td colspan="2">
                                                                            <i class="glyphicon glyphicon-calendar"></i>&nbsp;
                                                                            <b>{!! $hFunction->convertDateDMYHISFromDatetime($workAllocationReport->reportDate()) !!}</b>
                                                                            <br/>
                                                                            <em class="qc-color-grey">- {!! $workAllocationReport->content() !!}</em>
                                                                        </td>
                                                                        <td colspan="2">
                                                                            @foreach($dataWorkAllocationReportImage as $workAllocationReportImage)
                                                                                <div style="position: relative; float: left; margin: 5px; width: 70px; height: 70px; border: 1px solid #d7d7d7;">
                                                                                    <a class="qc_work_allocation_report_image_view qc-link"
                                                                                       title="Click xem chi tiết hình ảnh"
                                                                                       data-href="{!! route('qc.work.work_allocation.manage.order.allocation.report_image.get', $workAllocationReportImage->imageId()) !!}">
                                                                                        <img style="max-width: 100%; max-height: 100%;"
                                                                                             src="{!! $workAllocationReportImage->pathSmallImage($workAllocationReportImage->name()) !!}">
                                                                                    </a>
                                                                                </div>
                                                                            @endforeach
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @else
                                                                <tr>
                                                                    <td style=" background-color: whitesmoke;"></td>
                                                                    <td colspan="4">
                                                                        <em class="qc-color-grey">Không có báo cáo</em>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="5">
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
                                @endforeach
                            </table>
                        </div>
                    </div>
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
