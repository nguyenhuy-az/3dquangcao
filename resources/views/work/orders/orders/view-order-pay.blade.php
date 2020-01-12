<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
?>
@extends('components.container.container-8')
@section('qc_container_content')
    <div class="qc-padding-top-20 qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <h3>{!! $dataOrder->name() !!}</h3>
            <em>KH: {!! $dataOrder->customer->name() !!}</em>
        </div>
        {{-- chi tiết Thanh toán --}}
        <div class="qc-padding-top-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row qc-padding-top-10">
                <div class="qc-container-table col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" style="margin-bottom: 0;">
                            <tr style="background-color: whitesmoke;">
                                <th colspan="6">
                                    <i class="qc-font-size-16 glyphicon glyphicon-credit-card"></i>
                                    <b class="qc-color-red">CHI TIẾT THANH TOÁN</b>
                                </th>
                            </tr>
                            <tr>
                                <th class="text-center" style="width: 20px;">STT</th>
                                <th>Thủ quỹ</th>
                                <th>Tên KH</th>
                                <th>Điện thoại KH</th>
                                <th>Ngày</th>
                                <th class="text-right">Số tiền</th>
                            </tr>
                            <?php
                            $dataOrderPay = $dataOrder->infoOrderPayOfOrder();
                            $n_o = 0;
                            $totalMoney = 0;
                            ?>
                            @if($hFunction->checkCount($dataOrderPay))
                                @foreach($dataOrderPay as $orderPay)
                                    <?php
                                    $money = $orderPay->money();
                                    $totalMoney = $totalMoney  + $money;
                                    ?>
                                    <tr>
                                        <td class="text-center">
                                            {!! $n_o+=1  !!}
                                        </td>
                                        <td>
                                            {!! $orderPay->staff->fullName() !!}
                                        </td>
                                        <td>
                                            @if(!empty($orderPay->payerName()))
                                                <span>{!! $orderPay->payerName() !!}</span>
                                            @else
                                                <span>{!! $orderPay->order->customer->name() !!}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(!empty($orderPay->payerPhone()))
                                                <span>{!! $orderPay->payerPhone() !!}</span>
                                            @else
                                                <span>{!! $orderPay->order->customer->phone() !!}</span>
                                            @endif
                                        </td>
                                        <td>
                                            {!! $hFunction->convertDateDMYFromDatetime($orderPay->datePay())  !!}
                                        </td>
                                        <td class="text-right">
                                            {!! $hFunction->currencyFormat($money) !!}
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="text-right" colspan="6">
                                        <b class="qc-color-red">{!! $hFunction->currencyFormat($totalMoney) !!}</b>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td class="qc-padding-top-10" colspan="6">
                                        @if($dataOrder->discount()== 100)
                                            <em class="qc-color-red">Giảm 100%</em>
                                        @else
                                            <em style="color: brown;">Chưa thanh toán</em>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="qc-padding-top-10 text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <a class="qc_container_close btn btn-sm btn-primary">Đóng</a>
        </div>
    </div>
@endsection
