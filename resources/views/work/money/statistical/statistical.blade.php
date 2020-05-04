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

$totalMoneyReceive = $totalMoneyOrderPay + $totalMoneyTransferReceive + $totalMoneyImportPayOfImportStaff;
$totalMoneyPay = $totalMoneyTransferTransfer + $totalMoneyImportOfStaff + $totalMoneyImportPayOfPayStaff + $totalMoneyPayActivityDetailOfStaff + $totalMoneySalaryPayOfStaff + $totalMoneySalaryBeforePayOfStaff;
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
                        <label class="qc-color-red">THỐNG KÊ THU - CHI</label>
                    </div>
                    <div class="text-right qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <select class="qc_work_money_statistical_login_day" style="height: 25px;"
                                data-href="{!! route('qc.work.money.statistical.get') !!}">
                            <option value="0" @if($loginDay == null) selected="selected" @endif>
                                Trong tháng
                            </option>
                            @for($i = 1; $i <=31; $i++)
                                <option @if($loginDay == $i) selected="selected" @endif>
                                    {!! $i !!}
                                </option>
                            @endfor
                        </select>
                        <span>/</span>
                        <select class="qc_work_money_statistical_login_month" style="height: 25px;"
                                data-href="{!! route('qc.work.money.statistical.get') !!}">
                            @for($i = 1; $i <=12; $i++)
                                <option @if($loginMonth == $i) selected="selected" @endif>
                                    {!! $i !!}
                                </option>
                            @endfor
                        </select>
                        <span>/</span>
                        <select class="qc_work_money_statistical_login_year" style="height: 25px;"
                                data-href="{!! route('qc.work.money.statistical.get') !!}">
                            @for($i = 2017; $i <=2050; $i++)
                                <option @if($loginYear == $i) selected="selected" @endif>
                                    {!! $i !!}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="qc_work_money_statistical_list_content row">
                    <div class="qc-container-table col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <tr style="background-color: whitesmoke;">
                                    <th class="text-center" style="width:20px;">STT</th>
                                    <th>Lĩnh vực</th>
                                    <th>Mô tả</th>
                                    <th class="text-right">Thu</th>
                                    <th class="text-right">Chi</th>
                                </tr>
                                <tr>
                                    <td class="text-center">
                                        1
                                    </td>
                                    <td>
                                        Nhận từ thu đơn hàng
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
                                        2
                                    </td>
                                    <td>
                                        Nhận tiền bàn giao
                                    </td>
                                    <td>
                                        <em class="qc-color-grey">Do NV khác bàn bàn giao</em>
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
                                        3
                                    </td>
                                    <td>
                                        Nhận thanh toán Mua vật tư
                                    </td>
                                    <td>
                                        <em class="qc-color-grey">Mua vật tư thi công</em>
                                    </td>
                                    <td class="text-right">
                                        <b style="color: blue;">
                                            {!! $hFunction->currencyFormat($totalMoneyImportPayOfImportStaff)  !!}
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
                                        3
                                    </td>
                                    <td>
                                        Chi Mua vật tư
                                    </td>
                                    <td >
                                        <em class="qc-color-grey">Mua vật tư thi công</em>
                                    </td>
                                    <td class="text-right">
                                        <b style="color: blue;">
                                            {!! $hFunction->currencyFormat(0)  !!}
                                        </b>
                                    </td>
                                    <td class="text-right">
                                        <b style="color: red;">
                                            {!! $hFunction->currencyFormat($totalMoneyImportOfStaff)  !!}
                                        </b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center">
                                        4
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
                                        6
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
                                       7
                                    </td>
                                    <td>
                                        Thanh toán lương
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
                                       8
                                    </td>
                                    <td>
                                        Thanh toán mua vật tư
                                    </td>
                                    <td>
                                        <em class="qc-color-grey">Thủ quỹ thanh toán tiền vật tư cho NV</em>
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
                                      9
                                    </td>
                                    <td>
                                        Bàn giao tiền
                                    </td>
                                    <td>
                                        <em class="qc-color-grey">Do NV khác bàn bàn giao</em>
                                    </td>
                                    <td class="text-right">
                                        <b style="color: blue;">
                                            {!! $hFunction->currencyFormat(0)  !!}
                                        </b>
                                    </td>
                                    <td class="text-right">
                                        <b style="color: red;">
                                            {!! $hFunction->currencyFormat($totalMoneyTransferTransfer)  !!}
                                        </b>
                                    </td>
                                </tr>

                            </table>
                        </div>
                    </div>
                    <div class="qc-container-table col-xs-12 col-sm-12 col-md-12 col-lg-12">
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
