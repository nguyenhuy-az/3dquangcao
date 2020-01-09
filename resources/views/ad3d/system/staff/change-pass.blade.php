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
@extends('ad3d.system.staff.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="text-left col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <h3>ĐỔI MẬT KHẦU</h3>
            </div>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <form class="frmChangePassword" name="frmChangePassword" role="form" method="post"
                  action="{!! route('qc.ad3d.system.staff.change-pass.post') !!}">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
                     @if($mobileStatus) style="padding: 0 0;" @endif>
                    <div class="frm_notify form-group qc-color-red"></div>
                </div>
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
                     @if($mobileStatus) style="padding: 0 0;" @endif>
                    @if (Session::has('notifyChangePass'))
                        <div class="form-group form-group-sm qc-margin-none qc-color-red">
                            {!! Session::get('notifyChangePass') !!}
                            <?php
                            Session::forget('notifyChangePass');
                            ?>
                        </div>
                    @endif
                </div>
                <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6 ">
                    <div class="form-group form-group-sm qc-margin-none">
                        <label>Mật khẩu cũ:</label>
                        <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                        <input type="password" class="form-control" name="txtOldPass" value="">
                    </div>
                    <div class="form-group form-group-sm qc-margin-none">
                        <label>Mật khẩu mới:</label>
                        <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                        <input type="password" class="form-control" name="txtNewPass" value="">
                    </div>
                    <div class="form-group form-group-sm qc-margin-none">
                        <label>Nhập lại mật khẩu:</label>
                        <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                        <input type="password" class="form-control" name="txtConfirmPass" value="">
                    </div>
                </div>
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="margin-top: 10px;">
                    <div class="form-group form-group-sm">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <button type="button" class="qc_save btn btn-sm btn-primary">Lưu</button>
                        <a href="{!! route('qc.ad3d.system.staff.get') !!}">
                            <button type="button" class="btn btn-sm btn-default">Đóng</button>
                        </a>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection
