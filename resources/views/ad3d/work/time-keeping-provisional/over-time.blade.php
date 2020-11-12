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
$companyStaffWorkId = $dataCompanyStaffWork->workId();
$staffName = $dataCompanyStaffWork->staff->fullName();
?>
@extends('ad3d.components.container.container-8')
@section('qc_ad3d_container_content')
    <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
        <h3 style="color: red;">YÊU CẦU TĂNG CA </h3>
    </div>
    <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <form class="qc_ad3d_frm_over_time_add" name="qc_ad3d_frm_over_time_add" role="form" method="post"
              action="{!! route('qc.ad3d.work.time_keeping_provisional.over_time.post', $companyStaffWorkId) !!}">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="form-group">
                    <span style="color: blue">Thông báo tăng ca "BẮT BUỘC" trong ngày hôm nay</span>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="form-group">
                    <label>
                        Nhân viên
                    </label>
                    <input type="text" class="form-control" disabled="disabled" value="{!! $staffName !!}">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="form-group">
                    <label>
                        Ghi chú:
                    </label>
                    <input class="form-control" type="text" name="txtNote" value="">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="form-group qc_notify_content" style="color: red;">

                </div>
            </div>
            <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="form-group form-group-sm text-center">
                    <div class="col-sm-12 col-sm-12 col-md-12 col-lg-12">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <button type="button" class="qc_save btn btn-primary btn-sm">XÁC NHẬN GỬI</button>
                        <button type="button" class="qc_ad3d_container_close btn btn-default btn-sm">Đóng</button>
                    </div>
                </div>

            </div>
        </form>
    </div>
@endsection
