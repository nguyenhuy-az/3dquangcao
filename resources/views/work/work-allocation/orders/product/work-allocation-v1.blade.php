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
$urlReferer = $hFunction->getUrlReferer();
$mobileStatus = $mobile->isMobile();
$dataWorkAllocation = $dataProduct->workAllocationInfoOfProduct();
$role = 1; # mac dinh lam chín
$currentDay = (int)date('d');
$currentMonth = (int)date('m');
$currentYear = (int)date('Y');
$currentHour = (int)date('H');
$currentMinute = (int)date('i');
$designImage = $dataProduct->designImage();
# thiet ke dang ap dung
$dataProductDesign = $dataProduct->productDesignInfoApplyActivity();
if ($hFunction->getCountFromData($dataProductDesign) == 0) {
    # thiet ke sau cung
    $dataProductDesign = $dataProduct->productDesignInfoLast();
}
?>
@extends('work.work-allocation.index')
@section('titlePage')
    Sản phẩm - phân việc
@endsection
@section('qc_work_allocation_body')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <a class="qc-link-white-bold btn btn-primary" href="{!! $hFunction->getUrlReferer() !!}">
                Về Trang trước
            </a>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dotted brown;">
            <h3 style="color: red;">TRIỂN KHAI THI CÔNG</h3>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            {{-- thông tin sản phảm --}}
            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr>
                                <td>
                                    <h3>{!! $dataProduct->productType->name() !!}</h3>
                                    <em>{!! $dataProduct->width() !!}x{!! $dataProduct->height() !!}mm -
                                        SL: {!! $dataProduct->amount() !!}</em>
                                    <span class="qc-color-grey">- {!! $dataProduct->order->name() !!}</span>
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
                                    <th colspan="7" style="border: none;">
                                        <i class="glyphicon glyphicon-user qc-font-size-20"></i>
                                        <b style="color: blue; font-size: 1.5em;">ĐÃ PHÂN CÔNG</b>
                                    </th>
                                </tr>
                                <tr style="background-color: black; color: yellow;">
                                    <th class="text-center" style="width:20px;">STT</th>
                                    <th>Nhân viên</th>
                                    <th class="text-center">Ngày nhận</th>
                                    <th class="text-center">Ngày giao</th>
                                    <th class="text-center">Vai trò</th>
                                    <th>Chi chú</th>
                                    <th class="text-center">Trạng thái</th>
                                </tr>
                                @foreach($dataWorkAllocation as $workAllocation)
                                    <tr>
                                        <td class="text-center">
                                            {!! $n_o = (isset($n_o))?$n_o+1: 1 !!}
                                        </td>
                                        <td>
                                            {!! $workAllocation->receiveStaff->fullName() !!}
                                        </td>
                                        <td class="text-center">
                                            {!! date('d/m/Y H:j',strtotime($workAllocation->allocationDate())) !!}
                                        </td>
                                        <td class="text-center">
                                            {!! date('d/m/Y H:j', strtotime($workAllocation->receiveDeadline())) !!}
                                        </td>
                                        <td class="text-center">
                                            @if($workAllocation->checkRoleMain())
                                                <em class="qc-color-red">Làm chính</em>
                                            @else
                                                <em>Làm phụ</em>
                                            @endif
                                        </td>
                                        <td class="qc-color-grey">
                                            {!! $workAllocation->noted() !!}
                                        </td>
                                        <td class="text-center">
                                            @if($workAllocation->checkActivity())
                                                <em>Đang làm</em>
                                            @else
                                                <?php
                                                $workAllocationFinish = $workAllocation->workAllocationFinishInfo()
                                                ?>
                                                @if($hFunction->checkCount($workAllocationFinish) && $workAllocationFinish->checkSystemCancel())
                                                    <em class="qc-color-red">Đã hủy</em>
                                                @else
                                                    <em class="qc-color-red">Xong</em>
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
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr>
                                <th colspan="7" style="border: none;">
                                    <i class="glyphicon glyphicon-wrench qc-font-size-20"></i>
                                    <b style="color: blue; font-size: 1.5em;">PHÂN CÔNG</b>
                                </th>
                            </tr>
                            <tr style="background-color: black; color: yellow;">
                                <th class="text-center" style="width:20px;">STT</th>
                                <th>Nhân viên</th>
                                <th class="text-center">Thời gian nhận</th>
                                <th class="text-center">Thời gian bàn giao</th>
                                <th class="text-center">Vai trò</th>
                                <th>Nội dung</th>
                                <th class="text-center">Giao</th>
                            </tr>
                            @if($hFunction->checkCount($dataReceiveStaff))
                                @foreach($dataReceiveStaff as $receiveStaff)
                                    @if(!$dataProduct->checkStaffReceiveProduct($receiveStaff->staffId(), $dataProduct->productId()))
                                        <tr>
                                            <td class="text-center">
                                                {!! $n_o_add = (isset($n_o_add))?$n_o_add+1: 1 !!}
                                            </td>
                                            <td>
                                                {!! $receiveStaff->fullName() !!}
                                            </td>
                                            <td style="padding: 0; width: 200px;">
                                                <select class="cbDayAllocation col-sx-2 col-sm-2 col-md-2 col-lg-2"
                                                        name="cbDayAllocation[]"
                                                        style="padding: 0; height: 34px;">
                                                    <option value="">Ngày</option>
                                                    @for($i = 1;$i<= 31; $i++)
                                                        <option value="{!! $i !!}"
                                                                @if($i == $currentDay) selected="selected" @endif >{!! $i !!}</option>
                                                    @endfor
                                                </select>
                                                <select class="cbMonthAllocation col-sx-2 col-sm-2 col-md-2 col-lg-2"
                                                        name="cbMonthAllocation[]"
                                                        style="padding: 0; height: 34px;">
                                                    <option value="">Tháng</option>
                                                    @for($i = 1;$i<= 12; $i++)
                                                        <option value="{!! $i !!}"
                                                                @if($i == $currentMonth) selected="selected" @endif>{!! $i !!}</option>
                                                    @endfor
                                                </select>
                                                <select class="cbYearAllocation col-sx-4 col-sm-4 col-md-4 col-lg-4"
                                                        name="cbYearAllocation[]"
                                                        style="padding: 0; height: 34px;">
                                                    <?php
                                                    $currentYear = (int)date('Y');
                                                    ?>
                                                    <option value="{!! $currentYear !!}">{!! $currentYear !!}</option>
                                                    <option value="{!! $currentYear + 1 !!}">{!! $currentYear + 1 !!}</option>
                                                </select>
                                                <select class="cbHoursAllocation col-sx-2 col-sm-2 col-md-2 col-lg-2"
                                                        name="cbHoursAllocation[]"
                                                        style="padding: 0; height: 34px; color: red;">
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
                                                <select class="cbMinuteAllocation col-sx-2 col-sm-2 col-md-2 col-lg-2"
                                                        name="cbMinuteAllocation[]"
                                                        style="padding: 0; height: 34px; color: red;">
                                                    @for($i =0;$i<= 55; $i = $i+5)
                                                        <option value="{!! $i !!}">{!! $i !!}</option>
                                                    @endfor
                                                </select>
                                            </td>
                                            <td style="padding: 0; width: 200px;">
                                                <select class="cbDayDeadline col-sx-2 col-sm-2 col-md-2 col-lg-2"
                                                        name="cbDayDeadline[]" style="padding: 0; height: 34px;">
                                                    <option value="">Ngày</option>
                                                    @for($i = 1;$i<= 31; $i++)
                                                        <option value="{!! $i !!}"
                                                                @if($i == $currentDay) selected="selected" @endif>
                                                            {!! $i !!}
                                                        </option>
                                                    @endfor
                                                </select>
                                                <select class="cbMonthDeadline col-sx-2 col-sm-2 col-md-2 col-lg-2"
                                                        name="cbMonthDeadline[]" style="padding: 0; height: 34px;">
                                                    <option value="">Tháng</option>
                                                    @for($i = 1;$i<= 12; $i++)
                                                        <option value="{!! $i !!}"
                                                                @if($i == $currentMonth) selected="selected" @endif>
                                                            {!! $i !!}
                                                        </option>
                                                    @endfor
                                                </select>
                                                <select class="cbYearDeadline col-sx-4 col-sm-4 col-md-4 col-lg-4"
                                                        name="cbYearDeadline[]" style="padding: 0; height: 34px;">
                                                    <option value="{!! $currentYear !!}">{!! $currentYear !!}</option>
                                                    <option value="{!! $currentYear + 1 !!}">{!! $currentYear + 1 !!}</option>
                                                </select>
                                                <select class="cbHoursDeadline col-sx-2 col-sm-2 col-md-2 col-lg-2"
                                                        name="cbHoursDeadline[]"
                                                        style="padding: 0; height: 34px; color: red;">
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
                                                <select class="cbMinuteDeadline col-sx-2 col-sm-2 col-md-2 col-lg-2"
                                                        name="cbMinuteDeadline[]"
                                                        style="padding: 0; height: 34px; color: red;">
                                                    @for($i =0;$i<= 55; $i = $i+5)
                                                        <option value="{!! $i !!}">{!! $i !!}</option>
                                                    @endfor
                                                </select>
                                            </td>
                                            <td style="padding: 0;">
                                                <select class="cbRole form-control" name="cbRole[]">
                                                    <option value="0">Làm phụ</option>
                                                    <option value="1">Làm chính</option>
                                                </select>
                                            </td>
                                            <td class="text-center" style="padding: 0;">
                                                <input type="text" class="txtDescription form-control"
                                                       name="txtDescription[]"
                                                       placeholder="Chú thích công viêc" value="">
                                            </td>
                                            <td class="text-center" style="padding: 0;">
                                                <input type="checkbox" name="allocationStatus[]" checked="checked">
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12"
                         style="border-bottom: 1px solid grey ;">
                        <i class="glyphicon glyphicon-wrench qc-font-size-20"></i>
                        <label class="qc-font-size-20" style="color: brown;">Phân công</label>
                    </div>
                </div>
                @if($dataProduct->checkCancelStatus())
                    <div class="qc-padding-top-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <b style="color: brown;">Sản phẩm đã hủy</b>
                    </div>
                @else
                    @if($dataProduct->checkFinishStatus())
                        <div class="qc-padding-top-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <b style="color: red;">Sản phẩm Đã hoàn thành</b>
                        </div>
                    @else
                        <form id="frmWorkAllocationManageProductConstruction" role="form" method="post"
                              enctype="multipart/form-data"
                              action="{!! route('qc.work.work_allocation.manage.product.work-allocation.add.post', $dataProduct->productId()) !!}">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    @if (Session::has('notifyAddAllocation'))
                                        <div class="form-group form-group-sm text-center qc-color-red">
                                            {!! Session::get('notifyAddAllocation') !!}
                                            <?php
                                            Session::forget('notifyAddAllocation');
                                            ?>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class=" col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <div id="qc_product_work_allocation_staff_wrap" class="row">
                                    {{-- noi dung phan viec vai tro chinh --}}
                                    @if(!$dataProduct->existMaimRoleWorkAllocationActivity())
                                        <div class="qc_work_allocation_product_work_allocation_staff_add qc-margin-top-20 col-xs-12 col-sm-12 col-md-12 col-lg-12"
                                             style="border: 1px solid #d7d7d7; border-left: 5px solid red;">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6"
                                                         style="padding: 5px 0 5px 0;">
                                                        <select class="cbReceiveStaff" name="cbReceiveStaff[]">
                                                            <option value="">Chọn nhân viên</option>
                                                            @if(count($dataReceiveStaff) > 0)
                                                                @foreach($dataReceiveStaff as $receiveStaff)
                                                                    @if(!$dataProduct->checkStaffReceiveProduct($receiveStaff->staffId(), $dataProduct->productId()))
                                                                        <option value="{!! $receiveStaff->staffId() !!}">{!! $receiveStaff->fullName() !!}</option>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6"
                                                         style="padding: 5px 0 5px 0;">
                                                        <select class="cbRole qc-color-red" name="cbRole[]">
                                                            <option value="1">Làm chính</option>
                                                        </select>
                                                        <em class="qc-color-red">(Phụ trách SP)</em>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                    <div class="row">
                                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                            <label>Thời gian nhận: </label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                            <select class="cbDayAllocation" name="cbDayAllocation[]"
                                                                    style="margin-top: 5px; height: 25px;">
                                                                <option value="">Ngày</option>
                                                                @for($i = 1;$i<= 31; $i++)
                                                                    <option value="{!! $i !!}"
                                                                            @if($i == $currentDay) selected="selected" @endif >{!! $i !!}</option>
                                                                @endfor
                                                            </select>
                                                            <span>/</span>
                                                            <select class="cbMonthAllocation" name="cbMonthAllocation[]"
                                                                    style="margin-top: 5px; height: 25px;">
                                                                <option value="">Tháng</option>
                                                                @for($i = 1;$i<= 12; $i++)
                                                                    <option value="{!! $i !!}"
                                                                            @if($i == $currentMonth) selected="selected" @endif>{!! $i !!}</option>
                                                                @endfor
                                                            </select>
                                                            <span>/</span>
                                                            <select class="cbYearAllocation" name="cbYearAllocation[]"
                                                                    style="margin-top: 5px; height: 25px;">
                                                                <?php
                                                                $currentYear = (int)date('Y');
                                                                ?>
                                                                <option value="{!! $currentYear !!}">{!! $currentYear !!}</option>
                                                                <option value="{!! $currentYear + 1 !!}">{!! $currentYear + 1 !!}</option>
                                                            </select>
                                                            <select class="cbHoursAllocation" name="cbHoursAllocation[]"
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
                                                            <select class="cbMinuteAllocation"
                                                                    name="cbMinuteAllocation[]"
                                                                    style="margin-top: 5px; height: 25px;">
                                                                @for($i =0;$i<= 55; $i = $i+5)
                                                                    <option value="{!! $i !!}">{!! $i !!}</option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                    <div class="row">
                                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                            <label>Thời gian giao: </label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                            <select class="cbDayDeadline" name="cbDayDeadline[]"
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
                                                            <select class="cbMonthDeadline" name="cbMonthDeadline[]"
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
                                                            <select class="cbYearDeadline" name="cbYearDeadline[]"
                                                                    style="margin-top: 5px; height: 25px;">
                                                                <option value="{!! $currentYear !!}">{!! $currentYear !!}</option>
                                                                <option value="{!! $currentYear + 1 !!}">{!! $currentYear + 1 !!}</option>
                                                            </select>
                                                            <select class="cbHoursDeadline" name="cbHoursDeadline[]"
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
                                                            <select class="cbMinuteDeadline" name="cbMinuteDeadline[]"
                                                                    style="margin-top: 5px; height: 25px;">
                                                                @for($i =0;$i<= 55; $i = $i+5)
                                                                    <option value="{!! $i !!}">{!! $i !!}</option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    <div class="form-group form-group-sm qc-margin-none">
                                                        <label>Chi chú</label>
                                                        <input type="text" class="txtDescription form-control"
                                                               name="txtDescription[]"
                                                               placeholder="Chú thích công viêc" value=""
                                                               style="height: 25px;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        {{-- noi dung phan viec vai tro phu --}}
                                        @include('work.work-allocation.orders.product.work-allocation-staff', compact('dataReceiveStaff','dataProduct'))
                                    @endif

                                </div>
                            </div>
                            <div class="row">
                                <div class="qc-padding-top-20 qc-padding-bot-20  qc-border-none text-left col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                    <a class="qc_product_work_allocation_staff_add qc-link-green"
                                       data-href="{!! route('qc.work.work_allocation.manage.order.product.work-allocation.staff.get',$dataProduct->productId()) !!}">
                                        <i class="glyphicon glyphicon-plus"></i>
                                        Thêm nhân viên
                                    </a>
                                </div>
                            </div>


                            <div class="row">
                                <div class="qc-padding-top-20 qc-padding-bot-20  qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                    <button type="button" class="qc_save btn btn-primary btn-sm"> Giao</button>
                                    <a type="button" class="btn btn-sm btn-default" href="{!! $urlReferer !!}">Đóng</a>
                                </div>
                            </div>
                        </form>
                    @endif
                @endif

            </div>
        </div>
    </div>
@endsection
