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
if (!empty($companyStaffWorkId)) {
    $dataStaffWorkSalary = $modelCompanyStaffWork->staffWorkSalaryActivity($companyStaffWorkId);
    if (count($dataStaffWorkSalary) > 0) { # da co ban luong co ban cua he thong
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
    if (count($dataStaffWorkSalary) > 0) { //co bang luong moi
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
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed #C2C2C2;">
                <h3>CHI TIẾT LƯƠNG</h3>
            </div>
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="qc-border-none col-sx-12 col-sm-12 col-md-6 col-lg-6">
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
                    <div class="qc-border-none col-sx-12 col-sm-12 col-md-3 col-lg-3">
                        <em>Đến ngày: </em>
                        <span class="qc-color-red qc-font-bold">
                           {!! date('d-m-Y', strtotime($dataWork->toDate())) !!}
                        </span>
                    </div>
                </div>
                {{-- Bảng lương cơ bản   --}}
                <div class="row qc-container-table">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr class="qc-color-red">
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

                {{-- Thong tin lam viec   --}}
                <div class="row qc-container-table">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr class="qc-color-red">
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
                                    {!! floor(($sumPlusMinute-$sumPlusMinute%60)/60) !!}
                                    <b>h</b> {!! $sumPlusMinute%60 !!}
                                </td>
                                <td class="text-center">
                                    {!! floor(($sumMainMinute + $sumPlusMinute)/60) !!}
                                    <span>h</span> {!! ($sumMainMinute + $sumPlusMinute)%60 !!}
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
                            <tr class="qc-color-red">
                                <th colspan="7">CHI TIÊT LƯƠNG LÃNH</th>
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
                                        {!! $hFunction->currencyFormat($totalMoneyBeforePay) !!}
                                    </td>
                                    <td class="text-center">
                                        {!! $hFunction->currencyFormat($totalMoneyMinus) !!}
                                    </td>
                                    <td class="text-center qc-color-red">
                                        {!! $hFunction->currencyFormat($totalCurrentSalary -  $totalMoneyBeforePay - $totalMoneyMinus) !!}
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
                {{-- Chi tiết thanh toan lương   --}}
                <div class="row qc-container-table">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr class="qc-color-red">
                                <th colspan="7">CHI TIẾT THANH TOÁN LƯƠNG</th>
                            </tr>
                            <tr style="background-color: whitesmoke;">
                                <th class="text-center">STT</th>
                                <th class="text-center">Ngày</th>
                                <th class="text-center">Thủ quỹ</th>
                                <th class="text-center">Xác nhận</th>
                                <th class="text-center">Số tiền</th>
                            </tr>
                            @if(count($dataSalaryPayInfo) > 0)
                                @foreach($dataSalaryPayInfo as $salaryPay)
                                    <tr>
                                        <td class="text-center">
                                            {!! $n_o_pay = (isset($n_o_pay)) ? $n_o_pay + 1 : 1 !!}
                                        </td>
                                        <td class="text-center">
                                            {!!  date('d/m/Y',strtotime($salaryPay->datePay())) !!}
                                        </td>
                                        <td class="text-center">
                                            {!! $salaryPay->staff->fullName()  !!}
                                        </td>
                                        <td class="text-center">
                                            @if($salaryPay->checkConfirmed())
                                                <em class="qc-color-grey">Đã xác nhận</em>
                                            @else
                                                <em class="qc-color-grey">Chưa xác nhận</em>
                                            @endif
                                        </td>
                                        <td class="text-center qc-color-red">
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

                {{-- Chi tiet cham cong   --}}
                <div class="row qc-container-table">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr class="qc-color-red">
                                <th colspan="7">CHI TIẾT CHẤM CÔNG</th>
                            </tr>
                            <tr style="background-color: whitesmoke;">
                                <th class="text-center">STT</th>
                                <th class="text-center">Giờ vào</th>
                                <th class="text-center">Giờ ra</th>
                                <th class="text-center">Ghi chú</th>
                                <th class="text-center">Nghỉ có phép</th>
                                <th class="text-center">Nghỉ không phép</th>
                                <th class="text-center">Giờ chính (h)</th>
                                <th class="text-center">Tăng ca (h)</th>
                            </tr>
                            @if(count($dataTimekeeping)>0)
                                @foreach($dataTimekeeping as $timekeeping)
                                    <?php
                                    $timekeepingId = $timekeeping->timekeepingId();
                                    ?>
                                    <tr>
                                        <td class="text-center">
                                            {!! $n_o_timekeeping = (isset($n_o_timekeeping)) ? $n_o_timekeeping + 1 : 1 !!}
                                        </td>
                                        <td class="text-center">
                                            @if(!$timekeeping->checkOff())
                                                <span>{!! date('d-m-Y',strtotime($timekeeping->timeBegin())) !!}</span>
                                                <b style="color: blue;">
                                                    - {!! date('H:i',strtotime($timekeeping->timeBegin())) !!}</b>
                                            @else
                                                <span>---</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if(!$timekeeping->checkOff())
                                                @if(!empty($timekeeping->timeEnd()))
                                                    <span>{!! date('d-m-Y',strtotime($timekeeping->timeEnd())) !!}</span>
                                                    <b style="color: blue;">
                                                        - {!! date('H:i',strtotime($timekeeping->timeEnd())) !!}</b>
                                                @else
                                                    <span>Null</span>
                                                @endif
                                            @else
                                                <span>---</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
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
                                                <b class="qc-color-red">{!! date('d-m-Y', strtotime($timekeeping->dateOff())) !!}</b>
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
