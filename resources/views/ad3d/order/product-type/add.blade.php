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
@extends('ad3d.order.product-type.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
            <h3>THÊM MỚI</h3>
        </div>
        <div class="qc-padding-top-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <form class="frmAdd" name="frmAdd" role="form" method="post" enctype="multipart/form-data"
                  action="{!! route('qc.ad3d.order.product_type.add.post') !!}">
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
                            <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6"
                                 @if($mobileStatus) style="padding: 0 0;" @endif>
                                <div class="form-group form-group-sm">
                                    <label>
                                        Tên loại sản phẩm:
                                        <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    </label>
                                    <input type="text" name="txtName" class="form-control"
                                           placeholder="Nhập loại sản phẩm"
                                           value="">
                                </div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-3 col-lg-3"
                                 @if($mobileStatus) style="padding: 0 0;" @endif>
                                <div class="form-group form-group-sm">
                                    <label>
                                        Mã loại sản phẩm:
                                        <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    </label>
                                    <input type="text" name="txtTypeCode" class="form-control"
                                           placeholder="Nhập mã loại sản phẩm" value="">
                                </div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-3 col-lg-3"
                                 @if($mobileStatus) style="padding: 0 0;" @endif>
                                <div class="form-group form-group-sm">
                                    <label>
                                        Đơn vị tính:
                                        <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    </label>
                                    <input type="text" name="txtUnit" class="form-control"
                                           placeholder="Nhập đơn vị tính: cái, m2 ..." value="">
                                </div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
                                 @if($mobileStatus) style="padding: 0 0;" @endif>
                                <div class="form-group form-group-sm">
                                    <label>
                                        Mô tả sản phẩm:
                                    </label>
                                    <textarea name="txtDescription" rows="3" class="form-control"
                                              placeholder="Mô tả sản phẩm"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label>Ảnh mẫu:</label>
                                <input type="file" class="txtImage_1" name="txtImage_1">
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label>Ảnh mẫu:</label>
                                <input class="txtImage_2" type="file" name="txtImage_2">
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label>Ảnh mẫu:</label>
                                <input type="file" class="txtImage_3" name="txtImage_3">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="padding: 10px;">
                        <div class="form-group form-group-sm">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <button type="button" class="qc_save btn btn-sm btn-primary">Thêm</button>
                            <button type="reset" class="qc_reset btn btn-sm btn-default">Nhập lại</button>
                            <a href="{!! route('qc.ad3d.order.product-type.get') !!}">
                                <button type="button" class="btn btn-sm btn-default">Đóng</button>
                            </a>
                        </div>

                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection
