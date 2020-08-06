<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 *
 * dataStaffSalaryBasic
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
?>
@extends('ad3d.components.container.container-6')
@section('qc_ad3d_container_content')
    <div class="qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <h3 style="color: red;">XÁC NHẬN PHẢN HỒI</h3>
            </div>
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form class="frmAd3dConfirmFeedback" name="frmAd3dConfirmFeedback" role="form" method="post"
                      action="{!! route('qc.ad3d.finance.minus-money.feedback.confirm.post',$dataMinusMoneyFeedback->feedbackId()) !!}">
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="frm_notify text-center form-group qc-color-red"></div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <select class="form-control" name="cbConfirmAccept">
                                    <option value="1">Chấp nhận - hủy phạt</option>
                                    <option value="0">Không chấp nhận</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="text-center form-group">
                                <span style="background-color: red;color: yellow; padding: 5px;">Sau khi xác nhận sẽ không thay đổi được</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <button type="button" class="qc_save btn btn-primary">XÁC NHẬN</button>
                        <button type="button" class="qc_ad3d_container_close btn btn-default">Đóng</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection