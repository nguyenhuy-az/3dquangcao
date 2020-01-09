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
$loginStaffId = $dataStaff->staffId();
$dataWork = $dataStaff->firstInfoToWork($loginStaffId, date('Y-m', strtotime("$loginYear-$loginMonth")));
$workId = $dataWork->workId();
$totalMoneyMinus = $dataWork->totalMoneyMinus();
$totalMoneyBeforePay = $dataWork->totalMoneyBeforePay();
$sumMainMinute = $dataWork->sumMainMinute();
$sumPlusMinute = $dataWork->sumPlusMinute();
$sumPlusMinute_1_5 = $sumPlusMinute * 1.5;
$sumMinusMinute = $dataWork->sumMinusMinute();
$companyStaffWorkId = $dataWork->companyStaffWorkId();
$staffId = $dataWork->staffId();
$infoSalaryBasic = true;
# phien ban moi - nv lam nhieu cty
if (!empty($companyStaffWorkId)) {
    $dataStaffWorkSalary = $modelCompanyStaffWork->staffWorkSalaryActivity($companyStaffWorkId);
    if (count($dataStaffWorkSalary) > 0) { # da co ban luong co ban cua he thong
        $totalSalaryBasic = $dataStaffWorkSalary->totalSalary();
        $salaryBasic = $dataStaffWorkSalary->salary();
        $responsibility = $dataStaffWorkSalary->responsibility();# phu cap trach nhiem /26 ngay
        $usePhone = $dataStaffWorkSalary->usePhone();# phu cap su dung dien thoai
        $fuel = $dataStaffWorkSalary->fuel();# phu cap xang di lai
        $overtimeHour = $dataStaffWorkSalary->overtimeHour();# phu cap tang ca
        //$sumPlusMinute = $dataWork->sumPlusMinute($workId);
        $totalMoneyOvertimeHour = ($sumPlusMinute / 60) * $overtimeHour; # tien phu cap an uong tang ca
        $totalMoneyInsurance = $dataStaffWorkSalary->totalMoneyInsurance();# phu cap bao hiem %
        $salaryOneHour = $dataStaffWorkSalary->salaryOnHour();#luong theo gio
        $totalCurrentSalary = $dataWork->totalSalaryBasicOfWorkInMonth($workId);
    } else {
        $infoSalaryBasic = false;
    }
} else {
    $dataStaffWorkSalary = $modelCompanyStaffWork->staffWorkSalaryActivityOfStaff($staffId);
    if (count($dataStaffWorkSalary) > 0) {
        $totalSalaryBasic = $dataStaffWorkSalary->totalSalary();
        $salaryBasic = $dataStaffWorkSalary->salary();
        $responsibility = $dataStaffWorkSalary->responsibility();# phu cap trach nhiem /26 ngay
        $usePhone = $dataStaffWorkSalary->usePhone();# phu cap su dung dien thoai
        $fuel = $dataStaffWorkSalary->fuel();# phu cap xang di lai
        $overtimeHour = $dataStaffWorkSalary->overtimeHour();# phu cap tang ca
        //$sumPlusMinute = $dataWork->sumPlusMinute($workId);
        $totalMoneyOvertimeHour = ($sumPlusMinute / 60) * $overtimeHour; # tien phu cap an uong tang ca
        $totalMoneyInsurance = $dataStaffWorkSalary->totalMoneyInsurance();# phu cap bao hiem %
        $salaryOneHour = $dataStaffWorkSalary->salaryOnHour(); #luong theo gio
        $totalCurrentSalary = $dataWork->totalSalaryBasicOfWorkInMonth();
    } else {
        # truong hop phien ban cu chua cap nhat
        $salaryBasic = $dataWork->staff->salaryBasicOfStaff($staffId);
        if (count($salaryBasic) > 0) { # da co ban luong co ban cua he thong
            $totalSalaryBasic = $salaryBasic;
            $responsibility = 0;# phu cap trach nhiem /26 ngay
            $usePhone = 0;# phu cap su dung dien thoai
            $totalMoneyInsurance = 0;# phu cap bao hiem %
            $fuel = 0;# phu cap xang di lai
            $overtimeHour = 10;
            $totalMoneyOvertimeHour = 0;
            $totalMoneyInsurance = 0;
            $salaryOneHour = floor($salaryBasic / 208);
            $totalCurrentSalary = $dataWork->totalSalaryBasicOfWorkInMonth();//$dataWork->totalCurrentSalary();
        } else {
            $infoSalaryBasic = false;
        }
    }
}

?>
@extends('work.index')
@section('qc_work_body')
    <div class="row">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row" style="margin-bottom: 20px;">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <ul class="nav nav-tabs" role="tablist">
                        <li>
                            <a href="{!! route('qc.work.timekeeping.get') !!}">
                                <label>Chấm công</label>
                            </a>
                        </li>
                        <li class="active">
                            <a href="{!! route('qc.work.work.get') !!}">
                                <label>Thông tin làm việc hiện tại</label>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="qc-border-none col-sx-12 col-sm-12 col-md-3 col-lg-3">
                    <em>Nhân Viên: </em>
                    <span class="qc-font-bold">
                        @if(!empty($companyStaffWorkId))
                            {!! $dataWork->companyStaffWork->staff->fullName() !!}
                        @else
                            {!! $dataWork->staff->fullName() !!}
                        @endif
                    </span>
                </div>
                <div class="qc-border-none col-sx-12 col-sm-12 col-md-3 col-lg-3">
                    <em>Từ ngày: </em>
                    <span class="qc-color-red qc-font-bold">
                        {!! date('d-m-Y', strtotime($dataWork->fromDate())) !!}
                    </span>
                </div>
                <div class="qc-border-none col-sx-12 col-sm-12 col-md-6 col-lg-6">
                    <em>Đến ngày: </em>
                    <span class="qc-color-red qc-font-bold">
                       {!! date('d-m-Y', strtotime($dataWork->toDate())) !!}
                    </span>
                </div>
                {{--<div class="text-right qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-3 col-lg-3">
                    <select class="qc_work_login_month" style="height: 25px;"
                            data-href="{!! route('qc.work.work.get') !!}">
                        @for($i = 1; $i <=12; $i++)
                            <option @if($loginMonth == $i) selected="selected" @endif>
                                {!! $i !!}
                            </option>
                        @endfor
                    </select>
                    <span>/</span>
                    <select class="qc_work_login_year" style="height: 25px;"
                            data-href="{!! route('qc.work.work.get') !!}">
                        @for($i = 2017; $i <=2050; $i++)
                            <option @if($loginYear == $i) selected="selected" @endif>
                                {!! $i !!}
                            </option>
                        @endfor
                    </select>
                </div>--}}
            </div>


            {{-- chi tiêt --}}
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                {{-- Bảng lương cơ bản   --}}
                <div class="row qc-container-table">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr class="qc-color-green">
                                <th colspan="7">BẢNG LƯƠNG CƠ BẢN</th>
                            </tr>
                            <tr style="background-color: whitesmoke;">
                                <th class="text-center">Tổng lương</th>
                                <th class="text-center">Lương cơ bản</th>
                                <th class="text-center">Bảo hiểm</th>
                                <th class="text-center">Ngày phép</th>
                                <th class="text-center">PC Trách nhiệm</th>
                                <th class="text-center">Điện thoại/26Ng</th>
                                <th class="text-center">Xăng/26Ng</th>
                                <th class="text-center">Tăng ca/h</th>
                                <th class="text-center">Lương/h</th>
                            </tr>
                            @if($infoSalaryBasic)
                                <tr>
                                    <td class="text-center" style="color: blue;">
                                        {!! $hFunction->currencyFormat($totalSalaryBasic) !!}
                                    </td>
                                    <td class="text-center">
                                        {!! $hFunction->currencyFormat($salaryBasic) !!}
                                    </td>
                                    <td class="text-center">
                                        {!! $hFunction->currencyFormat($totalMoneyInsurance) !!}
                                    </td>
                                    <td class="text-center">
                                        {!! $hFunction->currencyFormat($salaryOneHour*8) !!}
                                    </td>
                                    <td class="text-center">
                                        {!! $hFunction->currencyFormat($responsibility) !!}
                                    </td>
                                    <td class="text-center">
                                        {!! $hFunction->currencyFormat($usePhone) !!}
                                    </td>
                                    <td class="text-center">
                                        {!! $hFunction->currencyFormat($fuel) !!}
                                    </td>
                                    <td class="text-center">
                                        {!! $hFunction->currencyFormat($overtimeHour) !!}
                                    </td>
                                    <td class="text-center" style="color: blue;">
                                        {!! $hFunction->currencyFormat($salaryOneHour) !!}
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <th class="text-center qc-color-red" colspan="7">
                                        Chưa có bảng lương cơ bản
                                    </th>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
                {{-- thong tin lam viec   --}}
                <div class="row qc-container-table">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr class="qc-color-green">
                                <th colspan="7">THÔNG TIN LÀM VIỆC</th>
                            </tr>
                            <tr style="background-color: whitesmoke;">
                                <th class="text-center">Giờ làm chính</th>
                                <th class="text-center">Tăng ca *1.5</th>
                                <th class="text-center">Tổng giờ làm</th>
                                <th class="text-center">Nghỉ có phép</th>
                                <th class="text-center">Nghỉ không phép</th>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    {!! floor(($sumMainMinute-$sumMainMinute%60)/60) !!}
                                    <b>h</b> {!! $sumMainMinute%60 !!}
                                </td>
                                <td class="text-center">
                                    {!! floor(($sumPlusMinute_1_5-$sumPlusMinute_1_5%60)/60) !!}
                                    <b>h</b> {!! $sumPlusMinute_1_5%60 !!}
                                </td>
                                <td class="text-center">
                                    {!! floor(($sumMainMinute + $sumPlusMinute_1_5)/60) !!}
                                    <span>h</span> {!! ($sumMainMinute + $sumPlusMinute_1_5)%60 !!}
                                </td>
                                <td class="text-center">
                                    {!! $dataWork->sumOffWorkTrue() !!}
                                </td>
                                <td class="text-center">
                                    {!! $dataWork->sumOffWorkFalse() !!}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                {{-- Chi tiết lương   --}}
                <div class="row qc-container-table">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr class="qc-color-green">
                                <th colspan="7">CHI TIẾT LƯƠNG LÃNH</th>
                            </tr>
                            <tr style="background-color: whitesmoke;">
                                <th class="text-center">
                                    P/C tăng ca ({!! floor(($sumPlusMinute-$sumPlusMinute%60)/60) !!}
                                    <b>h</b> {!! $sumPlusMinute%60 !!})
                                </th>
                                <th class="text-center">Lương lãnh</th>
                                <th class="text-center">Ứng</th>
                                <th class="text-center">Phạt</th>

                                <th class="text-center">lương còn lại</th>
                            </tr>
                            @if($infoSalaryBasic)
                                <tr>
                                    <td class="text-center">
                                        {!! $hFunction->currencyFormat($totalMoneyOvertimeHour) !!}
                                    </td>
                                    <td class="text-center">
                                        {!! $hFunction->currencyFormat($totalCurrentSalary) !!}
                                    </td>
                                    <td class="text-center">
                                        <a class="qc-font-bold" href="{!! route('qc.work.salary.before_pay.get') !!}">
                                            {!! $hFunction->currencyFormat($totalMoneyBeforePay) !!}
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <a class="qc-font-bold" href="{!! route('qc.work.minus_money.get') !!}">
                                            {!! $hFunction->currencyFormat($totalMoneyMinus) !!}
                                        </a>

                                    </td>
                                    <td class="text-center qc-color-red">
                                        {!! $hFunction->currencyFormat($totalCurrentSalary + $totalMoneyBeforePay - $totalMoneyMinus) !!}
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <th class="text-center qc-color-red" colspan="5">
                                        Chưa có bảng lương cơ bản
                                    </th>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
                <?php
                $dataTimekeeping = $dataWork->infoTimekeeping();
                ?>
                @if(count($dataTimekeeping)>0)
                    <div class="row qc-container-table">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <tr class="qc-color-green">
                                    <th colspan="7">CHI TIẾT LÀM VIỆC</th>
                                </tr>
                                <tr style="background-color: whitesmoke;">
                                    <th class="text-center">STT</th>
                                    <th class="text-center">Giờ vào</th>
                                    <th class="text-center">Giờ ra</th>
                                    <th>Ghi chú</th>
                                    <th class="text-center">Nghỉ có phép</th>
                                    <th class="text-center">Nghỉ không phép</th>
                                    <th class="text-center">Giờ chính (h)</th>
                                    <th class="text-center">Tăng ca (h)</th>
                                </tr>
                                <?php $n_o = 0; ?>
                                @foreach($dataTimekeeping as $timekeeping)
                                    <?php
                                    $timekeepingId = $timekeeping->timekeepingId();
                                    ?>
                                    <tr>
                                        <td class="text-center">
                                            {!! $n_o += 1 !!}
                                        </td>
                                        <td class="text-center">
                                            @if(!$timekeeping->checkOff())
                                                <span>{!! date('d-m-Y',strtotime($timekeeping->timeBegin())) !!}</span>
                                                <b>{!! date('H:i',strtotime($timekeeping->timeBegin())) !!}</b>
                                            @else
                                                <span>---</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if(!$timekeeping->checkOff())
                                                @if(!empty($timekeeping->timeEnd()))
                                                    <span>{!! date('d-m-Y',strtotime($timekeeping->timeEnd())) !!}</span>
                                                    <b>{!! date('H:i',strtotime($timekeeping->timeEnd())) !!}</b>
                                                @else
                                                    <span>Null</span>
                                                @endif
                                            @else
                                                <span>---</span>
                                            @endif
                                        </td>
                                        <td>
                                            <em class="qc-color-grey"> {!! $timekeeping->note() !!}</em>
                                        </td>
                                        <td class="text-center">
                                            @if($timekeeping->checkOff() && $timekeeping->checkPermissionStatus())
                                                <b class="qc-color-red">{!! date('d-m-Y', strtotime($timekeeping->dateOff())) !!}</b>
                                            @else
                                                <span>---</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($timekeeping->checkOff() && !$timekeeping->checkPermissionStatus())
                                                <b>{!! date('d-m-Y', strtotime($timekeeping->dateOff())) !!}</b>
                                            @else
                                                <span>---</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if(!$timekeeping->checkOff())
                                                <b>{!! ($timekeeping->mainMinute() - $timekeeping->mainMinute()%60 )/60 !!}</b>
                                                <span>h</span>
                                                <b>{!! $timekeeping->mainMinute()%60 !!}</b>
                                            @else
                                                <span>---</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if(!$timekeeping->checkOff())
                                                <b>{!! ($timekeeping->plusMinute()-$timekeeping->plusMinute()%60)/60 !!}</b>
                                                <span>h</span>
                                                <b>{!! $timekeeping->plusMinute()%60 !!}</b>
                                            @else
                                                <span>---</span>
                                            @endif
                                        </td>

                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="text-center qc-color-red qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <span>Không có thông tin làm viêc</span>
                        </div>
                    </div>
                @endif
            </div>
            <div class="row">
                <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <a href="{!! route('qc.work.home') !!}">
                        <button type="button" class="qc_ad3d_container_close btn btn-primary">
                            Đóng
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
