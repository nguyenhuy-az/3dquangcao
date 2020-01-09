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
@extends('ad3d.order.order.index')
@section('qc_ad3d_order_order')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"  style="border-bottom: 2px dashed brown;">
            <h3>THÊM SẢN PHẨM</h3>
        </div>
        <form id="frmAd3dAdd" role="form">
            {{-- thông tin đơn hàng --}}
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6"
                         @if($mobileStatus) style="padding: 0 0;" @endif>
                        <div class="form-group">
                            <label>Tên đơn hàng: <i
                                        class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                            <select class="form-control">
                                <option>Chọn đơn hàng</option>
                                <option>Đơn hàng 1</option>
                                <option>Đơn hàng 2</option>
                                <option>Đơn hàng 3</option>

                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="text-left col-xs-12 col-sm-12 col-md-12 col-lg-12"
                         @if($mobileStatus) style="padding: 0 0;" @endif>
                        <div class="form-group">
                            <label for="exampleInputFile">Mẫu thiết kế</label>
                            <input type="file" id="exampleInputFile" placeholder="Tên file thiết kế">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3"
                         @if($mobileStatus) style="padding: 0 0;" @endif>
                        <div class="form-group">
                            <label>Chiều Ngang (mm): <i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                            <input type="number" class="form-control" placeholder="Nhâp chiều ngang" value="0" >
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3"
                         @if($mobileStatus) style="padding: 0 0;" @endif>
                        <div class="form-group">
                            <div class="form-group">
                                <label>Chiều Cao (mm): <i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                <input type="number" class="form-control" placeholder="Nhâp chiều cao" value="0" >
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3"
                         @if($mobileStatus) style="padding: 0 0;" @endif>
                        <div class="form-group">
                            <div class="form-group">
                                <label>Chiều Sâu (mm): <i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                <input type="number" class="form-control" placeholder="Nhâp chiều sâu" value="0" >
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3"
                         @if($mobileStatus) style="padding: 0 0;" @endif>
                        <div class="form-group">
                            <div class="form-group">
                                <label>Giá (VND): <i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                <input type="number" class="form-control" placeholder="Nhâp Giá" >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                         @if($mobileStatus) style="padding: 0 0;" @endif>
                        <div class="form-group">
                            <label>
                                Ghi chú:
                                <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                            </label>
                            <input type="text" class="form-control" placeholder="Nội dung ghi chú" value="">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3"
                         @if($mobileStatus) style="padding: 0 0;" @endif>
                        <div class="form-group">
                            <label>Ngày nhận: <i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                            <select class="form-control">
                                <option value="">1/02/2018</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3"
                         @if($mobileStatus) style="padding: 0 0;" @endif>
                        <div class="form-group">
                            <label>Ngày giao: <i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                            <select class="form-control">
                                @for($i = 1;$i <= 31; $i++)
                                    <option value="{!! $i !!}">{!! $i !!}/02/2018</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="qc-padding-top-20 qc-padding-bot-20  qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <a class="qc_save btn btn-primary btn-sm">
                        Lưu
                    </a>
                    <a class="btn btn-default btn-sm" href="{!! route('qc.ad3d.order.product.get') !!}">
                        Đóng
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection
