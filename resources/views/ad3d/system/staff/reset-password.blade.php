<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 *
 * dataStaff
 */


$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
?>
@extends('ad3d.components.container.container-4')
@section('qc_ad3d_container_content')
    <div class="qc-padding-bot-30 col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <h3 style="color: red;">RESET LẠI MẬT KHẨU</h3>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <form class="qc_ad_frm_reset_pass" name="qc_ad_frm_reset_pass" role="form" method="post"
                      action="{!! route('qc.ad3d.system.staff.reset_pass.post') !!}">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm">
                                <img class="img-circle"
                                     style="background-color: white; width: 30px;height: 30px; border: 1px solid #d7d7d7;"
                                     src="{!! $dataStaff->pathAvatar($dataStaff->image()) !!}">
                                <b>{!! $dataStaff->fullName() !!}</b>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="frm_notify form-group form-group-sm qc-color-red"></div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm">
                                <label>Tài khoản</label>
                                <input type="text" class="form-control" name="txtAcount" disabled="disabled" value="{!! $dataStaff->Account() !!}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm">
                                <label>Mật khẩu mới <i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                <input type="text" class="form-control" name="txtNewPass"  disabled="disabled"
                                       value="{!! '3d'.$dataStaff->identityCard() !!}">
                            </div>
                        </div>
                    </div>
                    <div class="text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group form-group-sm">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <input type="hidden" name="txtStaff" value="{!! $dataStaff->staffId() !!}">
                            <button type="button" class="qc_save btn btn-sm btn-primary">CẬP NHẬT</button>
                            <button type="button" class="qc_ad3d_container_close btn btn-sm btn-default">ĐÓNG</button>
                        </div>

                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
