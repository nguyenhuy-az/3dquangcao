<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 5/15/2018
 * Time: 10:17 AM
 */
$hFunction = new Hfunction();
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
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <form class="frmWorRecruitmentRegister" role="form" name="frmWorRecruitmentRegister" method="post"
                  action="{!! route('qc.work.recruitment.login.post',$companyId) !!}">
                <div class="row">
                    <div class="qc-padding-top-20 col-xs-12 col-sm-12 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3">
                        @if(!$hFunction->checkEmpty($companyId))
                            <div class="form-group form-group-sm qc-padding-none">
                                <label style="color: blue; font-size: 1.5em;">ĐĂNG KÝ ỨNG TRUYỂN / TRA CỨU HỒ SƠ</label>
                                <input type="text" class="form-control" name="txtPhoneNumber"
                                       placeholder="Nhận số điện thoại" value="">
                            </div>
                            @if(Session::has('notifyRecruitmentLogin'))
                                <div class="form-group form-group-sm">
                                    <span style="background-color: red; color: yellow; padding: 3px;">
                                        {!! Session::get('notifyRecruitmentLogin') !!}
                                    </span>
                                    <?php
                                    Session:: forget('notifyRecruitmentLogin');
                                    ?>
                                </div>
                            @endif
                            <div class="form-group form-group-sm qc-padding-none">
                                <span style="color: red; font-size: 1.5em;">Lưu ý xem</span>
                                <a class="qc-link-green-bold" style="font-size: 2em;"
                                   href="{!! route('qc.work.rules') !!}"> NỘI QUY</a>
                                <span style="color: red; font-size: 1.5em;">trước khi ứng tuyển</span>
                            </div>
                            <div class="form-group form-group-sm qc-padding-none text-center">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <button type="submit" class="btn btn-sm btn-primary" style="width: 100%;">
                                    ĐĂNG KÝ
                                </button>
                            </div>
                        @else
                            <div class="text-center form-group form-group-sm">
                                <h4 style="color:red;">BẠN PHẢI TRUY CẬP TỪ ĐƯỜNG LINK ĐƯỢC CUNG CẤP BỞI CTY
                                    3DQUANGCAO</h4>
                                <span style="color: blue;">HotLine:</span>
                                <em style="color: red; font-size: 2em;">0939.88.99.07</em>
                                <span style="color: blue;">- Mr.Huy</span>
                            </div>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
