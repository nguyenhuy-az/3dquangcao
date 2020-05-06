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
@extends('work.work-allocation.index')
@section('titlePage')
    Sản phẩm
@endsection
@section('qc_work_allocation_body')
    <div class="row qc_work_allocation_construction_product_wrap">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            @include('work.work-allocation.menu')

            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="col-sx-12 col-sm-12 col-md-8 col-lg-8">
                        <em>Đơn hàng:</em>
                        <b class="qc-font-size-20 qc-color-red">{!! $dataOrders->name() !!}</b>
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
                <div class="qc_work_allocation_construction_product_list qc-container-table row">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr style="background-color: whitesmoke;">
                                <th class="text-center" style="width: 20px;">STT</th>
                                <th class="qc-padding-none">Tên SP</th>
                                <th class="text-center">Kích thước</th>
                                <th class="text-center">Thiết kế</th>
                                <th class="text-center">Ghi chú</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-center">Ngày thêm</th>
                                <th class="text-right">Trạng thái</th>
                            </tr>
                            @if($hFunction->checkCount($dataProduct))
                                <?php $n_o = 0; ?>
                                @foreach($dataProduct as $product)
                                    <?php
                                    $productId = $product->productId();
                                    $description = $product->description();
                                    $designImage = $product->designImage();
                                    # thiet ke dang ap dung
                                    $dataProductDesign = $product->productDesignInfoApplyActivity();
                                    if ($hFunction->getCountFromData($dataProductDesign) == 0) {
                                        # thiet ke sau cung
                                        $dataProductDesign = $product->productDesignInfoLast();
                                    }
                                    ?>
                                    <tr class="qc_work_list_content_object" data-object="{!! $orderId !!}">
                                        <td class="text-center">
                                            {!! $n_o+=1  !!}
                                        </td>
                                        <td>
                                            {!! $product->productType->name()  !!}
                                        </td>
                                        <td class="text-center">
                                            ({!! $product->width() !!} x {!! $product->height() !!}
                                            x {!! (empty($product->depth())?0:$product->depth()) !!})mm
                                        </td>
                                        <td class="text-center">

                                            @if($hFunction->checkCount($dataProductDesign))
                                                @if($dataProductDesign->checkApplyStatus())
                                                    <a class="qc_work_allocation_construct_product_design_image_view qc-link"
                                                       data-href="{!! route('qc.work.work_allocation.construction.product.design.view', $dataProductDesign->designId()) !!}">
                                                        <img style="width: 70px; height: auto; margin-bottom: 5px;"
                                                             title="Đang áp dụng"
                                                             src="{!! $dataProductDesign->pathSmallImage($dataProductDesign->image()) !!}">
                                                    </a>
                                                    <br/>
                                                @else
                                                    <a class="qc_work_allocation_construct_product_design_image_view qc-link"
                                                       data-href="{!! route('qc.work.work_allocation.construction.product.design.view', $dataProductDesign->designId()) !!}">
                                                        <img style="width: 70px; height: 70px; margin-bottom: 5px;"
                                                             title="Không được áp dụng"
                                                             src="{!! $dataProductDesign->pathSmallImage($dataProductDesign->image()) !!}">
                                                    </a>
                                                    <br/>
                                                @endif
                                            @else
                                                @if(!$hFunction->checkEmpty($designImage))
                                                    <a title="HÌNH ẢNH TỪ PHIÊN BẢNG CŨ - KHÔNG XEM FULL">
                                                        <img style="width: 70px; height: 70px; margin-bottom: 5px; "
                                                             src="{!! $product->pathSmallDesignImage($designImage) !!}">
                                                    </a>
                                                    <br/>
                                                @endif
                                            @endif

                                        </td>
                                        <td class="text-center">
                                            @if(!empty($description))
                                                {!! $description !!}
                                            @else
                                                <span class="qc-color-grey">---</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {!! $product->amount() !!}
                                        </td>
                                        <td class="text-center">
                                            {!! date('d/m/Y', strtotime($dataOrders->deliveryDate())) !!}
                                        </td>
                                        <td class="text-right">
                                            @if(!$product->checkCancelStatus())
                                                @if($product->checkFinishStatus())
                                                    <em>Đã hoàn thành</em>
                                                @else
                                                    @if($dataOrdersAllocation->checkActivity())
                                                        <a class="qc_confirm_finish_product_act qc-link-green"
                                                           data-href="{!! route('qc.work.work_allocation.construction.product.confirm.get',$productId) !!}">
                                                            Báo hoàn thành sản phẩm
                                                        </a>
                                                    @else
                                                        {{--khi huy ban giao don hang--}}
                                                        <em class="qc-color-grey">Đã kết thúc</em>
                                                    @endif
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="8">
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
                Về trang trước
            </a>
            <a class="btn btn-sm btn-default" href="{!! route('qc.work.work_allocation.construction.get') !!}">
                Về danh mục đơn hàng
            </a>
        </div>
    </div>
@endsection
