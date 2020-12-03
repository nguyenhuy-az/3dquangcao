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
$dataProductDesign = $dataProduct->productDesignInfoAll();
$dataOrder = $dataProduct->order;
?>
@extends('work.orders.orders.index')
@section('titlePage')
    Thiết kế sản phẩm
@endsection
@section('qc_work_order_order_body')
    <div class="qc-padding-top-20 qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-10">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <em>SẢN PHẨM:</em>
            <label class="qc-font-size-20">{!! $dataProduct->productType->name()  !!}</label>
            <br/>
            <em>ĐH:</em>
            <label>{!! $dataProduct->order->name()  !!}</label>
        </div>
        {{-- chi tiết thiết kế --}}
        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row qc-padding-top-10">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" style="margin-bottom: 0;">
                            <tr>
                                <th colspan="5" style="padding-top: 0; padding-bottom: 0;">
                                    <h5 class="qc-color-red">DANH SÁCH THIẾT KẾ SẢN PHẨM</h5>
                                </th>
                            </tr>
                            <tr style="background-color: black; color: yellow;">
                                <th class="text-center" style="width: 20px;">STT</th>
                                <th>Thiết kế</th>
                                <th class="text-center">Nhân viên</th>
                                <th class="text-center">Ngày</th>
                                <th class="text-center">Áp dụng</th>
                            </tr>
                            @if($hFunction->checkCount($dataProductDesign))
                                @foreach($dataProductDesign as $productDesign)
                                    <?php
                                    $designId = $productDesign->designId();
                                    ?>
                                    <tr>
                                        <td class="text-center">
                                            {!! $n_o = (isset($n_o))?$n_o + 1:1  !!}
                                        </td>
                                        <td>
                                            <a class="qc_work_order_product_design_image_view qc-link"
                                               data-href="{!! route('qc.work.orders.product_design.view.get', $designId) !!}">
                                                <img style="width: 70px; height: auto;" title="Đang áp dụng"
                                                     src="{!! $productDesign->pathSmallImage($productDesign->image()) !!}">
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            {!! $productDesign->staff->fullName() !!}
                                        </td>
                                        <td class="text-center">
                                            {!! $hFunction->convertDateDMYFromDatetime($productDesign->createdAt()) !!}
                                        </td>
                                        <td class="text-center">
                                            @if($dataOrder->checkStaffInput($modelStaff->loginStaffId()))
                                                @if($productDesign->checkApplyStatus())
                                                    <em style="color: grey;">Đang dùng</em>
                                                    <span>&nbsp;|&nbsp;</span>
                                                    <a class="qc_orders_product_design_apply_act qc-link-red"
                                                       title="Không sử dụng"
                                                       data-href="{!! route('qc.work.orders.product.design.apply.get',"$designId/0") !!}">
                                                        VÔ HIỆU
                                                    </a>
                                                @else
                                                    <em style="color: grey;">Đang tắt</em>
                                                    <span>&nbsp;|&nbsp;</span>
                                                    <a class="qc_orders_product_design_apply_act qc-link-green"
                                                       title="Sử dụng thiết kế"
                                                       data-href="{!! route('qc.work.orders.product.design.apply.get',"$designId/1") !!}">
                                                        ÁP DỤNG
                                                    </a>
                                                @endif
                                            @else
                                                <em class="qc-color-grey" >---</em>
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="qc-padding-top-10 text-center" colspan="5">
                                        <em class="qc-color-red">Không có thiết kế</em>
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
