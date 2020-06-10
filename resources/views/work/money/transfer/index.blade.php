<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 5/15/2018
 * Time: 9:43 AM
 */
?>
@extends('work.index')
@section('titlePage')
    Chuyển/Nhận tiền
@endsection
@section('qc_work_body')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="min-height: 1500px;">
            @yield('qc_work_money_transfer_body')
        </div>
    </div>
@endsection
@section('qc_js_footer')
    <script src="{{ url('public/work/money/transfer/js/index.js')}}"></script>
@endsection
