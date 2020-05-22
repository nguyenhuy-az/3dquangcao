<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 8:57 AM
 *
 * modelStaff
 */
$hFunction = new Hfunction();
$dataStaffLogin = $modelStaff->loginStaffInfo();
$staffCompanyId = $dataStaffLogin->companyId();
$sysInfoObject = isset($sysInfoObject) ? $sysInfoObject : 'home_timekeeping';
$systemNotify = $dataStaffLogin->totalNewNotify();
?>
<div class="row" style="margin-bottom: 10px;">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 10px;">
        <ul class="nav nav-tabs" role="tablist">
            <li @if($sysInfoObject == 'home_timekeeping') class="active" @endif >
                <a class="qc-link" href="{!! route('qc.work.home','home_timekeeping') !!}" @if($sysInfoObject == 'home_timekeeping')style="background-color: #d7d7d7;" @endif>
                    Trang chủ
                </a>
            </li>
            <li @if($sysInfoObject == 'home_system_notify') class="active" @endif>
                <a class="qc-link" href="{!! route('qc.work.news.notify.get') !!}" @if($sysInfoObject == 'home_system_notify')style="background-color: #d7d7d7;" @endif>
                    Thông báo
                    @if($systemNotify > 0)
                        &nbsp;
                        <i class="qc-font-size-14 glyphicon glyphicon-bullhorn" style="color: red;"></i>
                    @endif
                </a>
            </li>
            <li @if($sysInfoObject == 'home_systemDateOff') class="active" @endif>
                <a class="qc-link" href="{!! route('qc.work.news.date_off.get') !!}" @if($sysInfoObject == 'home_systemDateOff')style="background-color: #d7d7d7;" @endif>
                    Lịch nghỉ
                </a>
            </li>
        </ul>
    </div>
    @if($sysInfoObject == 'home_timekeeping')
        @include('work.components.system-info.system-info-timekeeping', compact('modelCompany','modelStaff'))
    @endif

</div>