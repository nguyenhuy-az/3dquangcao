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
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="padding: 20px 0 0 20px;">
                <label class="qc-font-size-20">SỬA SP: </label>
                <label class="qc-font-size-20 qc-color-red">{!! $dataProduct->productType->name() !!}</label>
            </div>
        </div>
        <div class="row">
            <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form class="frmWorkOrderProductInfoEdit" role="form" method="post"
                      action="{!! route('qc.work.orders.orders.product.info.edit.post', $dataProduct->productId()) !!}">
                    <div class="row">
                        <div class="notifyConfirm qc-color-red text-center col-xs-12 col-sm-12 col-md-6 col-lg-6"></div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group qc-padding-none">
                                <label>Chiều rộng <i class="qc-color-red glyphicon glyphicon-star-empty"></i>:</label>
                                <input type="text" class="form-control" name="txtWidth" placeholder="Nhập Chiều rộng"
                                       value="{!! $dataProduct->width() !!}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group qc-padding-none">
                                <label>Chiều cao <i class="qc-color-red glyphicon glyphicon-star-empty"></i>:</label>
                                <input type="text" class="form-control" name="txtHeight" placeholder="Nhập Chiều cao"
                                       value="{!! $dataProduct->height() !!}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group qc-padding-none">
                                <label>Số lượng <i class="qc-color-red glyphicon glyphicon-star-empty"></i>:</label>
                                <input type="number" class="form-control" name="txtAmount" placeholder="Nhập số lượng"
                                       value="{!! $dataProduct->amount() !!}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group qc-padding-none">
                                <label>Số tiền <i class="qc-color-red glyphicon glyphicon-star-empty"></i>:</label>
                                <input type="text" class="form-control" name="txtPrice" placeholder="Nhập giá sản phẩm"
                                       onkeyup="qc_main.showFormatCurrency(this);"
                                       value="{!! $hFunction->currencyFormat($dataProduct->price()) !!}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group qc-padding-none">
                                <label>Mô tả:</label>
                                <input type="text" class="form-control" name="txtDescription" placeholder="Chi chú"
                                       value="{!! $dataProduct->description()  !!}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="text-center qc-border-none col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <button type="button" class="qc_save btn btn-sm btn-primary">Xác nhận</button>
                            <button type="reset" class="btn btn-sm btn-default">Nhận lại</button>
                            <button type="button" class="qc_container_close btn btn-sm btn-default">Đóng</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
