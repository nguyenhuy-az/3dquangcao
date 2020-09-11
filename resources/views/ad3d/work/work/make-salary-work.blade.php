<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$workId = $dataWork->workId();
$totalMoneyMinus = $dataWork->totalMoneyMinus();
$totalMoneyBeforePay = $dataWork->totalMoneyBeforePay();
$sumMainMinute = $dataWork->sumMainMinute();
$sumPlusMinute = $dataWork->sumPlusMinute() * 1.5;
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
        $sumPlusMinute = $dataWork->sumPlusMinute($workId);
        $totalMoneyOvertimeHour = ($sumPlusMinute / 60) * $overtimeHour; # tien phu cap an uong tang ca
        $totalMoneyInsurance = $dataStaffWorkSalary->totalMoneyInsurance();# phu cap bao hiem %
        $salaryOneHour = $dataStaffWorkSalary->salaryOnHour();
        $totalCurrentSalary = $dataWork->totalSalaryBasicOfWorkInMonth($workId);
    } else {
        $infoSalaryBasic = false;
    }
} else {
# DU LIEU VERSION CU - BO
    /*$dataStaffWorkSalary = $modelCompanyStaffWork->staffWorkSalaryActivityOfStaff($staffId);
    if (count($dataStaffWorkSalary) > 0) {
        $totalSalaryBasic = $dataStaffWorkSalary->totalSalary();
        $salaryBasic = $dataStaffWorkSalary->salary();
        $responsibility = $dataStaffWorkSalary->responsibility();# phu cap trach nhiem /26 ngay
        $usePhone = $dataStaffWorkSalary->usePhone();# phu cap su dung dien thoai
        $fuel = $dataStaffWorkSalary->fuel();# phu cap xang di lai
        $overtimeHour = $dataStaffWorkSalary->overtimeHour();# phu cap tang ca
        $sumPlusMinute = $dataWork->sumPlusMinute($workId);
        $totalMoneyOvertimeHour = ($sumPlusMinute/ 60) * $overtimeHour; # tien phu cap an uong tang ca
        $totalMoneyInsurance = $dataStaffWorkSalary->totalMoneyInsurance();# phu cap bao hiem %
        $salaryOneHour = $dataStaffWorkSalary->salaryOnHour();
        $totalCurrentSalary = $dataWork->totalSalaryBasicOfWorkInMonth();//$dataWork->totalCurrentSalary();
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
    */
}

$dataTimekeeping = $dataWork->infoTimekeeping();
?>
@extends('ad3d.components.container.container-10')
@section('qc_ad3d_container_content')
    <form class="frmWordEnd form qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12" method="post" role="form"
          action="{!! route('qc.ad3d.work.work.make_salary.post', $workId ) !!}">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
             style="padding-top: 20px;padding-bottom: 20px; border-bottom: 2px dashed brown;">
            <h3>XUẤT BẢNG LƯƠNG</h3>
            <em class="qc-color-red">(Thanh toán khi hết tháng hoặc nghĩ làm)</em>
        </div>
        @if (!empty($companyStaffWorkId))
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
            @if($infoSalaryBasic)
                <div class="row" style="margin-top: 20px; ">
                    <div class="frm_notify text-center qc-color-red col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>
                </div>
                <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    {{-- Bảng lương cơ bản   --}}
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <tr class="qc-color-red">
                                    <th class="" colspan="7">Bảng lương cơ bản</th>
                                </tr>
                                <tr style="background-color: black;color: yellow;">
                                    <th class="text-center">Tổng lương</th>
                                    <th class="text-center">Lương cơ bản</th>
                                    <th class="text-center ">Bảo hiểm</th>
                                    <th class="text-center ">Ngày phép</th>
                                    <th class="text-center ">PC Trách nhiệm</th>
                                    <th class="text-center ">Điện thoại/26Ng</th>
                                    <th class="text-center ">Xăng/26Ng</th>
                                    <th class="text-center ">Tăng ca/h</th>
                                    <th class="text-center ">Lương/h</th>
                                </tr>
                                <tr>
                                    <td class="text-center qc-color-red ">
                                        {!! $hFunction->currencyFormat($totalSalaryBasic) !!}
                                    </td>
                                    <td class="text-center ">
                                        {!! $hFunction->currencyFormat($salaryBasic) !!}
                                    </td>
                                    <td class="text-center ">
                                        {!! $hFunction->currencyFormat($totalMoneyInsurance) !!}
                                    </td>
                                    <td class="text-center ">
                                        {!! $hFunction->currencyFormat($salaryOneHour*8) !!}
                                    </td>
                                    <td class="text-center ">
                                        {!! $hFunction->currencyFormat($responsibility) !!}
                                    </td>
                                    <td class="text-center ">
                                        {!! $hFunction->currencyFormat($usePhone) !!}
                                    </td>
                                    <td class="text-center ">
                                        {!! $hFunction->currencyFormat($fuel) !!}
                                    </td>
                                    <td class="text-center ">
                                        {!! $hFunction->currencyFormat($overtimeHour) !!}
                                    </td>
                                    <td class="text-center ">
                                        {!! $hFunction->currencyFormat($salaryOneHour) !!}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    {{-- thong tin lam viec   --}}
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <tr class="qc-color-red">
                                    <th class="" colspan="7">Thông tin làm việc</th>
                                </tr>
                                <tr style="background-color: black;color: yellow;">
                                    <th class="text-center ">Giờ làm chính</th>
                                    <th class="text-center ">Tăng ca *1.5</th>
                                    <th class="text-center ">Tổng giờ làm</th>
                                    <th class="text-center ">Nghỉ có phép</th>
                                    <th class="text-center ">Nghỉ không phép</th>
                                </tr>
                                <tr>
                                    <td class="text-center ">
                                        {!! floor(($sumMainMinute-$sumMainMinute%60)/60) !!}
                                        <b>h</b> {!! $sumMainMinute%60 !!}
                                    </td>
                                    <td class="text-center ">
                                        {!! floor(($sumPlusMinute-$sumPlusMinute%60)/60) !!}
                                        <b>h</b> {!! $sumPlusMinute%60 !!}
                                    </td>
                                    <td class="text-center ">
                                        {!! floor(($sumMainMinute + $sumPlusMinute)/60) !!}
                                        <span>h</span> {!! ($sumMainMinute + $sumPlusMinute)%60 !!}
                                    </td>
                                    <td class="text-center ">
                                        {!! $dataWork->sumOffWorkTrue() !!}
                                    </td>
                                    <td class="text-center ">
                                        {!! $dataWork->sumOffWorkFalse() !!}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    {{-- Chi tiết lương   --}}
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <tr class="qc-color-red">
                                    <th class="" colspan="7">Chi tiết lương</th>
                                </tr>
                                <tr style="background-color: black;color: yellow;">
                                    <th class="text-center ">Lương lãnh</th>
                                    <th class="text-center ">Ứng</th>
                                    <th class="text-center ">Phạt</th>
                                    <th class="text-center ">P/C tăng ca</th>
                                    <th class="text-center ">lương còn lại</th>
                                </tr>
                                <tr>
                                    <td class="text-center ">
                                        {!! $hFunction->currencyFormat($totalCurrentSalary) !!}
                                    </td>
                                    <td class="text-center ">
                                        {!! $hFunction->currencyFormat($totalMoneyBeforePay) !!}
                                    </td>
                                    <td class="text-center ">
                                        {!! $hFunction->currencyFormat($totalMoneyMinus) !!}
                                    </td>
                                    <td class="text-center ">
                                        {!! $hFunction->currencyFormat($totalMoneyOvertimeHour) !!}
                                    </td>
                                    <td class="text-center qc-color-red ">
                                        {!! $hFunction->currencyFormat($totalCurrentSalary + $totalMoneyOvertimeHour  - $totalMoneyBeforePay - $totalMoneyMinus) !!}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="text-center form-group form-group-sm" style="margin: 0;">
                            <label class="radio-inline">
                                <input type="radio" checked="checked" name="txtWorkStatus" value="1"> Tiếp tục
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="txtWorkStatus" value="0"> Nghỉ
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @if($dataWork->existTimeEndIsNullInTimekeeping())
                        <div class="qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <em class="qc-color-red">
                                Tồn tại ngày chưa báo giờ ra, Nếu tính lương thì những ngày này sẽ không được tính
                            </em>
                        </div>
                    @endif
                    <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <input type="hidden" name="txtWork" value="{!! $workId !!}">
                        <button type="button" class="save btn btn-primary btn-sm">
                            Tính lương
                        </button>
                        <button type="reset" class="btn btn-sm btn-default">
                            Hủy
                        </button>
                        <button type="button" class="qc_ad3d_container_close btn btn-sm btn-default">
                            Đóng
                        </button>
                    </div>
                </div>
                @if(count($dataTimekeeping)>0)
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <tr class="qc-color-red">
                                    <th class="" colspan="8">Chi tiết làm việc</th>
                                </tr>
                                <tr style="background-color: whitesmoke;">
                                    <th class="text-center ">STT</th>
                                    <th class="text-center ">Giờ vào</th>
                                    <th class="text-center ">Giờ ra</th>
                                    <th class="text-center ">Làm trưa</th>
                                    <th class="text-center ">Nghỉ có phép</th>
                                    <th class="text-center ">Nghỉ không phép</th>
                                    <th class="text-center ">Giờ chính (h)</th>
                                    <th class="text-center ">Tăng ca (h)</th>
                                    <th class="">Ghi chú</th>
                                </tr>
                                <?php $n_o = 0; ?>
                                @foreach($dataTimekeeping as $timekeeping)
                                    <?php
                                    $timekeepingId = $timekeeping->timekeepingId();
                                    ?>
                                    <tr>
                                        <td class="text-center ">
                                            {!! $n_o += 1 !!}
                                        </td>
                                        <td class="text-center ">
                                            @if(!$timekeeping->checkOff())
                                                <span>{!! date('d-m-Y',strtotime($timekeeping->timeBegin())) !!}</span>
                                                <b style="color: blue;">
                                                    - {!! date('H:i',strtotime($timekeeping->timeBegin())) !!}</b>
                                            @else
                                                <span>---</span>
                                            @endif
                                        </td>
                                        <td class="text-center ">
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
                                        <td class="text-center  qc-color-grey">
                                            @if($timekeeping->checkAfternoonStatus())
                                                <em>Có</em>
                                            @else
                                                <em>Không</em>
                                            @endif
                                        </td>
                                        <td class="text-center ">
                                            @if($timekeeping->checkOff() && $timekeeping->checkPermissionStatus())
                                                <b>{!! date('d-m-Y', strtotime($timekeeping->dateOff())) !!}</b>
                                            @else
                                                <span>---</span>
                                            @endif
                                        </td>
                                        <td class="text-center ">
                                            @if($timekeeping->checkOff() && !$timekeeping->checkPermissionStatus())
                                                <b>{!! date('d-m-Y', strtotime($timekeeping->dateOff())) !!}</b>
                                            @else
                                                <span>---</span>
                                            @endif
                                        </td>
                                        <td class="text-center ">
                                            @if(!$timekeeping->checkOff())
                                                <b>{!! ($timekeeping->mainMinute() - $timekeeping->mainMinute()%60 )/60 !!}</b>
                                                <span>h</span>
                                                <b>{!! $timekeeping->mainMinute()%60 !!}</b>
                                            @else
                                                <span>---</span>
                                            @endif
                                        </td>
                                        <td class="text-center ">
                                            @if(!$timekeeping->checkOff())
                                                <b>{!! ($timekeeping->plusMinute()-$timekeeping->plusMinute()%60)/60 !!}</b>
                                                <span>h</span>
                                                <b>{!! $timekeeping->plusMinute()%60 !!}</b>
                                            @else
                                                <span>---</span>
                                            @endif
                                        </td>
                                        <td>
                                            <em class="qc-color-grey"> {!! $timekeeping->note() !!}</em>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                @endif
            @else
                <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <span class="qc-color-red">Chưa có bảng lương trên hệ thống</span>
                </div>
            @endif
        @else
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <span class="qc-color-red">DỮ LIỆU CŨ - KHÔNG CÓ THÔNG TIN LÀM VIỆC</span>
            </div>
        @endif
    </form>
@endsection
