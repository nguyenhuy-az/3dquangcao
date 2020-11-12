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
    <div class="row">
        <div class="col-xs-12 col-md-12 col-md-12 col-lg-12">
            @include('ad3d.work.work-allocation.menu',compact('subObject'))
        </div>
        <div class="qc_ad3d_index_content col-xs-12 col-md-12 col-md-12 col-lg-12">
            @yield('qc_ad3d_index_content')
        </div>
    </div>
@endsection

@section('qc_js_footer')
    <script src="{{ url('public/ad3d/work/work-allocation/js/index.js')}}"></script>
@endsection

