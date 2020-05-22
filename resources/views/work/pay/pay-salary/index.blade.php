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

$currentMonth = $hFunction->currentMonth();
?>
@extends('work.index')

@section('qc_work_body')
    <div class="row qc_work_pay_salary_wrap">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            {{-- Menu --}}
            @include('work.pay.pay-menu')
            {{-- chi tiêt --}}
            <div class="row">
                <div class="text-right qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <select class="qc_work_pay_salary_login_month" style="height: 25px;"
                            data-href="{!! route('qc.work.pay.pay_salary.get') !!}">
                        @for($i = 1; $i <=12; $i++)
                            <option @if($filterMonth == $i) selected="selected" @endif>
                                {!! $i !!}
                            </option>
                        @endfor
                    </select>
                    <span>/</span>
                    <select class="qc_work_pay_salary_login_year" style="height: 25px;"
                            data-href="{!! route('qc.work.pay.pay_salary.get') !!}">
                        @for($i = 2017; $i <=2050; $i++)
                            <option @if($filterYear == $i) selected="selected" @endif>
                                {!! $i !!}
                            </option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <label style="color: deeppink;">*** Bảng lương </label>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="table-responsive qc-container-table">
                        <table class="table table-hover table-bordered">
                            <tr style="background-color: black; color: yellow;">
                                <th class="text-center" style="width: 20px;">STT</th>
                                <th>Tên</th>
                                <th class="text-center"></th>
                                <th class="text-center">Thanh toán</th>
                                <th class="text-right">Lương lãnh thực</th>
                                <th class="text-right">
                                    Mua vật tư <br/>
                                    <em>(Đã duyệt chưa TT)</em>
                                </th>
                                <th class="text-right">Cộng thêm</th>
                                <th class="text-right">Đã thanh toán</th>
                                <th class="text-right">còn lại</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="padding: 0;">
                                    <select class="qc_work_pay_salary_login_pay_status form-control"
                                            data-href="{!! route('qc.work.pay.pay_salary.get') !!}">
                                        <option value="3" @if($payStatus == 3) selected="selected" @endif>
                                            Tất cả
                                        </option>
                                        <option value="0" @if($payStatus == 0) selected="selected" @endif>
                                            Chưa thanh toán
                                        </option>
                                        <option value="1" @if($payStatus == 1) selected="selected" @endif>
                                            Đã thanh toán
                                        </option>
                                    </select>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>

                            </tr>
                            @if($hFunction->checkCount($dataSalary))
                                <?php
                                $sumSalary = 0;
                                $sumSalaryPaid = 0;
                                $sumSalaryUnpaid = 0;
                                $sumSalaryBenefit = 0;
                                ?>
                                @foreach($dataSalary as $salary)
                                    <?php
                                    $salaryId = $salary->salaryId();
                                    $benefitMoney = $salary->benefitMoney();
                                    $sumSalaryBenefit = $sumSalaryBenefit + $benefitMoney;
                                    $salaryPay = $salary->salary();

                                    $sumSalary = $sumSalary + $salaryPay;
                                    $totalPaid = $salary->totalPaid();
                                    $sumSalaryPaid = $sumSalaryPaid + $totalPaid;
                                    $sumSalaryUnpaid = $sumSalaryUnpaid + $salaryPay - $totalPaid;
                                    $dataWork = $salary->work;
                                    $fromDate = $dataWork->fromDate();
                                    // tong tien mua vat tu xac nhan chưa thanh toan
                                    $totalMoneyImportOfStaff = $modelStaff->totalMoneyImportOfStaff($dataWork->companyStaffWork->staff->staffId(), date('Y-m', strtotime($fromDate)), 2);
                                    ?>
                                    <tr>
                                        <td class="text-center">
                                            {!! $n_o = isset($n_o)?$n_o+ 1:1 !!}
                                        </td>
                                        <td>
                                            @if(!$hFunction->checkEmpty($dataWork->companyStaffWorkId()))
                                                {!! $dataWork->companyStaffWork->staff->fullName() !!}
                                            @else
                                                {!! $salary->work->staff->fullName() !!}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a class="qc-link-green"
                                               href="{!! route('qc.work.salary.salary.detail',$salaryId) !!}">
                                                Chi tiết lương
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            @if(!$salary->checkPaid())
                                                <a class="qc-link-green"
                                                   href="{!! route('qc.work.pay.pay_salary.pay.get',$salaryId) !!}">
                                                    Thanh toán
                                                </a>
                                            @else
                                                <em class="qc-color-grey">Đã Thanh toán</em>
                                            @endif
                                        </td>
                                        <td class="text-right qc-color-red">
                                            {!! $hFunction->currencyFormat($salaryPay) !!}
                                        </td>
                                        <td class="text-right">
                                            {!! $hFunction->currencyFormat($totalMoneyImportOfStaff) !!}
                                        </td>
                                        <td class="text-right">
                                            {!! $hFunction->currencyFormat($benefitMoney) !!}
                                        </td>
                                        <td class="text-right">
                                            {!! $hFunction->currencyFormat($totalPaid) !!}
                                        </td>
                                        <td class="text-right qc-color-red">
                                            {!! $hFunction->currencyFormat($salaryPay + $totalMoneyImportOfStaff + $benefitMoney -$totalPaid) !!}
                                        </td>

                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="text-right qc-color-red" style="background-color: whitesmoke;"
                                        colspan="5"></td>
                                    <td class="text-right qc-color-red">
                                        {!! $hFunction->currencyFormat($sumSalary)  !!}
                                    </td>
                                    <td class="text-right qc-color-red">
                                        {!! $hFunction->currencyFormat($sumSalaryBenefit)  !!}
                                    </td>
                                    <td class="text-right qc-color-red">
                                        {!! $hFunction->currencyFormat($sumSalaryPaid)  !!}
                                    </td>
                                    <td class="text-right qc-color-red">
                                        {!! $hFunction->currencyFormat($sumSalaryUnpaid)  !!}
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td class="text-center" colspan="9">
                                        <em class="qc-color-red">Không có bảng lương</em>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <label style="color: deeppink;">*** Chi thanh toán trong tháng </label>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr style="background-color: black;color: yellow;">
                                <th style="width: 20px;"></th>
                                <th>Ngày thanh toán</th>
                                <th>Người nhận</th>
                                <th class="text-center">Bảng lương</th>
                                <th class="text-right">Tiền chưa xác nhận</th>
                                <th class="text-right">Tiền đã xác nhận</th>
                            </tr>
                            @if($hFunction->checkCount($dataSalaryPay))
                                <?php
                                $n_o = 0;
                                $totalMoneyConfirmed = 0;
                                $totalMoneyNoConfirm = 0;
                                ?>
                                @foreach($dataSalaryPay as $salaryPay)
                                    <?php
                                    $money = $salaryPay->money();
                                    if ($salaryPay->checkConfirmed()) { #da xac nhan
                                        $totalMoneyConfirmed = $totalMoneyConfirmed + $money;
                                    } else { # chua xac nhan
                                        $totalMoneyNoConfirm = $totalMoneyNoConfirm + $money;
                                    }
                                    $dataWork = $salaryPay->salary->work;
                                    ?>
                                    <tr class="@if($n_o%2) info @endif">
                                        <td class="text-center">
                                            {!! $n_o = $n_o + 1 !!}
                                        </td>
                                        <td>
                                            {!! date('d/m/Y',strtotime($salaryPay->datePay()))  !!}
                                        </td>
                                        <td>
                                            <span>{!! $dataWork->companyStaffWork->staff->fullName() !!}</span>
                                        </td>
                                        <td class="text-center">
                                                <span>
                                                    Tháng
                                                    {!! date('m-Y', strtotime($dataWork->fromDate())) !!}
                                                </span>
                                        </td>
                                        <td class="text-right">
                                            @if (!$salaryPay->checkConfirmed())
                                                {!! $hFunction->currencyFormat($money) !!}
                                            @else
                                                <span>0</span>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            @if ($salaryPay->checkConfirmed())
                                                {!! $hFunction->currencyFormat($money) !!}
                                            @else
                                                <span>0</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                <tr style="border-top: 2px solid brown;">
                                    <td class="text-right qc-color-red"
                                        style="background-color: whitesmoke;" colspan="4">
                                    </td>
                                    <td class="text-right qc-color-red">
                                        <b>{!! $hFunction->currencyFormat($totalMoneyNoConfirm) !!}</b>
                                    </td>
                                    <td class="text-right qc-color-red">
                                        <b>{!! $hFunction->currencyFormat($totalMoneyConfirmed) !!}</b>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td class="qc-padding-top-5 qc-padding-bot-5 text-center" colspan="6">
                                        <em class="qc-color-red">Không có thông tin thanh toán</em>
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
                <a class="btn btn-sm btn-primary" onclick="qc_main.page_back();">
                    Về trang trước
                </a>
                <a class="btn btn-sm btn-default" href="{!! route('qc.work.home') !!}">
                    Về trang chủ
                </a>
            </div>
        </div>
    </div>
@endsection
