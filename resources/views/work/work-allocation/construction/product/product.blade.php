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

$dataOrders = $dataOrdersAllocation->orders;
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
    <div class="row qc_work_allocation_construction_wrap">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            @include('work.work-allocation.work-allocation-menu')

            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="col-sx-12 col-sm-12 col-md-8 col-lg-8">
                        <b class="qc-font-size-20">{!! $dataOrders->name() !!}</b>
                    </div>
                    <div class="text-right col-sx-12 col-sm-12 col-md-4 col-lg-4">
                        <em class="qc-color-grey">Nhận:</em>
                        <b class="qc-color-red">{!! date('d/m/Y', strtotime($dataOrdersAllocation->allocationDate())) !!}</b>
                        <em class="qc-color-grey">Giao:</em>
                        <b class="qc-color-red">{!! date('d/m/Y', strtotime($dataOrdersAllocation->receiveDeadline())) !!}</b>
                    </div>
                </div>
            </div>
            {{-- chi tiêt --}}
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="qc_work_allocation_construction_product_list row">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr style="background-color: whitesmoke;">
                                <th class="text-center qc-padding-none">STT</th>
                                <th class="qc-padding-none">Tên SP</th>
                                <th class="text-center qc-padding-none">Kích thước</th>
                                <th class="text-center qc-padding-none">Thiết kế</th>
                                <th class="text-center qc-padding-none">Ghi chú</th>
                                <th class="text-center qc-padding-none">Số lượng</th>
                                <th class="text-center qc-padding-none">Ngày thêm</th>
                                <th class="text-right qc-padding-none">Trạng thái</th>
                            </tr>
                            @if(count($dataProduct) > 0)
                                <?php $n_o = 0; ?>
                                @foreach($dataProduct as $product)
                                    <?php
                                    $productId = $product->productId();
                                    $description = $product->description();
                                    $productDesignImage = $product->designImage();
                                    ?>
                                    <tr class="qc_work_list_content_object" data-object="{!! $orderId !!}">
                                        <td class="text-center qc-padding-none">
                                            {!! $n_o+=1  !!}
                                        </td>
                                        <td class="qc-padding-none">
                                            {!! $product->productType->name()  !!}
                                        </td>
                                        <td class="text-center  qc-padding-none">
                                            ({!! $product->width() !!} x {!! $product->height() !!}
                                            x {!! (empty($product->depth())?0:$product->depth()) !!})mm
                                        </td>
                                        <td class="text-center qc-padding-none">
                                            @if(!empty($productDesignImage))
                                                <div style="position: relative; margin-right: 10px; width: 70px; height: 70px; padding: 5px 5px; ">
                                                    <a class="qc_work_allocation_product_design_image_view qc-link"
                                                       data-href="{!! route('qc.work.work_allocation.product_design_image.get', $productId) !!}">
                                                        <img style="max-width: 100%; max-height: 100%;"
                                                             src="{!! $product->pathSmallDesignImage($productDesignImage) !!}">
                                                    </a>
                                                </div>
                                            @else
                                                <span class="qc-color-grey">---</span>
                                            @endif
                                        </td>
                                        <td class="text-center qc-padding-none">
                                            @if(!empty($description))
                                                {!! $description !!}
                                            @else
                                                <span class="qc-color-grey">---</span>
                                            @endif
                                        </td>
                                        <td class="text-center qc-padding-none">
                                            {!! $product->amount() !!}
                                        </td>
                                        <td class="text-center qc-padding-none">
                                            {!! date('d/m/Y', strtotime($dataOrders->deliveryDate())) !!}
                                        </td>
                                        <td class="text-right qc-padding-none">
                                            @if(!$product->checkCancelStatus())
                                                @if($product->checkFinishStatus())
                                                    <em>Đã hoàn thành</em>
                                                @else
                                                    <a class="qc_confirm_act qc-link-green"
                                                       data-href="{!! route('qc.work.work_allocation.construction.product.confirm.get',$productId) !!}">
                                                        Báo hoàn thành
                                                    </a>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                   <td class="text-center qc-padding-none" colspan="8">
                                       <em class="qc-color-red">Không có sản phẩm</em>
                                   </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <a class="btn btn-sm btn-primary" onclick="qc_main.page_back();">
                Đóng
            </a>
        </div>
    </div>
@endsection
