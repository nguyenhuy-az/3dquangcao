<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 *
 * dataProductType
 */
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();

$orderName = $dataOrders->name();
$orderDiscount = $dataOrders->discount();
$orderReceiveStaff = $dataOrders->staffReceiveId();
$orderCode = $dataOrders->orderCode();
# thong tin khach hang

$href = route('work.orders.edit.addProduct.post', $dataOrders->orderId());
$returnHref = route('qc.work.orders.info.get', $dataOrders->orderId()); # them san pham -> quan ly san pham
?>
@extends('work.orders.index')
@section('titlePage')
    Thêm sản phẩm
@endsection
@section('qc_work_order_body')
    <div class="row qc_work_orders_wrap">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <h4>THÊM SẢN PHẨM</h4>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <form id="frmWorkOrdersEditAddProduct" role="form" method="post" enctype="multipart/form-data" action="{!! $href !!}">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <div class="form-group form-group-sm qc-margin-none">
                            <em>Đơn hàng:</em>
                            <b>{!! $dataOrders->name() !!}</b>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <div class="form-group form-group-sm qc-margin-none">
                            <em>Khách hàng:</em>
                            <b>{!! $dataOrders->customer->name() !!}</b>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <div class="form-group form-group-sm qc-margin-none">
                            <em>Điên thoại:</em>
                            <b>{!! $dataOrders->customer->phone() !!}</b>
                        </div>
                    </div>
                </div>
                {{-- sản phẩm --}}
                <div class=" col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 qc-padding-none"
                             style="border-bottom: 1px solid grey;">
                            <i class="glyphicon glyphicon-pencil qc-color-green"></i>
                            <span class="qc-color-red qc-font-size-16">Sản phẩm</span>
                        </div>
                    </div>
                    <div id="qc_work_orders_edit_add_product_wrap" class="row">
                        @include('work.orders.orders.add-product')
                    </div>
                </div>
                <div class="row">
                    <div class="qc-padding-top-20 qc-padding-bot-20  qc-border-none text-left col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <a class="qc_orders_edit_product_add_act qc-link-green"
                           data-href="{!! route('qc.work.orders.edit.product.get') !!}">
                            <i class="glyphicon glyphicon-plus"></i>
                            Thêm sản phẩm
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="qc-padding-top-20 qc-padding-bot-20  qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <input type="hidden" name="txtReturnHref" value="{!! $returnHref !!}">
                        <button type="button" class="qc_save btn btn-primary btn-sm"> Thêm</button>
                        <a class="btn btn-default btn-sm" type="button" onclick="qc_main.page_back();">
                            Đóng
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
