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
if (count($dataWork) > 0) {
    $dataMinusMoney = $dataWork->infoMinusMoneyOfWork();
}
?>
@extends('work.index')
@section('qc_work_body')
    <div class="row">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed #C2C2C2;">
                <h3>PHẠT</h3>
            </div>

            <div class="row">
                <div class="text-right qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <select class="qc_work_minus_money_month" style="height: 25px;"
                            data-href="{!! route('qc.work.minus_money.get') !!}">
                        @for($i = 1; $i <=12; $i++)
                            <option @if($monthFilter == $i) selected="selected" @endif>
                                {!! $i !!}
                            </option>
                        @endfor
                    </select>
                    <span>/</span>
                    <select class="qc_work_minus_money_year" style="height: 25px;"
                            data-href="{!! route('qc.work.minus_money.get') !!}">
                        @for($i = 2017; $i <=2050; $i++)
                            <option @if($yearFilter == $i) selected="selected" @endif>
                                {!! $i !!}
                            </option>
                        @endfor
                    </select>
                </div>
            </div>
            @if(count($dataWork) > 0)
            {{-- chi tiêt --}}
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row qc-container-table">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr style="background-color: whitesmoke;">
                                <th class="text-center">STT</th>
                                <th>Ngày</th>
                                <th class="text-center">Lý do</th>
                                <th class="text-right">Số tiền</th>
                            </tr>
                            @if(count($dataMinusMoney) > 0)
                                @foreach($dataMinusMoney as $minusMoney)
                                    <tr>
                                        <td class="text-center">
                                            {!! $n_o = (isset($n_o)) ? $n_o + 1 : 1 !!}
                                        </td>
                                        <td>
                                            {!! date('d/m/Y', strtotime($minusMoney->dateMinus())) !!}
                                        </td>
                                        <td class="text-center">
                                            {!! $minusMoney->punishContent->name() !!}
                                        </td>
                                        <td class="text-right">
                                            {!! $hFunction->dotNumber($minusMoney->money()) !!}
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="qc-color-red">
                                    <td colspan="3"></td>
                                    <td class="text-right qc-color-red">
                                        {!! $hFunction->currencyFormat($dataWork->totalMoneyMinus()) !!}
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td class="text-center qc-padding-none" colspan="4">
                                        Không có thông tin phạt
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
            @else
                <div class="row">
                    <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        Tính năng đang bảo trì
                    </div>
                </div>
            @endif
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
