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
@section('titlePage')
    Thống kê
@endsection

@extends('ad3d.index')
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
                        <a class="qc-link-green navbar-brand" href="{!! route('qc.ad3d') !!}">
                            <i class="glyphicon glyphicon-home"></i>
                        </a>
                        <a class="navbar-brand" style="color: blue;">{!! $dataCompanyLogin->nameCode() !!}</a>
                        <a class="qc-color-red navbar-brand" href="#">Thống kê</a>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            {{--<li @if($accessObject == 'revenue') class="active" @endif>
                                <a href="{!! route('qc.ad3d.statistic.revenue.system.get') !!}">
                                    Doanh thu
                                </a>
                            </li>--}}
                            <li @if($accessObject == 'receiveAndPay') class="active" @endif>
                                <a href="{!! route('qc.ad3d.statistic.revenue.company.get') !!}">
                                    Thu - Chi
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
            @yield('qc_ad3d_statistic_body')
        </div>
    </div>
@endsection
