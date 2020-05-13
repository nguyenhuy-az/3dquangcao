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
    <div class="row qc_work_minus_money_index">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            {{-- Menu --}}
            @include('work.bonus-minus.menu')
        </div>
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            {{-- Noi dung --}}
            @yield('qc_work_minus_money_body')
        </div>
    </div>
@endsection
@section('qc_js_footer')
    <script src="{{ url('public/work/minus-money/js/index.js')}}"></script>
@endsection
