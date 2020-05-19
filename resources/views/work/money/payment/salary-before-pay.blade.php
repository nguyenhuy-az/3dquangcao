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
?>
@extends('work.money.payment.index')
@section('titlePage')
    Thống kê ứng lương
@endsection
@section('qc_work_money_payment_content')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <tr style="background-color: black; color: yellow;">
                        <th style="width: 20px;"></th>
                        <th>Ngày cho ứng</th>
                        <th>Người nhận</th>
                        <th class="text-right">Tiền chưa xác nhận</th>
                        <th class="text-right">Tiền đã xác nhận</th>
                    </tr>
                    @if($hFunction->checkCount($dataSalaryBeforePay))
                        <?php
                        $n_o = 0;
                        $totalMoneyConfirmed = 0;
                        $totalMoneyNoConfirm = 0;
                        ?>
                        @foreach($dataSalaryBeforePay as $salaryBeforePay)
                            <?php
                            $payId = $salaryBeforePay->payId();
                            $money = $salaryBeforePay->money();
                            $checkConfirmStatus = $salaryBeforePay->checkConfirm();
                            if ($checkConfirmStatus) { #da xac nhan
                                $totalMoneyConfirmed = $totalMoneyConfirmed + $money;
                            } else { # chua xac nhan
                                $totalMoneyNoConfirm = $totalMoneyNoConfirm + $money;
                            }
                            $dataWork = $salaryBeforePay->work;
                            ?>
                            <tr class="@if($n_o%2) info @endif">
                                <td class="text-center">
                                    {!! $n_o = $n_o + 1 !!}
                                </td>
                                <td>
                                    {!! date('d/m/Y',strtotime($salaryBeforePay->datePay()))  !!}
                                </td>
                                <td>
                                    <span>{!! $dataWork->companyStaffWork->staff->fullName() !!}</span>
                                </td>
                                <td class="text-right">
                                    @if (!$checkConfirmStatus)
                                        {!! $hFunction->currencyFormat($money) !!}
                                    @else
                                        <span>0</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    @if ($checkConfirmStatus)
                                        {!! $hFunction->currencyFormat($money) !!}
                                    @else
                                        <span>0</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        <tr style="border-top: 2px solid brown;">
                            <td class="text-right qc-color-red"
                                style="background-color: whitesmoke;" colspan="3">
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
                                <em class="qc-color-red">Không có thông tin chi ứng lương</em>
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
        <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <a class="btn btn-sm btn-primary" onclick="qc_main.page_back();">
                Về trang trước
            </a>
            <a class="btn btn-sm btn-default" href="{!! route('qc.work.home') !!}">
                Về trang chủ
            </a>
        </div>
    </div>
@endsection
