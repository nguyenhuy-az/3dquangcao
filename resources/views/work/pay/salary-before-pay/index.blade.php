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

$currentMonth = $hFunction->currentMonth();
?>
@extends('work.index')
@section('qc_work_body')
    <div class="row qc_work_pay_salary_before_pay_index">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <a class="btn btn-sm btn-primary" onclick="qc_main.page_back();">
                Về trang trước
            </a>
        </div>
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            {{-- Menu --}}
            @include('work.pay.pay-menu')
            {{-- Noi dung --}}
            @yield('qc_work_pay_salary_before_pay_body')
        </div>
    </div>
@endsection
@section('qc_js_footer')
    <script src="{{ url('public/work/pay/salary-before-pay/js/index.js')}}"></script>
@endsection
