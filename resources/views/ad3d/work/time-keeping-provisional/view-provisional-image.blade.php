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
    <div class="qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <table class="table table-bordered">
            <tr>
                <td class="text-right">
                    <button type="button" class="qc_ad3d_container_close btn btn-sm btn-default qc-color-red">Đóng
                    </button>
                </td>
            </tr>
            <tr>
                <td style="width: auto; height: auto;">
                    <img class="qc-link qc-margin-top-10" title="Click xoay hình" style="max-width: 100%;" alt="..." onclick="qc_main.rotateImage(this);"
                         src="{!! $dataTimekeepingProvisionalImage->pathFullImage($dataTimekeepingProvisionalImage->name()) !!}">
                </td>
            </tr>
        </table>


    </div>
@endsection
