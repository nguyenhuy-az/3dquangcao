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
@extends('ad3d.components.container.container-6')
@section('qc_ad3d_container_content')
    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
                <h3>XÁC NHẬN </h3>
            </div>
        </div>
        <div class="row">
            <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form class="frmProductConfirm" role="form" method="post"
                      action="{!! route('qc.ad3d.order.product.confirm.post', $dataProduct->productId()) !!}">
                    <div class="row">
                        <div class="notifyConfirm qc-color-red text-center col-xs-12 col-sm-12 col-md-6 col-lg-6"></div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                             @if($mobileStatus) style="padding: 0 0;" @endif>
                            <div class="form-group qc-padding-none">
                                <label>Sản phẩm:</label>
                        <span>
                            {!! $dataProduct->productType->name() !!}({!! $dataProduct->width() !!} x
                            {!! $dataProduct->height() !!} x
                            {!! ($dataProduct->depth()==null)?0:$dataProduct->depth() !!})mm
                        </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group qc-padding-none">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                                 @if($mobileStatus) style="padding: 5px 0 5px 0;" @endif>
                                <label>Ngày hoàn thành: <i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <select name="cbDay" style="height: 25px;">
                                    <option value="">Ngày</option>
                                    @for($i = 1;$i<= 31; $i++)
                                        <option value="{!! $i !!}"
                                                @if((int)date('d') == $i) selected="selected" @endif>{!! $i !!}</option>
                                    @endfor
                                </select>
                                <span>/</span>
                                <select name="cbMonth" style="height: 25px;">
                                    <option value="">Tháng</option>
                                    @for($i = 1;$i<= 12; $i++)
                                        <option value="{!! $i !!}" @if((int)date('m') == $i) selected="selected" @endif>
                                            {!! $i !!}
                                        </option>
                                    @endfor
                                </select>
                                <span>/</span>
                                <select name="cbYear" style="height: 25px;">
                                    <option value="">Năm</option>
                                    @for($i = 2017;$i<= 2050; $i++)
                                        <option value="{!! $i !!}" @if((int)date('Y') == $i) selected="selected" @endif>
                                            {!! $i !!}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="qc-padding-top-20 qc-padding-bot-20  qc-border-none col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <button type="button" class="qc_save btn btn-sm btn-primary">Lưu</button>
                            <button type="reset" class="btn btn-sm btn-default">Hủy</button>
                            <button type="button" class="qc_ad3d_container_close btn btn-sm btn-default">Đóng</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
