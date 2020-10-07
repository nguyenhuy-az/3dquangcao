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
$subObject = (isset($dataAccess['subObject'])) ? $dataAccess['subObject'] : null;
?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <ul class="nav nav-tabs" role="tablist">
            <li @if($subObject == 'transferTransfer') class="active" @endif>
                <a class="qc-link" href="{!! route('qc.ad3d.finance.transfers.transfers.get') !!}" @if($subObject == 'transferTransfer') style="background-color: whitesmoke;" @endif>
                    <i class="qc-font-size-16 glyphicon glyphicon-refresh"></i>
                    <label>CHUYỂN TIỀN</label>
                </a>
            </li>
            <li @if($subObject == 'transferReceive') class="active" @endif>
                <a class="qc-link" href="{!! route('qc.ad3d.finance.transfers.receive.get') !!}" @if($subObject == 'transferReceive') style="background-color: whitesmoke;" @endif>
                    <i class="qc-font-size-16 glyphicon glyphicon-refresh"></i>
                    NHẬN TIỀN
                </a>
            </li>
        </ul>
    </div>
</div>
