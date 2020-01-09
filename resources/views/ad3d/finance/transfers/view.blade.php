<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
?>
@extends('ad3d.components.container.container-10')
@section('qc_ad3d_container_content')
    <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="padding-top: 20px;padding-bottom: 20px; border-bottom: 2px dashed #C2C2C2;">
            <h3>CHI TIẾT CHUYỂN</h3>
        </div>
        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr>
                            <th class="text-center qc-padding-none">Ngày</th>
                            <th class="qc-padding-none">Người giao</th>
                            <th class="qc-padding-none">Người nhận</th>
                            <th class="text-center qc-padding-none">Số tiền</th>
                            <th class="text-center qc-padding-none">Ghi chú</th>
                        </tr>
                        <tr>
                            <td class="text-center qc-padding-none">
                                {!! date('d/m/Y', strtotime($dataTransfers->transfersDate())) !!}
                            </td>
                            <td class="qc-padding-none">
                                {!! $dataTransfers->transfersStaff->fullName() !!}
                            </td>
                            <td class="qc-padding-none">
                                {!! $dataTransfers->receiveStaff->fullName() !!}
                            </td>
                            <td class="text-center qc-color-red qc-padding-none">
                                {!! $hFunction->currencyFormat($dataTransfers->money()) !!}
                            </td>
                            <td class="text-center qc-padding-none">
                                {!! $dataTransfers->reason() !!}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <button type="button" class="qc_ad3d_container_close btn btn-sm btn-primary">
                    Đóng
                </button>
            </div>
        </div>
    </div>
@endsection
