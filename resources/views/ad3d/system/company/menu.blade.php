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
//$companyStaffWorkLastInfo = $modelStaff->loginCompanyStaffWork();
$companyLogin = $modelStaff->companyLogin();
$subObject = (isset($dataAccess['subObject'])) ? $dataAccess['subObject'] : null;
?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <ul class="nav nav-tabs" role="tablist">
            <li @if($subObject == 'company') class="active" @endif>
                <a class="qc-link" href="{!! route('qc.ad3d.system.company.get') !!}"
                   @if($subObject == 'company') style="background-color: whitesmoke;" @endif>
                    <i class="qc-font-size-16 glyphicon glyphicon-refresh"></i>
                    CÔNG TY
                </a>
            </li>
            @if($companyLogin->checkRoot() && $companyLogin->checkParent())
                <li @if($subObject == 'partner') class="active" @endif>
                    <a class="qc-link" href="{!! route('qc.ad3d.system.company.partner.get') !!}"
                       @if($subObject == 'partner') style="background-color: whitesmoke;" @endif>
                        <i class="qc-font-size-16 glyphicon glyphicon-refresh"></i>
                        ĐỐI TÁC
                    </a>
                </li>
            @endif
        </ul>
    </div>
</div>
