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
            <a class="qc-link-white-bold btn btn-primary" onclick="qc_main.page_back_go();">
                Về Trang trước
            </a>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dotted brown;">
            <h3 style="color: red;">TRIỂN KHAI THI CÔNG SẢN PHẨM</h3>
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
                                    <?php
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
                                            <img style="max-width: 50px;height: 50px;" src="{!! $src !!}">
                                            {!! $dataStaffAllocation->fullName() !!}
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
                    <form id="frmWorkAllocationManageProductConstruction" role="form" method="post"
                          enctype="multipart/form-data"
                          action="{!! route('qc.work.work_allocation.manage.product.work-allocation.add.post', $dataProduct->productId()) !!}">
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
                                    <th class="text-center">Giao</th>
                                    <th class="text-center">Thời gian nhận</th>
                                    <th class="text-center">Thời gian bàn giao</th>
                                    <th class="text-center">Vai trò</th>
                                    <th>Nội dung</th>
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
                                                <td class="text-center">
                                                    {!! $n_o_add = (isset($n_o_add))?$n_o_add+1: 1 !!}
                                                </td>
                                                <td>
                                                    <img style="max-width: 50px;height: 50px;" src="{!! $src !!}">
                                                    {!! $receiveStaff->fullName() !!}
                                                </td>
                                                <td class="text-center" style="padding: 0;">
                                                    <input type="checkbox" name="staffReceive[]" checked="checked"
                                                           value="{!! $receiveStaffId !!}">
                                                </td>
                                                <td style="padding: 0; width: 200px;">
                                                    <select class="cbDayAllocation col-sx-2 col-sm-2 col-md-2 col-lg-2"
                                                            name="cbDayAllocation_{!! $receiveStaffId !!}"
                                                            style="padding: 0; height: 34px; color: red;">
                                                        <option value="">Ngày</option>
                                                        @for($i = 1;$i<= 31; $i++)
                                                            <option value="{!! $i !!}"
                                                                    @if($i == $currentDay) selected="selected" @endif >{!! $i !!}</option>
                                                        @endfor
                                                    </select>
                                                    <select class="cbMonthAllocation col-sx-2 col-sm-2 col-md-2 col-lg-2"
                                                            name="cbMonthAllocation_{!! $receiveStaffId !!}"
                                                            style="padding: 0; height: 34px; color: red;">
                                                        <option value="">Tháng</option>
                                                        @for($i = 1;$i<= 12; $i++)
                                                            <option value="{!! $i !!}"
                                                                    @if($i == $currentMonth) selected="selected" @endif>{!! $i !!}</option>
                                                        @endfor
                                                    </select>
                                                    <select class="cbYearAllocation col-sx-4 col-sm-4 col-md-4 col-lg-4"
                                                            name="cbYearAllocation_{!! $receiveStaffId !!}"
                                                            style="padding: 0; height: 34px; color: red;">
                                                        <?php
                                                        $currentYear = (int)date('Y');
                                                        ?>
                                                        <option value="{!! $currentYear !!}">{!! $currentYear !!}</option>
                                                        <option value="{!! $currentYear + 1 !!}">{!! $currentYear + 1 !!}</option>
                                                    </select>
                                                    <select class="cbHoursAllocation col-sx-2 col-sm-2 col-md-2 col-lg-2"
                                                            name="cbHoursAllocation_{!! $receiveStaffId !!}"
                                                            style="padding: 0; height: 34px;">
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
                                                            name="cbMinuteAllocation_{!! $receiveStaffId !!}"
                                                            style="padding: 0; height: 34px;">
                                                        @for($i =0;$i<= 55; $i = $i+5)
                                                            <option value="{!! $i !!}">{!! $i !!}</option>
                                                        @endfor
                                                    </select>
                                                </td>
                                                <td style="padding: 0; width: 200px;">
                                                    <select class="cbDayDeadline col-sx-2 col-sm-2 col-md-2 col-lg-2"
                                                            name="cbDayDeadline_{!! $receiveStaffId !!}"
                                                            style="padding: 0; height: 34px; color: red;">
                                                        <option value="">Ngày</option>
                                                        @for($i = 1;$i<= 31; $i++)
                                                            <option value="{!! $i !!}"
                                                                    @if($i == $currentDay) selected="selected" @endif>
                                                                {!! $i !!}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                    <select class="cbMonthDeadline col-sx-2 col-sm-2 col-md-2 col-lg-2"
                                                            name="cbMonthDeadline_{!! $receiveStaffId !!}"
                                                            style="padding: 0; height: 34px; color: red;">
                                                        <option value="">Tháng</option>
                                                        @for($i = 1;$i<= 12; $i++)
                                                            <option value="{!! $i !!}"
                                                                    @if($i == $currentMonth) selected="selected" @endif>
                                                                {!! $i !!}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                    <select class="cbYearDeadline col-sx-4 col-sm-4 col-md-4 col-lg-4"
                                                            name="cbYearDeadline_{!! $receiveStaffId !!}"
                                                            style="padding: 0; height: 34px; color: red;">
                                                        <option value="{!! $currentYear !!}">{!! $currentYear !!}</option>
                                                        <option value="{!! $currentYear + 1 !!}">{!! $currentYear + 1 !!}</option>
                                                    </select>
                                                    <select class="cbHoursDeadline col-sx-2 col-sm-2 col-md-2 col-lg-2"
                                                            name="cbHoursDeadline_{!! $receiveStaffId !!}"
                                                            style="padding: 0; height: 34px;">
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
                                                            name="cbMinuteDeadline_{!! $receiveStaffId !!}"
                                                            style="padding: 0; height: 34px;">
                                                        @for($i =0;$i<= 55; $i = $i+5)
                                                            <option value="{!! $i !!}">{!! $i !!}</option>
                                                        @endfor
                                                    </select>
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
                                            <td class="text-center" colspan="7" style="background-color: red; color: yellow;">
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
