<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 *
 * dataCompany
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
?>
@extends('components.container.container-6')
@section('qc_container_content')
    <div class="qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
                <h3>ẢNH THIẾT KẾ</h3>
            </div>
            <div class="qc-padding-top-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form class="qc_frm_product_add_design form-horizontal" name="qc_frm_product_add_design" role="form"
                      method="post" enctype="multipart/form-data"
                      action="{!! route('qc.work.orders.product.design.add.post',$dataProduct->productId()) !!}">
                    <div class="row">
                        <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="font-weight: bold;">
                            <div class="frm_notify qc-font-size-16 form-group form-group-sm qc-color-red"></div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm">
                                <label class="col-sm-3 control-label">Sản phẩm:</label>

                                <div class="col-sm-9">
                                    <span style="color: brown;">{!! $dataProduct->productType->name()  !!}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm">
                                <label class="col-sm-3 control-label">Ảnh thiết kế:</label>
                                <div class="col-sm-9">
                                    <input type="file" class="txtDesignImage" name="txtDesignImage">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="qc-color-red form-group form-group-sm">
                                    <b>ẢNH THIẾT KẾ SẼ KHÔNG ĐƯỢC THAY ĐỔI SAU KHI THÊM</b>
                                </div>
                            </div>
                            <div class="text-center form-group form-group-sm">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <button type="button" class="qc_save btn btn-sm btn-primary">Thêm</button>
                                <button type="button" class="qc_container_close btn btn-sm btn-default">Đóng</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
