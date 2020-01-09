<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 1/4/2018
 * Time: 5:41 PM
 *
 * dataProductType
 *
 */
date_default_timezone_set('Asia/Ho_Chi_Minh');
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$currentDay = (int)date('d');
$currentMonth = (int)date('m');
$currentYear = (int)date('Y');
$currentHour = (int)date('H');
$currentMinute = (int)date('i');
?>
<div class="qc_ad3d_work_product_add qc-margin-top-5 col-xs-12 col-sm-12 col-md-12 col-lg-12"
     style="border: 1px solid #d7d7d7; border-left: 5px solid brown;">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="form-group form-group-sm qc-margin-none">
                <label>Loại sản phẩm: <i
                            class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                <select class="cbProductType form-control" name="cbProductType[]" style="height: 25px;">
                    <option value="">Chọn sản phẩm</option>
                    @if(count($dataProduct) > 0)
                        @foreach($dataProduct as $product)
                            <option value="{!! $product->productId() !!}">{!! $product->order->name() !!}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <label>Thời gian hêt: </label>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <select name="cbDayAllocation" style="margin-top: 5px; height: 25px;">
                        <option value="">Ngày</option>
                        @for($i = 1;$i<= 31; $i++)
                            <option value="{!! $i !!}"
                                    @if($i == $currentDay) selected="selected" @endif >{!! $i !!}</option>
                        @endfor
                    </select>
                    <span>/</span>
                    <select name="cbMonthAllocation" style="margin-top: 5px; height: 25px;">
                        <option value="">Tháng</option>
                        @for($i = 1;$i<= 12; $i++)
                            <option value="{!! $i !!}"
                                    @if($i == $currentMonth) selected="selected" @endif>{!! $i !!}</option>
                        @endfor
                    </select>
                    <span>/</span>
                    <select name="cbYearAllocation" style="margin-top: 5px; height: 25px;">
                        <?php
                        $currentYear = (int)date('Y');
                        ?>
                        <option value="{!! $currentYear !!}">{!! $currentYear !!}</option>
                        <option value="{!! $currentYear + 1 !!}">{!! $currentYear + 1 !!}</option>
                    </select>
                    <select name="cbHoursAllocation" style="margin-top: 5px; height: 25px;">
                        <option value="">Giờ</option>
                        @for($i =1;$i<= 24; $i++)
                            <?php
                            $currentHour = ($currentHour < 8) ? 8 : $currentHour;
                            ?>
                            <option value="{!! $i !!}" @if($i == $currentHour) selected="selected" @endif>
                                {!! $i !!}
                            </option>
                        @endfor
                    </select>
                    <span>:</span>
                    <select name="cbMinuteAllocation" style="margin-top: 5px; height: 25px;">
                        @for($i =0;$i<= 55; $i = $i+5)
                            <option value="{!! $i !!}">{!! $i !!}</option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <label>Thời gian giao: </label>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <select name="cbDayDeadline" style="margin-top: 5px; height: 25px;">
                        <option value="">Ngày</option>
                        @for($i = 1;$i<= 31; $i++)
                            <option value="{!! $i !!}" @if($i == $currentDay) selected="selected" @endif>
                                {!! $i !!}
                            </option>
                        @endfor
                    </select>
                    <span>/</span>
                    <select name="cbMonthDeadline" style="margin-top: 5px; height: 25px;">
                        <option value="">Tháng</option>
                        @for($i = 1;$i<= 12; $i++)
                            <option value="{!! $i !!}" @if($i == $currentMonth) selected="selected" @endif>
                                {!! $i !!}
                            </option>
                        @endfor
                    </select>
                    <span>/</span>
                    <select name="cbYearDeadline" style="margin-top: 5px; height: 25px;">
                        <option value="{!! $currentYear !!}">{!! $currentYear !!}</option>
                        <option value="{!! $currentYear + 1 !!}">{!! $currentYear + 1 !!}</option>
                    </select>
                    <select name="cbHoursDeadline" style="margin-top: 5px; height: 25px;">
                        <option value="">Giờ</option>
                        @for($i =1;$i<= 24; $i++)
                            <?php
                            $currentHour = ($currentHour < 8) ? 8 : $currentHour;
                            ?>
                            <option value="{!! $i !!}" @if($i == $currentHour) selected="selected" @endif>
                                {!! $i !!}
                            </option>
                        @endfor
                    </select>
                    <span>:</span>
                    <select name="cbMinuteDeadline" style="margin-top: 5px; height: 25px;">
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
                <input type="text" class="txtDescription form-control" name="txtDescription[]"
                       placeholder="Chú thích công viêc" value="" style="height: 25px;">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <a class="qc_delete qc-link-red" data-href="">
                Xóa
            </a>
        </div>
    </div>
</div>
