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
                            <tr style="background-color: whitesmoke;">
                                <th class="text-center" style="width: 20px;">STT</th>
                                <th>Ngày</th>
                                <th>Lý do</th>
                                <th class="text-right">Số tiền</th>
                            </tr>
                            <tr>
                                <td class="text-center"></td>
                                <td style="padding: 0;">
                                    <select class="qc_work_bonus_month" style="height: 25px;"
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
                                    <select class="qc_work_bonus_year" style="height: 25px;"
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
                                <td class="text-right">
                                    <b style="color: red;">{!! $hFunction->currencyFormat($totalBonusMoney) !!}</b>
                                </td>
                            </tr>
                            @if($hFunction->checkCount($dataBonus))
                                <?php $n_o = 0; ?>
                                @foreach($dataBonus as $bonus)
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
                                        <td class="text-right">
                                            {!! $hFunction->dotNumber($bonus->money()) !!}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center qc-padding-none" colspan="4">
                                        Không có thông tin thưởng
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
