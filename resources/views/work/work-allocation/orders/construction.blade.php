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
                                <tr style="background-color: whitesmoke;">
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
                                                <img style="width: 40px; height: 40px; border: 1px solid #d7d7d7;"
                                                     src="{!! $src !!}">
                                                {!! $dataStaffReceiveManage->fullname() !!}
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
                                                        <br/>
                                                        <a class="qc_construction_cancel qc-link-red"
                                                           data-href="{!! route('qc.work.work_allocation.order.construction.delete',$ordersAllocationId) !!}">
                                                            HỦY BÀN GIAO
                                                        </a>
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
        @if(!$orderFinishStatus)
            @if($addAllocationStatus)
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                         style="background-color: black;color: yellow; font-size: 2em;">
                        <label>BÀN GIAO PHỤ TRÁCH THI CÔNG</label> <label style="color: white;">ĐƠN HÀNG</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-top: 1px dotted #d7d7d7;">
                        <form id="frmWorlAllocationOrderConstructionAdd" role="form" method="post"
                              enctype="multipart/form-data"
                              action="{!! route('qc.work.work_allocation.order.construction.add.post', $orderId) !!}">
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
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <label>Nhân viên phụ trách:</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <select class="cbReceiveStaff form-control" name="cbReceiveStaff">
                                                <option value="">Chọn nhân viên</option>
                                                @if($hFunction->checkCount($dataReceiveStaff))
                                                    @foreach($dataReceiveStaff as $receiveStaff)
                                                        @if($receiveStaff->checkWorkStatus() && $dataStaffLogin->staffId() != $receiveStaff->staffId())
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
                                            <select class="cbAllocationDay text-right col-xs-2 col-sm-2 col-md-2 col-lg-2"
                                                    name="cbAllocationDay" style="height: 34px; padding: 0;">
                                                <option value="">Ngày</option>
                                                @for($i = 1;$i<= 31; $i++)
                                                    <option value="{!! $i !!}"
                                                            @if($i == $currentDay) selected="selected" @endif >
                                                        {!! $i !!}
                                                    </option>
                                                @endfor
                                            </select>
                                            <select class="cbMonthAllocation text-right col-xs-2 col-sm-2 col-md-2 col-lg-2"
                                                    name="cbAllocationMonth" style="height: 34px; padding: 0;">
                                                <option value="">Tháng</option>
                                                @for($m = 1;$m<= 12; $m++)
                                                    <option value="{!! $m !!}"
                                                            @if($m == $currentMonth) selected="selected" @endif>
                                                        {!! $m !!}
                                                    </option>
                                                @endfor
                                            </select>
                                            <select class="cbAllocationYear text-right col-xs-4 col-sm-4 col-md-4 col-lg-4"
                                                    name="cbAllocationYear" style="height: 34px; padding: 0;">
                                                <?php
                                                $currentYear = (int)date('Y');
                                                ?>
                                                <option value="{!! $currentYear !!}">
                                                    {!! $currentYear !!}
                                                </option>
                                                <option value="{!! $currentYear + 1 !!}">
                                                    {!! $currentYear + 1 !!}
                                                </option>
                                            </select>
                                            <select class="cbAllocationHours text-right col-xs-2 col-sm-2 col-md-2 col-lg-2"
                                                    name="cbAllocationHours"
                                                    style="height: 34px; padding: 0; color: red;">
                                                <option value="">Giờ</option>
                                                @for($h =1;$h<= 24; $h++)
                                                    <?php
                                                    $currentHour = ($currentHour < 8) ? 8 : $currentHour;
                                                    ?>
                                                    <option value="{!! $h !!}"
                                                            @if($h == $currentHour) selected="selected" @endif>
                                                        {!! $h !!}
                                                    </option>
                                                @endfor
                                            </select>
                                            <select class="cbAllocationMinute text-right col-xs-2 col-sm-2 col-md-2 col-lg-2"
                                                    name="cbAllocationMinute"
                                                    style="height: 34px; padding: 0; color: red;">
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
                                            <select class="cbDeadlineDay text-right col-xs-2 col-sm-2 col-md-2 col-lg-2"
                                                    name="cbDeadlineDay" style=" height: 34px; padding: 0; ">
                                                <option value="">Ngày</option>
                                                @for($dd = 1;$dd<= 31; $dd++)
                                                    <option value="{!! $dd !!}"
                                                            @if($dd == $currentDay) selected="selected" @endif>
                                                        {!! $dd !!}
                                                    </option>
                                                @endfor
                                            </select>
                                            <select class="cbDeadlineMonth text-right col-xs-2 col-sm-2 col-md-2 col-lg-2"
                                                    name="cbDeadlineMonth" style="height: 34px; padding: 0;">
                                                <option value="">Tháng</option>
                                                @for($i = 1;$i<= 12; $i++)
                                                    <option value="{!! $i !!}"
                                                            @if($i == $currentMonth) selected="selected" @endif>
                                                        {!! $i !!}
                                                    </option>
                                                @endfor
                                            </select>
                                            <select class="cbDeadlineYear text-right col-xs-4 col-sm-4 col-md-4 col-lg-4"
                                                    name="cbDeadlineYear" style="height: 34px; padding: 0;">
                                                <option value="{!! $currentYear !!}">{!! $currentYear !!}</option>
                                                <option value="{!! $currentYear + 1 !!}">{!! $currentYear + 1 !!}</option>
                                            </select>
                                            <select class="cbDeadlineHours text-right col-xs-2 col-sm-2 col-md-2 col-lg-2"
                                                    name="cbDeadlineHours"
                                                    style="height: 34px; padding: 0; color: red;">
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
                                            <select class="cbDeadlineMinute text-right col-xs-2 col-sm-2 col-md-2 col-lg-2"
                                                    name="cbDeadlineMinute"
                                                    style="height: 34px; padding: 0; color: red;">
                                                @for($i =0;$i<= 55; $i = $i+5)
                                                    <option value="{!! $i !!}">{!! $i !!}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="qc-padding-top-20 qc-padding-bot-20  qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                    <button type="submit" class="qc_save btn btn-primary btn-sm"> BÀN GIAO</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                         style="padding-top: 10px; padding-bottom: 10px;">
                        <i class="qc-color-green glyphicon glyphicon-bullhorn"></i>&nbsp;&nbsp;
                        <b style="color: red;">
                            Công trình đang được bàn giao. Chỉ được bàn giao khi công trình đang không có
                            người phụ trách
                        </b>
                    </div>
                    @if($dataOrder->existOrderAllocationFinishOfOrder())
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                             style="padding-top: 5px; padding-bottom: 10px;">
                            <span style="padding: 5px; background-color: blue; color: white;">Chờ bàn giao khách hàng</span>
                        </div>
                    @endif
                </div>
            @endif
        @endif

        {{-- THI CONG SAN PHAM --}}
        <div class="row">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
                 style="background-color: black;color: yellow; font-size: 2em;">
                <label>PHÂN VIỆC THI CÔNG</label> <label style="color: white;">SẢN PHẨM</label>
            </div>
        </div>
        @if($hFunction->checkCount($dataProduct))
            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
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
                                <table class="table" style="border: 1px solid black;">
                                    <tr>
                                        <td style="width:50px;">
                                            SP_{!! $sp_n_o = (isset($sp_n_o))?$sp_n_o+1:1 !!}
                                        </td>
                                        <td style="border: 1px solid #d7d7d7;">
                                            {!! ucwords($product->productType->name()) !!}
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
                                        <td class="text-left" style="border: 1px solid #d7d7d7;">
                                            <em>{!! $product->description() !!}</em>
                                        </td>
                                        <td>
                                            <em class="qc-color-grey">Thiết kế thi công</em>
                                        </td>
                                        <td class="text-right" style="border: 1px solid #d7d7d7;">
                                            @if(!$checkFinishStatus)
                                                <a class="qc-link-red" title="Triển khai thi công"
                                                   href="{!! route('qc.work.work_allocation.order.product.work-allocation.add.get',$productId) !!}">
                                                    <i class="qc-font-size-16 glyphicon glyphicon-wrench"></i>
                                                    <span class="qc-font-size-14">PHÂN VIỆC</span>
                                                </a>
                                            @else
                                                <span>Đã xong</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" style="padding: 0;">
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
                                                                    <img style="max-width: 40px;height: 40px; border: 1px solid #d7d7d7;"
                                                                         src="{!! $src !!}">
                                                                    {!! ucwords($dataStaffReceive->lastName()) !!}
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
                                                                            <br/>
                                                                            <a class="qc_work_allocation_view qc-link-green-bold"
                                                                               title="Click xem chi tiết thi công"
                                                                               data-href="{!! route('qc.work.work_allocation.order.work_allocation.get',$allocationId) !!}">
                                                                                XEM BÁO CÁO
                                                                            </a>
                                                                        @endforeach
                                                                    @else
                                                                        <em class="qc-color-grey">Không có báo cáo</em>
                                                                    @endif
                                                                </td>
                                                                <td class="text-right">
                                                                    @if($workAllocation->checkActivity())
                                                                        <em style="color: black;">Đang thi công</em>
                                                                        <span>&nbsp;|&nbsp;</span>
                                                                        <a class="qc_cancel_allocation_product qc-link-red-bold"
                                                                           title="Hủy giao việc"
                                                                           data-href="{!! route('qc.work.work_allocation.order.product.work-allocation.cancel.get', $allocationId) !!}">
                                                                            Hủy
                                                                        </a>
                                                                    @else
                                                                        @if($workAllocation->checkCancel())
                                                                            <em style="color: grey;">Đã hủy</em>
                                                                        @else
                                                                            <em style="color: grey;">Đã kết thúc</em>
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
