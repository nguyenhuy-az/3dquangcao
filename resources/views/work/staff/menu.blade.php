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
$dataLoginStaff = $modelStaff->loginStaffInfo();
if (isset($dataAccess)) {
    $subObject = (isset($dataAccess['subObject'])) ? $dataAccess['subObject'] : null;
    $subObjectLabel = (isset($dataAccess['subObjectLabel'])) ? $dataAccess['subObjectLabel'] : null;
} else {
    $viewLoginObject = '';
    $subObject = null;
    $subObjectLabel = null;
}
?>
<div class="row">
    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <ul class="nav nav-tabs" role="tablist">
            <li @if($subObject == 'basicInfo') class="active" @endif>
                <a href="{!! route('qc.work.salary.salary.get') !!}" @if($subObject == 'basicInfo') style="background-color: whitesmoke;" @endif>
                    <label>THÔNG TIN</label>
                </a>
            </li>
            <li @if($subObject == 'statisticInfo') class="active" @endif>
                <a href="{!! route('qc.work.salary.before_pay.get') !!}" @if($subObject == 'statisticInfo') style="background-color: whitesmoke;" @endif>
                    <label>THỐNG KÊ</label>
                </a>
            </li>
            <li @if($subObject == 'accountInfo') class="active" @endif>
                <a href="{!! route('qc.work.salary.keep_money.get') !!}" @if($subObject == 'accountInfo') style="background-color: whitesmoke;" @endif>
                    <label>TÀI KHOẢN</label>
                </a>
            </li>
        </ul>
    </div>
</div>
