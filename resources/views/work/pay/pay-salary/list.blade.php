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
@extends('work.pay.pay-salary.index')
@section('qc_work_pay_salary_pay_body')
    <div class="row qc_work_pay_salary_wrap">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                    <label style="color: blue; font-size: 1.5em;">*** BẢNG LƯƠNG </label>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <select class="qc_work_pay_salary_month_filter text-right col-xs-4 col-sm-4 col-md-4 col-lg-4"
                            style="height: 34px;" data-href="{!! route('qc.work.pay.pay_salary.get') !!}">
                        @for($m = 1; $m <=12; $m++)
                            <option @if($filterMonth == $m) selected="selected" @endif value="{!! $m !!}">
                                Tháng {!! $m !!}
                            </option>
                        @endfor
                    </select>
                    <select class="qc_work_pay_salary_year_filter text-right col-xs-8 col-sm-8 col-md-8 col-lg-8 "
                            style=" height: 34px;" data-href="{!! route('qc.work.pay.pay_salary.get') !!}">
                        @for($y = 2017; $y <=2050; $y++)
                            <option @if($filterYear == $y) selected="selected" @endif value="{!! $y !!}">
                                Năm {!! $y !!}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr style="background-color: black; color: yellow;">
                                <th style="width: 200px;">NHÂN VIÊN</th>
                                <th style="width: 120px;">
                                    CHƯA THANH TOÁN
                                </th>
                                <th style="width: 120px;">LÃNH THỰC</th>
                                <th style="width: 120px;">
                                    MUA VẬT TƯ <br/>
                                    <em>(Đã duyệt chưa TT)</em>
                                </th>
                                <th style="width: 120px;">CỘNG THÊM</th>
                                <th style="width: 120px;">ĐÃ THANH TOÁN</th>
                                <th >GIỮ LẠI</th>
                            </tr>
                            @if($hFunction->checkCount($dataSalary))
                                <?php
                                $sumSalary = 0;
                                $sumSalaryPaid = 0;
                                $sumSalaryUnpaid = 0;
                                $sumSalaryBenefit = 0;
                                $sumMoneyImportOfStaff = 0;
                                $sumKeepMoney = 0;
                                ?>
                                @foreach($dataSalary as $salary)
                                    <?php
                                    $salaryId = $salary->salaryId();
                                    $benefitMoney = $salary->benefitMoney();
                                    $sumSalaryBenefit = $sumSalaryBenefit + $benefitMoney;
                                    $salaryPay = $salary->salary();
                                    $sumSalary = $sumSalary + $salaryPay;
                                    # da thanh toan
                                    $totalPaid = $salary->totalPaid();
                                    $sumSalaryPaid = $sumSalaryPaid + $totalPaid;

                                    $dataWork = $salary->work;
                                    $fromDate = $dataWork->fromDate();
                                    // tong tien mua vat tu xac nhan chưa thanh toan
                                    $totalMoneyImportOfStaff = $modelStaff->totalMoneyImportOfStaff($dataWork->companyStaffWork->staff->staffId(), date('Y-m', strtotime($fromDate)), 2);
                                    $sumMoneyImportOfStaff = $sumMoneyImportOfStaff + $totalMoneyImportOfStaff;
                                    # giu tiem trong thang
                                    $totalKeepMoney = $salary->totalKeepMoney();
                                    $sumKeepMoney = $sumKeepMoney + $totalKeepMoney;

                                    # chua thanh toan
                                    $salaryUnpaid = $salaryPay + $totalMoneyImportOfStaff + $benefitMoney - $totalPaid - $totalKeepMoney;
                                    $sumSalaryUnpaid = $sumSalaryUnpaid + $salaryUnpaid;

                                    if (!$hFunction->checkEmpty($dataWork->companyStaffWorkId())) {
                                        $dataStaffDetail = $dataWork->companyStaffWork->staff;
                                    } else {
                                        $dataStaffDetail = $salary->work->staff;
                                    }
                                    $bankAccount = $dataStaffDetail->bankAccount();
                                    $bankName = $dataStaffDetail->bankName();
                                    $n_o = isset($n_o) ? $n_o + 1 : 1;
                                    ?>
                                    <tr class="@if($n_o%2 == 0) info @endif">
                                        <td>
                                            <div class="media">
                                                <a class="pull-left" href="#">
                                                    <img class="media-object"
                                                         style="background-color: white; width: 40px;height: 40px; border: 1px solid #d7d7d7;border-radius: 10px;"
                                                         src="{!! $dataStaffDetail->pathAvatar($dataStaffDetail->image()) !!}">
                                                </a>
                                                <div class="media-body">
                                                    <label class="media-heading">{!! $dataStaffDetail->lastName() !!}</label>
                                                    @if(!empty($bankAccount))
                                                        <br/>
                                                        <em style="color: grey;">Số TK:</em>
                                                        <span>{!! $bankAccount !!}</span>
                                                        <br/>
                                                        <em style="color: grey;">Ngân hàng:</em>
                                                        <span>{!! $bankName !!}</span>
                                                    @else
                                                        <br/>
                                                        <em style="color: grey;">TK ngân hàng:</em>
                                                        <span style="color: grey;">Không có</span>
                                                    @endif

                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <b style="color: red;">{!! $hFunction->currencyFormat($salaryUnpaid) !!}</b>
                                            <br/>
                                            @if(!$salary->checkPaid())
                                                <a class="qc-link-green-bold"
                                                   href="{!! route('qc.work.pay.pay_salary.pay.get',$salaryId) !!}">
                                                    THANH TOÁN
                                                </a>
                                            @else
                                                <em class="qc-color-grey">Đã Thanh toán</em>
                                            @endif
                                        </td>
                                        <td>
                                            <b style="color: blue;">{!! $hFunction->currencyFormat($salaryPay) !!}</b>
                                            <br/>
                                            <a class="qc-link-green"
                                               href="{!! route('qc.work.salary.salary.detail',$salaryId) !!}">
                                                XEM CHI TIẾT
                                            </a>
                                        </td>
                                        <td>
                                            {!! $hFunction->currencyFormat($totalMoneyImportOfStaff) !!}
                                        </td>
                                        <td>
                                            <b style="color: red;">
                                                {!! $hFunction->currencyFormat($benefitMoney) !!}
                                            </b>
                                        </td>
                                        <td>
                                            <b style="color: green;">
                                                {!! $hFunction->currencyFormat($totalPaid) !!}
                                            </b>
                                        </td>
                                        <td >
                                            <a style="color: red;">
                                                {!! $hFunction->currencyFormat($totalKeepMoney) !!}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="qc-color-red" style="background-color: whitesmoke;"></td>
                                    <td class="qc-color-red">
                                        {!! $hFunction->currencyFormat($sumSalaryUnpaid)  !!}
                                    </td>
                                    <td class="qc-color-red">
                                        {!! $hFunction->currencyFormat($sumSalary)  !!}
                                    </td>
                                    <td class="qc-color-red">
                                        {!! $hFunction->currencyFormat($sumMoneyImportOfStaff)  !!}
                                    </td>
                                    <td class="qc-color-red">
                                        {!! $hFunction->currencyFormat($sumSalaryBenefit)  !!}
                                    </td>
                                    <td class="qc-color-red">
                                        {!! $hFunction->currencyFormat($sumSalaryPaid)  !!}
                                    </td>
                                    <td>
                                        {!! $hFunction->currencyFormat($sumKeepMoney)  !!}
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td class="text-center" colspan="7">
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
                    <label style="color: blue; font-size: 1.5em;">*** CHI THANH TOÁN LƯƠNG TRONG THÁNG</label>
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
                                    $payId = $salaryPay->payId();
                                    $money = $salaryPay->money();
                                    $confirmStatus = $salaryPay->checkConfirmed();
                                    if ($confirmStatus) { #da xac nhan
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
                                            @if(!$confirmStatus)
                                                <br/>
                                                <a class="qc_salary_pay_del qc-link-red"
                                                   data-href="{!! route('qc.work.pay.pay_salary.delete.get', $payId) !!}">
                                                    <i class="glyphicon glyphicon-trash" style="font-size: 1.5em;"></i>
                                                </a>
                                            @endif
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
    </div>
@endsection
