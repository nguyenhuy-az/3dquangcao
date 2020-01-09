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
            <li @if($viewLoginObject == 'payImport') class="active" @endif>
                <a href="{!! route('qc.work.import.get') !!}">
                    Chi mua vật tu
                </a>
            </li>
            @if($dataLoginStaff->checkTreasureDepartment())
                <li @if($viewLoginObject == 'payActivity') class="active" @endif>
                    <a href="{!! route('qc.work.pay.pay_activity.get') !!}">
                        Chi hoạt động
                    </a>
                </li>
                {{--<li @if($viewLoginObject == 'payImportLate') class="active" @endif>
                    <a href="#">
                        Công Nợ
                    </a>
                </li>--}}
                <li @if($viewLoginObject == 'paySalary') class="active" @endif>
                    <a href="{!! route('qc.work.pay.pay_salary.get') !!}">
                        Thanh toán lương
                    </a>
                </li>
                <li @if($viewLoginObject == 'paySalaryBeforePay') class="active" @endif>
                    <a href="#" title="Đang cập nhật">
                        Ứng lương mua vật tư
                    </a>
                </li>
            @endif
        </ul>
    </div>
</div>
