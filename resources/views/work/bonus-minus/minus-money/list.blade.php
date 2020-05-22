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
                                    <select class="qc_work_minus_money_month" style="height: 25px;"
                                            data-href="{!! $hrefIndex !!}">
                                        {{--<option value="100" @if((int)$monthFilter == 100) selected="selected" @endif >
                                            Tất cả tháng
                                        </option>--}}
                                        @for($i =1;$i<= 12; $i++)
                                            <option value="{!! $i !!}"
                                                    @if((int)$monthFilter == $i) selected="selected" @endif>
                                                Tháng {!! $i !!}
                                            </option>
                                        @endfor
                                    </select>
                                    <span>/</span>
                                    <select class="qc_work_minus_money_year" style="height: 25px;"
                                            data-href="{!! $hrefIndex !!}">
                                        {{--<option value="100" @if((int)$yearFilter == 100) selected="selected" @endif >
                                            Tất cả năm
                                        </option>--}}
                                        @for($i =2017;$i<= 2050; $i++)
                                            <option value="{!! $i !!}"
                                                    @if($yearFilter == $i) selected="selected" @endif>{!! $i !!}</option>
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
                                            {!! date('d/m/Y', strtotime($minusMoney->dateMinus())) !!}
                                        </td>
                                        <td>
                                            {!! $minusMoney->punishContent->name() !!}
                                        </td>
                                        <td>
                                            @if(!$hFunction->checkEmpty($orderAllocationId))
                                                <em>Đơn hàng:</em>
                                                <b>{!! $minusMoney->orderAllocation->orders->name() !!}</b>
                                                <br/>
                                                <em>Ghi chú báo cáo:</em>
                                                <b>{!! $minusMoney->orderAllocation->noted() !!}</b>
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
                                            {!! $hFunction->currencyFormat($money) !!}
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
