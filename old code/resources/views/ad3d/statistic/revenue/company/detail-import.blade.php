<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$statisticDateView = date('m-Y', strtotime($statisticDate));
?>
@extends('ad3d.components.container.container-8')
@section('qc_ad3d_container_content')
    <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
             style="padding-top: 20px;padding-bottom: 20px; border-bottom: 2px dashed #C2C2C2;">
            <label class="qc-font-size-20">MUA VẬT TƯ </label>
            <em class="qc-color-red">Trong tháng {!! $statisticDateView !!}</em>
        </div>
        {{-- chi tiêt --}}
        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row" style="margin-top: 20px; border-left: 3px solid #C2C2C2;">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <em>Nhân Viên: </em>
                            <b>{!! $dataStaff->fullName() !!}</b>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" style="margin-top: 20px;">
                <div class="table-responsive qc-container-table">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: whitesmoke;">
                            <th></th>
                            <th>Ngày</th>
                            <th class="text-center">Thanh toán</th>
                            <th class="text-right">Số tiền</th>
                            <th class="text-right">Đã thanh toán</th>
                            <th class="text-right">Chưa thanh toán</th>
                        </tr>
                        @if(count($dataImport) > 0)
                            <?php
                            $n_o = 0;
                            $sumMoney = 0;
                            $sumPaid = 0;
                            $sumUnPaid = 0
                            ?>
                            @foreach($dataImport as $import)
                                <?php
                                $importId = $import->importId();
                                $importDate = $import->importDate();
                                $totalMoneyOfImport = $import->totalMoneyOfImport();
                                $sumMoney = $sumMoney + $totalMoneyOfImport;
                                if ($import->checkPay()) { # da thanh toan
                                    $moneyPaid = $totalMoneyOfImport;
                                    $sumPaid = $sumPaid + $moneyPaid;
                                    $moneyUnPaid = 0;
                                } else {
                                    $moneyPaid = 0;
                                    $moneyUnPaid = $totalMoneyOfImport;
                                    $sumUnPaid = $sumUnPaid + $moneyUnPaid;
                                }
                                ?>
                                <tr class="@if($n_o%2) info @endif">
                                    <td class="text-center">
                                        {!! $n_o = $n_o+ 1!!}
                                    </td>
                                    <td>
                                        {!! date('d/m/Y',strtotime($importDate))  !!}
                                    </td>
                                    <td class="text-center">
                                        @if($import->checkPay())
                                            @if($import->checkPayConfirmOfImport($importId))
                                                <em class="qc-color-grey">Đã Nhận tiền</em>
                                            @else
                                                <em class="qc-color-grey">Chưa Xác nhận thanh toán</em>
                                            @endif
                                        @else
                                            <em>Chưa thanh toán</em>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        {!! $hFunction->currencyFormat($totalMoneyOfImport) !!}
                                    </td>
                                    <td class="text-right qc-color-red">
                                        {!! $hFunction->currencyFormat($moneyPaid)  !!}
                                    </td>
                                    <td class="text-right qc-color-green">
                                        {!! $hFunction->currencyFormat($moneyUnPaid)  !!}
                                    </td>
                                </tr>
                            @endforeach
                            <tr style="border-top: 2px solid brown;">
                                <td class="text-right qc-color-red" style="background-color: whitesmoke;" colspan="3"></td>
                                <td class="text-right qc-color-green">
                                    {!! number_format($sumMoney)  !!}
                                </td>
                                <td class="text-right qc-color-red">
                                    {!! $hFunction->currencyFormat($sumPaid)  !!}
                                </td>
                                <td class="text-right qc-color-green">
                                    {!! $hFunction->currencyFormat($sumUnPaid)  !!}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="text-right" colspan="6">
                                    <em class="qc-color-red">Không có thông tin mua</em>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <button type="button" class="qc_ad3d_container_close btn btn-sm btn-primary">
                    Đóng
                </button>
            </div>
        </div>
    </div>
@endsection
