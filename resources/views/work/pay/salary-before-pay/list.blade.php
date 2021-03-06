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
    <div class="row">
        <div class="qc_work_pay_salary_before_pay_wrap col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="qc_work_pay_salary_before_pay_list row">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr>
                                <td style="padding: 0;">
                                    <a class="qc-link-white-bold btn btn-primary form-control"
                                       href="{!! route('qc.work.pay.salary_before_pay.add.get') !!}">
                                        + ỨNG LƯƠNG
                                    </a>
                                </td>
                                <td colspan="2"></td>
                            </tr>
                            <tr style="background-color: black;color: yellow;">
                                <th style="width: 170px;">NGÀY</th>
                                <th style="width: 200px;">TIỀN ỨNG</th>
                                <th>NGƯỜI ỨNG</th>
                            </tr>
                            <tr>
                                <td style="padding: 0;">
                                    <select class="cbDayFilter col-sx-3 col-sm-3 col-md-3 col-lg-3"
                                            style="height: 34px;padding: 0;"
                                            data-href="{!! $hrefIndex !!}">
                                        <option value="100" @if((int)$dayFilter == 100) selected="selected" @endif >Tất
                                            cả
                                        </option>
                                        @for($d =1;$d<= 31; $d++)
                                            <option value="{!! $d !!}"
                                                    @if((int)$dayFilter == $d) selected="selected" @endif >
                                                {!! $d !!}
                                            </option>
                                        @endfor
                                    </select>
                                    <select class="cbMonthFilter col-sx-3 col-sm-3 col-md-3 col-lg-3"
                                            style="height: 34px;padding: 0;"
                                            data-href="{!! $hrefIndex !!}">
                                        <option value="100" @if((int)$monthFilter == 100) selected="selected" @endif >
                                            Tất cả
                                        @for($m = 1; $m <=12; $m++)
                                            <option @if($monthFilter == $m) selected="selected" @endif>
                                                {!! $m !!}
                                            </option>
                                        @endfor
                                    </select>
                                    <select class="cbYearFilter col-sx-6 col-sm-6 col-md-6 col-lg-6"
                                            style="height: 34px;padding: 0;" data-href="{!! $hrefIndex !!}">
                                        @for($y = 2017; $y <=2050; $y++)
                                            <option @if($yearFilter == $y) selected="selected" @endif>
                                                {!! $y !!}
                                            </option>
                                        @endfor
                                    </select>
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                            @if($hFunction->checkCount($dataSalaryBeforePay))
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
                                    # nhan vien ung
                                    $dataReceiveStaff = $dataWork->companyStaffWork->staff;
                                    ?>
                                    <tr data-object="{!! $payId !!}">
                                        <td>
                                            <b style="color: blue;">{!! date('d/m/Y', strtotime($salaryBeforePay->datePay())) !!}</b>
                                            <br/>
                                            @if(!$salaryBeforePay->checkConfirm())
                                                <i class="glyphicon glyphicon-ok" style="color: red;"></i>
                                                <em style="color: grey;">Chưa xác nhận</em>
                                                <span>|</span>
                                                <a class="qc_edit qc-link-green"
                                                   data-href="{!! route('qc.work.pay.salary_before_pay.edit.get',$payId) !!}">
                                                    <i class="glyphicon glyphicon-pencil" title="Sửa thông tin ứng"></i>
                                                </a>
                                                &nbsp;<span>|</span>&nbsp;
                                                <a class="qc_delete qc-link-red"
                                                   data-href="{!! route('qc.work.pay.salary_before_pay.delete',$payId) !!}">
                                                    <i class="glyphicon glyphicon-trash" title="Hủy ứng lương"></i>
                                                </a>
                                            @else
                                                <i class="glyphicon glyphicon-ok" style="color: green;"></i>
                                                <em class="qc-color-grey">Đã xác nhận</em>
                                            @endif
                                        </td>
                                        <td>
                                            <b style="color: red;">{!! $hFunction->currencyFormat($money) !!}</b>
                                            <p style="color: grey;">
                                                {!! $salaryBeforePay->description() !!}
                                            </p>
                                        </td>
                                        <td>
                                            <div class="media">
                                                <a class="pull-left" href="#">
                                                    <img class="media-object"
                                                         style="max-width: 40px;height: 40px; border: 1px solid #d7d7d7;border-radius: 10px;"
                                                         src="{!! $dataReceiveStaff->pathAvatar($dataReceiveStaff->image()) !!}">
                                                </a>

                                                <div class="media-body">
                                                    <h5 class="media-heading">{!! $dataReceiveStaff->fullName() !!}</h5>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td style="background-color: whitesmoke;"></td>
                                    <td>
                                        <b class="qc-color-red" style="font-size: 1.5em;">{!! $hFunction->currencyFormat($totalMoney) !!}</b>
                                    </td>
                                    <td></td>
                                </tr>
                            @else
                                <tr>
                                    <td class="text-left qc-padding-top-20 qc-padding-bot-20" colspan="3">
                                        <em class="qc-color-red">Không tìm thấy thông tin</em>
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
