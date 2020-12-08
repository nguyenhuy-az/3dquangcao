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
$importId = $dataImport->importId();
?>
@extends('components.container.container-6')
@section('qc_container_content')
    <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
        <h3 style="color: red;">THANH TOÁN MUA DỤNG CỤ / VẬT TƯ </h3>
    </div>
    <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <form class="qc_frm_pay_import_pay form-horizontal" name="qc_frm_pay_import_pay" role="form" method="post"
              action="{!! route('qc.work.pay.import.pay.post',$importId) !!}">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="text-center form-group form-group-sm qc-padding-none">
                        <span>Tôi đã thanh toán</span>&nbsp;
                        <b class="qc-color-red">{!! $hFunction->currencyFormat($dataImport->totalMoneyOfImport()) !!}</b>
                    </div>
                </div>
            </div>
            <div class="qc-padding-top-20 qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="form-group form-group-sm text-center">
                    <div class="col-sm-12 col-sm-12 col-md-12 col-lg-12">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <button type="button" class="qc_save btn btn-primary btn-sm">XÁC NHẬN</button>
                        <button type="button" class="qc_container_close btn btn-default btn-sm">ĐÒNG</button>
                    </div>
                </div>

            </div>
        </form>
    </div>
@endsection
