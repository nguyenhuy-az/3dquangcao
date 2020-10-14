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

$totalMoneyReceive = $totalMoneyOrderPay;
$totalMoneyPay = $totalMoneyImportPayOfPayStaff + $totalMoneyPayActivityDetailOfStaff + $totalMoneySalaryPayOfStaff + $totalMoneySalaryBeforePayOfStaff;
$hrefFilter = route('qc.work.money.statistical.get');
?>
@extends('work.money.statistical.index')
@section('titlePage')
    Thống kê tiền cty
@endsection
@section('qc_work_money_statistical_body')
    <div class="row">
        <div class="qc_work_money_statistical_wrap qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            {{-- chi tiêt --}}
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="qc_work_money_statistical_list_content row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <tr>
                                    <td colspan="3" style="padding: 0;">
                                        <label class="qc-color-red" style="font-size: 1.5em;">
                                            THỐNG KÊ TIỀN CTY ĐANG GIỮ CỦA THỦ QUỸ
                                        </label>
                                    </td>
                                    <td colspan="2" style="padding: 0;">
                                        <select class="qc_month_filter col-xs-5 col-sm-5 col-md-5 col-lg-5"
                                                style="padding: 0; height: 34px;"
                                                data-href="{!! $hrefFilter !!}">
                                            <option value="0" @if($monthFilter == 0) slected="selected" @endif>
                                                Tất cả
                                            </option>
                                            @for($m = 1; $m <=12; $m++)
                                                <option value="{!! $m !!}"
                                                        @if($monthFilter == $m) selected="selected" @endif>
                                                    Tháng {!! $m !!}
                                                </option>
                                            @endfor
                                        </select>
                                        <select class="qc_year_filter col-xs-7 col-sm-7 col-md-7 col-lg-7"
                                                style="padding: 0; height: 34px;" data-href="{!! $hrefFilter !!}">
                                            @for($y = 2017; $y <=2050; $y++)
                                                <option @if($yearFilter  == $y) selected="selected" @endif>
                                                    {!! $y !!}
                                                </option>
                                            @endfor
                                        </select>
                                    </td>
                                    <td style="padding: 0;">
                                        <a class="qc-link-white-bold btn btn-primary form-control"
                                           href="{!! route('qc.work.money.statistical.transfers.get') !!}">
                                            <i class="glyphicon glyphicon-plus"></i>
                                            NỘP TIỀN
                                        </a>
                                    </td>
                                </tr>
                                <tr style="background-color: black; color:yellow;">
                                    <th class="text-center" style="width:20px;"></th>
                                    <th>DANH MỤC THU - CHI</th>
                                    <th class="text-right">NHẬN TIỀN ĐẦU TƯ</th>
                                    <th class="text-right">NỘP CÔNG TY</th>
                                    <th class="text-right">THU</th>
                                    <th class="text-right">CHI</th>
                                </tr>
                                <tr>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td>
                                        <a class="qc-link-red-bold"
                                           href="{!! route('qc.work.money.transfer.receive.get',"$monthFilter/$yearFilter") !!}">
                                            Thu tiền đơn hàng
                                        </a>
                                        <br/>
                                        <em class="qc-color-grey">
                                            Nhận tiền bàn giao từ NV kinh doanh thu các đơn hàng
                                        </em>
                                    </td>
                                    <td class="text-right"></td>
                                    <td></td>
                                    <td class="text-right">
                                        <b style="color: blue;">
                                            {!! $hFunction->currencyFormat($totalMoneyOrderPay)  !!}
                                        </b>
                                    </td>
                                    <td class="text-right"></td>
                                </tr>
                                <tr>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td>
                                        <a class="qc-link-green-bold"
                                           href="{!! route('qc.work.money.transfer.receive.get',"$monthFilter/$yearFilter") !!}">
                                            Nhận tiền đầu tư
                                        </a>
                                        <br/>
                                        <em class="qc-color-grey">
                                            Nhận tiền từ thủ quỹ quản lý chuyển xuống
                                        </em>
                                    </td>
                                    <td class="text-right">
                                        <b style="color: green;">
                                            {!! $hFunction->currencyFormat($totalMoneyTransferReceive)  !!}
                                        </b>
                                    </td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                </tr>
                                <tr>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td>
                                        <a class="qc-link-red-bold"
                                           href="{!! route('qc.work.money.transfer.transfer.get',"$monthFilter/$yearFilter") !!}">
                                            Nộp tiền về công ty
                                        </a>
                                        <br/>
                                        <em class="qc-color-grey">
                                            Chuyển tiền cho thủ quỹ cấp quản lý
                                        </em>
                                    </td>
                                    <td class="text-right"></td>
                                    <td class="text-right">
                                        <b style="color: red;">
                                            {!! $hFunction->currencyFormat($totalMoneyTransferForTreasurerManage)  !!}
                                        </b>
                                    </td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                </tr>
                                <tr>
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td>
                                        <a class="qc-link-green-bold"
                                           href="{!! route('qc.work.money.payment.get',"activityPay/$monthFilter/$yearFilter") !!}">
                                            Chi hoạt động
                                        </a>
                                        <br/>
                                        <em class="qc-color-grey">Các hoạt động của cty</em>
                                    </td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
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
                                        <a class="qc-link-green-bold"
                                           href="{!! route('qc.work.money.payment.get',"salaryBeforePay/$monthFilter/$yearFilter") !!}">
                                            Chi ứng lương
                                        </a>
                                        <br/>
                                        <em class="qc-color-grey">Chi ứng lương cho nhân viên</em>
                                    </td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
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
                                        <a class="qc-link-green-bold"
                                           href="{!! route('qc.work.money.payment.get',"salaryPay/$monthFilter/$yearFilter") !!}">
                                            Chi thanh toán lương
                                        </a>
                                        <br/>
                                        <em class="qc-color-grey">Thanh toán lương cho nhân viên</em>
                                    </td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
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
                                        <a class="qc-link-green-bold"
                                           href="{!! route('qc.work.money.payment.get',"importPay/$monthFilter/$yearFilter") !!}">
                                            Chi thanh toán mua vật tư
                                        </a>
                                        <br/>
                                        <em class="qc-color-grey">
                                            Thanh toán tiền vật tư cho NV - người mua đã xác nhận
                                        </em>
                                    </td>
                                    <td class="text-right">
                                    </td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
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
                                        <a class="qc-link-green-bold"
                                           href="{!! route('qc.work.money.payment.get',"orderCancel/$monthFilter/$yearFilter") !!}">
                                            Chi hoàn tiền đơn hàng
                                        </a>
                                        <br/>
                                        <em class="qc-color-grey">
                                            Hoàn tiền lại cho khách hàng
                                        </em>
                                    </td>
                                    <td class="text-right">
                                    </td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right">
                                        <b style="color: red;">
                                            {!! $hFunction->currencyFormat($totalPaidOrderCancelOfStaffAndDate)  !!}
                                        </b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td class="text-right" style="border-top: 2px solid green;">
                                        <b style="color: green;">
                                            {!! $hFunction->currencyFormat($totalMoneyTransferReceive)  !!}
                                        </b>
                                    </td>
                                    <td class="text-right" style="border-top: 2px solid red;">
                                        0
                                    </td>
                                    <td class="text-right" style="border-top: 2px solid blue;">
                                        <b style="color: blue;">
                                            {!! $hFunction->currencyFormat($totalMoneyReceive)  !!}
                                        </b>
                                    </td>
                                    <td class="text-right" style="border-top: 2px solid red;">
                                        <b style="color: red;">
                                            {!! $hFunction->currencyFormat($totalMoneyPay)  !!}
                                        </b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-right" style="background-color: black;">
                                        <b style="color: yellow; font-size: 12px;">
                                            TỔNG TIỀN ĐANG GIỮ
                                        </b>
                                    </td>
                                    <td colspan="4" class="text-center" style="border-top: 2px solid red;">
                                        <b style="color: red;font-size: 2em;">
                                            {!! $hFunction->currencyFormat($totalMoneyReceive + $totalMoneyTransferReceive - $totalMoneyTransferForTreasurerManage - $totalMoneyPay)  !!}
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
