<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 *
 * dataStaff
 */
?>
@extends('ad3d.components.container.container-8')
@section('qc_ad3d_container_content')
    <div class="qc-padding-top-20 qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <img class="qc-img-100" src="{!! asset('public\imgtest\people.png') !!}">
                <h3>{!! $dataStaff->fullName() !!}</h3>
                <br/>
                <em>{!! $dataStaff->company->name() !!}</em>
            </div>
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <i class="glyphicon glyphicon-arrow-right"></i>
                <em class="qc-text-under">Mã nhân viên: </em>&nbsp;&nbsp;
                {!! $dataStaff->nameCode() !!}
            </div>
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <i class="glyphicon glyphicon-arrow-right"></i>
                <em class="qc-text-under">CMND: </em> &nbsp;&nbsp;
                {!! $dataStaff->identityCard() !!}
            </div>
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <i class="glyphicon glyphicon-arrow-right"></i>
                <em class="qc-text-under">Ngày sinh: </em>&nbsp;&nbsp;
                {!! $dataStaff->birthday() !!}
            </div>
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <i class="glyphicon glyphicon-arrow-right"></i>
                <em class="qc-text-under">Điện thoại: </em>
                {!! $dataStaff->phone() !!}
            </div>
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <i class="glyphicon glyphicon-arrow-right"></i>
                <em class="qc-text-under">Địa chỉ: </em>&nbsp;&nbsp;
                {!! $dataStaff->address() !!}
            </div>
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <i class="glyphicon glyphicon-arrow-right"></i>
                <em class="qc-text-under">Email: </em>&nbsp;&nbsp;
                {!! $dataStaff->email() !!}
            </div>
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <i class="glyphicon glyphicon-arrow-right"></i>
                <em class="qc-text-under">Ngày vào: </em>&nbsp;&nbsp;
                {!! $dataStaff->firstInfoToWork()->fromDate() !!}
            </div>
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <a href="{!! route('qc.ad3d.system.staff.get') !!}">
                    <button type="button" class="btn btn-primary">Đóng</button>
                </a>
            </div>
        </div>
    </div>
@endsection
