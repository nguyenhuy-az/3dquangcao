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
@extends('ad3d.finance.collect-money.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <h3>LẬP PHIẾU THU</h3>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <form id="frmAd3dAdd" role="form">
                {{-- thông tin khách hàng --}}
                <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px solid black;">
                            <i class="glyphicon glyphicon-pencil qc-color-green"></i>
                            <span class="qc-font-size-20">Chi tiết</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6"
                             @if($mobileStatus) style="padding: 0 0;" @endif>
                            <div class="form-group qc-padding-none">
                                <label>Đơn hàng: <i
                                            class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                <select  class="form-control">
                                    <option>Chọn đơn hàng</option>
                                    <option>DH_001 - Giặt tự động</option>
                                    <option>DH_002 - Lò quay đắc hào</option>
                                    <option>DH_003 - Châu gia huy</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="border-left: 5px solid #d7d7d7;">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                             @if($mobileStatus) style="padding: 0 0;" @endif>
                            <div class="qc-padding-none">
                                <em>* Chỉ hiện thông tin này khi đã chọn đơn hàng</em>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3"
                             @if($mobileStatus) style="padding: 0 0;" @endif>
                            <div class="form-group qc-padding-none">
                                <em>Tổng tiền:</em>
                                <input type="text" class="form-control" readonly value="10.000.000 VND">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3"
                             @if($mobileStatus) style="padding: 0 0;" @endif>
                            <div class="form-group">
                                <em>Đã thanh toán:</em>
                                <input type="text" class="form-control" readonly value="4.000.000 VND">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3"
                             @if($mobileStatus) style="padding: 0 0;" @endif>
                            <div class="form-group">
                                <em>Còn lại:</em>
                                <input type="text" class="form-control" readonly value="6.000.000 VND">
                            </div>
                        </div>
                    </div>
                    <div class="row" style="border-left: 5px solid #d7d7d7;">
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3"
                             @if($mobileStatus) style="padding: 0 0;" @endif>
                            <div class="form-group qc-padding-none">
                                <em>Mã Khách hàng:</em>
                                <input type="text" class="form-control" readonly value="KH_001">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3"
                             @if($mobileStatus) style="padding: 0 0;" @endif>
                            <div class="form-group qc-padding-none">
                                <em>Tên Khách hàng:</em>
                                <input type="text" class="form-control" readonly value="Nguyễn Văn tèo">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3"
                             @if($mobileStatus) style="padding: 0 0;" @endif>
                            <div class="form-group">
                                <em>Điện thoại:</em>
                                <input type="text" class="form-control" readonly value="0123456789">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6"
                             @if($mobileStatus) style="padding: 0 0;" @endif>
                            <div class="form-group qc-padding-none">
                                <em>Số tiền (VND):</em>
                                <input type="text" class="form-control" value="Nhập số tiền thu">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="qc-padding-top-20 qc-padding-bot-20  qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <a class="qc_save btn btn-primary btn-sm">
                            Lưu
                        </a>
                        <a class="btn btn-default btn-sm" href="{!! route('qc.ad3d.finance.collect-money.get') !!}">
                            Hủy
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
