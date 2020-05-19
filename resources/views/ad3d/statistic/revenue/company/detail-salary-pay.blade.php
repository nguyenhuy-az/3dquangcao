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
            <label class="qc-font-size-20">THANH TOÁN LƯƠNG </label>
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

            <div class="qc-ad3d-table-container row" style="margin-top: 20px;">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: whitesmoke;">
                            <th></th>
                            <th>Ngày</th>
                            <th>Người nhận</th>
                            <th class="text-right">Số tiền</th>
                        </tr>
                        @if($hFunction->checkCount($dataSalaryPay))
                            <?php
                            $n_o = 0;
                            $totalMoney = 0;
                            ?>
                            @foreach($dataSalaryPay as $salaryPay)
                                <?php
                                $money = $salaryPay->money();
                                $totalMoney = $totalMoney + $money;
                                ?>
                                <tr class="@if($n_o%2) info @endif">
                                    <td class="text-center">
                                        {!! $n_o = $n_o + 1 !!}
                                    </td>
                                    <td>
                                        {!! date('d/m/Y',strtotime($salaryPay->datePay()))  !!}
                                    </td>
                                    <td>
                                        <span>{!! $salaryPay->salary->work->companyStaffWork->staff->fullName() !!}</span>
                                    </td>
                                    <td class="text-right">
                                        {!! $hFunction->currencyFormat($money) !!}
                                    </td>
                                </tr>
                            @endforeach
                            <tr style="border-top: 2px solid brown;">
                                <td class="text-right qc-color-red"
                                    style="background-color: whitesmoke;" colspan="3">
                                </td>
                                <td class="text-right qc-color-green">
                                    <b>{!! $hFunction->currencyFormat($totalMoney) !!}</b>
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="qc-padding-top-5 qc-padding-bot-5 text-center" colspan="4">
                                    <em class="qc-color-red">Không có dữ liệu</em>
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
