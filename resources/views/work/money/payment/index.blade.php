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
$indexHref = route('qc.work.money.payment.get');
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
                    <div class="col-xs-6 col-sm-8 col-md-8 col-lg-8" >
                        <select class="qc_work_money_payment_filter_object form-control" style="background-color: yellow;" data-href="{!! $indexHref !!}">
                            <option value="activityPay" @if($object == 'activityPay') selected="selected" @endif >
                                Chi hoạt động được duyệt
                            </option>
                            <option value="importPay" @if($object == 'importPay') selected="selected" @endif >
                                Thanh toán mua vật tư
                            </option>
                            <option value="salaryPay" @if($object == 'salaryPay') selected="selected" @endif>
                                Thanh toán lương
                            </option>
                            <option value="salaryBeforePay" @if($object == 'salaryBeforePay') selected="selected" @endif>
                                Thanh toán ứng lương
                            </option>
                            <option value="orderCancel" @if($object == 'orderCancel') selected="selected" @endif>
                                Hoàn tiền đơn hàng
                            </option>
                        </select>
                    </div>
                    <div class="col-xs-3 col-sm-2 col-md-2 col-lg-2" style="padding-right: 0; padding-left: 0;">
                        <select class="qc_work_money_payment_filter_month form-control" data-href="{!! $indexHref !!}">
                            @for($m = 1; $m <=12; $m++)
                                <option value="{!! $m !!}" @if($monthFilter == $m) selected="selected" @endif>
                                    Tháng {!! $m !!}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-xs-3 col-sm-2 col-md-2 col-lg-2" style="padding-right: 0; padding-left: 0;">
                        <select class="qc_work_money_payment_filter_year form-control" data-href="{!! $indexHref !!}">
                            @for($y = 2017; $y <=2050; $y++)
                                <option value="{!! $y !!}" @if($yearFilter == $y) selected="selected" @endif>
                                    Năm {!! $y !!}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="row">
                    @yield('qc_work_money_payment_content')
                </div>
            </div>
        </div>
    </div>
@endsection
@section('qc_js_footer')
    <script src="{{ url('public/work/money/payment/js/index.js')}}"></script>
@endsection
