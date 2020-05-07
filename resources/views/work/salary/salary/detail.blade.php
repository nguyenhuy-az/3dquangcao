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
$dataWork = $dataSalary->work;
$benefitMoney = $dataSalary->benefitMoney();

$workId = $dataWork->workId();
$companyStaffWorkId = $dataWork->companyStaffWorkId();
$staffId = $dataWork->staffId();
$totalMoneyMinus = $dataWork->totalMoneyMinus();
$totalMoneyBeforePay = $dataWork->totalMoneyBeforePay();
$sumMainMinute = $dataWork->sumMainMinute();
$sumPlusMinute = $dataWork->sumPlusMinute();
$sumPlusMinute_1_5 = $sumPlusMinute * 1.5;
$sumMinusMinute = $dataWork->sumMinusMinute();
$totalMinute = $sumMainMinute + $sumPlusMinute - $sumMinusMinute;
$infoSalaryBasic = true;

# phien ban moi - nv lam nhieu cty
if (!$hFunction->checkEmpty($companyStaffWorkId)) {
    $dataStaffWorkSalary = $modelCompanyStaffWork->staffWorkSalaryActivity($companyStaffWorkId);
    if ($hFunction->checkCount($dataStaffWorkSalary)) { # da co ban luong co ban cua he thong
        $totalSalaryBasic = $dataStaffWorkSalary->totalSalary();
        $salaryBasic = $dataStaffWorkSalary->salary();
        $responsibility = $dataStaffWorkSalary->responsibility();# phu cap trach nhiem /26 ngay
        $usePhone = $dataStaffWorkSalary->usePhone();# phu cap su dung dien thoai
        $fuel = $dataStaffWorkSalary->fuel();# phu cap xang di lai
        $overtimeHour = $dataStaffWorkSalary->overtimeHour();# phu cap tang ca
        $sumPlusMinute = $dataWork->sumPlusMinute($workId);
        $totalMoneyOvertimeHour = ($sumPlusMinute / 60) * $overtimeHour; # tien phu cap an uong tang ca
        $totalMoneyInsurance = $dataStaffWorkSalary->totalMoneyInsurance();# phu cap bao hiem %
        $salaryOneHour = $dataStaffWorkSalary->salaryOnHour();#luong theo gio
        $totalCurrentSalary = $dataWork->totalSalaryBasicOfWorkInMonth($workId);
    } else {
        $infoSalaryBasic = false;
    }

} else {
    $dataStaffWorkSalary = $modelCompanyStaffWork->staffWorkSalaryActivityOfStaff($staffId);
    if ($hFunction->checkCount($dataStaffWorkSalary)) { //co bang luong moi
        $totalSalaryBasic = $dataStaffWorkSalary->totalSalary();
        $salaryBasic = $dataStaffWorkSalary->salary();
        $responsibility = $dataStaffWorkSalary->responsibility();# phu cap trach nhiem /26 ngay
        $usePhone = $dataStaffWorkSalary->usePhone();# phu cap su dung dien thoai
        $fuel = $dataStaffWorkSalary->fuel();# phu cap xang di lai
        $overtimeHour = $dataStaffWorkSalary->overtimeHour();# phu cap tang ca
        $sumPlusMinute = $dataWork->sumPlusMinute($workId);
        $totalMoneyOvertimeHour = ($sumPlusMinute / 60) * $overtimeHour; # tien phu cap an uong tang ca
        $totalMoneyInsurance = $dataStaffWorkSalary->totalMoneyInsurance();# phu cap bao hiem %
        $salaryOneHour = $dataStaffWorkSalary->salaryOnHour();
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
            //$totalMinusFuelInMonth = 0; # tru tien xang nghi trong tháng
            $salaryOneHour = floor($salaryBasic / 208);
            $totalCurrentSalary = $dataWork->totalSalaryBasicOfWorkInMonth();//$dataWork->totalCurrentSalary();
        } else {
            $infoSalaryBasic = false;
        }
    }
}
// 2 = tong tien mua vat tu da duyet chua thanh toan
$totalMoneyImportOfStaff = $modelStaff->totalMoneyImportOfStaff($staffId, date('Y-m', strtotime($dataWork->fromDate())), 2);
#thong tin thanh toan
$dataSalaryPayInfo = $dataSalary->infoSalaryPay();
$totalPaid = $dataSalary->totalPaid();
#cham cong
$dataTimekeeping = $dataWork->infoTimekeeping();
?>
@extends('work.index')
@section('qc_work_body')
    <div class="row">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <a class=" qc-link-red" onclick="qc_main.page_back();">
                    <i class="qc-font-size-14 glyphicon glyphicon-backward"></i>
                    <span class="qc-font-size-16" style="color: blue;">Trởlại</span>
                </a>
            </div>
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed #C2C2C2;">
                <h3>CHI TIẾT BẢNG LƯƠNG</h3>
                <em>Từ ngày: </em>
                <span class="qc-color-red qc-font-bold">
                    {!! $hFunction->convertDateDMYFromDatetime($dataWork->fromDate()) !!}
                </span>
                <em>Đến ngày: </em>
                <span class="qc-color-red qc-font-bold">
                   {!! $hFunction->convertDateDMYFromDatetime($dataWork->toDate()) !!}
                </span>
            </div>
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
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
                                                                <b>{!! $hFunction->currencyFormat($totalSalaryBasic) !!}</b>
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
                                                                <b>{!! $hFunction->currencyFormat($salaryOneHour) !!}</b>
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
                                                                {!! floor(($sumMainMinute + $sumPlusMinute)/60) !!}
                                                                <span>h</span> {!! ($sumMainMinute + $sumPlusMinute)%60 !!}
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
                            <div class="qc-container-table  qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
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
                                                    <em>Tổng lương:</em>
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
                                                    <b>+ {!! $hFunction->currencyFormat($totalMoneyOvertimeHour) !!}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <em class="qc-color-grey">Mua vật tư (Đã duyệt chưa TT):</em>
                                                </td>
                                                <td class="text-right">
                                                    <b> + {!! $hFunction->currencyFormat($totalMoneyImportOfStaff) !!}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <em class="qc-color-grey">Cộng thêm:</em>
                                                </td>
                                                <td class="text-right">
                                                    <b>+ {!! $hFunction->currencyFormat($benefitMoney) !!}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <em class="qc-color-grey">Ứng:</em>
                                                </td>
                                                <td class="text-right">
                                                    <b>- {!! $hFunction->currencyFormat($totalMoneyBeforePay) !!}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <em class="qc-color-grey">Phạt:</em>
                                                </td>
                                                <td class="text-right">
                                                    <b>- {!! $hFunction->currencyFormat($totalMoneyMinus) !!}</b>
                                                </td>
                                            </tr>

                                            <tr style="border-top: 1px solid whitesmoke; color: brown;">
                                                <td>
                                                    <em>Lương còn lại:</em>
                                                </td>
                                                <td class="text-right">
                                                    <b>{!! $hFunction->currencyFormat($totalCurrentSalary + $totalMoneyImportOfStaff + $benefitMoney -  $totalMoneyBeforePay - $totalMoneyMinus) !!}</b>
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
                {{-- Chi tiết thanh toan lương   --}}
                <div class="row">
                    <div class="qc-container-table col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <tr>
                                    <th colspan="5">
                                        <i class="qc-font-size-14 glyphicon glyphicon-credit-card"></i>
                                        <label class="qc-color-red">CHI TIẾT THANH TOÁN LƯƠNG</label>
                                    </th>
                                </tr>
                                <tr style="background-color: whitesmoke;">
                                    <th class="text-center" style="width: 20px;">STT</th>
                                    <th>Ngày</th>
                                    <th>Thủ quỹ</th>
                                    <th class="text-center">Xác nhận</th>
                                    <th class="text-right">Số tiền</th>
                                </tr>
                                @if($hFunction->checkCount($dataSalaryPayInfo))
                                    @foreach($dataSalaryPayInfo as $salaryPay)
                                        <tr>
                                            <td class="text-center">
                                                {!! $n_o_pay = (isset($n_o_pay)) ? $n_o_pay + 1 : 1 !!}
                                            </td>
                                            <td>
                                                {!!  $hFunction->convertDateDMYFromDatetime($salaryPay->datePay()) !!}
                                            </td>
                                            <td>
                                                {!! $salaryPay->staff->fullName()  !!}
                                            </td>
                                            <td class="text-center">
                                                @if($salaryPay->checkConfirmed())
                                                    <em class="qc-color-grey">Đã xác nhận</em>
                                                @else
                                                    <em class="qc-color-grey">Chưa xác nhận</em>
                                                @endif
                                            </td>
                                            <td class="text-right qc-color-red">
                                                {!! $hFunction->currencyFormat($salaryPay->money()) !!}
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">
                                            Không có thông tin thanh toán
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Chi tiet cham cong   --}}
                <div class="row">
                    <div class="qc-container-table col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <tr>
                                    <th colspan="8">
                                        <i class="qc-font-size-14 glyphicon glyphicon-list-alt"></i>
                                        <label class="qc-color-red">CHI TIẾT CHẤM CÔNG</label>
                                    </th>
                                </tr>
                                <tr style="background-color: whitesmoke;">
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
                                    <?php $n_o_timekeeping = 0; ?>
                                    @foreach($dataTimekeeping as $timekeeping)
                                        <?php
                                        $timekeepingId = $timekeeping->timekeepingId();
                                        ?>
                                        <tr class="@if($n_o_timekeeping%2) info @endif">
                                            <td class="text-center ">
                                                {!! $n_o_timekeeping += 1 !!}
                                            </td>
                                            <td class="text-center">
                                                @if(!$timekeeping->checkOff())
                                                    <span>{!! $hFunction->convertDateDMYFromDatetime($timekeeping->timeBegin()) !!}</span>
                                                    <b style="color: blue;">
                                                        - {!! date('H:i',strtotime($timekeeping->timeBegin())) !!}</b>
                                                @else
                                                    <span>---</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if(!$timekeeping->checkOff())
                                                    @if(!$hFunction->checkEmpty($timekeeping->timeEnd()))
                                                        <span>{!! $hFunction->convertDateDMYFromDatetime($timekeeping->timeEnd()) !!}</span>
                                                        <b style="color: blue;">
                                                            - {!! date('H:i',strtotime($timekeeping->timeEnd())) !!}</b>
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
                                                    <b class="qc-color-red">{!! $hFunction->convertDateDMYFromDatetime($timekeeping->dateOff()) !!}</b>
                                                @else
                                                    <span>---</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($timekeeping->checkOff() && !$timekeeping->checkPermissionStatus())
                                                    <b class="qc-color-red">{!! $hFunction->convertDateDMYFromDatetime($timekeeping->dateOff()) !!}</b>
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
                                        <td colspan="8">
                                            Không có thông tin chấm công
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <button type="button" class="btn btn-sm btn-primary" onclick="qc_main.page_back();">
                            Đóng
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
