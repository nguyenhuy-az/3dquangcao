<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:32 PM
 *
 * dataOrder
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$dataStaffLogin = $modelStaff->loginStaffInfo();
?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <ul class="nav nav-tabs" role="tablist">
        <li @if($subObject == 'workAllocationReport') class="active" @endif>
            <a href="{!! route('qc.ad3d.work.work_allocation_report.get') !!}">
                Báo cáo
            </a>
        </li>
        <li @if($subObject == 'workAllocation') class="active" @endif>
            <a href="{!! route('qc.ad3d.work.work_allocation.get') !!}">
                Phân việc
            </a>
        </li>
    </ul>
</div>
