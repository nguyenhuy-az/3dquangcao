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
                                <th class="text-center" style="width: 20px;"></th>
                                <th>Thời gian</th>
                                <th>Người chuyển</th>
                                <th>Hình thức</th>
                                <th>Ghi chú</th>
                                <th class="text-center">Xác nhận</th>
                                <th class="text-right">Số tiền</th>
                            </tr>
                            <tr>
                                <td class="text-center"></td>
                                <td style="padding: 0;">
                                    <select class="qc_work_money_transfer_filter_month col-sx-5 col-sm-5 col-md-5 col-lg-5" style="height: 34px; padding: 0;"
                                            data-href="{!! $hrefFilter !!}">
                                        @for($m = 1; $m <=12; $m++)
                                            <option value="{!! $m !!}" @if($monthFilter == $m) selected="selected" @endif>
                                                Tháng {!! $m !!}
                                            </option>
                                        @endfor
                                    </select>
                                    <select class="qc_work_money_transfer_filter_year col-sx-7 col-sm-7 col-md-7 col-lg-7" style="height: 34px;padding: 0;"
                                            data-href="{!! $hrefFilter !!}">
                                        @for($y = 2017; $y <=2050; $y++)
                                            <option value="{!! $y !!}" @if($yearFilter == $y) selected="selected" @endif>
                                                Năm {!! $y !!}
                                            </option>
                                        @endfor
                                    </select>
                                </td>
                                <td></td>
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
                                    $transferId = $transfer->transfersId();
                                    $transferMoney = $transfer->money();
                                    $totalMoney = $totalMoney + $transferMoney;
                                    $n_o = $n_o + 1;
                                    # thong tin nguoi chuyen
                                    $dataTransfersStaff = $transfer->transfersStaff;
                                    ?>
                                    <tr>
                                        <td class="text-center">
                                            {!! $n_o !!}
                                        </td>
                                        <td>
                                            <a class="qcMoneyReceiveView qc-link-green"
                                               data-href="{!! route('qc.work.money.transfer.receive.view',$transferId) !!}">
                                                {!! date('d/m/Y',strtotime($transfer->transfersDate()))  !!}
                                                &nbsp; <i class="glyphicon glyphicon-eye-open"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <div class="media">
                                                <a class="pull-left" href="#">
                                                    <img class="media-object"
                                                         style="max-width: 40px;height: 40px; border: 1px solid #d7d7d7;border-radius: 10px;"
                                                         src="{!! $dataTransfersStaff->pathAvatar($dataTransfersStaff->image()) !!}">
                                                </a>
                                                <div class="media-body">
                                                    <h5 class="media-heading">{!! $dataTransfersStaff->fullName() !!}</h5>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            {!! $transfer->transferTypeName() !!}
                                        </td>
                                        <td>
                                            {!! $transfer->reason() !!}
                                        </td>
                                        <td class="text-center qc-color-grey qc-padding-none">
                                            @if($transfer->checkConfirmReceive())
                                                <em>Đã xác nhận</em>
                                            @else
                                                <a class="qc_receive_confirm_act qc-link-red" data-money="{!! $transferMoney !!}"
                                                   data-href="{!! route('qc.work.money.transfer.receive.confirm.get',$transferId) !!}">
                                                    XÁC NHẬN
                                                </a>
                                            @endif
                                        </td>
                                        <td class="text-right" style="color: blue;">
                                            {!! $hFunction->currencyFormat($transfer->money()) !!}
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="text-right qc-color-red"
                                        style="background-color: whitesmoke;" colspan="6">
                                    </td>
                                    <td class="text-right qc-color-red">
                                        {!! $hFunction->currencyFormat($totalMoney)  !!}
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td class="qc-padding-top-5 qc-padding-bot-5 text-center" colspan="8">
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
