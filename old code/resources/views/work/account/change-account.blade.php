<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 5/15/2018
 * Time: 10:17 AM
 */
$changeAccountNotify = (isset($changeAccountNotify)) ? $changeAccountNotify : null;
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
            <form class="frmWorkChangeAccount" role="form" name="frmWorkChangeAccount" method="post"
                  action="{!! route('qc.work.change-account.post') !!}">
                <div class="row">
                    <div class="qc-padding-top-20 col-xs-12 col-sm-12 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3">
                        <div class="form-group qc-padding-none">
                            <h4>ĐỔI TÀI KHOẢN</h4>
                        </div>
                        @if(!empty($changeAccountNotify))
                            <div class="form-group qc-color-red qc-padding-none">
                                {!! $changeAccountNotify['content'] !!}
                            </div>
                            @if($changeAccountNotify['status'])
                                <div class="form-group qc-color-red qc-padding-none">
                                    <a href="{!! route('qc.work.home') !!}">
                                        Về trang chính
                                    </a>
                                </div>
                            @else
                                <div class="form-group qc-padding-none">
                                    <label>Nhập mã hiện tại:</label>
                                    <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    <input type="text" class="form-control" name="txtOldAccount" value="">
                                </div>
                                <div class="form-group qc-padding-none">
                                    <label>Nhập mã truy cập mới :</label>
                                    <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    <input type="text" class="form-control" name="txtNewAccount" value="">
                                </div>
                                <div class="form-group qc-padding-none text-center">
                                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                    <button type="button" class="qc_work_change_account btn btn-primary">Thay đổi</button>
                                    <a href="{!! route('qc.work.home') !!}">
                                        <button type="button" class=" btn btn-default">Đóng</button>
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="form-group qc-padding-none">
                                <label>Nhập mã hiện tại:</label>
                                <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                <input type="text" class="form-control" name="txtOldAccount" value="">
                            </div>
                            <div class="form-group qc-padding-none">
                                <label>Nhập mã truy cập mới :</label>
                                <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                <input type="text" class="form-control" name="txtNewAccount" value="">
                            </div>
                            <div class="form-group qc-padding-none text-center">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <button type="button" class="qc_work_change_account btn btn-primary">Thay đổi</button>
                                <a href="{!! route('qc.work.home') !!}">
                                    <button type="button" class=" btn btn-default">Đóng</button>
                                </a>
                            </div>
                        @endif

                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
