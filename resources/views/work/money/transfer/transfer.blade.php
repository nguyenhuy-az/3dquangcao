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
        <div class="qc_work_money_transfer_wrap qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            @include('work.money.money-menu')
            {{-- chi tiêt --}}
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row" style="margin-top: 10px;">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <span style="color: deeppink;">Tiền đã bàn giao </span>
                    </div>
                </div>
                <div class="qc_work_money_receive_list_content row">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr style="background-color: black; color: yellow;">
                                <th class="text-center" style="width: 20px;">STT</th>
                                <th>Ngày</th>
                                <th>Người nhận</th>
                                <th>Ghi chú</th>
                                <th class="text-center">Xác nhận</th>
                                <th class="text-right">Số tiền</th>
                            </tr>
                            <tr>
                                <td class="text-center"></td>
                                <td style="padding: 0;">
                                    <select class="qc_work_money_transfer_filter_month" style="height: 30px;"
                                            data-href="{!! $hrefFilter !!}">
                                        @for($m = 1; $m <=12; $m++)
                                            <option value="{!! $m !!}"
                                                    @if($monthFilter == $m) selected="selected" @endif>
                                                Tháng {!! $m !!}
                                            </option>
                                        @endfor
                                    </select>
                                    <select class="qc_work_money_transfer_filter_year" style="height: 30px;"
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
                                <td class="text-center"></td>
                                <td class="text-right"></td>
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
                                    ?>
                                    <tr class="@if($n_o%2) info @endif">
                                        <td class="text-center">
                                            {!! $n_o = $n_o + 1 !!}
                                        </td>
                                        <td>
                                            <a class="qcMoneyTransferView qc-link-green"
                                               data-href="{!! route('qc.work.money.transfer.transfer.view',$transfersId) !!}">
                                                {!! date('d/m/Y',strtotime($transfer->transfersDate()))  !!}
                                                &nbsp; <i class="glyphicon glyphicon-eye-open"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <span>{!! $transfer->receiveStaff->fullname() !!}</span>
                                        </td>
                                        <td>
                                            {!! $transfer->reason() !!}
                                        </td>
                                        <td class="text-center qc-color-grey">
                                            @if($transfer->checkConfirmReceive())
                                                <em>Đã xác nhận</em>
                                            @else
                                                <span style="background-color: red; color: white; padding: 3px;">Chưa xác nhận</span>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            {!! $hFunction->currencyFormat($transfer->money()) !!}
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="text-right qc-color-red"
                                        style="background-color: whitesmoke;" colspan="5">
                                    </td>
                                    <td class="text-right qc-color-red">
                                        {!! $hFunction->currencyFormat($totalMoney)  !!}
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td class="qc-padding-top-5 qc-padding-bot-5 text-center" colspan="6">
                                        <em class="qc-color-red">Không có thông tin giao tiền</em>
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
