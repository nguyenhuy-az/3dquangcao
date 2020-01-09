<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/29/2017
 * Time: 10:50 AM
 */
?>
@extends('ad3d.order.index')
@section('titlePage')
    Đơn hàng
@endsection
@section('qc_ad3d_order_body')
    <div class="qc_ad3d_order_order col-xs-12 col-md-12 col-md-12 col-lg-12">
        @yield('qc_ad3d_order_order')
    </div>
@endsection

@section('qc_js_footer')
    <script src="{{ url('public/ad3d/order/order/js/index.js')}}"></script>
@endsection

