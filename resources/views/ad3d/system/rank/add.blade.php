<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 */
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
?>
@extends('ad3d.system.rank.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-10 col-lg-8" style="border-bottom: 2px dashed brown;">
            <h3>THÊM MỚI</h3>
        </div>
        <div class="qc-padding-top-20 col-sx-12 col-sm-12 col-md-10 col-lg-8">
            <form class="frmAdd" name="frmAdd" role="form" method="post" action="{!! route('qc.ad3d.system.rank.add.post') !!}">
                <div class="row">
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="row">
                            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
                                 @if($mobileStatus) style="padding: 0 0;" @endif>
                                @if (Session::has('notifyAdd'))
                                    <div class="form-group form-group-sm text-center qc-color-red">
                                        {!! Session::get('notifyAdd') !!}
                                        <?php
                                        Session::forget('notifyAdd');
                                        ?>
                                    </div>
                                @endif
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
                                 @if($mobileStatus) style="padding: 0 0;" @endif>
                                <div class="form-group form-group-sm">
                                    <label>
                                        Tên cấp bậc
                                        <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    </label>
                                    <input type="text" name="txtName" class="form-control" placeholder="Nhập tên"
                                           value="">
                                </div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
                                 @if($mobileStatus) style="padding: 0 0;" @endif>
                                <div class="form-group form-group-sm">
                                    <label>
                                        Mô tả:
                                    </label>
                                    <input type="text" name="txtDescription" class="form-control"
                                           placeholder="Nhập mô tả" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group form-group-sm">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <button type="button" class="qc_save btn btn-sm btn-primary">Lưu</button>
                            <a href="{!! route('qc.ad3d.system.rank.get') !!}">
                                <button type="button" class="btn btn-sm btn-default">Đóng</button>
                            </a>
                        </div>

                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection
