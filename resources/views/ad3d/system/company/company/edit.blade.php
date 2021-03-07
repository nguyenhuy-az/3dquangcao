<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 *
 * dataCompany
 */
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$companyType = $dataCompany->companyType();
?>
@extends('ad3d.components.container.container-10')
@section('qc_ad3d_container_content')
    <div class="qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
                <h3 style="color: red;">CẬP NHẬT THÔNG TIN</h3>
            </div>
            <div class="qc-padding-top-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form class="frmEdit" name="frmEdit" role="form" method="post"
                      action="{!! route('qc.ad3d.system.company.post.get', $dataCompany->companyId()) !!}">
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="row">
                            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="frm_notify form-group qc-color-red"></div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group form-group-sm">
                                    <label>
                                        Tên công ty
                                        <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    </label>
                                    <input type="text" name="txtName" class="form-control" placeholder="Nhập tên cty"
                                           value="{!! $dataCompany->name() !!}">
                                </div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-3 col-lg-3">
                                <div class="form-group form-group-sm">
                                    <label>
                                        Mã Số Thuế:
                                        <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    </label>
                                    <input type="text" name="txtCompanyCode" class="form-control"
                                           placeholder="Nhập mã số thuế" value="{!! $dataCompany->companyCode() !!}">
                                </div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-3 col-lg-3">
                                <div class="form-group form-group-sm">
                                    <label>
                                        Loại hình
                                    </label>
                                    <select class="form-control" name="cbCompanyType">
                                        <option value="{!! $companyType !!}">
                                            {!! $dataCompany->companyTypeLabel($companyType) !!}
                                        </option>
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
                                           placeholder="Nhập mã cty" value="{!! $dataCompany->nameCode() !!}">
                                </div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group form-group-sm">
                                    <label>
                                        Địa chỉ <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    </label>
                                    <input type="text" name="txtAddress" class="form-control" placeholder="Nhập địa chỉ"
                                           value="{!! $dataCompany->address() !!}">
                                </div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group form-group-sm">
                                    <label>
                                        Điện thoại <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    </label>
                                    <input type="text" name="txtPhone" class="form-control"
                                           placeholder="Nhập số diện thoại"
                                           value="{!! $dataCompany->phone() !!}">
                                </div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6 ">
                                <div class="form-group form-group-sm">
                                    <label>
                                        Email
                                    </label>
                                    <input type="text" name="txtEmail" class="form-control" placeholder="Email"
                                           value="{!! $dataCompany->email() !!}">
                                </div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group form-group-sm">
                                    <label>
                                        Website
                                    </label>
                                    <input type="text" name="txtWebsite" class="form-control"
                                           placeholder="Địa chỉ website" value="{!! $dataCompany->website() !!}">
                                </div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group form-group-sm">
                                    @if(!empty($dataCompany->logo()))
                                        <img alt="..." src="{!! $dataCompany->pathSmallImage($dataCompany->logo()) !!}"
                                             style="max-width: 200px;;">
                                        <br/>
                                    @endif
                                    <label>
                                        Logo mới:
                                    </label>
                                    <input type="file" name="txtNewLogo" placeholder="Logo" value="">
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <button type="button" class="qc_save btn btn-sm btn-primary">CẬP NHẬT</button>
                        <button type="reset" class=" btn btn-sm btn-default">NHẬP LẠI</button>
                        <button type="button" class="qc_ad3d_container_close btn btn-sm btn-default">ĐÓNG</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
