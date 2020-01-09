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
$timekeepingId = $dataTimekeepingProvisional->timekeepingProvisionalId();
$timeBegin = $dataTimekeepingProvisional->timeBegin();
$timeEnd = $dataTimekeepingProvisional->timeEnd();
$note = $dataTimekeepingProvisional->note();
$lateStatus = ($modelLicenseLateWork->checkDateLateWork($timeBegin)) ? 1 : 0;
#bang cham com
$dataWork = $dataTimekeepingProvisional->work;
$companyStaffWorkId = $dataWork->companyStaffWorkId();
if(!empty($companyStaffWorkId)){
    $staffName = $dataWork->companyStaffWork->staff->fullName();
}else{
    $staffName = $dataWork->staff->fullName();
}
?>
@extends('ad3d.components.container.container-8')
@section('qc_ad3d_container_content')
    <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
        <h3>XÁC NHẬN CHẤM CÔNG </h3>
    </div>
    <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <form class="qc_ad3d_frm_confirm form-horizontal" name="qc_ad3d_frm_confirm" role="form" method="post"
              action="{!! route('qc.ad3d.work.time-keeping-provisional.confirm.post') !!}">
            <div class="row">
                <div class="notifyConfirm qc-color-red text-center col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group qc-padding-none">
                        <label class="col-sm-2 control-label">Nhân viên:</label>

                        <div class="col-sm-10">
                            <input class="form-control" type="text" readonly="true"
                                   value="{!! $staffName !!}"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group form-group-sm ">
                        <label class="col-sm-2 control-label">Giờ vào:</label>
                        <div class="col-sm-10">
                            <input class="form-control" readonly="true"
                                   value="{!! date('d-m-Y H:i', strtotime($timeBegin)) !!}"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group form-group-sm ">
                        <label class="col-sm-2 control-label">Giờ ra:</label>

                        <div class="col-sm-10">
                            <input class="form-control" readonly="true"
                                   value="{!! date('d-m-Y H:i', strtotime($timeEnd)) !!}"/>
                        </div>
                    </div>
                </div>
            </div>
            @if($dataTimekeepingProvisional->checkAfternoonWork($timekeepingId))
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group form-group-sm ">
                            <label class="col-sm-2 control-label">Làm trưa:</label>

                            <div class="col-sm-10">
                                <em>Có tăng ca làm trưa</em>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if(!empty($note))
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group form-group-sm ">
                            <label class="col-sm-2 control-label">Ghi chú:</label>

                            <div class="col-sm-10">
                                <em>{!! $note !!}</em>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="form-group" style="border-top: 1px dotted grey;">
                <div class="col-sm-offset-2 col-sm-10">
                    <div class="radio col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <label>
                            <input type="radio" name="txtPermissionLateStatus" value="1"
                                   @if($lateStatus == 1) checked @endif>
                            Trễ có phép
                        </label>
                    </div>
                    <div class="radio col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <label>
                            <input type="radio" name="txtPermissionLateStatus" value="0"
                                   @if($lateStatus == 0) checked @endif>
                            Trễ không phép
                        </label>
                    </div>
                </div>
                <div class="col-sm-offset-2 col-sm-10">
                    <div class="radio col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <label>
                            <input type="radio" name="txtAccuracyStatus" value="1" checked>
                            Báo Chính xác
                        </label>
                    </div>
                    <div class="radio col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <label>
                            <input type="radio" name="txtAccuracyStatus" value="0">
                            Báo Không chính xác
                        </label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group form-group-sm ">
                        <label class="col-sm-2 control-label">Ghi chú:</label>

                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="txtConfirmNote" value="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="qc-padding-top-20 qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="form-group form-group-sm text-center">
                    <div class="col-sm-12 col-sm-12 col-md-12 col-lg-12">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <input type="hidden" name="txtTimekeeping" value="{!! $timekeepingId !!}">
                        <button type="button" class="qc_save btn btn-primary btn-sm">Xác nhận</button>
                        <button type="button" class="qc_ad3d_container_close btn btn-default btn-sm">Đóng</button>
                    </div>
                </div>

            </div>
        </form>
    </div>
@endsection
