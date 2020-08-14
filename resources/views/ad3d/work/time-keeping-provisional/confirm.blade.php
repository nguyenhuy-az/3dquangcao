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
# kiem tra thoi gian xac nhan
$timeDefault = date('Y-m-d 17:30', strtotime($timeBegin)); # chi xac nhan khi het ngay lam viec
$timeCheck = date('Y-m-d H:i');
#bang cham com
$dataWork = $dataTimekeepingProvisional->work;
$companyStaffWorkId = $dataWork->companyStaffWorkId();
if (!empty($companyStaffWorkId)) {
    $staffName = $dataWork->companyStaffWork->staff->fullName();
} else {
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
            @if($timeCheck > $timeDefault)
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
                                @if(!$hFunction->checkEmpty($timeEnd))
                                    <input class="form-control" readonly="true"
                                           value="{!! date('d-m-Y H:i', strtotime($timeEnd)) !!}"/>
                                @else
                                    <input class="form-control" readonly="true" value="Null"/>
                                @endif
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
                    <div class="col-sm-offset-2 col-sm-10 ">
                        <div class="radio col-xs-6 col-sm-6 col-md-4 col-lg-4">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="txtPermissionLateStatus"
                                           @if($lateStatus == 0) checked @endif>
                                    Không phép nếu trễ
                                </label>
                            </div>
                        </div>
                        <div class="radio col-xs-6 col-sm-6 col-md-4 col-lg-4">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="txtAccuracyStatus">
                                    Báo Không chính xác
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-offset-2 col-sm-10">
                        <div class="radio col-xs-6 col-sm-6 col-md-4 col-lg-4">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="txtApplyTimekeepingStatus">
                                    Không Tính công
                                </label>
                            </div>
                        </div>
                        <div class="radio col-xs-6 col-sm-6 col-md-4 col-lg-4">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="txtApplyRuleStatus" checked>
                                    Áp dụng phạt theo nội quy
                                </label>
                            </div>
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
                @if($hFunction->checkEmpty($timeEnd))
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="text-center form-group form-group-sm ">
                                <span style="background-color: red; color: yellow; padding: 5px;">NGÀY NÀY CHƯA BÁO GIỜ RA. SẼ KHÔNG ĐƯỢC TÍNH CÔNG</span>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group form-group-sm text-center">
                        <div class="col-sm-12 col-sm-12 col-md-12 col-lg-12">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <input type="hidden" name="txtTimekeeping" value="{!! $timekeepingId !!}">
                            <button type="button" class="qc_save btn btn-primary btn-sm">Xác nhận</button>
                            <button type="button" class="qc_ad3d_container_close btn btn-default btn-sm">Đóng</button>
                        </div>
                    </div>

                </div>
            @else
                <div class="row">
                    <div class="text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <span style="color: red; font-size: 2em;">CHỈ XÁC NHẬN SAU NGÀY LÀM VIỆC</span>
                    </div>
                </div>
                <div class="row">
                    <div class="text-center qc-padding-bot-20 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <button type="button" class="qc_ad3d_container_close btn btn-primary btn-sm">Đóng</button>
                    </div>
                </div>
            @endif
        </form>
    </div>
@endsection
