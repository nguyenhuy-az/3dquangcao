<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 5/15/2018
 * Time: 10:17 AM
 */
$loginNotify = (isset($loginNotify)) ? $loginNotify : null;
?>
@extends('master')
@section('titlePage')
    Đăng nhập
@endsection
@section('qc_js_header')
    <script src="{{ url('public/work/js/work.js')}}"></script>
@endsection
@section('qc_master_body')
    <div class="row">
        <div class="col-xs-12 col-sm-1 col-md-12 col-lg-12">
            <form class="frmWorkLogin" role="form" method="post" action="{!! route('qc.work.login.post') !!}">
                <div class="row">
                    <div class="qc-padding-top-20 col-xs-12 col-sm-12 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3">
                        @if(Session::has('notifyLogin'))
                            <div class="form-group form-group-sm text-center qc-color-red qc-padding-none">
                                {!! Session::get('notifyLogin') !!}
                                <?php
                                Session:: forget('notifyLogin');
                                ?>
                            </div>
                        @endif
                        <div class="form-group form-group-sm qc-padding-none">
                            <label>Tài khoản:</label>
                            <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                            <input type="text" class="form-control" name="txtAccount" value="">
                        </div>
                        <div class="form-group form-group-sm qc-padding-none">
                            <label>Mật khẩu:</label>
                            <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                            <input type="password" class="form-control" name="txtPass" value="">
                        </div>
                        <div class="form-group form-group-sm qc-padding-none text-center">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <button type="submit" class="qc_work_login btn btn-sm btn-primary">Đăng nhập</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
