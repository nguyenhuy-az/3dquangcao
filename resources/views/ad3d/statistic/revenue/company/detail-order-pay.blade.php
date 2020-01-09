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
            <label class="qc-font-size-20">NHẬN TIỀN ĐƠN HÀNG TRONG THÁNG </label>
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
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: whitesmoke;">
                            <th></th>
                            <th>Ngày</th>
                            <th>Mã ĐH</th>
                            <th>Tên ĐH</th>
                            <th>Ngày nhận ĐH</th>
                            <th class="text-right">
                                ĐH trong tháng <br/>
                                {!! $statisticDateView !!}
                            </th>
                            <th class="text-right">
                                ĐH trước tháng <br/>
                                {!! $statisticDateView !!}
                            </th>
                        </tr>
                        @if(count($dataOrderPay) > 0)
                            <?php
                            $n_o = 0;
                            $totalMoney = 0;
                            $totalMoneyCurrentMonth = 0;
                            $totalMoneyOldMonth = 0;
                            ?>
                            @foreach($dataOrderPay as $orderPay)
                                <?php
                                $money = $orderPay->money();
                                $totalMoney = $totalMoney + $money;
                                $checkMonth = date('m', strtotime($statisticDate));
                                $checkYear = date('Y', strtotime($statisticDate));
                                $firstDateOfSort = $hFunction->firstDateOfMonthFromDate(date('Y/m/d', strtotime("1-$checkMonth-$checkYear")));
                                $order = $orderPay->order;
                                $orderReceiveDate = $order->receiveDate();
                                if ($firstDateOfSort <= $orderReceiveDate) {
                                    $totalMoneyCurrentMonth = $totalMoneyCurrentMonth + $money;
                                } else {
                                    $totalMoneyOldMonth = $totalMoneyOldMonth + $money;
                                }
                                ?>
                                <tr class="@if($firstDateOfSort > $orderReceiveDate) warning @endif">
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
                                    <td>
                                        <span class="qc-color-grey">{!! date('d/m/Y',strtotime($orderReceiveDate))  !!}</span>
                                    </td>
                                    <td class="text-right qc-color-red">
                                        @if($firstDateOfSort <= $orderReceiveDate)
                                            {!! $hFunction->currencyFormat($money) !!}
                                        @else
                                            0
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if($firstDateOfSort > $orderReceiveDate)
                                            {!! $hFunction->currencyFormat($money) !!}
                                        @else
                                            0
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr style="border-top: 2px solid brown;">
                                <td class="text-right qc-color-red"
                                    style="background-color: whitesmoke;" colspan="5">
                                </td>
                                <td class="text-right qc-color-green">
                                    <b>{!! $hFunction->currencyFormat($totalMoneyCurrentMonth) !!}</b>
                                </td>
                                <td class="text-right qc-color-green">
                                    <b>{!! $hFunction->currencyFormat($totalMoneyOldMonth) !!}</b>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right qc-color-red"
                                    style="background-color: whitesmoke;" colspan="6">
                                    Tổng thu
                                </td>
                                <td class="text-right qc-color-red" colspan="2">
                                    <b>{!! $hFunction->currencyFormat($totalMoneyCurrentMonth + $totalMoneyOldMonth) !!}</b>
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="qc-padding-top-5 qc-padding-bot-5 text-center" colspan="8">
                                    <em class="qc-color-red">Không có thông tin thu</em>
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
