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
    <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4"
         @if($mobileStatus) style="padding: 0 0;" @endif>
        <div class="form-group form-group-sm">
            <label>Ngày:</label><br/>
            <select name="cbDay[]" style="margin-top: 5px; height: 35px; color:red; font-weight: bold;">
                @for($i = 1; $i<= 31; $i++)
                    <option value="{!! $i !!}" @if($i == $currentDay) selected="selected" @endif>
                        {!! $i !!}
                    </option>
                @endfor
            </select>
            <span>/</span>
            <select name="cbMonth[]" style="margin-top: 5px; height: 25px;">
                @for($i = 1; $i<= 31; $i++)
                    <option value="{!! $i !!}" @if($i == $currentMonth) selected="selected" @endif>
                        {!! $i !!}
                    </option>
                @endfor
            </select>
            <span>/</span>
            <select name="cbYear[]" style="margin-top: 5px; height: 25px;">
                <option value="{!! $currentYear !!}" selected="selected">
                    {!! $currentYear !!}
                </option>
                <option value="{!! $currentYear +1 !!}">
                    {!! $currentYear + 1 !!}
                </option>
            </select>
        </div>
    </div>
    <div class="col-sx-12 col-sm-12 col-md-2 col-lg-2">
        <div class="form-group form-group-sm">
            <label>Hình thức nghỉ:</label>
            <select class="form-control" name="cbType[]">
                <option value="1">Cố định (Bắt buộc)</option>
                <option value="2">Không cố định</option>
            </select>
        </div>
    </div>
    <div class="col-sx-12 col-sm-12 col-md-5 col-lg-5">
        <div class="form-group form-group-sm">
            <label>Mô tả:</label>
            <input type="text" name="txtDescription[]" class="form-control" placeholder="Mô tả"
                   value="">
        </div>
    </div>
    <div class="text-right col-sx-12 col-sm-12 col-md-1 col-lg-1">
        <a class="qc_date_off_add_cancel qc-link-red">Hủy</a>
    </div>
</div>
