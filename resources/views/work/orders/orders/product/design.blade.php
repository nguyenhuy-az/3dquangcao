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
@extends('work.orders.index')
@section('titlePage')
    Thông tin thiết kế
@endsection
@section('qc_work_order_body')
    <div class="qc-padding-top-20 qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-10">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <a class=" qc-link-red" href="{!! $hFunction->getUrlReferer() !!}">
                <i class="qc-font-size-14 glyphicon glyphicon-backward"></i>
                <span class="qc-font-size-16" style="color: blue;">Trởlại</span>
            </a>
        </div>
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
                <div class="qc-container-table col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" style="margin-bottom: 0;">
                            <tr style="background-color: whitesmoke;">
                                <th colspan="10">
                                    <i class="qc-font-size-16 glyphicon glyphicon-pencil"></i>
                                    <b class="qc-color-red">DANH SÁCH THIẾT KẾ</b>
                                </th>
                            </tr>
                            <tr>
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
                                                <img style="margin: 10px; width: 70px; height: auto;"
                                                     title="Đang áp dụng"
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
                                                    <a class="qc_orders_product_design_apply_act qc-link-red"
                                                       title="Không sử dụng"
                                                       data-href="{!! route('qc.work.orders.product.design.apply.get',"$designId/0") !!}">
                                                        Vô hiệu
                                                    </a>
                                                @else
                                                    <a class="qc_orders_product_design_apply_act qc-link-green"
                                                       title="Sử dụng thiết kế"
                                                       data-href="{!! route('qc.work.orders.product.design.apply.get',"$designId/1") !!}">
                                                        Áp dụng
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
