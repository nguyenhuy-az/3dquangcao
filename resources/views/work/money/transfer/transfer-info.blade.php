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
                            <th style="width: 150px;">Ngày thu tiền</th>
                            <th>
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
                                $detailId = $transferDetail->detailId();
                                $orderPay = $transferDetail->orderPay;
                                $money = $orderPay->money();
                                $totalMoney = $totalMoney + $money;
                                $order = $orderPay->order;
                                ?>
                                <tr @if($n_o % 2) class="info" @endif>
                                    <td>
                                        <b style="color: blue;">
                                            {!! date('d/m/Y',strtotime($orderPay->datePay()))  !!}
                                        </b>
                                        @if($confirmReceiveStatus)
                                            <br/>
                                            <i class="glyphicon glyphicon-ok" style="font-size: 14px; color: green;"></i>
                                            <em style="color: grey;">Đã xác nhận</em>
                                        @else
                                            <br/>
                                            <a class="qc_transfer_detail_del qc-font-size-14 qc-link-red"
                                               data-href="{!! route('qc.work.money.transfer.transfer.info.delete',$detailId) !!}">
                                                HỦY
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        <b style="color: red;">
                                            {!! $hFunction->currencyFormat($money) !!}
                                        </b>
                                        <br/>
                                        <em style="color: grey;">ĐH: {!! $orderPay->order->name() !!}</em>
                                    </td>
                                </tr>
                            @endforeach
                            <tr style="border-top: 2px solid brown;">
                                <td class="text-right"
                                    style="background-color: whitesmoke;">
                                    Tổng chuyển
                                </td>
                                <td>
                                    <b style="color: red; font-size: 1.5em;">
                                        {!! $hFunction->currencyFormat($totalMoney) !!}
                                    </b>
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="qc-padding-top-5 qc-padding-bot-5 text-center" colspan="2">
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
