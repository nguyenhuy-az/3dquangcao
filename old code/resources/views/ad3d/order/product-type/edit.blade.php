<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 *
 * dataCompany
 */
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
?>
@extends('ad3d.components.container.container-6')
@section('qc_ad3d_container_content')
    <div class="qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
                <h3>SỬA THÔNG TIN</h3>
            </div>
            <div class="qc-padding-top-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form class="frmEdit" name="frmEdit" role="form" method="post" action="{!! route('qc.ad3d.order.product_type.edit.post', $dataProductType->typeId()) !!}">
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="row">
                            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
                                 @if($mobileStatus) style="padding: 0 0;" @endif>
                                <div class="frm_notify form-group qc-color-red"></div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
                                 @if($mobileStatus) style="padding: 0 0;" @endif>
                                <div class="form-group">
                                    <label>
                                        Tên:
                                        <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    </label>
                                    <input type="text" name="txtName" class="form-control" placeholder="Nhập tên loại sản phẩm"
                                           value="{!! $dataProductType->name() !!}">
                                </div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
                                 @if($mobileStatus) style="padding: 0 0;" @endif>
                                <div class="form-group">
                                    <label>
                                        Mã loại sản phẩm:
                                        <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    </label>
                                    <input type="text" name="txtTypeCode" class="form-control"
                                           placeholder="Nhập mã loại sản phẩm" value="{!! $dataProductType->typeCode() !!}">
                                </div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
                                 @if($mobileStatus) style="padding: 0 0;" @endif>
                                <div class="form-group">
                                    <label>
                                        Đơn vị tính:
                                        <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    </label>
                                    <input type="text" name="txtUnit" class="form-control"
                                           placeholder="Nhập đơn vị tính" value="{!! $dataProductType->unit() !!}">
                                </div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
                                 @if($mobileStatus) style="padding: 0 0;" @endif>
                                <div class="form-group">
                                    <label>
                                        Mô tả sản phẩm:
                                    </label>
                                    <textarea name="txtDescription" class="form-control" placeholder="Mô tả sản phẩm">{!! $dataProductType->description() !!}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <button type="button" class="qc_save btn btn-primary">Lưu</button>
                        <button type="button" class="qc_ad3d_container_close btn btn-default">Đóng</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
