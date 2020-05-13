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
            <li @if($viewLoginObject == 'bonus') class="active" @endif>
                <a href="{!! route('qc.work.bonus.get') !!}" @if($viewLoginObject == 'bonus') style="background-color: whitesmoke;" @endif>
                    <label>Thưởng</label>
                </a>
            </li>
            <li @if($viewLoginObject == 'minusMoney') class="active" @endif>
                <a href="{!! route('qc.work.minus_money.get') !!}" @if($viewLoginObject == 'minusMoney') style="background-color: whitesmoke;" @endif>
                    <label>Phạt</label>
                </a>
            </li>
        </ul>
    </div>
</div>
