<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:28 PM
 */
/*
 * dataAccess
 */
$accessObject = (isset($dataAccess['accessObject'])) ? $dataAccess['accessObject'] : null;
$dataLogin = $modelStaff->loginStaffInfo();
$dataCompanyLogin = $dataLogin->companyInfoActivity();
?>
@extends('ad3d.index')
@section('titlePage')
    Hệ thống
@endsection
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
                        <a class="qc-color-red navbar-brand" href="#">Hệ Thống</a>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            <li @if($accessObject == 'staff') class="active" @endif>
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    Nhân viên <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="qc-link-bold" href="{!! route('qc.ad3d.system.staff.get') !!}">
                                            Nhân viên
                                        </a>
                                    </li>
                                    {{--<li @if($accessObject == 'salary') class="active" @endif>
                                        <a href="{!! route('qc.ad3d.system.salary.get') !!}">
                                            Lương cơ bản
                                        </a>
                                    </li>--}}
                                </ul>
                            </li>

                            <li @if($accessObject == 'company') class="active" @endif>
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    Công ty <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="qc-link-bold" href="{!! route('qc.ad3d.system.company.get') !!}">
                                            Công ty
                                        </a>
                                    </li>
                                    <li @if($accessObject == 'department') class="active" @endif>
                                        <a class="qc-link-bold" href="{!! route('qc.ad3d.system.department.get') !!}">
                                            Bộ phận
                                        </a>
                                    </li>
                                    <li @if($accessObject == 'rank') class="active" @endif>
                                        <a class="qc-link-bold" href="{!! route('qc.ad3d.system.rank.get') !!}">
                                            Cấp bậc quản lý
                                        </a>
                                    </li>
                                    <li @if($accessObject == 'rules') class="active" @endif>
                                        <a class="qc-link-bold" href="{!! route('qc.ad3d.system.rules.get') !!}">
                                            Nội quy
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li @if($accessObject == 'paymentType') class="active" @endif>
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    Danh mục chi <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="qc-link-bold"
                                           href="{!! route('qc.ad3d.system.pay_activity_list.get') !!}">
                                            Danh mục chi hoạt động
                                        </a>
                                    </li>
                                    <li @if($accessObject == 'department') class="active" @endif>
                                        <a class="qc-color-grey" href="#">
                                            Danh mục chi
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li @if($accessObject == 'punish') class="active" @endif>
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    Phạt <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="qc-link-bold" href="{!! route('qc.ad3d.system.punish-type.get') !!}">
                                            Lĩnh vực phạt
                                        </a>
                                    </li>
                                    <li @if($accessObject == 'department') class="active" @endif>
                                        <a class="qc-link-bold"
                                           href="{!! route('qc.ad3d.system.punish-content.get') !!}">
                                            Danh mục phạt
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li @if($accessObject == 'systemDateOff') class="active" @endif>
                                <a href="{!! route('qc.ad3d.system.system_date_off.get') !!}">
                                    Ngày nghỉ
                                </a>
                            </li>
                            <li @if($accessObject == 'kpi') class="active" @endif>
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    KPI <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="qc-link-bold" href="{!! route('qc.ad3d.system.kpi.get') !!}">
                                            Danh mục KPI
                                        </a>
                                    </li>
                                </ul>
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
            @yield('qc_ad3d_system_body')
        </div>
    </div>
@endsection