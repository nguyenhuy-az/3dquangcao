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
$dataStaffLogin = $modelStaff->loginStaffInfo();
$dataStaff = $modelStaff->getInfoActivityByLevel(0); // người quản lý
?>
@extends('ad3d.components.container.container-6')
@section('qc_ad3d_container_content')
    <div class="qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <h3 style="color: red;">CẬP NHẬT THÔNG TIN</h3>
            </div>
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form class="frmEdit" name="frmEdit" role="form" method="post"
                      action="{!! route('qc.ad3d.finance.transfers.transfers.edit.get',$dataTransfers->transfersId()) !!}">
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="frm_notify text-center form-group form-group-sm qc-color-red"></div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm">
                                <label>
                                    Người nhận:
                                    <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                </label>
                                <input class="form-control" type="text" readonly
                                       value="{!! $dataTransfers->receiveStaff->fullName() !!}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm">
                                <label>
                                    Số tiền (VND):
                                    <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                </label>
                                <input type="text" class="form-control" name="txtMoney" onkeyup="qc_main.showFormatCurrency(this);"
                                       value="{!! $hFunction->currencyFormat($dataTransfers->money()) !!}"
                                       placeholder="Nhập số tiền">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm">
                                <label>
                                    Lý do:
                                    <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                </label>
                                <input type="text" class="form-control" name="txtReason"
                                       value="{!! $dataTransfers->reason() !!}">
                            </div>
                        </div>
                    </div>
                    <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <button type="button" class="qc_save btn btn-sm btn-primary">CẬP NHẬT</button>
                        <button type="button" class="qc_ad3d_container_close btn btn-sm btn-default">Đóng</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
