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
@extends('ad3d.components.container.container-6')
@section('qc_ad3d_container_content')
    <div class="qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
                <h3>CẬP NHẬT GIÁ</h3>
            </div>
            <div class="qc-padding-top-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form class="frmEdit" name="frmEdit" role="form" method="post" action="{!! route('qc.ad3d.order.product_type_price.edit.post', $dataProductTypePrice->priceId()) !!}">
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="row">
                            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="frm_notify form-group qc-color-red"></div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group form-group-sm">
                                    <label>
                                        Loại sản phẩm:
                                        <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    </label>
                                    <input type="text" name="txtName" class="form-control" disabled="disabled"
                                           value="{!! $dataProductTypePrice->productType->name() !!}">
                                </div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group form-group-sm">
                                    <label>
                                        Giá cũ / {!! $dataProductTypePrice->productType->unit() !!}
                                    </label>
                                    <input type="text" name="txtOldPrice" class="form-control" disabled="disabled"
                                           value="{!! $hFunction->currencyFormat($dataProductTypePrice->price()) !!}">
                                </div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group form-group-sm">
                                    <label>
                                        Giá mới:
                                        <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    </label>
                                    <input type="text" name="txtNewPrice" class="form-control" onkeyup="qc_main.showFormatCurrency(this);"
                                           placeholder="Nhập giá mới" value="0">
                                </div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group form-group-sm">
                                    <label>
                                        Chi chú:
                                    </label>
                                    <input type="text" name="txtNote" class="form-control"  value="{!! $dataProductTypePrice->note() !!}">
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
