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
$productId = $dataProduct->productId();
$description = $dataProduct->description();
$dataWorkAllocation = $dataProduct->workAllocationInfoOfProduct();
$role = 1; # mac dinh lam chín
$currentDay = (int)date('d');
$currentMonth = (int)date('m');
$currentYear = (int)date('Y');
$currentHour = (int)date('H');
$currentMinute = (int)date('i');
$currentHour = ($currentHour < 8) ? 8 : $currentHour;
$designImage = $dataProduct->designImage();
# thiet ke dang ap dung
$dataProductDesign = $dataProduct->productDesignInfoApplyActivity();
if ($hFunction->getCountFromData($dataProductDesign) == 0) {
    # thiet ke sau cung
    $dataProductDesign = $dataProduct->productDesignInfoLast();
}
?>
@extends('work.work-allocation.orders.index')
@section('titlePage')
    Sản phẩm - phân việc
@endsection
@section('qc_work_allocation_body')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <a class="qc-link-white-bold btn btn-primary" onclick="qc_main.page_back_go();">
                Về Trang trước
            </a>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <h3 style="color: red;">TRIỂN KHAI THI CÔNG SẢN PHẨM</h3>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            {{-- thông tin sản phảm --}}
            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" style="background-color: #d7d7d7;">
                            <tr>
                                <td>
                                    <label style="font-size: 2em;">{!! $dataProduct->productType->name() !!}</label>
                                    <span class="qc-color-grey">- {!! $dataProduct->order->name() !!}</span>
                                    <br/>
                                    <em>
                                        {!! $dataProduct->width() !!}x{!! $dataProduct->height() !!}mm -
                                        SL: {!! $dataProduct->amount() !!}</em>
                                    @if($hFunction->checkCount($description))
                                        - {!! $description !!}
                                    @endif
                                </td>
                                <td>
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
                                <td>
                                    <em class="qc-color-grey">Thiết kế thi công</em>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                @if($hFunction->checkCount($dataWorkAllocation))
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <tr>
                                    <th colspan="6" style="border: none;">
                                        <i class="glyphicon glyphicon-user qc-font-size-20"></i>
                                        <b style="color: blue; font-size: 1.5em;">ĐÃ PHÂN CÔNG</b>
                                    </th>
                                </tr>
                                <tr style="background-color: black; color: yellow;">
                                    <th class="text-center" style="width:20px;">STT</th>
                                    <th>Nhân viên</th>
                                    <th class="text-center">Ngày nhận</th>
                                    <th class="text-center">Ngày giao</th>
                                    <th>Chi chú</th>
                                    <th class="text-center">Trạng thái</th>
                                </tr>
                                @foreach($dataWorkAllocation as $workAllocation)
                                    <?php
                                    $allocationId = $workAllocation->allocationId();
                                    $dataStaffAllocation = $workAllocation->receiveStaff;
                                    # anh dai dien
                                    $image = $dataStaffAllocation->image();
                                    if ($hFunction->checkEmpty($image)) {
                                        $src = $dataStaffAllocation->pathDefaultImage();
                                    } else {
                                        $src = $dataStaffAllocation->pathFullImage($image);
                                    }
                                    ?>
                                    <tr>
                                        <td class="text-center">
                                            {!! $n_o = (isset($n_o))?$n_o+1: 1 !!}
                                        </td>
                                        <td>
                                            <div class="media">
                                                <a class="pull-left" href="#">
                                                    <img class="media-object" style="max-width: 40px;height: 40px; border: 1px solid #d7d7d7;"
                                                         src="{!! $src !!}">
                                                </a>
                                                <div class="media-body">
                                                    <h5 class="media-heading">{!! $dataStaffAllocation->fullName() !!}</h5>
                                                    @if($workAllocation->checkRoleMain())
                                                        <em class="qc-color-red">Làm chính</em>
                                                    @else
                                                        <em style="color: brown;">Làm phụ</em>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            {!! date('d/m/Y H:j',strtotime($workAllocation->allocationDate())) !!}
                                        </td>
                                        <td class="text-center">
                                            {!! date('d/m/Y H:j', strtotime($workAllocation->receiveDeadline())) !!}
                                        </td>
                                        <td class="qc-color-grey">
                                            {!! $workAllocation->noted() !!}
                                        </td>
                                        <td class="text-center">
                                            @if($workAllocation->checkActivity())
                                                <em>Đang làm</em>
                                                <br/>
                                                <a class="qc_cancel_allocation_product qc-link-red-bold"
                                                   data-href="{!! route('qc.work.work_allocation.order.product.work-allocation.cancel.get', $allocationId) !!}">
                                                    HỦY
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
                            </table>
                        </div>
                    </div>
                @endif
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <form id="frmWorkAllocationOrderProductConstruction" role="form" method="post"
                          enctype="multipart/form-data"
                          action="{!! route('qc.work.work_allocation.order.product.work-allocation.add.post', $productId) !!}">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <tr>
                                    <th colspan="4" style="border: none;">
                                        <i class="glyphicon glyphicon-wrench qc-font-size-20"></i>
                                        <b style="color: red; font-size: 1.5em;">PHÂN CÔNG</b>
                                    </th>
                                </tr>
                                <tr class="text-right">
                                    <td>
                                        Từ:
                                    </td>
                                    <td style="padding: 0;">
                                        <select class="cbDayAllocation text-right col-sx-2 col-sm-2 col-md-2 col-lg-2"
                                                name="cbDayAllocation"
                                                style="padding: 0; height: 34px; color: blue;">
                                            <option value="">Ngày</option>
                                            @for($i = 1;$i<= 31; $i++)
                                                <option value="{!! $i !!}"
                                                        @if($i == $currentDay) selected="selected" @endif >
                                                    {!! $i !!}
                                                </option>
                                            @endfor
                                        </select>
                                        <select class="cbMonthAllocation text-right col-sx-2 col-sm-2 col-md-2 col-lg-2"
                                                name="cbMonthAllocation"
                                                style="padding: 0; height: 34px; color: blue;">
                                            <option value="">Tháng</option>
                                            @for($i = 1;$i<= 12; $i++)
                                                <option value="{!! $i !!}"
                                                        @if($i == $currentMonth) selected="selected" @endif>{!! $i !!}</option>
                                            @endfor
                                        </select>
                                        <select class="cbYearAllocation text-right col-sx-4 col-sm-4 col-md-4 col-lg-4"
                                                name="cbYearAllocation"
                                                style="padding: 0; height: 34px; color: blue;">
                                            <?php
                                            $currentYear = (int)date('Y');
                                            ?>
                                            <option value="{!! $currentYear !!}">{!! $currentYear !!}</option>
                                            <option value="{!! $currentYear + 1 !!}">{!! $currentYear + 1 !!}</option>
                                        </select>
                                        <select class="cbHoursAllocation text-right col-sx-2 col-sm-2 col-md-2 col-lg-2"
                                                name="cbHoursAllocation"
                                                style="padding: 0; height: 34px; color: blue;">
                                            <option value="">Giờ</option>
                                            @for($i =1;$i<= 24; $i++)
                                                <option value="{!! $i !!}"
                                                        @if($i == $currentHour) selected="selected" @endif>
                                                    {!! $i !!}
                                                </option>
                                            @endfor
                                        </select>
                                        <select class="cbMinuteAllocation text-right col-sx-2 col-sm-2 col-md-2 col-lg-2"
                                                name="cbMinuteAllocation"
                                                style="padding: 0; height: 34px; color: blue;">
                                            @for($i =0;$i<= 55; $i = $i+5)
                                                <option value="{!! $i !!}">{!! $i !!}</option>
                                            @endfor
                                        </select>
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                                <tr class="text-right">
                                    <td>
                                        Đến
                                    </td>
                                    <td style="padding: 0;">
                                        <select class="cbDayDeadline text-right col-sx-2 col-sm-2 col-md-2 col-lg-2"
                                                name="cbDayDeadline" style="padding: 0; height: 34px; color: red;">
                                            <option value="">Ngày</option>
                                            @for($i = 1;$i<= 31; $i++)
                                                <option value="{!! $i !!}"
                                                        @if($i == $currentDay) selected="selected" @endif>
                                                    {!! $i !!}
                                                </option>
                                            @endfor
                                        </select>
                                        <select class="cbMonthDeadline text-right col-sx-2 col-sm-2 col-md-2 col-lg-2"
                                                name="cbMonthDeadline" style="padding: 0; height: 34px; color: red;">
                                            <option value="">Tháng</option>
                                            @for($i = 1;$i<= 12; $i++)
                                                <option value="{!! $i !!}"
                                                        @if($i == $currentMonth) selected="selected" @endif>
                                                    {!! $i !!}
                                                </option>
                                            @endfor
                                        </select>
                                        <select class="cbYearDeadline text-right col-sx-4 col-sm-4 col-md-4 col-lg-4"
                                                name="cbYearDeadline"
                                                style="padding: 0; height: 34px; color: red;">
                                            <option value="{!! $currentYear !!}">{!! $currentYear !!}</option>
                                            <option value="{!! $currentYear + 1 !!}">{!! $currentYear + 1 !!}</option>
                                        </select>
                                        <select class="cbHoursDeadline text-right col-sx-2 col-sm-2 col-md-2 col-lg-2"
                                                name="cbHoursDeadline"
                                                style="padding: 0; height: 34px; color: red;">
                                            <option value="">Giờ</option>
                                            @for($i =1;$i<= 24; $i++)
                                                <option value="{!! $i !!}"
                                                        @if($i == $currentHour) selected="selected" @endif>
                                                    {!! $i !!}
                                                </option>
                                            @endfor
                                        </select>
                                        <select class="cbMinuteDeadline text-right col-sx-2 col-sm-2 col-md-2 col-lg-2"
                                                name="cbMinuteDeadline"
                                                style="padding: 0; height: 34px; color: red;">
                                            @for($i =0;$i<= 55; $i = $i+5)
                                                <option value="{!! $i !!}">{!! $i !!}</option>
                                            @endfor
                                        </select>
                                    </td>
                                    </td>
                                    <td colspan="2" style="border: 0;"></td>
                                </tr>
                                <tr style="background-color: black; color: yellow;">
                                    <th class="text-center" style="width:20px;">GIAO</th>
                                    <th>Nhân viên</th>
                                    <th class="text-center">Vai trò</th>
                                    <th style="border: 0;">Nội dung</th>
                                </tr>
                                @if($hFunction->checkCount($dataReceiveStaff))
                                    @foreach($dataReceiveStaff as $receiveStaff)
                                        <?php
                                        $receiveStaffId = $receiveStaff->staffId();
                                        # anh dai dien
                                        $image = $receiveStaff->image();
                                        if ($hFunction->checkEmpty($image)) {
                                            $src = $receiveStaff->pathDefaultImage();
                                        } else {
                                            $src = $receiveStaff->pathFullImage($image);
                                        }
                                        ?>
                                        @if(!$dataProduct->checkStaffReceiveProduct($receiveStaff->staffId(), $dataProduct->productId()))
                                            <tr>
                                                <td class="text-center" style="padding: 0;">
                                                    <input type="checkbox" name="staffReceive[]" checked="checked"
                                                           value="{!! $receiveStaffId !!}">
                                                </td>
                                                <td>
                                                    <div class="media">
                                                        <a class="pull-left" href="#">
                                                            <img class="media-object" style="max-width: 40px;height: 40px; border: 1px solid #d7d7d7;"
                                                                 src="{!! $src !!}">
                                                        </a>
                                                        <div class="media-body">
                                                            <h5 class="media-heading">{!! $receiveStaff->fullName() !!}</h5>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td style="padding: 0;">
                                                    <select class="cbRole text-center form-control"
                                                            name="cbRole_{!! $receiveStaffId !!}">
                                                        <option value="0">Làm phụ</option>
                                                        <option value="1">Làm chính</option>
                                                    </select>
                                                </td>
                                                <td class="text-center" style="padding: 0;">
                                                    <input type="text" class="txtDescription form-control"
                                                           name="txtDescription_{!! $receiveStaffId !!}"
                                                           placeholder="Chú thích công viêc" value="">
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    @if (Session::has('notifyAddAllocation'))
                                        <tr>
                                            <td class="text-center" colspan="7"
                                                style="background-color: red; color: yellow;">
                                                {!! Session::get('notifyAddAllocation') !!}
                                                <?php
                                                Session::forget('notifyAddAllocation');
                                                ?>
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td colspan="7" style="border: none;">
                                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                            <button class="qc_save btn btn-primary form-control">
                                                XÁC NHẬN PHÂN VIỆC
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
