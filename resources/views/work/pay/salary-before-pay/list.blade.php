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
$currentMonth = $hFunction->currentMonth();

$hrefIndex = route('qc.work.pay.salary_before_pay.get');
?>
@extends('work.pay.salary-before-pay.index')
@section('qc_work_pay_salary_before_pay_body')
    <div class="qc_work_pay_salary_before_pay_wrap qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <h3>DANH SÁCH ỨNG LƯƠNG</h3>
            </div>
            <div class="text-right qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <select class="cbDayFilter" style="margin-top: 5px; height: 25px;"
                        data-href="{!! $hrefIndex !!}">
                    <option value="100" @if((int)$dayFilter == 100) selected="selected" @endif >Tất cả
                    </option>
                    @for($i =1;$i<= 31; $i++)
                        <option value="{!! $i !!}"
                                @if((int)$dayFilter == $i) selected="selected" @endif >{!! $i !!}</option>
                    @endfor
                </select>
                <span>|</span>
                <select class="qc_work_pay_salary_login_month" style="height: 25px;"
                        data-href="{!! $hrefIndex !!}">
                    <option value="100" @if((int)$dayFilter == 100) selected="selected" @endif >Tất cả
                    @for($i = 1; $i <=12; $i++)
                        <option @if($monthFilter == $i) selected="selected" @endif>
                            {!! $i !!}
                        </option>
                    @endfor
                </select>
                <span>/</span>
                <select class="qc_work_pay_salary_login_year" style="height: 25px;"
                        data-href="{!! $hrefIndex !!}">
                    @for($i = 2017; $i <=2050; $i++)
                        <option @if($yearFilter == $i) selected="selected" @endif>
                            {!! $i !!}
                        </option>
                    @endfor
                </select>
                <a class="qc-link-red-bold btn btn-sm" href="{!! route('qc.work.pay.salary_before_pay.add.get') !!}">
                    + Ứng lương
                </a>
            </div>
        </div>
        <div class="qc_work_pay_salary_before_pay_list row">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="table-responsive qc-container-table">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: whitesmoke;">
                            <th class="text-center">STT</th>
                            <th>Ngày</th>
                            <th>Tên</th>
                            <th>Ghi chú</th>
                            <th class="text-right"></th>
                            <th class="text-right">Tiền ứng</th>

                        </tr>
                        @if(count($dataSalaryBeforePay) > 0)
                            <?php
                            $n_o = 0; // set row number
                            $totalMoney = 0;
                            ?>
                            @foreach($dataSalaryBeforePay as $salaryBeforePay)
                                <?php
                                $payId = $salaryBeforePay->payId();
                                $money = $salaryBeforePay->money();
                                $dataWork = $salaryBeforePay->work;
                                $totalMoney = $totalMoney + $money;
                                ?>
                                <tr data-object="{!! $payId !!}">
                                    <td class="text-center">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td>
                                        {!! date('d/m/Y', strtotime($salaryBeforePay->datePay())) !!}
                                    </td>
                                    <td>
                                        @if(!empty($dataWork->companyStaffWorkId()))
                                            {!! $dataWork->companyStaffWork->staff->fullName() !!}
                                        @else
                                            {!! $dataWork->staff->fullName() !!}
                                        @endif
                                    </td>
                                    <td>
                                        {!! $salaryBeforePay->description() !!}
                                    </td>
                                    <td class="text-right">
                                        @if(!$salaryBeforePay->checkConfirm())
                                            <em style="color: brown;">Chưa xác nhận</em>
                                            <span>|</span>
                                            <a class="qc_edit qc-link-green" data-href="{!! route('qc.work.pay.salary_before_pay.edit.get',$payId) !!}">
                                                <i class="glyphicon glyphicon-pencil" title="Sửa thông tin ứng"></i>
                                            </a>
                                            &nbsp;<span>|</span>&nbsp;
                                            <a class="qc_delete qc-link-red"
                                               data-href="{!! route('qc.work.pay.salary_before_pay.delete',$payId) !!}">
                                                <i class="glyphicon glyphicon-trash" title="Hủy ứng lương"></i>
                                            </a>
                                        @else
                                            <em class="qc-color-grey">Đã xác nhận</em>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        {!! $hFunction->currencyFormat($money) !!}
                                    </td>

                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="5" style="background-color: whitesmoke;"></td>
                                <td class="text-right">
                                    <em class="qc-color-red">{!! $hFunction->currencyFormat($totalMoney) !!}</em>
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="text-left qc-padding-top-20 qc-padding-bot-20" colspan="6">
                                    <em class="qc-color-red">Không tìm thấy thông tin</em>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
