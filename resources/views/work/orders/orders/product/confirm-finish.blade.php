<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 */
/*
 *dataCompany
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
                <h3 style="color: red;">XÁC NHẬN HOÀN THÀNH SẢN PHẨM </h3>
            </div>
        </div>
        <div class="row">
            <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form class="frmWorkOrderProductConfirm" role="form" method="post"
                      action="{!! route('qc.work.orders.product.confirm.post', $dataProduct->productId()) !!}">
                    <div class="row">
                        <div class="notifyConfirm qc-color-red text-center col-xs-12 col-sm-12 col-md-6 col-lg-6"></div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                             @if($mobileStatus) style="padding: 0 0;" @endif>
                            <div class="text-center form-group qc-padding-none">
                                <span>
                                    {!! $dataProduct->productType->name() !!}({!! $dataProduct->width() !!} x
                                    {!! $dataProduct->height() !!} x
                                    {!! ($dataProduct->depth()==null)?0:$dataProduct->depth() !!})mm
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group qc-padding-none">
                                <em >Ngày hoàn thành là ngày xác nhận</em> <br/>
                                <span class="qc-font-size-16 qc-color-red">SAU KHI BÁO HOÀN THÀNH SẼ ĐƯỢC THAY ĐỔI</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="text-center qc-padding-bot-20  qc-border-none col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <button type="button" class="qc_save btn btn-sm btn-primary">XÁC NHẬN</button>
                            <button type="button" class="qc_container_close btn btn-sm btn-default">Đóng</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
