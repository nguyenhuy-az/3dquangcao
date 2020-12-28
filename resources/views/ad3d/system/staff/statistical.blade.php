<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 *
 * dataStaff
 */

$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$dataCompany = $dataCompanyStaffWork->company;
?>
@extends('ad3d.system.staff.index')
@section('qc_ad3d_index_content')
    <div class="qc_ad3d_sys_staff_statistical_wrap qc-padding-bot-30 row">
        <div class="row" style="margin-bottom: 20px;">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <a class="qc-link-white-bold btn btn-sm btn-primary" onclick="qc_main.page_back_go();">
                    <i class="glyphicon glyphicon-backward"></i> Về trang trước
                </a>
            </div>
        </div>
        <div class="row" style="margin-bottom: 10px;">
            <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6">
                <div class="media">
                    <a class="pull-left">
                        <img class="media-object"
                             style="background-color: white; width: 60px;height: 60px; border: 1px solid #d7d7d7;border-radius: 10px;"
                             src="{!! $dataStaff->pathAvatar($dataStaff->image()) !!}">
                    </a>

                    <div class="media-body">
                        <h5 class="media-heading">{!! $dataStaff->fullName() !!}</h5>
                        <em style="color: grey;">{!! $dataCompany->name() !!}</em>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" style="margin-bottom: 10px;">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="background-color: black; color: yellow;">
                CHUYÊN CẦN
            </div>
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row" style="margin-bottom: 10px;">
                    <div class="col-sx-12 col-sm-12 col-md-8 col-lg-8">
                        Ngày làm
                    </div>
                    <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                        SL: 20
                    </div>
                </div>
            </div>
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row" style="margin-bottom: 10px;">
                    <div class="col-sx-12 col-sm-12 col-md-8 col-lg-8">
                        Ngày nghỉ có phép
                    </div>
                    <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                        SL: 2
                    </div>
                </div>
            </div>
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row" style="margin-bottom: 10px;">
                    <div class="col-sx-12 col-sm-12 col-md-8 col-lg-8">
                        Ngày nghỉ không phép
                    </div>
                    <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                        SL: 2
                    </div>
                </div>
            </div>
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row" style="margin-bottom: 10px;">
                    <div class="col-sx-12 col-sm-12 col-md-8 col-lg-8">
                        Ngày trễ
                    </div>
                    <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                        SL: 5
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="margin-bottom: 10px;">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="background-color: black; color: yellow;">
                NĂNG LỰC CHUYÊN MÔN - THI CÔNG
            </div>
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row" style="margin-bottom: 10px;">
                    <div class="col-sx-12 col-sm-12 col-md-8 col-lg-8">
                        1.Sản Phẩm mới
                    </div>
                    <div class="col-sx-12 col-sm-12 col-md-2 col-lg-2">
                        SL: 10
                    </div>
                    <div class="col-sx-12 col-sm-12 col-md-2 col-lg-2">
                        $ 1000
                    </div>
                </div>
                <div class="row" style="margin-bottom: 10px;">
                    <div class="col-sx-12 col-sm-12 col-md-8 col-lg-8">
                        - Đúng hẹn
                    </div>
                    <div class="col-sx-12 col-sm-12 col-md-2 col-lg-2">
                        SL: 7
                    </div>
                    <div class="col-sx-12 col-sm-12 col-md-2 col-lg-2">
                        $ 800
                    </div>
                </div>
                <div class="row">
                    <div class="col-sx-12 col-sm-12 col-md-8 col-lg-8">
                        - Trễ hẹn
                    </div>
                    <div class="col-sx-12 col-sm-12 col-md-2 col-lg-2">
                        SL: 3
                    </div>
                    <div class="col-sx-12 col-sm-12 col-md-2 col-lg-2">
                        $ 200
                    </div>
                </div>
            </div>

        </div>
        <div class="row" style="margin-bottom: 10px;">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="background-color: black; color: yellow;">
                NĂNG LỰC CHUYÊN MÔN - KINH DOANH
            </div>
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row" style="margin-bottom: 10px;">
                    <div class="col-sx-12 col-sm-12 col-md-8 col-lg-8">
                        Đơn hàng
                    </div>
                    <div class="col-sx-12 col-sm-12 col-md-2 col-lg-2">
                        SL: 10
                    </div>
                    <div class="col-sx-12 col-sm-12 col-md-2 col-lg-2">
                        $ 1000
                    </div>
                </div>
                <div class="row" style="margin-bottom: 10px;">
                    <div class="col-sx-12 col-sm-12 col-md-8 col-lg-8">
                        - Đúng hẹn
                    </div>
                    <div class="col-sx-12 col-sm-12 col-md-2 col-lg-2">
                        SL: 7
                    </div>
                    <div class="col-sx-12 col-sm-12 col-md-2 col-lg-2">
                        $ 800
                    </div>
                </div>
                <div class="row">
                    <div class="col-sx-12 col-sm-12 col-md-8 col-lg-8">
                        - Trễ hẹn
                    </div>
                    <div class="col-sx-12 col-sm-12 col-md-2 col-lg-2">
                        SL: 3
                    </div>
                    <div class="col-sx-12 col-sm-12 col-md-2 col-lg-2">
                        $ 200
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
