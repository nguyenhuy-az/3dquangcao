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
$currentDateCheck = $hFunction->carbonNow();
$dataProduct = $dataWorkAllocation->product;
?>
@extends('ad3d.components.container.container-6')
@section('qc_ad3d_container_content')
    <div class="qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
                <h3 style="color: red;">PHẠT BỒI THƯỜNG VẬT TƯ</h3>
            </div>
            <div class="qc-padding-top-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form id="qcFrmMinusMoneyForSupplies" class="form-horizontal" name="qcFrmMinusMoneyForSupplies"
                      role="form"
                      method="post" enctype="multipart/form-data"
                      action="{!! route('qc.ad3d.work.work_allocation.minus_money.add.post') !!}">
                    <div class="row">
                        <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="font-weight: bold;">
                            <div class="frm_notify qc-font-size-16 form-group form-group-sm qc-color-red"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label style="color: red; text-decoration: underline;">Sản phẩm:</label>
                            {!! $dataProduct->productType->name() !!}
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label style="color: red; text-decoration: underline;">Đơn hàng:</label>
                            {!! $dataProduct->order->name() !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label>Số tiền:</label><i class="glyphicon glyphicon-star" style="color: red;"></i>
                            <input type="text" class="form-control" name="txtMoney"
                                   onkeyup="qc_main.showFormatCurrency(this);" value="" title="Nhập số tiền phạt">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label>Lý do:</label>
                            <i class="glyphicon glyphicon-star" style="color: red;"></i>
                            <input class="txtNote form-control" type="text" name="txtNote"
                                   value="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="text-center form-group form-group-sm">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <input type="hidden" name="txtAllocationId"
                                       value="{!! $dataWorkAllocation->allocationId() !!}">
                                <button type="button" class="qc_save btn btn-sm btn-primary">XÁC NHẬN PHẠT</button>
                                <button type="reset" class="btn btn-sm btn-default">NHẬP LẠI</button>
                                <button type="button" class="qc_ad3d_container_close btn btn-sm btn-default">ĐÓNG</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
