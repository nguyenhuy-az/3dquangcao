<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/29/2017
 * Time: 10:50 AM
 */
?>
@extends('ad3d.work.index')
@section('titlePage')
    Phân việc
@endsection
@section('qc_ad3d_work_body')
    <div class="qc_ad3d_index_content col-xs-12 col-md-12 col-md-12 col-lg-12">
        @yield('qc_ad3d_index_content')
    </div>
@endsection

@section('qc_js_footer')
    <script src="{{ url('public/ad3d/work/work-allocation-report/js/index.js')}}"></script>
@endsection

