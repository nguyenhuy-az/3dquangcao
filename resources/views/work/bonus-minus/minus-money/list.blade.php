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
$hrefIndex = route('qc.work.minus_money.get');
?>
@extends('work.bonus-minus.minus-money.index')
@section('qc_work_minus_money_body')
    <div class="row">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            {{-- chi tiêt --}}
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr style="background-color: black;color: yellow;">
                                <th class="text-center" style="width: 20px;">STT</th>
                                <th>Ngày</th>
                                <th>Nguyên nhân</th>
                                <th>Ghi chú</th>
                                <th class="text-center">Áp dụng</th>
                                <th class="text-right">Số tiền</th>
                            </tr>
                            <tr>
                                <td class="text-center"></td>
                                <td style="padding: 0;">
                                    <select class="qc_work_minus_money_month" style="height: 25px;" data-href="{!! $hrefIndex !!}">
                                        {{--<option value="100" @if((int)$monthFilter == 100) selected="selected" @endif >
                                            Tất cả tháng
                                        </option>--}}
                                        @for($m =1;$m<= 12; $m++)
                                            <option value="{!! $m !!}"
                                                    @if((int)$monthFilter == $m) selected="selected" @endif>
                                                Tháng {!! $m !!}
                                            </option>
                                        @endfor
                                    </select>
                                    <span>/</span>
                                    <select class="qc_work_minus_money_year" style="height: 25px;"
                                            data-href="{!! $hrefIndex !!}">
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
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-right">
                                </td>
                            </tr>
                            @if($hFunction->checkCount($dataMinusMoney))
                                <?php
                                $n_o = 0;
                                $totalMoney = 0;
                                ?>
                                @foreach($dataMinusMoney as $minusMoney)
                                    <?php
                                    $orderAllocationId = $minusMoney->orderAllocationId();
                                    $orderConstructionId = $minusMoney->orderConstructionId();
                                    $reason = $minusMoney->reason();
                                    $cancelStatus = $minusMoney->checkCancelStatus();
                                    if ($cancelStatus) {
                                        $money = 0;
                                    } else {
                                        $money = $minusMoney->money();
                                    }
                                    $totalMoney = $totalMoney + $money;
                                    ?>
                                    <tr @if($n_o%2) class="info" @endif>
                                        <td class="text-center">
                                            {!! $n_o = (isset($n_o)) ? $n_o + 1 : 1 !!}
                                        </td>
                                        <td>
                                            {!! date('d-m-Y', strtotime($minusMoney->dateMinus())) !!}
                                        </td>
                                        <td>
                                            {!! $minusMoney->punishContent->name() !!}
                                        </td>
                                        <td>
                                            <b>{!! $minusMoney->reason() !!}</b>
                                            @if(!$hFunction->checkEmpty($orderAllocationId))
                                                <br/>
                                                <em>Đơn hàng:</em>
                                                <a class="qc-link-red" href="{!! route('qc.work.work_allocation.construction.product.get',$orderAllocationId) !!}">
                                                    {!! $minusMoney->orderAllocation->orders->name() !!}
                                                </a>
                                            @endif
                                            @if(!$hFunction->checkEmpty($orderConstructionId))
                                                <br/>
                                                <em>Đơn hàng:</em>
                                                <a class="qc-link-red" href="{!! route('qc.work.work_allocation.manage.order.construction.get',$orderConstructionId) !!}">
                                                    {!! $minusMoney->orderConstruction->name() !!}
                                                </a>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($cancelStatus)
                                                <em style="color: grey;">Đã hủy</em>
                                            @else
                                                @if($minusMoney->checkEnableApply())
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
                                    <td class="text-center" colspan="5" style="background-color: black;"></td>
                                    <td class="text-right">
                                        <b style="color: red;">{!! $hFunction->currencyFormat($totalMoney) !!}</b>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td class="text-center qc-padding-none" colspan="5">
                                        Không có thông tin phạt
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <a class="btn btn-sm btn-primary" onclick="qc_main.page_back();">
                        Về trang trước
                    </a>
                    <a class="btn btn-sm btn-default" href="{!! route('qc.work.home') !!}">
                        Về trang chủ
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection