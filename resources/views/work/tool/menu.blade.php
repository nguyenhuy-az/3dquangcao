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
            <li @if($viewLoginObject == 'toolPackageAllocation') class="active" @endif>
                <a class="qc-link-bold" href="{!! route('qc.work.tool.package_allocation.get') !!}"
                   @if($viewLoginObject == 'toolPackageAllocation') style="background-color: whitesmoke" @endif>
                    Đồ nghề được phát
                </a>
            </li>
            <li @if($viewLoginObject == 'toolCheckStore') class="active" @endif>
                <a class="qc-link-bold" href="{!! route('qc.work.tool.check_store.get') !!}"
                   @if($viewLoginObject == 'toolCheckStore') style="background-color: whitesmoke;" @endif>
                    Kiểm tra đồ nghề chung
                </a>
            </li>
        </ul>
    </div>
</div>
