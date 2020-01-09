<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 4/11/2017
 * Time: 12:10 AM
 */

?>
<div class="row">
    {{--banner--}}
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <img src="{!! asset('public\imgtest\banner-web.jpg') !!}" style="width: 100%; height: 300px;">
    </div>

    <div class="qc-padding-top-10 qc-padding-bot-10 text-right col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <em style="color: red; text-decoration:underline;">Hot line:</em> <span>0123456789</span> &nbsp;&nbsp;
        <em style="color: red; text-decoration:underline;">Email:</em> <span>dia-chi-email@gmail.com</span>
    </div>
    {{--menu--}}
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <nav class="navbar navbar-default" role="navigation" style="background-color: #000000;">
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
                    <a class="navbar-brand" href="{!! route('qc.home') !!}"
                       style="color: yellow; font-size: 16px; font-weight:bold; ">
                        Trang chủ
                    </a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li>
                            <a href="{!! route('qc.about') !!}"
                               style="color: yellow; font-size: 16px; font-weight:bold;">Giới thiệu</a>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                               style="color: yellow; font-size: 16px; font-weight:bold; background-color: #000000;">
                                Dịch vụ <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="{!! route('qc.service.repair') !!}">Sửa chữa và Bảo hành</a>
                                </li>
                                <li>
                                    <a href="{!! route('qc.service.advisory') !!}">Tư vấn và Thiết kế</a>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                               style="color: yellow; font-size: 16px; font-weight:bold; background-color: #000000;">
                                Sản phẩm <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="{!! route('qc.product') !!}">Thi công bảng hiệu</a></li>
                                <li><a href="{!! route('qc.product') !!}">Tổ chức sự kiện</a></li>
                                <li><a href="{!! route('qc.product') !!}">Công trình nội thất</a></li>
                                <li class="divider"></li>
                                <li>
                                    <a href="{!! route('qc.product') !!}">Logo</a>
                                </li>
                                <li>
                                    <a href="{!! route('qc.product') !!}">Hộp đèn</a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                               style="color: yellow; font-size: 16px; font-weight:bold;  background-color: #000000;">
                                Báo Giá <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="{!! route('qc.price.banner') !!}">Bảng Hiệu</a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="{!! route('qc.price.material') !!}">Chất liệu</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="{!! route('qc.recruitment') !!}"
                               style="color: yellow; font-size: 16px; font-weight:bold;">
                                Tuyển dụng
                            </a>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="{!! route('qc.contact') !!}"
                               style="color: yellow; font-size: 16px; font-weight:bold; ">
                                Liên hệ
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container-fluid -->
        </nav>
    </div>
</div>
