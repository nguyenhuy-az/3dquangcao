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
$dataWork = $dataStaff->firstInfoActivityToWork($loginStaffId, date('Y-m', strtotime("$loginYear-$loginMonth")));
$workId = $dataWork->workId();
$totalMoneyMinus = $dataWork->totalMoneyMinus();
$totalMoneyBeforePay = $dataWork->totalMoneyConfirmedBeforePay();
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
#cham cong
$dataTimekeeping = $dataWork->infoTimekeeping();
if ($hFunction->checkCount($dataWork)) {
    $totalMoneyBonus = $dataWork->totalMoneyBonus();
} else {
    $totalMoneyBonus = 0;
}
?>
@extends('work.timekeeping.work.index')
@section('qc_work_timekeeping_work_body')
    <div class="row">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            {{-- chi tiêt --}}
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="qc-border-none col-sx-12 col-sm-12 col-md-6 col-lg-6">
                        <em>Nhân Viên: </em>
                        <span class="qc-font-bold">
                            @if(!$hFunction->checkEmpty($companyStaffWorkId))
                                {!! $dataWork->companyStaffWork->staff->fullName() !!}
                            @else
                                {!! $dataWork->staff->fullName() !!}
                            @endif
                        </span>
                    </div>
                    <div class="qc-border-none col-sx-12 col-sm-12 col-md-3 col-lg-3">
                        <em>Từ ngày: </em>
                        <span class="qc-color-red qc-font-bold">
                            {!! $hFunction->convertDateDMYFromDatetime($dataWork->fromDate()) !!}
                        </span>
                    </div>
                    <div class="qc-border-none col-sx-12 col-sm-12 col-md-3 col-lg-3">
                        <em>Đến ngày: </em>
                        <span class="qc-color-red qc-font-bold">
                           {!! $hFunction->convertDateDMYFromDatetime($dataWork->toDate()) !!}
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6">
                        {{-- Bảng lương cơ bản   --}}
                        <div class="row">
                            <div class="qc-padding-top-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="qc-container-table qc-container-table-border-none table-responsive">
                                    <table class="table table-hover qc-margin-bot-none">
                                        <tr class="qc-link-green" data-toggle="modal"
                                            data-target="#qc_work_system_info_salary_basic_show">
                                            <th>
                                                <i class="qc-font-size-14 glyphicon glyphicon-play"></i>
                                                <span class="qc-font-size-14">Bảng lương cơ bản</span>
                                            </th>
                                            <th class="text-right">
                                                <i class="qc-font-size-16 glyphicon glyphicon-eye-open"></i>
                                            </th>
                                        </tr>
                                    </table>
                                </div>
                                <div class="modal fade" id="qc_work_system_info_salary_basic_show" tabindex="-1"
                                     role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header" style="background-color: #d7d7d7;">
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span aria-hidden="true">&times;</span><span
                                                            class="sr-only">Close</span>
                                                </button>
                                                <h4 class="qc-color-red modal-title" id="myModalLabel">
                                                    BẢNG LƯƠNG CƠ BẢN
                                                </h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="table-responsive">
                                                    <table class="table table-hover">
                                                        <tr>
                                                            <td>
                                                                <em class=" qc-color-grey">Tổng lương:</em>
                                                            </td>
                                                            <td class="text-right">
                                                                <b class="qc-color-red">{!! $hFunction->currencyFormat($totalSalaryBasic) !!}</b>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <em class="qc-color-grey">Lương cơ bản:</em>
                                                            </td>
                                                            <td class="text-right">
                                                                <b>{!! $hFunction->currencyFormat($salaryBasic) !!}</b>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <em class="qc-color-grey">Bảo hiểm:</em>
                                                            </td>
                                                            <td class="text-right">
                                                                <b>{!! $hFunction->currencyFormat($totalMoneyInsurance) !!}</b>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <em class="qc-color-grey">Ngày phép:</em>
                                                            </td>
                                                            <td class="text-right">
                                                                <b>{!! $hFunction->currencyFormat($salaryOneHour*8) !!}</b>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <em class="qc-color-grey">PC Trách nhiệm:</em>
                                                            </td>
                                                            <td class="text-right">
                                                                {!! $hFunction->currencyFormat($responsibility) !!}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <em class="qc-color-grey">Tăng ca/h:</em>
                                                            </td>
                                                            <td class="text-right">
                                                                <b>{!! $hFunction->currencyFormat($overtimeHour) !!}</b>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <em class="qc-color-grey">Điện thoại/26Ng:</em>
                                                            </td>
                                                            <td class="text-right">
                                                                <b>{!! $hFunction->currencyFormat($usePhone) !!}</b>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <em class="qc-color-grey">Xăng/26Ng:</em>
                                                            </td>
                                                            <td class="text-right">
                                                                <b>{!! $hFunction->currencyFormat($fuel) !!}</b>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <em class="qc-color-grey">Lương/h:</em>
                                                            </td>
                                                            <td class="text-right">
                                                                <b class="qc-color-red">{!! $hFunction->currencyFormat($salaryOneHour) !!}</b>
                                                            </td>
                                                        </tr>

                                                    </table>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-sm btn-primary"
                                                        data-dismiss="modal">Đóng
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Thong tin lam viec   --}}
                        <div class="row">
                            <div class="qc-padding-top-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="qc-container-table qc-container-table-border-none table-responsive">
                                    <table class="table table-hover qc-margin-bot-none">
                                        <tr class="qc-link-green" data-toggle="modal"
                                            data-target="#qc_work_system_info_salary_work_statistic_show">
                                            <th>
                                                <i class="qc-font-size-14 glyphicon glyphicon-play"></i>
                                                <span class="qc-font-size-14">Thống kê làm việc</span>
                                            </th>
                                            <th class="text-right">
                                                <i class="qc-font-size-16 glyphicon glyphicon-eye-open"></i>
                                            </th>
                                        </tr>
                                    </table>
                                </div>
                                <div class="modal fade" id="qc_work_system_info_salary_work_statistic_show"
                                     tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header" style="background-color: #d7d7d7;">
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span aria-hidden="true">&times;</span><span
                                                            class="sr-only">Close</span>
                                                </button>
                                                <h4 class="qc-color-red modal-title" id="myModalLabel">
                                                    THỐNG KÊ LÀM VIỆC
                                                </h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="table-responsive">
                                                    <table class="table table-hover qc-margin-bot-none">
                                                        <tr>
                                                            <td>
                                                                <em class=" qc-color-grey">Giờ làm chính:</em>
                                                            </td>
                                                            <td class="text-right">
                                                                {!! floor(($sumMainMinute-$sumMainMinute%60)/60) !!}
                                                                <b>h</b> {!! $sumMainMinute%60 !!}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <em class="qc-color-grey">Tăng ca *1.5:</em>
                                                            </td>
                                                            <td class="text-right">
                                                                {!! floor(($sumPlusMinute-$sumPlusMinute%60)/60) !!}
                                                                <b>h</b> {!! $sumPlusMinute%60 !!}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <em class="qc-color-grey">Tổng giờ làm:</em>
                                                            </td>
                                                            <td class="text-right">
                                                                {!! floor(($sumMainMinute + $sumPlusMinute_1_5)/60) !!}
                                                                <span>h</span> {!! ($sumMainMinute + $sumPlusMinute_1_5)%60 !!}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <em class="qc-color-grey">Nghỉ có phép:</em>
                                                            </td>
                                                            <td class="text-right">
                                                                <b>{!! $dataWork->sumOffWorkTrue() !!}</b>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <em class="qc-color-grey">Nghỉ không phép:</em>
                                                            </td>
                                                            <td class="text-right">
                                                                <b>{!! $dataWork->sumOffWorkFalse() !!}</b>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-sm btn-primary"
                                                        data-dismiss="modal">Đóng
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="row">
                            {{-- Chi tiết lương   --}}
                            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="table-responsive">
                                    <table class="table table-hover qc-margin-bot-none">
                                        <tr style="background-color: whitesmoke;">
                                            <th colspan="2">
                                                <i class="qc-font-size-14 glyphicon glyphicon-usd"></i>
                                                <b class="qc-color-red">CHI TIÊT LƯƠNG LÃNH</b>
                                            </th>
                                        </tr>
                                        @if($infoSalaryBasic)
                                            <tr style="color: brown;">
                                                <td>
                                                    <em>Lương lãnh:</em>
                                                </td>
                                                <td class="text-right">
                                                    <b>{!! $hFunction->currencyFormat($totalCurrentSalary) !!}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    P/C tăng ca ({!! floor(($sumPlusMinute-$sumPlusMinute%60)/60) !!}
                                                    <b>h</b> {!! $sumPlusMinute%60 !!})
                                                </td>
                                                <td class="text-right">
                                                    <b> + {!! $hFunction->currencyFormat($totalMoneyOvertimeHour) !!}</b>
                                                </td>
                                            </tr>
                                            <tr style="color: brown;">
                                                <td>
                                                    <span>Thưởng:</span>
                                                    <em class="qc-color-grey"> - (Tạm thời)</em>
                                                </td>
                                                <td class="text-right">
                                                    <b>+ {!! $hFunction->currencyFormat($totalMoneyBonus) !!}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <em class="qc-color-grey">Ứng:</em>
                                                </td>
                                                <td class="text-right">
                                                    <a class="qc-link" href="{!! route('qc.work.salary.before_pay.get') !!}" >
                                                        - {!! $hFunction->currencyFormat($totalMoneyBeforePay) !!}
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span>Phạt</span>
                                                    <em class="qc-color-grey"> - (Tạm thời)</em>
                                                </td>
                                                <td class="text-right">
                                                    <a class="qc-link" href="{!! route('qc.work.minus_money.get') !!}">
                                                        - {!! $hFunction->currencyFormat($totalMoneyMinus) !!}
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr style="border-top: 1px solid whitesmoke; color: brown;">
                                                <td>
                                                    <em>Lương còn lại:</em>
                                                </td>
                                                <td class="text-right">
                                                    <b class="qc-color-red">{!! $hFunction->currencyFormat($totalCurrentSalary + $totalMoneyBonus -  $totalMoneyBeforePay - $totalMoneyMinus) !!}</b>
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
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <tr>
                                    <th colspan="8">
                                        <i class="qc-font-size-14 glyphicon glyphicon-list-alt"></i>
                                        <label class="qc-color-red">CHI TIẾT CHẤM CÔNG</label>
                                    </th>
                                </tr>
                                <tr style="background-color: black; color: yellow;">
                                    <th class="text-center" style="width: 20px;">STT</th>
                                    <th class="text-center">Giờ vào</th>
                                    <th class="text-center">Giờ ra</th>
                                    <th>Ghi chú</th>
                                    <th class="text-center">Nghỉ có phép</th>
                                    <th class="text-center">Nghỉ không phép</th>
                                    <th class="text-center">Giờ chính (h)</th>
                                    <th class="text-center">Tăng ca (h)</th>
                                </tr>
                                @if($hFunction->checkCount($dataTimekeeping))
                                    <?php $n_o = 0; ?>
                                    @foreach($dataTimekeeping as $timekeeping)
                                        <?php
                                        $timekeepingId = $timekeeping->timekeepingId();
                                        $timekeepingBegin = $timekeeping->timeBegin();
                                        $timekeepingEnd = $timekeeping->timeEnd();
                                        $timekeepingNote = $timekeeping->note();
                                        $timekeepingConfirmNote = $timekeeping->confirmNote();
                                        ?>
                                        <tr class="@if($n_o%2) info @endif">
                                            <td class="text-center">
                                                {!! $n_o += 1 !!}
                                            </td>
                                            <td class="text-center">
                                                @if(!$timekeeping->checkOff())
                                                    <span>{!! $hFunction->convertDateDMYFromDatetime($timekeepingBegin) !!}</span>
                                                    <b>{!! date('H:i',strtotime($timekeepingBegin)) !!}</b>
                                                @else
                                                    <span>---</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if(!$timekeeping->checkOff())
                                                    @if(!$hFunction->checkEmpty($timekeepingEnd))
                                                        <span>{!! $hFunction->convertDateDMYFromDatetime($timekeepingEnd) !!}</span>
                                                        <b>{!! date('H:i',strtotime($timekeepingEnd)) !!}</b>
                                                    @else
                                                        <span>Null</span>
                                                    @endif
                                                @else
                                                    <span>---</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(!empty($timekeepingConfirmNote))
                                                    <em class="qc-color-grey"> {!! $timekeepingConfirmNote !!}</em>
                                                @else
                                                    <em class="qc-color-grey"> {!! $timekeepingNote !!}</em>
                                                @endif

                                            </td>
                                            <td class="text-center">
                                                @if($timekeeping->checkOff() && $timekeeping->checkPermissionStatus())
                                                    <b class="qc-color-red">{!! $hFunction->convertDateDMYFromDatetime($timekeeping->dateOff()) !!}</b>
                                                @else
                                                    <span>---</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($timekeeping->checkOff() && !$timekeeping->checkPermissionStatus())
                                                    <b>{!! $hFunction->convertDateDMYFromDatetime($timekeeping->dateOff()) !!}</b>
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
                                @else
                                    <tr>
                                        <td class="text-center qc-color-red" colspan="8">
                                            <span>Không có thông tin làm viêc</span>
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <a href="{!! route('qc.work.home') !!}">
                        <button type="button" class="btn btn-primary">
                            Đóng
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
