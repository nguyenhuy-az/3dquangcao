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
$dateFilter = date('Y-m');
$dataWork = $dataStaffLogin->firstInfoActivityToWork($loginStaffId, date('Y-m', strtotime("$currentYear-$currentMonth")));
$companyStaffWorkId = $dataWork->companyStaffWorkId();
// thong tin lương co ban
$dataStaffWorkSalary = $dataStaffLogin->staffWorkSalaryActivityOfStaff($loginStaffId);
$salaryOneHour = $dataStaffWorkSalary->salaryOnHour();#luong theo gio
$overtimeHour = $dataStaffWorkSalary->overtimeHour();# phu cap tang ca
if ($hFunction->checkCount($dataWork)) {
    $sumMainMinute = $dataWork->sumMainMinute();
    $sumPlusMinute = $dataWork->sumPlusMinute();
    //$totalSalary = $dataWork->totalSalaryBasicOfWorkInMonth(); // tong luong co ban
    # ung lương
    $totalMoneyBeforePay = $dataWork->totalMoneyConfirmedBeforePay();
    // phat - tru tien
    $totalMoneyMinus = $dataWork->totalMoneyMinus();
    // thuong
    $totalMoneyBonus = $dataWork->totalMoneyBonus();
} else {
    $sumMainMinute = 0;
    $sumPlusMinute = 0;
    $sumPlusMinute_1_5 = 0;
    //$totalSalary = 0;
    $totalMoneyBeforePay = 0;
    //ung luong
    $totalMoneyMinus = 0;
    // thuong
    $totalMoneyBonus = 0;

}
$sumPlusMinute_1_5 = $sumPlusMinute * 1.5;
# ======== ======= THO
# --- ---- THU ---------
$totalMoneyMainWork = ($sumMainMinute / 60) * $salaryOneHour;
# tien lam tang ca
$totalMoneyOvertimeWork = ($sumPlusMinute_1_5 / 60) * $salaryOneHour;
# phu cap an uong tang ca
$totalMoneyAllowanceOvertime = ($sumPlusMinute / 60) * $overtimeHour;
# tong tien mua vat tu
$totalMoneyImportOfStaff = $modelStaff->totalMoneyImportOfStaff($loginStaffId, $dateFilter, 3);

# chi ung luong
//$totalMoneyPaidSalaryBeforePayOfStaffAndDate = $modelStaff->totalMoneyPaidSalaryBeforePayOfStaffAndDate($loginStaffId, $dateFilter);


# --- ---- CHI ---------
$totalMoneyImportPaidOfStaff = $modelStaff->totalMoneyImportOfStaff($loginStaffId, $dateFilter, 1); // tong tien mua vat tu da thanh toan
# hoan tra huy don hang
//$totalPaidOrderCancelOfStaffAndDate = $modelStaff->totalPaidOrderCancelOfStaffAndDate($loginStaffId, $dateFilter);

#tong lương lanh
$totalSalary = $totalMoneyBonus + $totalMoneyMainWork + $totalMoneyOvertimeWork + $totalMoneyAllowanceOvertime + $totalMoneyImportOfStaff;
#
$totalSalaryAvailability = $totalSalary - $totalMoneyImportPaidOfStaff - $totalMoneyMinus;

?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <Label class="qc-color-red">THỐNG KÊ TÀI CHÍNH CÁ NHÂN </Label>
        <em>(Tháng {!! $currentMonth !!})</em> <br/>
    </div>
</div>
<div class="row" style="margin-bottom: 20px;">
    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
        <div class="row">
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="table-responsive">
                    <table class="table table-hover qc-margin-bot-none">
                        {{--<tr style="border-bottom: 1px solid #d7d7d7;">
                            <td>
                                <em class="qc-color-grey">Tổng Tiền:</em>
                            </td>
                            <td class="text-right">
                                    <b class="qc-color-red">{!! $hFunction->currencyFormat($totalSalary) !!}</b>
                            </td>
                        </tr>--}}
                        <tr>
                            <td>
                                <i class="glyphicon glyphicon-plus"></i>
                                <span>Tiền ngày công chính:</span>
                            </td>
                            <td class="text-right">
                                <b style="color: green;">+ {!! $hFunction->currencyFormat($totalMoneyMainWork) !!}</b>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <i class="glyphicon glyphicon-plus"></i>
                                <span>Tiền làm tăng ca:</span>
                            </td>
                            <td class="text-right">
                                <b style="color: green;">+ {!! $hFunction->currencyFormat($totalMoneyOvertimeWork) !!}</b>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <i class="glyphicon glyphicon-plus"></i>
                                <span>Tiền Ăn/uống tăng ca:</span>
                            </td>
                            <td class="text-right">
                                <b style="color: green;">+ {!! $hFunction->currencyFormat($totalMoneyAllowanceOvertime) !!}</b>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <i class="glyphicon glyphicon-plus"></i>
                                <span>Mua vật tư</span>
                                <em class="qc-color-grey">- (Đã xác nhận):</em>
                            </td>
                            <td class="text-right">
                                <b class="qc-color-green">+ {!! $hFunction->currencyFormat($totalMoneyImportOfStaff) !!}</b>
                            </td>
                        </tr>
                        {{--<tr>
                            <td>
                                <i class="glyphicon glyphicon-plus"></i>
                                <span>Chi ứng lương</span>
                                <em class="qc-color-grey">- (Đã xác nhận):</em>
                            </td>
                            <td class="text-right">
                                <b class="qc-color-green">+ {!! $hFunction->currencyFormat($totalMoneyPaidSalaryBeforePayOfStaffAndDate) !!}</b>
                            </td>
                        </tr>--}}
                        {{--<tr>
                            <td>
                                <i class="glyphicon glyphicon-plus"></i>
                                <span>Hoàn tiền ĐH:</span>
                            </td>
                            <td class="text-right">
                                <b class="qc-color-green">+ {!! $hFunction->currencyFormat($totalPaidOrderCancelOfStaffAndDate) !!}</b>
                            </td>
                        </tr>--}}
                        <tr>
                            <td>
                                <i class="glyphicon glyphicon-plus"></i>
                                <span>Thưởng Nóng </span>
                                <em class="qc-color-grey">- (Tạm thời):</em>
                            </td>
                            <td class="text-right">
                                <a class="qc-color-red" href="{!! route('qc.work.bonus.get') !!}">
                                    + {!! $hFunction->currencyFormat($totalMoneyBonus) !!}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <i class="glyphicon glyphicon-plus"></i>
                                <span>Thưởng KPI</span>
                                <em class="qc-color-grey">- (Đang cập nhật):</em>
                            </td>
                            <td class="text-right">
                                <b class="qc-color-red">+ {!! $hFunction->currencyFormat(0) !!}</b>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <i class="glyphicon glyphicon-minus"></i>
                                <span>Được thanh toán mua vật tư:</span>
                            </td>
                            <td class="text-right">
                                <b>- {!! $hFunction->currencyFormat($totalMoneyImportPaidOfStaff) !!}</b>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <i class="glyphicon glyphicon-minus"></i>
                                <span>Tiền phạt </span><em class="qc-color-grey"> - (Tạm thời):</em>
                            </td>
                            <td class="text-right">
                                <a href="{!! route('qc.work.minus_money.get') !!}">
                                    - {!! $hFunction->currencyFormat($totalMoneyMinus) !!}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <i class="glyphicon glyphicon-minus"></i>
                                <em>Ứng lương:</em>
                            </td>
                            <td class="text-right">
                                <a target="_blank" href="{!! route('qc.work.salary.before_pay.get') !!}">
                                    - {!! $hFunction->currencyFormat($totalMoneyBeforePay) !!}
                                </a>
                            </td>
                        </tr>
                        <tr style="border-top: 1px solid #d7d7d7;">
                            <td>
                                <em class="qc-color-grey">Lương khả dụng(Lãnh):</em>
                            </td>
                            <td class="text-right">
                                <b class="qc-color-red">{!! $hFunction->currencyFormat($totalSalaryAvailability) !!}</b>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @if($dataStaffLogin->checkBusinessDepartment())
        <?php
        $dataStaffKpi = $dataStaffLogin->staffKpiInfoActivity();
        ?>
        <div class="qc_work_kpi_statistic_wrap col-xs-12 col-sm-12 col-md-4 col-lg-4"
             style="border-left: 3px solid brown;">
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
                        <a class="qc-link pull-right qc-color-red"
                           data-href="{!! route('qc.work.kpi.add_register.get') !!}">
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