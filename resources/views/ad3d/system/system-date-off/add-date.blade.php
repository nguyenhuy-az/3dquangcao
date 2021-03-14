<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$currentDay = $hFunction->currentDay();
$currentMonth = $hFunction->currentMonth();
$currentYear = $hFunction->currentYear();
?>
<div class="qc_date_off_add_wrap row" style="border-left: 2px solid brown; margin-bottom: 10px;">
    <div class="col-xs-11 col-sm-11 col-md-11 col-lg-11">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="form-group form-group-sm">
                    <label>Ngày:</label><br/>
                    <select class="col-xs-4 col-sm-4 col-md-4 col-lg-4" name="cbDay[]"
                            style="height: 30px; color:red; font-weight: bold;">
                        @for($d = 1; $d<= 31; $d++)
                            <option value="{!! $d !!}" @if($d == $currentDay) selected="selected" @endif>
                                {!! $d !!}
                            </option>
                        @endfor
                    </select>
                    <select class="col-xs-4 col-sm-4 col-md-4 col-lg-4" name="cbMonth[]" style="height: 30px;">
                        @for($m = 1; $m<= 31; $m++)
                            <option value="{!! $m !!}" @if($m == $currentMonth) selected="selected" @endif>
                                {!! $m !!}
                            </option>
                        @endfor
                    </select>
                    <select class="col-xs-4 col-sm-4 col-md-4 col-lg-4" name="cbYear[]" style="height: 30px;">
                        <option value="{!! $currentYear !!}" selected="selected">
                            {!! $currentYear !!}
                        </option>
                        <option value="{!! $currentYear +1 !!}">
                            {!! $currentYear + 1 !!}
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                <div class="form-group form-group-sm">
                    <label>Hình thức nghỉ:</label>
                    <select class="form-control" name="cbType[]">
                        <option value="{!! $modelSystemDateOff->getDefaultTypeHasMain() !!}">
                            Cố định (Bắt buộc)
                        </option>
                        <option value="{!! $modelSystemDateOff->getDefaultTypeNotMain() !!}">
                            Không cố định
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <div class="form-group form-group-sm">
                    <label>Mô tả:</label>
                    <input type="text" name="txtDescription[]" class="form-control" placeholder="Mô tả"
                           value="">
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
        <div class="row">
            <div class="text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <a class="qc_date_off_add_cancel qc-link-red-bold qc-font-size-14">
                    HỦY
                </a>
            </div>
        </div>
    </div>
</div>
