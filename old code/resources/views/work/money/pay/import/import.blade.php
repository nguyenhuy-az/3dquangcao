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

$currentMonth = $hFunction->currentMonth();
If (empty($loginDay)) {
    $loginDate = date('Y-m', strtotime("$loginYear-$loginMonth"));
} else {
    $loginDate = date('Y-m-d', strtotime("$loginYear-$loginMonth-$loginDay"));
}

$dataImport = $dataStaffLogin->importInfoOfStaff($loginStaffId, $loginPayStatus, $loginDate);
?>
@extends('work.index')
@section('titlePage')
    Thông tin chi
@endsection
@section('qc_work_body')
    <div class="row">
        <div class="qc_work_money_receive_wrap qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            @include('work.money.money-menu')

            {{-- chi tiêt --}}
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <label class="qc-color-red">DANH SÁCH CHI</label>
                    </div>
                    <div class="text-right qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <select class="qc_work_money_pay_import_login_day" style="height: 25px;"
                                data-href="{!! route('qc.work.money.pay.import.get') !!}">
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
                        <select class="qc_work_money_pay_import_login_month" style="height: 25px;"
                                data-href="{!! route('qc.work.money.pay.import.get') !!}">
                            @for($i = 1; $i <=12; $i++)
                                <option @if($loginMonth == $i) selected="selected" @endif>
                                    {!! $i !!}
                                </option>
                            @endfor
                        </select>
                        <span>/</span>
                        <select class="qc_work_money_pay_import_login_year" style="height: 25px;"
                                data-href="{!! route('qc.work.money.pay.import.get') !!}">
                            @for($i = 2017; $i <=2050; $i++)
                                <option @if($loginYear == $i) selected="selected" @endif>
                                    {!! $i !!}
                                </option>
                            @endfor
                        </select>
                        <select class="qc_work_money_pay_import_status" style="height: 25px;"
                                data-href="{!! route('qc.work.money.pay.import.get') !!}">
                            <option value="3" @if($loginPayStatus == 3) selected="selected" @endif>
                                Tất cả
                            </option>
                            <option value="0" @if($loginPayStatus == 0) selected="selected" @endif>
                                Chưa thanh toán
                            </option>
                            <option value="1" @if($loginPayStatus == 1) selected="selected" @endif>
                                Đã thanh toán
                            </option>
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
                                    <th>Thanh toán</th>
                                    <th></th>
                                    <th class="text-right">Số tiền</th>
                                    <th class="text-right">Đã thanh toán</th>
                                    <th class="text-right">Chưa thanh toán</th>
                                </tr>
                                @if(count($dataImport) > 0)
                                    <?php
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
                                        <tr>
                                            <td class="text-center">
                                                {!! $n_o = isset($n_o)?$n_o+ 1:1 !!}
                                            </td>
                                            <td>
                                                {!! date('d/m/Y',strtotime($importDate))  !!}
                                            </td>
                                            <td>
                                                @if($import->checkPay())
                                                    @if($import->checkPayConfirmOfImport($importId))
                                                        <em class="qc-color-grey">Đã Nhận tiền</em>
                                                    @else
                                                        <a class="qc_work_import_confirm_pay_act qc-link-green"
                                                           data-href="{!! route('qc.work.import.confirm_pay.get') !!}">
                                                            Xác nhận thanh toán
                                                        </a>
                                                    @endif
                                                @else
                                                    <em>Chưa thanh toán</em>
                                                @endif

                                            </td>
                                            <td>
                                                <a href="{!! route('qc.work.import.view.get',$importId) !!}">
                                                    Chi tiết
                                                </a>
                                            </td>
                                            <td class="text-right">
                                                {!! $hFunction->currencyFormat($totalMoneyOfImport) !!}
                                            </td>
                                            <td class="text-right">
                                                {!! $hFunction->currencyFormat($moneyPaid) !!}
                                            </td>
                                            <td class="text-right">
                                                {!! $hFunction->currencyFormat($moneyUnPaid) !!}
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td class="text-right qc-color-red"
                                            style="background-color: whitesmoke;" colspan="4">
                                        </td>
                                        <td class="text-right qc-color-red">
                                            {!! $hFunction->currencyFormat($sumMoney)  !!}
                                        </td>
                                        <td class="text-right qc-color-red">
                                            {!! $hFunction->currencyFormat($sumPaid)  !!}
                                        </td>
                                        <td class="text-right qc-color-red">
                                            {!! $hFunction->currencyFormat($sumUnPaid)  !!}
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td class="text-center qc-padding-top-10 qc-padding-bot-10" colspan="7">
                                            <em class="qc-color-red">Không có thông tin mua</em>
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
