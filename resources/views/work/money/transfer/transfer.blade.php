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
$hrefFilter = route('qc.work.money.transfer.transfer.get');
?>
@extends('work.money.transfer.index')
@section('titlePage')
    Giao tiền
@endsection
@section('qc_work_money_transfer_body')
    <div class="row">
        <div class="qc-border-none col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <a class="btn btn-sm btn-primary" onclick="qc_main.page_back();">
                Về trang trước
            </a>
        </div>
        <div class="qc_work_money_transfer_wrap qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            {{-- chi tiêt --}}
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="qc_work_money_receive_list_content row">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr style="background-color: black; color: yellow;">
                                <th style="width: 170px;">Ngày</th>
                                <th style="width: 150px;">Số tiền</th>
                                <th style="width: 170px;">Người nhận</th>
                                <th>Ghi chú</th>
                            </tr>
                            <tr>
                                <td style="padding: 0;">
                                    <select class="qc_work_money_transfer_filter_month col-sx-5 col-sm-5 col-md-5 col-lg-5"
                                            style="height: 34px; padding: 0;"
                                            data-href="{!! $hrefFilter !!}">
                                        @for($m = 1; $m <=12; $m++)
                                            <option value="{!! $m !!}"
                                                    @if($monthFilter == $m) selected="selected" @endif>
                                                Tháng {!! $m !!}
                                            </option>
                                        @endfor
                                    </select>
                                    <select class="qc_work_money_transfer_filter_year col-sx-7 col-sm-7 col-md-7 col-lg-7"
                                            style="height: 34px; padding: 0;"
                                            data-href="{!! $hrefFilter !!}">
                                        @for($y = 2017; $y <=2050; $y++)
                                            <option value="{!! $y !!}"
                                                    @if($yearFilter == $y) selected="selected" @endif>
                                                Năm {!! $y !!}
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
                                    $transfersId = $transfer->transfersId();
                                    $totalMoney = $totalMoney + $transfer->money();
                                    $confirmReceiveStatus = $transfer->checkConfirmReceive();
                                    # thong tin nguoi nhan
                                    $dataReceiveStaff = $transfer->receiveStaff;
                                    ?>
                                    <tr class="@if($n_o%2) info @endif">
                                        <td>
                                            <span style="color: blue;">
                                                {!! date('d/m/Y',strtotime($transfer->transfersDate()))  !!}
                                            </span>
                                            <br/>
                                            @if(!$confirmReceiveStatus)
                                                &nbsp;
                                                <a class="qc-font-size-14 qc-link-green-bold"
                                                   href="{!! route('qc.work.money.transfer.transfer.info.get',$transfersId) !!}">
                                                    SỬA
                                                </a>
                                                <span class="qc-font-size-14">|</span>
                                                <a class="qc_transfer_cancel qc-font-size-14 qc-link-red-bold"
                                                   data-href="{!! route('qc.work.money.transfer.transfer.cancel.get',$transfersId) !!}">
                                                    HỦY
                                                </a>
                                            @else
                                                <a class="qcMoneyTransferView qc-font-size-1 qc-link-green"
                                                   data-href="{!! route('qc.work.money.transfer.transfer.view',$transfersId) !!}">
                                                    CHI TIẾT
                                                </a>
                                            @endif
                                        </td>
                                        <td >
                                            <b style="color: red;">{!! $hFunction->currencyFormat($transfer->money()) !!}</b>
                                            @if($confirmReceiveStatus)
                                                <br/>
                                                <i class="qc-font-size-12 glyphicon glyphicon-ok" style="color: green;"></i>
                                                <em style="color: grey;">Đã xác nhận</em>
                                            @else
                                                <br/>
                                                <i class="qc-font-size-12 glyphicon glyphicon-ok" style="color: red;"></i>
                                                <em style="color: grey;" >Chưa xác nhận</em>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="media">
                                                <a class="pull-left" href="#">
                                                    <img class="media-object"
                                                         style="background-color: white; width: 40px;height: 40px; border: 1px solid #d7d7d7;border-radius: 10px;"
                                                         src="{!! $dataReceiveStaff->pathAvatar($dataReceiveStaff->image()) !!}">
                                                </a>

                                                <div class="media-body">
                                                    <h5 class="media-heading">{!! $dataReceiveStaff->lastName() !!}</h5>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            {!! $transfer->reason() !!}
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td></td>
                                    <td class="qc-color-red">
                                        <b style="font-size: 1.5em;">
                                            {!! $hFunction->currencyFormat($totalMoney)  !!}
                                        </b>
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                            @else
                                <tr>
                                    <td class="qc-padding-top-5 qc-padding-bot-5 text-center" colspan="4">
                                        <em class="qc-color-red">Không có thông tin giao tiền</em>
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
