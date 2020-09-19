<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 5/15/2018
 * Time: 10:17 AM
 */
$hFunction = new Hfunction();
$jobApplicationId = $dataJobApplication->jobApplicationId();
$companyId = $dataJobApplication->companyId();
?>
@extends('master')
@section('titlePage')
    Đăng ký
@endsection
@section('qc_js_header')
    {{--<script src="{{ url('public/work/js/work.js')}}"></script>--}}
@endsection
@section('qc_master_body')
    <div class="row">
        <div class="qc-padding-top-20 col-xs-12 col-sm-12 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3">
            <div class="text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <h3 style="color: red;">HỒ SƠ ĐÃ GỬI THÀNH CÔNG</h3>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding: 0;">
                <a class="btn btn-primary" style="width: 100%;" href="{!! route('qc.work.recruitment.info.get',$jobApplicationId) !!}">
                    Xem hồ sơ
                </a>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding: 0;">
                <a class="btn btn-default" style="width: 100%;" href="{!! route('qc.work.recruitment.login.get', $companyId) !!}">
                    Đóng
                </a>
            </div>
        </div>
    </div>
@endsection
