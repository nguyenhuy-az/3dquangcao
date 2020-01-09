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

$dataOrderPay = null;
$currentMonth = $hFunction->currentMonth();
If (empty($loginDay)) {
    $loginDate = date('Y-m', strtotime("$loginYear-$loginMonth"));
} else {
    $loginDate = date('Y-m-d', strtotime("$loginYear-$loginMonth-$loginDay"));
}
$dataOrderPay = $dataStaffLogin->orderPayInfoOfStaff($loginStaffId, $loginDate);
?>
@extends('work.index')
@section('titlePage')
    Lịch sử Thu
@endsection
@section('qc_work_body')
    <div class="row">
        <div class="qc_work_money_receive_wrap qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            @include('work.money.money-menu')

            {{-- chi tiêt --}}
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <label class="qc-color-red">LỊCH SỬ THU TIỀN</label>
                    </div>
                    <div class="text-right qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <select class="qc_work_money_history_receive_login_day" style="height: 25px;"
                                data-href="{!! route('qc.work.money.history.receive.get') !!}">
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
                        <select class="qc_work_money_history_receive_login_month" style="height: 25px;"
                                data-href="{!! route('qc.work.money.history.receive.get') !!}">
                            @for($i = 1; $i <=12; $i++)
                                <option @if($loginMonth == $i) selected="selected" @endif>
                                    {!! $i !!}
                                </option>
                            @endfor
                        </select>
                        <span>/</span>
                        <select class="qc_work_money_history_receive_login_year" style="height: 25px;"
                                data-href="{!! route('qc.work.money.history.receive.get') !!}">
                            @for($i = 2017; $i <=2050; $i++)
                                <option @if($loginYear == $i) selected="selected" @endif>
                                    {!! $i !!}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="qc_work_money_receive_list_content row">
                    <div class="qc-container-table col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <tr style="background-color: whitesmoke;">
                                    <th></th>
                                    <th>Ngày</th>
                                    <th>Mã ĐH</th>
                                    <th>Tên ĐH</th>
                                    <th>Ngày nhận ĐH</th>
                                    <th>Điện thoại</th>
                                    <th class="text-right">ĐH trong tháng {!! $loginMonth !!} </th>
                                    <th class="text-right">ĐH trước tháng {!! $loginMonth !!}</th>
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
                                        //$firstDateOfSort =
                                        $firstDateOfSort = $hFunction->firstDateOfMonthFromDate(date('Y/m/d', strtotime("1-$loginMonth-$loginYear")));
                                        $order = $orderPay->order;
                                        $orderReceiveDate = $order->receiveDate();
                                        if ($firstDateOfSort <= $orderReceiveDate) {
                                            $totalMoneyCurrentMonth = $totalMoneyCurrentMonth + $money;
                                        } else {
                                            $totalMoneyOldMonth = $totalMoneyOldMonth + $money;
                                        }
                                        ?>
                                        <tr>
                                            <td class="text-center">
                                                <input type="checkbox" checked="checked" name="txtPayShow[]"
                                                       disabled value="{!! $orderPay->payId() !!}">
                                                <input type="hidden" name="txtOrderPay[]"
                                                       value="{!! $orderPay->payId() !!}">
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
                                            <td>
                                                @if(!empty($orderPay->payerPhone()))
                                                    <span>{!! $orderPay->payerPhone() !!}</span>
                                                @else
                                                    <span>{!! $orderPay->order->customer->phone() !!}</span>
                                                @endif
                                            </td>
                                            <td class="text-right qc-color-red">
                                                @if($firstDateOfSort <= $orderReceiveDate)
                                                    {!! $hFunction->currencyFormat($money) !!}
                                                @else
                                                    ---
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                @if($firstDateOfSort > $orderReceiveDate)
                                                    {!! $hFunction->currencyFormat($money) !!}
                                                @else
                                                    ---
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td class="text-right qc-color-red"
                                            style="background-color: whitesmoke;" colspan="6">
                                        </td>
                                        <td class="text-right qc-color-green">
                                            <b>{!! $hFunction->currencyFormat($totalMoneyCurrentMonth) !!}</b>
                                        </td>
                                        <td class="text-right qc-color-green">
                                            <b>{!! $hFunction->currencyFormat($totalMoneyOldMonth) !!}</b>
                                        </td>
                                    </tr>
                                        <tr>
                                            <td class="text-right"
                                                style="background-color: whitesmoke;" colspan="6">
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
            </div>
        </div>
    </div>
@endsection
