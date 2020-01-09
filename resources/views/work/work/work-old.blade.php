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
$dataWork = $dataStaff->firstInfoToWorkOld($loginStaffId, date('Y-m', strtotime("$loginYear-$loginMonth")));

//$dataWork = null;
if (count($dataWork) > 0) {

    $dataTimekeeping = $dataWork->infoTimekeeping();

    $salaryBasic = $dataStaff->salaryBasicOfStaff($loginStaffId, date('Y-m', strtotime("$loginYear-$loginMonth")));
    $priceHours = floor(($salaryBasic / 26) / 8);
    $workId = $dataWork->workId();

    $totalMoneyMinus = $dataWork->totalMoneyMinus();
    $totalMoneyBeforePay = $dataWork->totalMoneyBeforePay();
    $sumMainMinute = $dataWork->sumMainMinute();
    $sumPlusMinute = $dataWork->sumPlusMinute() * 1.5;
    $sumMinusMinute = $dataWork->sumMinusMinute();
    $totalMinute = $sumMainMinute + $sumPlusMinute;
}

?>
@extends('work.index')
@section('qc_work_body')
    <div class="row">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed #C2C2C2;">
                <h3>CHI TIẾT LÀM VIỆC</h3>
                <span class="qc-color-red">Tiền phụ cấp dành cho nhân viên làm đủ số ngày trong tháng, không vi phạm nội qui đi trễ, không nghỉ không phép, làm không bị sai, bị đền đơn hàng.</span>
            </div>

            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <em>Tên:</em>
                        <span class="qc-font-bold">{!! $dataStaff->fullName() !!}</span>
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
                    <div class="text-right qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-3 col-lg-3">
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
                                    0
                                </td>
                                <td class="text-center qc-padding-none">
                                    0
                                </td>
                                <td class="text-center qc-padding-none">
                                    100.000
                                </td>
                                <td class="text-center qc-padding-none">
                                    500.000
                                </td>
                                <td class="text-center qc-padding-none">
                                    10.000
                                </td>
                                <td class="text-center qc-padding-none">
                                    {!! $hFunction->currencyFormat($priceHours) !!}
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
                                    {!! floor(($sumPlusMinute-$sumPlusMinute%60)/60) !!}
                                    <b>h</b> {!! $sumPlusMinute%60 !!}
                                </td>
                                <td class="text-center qc-padding-none">
                                    {!! floor(($sumMainMinute + $sumPlusMinute)/60) !!}
                                    <span>h</span> {!! ($sumMainMinute + $sumPlusMinute)%60 !!}
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
                    <?php
                    $totalMinutePay = $sumMainMinute + $sumPlusMinute - $sumMinusMinute;
                    $totalCurrentSalary = $dataWork->totalCurrentSalary();
                    ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr class="qc-color-red">
                                <th class="qc-padding-none" colspan="7">Chi tiết lương</th>
                            </tr>
                            <tr style="background-color: whitesmoke;">
                                <th class="text-center qc-padding-none">Lương lãnh</th>
                                <th class="text-center qc-padding-none">Ứng</th>
                                <th class="text-center qc-padding-none">Phạt</th>
                                <th class="text-center qc-padding-none">P/C tăng ca</th>
                                <th class="text-center qc-padding-none">lương còn lại</th>
                            </tr>
                            <tr>
                                <td class="text-center qc-padding-none">
                                    {!! $hFunction->currencyFormat($totalCurrentSalary) !!}
                                </td>
                                <td class="text-center qc-padding-none">
                                    <a class="qc-font-bold" href="{!! route('qc.work.salary.before_pay.get') !!}">
                                        {!! $hFunction->currencyFormat($totalMoneyBeforePay) !!}
                                    </a>
                                </td>
                                <td class="text-center qc-padding-none">
                                    <a class="qc-font-bold" href="{!! route('qc.work.minus_money.get') !!}">
                                        {!! $hFunction->currencyFormat($totalMoneyMinus) !!}
                                    </a>

                                </td>
                                <td class="text-center qc-padding-none">
                                    0
                                </td>
                                <td class="text-center qc-color-red qc-padding-none">
                                    {!! $hFunction->currencyFormat(floor($totalCurrentSalary-$totalMoneyBeforePay-$totalMoneyMinus)) !!}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                @if(count($dataTimekeeping)>0)
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <tr class="qc-color-red">
                                    <th class="qc-padding-none" colspan="7">Chi làm việc</th>
                                </tr>
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
                                                <b>{!! date('H:i',strtotime($timekeeping->timeBegin())) !!}</b>
                                            @else
                                                <span>---</span>
                                            @endif
                                        </td>
                                        <td class="text-center qc-padding-none">
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
                                        <td class="text-center qc-padding-none">
                                            @if($timekeeping->checkOff() && $timekeeping->checkPermissionStatus())
                                                <b>{!! date('d-m-Y', strtotime($timekeeping->dateOff())) !!}</b>
                                            @endif
                                        </td>
                                        <td class="qc-padding-none">
                                            @if($timekeeping->checkOff() && !$timekeeping->checkPermissionStatus())
                                                <b>{!! date('d-m-Y', strtotime($timekeeping->dateOff())) !!}</b>
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
