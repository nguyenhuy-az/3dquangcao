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
@extends('ad3d.components.container.container-10')
@section('qc_ad3d_container_content')
    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="padding: 0;">
        <div class="panel panel-default">
            <div class="panel-heading text-right" style="background-color: white;">
                <button type="button" class="qc_ad3d_container_close btn btn-sm btn-default qc-color-red">
                    ĐÓNG
                </button>
            </div>

            <div class="panel-body">
                <img class="qc-link" onclick="qc_main.rotateImage(this);" style="max-width: 100%;" alt="..."
                     src="{!! $dataWorkAllocationReportImage->pathSmallImage($dataWorkAllocationReportImage->name()) !!}">
            </div>
        </div>
    </div>
@endsection
