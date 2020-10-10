<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 5/15/2018
 * Time: 10:12 AM
 *
 * dataStaff
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$currentMonth = date('m');
if (isset($dataAccess)) {
    $viewLoginObject = $dataAccess['object'];
    //$subObjectLabel = (isset($dataAccess['subObjectLabel'])) ? $dataAccess['subObjectLabel'] : null;
} else {
    $viewLoginObject = '';
    $subObject = null;
}
?>
<div class="row">
    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <ul class="nav nav-tabs" role="tablist">
            <li @if($viewLoginObject == 'timekeeping') class="active" @endif>
                <a href="{!! route('qc.work.timekeeping.get') !!}" @if($viewLoginObject == 'timekeeping') style="background-color: whitesmoke;" @endif>
                    <i class="glyphicon glyphicon-refresh qc-font-size-16"></i>
                    <label>CHẤM CÔNG THÁNG {!! $currentMonth !!}</label>
                </a>
            </li>
            <li @if($viewLoginObject == 'timekeepingWork') class="active" @endif>
                <a href="{!! route('qc.work.timekeeping.work.get') !!}" @if($viewLoginObject == 'timekeepingWork') style="background-color: whitesmoke;" @endif>
                    <i class="glyphicon glyphicon-refresh qc-font-size-16"></i>
                    <label>BẢNG LƯƠNG TẠM THÁNG {!! $currentMonth !!}</label>
                </a>
            </li>
        </ul>
    </div>
</div>
