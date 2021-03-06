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
@section('titlePage')
    Thưởng
@endsection
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
                                <th style="width: 120px;">NGÀY THÁNG</th>
                                <th>SỐ TIỀN - LÝ DO</th>
                            </tr>
                            <tr>
                                <td style="padding: 0;">
                                    <select class="qc_work_bonus_month col-sx-6 col-sm-6 col-md-6 col-lg-6"
                                            style="height: 34px; padding: 0;" data-href="{!! $hrefIndex !!}">
                                        {{--<option value="100" @if((int)$monthFilter == 100) selected="selected" @endif >
                                            Tất cả tháng
                                        </option>--}}
                                        @for($m =1;$m<= 12; $m++)
                                            <option value="{!! $m !!}"
                                                    @if((int)$monthFilter == $m) selected="selected" @endif>
                                                {!! $m !!}
                                            </option>
                                        @endfor
                                    </select>
                                    <select class="qc_work_bonus_year col-sx-6 col-sm-6 col-md-6 col-lg-6"
                                            style="height: 34px; padding: 0;" data-href="{!! $hrefIndex !!}">
                                        {{--<option value="100" @if((int)$yearFilter == 100) selected="selected" @endif >
                                            Tất cả năm
                                        </option>--}}
                                        @for($y =2017;$y<= 2050; $y++)
                                            <option value="{!! $y !!}"
                                                    @if($yearFilter == $y) selected="selected" @endif>
                                                {!! $y !!}
                                            </option>
                                        @endfor
                                    </select>
                                </td>
                                <td class="text-center"></td>
                            </tr>
                            @if($hFunction->checkCount($dataBonus))
                                <?php
                                $n_o = 0;
                                $totalMoney = 0;
                                ?>
                                @foreach($dataBonus as $bonus)
                                    <?php
                                    $bonusId = $bonus->bonusId();
                                    # thi cong - quan ly thi cong
                                    $orderAllocationId = $bonus->orderAllocationId();
                                    # kinh doanh quan ly don hang
                                    $orderConstructionId = $bonus->orderConstructionId();
                                    # thi cong san pham
                                    $workAllocationId = $bonus->workAllocationId();
                                    # thanh toan
                                    $orderPayId = $bonus->orderPayId();
                                    $cancelNote = $bonus->cancelNote();
                                    $cancelImage = $bonus->cancelImage();
                                    $cancelStatus = $bonus->checkCancelStatus();
                                    $money = $bonus->money();
                                    if (!$cancelStatus) {
                                        $totalMoney = $totalMoney + $money;
                                    }
                                    $n_o = $n_o + 1;
                                    ?>
                                    <tr @if($n_o%2 == 0) class="info" @endif>
                                        <td>
                                            <b style="color: blue;">{!! date('d/m/Y', strtotime($bonus->bonusDate())) !!} </b>
                                            <br/>
                                            @if($cancelStatus)
                                                <i class="glyphicon glyphicon-ok qc-font-size-12" style="color: green;" ></i>
                                                <em style="color: grey;">Đã hủy</em>
                                                @if(!$hFunction->checkEmpty($cancelNote))
                                                    <br/>
                                                    <em style="color: grey;">- {!! $cancelNote !!}</em>
                                                @endif
                                                @if(!$hFunction->checkEmpty($cancelImage))
                                                    <br/>
                                                    <img alt="huy_thuong" style="border: 1px solid grey; width: 70px;"
                                                         src="{!! $bonus->pathSmallImage($cancelImage) !!}">
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <b style="@if(!$cancelStatus) color: red; @endif">
                                                {!! $hFunction->currencyFormat($money) !!}
                                            </b>
                                            <br/>
                                            <em style="color: grey;">- {!! $bonus->note() !!}</em>
                                            @if(!$hFunction->checkEmpty($workAllocationId))
                                                <br/>
                                                <em style="color: grey;">- SP:</em>
                                                <b style="color: blue;">{!! $bonus->workAllocation->product->productType->name() !!}</b>
                                                <br/>
                                                <em style="color: grey;">- ĐH:</em>
                                                <b style="color: deeppink;">{!! $bonus->workAllocation->product->order->name() !!}</b>
                                            @endif
                                            @if(!$hFunction->checkEmpty($orderAllocationId))
                                                <br/>
                                                <em style="color: grey;">- ĐH:</em>
                                                <b style="color: blue;">{!! $bonus->orderAllocation->orders->name() !!}</b>
                                            @endif
                                            @if(!$hFunction->checkEmpty($orderConstructionId))
                                                <br/>
                                                <em style="color: grey;">- ĐH:</em>
                                                <b style="color: red;">{!! $bonus->orderConstruction->name() !!}</b>
                                            @endif
                                            @if(!$hFunction->checkEmpty($orderPayId))
                                                <br/>
                                                <em style="color: grey;">- ĐH:</em>
                                                <b style="color: blue;">{!! $bonus->orderPay->order->name() !!}</b>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                <tr style="background-color: black;">
                                    <td></td>
                                    <td>
                                        <b class="qc-font-size-14" style="color: yellow;">
                                            {!! $hFunction->currencyFormat($totalMoney) !!}
                                        </b>
                                        <b style="color: white;">(Tổng)</b>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td class="text-center qc-padding-none" colspan="3">
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
