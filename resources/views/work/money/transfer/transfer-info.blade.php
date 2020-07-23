<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$confirmReceiveStatus = $dataTransfer->checkConfirmReceive();
$transfersId = $dataTransfer->transfersId();
?>
@extends('work.money.transfer.index')
@section('titlePage')
    Thông tin Giao tiền
@endsection
@section('qc_work_money_transfer_body')
    <div class="qc_work_money_transfer_transfer_info qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <a class="" onclick="qc_main.page_back_go()">
                    <button type="button" class="btn btn-primary">Về trang trước</button>
                </a>
            </div>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
             style="border-bottom: 1px dashed #C2C2C2;">
            <h4 style="color: deeppink">Chi tiết chuyển tiền</h4>
        </div>
        {{-- chi tiêt --}}
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: black; color: yellow;">
                            <th style="width: 20px;"></th>
                            <th>Ngày thu tiền</th>
                            <th>Mã ĐH</th>
                            <th>Tên ĐH</th>
                            <th class="text-right">
                                Số tiền
                            </th>
                            <th></th>
                        </tr>
                        @if($hFunction->checkCount($dataTransferDetail))
                            <?php
                            $n_o = 0;
                            $totalMoney = 0;
                            ?>
                            @foreach($dataTransferDetail as $transferDetail)
                                <?php
                                $detailId = $transferDetail->detailId();
                                $orderPay = $transferDetail->orderPay;
                                $money = $orderPay->money();
                                $totalMoney = $totalMoney + $money;
                                $order = $orderPay->order;
                                ?>
                                <tr @if($n_o % 2) class="info" @endif>
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
                                    <td class="text-right" style="color: blue;">
                                        {!! $hFunction->currencyFormat($money) !!}
                                    </td>
                                    <td class="text-center">
                                        @if($confirmReceiveStatus)
                                            <span>Đã xác nhận</span>
                                        @else
                                            <a class="qc_transfer_detail_del qc-link-red"
                                               data-href="{!! route('qc.work.money.transfer.transfer.info.delete',$detailId) !!}">
                                                <i class="glyphicon glyphicon-trash" style="font-size: 16px;"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr style="border-top: 2px solid brown;">
                                <td class="text-right qc-color-red"
                                    style="background-color: whitesmoke;" colspan="4">
                                    Tổng chuyển
                                </td>
                                <td class="text-right qc-color-green">
                                    <b>{!! $hFunction->currencyFormat($totalMoney) !!}</b>
                                </td>
                                <td></td>
                            </tr>
                        @else
                            <tr>
                                <td class="qc-padding-top-5 qc-padding-bot-5 text-center" colspan="6">
                                    <em class="qc-color-red">Không có thông tin thu</em>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
