<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 5/15/2018
 * Time: 10:17 AM
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$staffLoginId = $modelStaff->loginStaffId();
$changeAccountNotify = (isset($changeAccountNotify)) ? $changeAccountNotify : null;
?>
@extends('work.staff.index')
@section('qc_work_staff_body')
    <div class="row">
        <div class="col-xs-12 col-sm-1 col-md-12 col-lg-12">
            <form class="frmWorkStaffChangeAccount" role="form" name="frmWorkStaffChangeAccount" method="post"
                  action="{!! route('qc.work.staff.account.update.post') !!}">
                <div class="row">
                    <div class="qc-padding-top-20 col-xs-12 col-sm-12 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3">
                        <div class="form-group qc-padding-none">
                            <h4 style="color: red">ĐỔI TÀI KHOẢN</h4>
                        </div>
                        @if(!$hFunction->checkEmpty($changeAccountNotify))
                            <div class="form-group qc-color-red qc-padding-none">
                                {!! $changeAccountNotify['content'] !!}
                            </div>
                            @if($changeAccountNotify['status'])
                                <div class="form-group">
                                    <a class="qc-link-green-bold" href="{!! route('qc.work.logout.get') !!}">
                                        NHẤP VÀO ĐÂY ĐỂ ĐĂNG NHẬP LẠI
                                    </a>
                                </div>
                            @else
                                <div class="form-group qc-padding-none">
                                    <label>TÊN TÀI KHOẢN HIỆN TẠI:</label>
                                    <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    <input type="text" class="form-control" name="txtOldAccount" value="">
                                </div>
                                <div class="form-group qc-padding-none">
                                    <label>TÊN TÀI KHOẢN MỚI :</label>
                                    <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    <input type="text" class="form-control" name="txtNewAccount" value="">
                                </div>
                                <div class="form-group qc-padding-none text-center">
                                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                    <button type="button" class="qc_save btn btn-primary">CẬP NHẬT</button>
                                    <a href="{!! route('qc.work.staff.index.get', $staffLoginId) !!}">
                                        <button type="button" class=" btn btn-default">ĐÓNG</button>
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="form-group qc-padding-none">
                                <label>TÊN TÀI KHOẢN HIỆN TẠI:</label>
                                <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                <input type="text" class="form-control" name="txtOldAccount" value="">
                            </div>
                            <div class="form-group qc-padding-none">
                                <label>TÊN TÀI KHOẢN MỚI :</label>
                                <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                <input type="text" class="form-control" name="txtNewAccount" value="">
                            </div>
                            <div class="form-group qc-padding-none text-center">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <button type="button" class="qc_save btn btn-primary">CẬP NHẬT</button>
                                <a href="{!! route('qc.work.staff.index.get', $staffLoginId) !!}">
                                    <button type="button" class=" btn btn-default">ĐÓNG</button>
                                </a>
                            </div>
                        @endif

                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
