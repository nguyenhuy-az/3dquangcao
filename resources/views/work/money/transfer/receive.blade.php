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
$hrefFilter = route('qc.work.money.transfer.receive.get');
?>
@extends('work.money.transfer.index')
@section('titlePage')
    Nhận tiền
@endsection
@section('qc_work_money_transfer_body')
    <div class="row">
        <div class="qc_work_money_transfer_receive_wrap qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            {{-- chi tiêt --}}
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="qc_work_money_transfer_receive_list_content row">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr style="background-color: black; color: yellow;">
                                <th style="width: 140px;">Ngày</th>
                                <th style="width: 120px;">Số tiền</th>
                                <th style="width: 170px;">Người chuyển</th>
                                <th>Hình thức</th>
                            </tr>
                            <tr>
                                <td style="padding: 0;">
                                    <select class="qc_work_money_transfer_filter_month col-sx-6 col-sm-6 col-md-6 col-lg-6"
                                            style="height: 34px; padding: 0;"
                                            data-href="{!! $hrefFilter !!}">
                                        @for($m = 1; $m <=12; $m++)
                                            <option value="{!! $m !!}"
                                                    @if($monthFilter == $m) selected="selected" @endif>
                                                Tháng {!! $m !!}
                                            </option>
                                        @endfor
                                    </select>
                                    <select class="qc_work_money_transfer_filter_year col-sx-6 col-sm-6 col-md-6 col-lg-6"
                                            style="height: 34px;padding: 0;"
                                            data-href="{!! $hrefFilter !!}">
                                        @for($y = 2017; $y <=2050; $y++)
                                            <option value="{!! $y !!}"
                                                    @if($yearFilter == $y) selected="selected" @endif>
                                                {!! $y !!}
                                            </option>
                                        @endfor
                                    </select>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @if($hFunction->checkCount($dataTransfer))
                                <?php
                                $n_o = 0;
                                $totalMoney = 0;
                                ?>
                                @foreach($dataTransfer as $transfer)
                                    <?php
                                    $transferId = $transfer->transfersId();
                                    $reason = $transfer->reason();
                                    $transferMoney = $transfer->money();
                                    $totalMoney = $totalMoney + $transferMoney;
                                    $n_o = $n_o + 1;
                                    # thong tin nguoi chuyen
                                    $dataTransfersStaff = $transfer->transfersStaff;
                                    ?>
                                    <tr>
                                        <td>
                                            <b style="color: blue;">
                                                {!! date('d/m/Y',strtotime($transfer->transfersDate()))  !!}
                                            </b>
                                            <br/>
                                            <a class="qcMoneyReceiveView qc-link-green"
                                               data-href="{!! route('qc.work.money.transfer.receive.view',$transferId) !!}">
                                                CHI TIẾT
                                            </a>
                                        </td>
                                        <td>
                                            <b style="color: red;">
                                                {!! $hFunction->currencyFormat($transfer->money()) !!}
                                            </b>
                                            @if($transfer->checkConfirmReceive())
                                                <br/>
                                                <i class="qc-font-size-12 glyphicon glyphicon-ok"
                                                   style="color: green;"></i>
                                                <em style="color: grey;">Đã xác nhận</em>
                                            @else
                                                <br/>
                                                <a class="qc_receive_confirm_act qc-font-size-14 qc-link-green-bold"
                                                   data-money="{!! $transferMoney !!}"
                                                   data-href="{!! route('qc.work.money.transfer.receive.confirm.get',$transferId) !!}">
                                                    XÁC NHẬN
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="media">
                                                <a class="pull-left" href="#">
                                                    <img class="media-object"
                                                         style="background-color: white; width: 40px;height: 40px; border: 1px solid #d7d7d7;border-radius: 10px;"
                                                         src="{!! $dataTransfersStaff->pathAvatar($dataTransfersStaff->image()) !!}">
                                                </a>

                                                <div class="media-body">
                                                    <h5 class="media-heading">{!! $dataTransfersStaff->lastName() !!}</h5>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <b>
                                                {!! $transfer->transferTypeName() !!}
                                            </b>
                                            @if(!$hFunction->checkEmpty($reason))
                                                <br/>
                                                <em style="color: grey;">
                                                    {!! $reason !!}
                                                </em>
                                            @endif
                                            {{--thu tien don hang--}}
                                            @if($transfer->checkTransferOrderPay())
                                                <br/>
                                                <?php
                                                $dataTransferDetail = $transfer->transfersDetailInfo();
                                                ?>
                                                @if($hFunction->checkCount($dataTransferDetail))
                                                    @foreach($dataTransferDetail as $transferDetail)
                                                        <em style="color: grey;">   {!! $transferDetail->orderPay->order->name() !!}</em>
                                                        <span>|</span>
                                                    @endforeach
                                                @endif
                                            @endif
                                        </td>

                                    </tr>
                                @endforeach
                                <tr>
                                    <td></td>
                                    <td>
                                        <b style="color: red; font-size: 1.5em;">
                                            {!! $hFunction->currencyFormat($totalMoney)  !!}
                                        </b>
                                    </td>
                                    <td colspan="2">

                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td class="qc-padding-top-5 qc-padding-bot-5 text-center" colspan="4">
                                        <em class="qc-color-red">Không có thông tin nhận tiền</em>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <a class="btn btn-sm btn-primary" onclick="qc_main.page_back();">
                Về trang trước
            </a>
            <a class="btn btn-sm btn-default" href="{!! route('qc.work.home') !!}">
                Về trang chủ
            </a>
        </div>
    </div>
@endsection
