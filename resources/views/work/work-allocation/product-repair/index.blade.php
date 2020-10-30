<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 5/15/2018
 * Time: 10:12 AM
 *
 * dataStaff
 */
?>
@extends('work.index')
@section('titlePage')
    SỬA CHỬA SẢN PHẨM
@endsection
@section('qc_work_body')
    <div id="qc_work_allocation_product_repair_wrap" class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="min-height: 1500px;">
            @yield('qc_work_allocation_body')
        </div>
    </div>
@endsection
@section('qc_js_footer')
    <script src="{{ url('public/work/allocation/product-repair/js/index.js')}}"></script>
@endsection
