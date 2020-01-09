<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 */
/*
 *dataCompany
 */
?>
@extends('ad3d.components.container.container-8')
@section('qc_ad3d_container_content')
    <div class="qc-padding-top-20 qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
            <h3>{!! $dataCompany->name() !!}</h3>
            <br/>
            @if($dataCompany->checkBranch())
                <em>Chi Nhánh</em>
            @else
                <em>Trụ sở chính</em>
            @endif
        </div>
        @if(!empty($dataCompany->logo()))
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <img alt="..." src="{!! $dataCompany->pathSmallImage($dataCompany->logo()) !!}"
                     style="max-width: 300px;">
            </div>
        @endif
        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <i class="glyphicon glyphicon-arrow-right"></i>
            <em>Mã số thuế: </em>
            {!! $dataCompany->companyCode() !!}
        </div>
        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <i class="glyphicon glyphicon-arrow-right"></i>
            <em>Mã Cty: </em>
            {!! $dataCompany->nameCode() !!}
        </div>
        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <i class="glyphicon glyphicon-arrow-right"></i>
            <em>Địa chỉ: </em>
            {!! $dataCompany->address() !!}
        </div>
        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <i class="glyphicon glyphicon-arrow-right"></i>
            <em>Điện thoại: </em>
            {!! $dataCompany->phone() !!}
        </div>
        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <i class="glyphicon glyphicon-arrow-right"></i>
            <em>Email: </em>
            {!! $dataCompany->email() !!}
        </div>
        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <i class="glyphicon glyphicon-arrow-right"></i>
            <em>Website: </em>
            {!! $dataCompany->website() !!}
        </div>
        <div class=" text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <button type="button" class="qc_ad3d_container_close btn btn-primary">
                Đóng
            </button>
        </div>
    </div>
@endsection
