<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 5/15/2018
 * Time: 10:17 AM
 */
?>
@extends('master')
@section('titlePage')
    Đăng nhập
@endsection
@section('qc_js_header')
    {{--<script src="{{ url('public/work/js/work.js')}}"></script>--}}
@endsection
@section('qc_master_body')
    <div class="row">
        <div class="col-xs-12 col-sm-1 col-md-12 col-lg-12">
            <form class="frmWorRecruitmentRegister" role="form" name="frmWorRecruitmentRegister" method="post" action="#">
                <div class="row">
                    <div class="qc-padding-top-20 col-xs-12 col-sm-12 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3">
                        @if(Session::has('notifyContent'))
                            <div class="form-group form-group-sm text-center qc-color-red qc-padding-none">
                                {!! Session::get('notifyContent') !!}
                                <?php
                                Session:: forget('notifyContent');
                                ?>
                            </div>
                        @endif
                        <div class="form-group form-group-sm qc-padding-none">
                            <label style="color: blue; font-size: 1.5em;">ĐĂNG KÝ ỨNG TRUYỂN / TRA CỨU HỒ SƠ</label>
                            <input type="text" class="form-control" name="txtAccount" placeholder="Nhận số điện thoại" value="">
                        </div>
                        <div class="form-group form-group-sm qc-padding-none">
                            <span style="color: red; font-size: 1.5em;">Lưu ý xem</span>
                            <a class="qc-link-green-bold" style="font-size: 2em;" href="{!! route('qc.work.rules') !!}" > NỘI QUY</a>
                            <span style="color: red; font-size: 1.5em;">trước khi ứng tuyển</span>
                        </div>
                        <div class="form-group form-group-sm qc-padding-none text-center">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <a type="button" class="qc_save btn btn-sm btn-primary" style="width: 100%;" href="{!! route('qc.work.recruitment.register.add.get') !!}">
                                ĐĂNG KÝ
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
