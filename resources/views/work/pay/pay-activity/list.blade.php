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
$hrefIndex = route('qc.work.pay.pay_activity.get');
?>
@extends('work.pay.pay-activity.index')
@section('qc_work_pay_activity_body')
    <div class="row qc_work_pay_activity_wrap">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            {{-- chi tiêt --}}
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="text-right qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <a class="qc_work_before_pay_request_action qc-link-red"
                           href="{!! route('qc.work.pay.pay_activity.add.get') !!}">
                            <b style="font-size: 1.5em;">+ THÊM</b>
                        </a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr style="background-color: black;color: yellow;">
                                <th class="text-center" style="width: 20px;">STT</th>
                                <th style="width: 150px;">NGÀY</th>
                                <th>SỐ TIỀN - LÝ DO</th>
                                <th style="width: 400px !important;">GHI CHÚ</th>
                            </tr>
                            <tr>
                                <td class="text-center"></td>
                                <td style="padding: 0;">
                                    <select class="cbDayFilter col-sx-3 col-sm-3 col-md-3 col-lg-3"
                                            style="height: 34px; padding: 0;"
                                            data-href="{!! $hrefIndex !!}">
                                        <option value="0" @if($dayFilter == null) selected="selected" @endif>
                                            Tất cả
                                        </option>
                                        @for($d = 1; $d <=31; $d++)
                                            <option @if($dayFilter == $d) selected="selected" @endif>
                                                {!! $d !!}
                                            </option>
                                        @endfor
                                    </select>
                                    <select class="cbMonthFilter col-sx-3 col-sm-3 col-md-3 col-lg-3"
                                            style="height: 34px; padding: 0;"
                                            data-href="{!! $hrefIndex !!}">
                                        @for($m = 1; $m <=12; $m++)
                                            <option @if($monthFilter == $m) selected="selected" @endif>
                                                {!! $m !!}
                                            </option>
                                        @endfor
                                    </select>
                                    <select class="cbYearFilter col-sx-6 col-sm-6 col-md-6 col-lg-6"
                                            style="height: 34px;padding: 0;"
                                            data-href="{!! $hrefIndex !!}">
                                        @for($y = 2017; $y <=2050; $y++)
                                            <option @if($yearFilter == $y) selected="selected" @endif>
                                                {!! $y !!}
                                            </option>
                                        @endfor
                                    </select>
                                </td>
                                <td class="text-right" style="padding: 0;">
                                    <select class="cbConfirmStatusFilter form-control"
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
                                    $payImage = $payActivityDetail->payImage();
                                    $confirmNote = $payActivityDetail->confirmNote();
                                    $sumPay = $sumPay + $money;
                                    ?>
                                    <tr class="@if($n_o%2) info @endif">
                                        <td class="text-center">
                                            {!! $n_o = $n_o+ 1 !!}
                                        </td>
                                        <td>
                                            <b style="color: blue;">{!! date('d/m/Y',strtotime($payDate))  !!}</b>
                                            <br/>
                                            @if(!$payActivityDetail->checkConfirm())
                                                <em class="qc-color-grey">Chờ duyệt</em>
                                                <span>|</span>
                                                <a class="qc_delete qc-font-size-14 qc-link-red"
                                                   data-href="{!! route('qc.work.pay.pay_activity.delete.get',$payId) !!}">
                                                    HỦY
                                                </a>
                                            @else
                                                <em class="qc-color-grey">- Đã duyệt</em>
                                                @if(!$hFunction->checkEmpty($confirmNote))
                                                    <br/>
                                                    <em class="qc-color-grey">- {!! $payActivityDetail->confirmNote()  !!}</em>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <b style="color: red;">{!! $hFunction->currencyFormat($money)  !!}</b>
                                            <br/>
                                            <em style="color: grey;">{!! $payActivityDetail->payActivityList->name()  !!}</em>
                                        </td>
                                        <td>
                                            @if(!empty($payNote))
                                                {!! $payNote !!}
                                            @else
                                                <em class="qc-color-grey">---</em>
                                            @endif
                                            @if(!empty($payImage))
                                                <br/>
                                                <img class="qc-link" onclick="qc_main.rotateImage(this);"
                                                     style="width: 150px;"
                                                     src="{!! $payActivityDetail->pathSmallImage($payImage) !!}">
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="text-right qc-color-red"
                                        style="background-color: whitesmoke;" colspan="2"></td>
                                    <td class="qc-color-red">
                                        <b>{!! $hFunction->currencyFormat($sumPay)  !!}</b>
                                    </td>
                                    <td></td>
                                </tr>
                            @else
                                <tr>
                                    <td class="text-center" colspan="4">
                                        <em class="qc-color-red">Không có thông chi</em>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            {{--<div class="row">
                <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <a class="btn btn-sm btn-primary" onclick="qc_main.page_back();">
                        Về trang trước
                    </a>
                    <a class="btn btn-sm btn-default" href="{!! route('qc.work.home') !!}">
                        Về trang chủ
                    </a>
                </div>
            </div>--}}
        </div>
    </div>
@endsection
