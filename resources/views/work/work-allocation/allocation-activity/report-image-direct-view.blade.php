<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 *
 * dataCompany
 */
date_default_timezone_set('Asia/Ho_Chi_Minh');
$hFunction = new Hfunction();
?>
@extends('components.container.container-10')
@section('qc_container_content')
    <div class="qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <button type="button" class="qc_container_close btn btn-sm btn-default qc-color-red"
                style="position: absolute; top: 0; right: 0;">Đóng
        </button>
        <img class="qc-margin-top-10" style="max-width: 100%;" alt="..."
             src="{!! $dataWorkAllocationReportImage->pathSmallImage($dataWorkAllocationReportImage->name()) !!}">
    </div>
@endsection
