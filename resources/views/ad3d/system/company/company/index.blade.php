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
    Công ty
@endsection
@section('qc_ad3d_system_body')
    <div class="qc_ad3d_index_content col-xs-12 col-md-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-left: 0;">
                @include('ad3d.system.company.menu')
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                @yield('qc_ad3d_index_content')
            </div>
        </div>

    </div>
@endsection

@section('qc_js_footer')
    <script src="{{ url('public/ad3d/system/company/js/company.js')}}"></script>
@endsection

