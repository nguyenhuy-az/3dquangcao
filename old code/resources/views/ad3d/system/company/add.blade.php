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
@extends('ad3d.system.company.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
            <h3>THÊM MỚI</h3>
        </div>
        <div class="qc-padding-top-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <form class="frmAdd" name="frmAdd" role="form" method="post" action="{!! route('qc.ad3d.system.company.add.post') !!}">
                <div class="row">
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="row">
                            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                @if (Session::has('notifyAdd'))
                                    <div class="form-group text-center qc-color-red">
                                        {!! Session::get('notifyAdd') !!}
                                        <?php
                                        Session::forget('notifyAdd');
                                        ?>
                                    </div>
                                @endif
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group form-group-sm">
                                    <label>
                                        Tên công ty
                                        <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    </label>
                                    <input type="text" name="txtName" class="form-control" placeholder="Nhập tên cty"
                                           value="">
                                </div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-3 col-lg-3">
                                <div class="form-group form-group-sm">
                                    <label>
                                        Mã Số Thuế:
                                        <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    </label>
                                    <input type="text" name="txtCompanyCode" class="form-control"
                                           placeholder="Nhập mã số thuế" value="">
                                </div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-3 col-lg-3">
                                <div class="form-group form-group-sm">
                                    <label>
                                        Loại hình
                                    </label>
                                    <select class="form-control" name="cbCompanyType">
                                        <option value="1" selected="selected">Chi nhánh</option>
                                        <option value="0" >Trụ sở</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group form-group-sm">
                                    <label>
                                        Mã cty:
                                        <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    </label>
                                    <input type="text" name="txtNameCode" class="form-control"
                                           placeholder="Nhập mã cty" value="">
                                </div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group form-group-sm">
                                    <label>
                                        Địa chỉ <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    </label>
                                    <input type="text" name="txtAddress" class="form-control" placeholder="Nhập tên"
                                           value="">
                                </div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6" >
                                <div class="form-group form-group-sm">
                                    <label>
                                        Điện thoại <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    </label>
                                    <input type="text" name="txtPhone" class="form-control" placeholder="Nhập số điện thoại"
                                           value="">
                                </div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group form-group-sm">
                                    <label>
                                        Email
                                    </label>
                                    <input type="text" name="txtEmail" class="form-control" placeholder="Email"
                                           value="">
                                </div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group form-group-sm">
                                    <label>
                                        Website
                                    </label>
                                    <input type="text" name="txtWebsite" class="form-control"
                                           placeholder="Địa chỉ website" value="">
                                </div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group form-group-sm">
                                    <label>
                                        Logo
                                    </label>
                                    <input type="file" name="txtLogo" placeholder="Logo công ty" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group form-group-sm">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <button type="button" class="qc_save btn btn-sm btn-primary">Thêm</button>
                            <button type="reset" class="btn btn-sm btn-default">Nhập lại</button>
                            <a href="{!! route('qc.ad3d.system.company.get') !!}">
                                <button type="button" class="btn btn-sm btn-default">Đóng</button>
                            </a>
                        </div>

                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection
