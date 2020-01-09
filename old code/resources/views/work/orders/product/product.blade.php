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

$orderId = $dataOrders->orderId();
$customerId = $dataOrders->customerId();
$dataProduct = $dataOrders->allProductOfOrder();
?>
@extends('work.index')
@section('titlePage')
    Sản phẩm
@endsection
<style type="text/css">
    .qc_work_list_content_object {
        border-bottom: 1px solid #d7d7d7;
    }

    .qc_work_list_content_object:hover {
        background-color: whitesmoke;
    }
</style>
@section('qc_work_body')
    <div class="row qc_work_orders_wrap">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6">
                        <b class="qc-font-size-20">{!! $dataOrders->name() !!}</b>
                    </div>
                    <div class="text-right col-sx-12 col-sm-12 col-md-4 col-lg-4">
                        <em class="qc-color-grey">Nhận:</em>
                        <b>{!! date('d/m/Y', strtotime($dataOrders->receiveDate())) !!}</b>
                        <em class="qc-color-grey">Giao:</em>
                        <b>{!! date('d/m/Y', strtotime($dataOrders->deliveryDate())) !!}</b>
                    </div>
                    <div class="text-right col-sx-12 col-sm-12 col-md-2 col-lg-2">
                        @if(!$dataOrders->checkCancelStatus())
                            <a class="qc-link-green"
                               href="{!! route('qc.work.orders.add.get',"$customerId/$orderId") !!}">
                                + Thêm SP
                            </a>
                        @else
                            <span class="qc-color-red">Đã hủy</span>
                        @endif
                    </div>
                </div>
            </div>
            {{-- chi tiêt --}}
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="qc_work_orders_product_list_content row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr style="background-color: whitesmoke;">
                                        <th class="text-center qc-padding-none">STT</th>
                                        <th class="qc-padding-none">Tên SP</th>
                                        <th class="qc-padding-none">Kích thước</th>
                                        <th class="text-center qc-padding-none">Số lượng</th>
                                        <th class="qc-padding-none">Thiết kê</th>
                                        <th class="qc-padding-none">Chú thích</th>
                                        <th class="text-right qc-padding-none">Giá/SP</th>
                                        <th class="text-right qc-padding-none">Ngày tạo</th>
                                        <th class="text-right qc-padding-none"></th>
                                    </tr>
                                    @if(count($dataProduct) > 0)
                                        <?php
                                        $n_o = 0;
                                        ?>
                                    @foreach($dataProduct as $product)
                                        <?php
                                        $productId = $product->productId();
                                        $designImage = $product->designImage();
                                        ?>
                                        <tr>
                                            <td class="text-center qc-padding-none">
                                                {!! $n_o+=1  !!}
                                            </td>
                                            <td class="qc-padding-none">
                                                {!! $product->productType->name()  !!}
                                            </td>
                                            <td class="qc-padding-none">
                                                {!! $product->width() !!} x {!! $product->height() !!}
                                                x {!! (empty($product->depth())?0:$product->depth()) !!} mm
                                            </td>
                                            <td class="text-center qc-padding-none">
                                                {!! $product->amount() !!}
                                            </td>
                                            <td class="qc-padding-none">
                                                @if(!empty($designImage))
                                                    <div style="position: relative; margin-right: 10px; width: 70px; height: 70px; padding: 5px 5px; ">
                                                        <a class="qc_work_order_product_design_image_view qc-link"
                                                           data-href="{!! route('qc.work.orders.product.design.view.get', $productId) !!}">
                                                            <img style="max-width: 100%; max-height: 100%;"
                                                                 src="{!! $product->pathSmallDesignImage($designImage) !!}">
                                                        </a>
                                                        {{--<a class="qc_work_order_product_design_image_delete qc-link"
                                                           data-href="#">
                                                            <i style="position: absolute; font-weight: bold; padding: 0 3px; color: red; top: 3px; right: 3px; border: 1px solid #d7d7d7;">x</i>
                                                        </a>--}}
                                                    </div>
                                                @else
                                                    @if(!$product->checkCancelStatus())
                                                        @if($product->checkFinishStatus())
                                                            <em>---</em>
                                                        @else
                                                            <a class="qc_work_order_product_design_image_add qc-link-green"
                                                               data-href="{!! route('qc.work.orders.product.design.add.get',$productId) !!}">
                                                                Thêm thiết kế
                                                            </a>
                                                        @endif
                                                    @else
                                                        <em>---</em>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="qc-padding-none">
                                                {!! $product->description()  !!}
                                            </td>
                                            <td class="text-right qc-padding-none">
                                                {!! $hFunction->currencyFormat($product->price()) !!}
                                            </td>
                                            <td class="text-right qc-padding-none">
                                                <b>{!! date('d/m/Y', strtotime($product->createdAt())) !!}</b>
                                            </td>
                                            <td class="text-right qc-padding-none">
                                                @if(!$product->checkCancelStatus())
                                                    @if($product->checkFinishStatus())
                                                        <em>Đã hoàn thành</em>
                                                    @else
                                                        <a class="qc_work_orders_product_confirm_act qc-link-green"
                                                           data-href="{!! route('qc.work.orders.product.confirm.get',$productId) !!}">
                                                            Báo hoàn thành
                                                        </a>
                                                        @if(!$dataOrders->checkFinishPayment())
                                                            <span>|</span>
                                                            <a class="qc_work_orders_product_cancel_act qc-link-green"
                                                               data-href="{!! route('qc.work.orders.product.cancel.get',$productId) !!}">Hủy</a>
                                                        @endif
                                                    @endif
                                                @else
                                                    <em class="qc-color-red">Đã hủy</em>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    @else
                                        <tr>
                                            <td class="text-right qc-padding-none" colspan="9">
                                                Không có sản phẩm
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                </div>
            </div>
        </div>
        <div class="text-center qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <button type="button" class="btn btn-sm btn-primary" onclick="qc_main.page_back();" >
                Đóng
            </button>
        </div>
    </div>
@endsection
