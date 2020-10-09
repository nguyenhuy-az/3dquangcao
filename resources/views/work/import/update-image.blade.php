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
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
?>
@extends('components.container.container-6')
@section('qc_container_content')
    <div class="qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="qc-padding-top-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form class="qc_frm_import_update_image form-horizontal" name="qc_frm_import_update_image" role="form"
                      method="post" enctype="multipart/form-data"
                      action="{!! route('qc.work.import.image.update.post',$dataImport->importId()) !!}">
                    <div class="row">
                        <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="font-weight: bold;">
                            <div class="frm_notify qc-font-size-16 form-group form-group-sm qc-color-red"></div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm">
                                <label class="col-sm-3 control-label" style="color: red;">CHỌN ẢNH HÓA ĐƠN:</label>
                                <div class="col-sm-9">
                                    <input type="file" class="txtImportImage" name="txtImportImage" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="text-center form-group form-group-sm">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <button type="button" class="qc_save btn btn-sm btn-primary">CẬP NHẬT</button>
                                <button type="button" class="qc_container_close btn btn-sm btn-default">Đóng</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
