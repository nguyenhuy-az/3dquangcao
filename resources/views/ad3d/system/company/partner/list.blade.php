<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:32 PM
 */
/*
 *$dataCompany
 */
$hFunction = new Hfunction();
$dataStaffLogin = $modelStaff->loginStaffInfo();//checkRoot
$dataStaffLogin = $modelStaff->loginStaffInfo();
$dataCompanyLogin = $dataStaffLogin->companyInfoActivity();
# nhan su
# kiem tra bo phan truy cap
##$manageDepartmentStatus = $dataStaffLogin->checkManageDepartment();
##$businessDepartmentStatus = $dataStaffLogin->checkBusinessDepartment();
# kiem tra cong ty dang nhap phai cty me hay ko
$companyParentStatus = $dataCompanyLogin->checkParent();
?>
@extends('ad3d.system.company.partner.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="qc_ad3d_list_content row"
                 data-href-view="{!! route('qc.ad3d.system.company.view.get') !!}"
                 data-href-edit="{!! route('qc.ad3d.system.company.edit.get') !!}">
                <table class="table table-hover table-bordered">
                    {{--cong ty me moi duoc them chi nhanh--}}
                    @if($companyParentStatus)
                        {{--<tr>
                            <td style="padding: 0;">
                                <a class="form-control qc-font-size-16 qc-link-white-bold btn btn-primary"
                                   title="Thêm"
                                   href="{!! route('qc.ad3d.system.company.add.get') !!}">
                                    <i class="qc-font-size-16 glyphicon glyphicon-plus"></i>
                                    THÊM CHI NHÁNH
                                </a>
                            </td>
                        </tr>--}}
                    @endif
                    <tr style="background-color: black; color: yellow;">
                        <th>Danh sách công ty</th>
                    </tr>
                    <tr>
                        <td>
                            <h3 style="color: red;">
                                CHƯA PHÁT TRIỂN
                            </h3>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@endsection
