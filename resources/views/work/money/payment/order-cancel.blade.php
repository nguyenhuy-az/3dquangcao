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
    Thống kê chi hoạt đông đã xác nhận
@endsection
@section('qc_work_money_payment_content')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <tr style="background-color: black; color: yellow;">
                        <th style="width: 20px;"></th>
                        <th>Ngày chi</th>
                        <th>Đơn hàng</th>
                        <th>Ghi chú</th>
                        <th class="text-right">Số tiền</th>
                    </tr>
                    @if($hFunction->checkCount($dataOrderCancel))
                        <?php
                        $n_o = 0;
                        $totalMoney = 0;
                        ?>
                        @foreach($dataOrderCancel as $orderCancel)
                            <?php
                            $cancaleId = $orderCancel->cancelId();
                            $money = $orderCancel->payment();
                            $totalMoney = $totalMoney + $money;
                            $dataOrder = $orderCancel->order;
                            ?>
                            <tr class="@if($n_o%2) info @endif">
                                <td class="text-center">
                                    {!! $n_o = $n_o + 1 !!}
                                </td>
                                <td>
                                    {!! date('d/m/Y',strtotime($orderCancel->cancelDate()))  !!}
                                </td>
                                <td>
                                    <span>{!! $dataOrder->name() !!}</span>
                                </td>
                                <td>
                                    <span>{!! $orderCancel->reason() !!}</span>
                                </td>
                                <td class="text-right">
                                    {!! $hFunction->currencyFormat($money) !!}
                                </td>
                            </tr>
                        @endforeach
                        <tr style="border-top: 2px solid brown;">
                            <td class="text-right qc-color-red"
                                style="background-color: whitesmoke;" colspan="4">
                            </td>
                            <td class="text-right qc-color-red">
                                <b>{!! $hFunction->currencyFormat($totalMoney) !!}</b>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td class="qc-padding-top-5 qc-padding-bot-5 text-center" colspan="5">
                                <em class="qc-color-red">Không có thông tin thanh toán</em>
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
