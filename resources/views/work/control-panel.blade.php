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

?>
@extends('work.index')
@section('titlePage')
    Trang chủ
@endsection
<style type="text/css">
    .qc-work-panel {
        text-align: center;
        height: 50px;
        line-height: 50px;
        border: 1px solid #d7d7d7;
    }

    .qc-work-panel:hover {
        background-color: #d7d7d7;
        color: red;
    }
</style>
@section('qc_work_body')
    {{--system info--}}
    @include('work.components.system-info', compact('modelStaff','dataTimekeepingProvisional'))

    {{--control panel--}}
    <div class="row">

        <div class="qc-work-panel col-xs-12 col-sm-4 col-md-4 col-lg-4">
            <a class="qc-work-panel-icon-link" href="{!! route('qc.work.work_allocation.activity.get') !!}">
                PHÂN VIỆC
            </a>
        </div>
        <div class="qc-work-panel col-xs-12 col-sm-4 col-md-4 col-lg-4">
            <a class="qc-work-panel-icon-link" href="{!! route('qc.work.timekeeping.get') !!}">
                CHẤM CÔNG
            </a>
        </div>
        @if($dataStaffLogin->checkBusinessDepartment() || $dataStaffLogin->checkDesignDepartment())
            <div class="qc-work-panel col-xs-12 col-sm-4 col-md-4 col-lg-4">
                <a class="qc-work-panel-icon-link" href="{!! route('qc.work.orders.get') !!}">
                    ĐƠN HÀNG
                </a>
            </div>
        @endif
        {{--<div class="qc-work-panel col-xs-12 col-sm-4 col-md-4 col-lg-4">
            <a class="qc-work-panel-icon-link" href="{!! route('qc.work.import.get') !!}">
                Mua vật tư
            </a>
        </div>--}}
        <div class="qc-work-panel col-xs-12 col-sm-4 col-md-4 col-lg-4">
            <a class="qc-work-panel-icon-link" href="{!! route('qc.work.import.get') !!}">
                CHI
            </a>
        </div>
        <div class="qc-work-panel col-xs-12 col-sm-4 col-md-4 col-lg-4">
            <a class="qc-work-panel-icon-link" href="{!! route('qc.work.money.receive.get') !!}">
                QUẢN LÝ THU -CHI
            </a>
        </div>

        <div class="qc-work-panel col-xs-12 col-sm-4 col-md-4 col-lg-4">
            <a class="qc-work-panel-icon-link" href="{!! route('qc.work.salary.salary.get') !!}">
                LƯƠNG
            </a>
        </div>
        <div class="qc-work-panel col-xs-12 col-sm-4 col-md-4 col-lg-4">
            <a class="qc-work-panel-icon-link" href="{!! route('qc.work.minus_money.get') !!}">
                PHẠT
            </a>
        </div>
        <div class="qc-work-panel col-xs-12 col-sm-4 col-md-4 col-lg-4">
            <a class="qc-work-panel-icon-link" href="{!! route('qc.work.tool.get') !!}">
                ĐỒ NGHỀ
            </a>
        </div>
        <div class="qc-work-panel col-xs-12 col-sm-4 col-md-4 col-lg-4">
            <a class="qc-work-panel-icon-link" href="{!! route('qc.work.product_type_price.get') !!}">
                BẢNG GIÁ
            </a>
        </div>
        <div class="qc-work-panel col-xs-12 col-sm-4 col-md-4 col-lg-4">
            <a class="qc-work-panel-icon-link" href="{!! route('qc.work.rules') !!}">
                NỘI QUY
            </a>
        </div>
        <div class="qc-work-panel col-xs-12 col-sm-4 col-md-4 col-lg-4">
            <a class="qc-work-panel-icon-link" href="{!! route('qc.work.change-account.get') !!}">
                Đổi tài khoản
            </a>
        </div>
        <div class="qc-work-panel col-xs-12 col-sm-4 col-md-4 col-lg-4">
            <a class="qc-work-panel-icon-link" href="{!! route('qc.work.logout.get') !!}">
                Thoát
            </a>
        </div>
    </div>
@endsection
