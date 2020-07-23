<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/29/2017
 * Time: 10:50 AM
 */
$dataLogin = $modelStaff->loginStaffInfo();

?>
@extends('ad3d.store.index')
@section('qc_ad3d_store_body')
    <div class="qc_ad3d_index_content col-xs-12 col-md-12 col-md-12 col-lg-12">
        @yield('qc_ad3d_index_content')
    </div>
@endsection

@section('qc_js_footer')
    <script src="{{ url('public/ad3d/store/tool/tool/js/index.js')}}"></script>
@endsection

