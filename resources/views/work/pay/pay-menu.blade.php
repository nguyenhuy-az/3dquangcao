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
                <a href="{!! route('qc.work.import.get') !!}" @if($viewLoginObject == 'payImport') style="background-color: whitesmoke;" @endif>
                    Chi mua vật tu
                </a>
            </li>
            @if($dataLoginStaff->checkTreasureDepartment())
                <li @if($viewLoginObject == 'payActivity') class="active" @endif>
                    <a href="{!! route('qc.work.pay.pay_activity.get') !!}" @if($viewLoginObject == 'payActivity') style="background-color: whitesmoke;" @endif>
                        Chi hoạt động
                    </a>
                </li>
                {{--<li @if($viewLoginObject == 'payImportLate') class="active" @endif>
                    <a href="#">
                        Công Nợ
                    </a>
                </li>--}}
                <li @if($viewLoginObject == 'paySalaryBeforePay') class="active" @endif>
                    <a href="{!! route('qc.work.pay.salary_before_pay.get') !!}" @if($viewLoginObject == 'paySalaryBeforePay') style="background-color: whitesmoke;" @endif >
                        Ứng lương mua vật tư
                    </a>
                </li>
                <li @if($viewLoginObject == 'paySalary') class="active" @endif>
                    <a href="{!! route('qc.work.pay.pay_salary.get') !!}" @if($viewLoginObject == 'paySalary') style="background-color: whitesmoke" @endif>
                        Thanh toán lương
                    </a>
                </li>
                <li @if($viewLoginObject == 'keepMoney') class="active" @endif>
                    <a href="{!! route('qc.work.pay.keep_money.get') !!}" @if($viewLoginObject == 'keepMoney') style="background-color: whitesmoke" @endif>
                        Thanh toán tiền giữ
                    </a>
                </li>
            @endif
        </ul>
    </div>
</div>
