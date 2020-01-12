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
$designId = $dataProductDesign->designId();
?>
@extends('components.container.container-6')
@section('qc_container_content')
    <div class="qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
                <h3>XÁC NHẬN ÁP DỤNG THIẾT KẾ </h3>
            </div>
        </div>
        <div class="row">
            <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form class="frmWorkOrderProductDesignApplyConfirm" role="form" method="post"
                      action="{!! route('qc.work.orders.product.design.apply.post', "$designId/$applyStatus") !!}">
                    <div class="row">
                        <div class="notifyConfirm qc-color-red text-center col-xs-12 col-sm-12 col-md-6 col-lg-6"></div>
                    </div>
                    <div class="row">
                        <div class="text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group qc-padding-none">
                                @if($applyStatus == 1)
                                    <span class="qc-font-size-16 qc-color-red">
                                        THIẾT KẾ SẼ ĐƯỢC ÁP DỤNG IN RA GỬI ĐẾN KHÁCH HÀNG
                                    </span>
                                @else
                                    <span class="qc-font-size-16 qc-color-red">
                                        THIẾT KẾ SẼ BỊ VÔ HIỆU HÓA
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="text-center qc-padding-bot-20  qc-border-none col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <button type="button" class="qc_save btn btn-sm btn-primary">Xác nhận</button>
                            <button type="button" class="qc_container_close btn btn-sm btn-default">Đóng</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
