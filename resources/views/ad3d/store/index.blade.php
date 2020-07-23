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
$accessObject = (isset($dataAccess['accessObject']))?$dataAccess['accessObject']: null;
$dataLogin = $modelStaff->loginStaffInfo();
$dataCompanyLogin = $dataLogin->companyInfoActivity();
?>
@extends('ad3d.index')
@section('titlePage')
    Dụng cụ - Vật tư
@endsection
@section('qc_ad3d_header')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <nav class="navbar navbar-default" role="navigation">
                <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="{!! route('qc.ad3d') !!}">
                            <i class="qc-color-green glyphicon glyphicon-home"></i>
                        </a>
                        <a class="navbar-brand" style="color: blue;">{!! $dataCompanyLogin->nameCode() !!}</a>
                        <a class="qc-color-red navbar-brand" href="#">QL Dụng cụ - Vật tư</a>

                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            {{--<li @if($accessObject == 'import') class="active" @endif>
                                <a href="{!! route('qc.ad3d.store.import.get') !!}">
                                    Mua vật tư/ dụng cụ
                                </a>
                            </li>--}}
                            <li @if($accessObject == 'supplies') class="active" @endif>
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    Vật tư <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="qc-link" href="{!! route('qc.ad3d.store.supplies.supplies.get') !!}">
                                            Các loại vật tư
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li @if($accessObject == 'tool') class="active" @endif>
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    Dụng cụ <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="qc-link" href="{!! route('qc.ad3d.store.tool.tool.get') !!}">
                                            Các loại Đồ nghề
                                        </a>
                                    </li>
                                    {{--<li>
                                        <a class="qc-link" href="{!! route('qc.ad3d.store.tool.allocation.get') !!}">
                                            Bàn giao đồ nghề
                                        </a>
                                    </li>--}}
                                </ul>
                            </li>
                            {{--<li @if($accessObject == 'store') class="active" @endif>
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    Kho <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="qc-link" href="{!! route('qc.ad3d.store.store.tool.get') !!}">
                                            Dụng cụ
                                        </a>
                                    </li>
                                    <li>
                                        <a class="qc-color-grey" href="#">
                                            Vật tu
                                        </a>
                                    </li>
                                </ul>
                            </li>--}}
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
            @yield('qc_ad3d_store_body')
        </div>
    </div>
@endsection
