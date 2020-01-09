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

$currentMonth = $hFunction->currentMonth();
If (empty($loginDay)) {
    $loginDate = date('Y-m', strtotime("$loginYear-$loginMonth"));
} else {
    $loginDate = date('Y-m-d', strtotime("$loginYear-$loginMonth-$loginDay"));
}
$dataTransfer = $dataStaffLogin->transferOfReceiveStaff($loginStaffId, $loginDate);
?>
@extends('work.index')
@section('titlePage')
    Nhận tiền
@endsection
@section('qc_work_body')
    <div class="row">
        <div class="qc_work_money_transfer_receive_wrap qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            @include('work.money.money-menu')

            {{-- chi tiêt --}}
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <label class="qc-color-red">NHẬN TIỀN</label>
                    </div>
                    <div class="text-right qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">

                        <select class="qc_work_money_transfer_login_day" style="height: 25px;"
                                data-href="{!! route('qc.work.money.transfer.receive.get') !!}">
                            <option value="0" @if($loginDay == null) selected="selected" @endif>
                                Trong tháng
                            </option>
                            @for($i = 1; $i <=31; $i++)
                                <option @if($loginDay == $i) selected="selected" @endif>
                                    {!! $i !!}
                                </option>
                            @endfor
                        </select>
                        <span>/</span>
                        <select class="qc_work_money_transfer_login_month" style="height: 25px;"
                                data-href="{!! route('qc.work.money.transfer.receive.get') !!}">
                            @for($i = 1; $i <=12; $i++)
                                <option @if($loginMonth == $i) selected="selected" @endif>
                                    {!! $i !!}
                                </option>
                            @endfor
                        </select>
                        <span>/</span>
                        <select class="qc_work_money_transfer_login_year" style="height: 25px;"
                                data-href="{!! route('qc.work.money.transfer.receive.get') !!}">
                            @for($i = 2017; $i <=2050; $i++)
                                <option @if($loginYear == $i) selected="selected" @endif>
                                    {!! $i !!}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="qc_work_money_transfer_receive_list_content qc-container-table row">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <tr style="background-color: whitesmoke;">
                                    <th class="text-center">STT</th>
                                    <th>Ngày</th>
                                    <th>Người nhận</th>
                                    <th>Người giao</th>
                                    <th class="text-center">Xác nhận</th>
                                    <th class="text-right">Số tiền</th>
                                </tr>
                                @if(count($dataTransfer) > 0)
                                    <?php
                                    $n_o = 0;
                                    $totalMoney = 0;
                                    ?>
                                @foreach($dataTransfer as $transfer)
                                    <?php
                                    $transferId = $transfer->transfersId();
                                    $transferMoney = $transfer->money();
                                    $totalMoney = $totalMoney + $transferMoney;
                                    $n_o = $n_o + 1;
                                    ?>
                                    <tr>
                                        <td class="text-center">
                                            {!! $n_o !!}
                                        </td>
                                        <td>
                                            {!! date('d/m/Y',strtotime($transfer->transfersDate()))  !!}
                                        </td>
                                        <td>
                                            <span>{!! $transfer->receiveStaff->fullname() !!}</span>
                                        </td>
                                        <td>
                                            <span>{!! $transfer->transfersStaff->fullname() !!}</span>
                                        </td>
                                        <td class="text-center qc-color-grey qc-padding-none">
                                            @if($transfer->checkConfirmReceive())
                                                <em>Đã xác nhận</em>
                                            @else
                                                <a class="qc_receive_confirm_act qc-link-green" data-money="{!! $transferMoney !!}"
                                                   data-href="{!! route('qc.work.money.transfer.receive.confirm.get',$transferId) !!}">
                                                    Xác nhận
                                                </a>
                                            @endif
                                        </td>
                                        <td class="text-right qc-padding-none">
                                            {!! $hFunction->currencyFormat($transfer->money()) !!}
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="text-right qc-color-red"
                                        style="background-color: whitesmoke;" colspan="5">
                                    </td>
                                    <td class="text-right qc-color-red">
                                        {!! number_format($totalMoney)  !!}
                                    </td>
                                </tr>
                                @else
                                    <tr>
                                        <td class="qc-padding-top-5 qc-padding-bot-5 text-center" colspan="6">
                                            <em class="qc-color-red">Không thông tin thu</em>
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
