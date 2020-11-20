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
$timekeepingId = $dataTimekeepingProvisional->timekeepingProvisionalId();
@extends('ad3d.components.container.container-6')
@section('qc_ad3d_container_content')
    <div class="qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <h3 style="color: red;">CẢNH BÁO GIỜ VÀO KHÔNG ĐÚNG</h3>
            </div>
            @if($true)
                <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="padding: 30px 0 30px 0;">
                    <span class="qc-color-red">Hết hạn báo giờ ra</span>
                </div>
            @else
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <form class="qc_frm_warming_add form" name="qc_frm_warming_add" role="form" method="post"
                          enctype="multipart/form-data"
                          action="{!! route('qc.ad3d.work.time_keeping_provisional.warning_begin.post') !!}">
                        <div class="row">
                            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="font-weight: bold;">
                                <div class="frm_notify qc-font-size-16 form-group form-group-sm qc-color-red"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label>Nguyên nhân:</label>
                                <input class="form-control" type="text" name="txtNote" value="Giờ vào thực tế không đúng giờ báo">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label>Ảnh cảnh báo:</label>
                                <input type="file" class="txtImage form-control" name="txtImage">
                            </div>
                        </div>
                        <div class="row">
                            <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-center form-group form-group-sm">
                                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                    <button type="button" class="qc_save btn btn-sm btn-primary">GỬI CẢNH BÁO</button>
                                    <button type="reset" class="btn btn-sm btn-default">NHẬP LẠI</button>
                                    <button type="button" class="qc_ad3d_container_close btn btn-sm btn-default">
                                        ĐÓNG
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            @endif

        </div>
    </div>
@endsection
