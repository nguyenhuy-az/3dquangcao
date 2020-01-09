<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 */
$hFunction = new Hfunction();
$workId = $dataWork->workId();
$totalMoneyMinus = $dataWork->totalMoneyMinus();
$totalMoneyBeforePay = $dataWork->totalMoneyBeforePay();
$sumMainMinute = $dataWork->sumMainMinute();
$sumPlusMinute = $dataWork->sumPlusMinute();
$sumPlusMinute_1_5 = $dataWork->sumPlusMinute() * 1.5;
$sumMinusMinute = $dataWork->sumMinusMinute();
$companyStaffWorkId = $dataWork->companyStaffWorkId();

$dataStaffWorkSalary = $modelCompanyStaffWork->staffWorkSalaryActivity($companyStaffWorkId);
$salaryBasic = $dataStaffWorkSalary->salary();
$responsibility = $dataStaffWorkSalary->responsibility();# phu cap trach nhiem /26 ngay
$usePhone = $dataStaffWorkSalary->usePhone();# phu cap su dung dien thoai
$fuel = $dataStaffWorkSalary->fuel();# phu cap xang di lai
$overtimeHour = $dataStaffWorkSalary->overtimeHour();# phu cap tang ca
$totalMoneyOvertimeHour = ($sumPlusMinute / 60) * $overtimeHour; # tien phu cap an uong tang ca
$totalMoneyInsurance = $dataStaffWorkSalary->totalMoneyInsurance();# phu cap bao hiem %
$totalMinusFuelInMonth = $dataWork->totalMinusFuelInMonth(); # tru tien xang nghi trong tháng
$salaryOneHour = $dataStaffWorkSalary->salaryOnHour();
$totalCurrentSalary = $dataWork->totalSalaryBasicOfWorkInMonth($workId);//$dataWork->totalCurrentSalary();

$dataTimekeeping = $dataWork->infoTimekeeping();
?>
@extends('ad3d.components.container.container-10')
@section('qc_ad3d_container_content')
    <div class="qc-padding-top-20 qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed #C2C2C2;">
            <h3>CHI TIẾT LÀM VIỆC</h3>
        </div>

        {{-- thông tin khách hàng --}}
        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-3 col-lg-3">
                    <em>Tên:</em>
                    <span class="qc-font-bold"> {!! $dataWork->companyStaffWork->staff->fullName() !!}</span>
                </div>
                <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-3 col-lg-3">
                    <em>Mã NV:</em>
                    <span class="qc-font-bold">{!! $dataWork->companyStaffWork->staff->nameCode() !!}</span>
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

        </div>

        {{-- chi tiêt --}}
        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            {{-- Bảng lương cơ bản   --}}
            <div class="row">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr class="qc-color-red">
                            <th class="qc-padding-none" colspan="7">Bảng lương cơ bản</th>
                        </tr>
                        <tr style="background-color: whitesmoke;">
                            <th class="text-center qc-padding-none">Lương cơ bản</th>
                            <th class="text-center qc-padding-none">Bảo hiểm</th>
                            <th class="text-center qc-padding-none">PC Trách nhiệm</th>
                            <th class="text-center qc-padding-none">Điện thoại/26Ng</th>
                            <th class="text-center qc-padding-none">Xăng/26Ng</th>
                            <th class="text-center qc-padding-none">Tăng ca/h</th>
                            <th class="text-center qc-padding-none">Lương/h</th>
                        </tr>
                        <tr>
                            <td class="text-center qc-padding-none">
                                {!! $hFunction->currencyFormat($salaryBasic) !!}
                            </td>
                            <td class="text-center qc-padding-none">
                                {!! $hFunction->currencyFormat($totalMoneyInsurance) !!}
                            </td>
                            <td class="text-center qc-padding-none">
                                {!! $hFunction->currencyFormat($responsibility) !!}
                            </td>
                            <td class="text-center qc-padding-none">
                                {!! $hFunction->currencyFormat($usePhone) !!}
                            </td>
                            <td class="text-center qc-padding-none">
                                {!! $hFunction->currencyFormat($fuel) !!}
                            </td>
                            <td class="text-center qc-padding-none">
                                {!! $hFunction->currencyFormat($overtimeHour) !!}
                            </td>
                            <td class="text-center qc-padding-none">
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
                            <th class="qc-padding-none" colspan="7">Thông tin làm việc</th>
                        </tr>
                        <tr style="background-color: whitesmoke;">
                            <th class="text-center qc-padding-none">Giờ làm chính</th>
                            <th class="text-center qc-padding-none">Tăng ca *1.5</th>
                            <th class="text-center qc-padding-none">Tổng giờ làm</th>
                            <th class="text-center qc-padding-none">Nghỉ có phép</th>
                            <th class="text-center qc-padding-none">Nghỉ không phép</th>
                        </tr>
                        <tr>
                            <td class="text-center qc-padding-none">
                                {!! floor(($sumMainMinute-$sumMainMinute%60)/60) !!}
                                <b>h</b> {!! $sumMainMinute%60 !!}
                            </td>
                            <td class="text-center qc-padding-none">
                                {!! floor(($sumPlusMinute_1_5-$sumPlusMinute_1_5%60)/60) !!}
                                <b>h</b> {!! $sumPlusMinute_1_5%60 !!}
                            </td>
                            <td class="text-center qc-padding-none">
                                {!! floor(($sumMainMinute + $sumPlusMinute_1_5)/60) !!}
                                <span>h</span> {!! ($sumMainMinute + $sumPlusMinute_1_5)%60 !!}
                            </td>
                            <td class="text-center qc-padding-none">
                                {!! $dataWork->sumOffWorkTrue() !!}
                            </td>
                            <td class="text-center qc-padding-none">
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
                            <th class="qc-padding-none" colspan="7">Chi tiết lương</th>
                        </tr>
                        <tr style="background-color: whitesmoke;">
                            <th class="text-center qc-padding-none">Phụ cấp TC</th>
                            <th class="text-center qc-padding-none">Lương lãnh</th>
                            <th class="text-center qc-padding-none">Ứng</th>
                            <th class="text-center qc-padding-none">Phạt</th>
                            <th class="text-center qc-padding-none">lương còn lại</th>
                        </tr>
                        <tr>
                            <td class="text-center qc-padding-none">
                                {!! $hFunction->currencyFormat($totalMoneyOvertimeHour) !!}
                            </td>
                            <td class="text-center qc-padding-none">
                                {!! $hFunction->currencyFormat($totalCurrentSalary) !!}
                            </td>
                            <td class="text-center qc-padding-none">
                                {!! $hFunction->currencyFormat($totalMoneyBeforePay) !!}
                            </td>
                            <td class="text-center qc-padding-none">
                                {!! $hFunction->currencyFormat($totalMoneyMinus) !!}
                            </td>

                            <td class="text-center qc-padding-none">
                                {!! $hFunction->currencyFormat($totalCurrentSalary  - $totalMoneyBeforePay - $totalMoneyMinus) !!}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            @if(count($dataTimekeeping)>0)
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr style="background-color: whitesmoke;">
                                <th class="text-center qc-padding-none">STT</th>
                                <th class="text-center qc-padding-none">Giờ vào</th>
                                <th class="text-center qc-padding-none">Giờ ra</th>
                                <th class="qc-padding-none">Ghi chú</th>
                                <th class="text-center qc-padding-none">Nghỉ có phép</th>
                                <th class="text-center qc-padding-none">Nghỉ không phép</th>
                                <th class="text-center qc-padding-none">Giờ chính (h)</th>
                                <th class="text-center qc-padding-none">Tăng ca (h)</th>
                            </tr>
                            <?php $n_o = 0; ?>
                            @foreach($dataTimekeeping as $timekeeping)
                                <?php
                                $timekeepingId = $timekeeping->timekeepingId();
                                ?>
                                <tr>
                                    <td class="text-center qc-padding-none">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td class="text-center qc-padding-none">
                                        @if(!$timekeeping->checkOff())
                                            <span>{!! date('d-m-Y',strtotime($timekeeping->timeBegin())) !!}</span>
                                            <b style="color: blue;">
                                                - {!! date('H:i',strtotime($timekeeping->timeBegin())) !!}</b>
                                        @else
                                            <span>---</span>
                                        @endif
                                    </td>
                                    <td class="text-center qc-padding-none">
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
                                    <td>
                                        <em class="qc-color-grey"> {!! $timekeeping->note() !!}</em>
                                    </td>
                                    <td class="text-center qc-padding-none">
                                        @if($timekeeping->checkOff() && $timekeeping->checkPermissionStatus())
                                            <b>{!! date('d-m-Y', strtotime($timekeeping->dateOff())) !!}</b>
                                        @else
                                            <span>---</span>
                                        @endif
                                    </td>
                                    <td class="text-center qc-padding-none">
                                        @if($timekeeping->checkOff() && !$timekeeping->checkPermissionStatus())
                                            <b>{!! date('d-m-Y', strtotime($timekeeping->dateOff())) !!}</b>
                                        @else
                                            <span>---</span>
                                        @endif
                                    </td>
                                    <td class="text-center qc-padding-none">
                                        @if(!$timekeeping->checkOff())
                                            <b>{!! ($timekeeping->mainMinute() - $timekeeping->mainMinute()%60 )/60 !!}</b>
                                            <span>h</span>
                                            <b>{!! $timekeeping->mainMinute()%60 !!}</b>
                                        @else
                                            <span>---</span>
                                        @endif
                                    </td>
                                    <td class="text-center qc-padding-none">
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
            @endif
        </div>


        <div class="row">
            <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <button type="button" class="qc_ad3d_container_close btn btn-sm btn-primary">
                    Đóng
                </button>
            </div>
        </div>
    </div>
@endsection
