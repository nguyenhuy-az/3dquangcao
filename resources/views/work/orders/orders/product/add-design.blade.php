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
$productId = $dataProduct->productId();
$productName = $dataProduct->productType->name();
?>
@extends('components.container.container-6')
@section('qc_container_content')
    <div class="qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
                @if($modelProductDesign->checkIsDesignProduct($designType))
                    <h3 style="color: red;">THÊM THIẾT KẾ SẢN PHẨM</h3>
                @endif
                @if($modelProductDesign->checkIsDesignConstruction($designType))
                    <h3 style="color: red;">THÊM THIẾT KẾ THI CÔNG</h3>
                @endif
            </div>
            <div class="qc-padding-top-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form class="qc_frm_product_add_design form" name="qc_frm_product_add_design" role="form"
                      method="post" enctype="multipart/form-data"
                      action="{!! route('qc.work.orders.product.design.add.post',$productId) !!}">
                    <div class="row">
                        <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="font-weight: bold;">
                            <div class="frm_notify qc-font-size-16 form-group form-group-sm qc-color-red"></div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm">
                                <label>Sản phẩm:</label>
                                <input type="text" class="form-control" disabled="disabled" name="txtProductType"
                                       value="{!! $productName  !!}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label>Ảnh thiết kế:</label>
                                <input type="file" class="form-control txtDesignImage" name="txtDesignImage">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group">
                                    <b style="color: blue;">ẢNH THIẾT KẾ SẼ KHÔNG ĐƯỢC THAY ĐỔI SAU KHI THÊM</b>
                                </div>
                            </div>
                            <div class="text-center form-group">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <input type="hidden" name="txtDesignType" value="{!! $designType !!}">
                                <button type="button" class="qc_save btn btn-sm btn-primary">THÊM</button>
                                <button type="button" class="qc_container_close btn btn-sm btn-default">ĐÓNG</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
