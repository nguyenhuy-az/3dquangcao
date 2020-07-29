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
    <div class="text-center qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <button type="button" class="qc_container_close btn btn-sm btn-primary qc-color-red"
                style="position: absolute; top: 0; right: 0;">Đóng
        </button>
        <img class="qc-link qc-margin-top-10" onclick="qc_main.rotateImage(this);"  style="max-width: 100%;" alt="..."
             src="{!! $dataToolReturn->pathFullImage($dataToolReturn->image())  !!}">
    </div>
@endsection
