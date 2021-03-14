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
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
?>
@extends('ad3d.components.container.container-6')
@section('qc_ad3d_container_content')
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-xs-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
                <h3 style="color: red;">CẬP NHẬT NGÀY NGHỈ</h3>
            </div>
        </div>
        <div class="row">
            <div class="qc-padding-top-10 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <form id="frmAd3dSystemDateOffEdit" class="frmAd3dSystemDateOffEdit" role="form" method="post"
                      action="{!! route('qc.ad3d.system.system_date_off.edit.post', $dataSystemDateOff->dateOffId()) !!}">
                    <div class="row">
                        <div class="frm_notify qc-color-red text-center col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm">
                                <label>Ngày:</label>
                                <span class="qc-color-red qc-font-size-20">{!! $hFunction->convertDateDMYFromDatetime($dataSystemDateOff->dateOff()) !!}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm">
                                <label>Hình thức nghỉ:</label>
                                <select class="form-control" name="cbType">
                                    <option value="1" @if($dataSystemDateOff->type() == 1) selected="selected" @endif>
                                        Cố định (Bắt buộc)
                                    </option>
                                    <option value="2" @if($dataSystemDateOff->type() == 2) selected="selected" @endif>
                                        Không cố định
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm">
                                <label>Mô tả:</label>
                                <input type="text" name="txtDescription" class="form-control" placeholder="Mô tả"
                                       value="{!! $dataSystemDateOff->description() !!}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="text-center qc-padding-top-20 qc-padding-bot-20  qc-border-none col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <button type="button" class="qc_save btn btn-sm btn-primary">CẬP NHẬT</button>
                            <button type="button" class="qc_ad3d_container_close btn btn-sm btn-default">ĐÓNG</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
