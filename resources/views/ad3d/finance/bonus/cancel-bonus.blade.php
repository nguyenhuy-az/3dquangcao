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
$bonusId = $dataBonus->bonusId();
$money = $dataBonus->money();
$dataWork = $dataBonus->work;
# thong tin nhan vien
if (!empty($dataWork->companyStaffWorkId())) {
    $dataStaffBonus = $dataWork->companyStaffWork->staff;
} else {
    $dataStaffBonus = $dataWork->staff; // phien ban cu
}
?>
@extends('ad3d.components.container.container-6')
@section('qc_ad3d_container_content')
    <div class="qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <h3 style="color: red;">HỦY THƯỞNG</h3>
            </div>
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form class="qc_frm_bonus_cancel form" name="qc_frm_bonus_cancel" role="form" method="post"
                      enctype="multipart/form-data" action="{!! route('qc.ad3d.finance.bonus.cancel.post',$bonusId) !!}">
                    <div class="row">
                        <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="background-color: red;">
                            <i class="glyphicon glyphicon-warning-sign" style="color: yellow; font-size: 1.5em;"></i>
                            <label style="color: white;">
                                SAU KHI HỦY SẼ KHÔNG ĐƯỢC PHỤC HỒI.
                            </label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group " style="margin: 0; font-size: 1.5em;">
                                <em>Hủy thưởng</em>
                                <span style="color: blue;">{!! $hFunction->currencyFormat($money) !!}</span>
                                <em> của </em>
                                <span style="color: blue;">{!! $dataStaffBonus->fullName() !!}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label>Ghi chú:</label>
                            <input class="txtCancelNote form-control" type="text" name="txtCancelNote" placeholder="Nội dung ghi chú nếu có" value="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label>Ảnh ghi chú:</label>
                            <input type="file" class="txtCancelImage form-control" name="txtCancelImage">
                        </div>
                    </div>
                    <div class="row">
                        <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="font-weight: bold;">
                            <div class="qc_notify qc-font-size-16 form-group qc-color-red"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="text-center form-group form-group-sm">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <button type="button" class="qc_save btn btn-sm btn-primary">XÁC NHẬN HỦY</button>
                                <button type="reset" class="btn btn-sm btn-default">NHẬP LẠI</button>
                                <button type="button" class="qc_ad3d_container_close btn btn-sm btn-default">
                                    ĐÓNG
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
