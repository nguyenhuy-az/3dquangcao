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
$subObject = isset($dataAccess['subObject']) ? $dataAccess['subObject'] : 'workAllocation';
?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <ul class="nav nav-tabs" role="tablist">
            <li @if($subObject == 'workAllocationReport') class="active" @endif>
                <a class="qc-link" href="{!! route('qc.ad3d.work.work_allocation_report.get') !!}" @if($subObject == 'workAllocationReport') style="background-color: whitesmoke;" @endif>
                    @if($subObject == 'workAllocationReport')
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    @endif
                    BÁO CÁO TRÊN PHÂN VIỆC
                </a>
            </li>
            <li @if($subObject == 'workAllocation') class="active" @endif>
                <a class="qc-link" href="{!! route('qc.ad3d.work.work_allocation.get') !!}" @if($subObject == 'workAllocation') style="background-color: whitesmoke;" @endif>
                    @if($subObject == 'workAllocation')
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    @endif
                    PHÂN VIỆC
                </a>
            </li>
        </ul>
    </div>
</div>
