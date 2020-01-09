<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 1:32 PM
 */

?>
@extends('ad3d.index')
@section('titlePage')
    Đăng nhập
@endsection
@section('qc_ad3d_body')
    <div class="row">
        <div class="qc-padding-top-30 col-xs-12 col-sm-12 col-md-6 col-lg-offset-6 col-lg-6 col-lg-offset-3">
            <form class="frmLogin" name="frmLogin" role="form" method="post"
                  action="{!! route('qc.ad3d.login.post') !!}">
                @if(Session::has('notifyLogin'))
                    <div class="form-group form-group-sm text-center qc-color-red">
                        {!! Session::get('notifyLogin') !!}
                        <?php
                        Session:: forget('notifyLogin');
                        ?>
                    </div>
                @endif
                <div class="form-group form-group-sm">
                    <label>Tài khoản</label>
                    <input type="text" class="form-control" name="txtAccount" placeholder="Nhập tài khoản">
                </div>
                <div class="form-group form-group-sm">
                    <label>Mật khẩu</label>
                    <input type="password" class="form-control" name="txtPass" placeholder="Nhập mật khẩu">
                </div>
                <div class="form-group form-group-sm text-center">
                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                    <button type="submit" class="btn btn-primary">Đăng nhập</button>
                </div>
            </form>
        </div>
    </div>
@endsection
