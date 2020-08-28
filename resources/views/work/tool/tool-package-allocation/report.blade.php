<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 5/15/2018
 * Time: 10:12 AM
 *
 * dataStaff
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$dataStaff = $modelStaff->loginStaffInfo();
?>
@extends('components.container.container-8')
@section('qc_container_content')
    <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <h3 style="color: red;">BÁO CÁO ĐỒ NGHỀ</h3>
        <form id ="qc_frm_work_orders_payment" class="qc_frm_work_orders_payment form-horizontal" name="qc_frm_work_orders_payment" role="form"
              method="post" enctype="multipart/form-data"
              action="{!! route('qc.work.orders.payment.post', $orderId) !!}">
            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    @if (Session::has('notifyAdd'))
                        <div class="form-group text-center qc-color-red">
                            {!! Session::get('notifyAdd') !!}
                            <?php
                            Session::forget('notifyAdd');
                            ?>
                        </div>
                    @endif
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group form-group-sm">
                        <label class="col-sm-2 control-label">TRẠNG THÁI:</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="txtNote" value="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="text-center form-group form-group-sm">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <button type="button" class="qc_save btn btn-sm btn-primary">
                            BÁO CÁO
                        </button>
                        <a class="btn btn-sm btn-default"
                           onclick="qc_main.page_back();">
                            Đóng
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
