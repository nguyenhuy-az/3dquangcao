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
?>
@extends('work.index')
@section('qc_work_body')
    <div class="row qc_work_salary_before_pay_index">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-10">
            {{-- Menu --}}
            @include('work.salary.menu')
            {{-- Noi dung --}}
            @yield('qc_work_salary_before_pay_body')
        </div>
    </div>
@endsection
@section('qc_js_footer')
    <script src="{{ url('public/work/salary/before-pay/js/index.js')}}"></script>
@endsection