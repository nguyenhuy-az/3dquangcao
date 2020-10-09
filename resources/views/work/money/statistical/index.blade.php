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
?>
@extends('work.index')
@section('qc_work_body')
    <div class="qc_work_money_statistical_index row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            @include('work.money.money-menu')
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            {{-- Noi dung --}}
            @yield('qc_work_money_statistical_body')
        </div>
    </div>
@endsection
@section('qc_js_footer')
    <script src="{{ url('public/work/money/statistical/js/index.js')}}"></script>
@endsection