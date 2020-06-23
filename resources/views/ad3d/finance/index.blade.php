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
$dataLogin = $modelStaff->loginStaffInfo();
$dataCompanyLogin = $dataLogin->companyInfoActivity();
?>
@extends('ad3d.index')
@section('titlePage')
    Tài chính
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
                        <span class="qc-color-red navbar-brand">
                            Tài chính
                        </span>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            <li @if($accessObject == 'orderPayment') class="active" @endif>
                                <a href="{!! route('qc.ad3d.finance.order-payment.get') !!}">
                                    Thu
                                </a>
                            </li>
                            <li @if($accessObject == 'payment') class="active" @endif>
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    QL Chi<span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="qc-link" href="{!! route('qc.ad3d.finance.pay_activity.get') !!}">
                                            Chi hoạt động
                                        </a>
                                    </li>
                                    {{--<li >
                                        <a class="qc-color-grey">
                                            Thanh toán mua vật tư
                                        </a>
                                    </li>
                                    <li>
                                        <a class="qc-color-grey">
                                            Thanh toán công nợ
                                        </a>
                                    </li>
                                    <li>
                                        <a class="qc-color-grey">
                                            Thanh toán lương
                                        </a>
                                    </li>--}}
                                </ul>
                            </li>
                            <li @if($accessObject == 'salary' || $accessObject == 'payBefore' || $accessObject == 'payBeforeRequest' ) class="active" @endif>
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    QL Lương<span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="qc-link" href="{!! route('qc.ad3d.finance.salary.payment.get') !!}">
                                            Lương
                                        </a>
                                    </li>
                                    <li class="qc-color-grey">
                                        <a class="qc-link" href="{!! route('qc.ad3d.finance.salary.pay-before.get') !!}">
                                            Ứng lương
                                        </a>
                                    </li>
                                    <li class="qc-color-grey">
                                        <a class="qc-link" href="{!! route('qc.ad3d.salary.before_pay_request.get') !!}">
                                            Duyệt Ứng lương
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li @if($accessObject == 'bonus') class="active" @endif>
                                <a href="{!! route('qc.ad3d.finance.bonus.get') !!}">
                                    Thưởng
                                </a>
                            </li>
                            <li @if($accessObject == 'penalize') class="active" @endif>
                                <a href="{!! route('qc.ad3d.finance.minus-money.get') !!}">
                                    Phạt
                                </a>
                            </li>
                            <li @if($accessObject == 'transfers') class="active" @endif>
                                <a href="{!! route('qc.ad3d.finance.transfers.get') !!}">
                                    Giao tiền
                                </a>
                            </li>
                            <li @if($accessObject == 'keepMoney') class="active" @endif>
                                <a href="{!! route('qc.ad3d.finance.keep_money.get') !!}">
                                    Giữ tiền tiền
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
            @yield('qc_ad3d_finance_body')
        </div>
    </div>
@endsection
