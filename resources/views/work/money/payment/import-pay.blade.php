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
    Thống kê thanh toán mua vật tu
@endsection
@section('qc_work_money_payment_content')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <tr style="background-color: black; color: yellow;">
                        <th style="width: 20px;"></th>
                        <th>Ngày thanh toán</th>
                        <th>Người mua</th>
                        <th class="text-center">
                            Ngày mua
                        </th>
                        <th class="text-center">Tiền chưa xác nhận</th>
                        <th class="text-right">Tiền đã xác nhận</th>
                    </tr>
                    @if($hFunction->checkCount($dataImportPay))
                        <?php
                        $n_o = 0;
                        $totalMoneyConfirmed = 0;
                        $totalMoneyNoConfirm = 0;
                        ?>
                        @foreach($dataImportPay as $importPay)
                            <?php
                            $money = $importPay->money();
                            if ($importPay->checkConfirm()) {
                                $totalMoneyConfirmed = $totalMoneyConfirmed + $money;
                            } else {
                                $totalMoneyNoConfirm = $totalMoneyNoConfirm + $money;
                            }
                            $dataImport = $importPay->import;
                            ?>
                            <tr class="@if($n_o%2) info @endif">
                                <td class="text-center">
                                    {!! $n_o = $n_o + 1 !!}
                                </td>
                                <td>
                                    {!! date('d/m/Y',strtotime($importPay->createdAt()))  !!}
                                </td>
                                <td>
                                    <span>{!! $dataImport->staffImport->fullName() !!}</span>
                                </td>
                                <td class="text-center">
                                    {!! date('d/m/Y',strtotime($dataImport->importDate()))  !!}
                                </td>
                                <td class="text-right">
                                    @if($importPay->checkConfirm())
                                        <span>0</span>
                                    @else
                                        {!! $hFunction->currencyFormat($money) !!}
                                    @endif
                                </td>
                                <td class="text-right">
                                    @if($importPay->checkConfirm())
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
                            <td class="text-right" style="color: blue;">
                                <b>{!! $hFunction->currencyFormat($totalMoneyConfirmed) !!}</b>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td class="text-center" colspan="6">
                                <em class="qc-color-red">Không có thông tin thanh toán</em>
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
@endsection
