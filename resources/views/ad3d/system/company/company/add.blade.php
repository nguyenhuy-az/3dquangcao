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
@extends('ad3d.system.company.company.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <h3 style="color:red;">THÊM CHI NHÁNH MỚI</h3>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <form class="frmAdd" name="frmAdd" role="form" method="post"
                  action="{!! route('qc.ad3d.system.company.add.post') !!}">
                @if (Session::has('notifyAdd'))
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group qc-font-size-16" style="background-color: red; color: yellow;">
                                {!! Session::get('notifyAdd') !!}
                                <?php
                                Session::forget('notifyAdd');
                                ?>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row">
                    {{--thông tin công cty--}}
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="border-left: 3px solid grey;">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                <div class="form-group form-group-sm">
                                    <h4>
                                        THÔNG TIN CÔNG TY
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                <div class="form-group form-group-sm">
                                    <label>Tên công ty :</label>
                                    <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    <input type="text" name="txtName" class="form-control" placeholder="Nhập tên cty"
                                           value="">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="form-group form-group-sm">
                                    <label>Loại hình :</label>
                                    <select class="form-control" name="cbCompanyType">
                                        <option value="1" selected="selected">Chi nhánh</option>
                                        {{--<option value="0" >Trụ sở</option>--}}
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                <div class="form-group form-group-sm">
                                    <label>Mã Số Thuế :</label>
                                    <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    <input type="text" name="txtCompanyCode" class="form-control"
                                           placeholder="Nhập mã số thuế" value="">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                <div class="form-group form-group-sm">
                                    <label>Mã cty :</label>
                                    <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    <input type="text" name="txtNameCode" class="form-control"
                                           placeholder="Nhập mã cty" value="">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                <div class="form-group form-group-sm">
                                    <label>Điện thoại :</label>
                                    <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    <input type="text" name="txtPhone" class="form-control"
                                           placeholder="Nhập số điện thoại"
                                           value="">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group form-group-sm">
                                    <label>Địa chỉ :</label>
                                    <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    <input type="text" name="txtAddress" class="form-control" placeholder="Nhập tên"
                                           value="">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group form-group-sm">
                                    <label>Email công ty :</label>
                                    <input type="text" name="txtEmail" class="form-control" placeholder="Email"
                                           value="">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group form-group-sm">
                                    <label>Website :</label>
                                    <input type="text" name="txtWebsite" class="form-control"
                                           placeholder="Địa chỉ website" value="">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group form-group-sm">
                                    <label>Logo :</label>
                                    <input type="file" name="txtLogo" placeholder="Logo công ty" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--thông tin người quản lý--}}
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="border-left: 3px solid grey;">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                <div class="form-group form-group-sm">
                                    <h4>
                                        THÔNG TIN QUẢN LÝ
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group form-group-sm">
                                    <label>Họ :</label>
                                    <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    <input type="text" class="form-control" name="txtFirstName" placeholder="Nhập họ"
                                           value="">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group form-group-sm qc-padding-none">
                                    <label>Tên :</label>
                                    <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    <input type="text" class="form-control" name="txtLastName" placeholder="Nhập Tên"
                                           value="">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group form-group-sm">
                                    <label>Chứng minh thư :</label>
                                    <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    <input type="text" class="form-control" name="txtIdentityCard"
                                           placeholder="Số chứng minh nhân dân" value="">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group form-group-sm">
                                    <label>Giới tính :</label>
                                    <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    <select class="form-control" name="cbGender">
                                        <option value="">Chọn giới tính</option>
                                        <option value="{!! $modelStaff->getDefaultGenderMale() !!}">Nam</option>
                                        <option value="{!! $modelStaff->getDefaultGenderFeMale() !!}">Nữ</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group form-group-sm" style="margin: 0;">
                                    <label>Điện thoại :</label>
                                    <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    <input type="text" class="form-control" name="txtStaffPhone"
                                           onkeyup="qc_main.showNumberInput(this);" placeholder="Số điện thoại"
                                           value="">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group form-group-sm">
                                    <label>Địa chỉ :</label>
                                    <input type="text" class="form-control" name="txtStaffAddress"
                                           placeholder="Thông tin địa chỉ" value="">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group form-group-sm">
                                    <label>Email :</label>
                                    <input type="text" class="form-control" name="txtStaffEmail" placeholder="Địa chỉ email"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group form-group-sm">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <button type="button" class="qc_save btn btn-sm btn-primary">THÊM</button>
                            <button type="reset" class="btn btn-sm btn-default">NHẬP LẠI</button>
                            <a href="{!! route('qc.ad3d.system.company.get') !!}">
                                <button type="button" class="btn btn-sm btn-default">ĐÓNG</button>
                            </a>
                        </div>

                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection
