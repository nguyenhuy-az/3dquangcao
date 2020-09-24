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
$subObject = null;
if (isset($dataAccess)) {
    $subMenuObject = (isset($dataAccess['subObject'])) ? $dataAccess['subObject'] : null;
}
?>
<div class="row">
    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <ul class="nav nav-tabs" role="tablist">
            <li @if($subMenuObject == 'jobApplication') class="active" @endif>
                <a href="{!! route('qc.ad3d.system.job-application.get') !!}"
                   @if($subMenuObject == 'jobApplication') style="background-color: whitesmoke;" @endif>
                    <i class="glyphicon glyphicon-refresh qc-font-size-16"></i>
                    <label>HỒ SƠ ỨNG TUYỂN</label>
                </a>
            </li>
            <li @if($subMenuObject == 'jobApplicationInterview') class="active" @endif>
                <a href="{!! route('qc.ad3d.system.job-application-interview.get') !!}"
                   @if($subMenuObject == 'jobApplicationInterview') style="background-color: whitesmoke;" @endif>
                    <i class="glyphicon glyphicon-refresh qc-font-size-16"></i>
                    <label>HỒ SƠ PHỎNG VẤN</label>
                </a>
            </li>
        </ul>
    </div>
</div>