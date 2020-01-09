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
@extends('ad3d.components.container.container-6')
@section('qc_ad3d_container_content')
    <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="padding-top: 20px;padding-bottom: 20px; border-bottom: 2px dashed #C2C2C2;">
            <h3>CHI TIẾT PHẠT</h3>
        </div>

        {{-- chi tiêts --}}
        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <em>Nhân Viên:</em>
                    <span class="qc-font-bold">{!! $dataMinusMoney->work->staff->fullName() !!}</span>
                </div>
                <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <em>Mã NV:</em>
                    <span class="qc-font-bold">{!! $dataMinusMoney->work->staff->nameCode() !!}</span>
                </div>
            </div>
            <div class="row">
                <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <em>Số tiền:</em>
                    <span class="qc-font-bold">{!! $hFunction->dotNumber($dataMinusMoney->money()) !!}</span>
                </div>
                <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <em>Ngày:</em>
                    <span class="qc-font-bold">{!! date('d-m-Y', strtotime($dataMinusMoney->dateMinus())) !!}</span>
                </div>
                <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <em>Thủ Quỹ:</em>
                    <span class="qc-font-bold">{!! $dataMinusMoney->staff->fullName() !!}</span>
                </div>
            </div>
            <div class="row">
                <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <em class="qc-text-under" >Lý do:</em>
                    <span>{!! $dataMinusMoney->punishContent->name() !!}</span>
                </div>
            </div>
            <div class="row">
                <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <em class="qc-text-under" >Ghi chú:</em>
                    <span>{!! $dataMinusMoney->reason() !!}</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <button type="button" class="qc_ad3d_container_close btn btn-primary btn-sm">
                    Đóng
                </button>
            </div>
        </div>
    </div>
@endsection
