<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 *
 * dataCompany
 */
$hFunction = new Hfunction();
$note = $dataTimekeepingProvisionalWarning->note();
$image = $dataTimekeepingProvisionalWarning->image();

?>
@extends('components.container.container-10')
@section('qc_container_content')
    <div class="qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-right col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <button type="button" class="qc_container_close btn btn-sm btn-default qc-color-red">
                    ĐÓNG
                </button>
            </div>
        </div>
        @if(!$hFunction->checkEmpty($note))
            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <b style="color: red;">
                        {!! $note !!}
                    </b>
                </div>
            </div>
        @endif
        @if(!$hFunction->checkEmpty($image))
            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <img class="qc-cursor-pointer qc-margin-top-10" style="max-width: 100%;" alt="..."
                         onclick="qc_main.rotateImage(this);"
                         src="{!! $dataTimekeepingProvisionalWarning->pathFullImage($image) !!}">
                </div>
            </div>
        @endif
    </div>
@endsection
