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
            <h3>{!! $dataPaymentType->name() !!}</h3>
        </div>
        <div class=" text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <button type="button" class="qc_ad3d_container_close btn btn-primary">
                Đóng
            </button>
        </div>
    </div>
@endsection
