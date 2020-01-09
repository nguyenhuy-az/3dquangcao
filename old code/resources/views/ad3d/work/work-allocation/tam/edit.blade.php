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
@extends('ad3d.components.container.container-10')
@section('qc_ad3d_container_content')
    <div class="qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <h3>SỬA ĐƠN HÀNG</h3>
            </div>
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form role="form">
                    {{-- thông tin khách hàng --}}
                    <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px solid black;">
                                <i class="glyphicon glyphicon-pencil qc-color-green"></i>
                                <span class="qc-font-size-20">Khách Hàng</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3"
                                 @if($mobileStatus) style="padding: 0 0;" @endif>
                                <div class="form-group qc-padding-none">
                                    <label>Tên Khách hàng: <i
                                                class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                    <input type="text" class="form-control" placeholder="Nhập tên khách hàng"
                                           value="Nguyễn văn tèo">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3"
                                 @if($mobileStatus) style="padding: 0 0;" @endif>
                                <div class="form-group">
                                    <label>Điện thoại:</label>
                                    <input type="text" class="form-control" placeholder="Số điện thoại"
                                           value="0123456789">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6"
                                 @if($mobileStatus) style="padding: 0 0;" @endif>
                                <div class="form-group">
                                    <label>Địa chỉ:</label>
                                    <input type="text" class="form-control" placeholder="Địa chỉ" value="B17/18 quốc lộ 50, Bình Hưng, Bình Chánh, Tp.HCM">
                                </div>
                            </div>
                        </div>

                    </div>
                    {{-- chi tiết đơn hàng --}}
                    <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px solid black;">
                                <i class="glyphicon glyphicon-pencil qc-color-green"></i>
                                <span class="qc-font-size-20">Sản phẩm</span>
                            </div>
                        </div>
                        <div class="row qc-padding-top-10">
                            <div class="qc_ad3d_order_product_object qc-margin-top-20 qc-padding-top-10 col-xs-12 col-sm-12 col-md-12 col-lg-12"
                                 style="border: 1px solid #d7d7d7; border-left: 5px solid brown;">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3"
                                         @if($mobileStatus) style="padding: 0 0;" @endif>
                                        <div class="form-group">
                                            <label>Loại sản phẩm: <i
                                                        class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                            <select class="form-control">
                                                <option>Hộp đèn</option>
                                                <option>Bảng hiệu</option>
                                                <option>Băng rôn</option>
                                                <option>In decal</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3"
                                         @if($mobileStatus) style="padding: 0 0;" @endif>
                                        <div class="form-group">
                                            <label>Chất liệu: <i
                                                        class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                            <select class="form-control">
                                                <option>Alu</option>
                                                <option>Hiflex</option>
                                                <option>Mica</option>
                                                <option>Decal</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="text-left col-xs-12 col-sm-12 col-md-3 col-lg-3"
                                         @if($mobileStatus) style="padding: 0 0;" @endif>
                                        <div class="form-group">
                                            <label for="exampleInputFile">Mẫu thiết kế</label>
                                            <input type="file" id="exampleInputFile">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3"
                                         @if($mobileStatus) style="padding: 0 0;" @endif>
                                        <div class="form-group">
                                            <label>Chiều rộng: <i
                                                        class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                            <input class="form-control" type="text" placeholder="Chiều rộng sản phẩm" value="">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3"
                                         @if($mobileStatus) style="padding: 0 0;" @endif>
                                        <div class="form-group">
                                            <label>Chiều Cao: <i
                                                        class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                            <input class="form-control" type="text" placeholder="Chiều cao sản phẩm" value="">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3"
                                         @if($mobileStatus) style="padding: 0 0;" @endif>
                                        <div class="form-group">
                                            <label>
                                                Chiều Sâu:
                                                <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                            </label>
                                            <input class="form-control" type="text" placeholder="Chiều sâu sản phẩm" value="">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3"
                                         @if($mobileStatus) style="padding: 0 0;" @endif>
                                        <div class="form-group">
                                            <label>Số lượng: <i
                                                        class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                            <input class="form-control" type="text" placeholder="Số lượng sản phẩm"
                                                   value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3"
                                         @if($mobileStatus) style="padding: 0 0;" @endif>
                                        <div class="form-group">
                                            <label>
                                                Giá/sản phẩm:
                                                <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                            </label>
                                            <input class="form-control" type="text" placeholder="Gía trên một sản phẩm" value="">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3"
                                         @if($mobileStatus) style="padding: 0 0;" @endif>
                                        <div class="form-group">
                                            <label>
                                                Thành tiền:
                                                <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                            </label>
                                            <input class="form-control" type="text" placeholder="Tổng tiền của sản phẩm" readonly value="">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6"
                                         @if($mobileStatus) style="padding: 0 0;" @endif>
                                        <div class="form-group">
                                            <label>Chi chú</label>
                                            <input type="text" class="form-control" placeholder="Chú thích sản phảm" value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <a class="qc_delete qc-link-red" data-href="">
                                            Xóa
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- thông tin đơn hàng --}}
                    <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px solid black;">
                                <i class="glyphicon glyphicon-pencil qc-color-green"></i>
                                <span class="qc-font-size-20">Đơn hàng</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3"
                                 @if($mobileStatus) style="padding: 0 0;" @endif>
                                <div class="form-group">
                                    <label>Tên đơn hàng: <i
                                                class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                    <input type="text" class="form-control" placeholder="Nhập tên đơn hàng"
                                           value="">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-"
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
                            <div class="text-left col-xs-12 col-sm-12 col-md-3 col-lg-3"
                                 @if($mobileStatus) style="padding: 0 0;" @endif>
                                <div class="form-group">
                                    <label for="exampleInputFile">File thiết kế</label>
                                    <input type="file" id="exampleInputFile" placeholder="Tên file thiết kế">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3"
                                 @if($mobileStatus) style="padding: 0 0;" @endif>
                                <div class="form-group">
                                    <label>
                                        Tồng thanh toán:
                                    </label>
                                    <input type="text" class="form-control" placeholder="Giá tổng đơn hàng" readonly value="">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3"
                                 @if($mobileStatus) style="padding: 0 0;" @endif>
                                <div class="form-group">
                                    <label>
                                        Cọc:
                                        <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    </label>
                                    <input type="text" class="form-control" placeholder="Nhập tiền cọc" value="">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3"
                                 @if($mobileStatus) style="padding: 0 0;" @endif>
                                <div class="form-group">
                                    <label>Còn lại:</label>
                                    <input type="text" class="form-control" placeholder="Giá tổng còn lại" readonly value="">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="qc-padding-top-20 qc-padding-bot-20  qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <button type="submit" class="btn btn-primary">Lưu</button>
                            <button type="button" class="qc_ad3d_container_close btn btn-default">Đóng</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
