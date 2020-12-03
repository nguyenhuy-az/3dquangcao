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
        $totalMoneyBonus = $dataWork->totalMoneyBonus(); # thuong
    } else {
        $infoSalaryBasic = false;
    }
}

$dataTimekeeping = $dataWork->infoTimekeeping();
?>
@extends('ad3d.components.container.container-10')
@section('qc_ad3d_container_content')
    <form class="frmWordEnd form qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12" method="post" role="form"
          action="{!! route('qc.ad3d.work.work.make_salary.post', $workId ) !!}">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
             style="padding-top: 20px;">
            <h3 style="color:red;">XUẤT BẢNG LƯƠNG</h3>
            <em style="color: blue;">(Khi hết tháng hoặc nghỉ làm)</em>
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
                <div class="row">
                    {{-- Bảng lương cơ bản   --}}
                    <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <tr>
                                    <th colspan="2" style="background-color: black;">
                                        <span style="color: yellow;">BẢNG LƯƠNG CƠ BẢN</span>
                                    </th>
                                </tr>
                                <tr>
                                    <td>Tổng lương</td>
                                    <td class="text-right qc-color-red ">
                                        {!! $hFunction->currencyFormat($totalSalaryBasic) !!}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Lương cơ bản</td>
                                    <td class="text-right">
                                        {!! $hFunction->currencyFormat($salaryBasic) !!}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Bảo hiểm</td>
                                    <td class="text-right">
                                        {!! $hFunction->currencyFormat($totalMoneyInsurance) !!}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Ngày phép</td>
                                    <td class="text-right">
                                        {!! $hFunction->currencyFormat($salaryOneHour*8) !!}
                                    </td>
                                </tr>
                                <tr>
                                    <td>PC Trách nhiệm</td>
                                    <td class="text-right">
                                        {!! $hFunction->currencyFormat($responsibility) !!}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Điện thoại/26Ng</td>
                                    <td class="text-right">
                                        {!! $hFunction->currencyFormat($usePhone) !!}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Xăng/26Ng</td>
                                    <td class="text-right">
                                        {!! $hFunction->currencyFormat($fuel) !!}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tăng ca/h</td>
                                    <td class="text-right">
                                        {!! $hFunction->currencyFormat($overtimeHour) !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Lương/h</th>
                                    <td class="text-right" style="color: red;">
                                        {!! $hFunction->currencyFormat($salaryOneHour) !!}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    {{-- thong tin lam viec   --}}
                    <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <tr>
                                    <th colspan="2" style="background-color: black;">
                                        <span style="color: yellow;">THÔNG TIN LÀM VIỆC</span>
                                    </th>
                                </tr>
                                <tr>
                                    <td>Giờ làm chính</td>
                                    <td class="text-right">
                                        {!! floor(($sumMainMinute-$sumMainMinute%60)/60) !!}
                                        <b>h</b> {!! $sumMainMinute%60 !!}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tăng ca *1.5</td>
                                    <td class="text-right">
                                        {!! floor(($sumPlusMinute-$sumPlusMinute%60)/60) !!}
                                        <b>h</b> {!! $sumPlusMinute%60 !!}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tổng giờ làm</td>
                                    <td class="text-right">
                                        {!! floor(($sumMainMinute + $sumPlusMinute)/60) !!}
                                        <span>h</span> {!! ($sumMainMinute + $sumPlusMinute)%60 !!}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Nghỉ có phép</td>
                                    <td class="text-right">
                                        {!! $dataWork->sumOffWorkTrue() !!}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Nghỉ không phép</td>
                                    <td class="text-right">
                                        {!! $dataWork->sumOffWorkFalse() !!}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    {{-- Chi tiết lương   --}}
                    <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <tr>
                                    <th colspan="2" style="background-color: black;">
                                        <span style="color: yellow;">CHI TIẾT LƯƠNG</span>
                                    </th>
                                </tr>
                                <tr>
                                    <td>Lương lãnh</td>
                                    <td class="text-right">
                                        {!! $hFunction->currencyFormat($totalCurrentSalary) !!}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Thưởng</td>
                                    <td class="text-right">
                                        <span>{!! $hFunction->currencyFormat($totalMoneyBonus) !!}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Ứng</td>
                                    <td class="text-right">
                                        {!! $hFunction->currencyFormat($totalMoneyBeforePay) !!}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Phạt</td>
                                    <td class="text-right">
                                        {!! $hFunction->currencyFormat($totalMoneyMinus) !!}
                                    </td>
                                </tr>
                                <tr>
                                    <td>P/C tăng ca</td>
                                    <td class="text-right">
                                        {!! $hFunction->currencyFormat($totalMoneyOvertimeHour) !!}
                                    </td>
                                </tr>
                                <tr>
                                    <td>lương còn lại</td>
                                    <td class="text-right qc-color-red">
                                        {!! $hFunction->currencyFormat($totalCurrentSalary + $totalMoneyBonus + $totalMoneyOvertimeHour  - $totalMoneyBeforePay - $totalMoneyMinus) !!}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row" style="padding-top: 10px; border-left: 5px solid blue; background-color: whitesmoke;">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group form-group-sm" style="margin: 0;">
                            <label class="radio-inline">
                                <input type="radio" checked="checked" name="txtWorkStatus" value="1"> Tiếp tục
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="txtWorkStatus" value="0"> Nghỉ
                            </label>
                        </div>
                    </div>
                    @if($dataWork->existTimeEndIsNullInTimekeeping())
                        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="padding-top: 10px;">
                            <em class="qc-color-red">
                                Tồn tại ngày chưa báo giờ ra, Nếu tính lương thì những ngày này sẽ được duyệt tự động
                            </em>
                        </div>
                    @endif
                    <div class="qc-padding-top-10 qc-padding-bot-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <input type="hidden" name="txtWork" value="{!! $workId !!}">
                        <button type="button" class="save btn btn-primary btn-sm">
                            TÍNH LƯƠNG
                        </button>
                        <button type="button" class="qc_ad3d_container_close btn btn-sm btn-default">
                            ĐÓNG
                        </button>
                    </div>
                </div>
                @if($hFunction->checkCount($dataTimekeeping))
                    <div class="row">
                        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <tr class="qc-color-red">
                                        <th colspan="3">
                                            CHI TIẾT LÀM VIỆC
                                        </th>
                                    </tr>
                                    <tr style="background-color: whitesmoke;">
                                        <th style="width: 120px;">GIỜ VÀO - RA</th>
                                        <th class="text-center" style="width: 140px;">GIỜ CHÍNH - TĂNG CA</th>
                                        <th>Ghi chú</th>
                                    </tr>
                                    <?php $n_o = 0; ?>
                                    @foreach($dataTimekeeping as $timekeeping)
                                        <?php
                                        $timekeepingId = $timekeeping->timekeepingId();
                                        ?>
                                        <tr>
                                            <td>
                                                @if(!$timekeeping->checkOff())
                                                    <span style="color: blue;">{!! date('d-m-Y',strtotime($timekeeping->timeBegin())) !!}</span>
                                                    <b>
                                                        {!! date('H:i',strtotime($timekeeping->timeBegin())) !!}
                                                    </b>
                                                    @if(!empty($timekeeping->timeEnd()))
                                                        <br/>
                                                        <span style="color: brown;">{!! date('d-m-Y',strtotime($timekeeping->timeEnd())) !!}</span>
                                                        <b> {!! date('H:i',strtotime($timekeeping->timeEnd())) !!}</b>
                                                        <br/>
                                                        @if($timekeeping->checkAfternoonStatus())
                                                            <em style="color: grey;">Có làm trưa</em>
                                                        @endif
                                                    @else
                                                        <em style="color: grey;">Không có giờ ra</em>
                                                    @endif
                                                @else
                                                    @if($timekeeping->checkPermissionStatus())
                                                        <b>{!! date('d-m-Y', strtotime($timekeeping->dateOff())) !!}</b>
                                                        <br/>
                                                        <em style="color: grey;">Nghỉ có phép</em>
                                                    @else
                                                        <b style="color: red;">{!! date('d-m-Y', strtotime($timekeeping->dateOff())) !!}</b>
                                                        <br/>
                                                        <em style="color: grey;">Nghỉ không phép</em>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if(!$timekeeping->checkOff())
                                                    <b style="color: blue;">{!! ($timekeeping->mainMinute() - $timekeeping->mainMinute()%60 )/60 !!}</b>
                                                    <span>h</span>
                                                    <b>{!! $timekeeping->mainMinute()%60 !!}</b>
                                                    <br/>
                                                    <b style="color: brown;">{!! ($timekeeping->plusMinute()-$timekeeping->plusMinute()%60)/60 !!}</b>
                                                    <span>h</span>
                                                    <b>{!! $timekeeping->plusMinute()%60 !!}</b>
                                                @else
                                                    <span>0</span>
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
