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
@extends('ad3d.store.supplies.supplies.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-8 col-lg-8" style="border-bottom: 2px dashed brown;">
            <h3>THÊM MỚI</h3>
        </div>
        <div class="qc-padding-top-20 col-sx-12 col-sm-12 col-md-8 col-lg-8">
            <form class="frmAdd" name="frmAdd" role="form" method="post" action="{!! route('qc.ad3d.Store.supplies.supplies.add.post') !!}">
                <div class="row">
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="row">
                            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                @if (Session::has('notifyAdd'))
                                    <div class="form-group text-center qc-color-red">
                                        {!! Session::get('notifyAdd') !!}
                                        <?php
                                        Session::forget('notifyAdd');
                                        ?>
                                    </div>
                                @endif
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group" style="margin: 0">
                                    <label>
                                        Tên vật tư:
                                        <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    </label>
                                    <input type="text" name="txtName" class="form-control" style="height: 25px;" placeholder="Nhập loại vật tư"
                                           value="">
                                </div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group">
                                    <label>
                                        Đơn vị tính:
                                        <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    </label>
                                    <input type="text" name="txtUnit" class="form-control" style="height: 25px;"
                                           placeholder="Nhập đơn vị tính" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <button type="button" class="qc_save btn btn-sm btn-primary">Lưu</button>
                            <a href="{!! route('qc.ad3d.store.supplies.supplies.get') !!}">
                                <button type="button" class="btn btn-sm btn-default">Đóng</button>
                            </a>
                        </div>

                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection
