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
$loginStaffId = $dataStaffLogin->staffId();
$currentMonth = $hFunction->currentMonth();
$currentYear = $hFunction->currentYear();

$dataWork = $dataStaffLogin->firstInfoToWork($loginStaffId, date('Y-m', strtotime("$currentYear-$currentMonth")));
$companyStaffWorkId = $dataWork->companyStaffWorkId();
// thong tin lương co ban
$dataStaffWorkSalary = $dataStaffLogin->staffWorkSalaryActivityOfStaff($loginStaffId);
$salaryOneHour = $dataStaffWorkSalary->salaryOnHour();#luong theo gio
$overtimeHour = $dataStaffWorkSalary->overtimeHour();# phu cap tang ca
if (count($dataWork) > 0) {
    $sumMainMinute = $dataWork->sumMainMinute();
    $sumPlusMinute = $dataWork->sumPlusMinute();
    //$totalSalary = $dataWork->totalSalaryBasicOfWorkInMonth(); // tong luong co ban
    $totalMoneyBeforePay = $dataWork->totalMoneyBeforePay(); // ung lương
    $totalMoneyMinus = $dataWork->totalMoneyMinus(); // phat - tru tien
} else {
    $sumMainMinute = 0;
    $sumPlusMinute = 0;
    $sumPlusMinute_1_5 = 0;
    //$totalSalary = 0;
    $totalMoneyBeforePay = 0; //ung luong
    $totalMoneyMinus = 0;
}
$sumPlusMinute_1_5 = $sumPlusMinute * 1.5;
$totalMoneyMainWork = ($sumMainMinute / 60) * $salaryOneHour;
$totalMoneyOvertimeWork = ($sumPlusMinute_1_5 / 60) * $salaryOneHour; // tien lam tang ca
$totalMoneyAllowanceOvertime = ($sumPlusMinute / 60) * $overtimeHour; // phu cap an uong tang ca
$totalMoneyImportOfStaff = $modelStaff->totalMoneyImportOfStaff($loginStaffId, date('Y-m'), 3); // tong tien mua vat tu
$totalMoneyImportPaidOfStaff = $modelStaff->totalMoneyImportOfStaff($loginStaffId, date('Y-m'), 1); // tong tien mua vat tu da thanh toan
$totalSalary = $totalMoneyMainWork + $totalMoneyOvertimeWork + $totalMoneyAllowanceOvertime + $totalMoneyImportOfStaff;
$totalSalaryAvailability = $totalSalary - $totalMoneyImportPaidOfStaff - $totalMoneyMinus;
?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <Label class="qc-color-red">THỐNG KÊ TÀI CHÍNH </Label>
        <em>(Tháng {!! $currentMonth !!})</em> <br/>
        <em style="color: red;">(Phần này đang thử nghiệm)</em>
    </div>
</div>
<div class="row" style="margin-bottom: 20px;">
    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="color: blue;">
                <em class="qc-color-grey">Tổng lương:</em>
                <b class="pull-right">{!! $hFunction->currencyFormat($totalSalary) !!}</b>
                <hr style="margin: 3px;">
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <em class="qc-color-grey">1. Tiền ngày công chính:</em>
                <b class="pull-right"
                   style="color: green;">+ {!! $hFunction->currencyFormat($totalMoneyMainWork) !!}</b>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <em class="qc-color-grey">2. Tiền làm tăng ca:</em>
                <b class="pull-right"
                   style="color: green;">+ {!! $hFunction->currencyFormat($totalMoneyOvertimeWork) !!}</b>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <em class="qc-color-grey">3. Tiền Ăn/uống tăng ca:</em>
                <b class="pull-right"
                   style="color: green;">+ {!! $hFunction->currencyFormat($totalMoneyAllowanceOvertime) !!}</b>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <em class="qc-color-grey">4. Mua vật tư:</em>
                <b class="pull-right qc-color-green">+ {!! $hFunction->currencyFormat($totalMoneyImportOfStaff) !!}</b>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <em class="qc-color-grey">5. Thưởng KPI (Đang cập nhật):</em>
                <b class="pull-right qc-color-red">+ {!! $hFunction->currencyFormat(0) !!}</b>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <em class="qc-color-grey">6. Được thanh toán mua vật tư:</em>
                <b class="pull-right">- {!! $hFunction->currencyFormat($totalMoneyImportPaidOfStaff) !!}</b>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <em class="qc-color-grey">7. Tiền phạt:</em>
                <a class="pull-right" href="{!! route('qc.work.minus_money.get') !!}">
                    - {!! $hFunction->currencyFormat($totalMoneyMinus) !!}
                </a>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <em class="qc-color-grey">8. Ứng lương:</em>
                <a class="pull-right" href="{!! route('qc.work.salary.before_pay.get') !!}">
                    - {!! $hFunction->currencyFormat($totalMoneyBeforePay) !!}
                </a>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <hr style="margin: 3px;">
                <em class="qc-color-grey">Lương khả dụng(Lãnh):</em>
                <b class="pull-right qc-color-red">{!! $hFunction->currencyFormat($totalSalaryAvailability) !!}</b>
            </div>
        </div>
    </div>
    @if($dataStaffLogin->checkBusinessDepartment())
        <?php
        $dataStaffKpi = $dataStaffLogin->staffKpiInfoActivity();
        ?>
        <div class="qc_work_kpi_statistic_wrap col-xs-12 col-sm-12 col-md-4 col-lg-4" style="border-left: 3px solid brown;">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <label class="qc-color-red">THỐNG KÊ KPI</label>
                </div>
                @if(count($dataStaffKpi) > 0)
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <em class="qc-color-grey">Mức KPI đăng ký:</em>
                        <a class="pull-right" href="#">
                            - {!! $hFunction->currencyFormat(50000000) !!}
                        </a>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <em class="qc-color-grey">KPI hiện tại:</em>
                        <a class="pull-right" href="{!! route('qc.work.minus_money.get') !!}">
                            - {!! $hFunction->currencyFormat($totalMoneyMinus) !!}
                        </a>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <em class="qc-color-grey">Tiền đạt được:</em>
                        <a class="pull-right" href="{!! route('qc.work.minus_money.get') !!}">
                            - {!! $hFunction->currencyFormat($totalMoneyMinus) !!}
                        </a>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <em class="qc-color-grey">Trạng thái nhận:</em>
                        <span class="pull-right qc-color-red">
                            Chưa đạt mức nhận
                        </span>
                    </div>
                @else
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <em class="qc-color-grey">Chưa đăng ký KPI</em>
                        <a class="qc-link pull-right qc-color-red" data-href="{!! route('qc.work.kpi.add_register.get') !!}">
                            Đang cập nhật...
                        </a>
                        {{--<a class="qc_work_kpi_register_get qc-link pull-right qc-color-red" data-href="{!! route('qc.work.kpi.add_register.get') !!}">
                            Đăng ký
                        </a>--}}
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>