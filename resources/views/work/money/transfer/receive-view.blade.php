<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$dataTransferDetail = $dataTransfer->transfersDetailInfo();
?>
@extends('components.container.container-10')
@section('qc_container_content')
    <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
             style="padding-top: 20px;padding-bottom: 20px; border-bottom: 2px dashed #C2C2C2;">
            <label class="qc-font-size-20">CHI TIẾT NHẬN TIỀN </label>
        </div>
        {{-- chi tiêt --}}
        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row" style="margin-top: 20px; border-left: 3px solid #C2C2C2;">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <em>Số tiền: </em>
                            <b>{!! $hFunction->currencyFormat($dataTransfer->money()) !!}</b>
                        </div>
                    </div>
                </div>
            </div>
            {{--nhan tu thu don hang--}}
            @if($dataTransfer->checkTransferOrderPay())
                <div class="row" style="margin-top: 20px;">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr style="background-color: black; color: yellow;">
                                <th style="width: 20px;"></th>
                                <th>Ngày thu tiền ĐH</th>
                                <th>Mã ĐH</th>
                                <th>Tên ĐH</th>
                                <th class="text-right">
                                    Số tiền
                                </th>
                            </tr>
                            @if($hFunction->checkCount($dataTransferDetail))
                                <?php
                                $n_o = 0;
                                $totalMoney = 0;
                                ?>
                                @foreach($dataTransferDetail as $transferDetail)
                                    <?php
                                    $orderPay = $transferDetail->orderPay;
                                    $money = $orderPay->money();
                                    $totalMoney = $totalMoney + $money;
                                    $order = $orderPay->order;
                                    ?>
                                    <tr>
                                        <td class="text-center">
                                            {!! $n_o = $n_o + 1 !!}
                                        </td>
                                        <td>
                                            {!! date('d/m/Y',strtotime($orderPay->datePay()))  !!}
                                        </td>
                                        <td>
                                            <span class="qc-color-grey">{!! $order->orderCode() !!}</span>
                                        </td>
                                        <td>
                                            <span>{!! $orderPay->order->name() !!}</span>
                                        </td>
                                        <td class="text-right qc-color-red">
                                            {!! $hFunction->currencyFormat($money) !!}
                                        </td>

                                    </tr>
                                @endforeach
                                <tr style="border-top: 2px solid brown;">
                                    <td class="text-right qc-color-red"
                                        style="background-color: whitesmoke;" colspan="4">
                                        Tổng thu
                                    </td>
                                    <td class="text-right qc-color-green">
                                        <b>{!! $hFunction->currencyFormat($totalMoney) !!}</b>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td class="qc-padding-top-5 qc-padding-bot-5 text-center" colspan="5">
                                        <em class="qc-color-red">Không có thông tin thu</em>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
                {{--nhan tu dau tu--}}
            @elseif($dataTransfer->checkTransferInvestment())
                <div class="row" style="margin-top: 20px; border-left: 3px solid #C2C2C2;">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="row">
                            <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <h4>Tiền đầu tư</h4>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="row">
            <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <button type="button" class="qc_container_close btn btn-sm btn-primary">
                    Đóng
                </button>
            </div>
        </div>
    </div>
@endsection
