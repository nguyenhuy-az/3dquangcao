<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
?>
@extends('ad3d.components.container.container-10')
@section('qc_ad3d_container_content')
    <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="padding-top: 20px;padding-bottom: 20px; border-bottom: 2px dashed #C2C2C2;">
            <h3>CHI TIẾT ỨNG LƯƠNG</h3>
        </div>

        {{-- chi tiêts --}}
        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <em>Nhân Viên:</em>
                    <span class="qc-font-bold">{!! $dataSalaryBeforePay->work->staff->fullName() !!}</span>
                </div>
                <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <em>Mã NV:</em>
                    <span class="qc-font-bold">{!! $dataSalaryBeforePay->work->staff->nameCode() !!}</span>
                </div>
            </div>
            <div class="row">
                <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <em>Số tiền:</em>
                    <span class="qc-font-bold">{!! $hFunction->dotNumber($dataSalaryBeforePay->money()) !!}</span>
                </div>
                <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <em>Ngày:</em>
                    <span class="qc-font-bold">{!! date('Y-m-d', strtotime($dataSalaryBeforePay->datePay())) !!}</span>
                </div>
                <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <em>Thủ Quỹ:</em>
                    <span class="qc-font-bold">{!! $dataSalaryBeforePay->staff->fullName() !!}</span>
                </div>
            </div>
            <div class="row">
                <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <em class="qc-text-under" >Ghi Chú</em>
                    <p>{!! $dataSalaryBeforePay->description() !!}</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <button type="button" class="qc_ad3d_container_close btn btn-primary">
                    Đóng
                </button>
            </div>
        </div>
    </div>
@endsection
