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
$dataStaffLogin = $modelStaff->loginStaffInfo();
$loginStaffId = $dataStaffLogin->staffId();
#$totalMoneyOrder = $modelOrders->totalMoneyOfListOrder($dataOrders);
$hrefIndex = route('qc.work.orders.get');
$manageStatus = false;
if ($dataStaffLogin->checkBusinessDepartmentAndManageRank()) $manageStatus = true;
?>
@extends('work.orders.index')
@section('titlePage')
    Danh sách đơn hàng
@endsection
@section('qc_work_order_body')
    <div id="qc_work_orders_wrap" class="row qc_work_orders_wrap">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="padding-bottom: 5px;">
                <a class="btn btn-sm btn-primary" onclick="qc_main.page_back();">
                    Về trang trước
                </a>
            </div>
            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    @include('work.orders.menu')
                </div>
            </div>
            {{-- chi tiêt --}}
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="min-height: 1500px;">
                @yield('qc_work_order_order_body')
            </div>
        </div>
    </div>
@endsection
