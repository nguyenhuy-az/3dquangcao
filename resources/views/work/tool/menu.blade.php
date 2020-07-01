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
            <li @if($viewLoginObject == 'toolPrivate') class="active" @endif>
                <a class="qc-link-bold" href="{!! route('qc.work.tool.private.get') !!}"
                   @if($viewLoginObject == 'toolPrivate') style="background-color: whitesmoke" @endif>
                    Đồ nghề được phát
                </a>
            </li>
            <li @if($viewLoginObject == 'toolPublic') class="active" @endif>
                <a class="qc-link-bold" href="{!! route('qc.work.pay.keep_money.get') !!}"
                   @if($viewLoginObject == 'toolPublic') style="background-color: whitesmoke;" @endif>
                    Đồ nghề dùng chung
                </a>
            </li>
        </ul>
    </div>
</div>
