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
$currentMonth = $hFunction->currentMonth();
if (empty($loginDay)) {
    $loginDate = date('Y-m', strtotime("$loginYear-$loginMonth"));
} else {
    $loginDate = date('Y-m-d', strtotime("$loginYear-$loginMonth-$loginDay"));
}
$dataPayActivityDetail = $dataStaff->payActivityDetailInfoOfStaff($loginStaffId, $confirmStatus, $loginDate);
?>
@extends('work.index')
@section('qc_work_body')
    <div class="row qc_work_pay_activity_wrap">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            {{-- Menu --}}
            @include('work.pay.pay-menu')

            {{-- chi tiêt --}}
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="text-right qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <select class="qc_work_import_login_day" style="height: 25px;"
                                data-href="{!! route('qc.work.pay.pay_activity.get') !!}">
                            <option value="0" @if($loginDay == null) selected="selected" @endif>
                                Trong tháng
                            </option>
                            @for($i = 1; $i <=31; $i++)
                                <option @if($loginDay == $i) selected="selected" @endif>
                                    {!! $i !!}
                                </option>
                            @endfor
                        </select>
                        <select class="qc_work_import_login_month" style="height: 25px;"
                                data-href="{!! route('qc.work.pay.pay_activity.get') !!}">
                            @for($i = 1; $i <=12; $i++)
                                <option @if($loginMonth == $i) selected="selected" @endif>
                                    {!! $i !!}
                                </option>
                            @endfor
                        </select>
                        <span>/</span>
                        <select class="qc_work_import_login_year" style="height: 25px;"
                                data-href="{!! route('qc.work.pay.pay_activity.get') !!}">
                            @for($i = 2017; $i <=2050; $i++)
                                <option @if($loginYear == $i) selected="selected" @endif>
                                    {!! $i !!}
                                </option>
                            @endfor
                        </select>
                        <a class="qc_work_before_pay_request_action qc-link-green"
                           href="{!! route('qc.work.pay.pay_activity.add.get') !!}">
                            <b style="font-size: 1.5em;">+ Thêm</b>
                        </a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="table-responsive qc-container-table">
                        <table class="table table-hover table-bordered">
                            <tr style="background-color: black;color: yellow;">
                                <th class="text-center" style="width: 20px;">STT</th>
                                <th>Ngày</th>
                                <th>Danh mục chi</th>
                                <th>Ghi chú</th>
                                <th class="text-right"></th>
                                <th>Ghi chú duyệt</th>
                                <th class="text-right">Số tiền</th>
                                <th class="text-right">Được duyệt</th>
                                <th class="text-right">Không được duyệt</th>
                            </tr>
                            <tr>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td></td>
                                <td class="text-center"></td>
                                <td class="text-right" style="padding: 0;">
                                    <select class="qc_work_import_login_status form-control"
                                            data-href="{!! route('qc.work.pay.pay_activity.get') !!}">
                                        <option value="3" @if($confirmStatus == 3) selected="selected" @endif>
                                            Tất cả
                                        </option>
                                        <option value="0" @if($confirmStatus == 0) selected="selected" @endif>
                                            Chưa duyệt
                                        </option>
                                        <option value="1" @if($confirmStatus == 1) selected="selected" @endif>
                                            Đã duyệt
                                        </option>
                                    </select>
                                </td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>

                            </tr>
                            @if($hFunction->checkCount($dataPayActivityDetail))
                                <?php
                                $n_o = 0;
                                $sumPay = 0;
                                $sumPayInvalid = 0;
                                $sumPayUnInvalid = 0
                                ?>
                                @foreach($dataPayActivityDetail as $payActivityDetail)
                                    <?php
                                    $payId = $payActivityDetail->payId();
                                    $money = $payActivityDetail->money();
                                    $payDate = $payActivityDetail->payDate();
                                    $payNote = $payActivityDetail->note();
                                    $sumPay = $sumPay + $money;
                                    ?>
                                    <tr class="@if($n_o%2) info @endif">
                                        <td class="text-center">
                                            {!! $n_o = $n_o+ 1 !!}
                                        </td>
                                        <td class="text-center">
                                            {!! date('d/m/Y',strtotime($payDate))  !!}
                                        </td>
                                        <td>
                                            {!! $payActivityDetail->payActivityList->name()  !!}
                                        </td>
                                        <td>
                                            @if(!empty($payNote))
                                                {!! $payNote !!}
                                            @else
                                                <em class="qc-color-grey">---</em>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if(!$payActivityDetail->checkConfirm())
                                                <em class="qc-color-grey">Chờ duyệt</em>
                                                <span>|</span>
                                                <a class="qc_delete qc-link-red"
                                                   data-href="{!! route('qc.work.pay.pay_activity.delete.get',$payId) !!}">
                                                    Hủy
                                                </a>
                                            @else
                                                <em class="qc-color-grey">Đã duyệt</em>
                                            @endif
                                        </td>
                                        <td>
                                            <em class="qc-color-grey">{!! $payActivityDetail->confirmNote()  !!}</em>
                                        </td>
                                        <td class="text-right">
                                            {!! $hFunction->currencyFormat($money)  !!}
                                        </td>

                                        <td class="text-right">
                                            @if($payActivityDetail->checkConfirm())
                                                @if($payActivityDetail->checkInvalid())
                                                    {!! $hFunction->currencyFormat($money)  !!}
                                                    <?php $sumPayInvalid = $sumPayInvalid + $money;  ?>
                                                @else
                                                    0
                                                @endif
                                            @else
                                                <em>0</em>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            @if($payActivityDetail->checkConfirm())
                                                @if(!$payActivityDetail->checkInvalid())
                                                    {!! $hFunction->currencyFormat($money)  !!}
                                                    <?php $sumPayUnInvalid = $sumPayUnInvalid + $money;  ?>
                                                @else
                                                    0
                                                @endif
                                            @else
                                                <em>0</em>
                                            @endif
                                        </td>

                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="text-right qc-color-red"
                                        style="background-color: whitesmoke;" colspan="6"></td>
                                    <td class="text-right qc-color-red">
                                        {!! $hFunction->currencyFormat($sumPay)  !!}
                                    </td>
                                    <td class="text-right qc-color-red">
                                        {!! $hFunction->currencyFormat($sumPayInvalid)  !!}
                                    </td>
                                    <td class="text-right qc-color-red">
                                        {!! $hFunction->currencyFormat($sumPayUnInvalid)  !!}
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td class="text-center" colspan="9">
                                        <em class="qc-color-red">Không có thông chi</em>
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
