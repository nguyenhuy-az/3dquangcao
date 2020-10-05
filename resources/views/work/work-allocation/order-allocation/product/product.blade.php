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
@extends('work.work-allocation.order-allocation.index')
@section('titlePage')
    Sản phẩm
@endsection
@section('qc_work_allocation_body')
    <div class="row qc_work_allocation_construction_product_wrap">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <a class="btn btn-sm btn-primary" onclick="qc_main.page_back();">
                Về trang trước
            </a>
        </div>
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            @include('work.work-allocation.menu')

            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <span style="color:grey;">ĐƠN HÀNG: </span>
                    <label style="font-size: 20px; color: red;">{!! $dataOrders->name() !!}</label>
                    <br/>
                    <em class="qc-color-grey">- Thời gian được giao:</em>
                    <b class="qc-color-red">{!! date('d/m/Y', strtotime($dataOrdersAllocation->allocationDate())) !!}</b>
                    <br/>
                    <em class="qc-color-grey">- Thời hạn hoàn thành:</em>
                    <b class="qc-color-red">{!! date('d/m/Y', strtotime($dataOrdersAllocation->receiveDeadline())) !!}</b>
                    <br/>
                    <em style="color:grey;"> - Thông tin thi công:</em>
                    <span>{!! $dataOrders->constructionAddress() !!}
                        - ĐT: {!! $dataOrders->constructionPhone() !!}
                        - tên: {!! $dataOrders->constructionContact() !!}
                    </span>
                </div>
            </div>
            {{-- chi tiêt --}}
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="qc_work_allocation_construction_product_list row">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr style="background-color: black; color: yellow;">
                                <th class="text-center" style="width: 20px;">STT</th>
                                <th>TÊN SP</th>
                                <th>KÍCH THƯỚC</th>
                                <th>THIẾT KẾ</th>
                                <th>GHI CHÚ</th>
                                <th class="text-center">SỐ LƯỢNG</th>
                                <th class="text-center">NGÀY THÊM</th>
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
                                            <b>
                                                {!! $product->productType->name()  !!}
                                            </b>
                                            <br/>
                                            @if(!$product->checkCancelStatus())
                                                <i class="glyphicon glyphicon-arrow-right"></i>
                                                @if($product->checkFinishStatus())
                                                    <em style="color: blue;">Đã hoàn thành</em>
                                                @else
                                                    @if($dataOrdersAllocation->checkActivity())
                                                        <a class="qc_confirm_finish_product_act qc-link-green"
                                                           data-href="{!! route('qc.work.work_allocation.order_allocation.product.confirm.get',$productId) !!}">
                                                            BÁO HOÀN THÀNH
                                                        </a>
                                                    @else
                                                        {{--khi huy ban giao don hang--}}
                                                        <em class="qc-color-grey">Đã kết thúc</em>
                                                    @endif
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            ({!! $product->width() !!} x {!! $product->height() !!}
                                            x {!! (empty($product->depth())?0:$product->depth()) !!})mm
                                        </td>
                                        <td>
                                            @if($hFunction->checkCount($dataProductDesign))
                                                @if($dataProductDesign->checkApplyStatus())
                                                    <a class="qc_work_allocation_construct_product_design_image_view qc-link"
                                                       data-href="{!! route('qc.work.work_allocation.order_allocation.product.design.view', $dataProductDesign->designId()) !!}">
                                                        <img style="width: 70px; height: auto; margin-bottom: 5px;"
                                                             title="Đang áp dụng"
                                                             src="{!! $dataProductDesign->pathSmallImage($dataProductDesign->image()) !!}">
                                                    </a>
                                                    <br/>
                                                @else
                                                    <a class="qc_work_allocation_construct_product_design_image_view qc-link"
                                                       data-href="{!! route('qc.work.work_allocation.order_allocation.product.design.view', $dataProductDesign->designId()) !!}">
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
                                                @else
                                                    <em>Chưa có thiết kế</em>
                                                @endif
                                            @endif

                                        </td>
                                        <td>
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
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="7">
                                        <em class="qc-color-red">Không có sản phẩm</em>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
