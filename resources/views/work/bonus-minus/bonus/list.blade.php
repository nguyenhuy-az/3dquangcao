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
$hrefIndex = route('qc.work.bonus.get');
?>
@extends('work.bonus-minus.bonus.index')
@section('qc_work_bonus_body')
    <div class="row">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            {{-- chi tiêt --}}
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr style="background-color: black; color: yellow;">
                                <th class="text-center" style="width: 20px;">STT</th>
                                <th>Ngày</th>
                                <th>Lý do</th>
                                <th>Ghi chú</th>
                                <th class="text-center">Áp dụng</th>
                                <th class="text-right">Số tiền</th>
                            </tr>
                            <tr>
                                <td class="text-center"></td>
                                <td style="padding: 0;">
                                    <select class="qc_work_bonus_month col-sx-6 col-sm-6 col-md-6 col-lg-6" style="height: 34px; padding: 0;" data-href="{!! $hrefIndex !!}">
                                        {{--<option value="100" @if((int)$monthFilter == 100) selected="selected" @endif >
                                            Tất cả tháng
                                        </option>--}}
                                        @for($m =1;$m<= 12; $m++)
                                            <option value="{!! $m !!}" @if((int)$monthFilter == $m) selected="selected" @endif>
                                                Tháng {!! $m !!}
                                            </option>
                                        @endfor
                                    </select>
                                    <select class="qc_work_bonus_year col-sx-6 col-sm-6 col-md-6 col-lg-6" style="height: 34px; padding: 0;" data-href="{!! $hrefIndex !!}">
                                        {{--<option value="100" @if((int)$yearFilter == 100) selected="selected" @endif >
                                            Tất cả năm
                                        </option>--}}
                                        @for($y =2017;$y<= 2050; $y++)
                                            <option value="{!! $y !!}" @if($yearFilter == $y) selected="selected" @endif>
                                                {!! $y !!}
                                            </option>
                                        @endfor
                                    </select>
                                </td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-right"></td>
                            </tr>
                            @if($hFunction->checkCount($dataBonus))
                                <?php
                                $n_o = 0;
                                $totalMoney = 0;
                                ?>
                                @foreach($dataBonus as $bonus)
                                    <?php
                                    $bonusId = $bonus->bonusId();
                                    $orderAllocationId = $bonus->orderAllocationId();
                                    $orderConstructionId = $bonus->orderConstructionId();
                                    $orderPayId = $bonus->orderPayId();
                                    $cancelStatus = $bonus->checkCancelStatus();
                                    if ($cancelStatus) {
                                        $money = 0;
                                    } else {
                                        $money = $bonus->money();
                                    }
                                    $totalMoney = $totalMoney + $money;
                                    ?>
                                    <tr @if($n_o%2) class="info" @endif>
                                        <td class="text-center">
                                            {!! $n_o = (isset($n_o)) ? $n_o + 1 : 1 !!}
                                        </td>
                                        <td>
                                            {!! date('d/m/Y', strtotime($bonus->bonusDate())) !!}
                                        </td>
                                        <td>
                                            {!! $bonus->note() !!}
                                        </td>
                                        <td>
                                            @if(!$hFunction->checkEmpty($orderAllocationId))
                                                <em>Đơn hàng:</em>
                                                <b style="color: red;">{!! $bonus->orderAllocation->orders->name() !!}</b>
                                            @endif
                                            @if(!$hFunction->checkEmpty($orderConstructionId))
                                                <em>Đơn hàng:</em>
                                                <b style="color: red;">{!! $bonus->orderConstruction->name() !!}</b>
                                            @endif
                                            @if(!$hFunction->checkEmpty($orderPayId))
                                                <em>Đơn hàng:</em>
                                                <b style="color: red;">{!! $bonus->orderPay->order->name() !!}</b>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($cancelStatus)
                                                <em>Đã hủy</em>
                                            @else
                                                @if($bonus->checkEnableApply())
                                                    <em>Có hiệu lực</em>
                                                @else
                                                    <span>Tạm thời</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            <span style="color: blue;">
                                                {!! $hFunction->currencyFormat($money) !!}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="5" style="background-color: black;"></td>
                                    <td class="text-right">
                                        <b style="color: red;">{!! $hFunction->currencyFormat($totalMoney) !!}</b>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td class="text-center qc-padding-none" colspan="6">
                                        Không có thông tin thưởng
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
