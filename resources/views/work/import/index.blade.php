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
?>
@extends('work.index')
@section('qc_work_body')
    <div class="row qc_work_import_index">
        <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6">
            <a class="btn btn-sm btn-primary" onclick="qc_main.page_back();">
                Về trang trước
            </a>
        </div>
        <div class="text-right qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <a class="qc_work_before_pay_request_action qc-link-green "
               href="{!! route('qc.work.import.add.get') !!}">
                <b style="font-size: 1.5em;">+ Thêm</b>
            </a>
        </div>
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            {{-- Noi dung --}}
            @yield('qc_work_import_body')
        </div>
    </div>
@endsection
@section('qc_js_footer')
    <script src="{{ url('public/work/import/js/index.js')}}"></script>
@endsection