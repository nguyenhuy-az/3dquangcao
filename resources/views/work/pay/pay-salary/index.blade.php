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
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="text-right qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <select class="qc_work_pay_salary_login_month" style="height: 25px;"
                                data-href="{!! route('qc.work.pay.pay_salary.get') !!}">
                            @for($i = 1; $i <=12; $i++)
                                <option @if($loginMonth == $i) selected="selected" @endif>
                                    {!! $i !!}
                                </option>
                            @endfor
                        </select>
                        <span>/</span>
                        <select class="qc_work_pay_salary_login_year" style="height: 25px;"
                                data-href="{!! route('qc.work.pay.pay_salary.get') !!}">
                            @for($i = 2017; $i <=2050; $i++)
                                <option @if($loginYear == $i) selected="selected" @endif>
                                    {!! $i !!}
                                </option>
                            @endfor
                        </select>
                        <select class="qc_work_pay_salary_login_pay_status" style="height: 25px;"
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
                    </div>
                </div>
                <div class="table-responsive qc-container-table">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: whitesmoke;">
                            <th class="text-center" style="width: 20px;">STT</th>
                            <th>Tên</th>
                            <th class="text-right">Lương lãnh thực</th>
                            <th class="text-right">
                                Mua vật tư <br/>
                                <em>(Đã duyệt chưa TT)</em>
                            </th>
                            <th class="text-right">Cộng thêm</th>
                            <th class="text-right">Đã thanh toán</th>
                            <th class="text-right">còn lại</th>
                            <th class="text-center"></th>
                            <th class="text-center">Thanh toán</th>
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
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-right qc-color-red" style="background-color: whitesmoke;"
                                    colspan="3"></td>
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
                                <td class="text-right qc-color-red" style="background-color: whitesmoke;"
                                    colspan="2"></td>
                            </tr>
                        @else
                            <tr>
                                <td class="text-center" colspan="8">
                                    <em class="qc-color-red">Không có thông lương</em>
                                </td>
                            </tr>
                        @endif
                    </table>
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
