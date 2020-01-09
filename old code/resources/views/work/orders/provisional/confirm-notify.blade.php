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
$orderId = $dataOrder->orderId();
?>
@extends('components.container.container-6')
@section('qc_container_content')
    <div class="qc_frm_work_orders_provision_confirm_wrap qc-padding-top-20 qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="qc-color-red text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                @if($updateStatus)
                    <h3>ĐƠN HÀNG ĐÃ ĐẶT THÀNH CÔNG</h3>
                    <b class="qc-font-size-20" style="color: brown;">Đã chuyển vào danh sách đơn hàng</b>
                @else
                    <h3>TÍNH NĂNG ĐANG CẬP NHẬT</h3>
                @endif
            </div>
            <div class="qc-padding-top-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                @if($updateStatus)
                    <div class="list-group">
                        <a href="{!! route('qc.work.orders.get') !!}"
                           class="qc-link list-group-item list-group-item-success">
                            Đến danh sách đơn hàng
                        </a>
                        <a href="{!! route('qc.work.orders.info.get',$orderId) !!}"
                           class="qc-link list-group-item list-group-item-info">
                            Quản lý đơn hàng
                        </a>
                        <a href="{!! route('qc.work.orders.provisional.view.get',$orderId) !!}"
                           class="qc-link list-group-item list-group-item-warning">
                            Xem chi tiết đơn hàng
                        </a>
                        <a href="{!! route('qc.work.orders.provisional.print.get', $orderId) !!}"
                           class="qc-link list-group-item list-group-item-danger">
                            In đơn hàng
                        </a>
                    </div>
                    <a class="btn btn-sm btn-default" onclick="qc_main.window_reload();">
                        Để sau
                    </a>
                @else
                    <a class="btn btn-sm btn-primary" onclick="qc_main.window_reload();">
                        Đóng
                    </a>
                @endif

            </div>
        </div>
    </div>

@endsection
