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
?>
@extends('work.index')
@section('qc_work_body')
    <div class="row qc_work_pay_salary_before_pay_index">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            {{-- chi tiêt --}}
            <div class="row">
                <div class="qc-border-none col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <a class="btn btn-sm btn-primary" onclick="qc_main.page_back();">
                        Về trang trước
                    </a>
                </div>
            </div>
            {{-- Menu --}}
            @include('work.pay.pay-menu')
            {{-- Noi dung --}}
            @yield('qc_work_pay_salary_pay_body')
        </div>
    </div>
@endsection
@section('qc_js_footer')
    <script src="{{ url('public/work/pay/pay-salary/js/index.js')}}"></script>
@endsection
