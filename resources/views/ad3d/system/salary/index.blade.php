<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/29/2017
 * Time: 10:50 AM
 */
?>
@extends('ad3d.system.index')
@section('titlePage')
    Lương cơ bản
@endsection
@section('qc_ad3d_system_body')
    <div class="qc_ad3d_staff_salary col-xs-12 col-md-12 col-md-12 col-lg-12">
        @yield('qc_ad3d_staff_salary')
    </div>
@endsection

@section('qc_js_footer')
    <script src="{{ url('public/ad3d/system/salary/js/salary.js')}}"></script>
@endsection

