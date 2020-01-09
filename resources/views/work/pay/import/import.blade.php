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
$loginStaffId = $dataStaff->staffId();
$hrefIndex = route('qc.work.import.get');
$currentMonth = $hFunction->currentMonth();
If (empty($loginDay)) {
    $loginDate = date('Y-m', strtotime("$loginYear-$loginMonth"));
} else {
    $loginDate = date('Y-m-d', strtotime("$loginYear-$loginMonth-$loginDay"));
}
$dataImport = $dataStaff->importInfoOfStaff($loginStaffId, $loginPayStatus, $loginDate);
?>
@extends('work.index')
@section('qc_work_body')
    <div class="row qc_work_import_wrap">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            {{-- Menu --}}
            @include('work.pay.pay-menu')

            <div class="text-right col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <a class="qc_work_before_pay_request_action qc-link-green"
                   href="{!! route('qc.work.import.add.get') !!}">
                    <em>+ Thêm</em>
                </a>
            </div>
            {{-- chi tiêt --}}
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="text-right qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <select class="qc_work_import_login_day" style="height: 25px;" data-href="{!! $hrefIndex !!}">
                            <option value="0" @if($loginDay == null) selected="selected" @endif>
                                Trong tháng
                            </option>
                            @for($i = 1; $i <=31; $i++)
                                <option @if($loginDay == $i) selected="selected" @endif>
                                    {!! $i !!}
                                </option>
                            @endfor
                        </select>
                        <select class="qc_work_import_login_month" style="height: 25px;" data-href="{!! $hrefIndex !!}">
                            @for($i = 1; $i <=12; $i++)
                                <option @if($loginMonth == $i) selected="selected" @endif>
                                    {!! $i !!}
                                </option>
                            @endfor
                        </select>
                        <span>/</span>
                        <select class="qc_work_import_login_year" style="height: 25px;" data-href="{!! $hrefIndex !!}">
                            @for($i = 2017; $i <=2050; $i++)
                                <option @if($loginYear == $i) selected="selected" @endif>
                                    {!! $i !!}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="table-responsive qc-container-table">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: whitesmoke;">
                            <th></th>
                            <th>Ngày</th>
                            <th>Chi chú</th>
                            <th class="text-center">Thanh toán</th>
                            <th class="text-center"></th>
                            <th class="text-right">Số tiền</th>
                            <th class="text-right">Đã thanh toán</th>
                            <th class="text-right">Chưa thanh toán</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th class="text-center" style="padding: 0;">
                                <select class="qc_work_import_login_status form-control" data-href="{!! $hrefIndex !!}" style="border: 0;">
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
                            </th>
                            <th class="text-center"></th>
                            <th class="text-right"></th>
                            <th class="text-right"></th>
                            <th class="text-right"></th>
                        </tr>
                        @if(count($dataImport) > 0)
                            <?php
                            $n_o = 0;
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
                                <tr class="@if(!$import->checkExactlyStatus()) danger  @else @if($n_o%2 == 1) info @endif @endif">
                                    <td class="text-center">
                                        {!! $n_o = $n_o + 1 !!}
                                    </td>
                                    <td>
                                        {!! date('d/m/Y',strtotime($importDate))  !!}
                                    </td>
                                    <td>
                                        <em class="qc-color-grey">{!! $import->confirmNote() !!}</em>
                                    </td>
                                    <td class="text-center">
                                        @if($import->checkExactlyStatus())
                                            @if($import->checkPay())
                                                @if($import->checkPayConfirmOfImport($importId))
                                                    <em class="qc-color-grey">Đã Nhận tiền</em>
                                                @else
                                                    <a class="qc_work_import_confirm_pay_act qc-link-green"
                                                       data-href="{!! route('qc.work.import.confirm_pay.get',$importId) !!}">
                                                        Xác nhận thanh toán
                                                    </a>
                                                @endif
                                            @else
                                                <em>Chưa thanh toán</em>
                                            @endif
                                        @else
                                            <em class="qc-color-red">Không được chấp nhận</em>
                                        @endif

                                    </td>
                                    <td class="text-center">
                                        <a href="{!! route('qc.work.import.view.get',$importId) !!}">
                                            Chi tiết
                                        </a>
                                        @if(!$import->checkConfirm())
                                            <span>&nbsp || &nbsp</span>
                                            <a class="qc_work_import_delete qc-link"
                                               data-href="{!! route('qc.work.import.delete.get',$importId) !!}">
                                                Hủy
                                            </a>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        {!! $hFunction->currencyFormat($totalMoneyOfImport) !!}
                                    </td>
                                    <td class="text-right">
                                        {!! $hFunction->currencyFormat($moneyPaid)  !!}
                                    </td>
                                    <td class="text-right">
                                        {!! $hFunction->currencyFormat($moneyUnPaid)  !!}
                                    </td>
                                </tr>
                            @endforeach
                            <tr style="border-top: 2px solid brown;">
                                <td class="text-right qc-color-red"
                                    style="background-color: whitesmoke;" colspan="5"></td>
                                <td class="text-right qc-color-red">
                                    {!! number_format($sumMoney)  !!}
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
                                <td class="text-right" colspan="8">
                                    <em class="qc-color-red">Không có thông tin mua</em>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <a href="{!! route('qc.work.home') !!}">
                        <button type="button" class="qc_ad3d_container_close btn btn-sm btn-primary">
                            Đóng
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
