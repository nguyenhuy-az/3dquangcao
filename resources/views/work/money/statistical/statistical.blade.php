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
$dataStaffLogin = $modelStaff->loginStaffInfo();
$loginStaffId = $dataStaffLogin->staffId();

$totalMoneyReceive = $totalMoneyOrderPay + $totalMoneyTransferReceive;
$totalMoneyPay = $totalMoneyImportPayOfPayStaff + $totalMoneyPayActivityDetailOfStaff + $totalMoneySalaryPayOfStaff + $totalMoneySalaryBeforePayOfStaff;
?>
@extends('work.index')
@section('titlePage')
    Thống kê
@endsection
@section('qc_work_body')
    <div class="row">
        <div class="qc_work_money_statistical_wrap qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            @include('work.money.money-menu')

            {{-- chi tiêt --}}
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <label class="qc-color-red" style="font-size: 1.5em;">THỐNG KÊ THU - CHI TIỀN CTY</label>
                        <em style="color: red;"> Của {!! $dataStaffLogin->fullName() !!}</em>
                    </div>
                    <div class="text-right qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <select class="qc_work_money_statistical_login_day" style="height: 25px;"
                                data-href="{!! route('qc.work.money.statistical.get') !!}">
                            <option value="0" @if($loginDay == null) selected="selected" @endif>
                                Trong tháng
                            </option>
                            @for($d = 1; $d <=31; $d++)
                                <option @if($loginDay == $d) selected="selected" @endif>
                                    {!! $d !!}
                                </option>
                            @endfor
                        </select>
                        <span>/</span>
                        <select class="qc_work_money_statistical_login_month" style="height: 25px;"
                                data-href="{!! route('qc.work.money.statistical.get') !!}">
                            @for($m = 1; $m <=12; $m++)
                                <option @if($loginMonth == $m) selected="selected" @endif>
                                    {!! $m !!}
                                </option>
                            @endfor
                        </select>
                        <span>/</span>
                        <select class="qc_work_money_statistical_login_year" style="height: 25px;"
                                data-href="{!! route('qc.work.money.statistical.get') !!}">
                            @for($y = 2017; $y <=2050; $y++)
                                <option @if($loginYear == $y) selected="selected" @endif>
                                    {!! $y !!}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="qc_work_money_statistical_list_content row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <tr style="background-color: whitesmoke;">
                                    <th class="text-center" style="width:20px;"></th>
                                    <th>Lĩnh vực</th>
                                    <th>Mô tả</th>
                                    <th class="text-right">Thu</th>
                                    <th class="text-right">Chi</th>
                                </tr>
                                <tr>
                                    <td class="text-center">
                                      -
                                    </td>
                                    <td>
                                        Thu tiền đơn hàng
                                    </td>
                                    <td>
                                        <em class="qc-color-grey">Thu tiền từ các đơn hàng</em>
                                    </td>
                                    <td class="text-right">
                                        <b style="color: blue;">
                                            {!! $hFunction->currencyFormat($totalMoneyOrderPay)  !!}
                                        </b>
                                    </td>
                                    <td class="text-right">
                                        <b style="color: red;">
                                            {!! $hFunction->currencyFormat(0)  !!}
                                        </b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center">
                                       -
                                    </td>
                                    <td>
                                        Nhận tiền bàn giao
                                    </td>
                                    <td>
                                        <em class="qc-color-grey">Nhận từ cty mẹ bàn giao</em>
                                    </td>
                                    <td class="text-right">
                                        <b style="color: blue;">
                                            {!! $hFunction->currencyFormat($totalMoneyTransferReceive)  !!}
                                        </b>
                                    </td>
                                    <td class="text-right">
                                        <b style="color: red;">0</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td>
                                        Chi hoạt động
                                    </td>
                                    <td>
                                        <em class="qc-color-grey">Các hoạt động của cty</em>
                                    </td>
                                    <td class="text-right">
                                        <b style="color: blue;">
                                            {!! $hFunction->currencyFormat(0)  !!}
                                        </b>
                                    </td>
                                    <td class="text-right">
                                        <b style="color: red;">
                                            {!! $hFunction->currencyFormat($totalMoneyPayActivityDetailOfStaff)  !!}
                                        </b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td>
                                        Chi ứng lương
                                    </td>
                                    <td>
                                        <em class="qc-color-grey">Chi ứng lương cho nhân viên</em>
                                    </td>
                                    <td class="text-right">
                                        <b style="color: blue;">
                                            {!! $hFunction->currencyFormat(0)  !!}
                                        </b>
                                    </td>
                                    <td class="text-right">
                                        <b style="color: red;">
                                            {!! $hFunction->currencyFormat($totalMoneySalaryBeforePayOfStaff)  !!}
                                        </b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center">
                                       -
                                    </td>
                                    <td>
                                        Chi thanh toán lương
                                    </td>
                                    <td>
                                        <em class="qc-color-grey">Thanh toán lương cho nhân viên</em>
                                    </td>
                                    <td class="text-right">
                                        <b style="color: blue;">
                                            {!! $hFunction->currencyFormat(0)  !!}
                                        </b>
                                    </td>
                                    <td class="text-right">
                                        <b style="color: red;">
                                            {!! $hFunction->currencyFormat($totalMoneySalaryPayOfStaff)  !!}
                                        </b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center">
                                       -
                                    </td>
                                    <td>
                                        Chi thanh toán mua vật tư
                                    </td>
                                    <td>
                                        <em class="qc-color-grey">Thanh toán tiền vật tư cho NV - người mua đã xác nhận</em>
                                    </td>
                                    <td class="text-right">
                                        <b style="color: blue;">
                                            {!! $hFunction->currencyFormat(0)  !!}
                                        </b>
                                    </td>
                                    <td class="text-right">
                                        <b style="color: red;">
                                            {!! $hFunction->currencyFormat($totalMoneyImportPayOfPayStaff)  !!}
                                        </b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td>
                                        Chi hoàn tiền đơn hàng
                                    </td>
                                    <td>
                                        <em class="qc-color-grey">Hoàn tiền lại cho khách hàng</em>
                                    </td>
                                    <td class="text-right">
                                        <b style="color: blue;">
                                            {!! $hFunction->currencyFormat(0)  !!}
                                        </b>
                                    </td>
                                    <td class="text-right">
                                        <b style="color: red;">
                                            {!! $hFunction->currencyFormat($totalPaidOrderCancelOfStaffAndDate)  !!}
                                        </b>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <tr style="background-color: whitesmoke;">
                                    <th class="text-center">Tổng thu</th>
                                    <th class="text-center">Tổng Chi</th>
                                    <th class="text-center">Còn lại</th>
                                </tr>
                                <tr>
                                    <td class="text-center" style="color: red;">
                                        <b style="color: blue;">
                                            {!! $hFunction->currencyFormat($totalMoneyReceive)  !!}
                                        </b>
                                    </td>
                                    <td class="text-center" style="color: red;">
                                        <b style="color: red;">
                                            {!! $hFunction->currencyFormat($totalMoneyPay)  !!}
                                        </b>
                                    </td>
                                    <td class="text-center" style="color: red;">
                                        <b style="color: green;">
                                            {!! $hFunction->currencyFormat($totalMoneyReceive-$totalMoneyPay)  !!}
                                        </b>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
