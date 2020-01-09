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
$licenseId = $dataLicenseLateWork->licenseId();
$dateOff = $dataLicenseLateWork->dateLate();
$note = $dataLicenseLateWork->note();
$currentDate = date('Y-m-d H:i:s');
?>
@extends('ad3d.components.container.container-8')
@section('qc_ad3d_container_content')
    <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
        <h3>XÁC NHẬN ĐI TRỄ </h3>
    </div>
    <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <form class="qc_ad3d_frm_confirm form-horizontal" name="qc_ad3d_frm_confirm" role="form" method="post"
              action="{!! route('qc.ad3d.work.late-work.confirm.post') !!}">
            <div class="row">
                <div class="notifyConfirm qc-color-red text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    @if($dateOff < $currentDate)
                        <span>Đã hết hạn duyệt</span>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group form-group-sm" style="margin: 0;">
                        <label>Nhân viên:</label>
                        <input class="form-control" type="text" readonly="true" style="height: 25px;"
                               value="{!! $dataLicenseLateWork->staff->fullName() !!}"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group form-group-sm" style="margin: 0;">
                        <label>Ngày nghỉ:</label>
                        <input class="form-control" readonly="true" style="height: 25px;"
                               value="{!! date('d-m-Y', strtotime($dateOff)) !!}"/>
                    </div>
                </div>
            </div>
            @if(!empty($note))
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group form-group-sm " style="margin: 0;">
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
                    @if($dateOff > $currentDate)
                        <div class="radio" style="margin: 0;">
                            <label>
                                <input type="radio" name="txtAgreeStatus" value="1" checked>
                                Đồng ý
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="txtAgreeStatus" value="0">
                                Không đồng ý
                            </label>
                        </div>
                    @else
                        <div class="radio" style="margin: 0;">
                            <label>
                                <input type="radio" name="txtAgreeStatusShow" value="0" checked disabled>
                                <input type="hidden" name="txtAgreeStatus" value="0">
                                Không đồng ý
                            </label>
                        </div>
                    @endif

                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group form-group-sm " style="margin:0;">
                        <label >Ghi chú:</label>
                        <input class="form-control" style="height: 25px;" type="text" name="txtConfirmNote" value="">
                    </div>
                </div>
            </div>
            <div class="qc-padding-top-20 qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="form-group form-group-sm text-center">
                    <div class="col-sm-12 col-sm-12 col-md-12 col-lg-12">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <input type="hidden" name="txtLicense" value="{!! $licenseId !!}">
                        <button type="button" class="qc_save btn btn-primary btn-sm">Xác nhận</button>
                        <button type="button" class="qc_ad3d_container_close btn btn-default btn-sm">Đóng</button>
                    </div>
                </div>

            </div>
        </form>
    </div>
@endsection
