<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 */
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$hFunction = new Hfunction();
$productId = $dataProduct->productId();
?>
@extends('components.container.container-6')
@section('qc_container_content')
    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="padding: 20px 0px; border-bottom: 2px dashed brown;">
                <label class="qc-font-size-20">HỦY SẢN PHẨM</label><br/>
                <label class="qc-font-size-20 qc-color-red">{!! $dataProduct->productType->name()  !!}</label>
            </div>
        </div>
        <div class="row">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form id="frmWorkOrderOrderProductCancel" class="frmWorkOrderOrderProductCancel" role="form" method="post"
                      action="{!! route('qc.work.orders.orders.product_cancel.post',$productId) !!}">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="qc_notify form-group text-center qc-color-red"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm qc-padding-none">
                                <label>Lý do hủy: <i class="qc-color-red glyphicon glyphicon-star-empty"></i>: </label>
                                <input type="text" class="form-control" name="txtReason" value="">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm qc-padding-none">
                                <em style="text-decoration: underline">Lưu ý:</em><br/>
                                <span class="qc-color-red">SẢN PHẨM HỦY SẼ KHÔNG ĐƯƠC PHỤC HỒI - VÀ KHÔNG ĐƯỢC SỬA ĐỔI THÔNG TIN</span>
                            </div>
                        </div>
                        <div class="text-center qc-padding-bot-20  qc-border-none col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <button type="button" class="qc_save btn btn-sm btn-primary">Hủy sản phẩm </button>
                            <button type="button" class="qc_container_close btn btn-sm btn-default">Đóng</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
