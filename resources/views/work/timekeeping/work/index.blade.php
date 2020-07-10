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

?>
@extends('work.index')
@section('qc_work_body')
    <div class="row">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            @include('work.timekeeping.menu')
            {{-- chi tiêt --}}
            @yield('qc_work_timekeeping_work_body')
        </div>
    </div>
@endsection
@section('qc_js_footer')
    {{--<script src="{{ url('public/work/timekeeping/timekeeping/js/index.js')}}"></script>--}}
@endsection
