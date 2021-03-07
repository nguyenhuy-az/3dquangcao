<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:28 PM
 */
/*
 * $dataAccess
 */
$accessObject = (isset($dataAccess['accessObject'])) ? $dataAccess['accessObject'] : null;
$dataStaffLogin = $modelStaff->loginStaffInfo();
$dataCompanyLogin = $dataStaffLogin->companyInfoActivity();

# kiem tra bo phan truy cap
$manageDepartmentStatus = $dataStaffLogin->checkManageDepartment();
$businessDepartmentStatus = $dataStaffLogin->checkBusinessDepartment();
?>
@extends('ad3d.index')
@section('qc_ad3d_header')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <nav class="navbar navbar-default" role="navigation">
                <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse"
                                data-target="#bs-example-navbar-collapse-1">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="qc-link-green navbar-brand" href="{!! route('qc.ad3d') !!}">
                            <i class="glyphicon glyphicon-home"></i>
                        </a>
                        <a class="navbar-brand" style="color: blue;">{!! $dataCompanyLogin->nameCode() !!}</a>
                        <a class="qc-color-red navbar-brand" href="#">QL Đơn hàng</a>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            @if($manageDepartmentStatus)
                                <li @if($accessObject == 'order') class="active" @endif>
                                    <a href="{!! route('qc.ad3d.order.order.get') !!}">
                                        Đơn hàng
                                    </a>
                                </li>
                                <li @if($accessObject == 'orderProvisional') class="active" @endif>
                                    <a href="#">
                                        Báo giá
                                    </a>
                                </li>
                                <li @if($accessObject == 'orderAllocation') class="active" @endif>
                                    <a href="{!! route('qc.ad3d.order.allocation.get') !!}">
                                        ĐH bàn giao
                                    </a>
                                </li>
                                <li @if($accessObject == 'product') class="active" @endif>
                                    <a href="{!! route('qc.ad3d.order.product.get') !!}">
                                        Sản Phẩm
                                    </a>
                                </li>
                                <li @if($accessObject == 'productType') class="active" @endif>
                                    <a href="{!! route('qc.ad3d.order.product-type.get') !!}">
                                        Loại sản phẩm
                                    </a>
                                </li>
                            @endif
                            <li @if($accessObject == 'productTypePrice') class="active" @endif>
                                <a href="{!! route('qc.ad3d.order.product_type_price.get') !!}">
                                    Bảng giá
                                </a>
                            </li>
                        </ul>

                        <ul class="nav navbar-nav navbar-right">
                            <li>
                                <a href="{!! route('qc.ad3d') !!}">Trang chủ</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </div>
@endsection

@section('qc_ad3d_body')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            @yield('qc_ad3d_order_body')
        </div>
    </div>
@endsection
