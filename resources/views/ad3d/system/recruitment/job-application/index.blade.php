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
    Hồ sơ tuyển dụng
@endsection
@section('qc_ad3d_system_body')
    {{--menu--}}
    @include('ad3d.system.recruitment.menu', compact('dataAccess'))

    <div class="qc_ad3d_index_content col-xs-12 col-md-12 col-md-12 col-lg-12">
        @yield('qc_ad3d_index_content')
    </div>
@endsection

@section('qc_js_footer')
    <script src="{{ url('public/ad3d/system/job-application/js/index.js')}}"></script>
@endsection

