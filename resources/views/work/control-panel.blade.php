<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 8:57 AM
 *
 * modelStaff
 */
$dataStaffLogin = $modelStaff->loginStaffInfo();
$dataCompanyStaffWorkLogin = $modelStaff->loginCompanyStaffWork();
?>
@extends('work.index')
@section('titlePage')
    Trang chủ
@endsection
<style type="text/css">
    .qc-work-panel {
        text-align: center;
        height: 60px;
        padding-top: 10px;
        /*line-height: 100px;*/
        border: 1px solid #d7d7d7;
    }

    .qc-work-panel:hover {
        background-color: #d7d7d7;
        color: red;
    }
</style>
@section('qc_work_body')
    @if($dataStaffLogin->checkActivityWork())
        {{--thong bao cua he thong--}}
        @include('work.components.system-info.system-info', compact('modelCompany','modelStaff','dataTimekeepingProvisional'))

        {{--canh bao kiem tra do nghe--}}
        @if($dataCompanyStaffWorkLogin->existUnConfirmInRoundCompanyStoreCheck())
            @include('work.components.warning.confirm-company-store-check')
        @endif

        {{--bang dieu khien--}}
        <div class="row">
            <div class="qc-work-panel col-xs-12 col-sm-4 col-md-4 col-lg-4">
                <a class="qc-work-panel-icon-link" href="{!! route('qc.work.timekeeping.get') !!}">
                    <i class="glyphicon glyphicon-calendar" style="font-size: 20px; color: green;"></i> <br/>
                    CHẤM CÔNG
                </a>
            </div>
            <div class="qc-work-panel col-xs-12 col-sm-4 col-md-4 col-lg-4">
                <a class="qc-work-panel-icon-link" href="{!! route('qc.work.work_allocation.get') !!}">
                    <i class="glyphicon glyphicon-wrench" style="font-size: 20px;color: grey;"></i> <br/>
                    THI CÔNG
                </a>
            </div>
            @if($dataStaffLogin->checkBusinessDepartment() || $dataStaffLogin->checkDesignDepartment())
                <div class="qc-work-panel col-xs-12 col-sm-4 col-md-4 col-lg-4">
                    <a class="qc-work-panel-icon-link" href="{!! route('qc.work.orders.get') !!}">
                        <i class="glyphicon glyphicon-shopping-cart" style="font-size: 20px;color: blue;"></i> <br/>
                        ĐƠN HÀNG
                    </a>
                </div>
            @endif
            <div class="qc-work-panel col-xs-12 col-sm-4 col-md-4 col-lg-4">
                <a class="qc-work-panel-icon-link" href="{!! route('qc.work.import.get') !!}">
                    <i class="glyphicon glyphicon-save" style="font-size: 20px;color: grey;"></i> <br/>
                    MUA VẬT TƯ - ĐỐ NGHỀ
                </a>
            </div>
            @if($dataStaffLogin->checkTreasureDepartment())
                <div class="qc-work-panel col-xs-12 col-sm-4 col-md-4 col-lg-4">
                    <a class="qc-work-panel-icon-link" href="{!! route('qc.work.pay.import.get') !!}">
                        <i class="glyphicon glyphicon-credit-card" style="font-size: 20px;color: brown;"></i> <br/>
                        CHI
                    </a>
                </div>
            @endif
            @if($dataStaffLogin->checkBusinessDepartment() || $dataStaffLogin->checkTreasureDepartment())
                <div class="qc-work-panel col-xs-12 col-sm-4 col-md-4 col-lg-4">
                    <a class="qc-work-panel-icon-link" href="{!! route('qc.work.money.receive.get') !!}">
                        <i class="glyphicon glyphicon-th-list" style="font-size: 20px;color: grey;"></i> <br/>
                        QUẢN LÝ THU -CHI
                    </a>
                </div>
            @endif
            <div class="qc-work-panel col-xs-12 col-sm-4 col-md-4 col-lg-4">
                <a class="qc-work-panel-icon-link" href="{!! route('qc.work.salary.salary.get') !!}">
                    <i class="glyphicon glyphicon-usd" style="font-size: 20px;color: red;"></i> <br/>
                    LƯƠNG
                </a>
            </div>
            <div class="qc-work-panel col-xs-12 col-sm-4 col-md-4 col-lg-4">
                <a class="qc-work-panel-icon-link" href="{!! route('qc.work.minus_money.get') !!}">
                    <i class="glyphicon glyphicon-plus" style="color: grey;"></i>
                    <i class="glyphicon glyphicon-usd" style="font-size: 20px;color: blue;"></i>
                    <i class="glyphicon glyphicon-minus" style="color: grey;"></i> <br/>
                    THƯỞNG - PHẠT
                </a>
            </div>
            <div class="qc-work-panel col-xs-12 col-sm-4 col-md-4 col-lg-4">
                <a class="qc-work-panel-icon-link" href="{!! route('qc.work.tool.check_store.get') !!}">
                    <i class="glyphicon glyphicon-briefcase" style="font-size: 20px;color: green;"></i> <br/>
                    ĐỒ NGHỀ
                </a>
            </div>
            @if($dataStaffLogin->checkManageDepartment())
                <div class="qc-work-panel col-xs-12 col-sm-4 col-md-4 col-lg-4">
                    <a class="qc-work-panel-icon-link"
                       href="{!! route('qc.work.store.tool_package_allocation.get') !!}">
                        <i class="glyphicon glyphicon-list-alt" style="font-size: 20px;color: grey;"></i> <br/>
                        QUẢN LÝ ĐỒ NGHỀ
                    </a>
                </div>
            @endif
            <div class="qc-work-panel col-xs-12 col-sm-4 col-md-4 col-lg-4">
                <a class="qc-work-panel-icon-link" href="{!! route('qc.work.product_type_price.get') !!}">
                    <i class="glyphicon glyphicon-list" style="font-size: 20px;color: brown;"></i><br/>
                    BẢNG GIÁ
                </a>
            </div>
            <div class="qc-work-panel col-xs-12 col-sm-4 col-md-4 col-lg-4">
                <a class="qc-work-panel-icon-link" href="{!! route('qc.work.rules') !!}">
                    <i class="glyphicon glyphicon-warning-sign" style="font-size: 20px;color: orangered;"></i> <br/>
                    NỘI QUY
                </a>
            </div>
            <div class="qc-work-panel col-xs-12 col-sm-4 col-md-4 col-lg-4">
                <a class="qc-work-panel-icon-link" href="{!! route('qc.work.change-account.get') !!}">
                    <i class="glyphicon glyphicon-user" style="font-size: 20px;color: grey;"></i> <br/>
                    Đổi tài khoản
                </a>
            </div>
            <div class="qc-work-panel col-xs-12 col-sm-4 col-md-4 col-lg-4">
                <a class="qc-work-panel-icon-link" href="{!! route('qc.work.logout.get') !!}">
                    <i class="glyphicon glyphicon-log-out" style="font-size: 20px;color: grey;"></i> <br/>
                    Thoát
                </a>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <h2 style="background-color: red; color: yellow; padding: 10px;">BẢNG CHẤM CÔNG ĐÃ TẮT</h2>
            </div>
        </div>
    @endif
@endsection
