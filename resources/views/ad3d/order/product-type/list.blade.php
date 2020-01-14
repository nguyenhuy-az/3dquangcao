<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:32 PM
 */
/*
 *dataProductType
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$dataStaffLogin = $modelStaff->loginStaffInfo();
?>
@extends('ad3d.order.product-type.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
             style="margin-bottom: 10px; padding-top: 10px; padding-bottom: 10px; border-bottom: 2px dashed brown;">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <i class="qc-font-size-20 glyphicon glyphicon-list-alt"></i>
                    <label class="qc-font-size-20">LOẠI SẢN PHẨM</label>
                </div>
            </div>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 2px 0 2px 0; ">
                    <form name="" action="">
                        <div class="row">
                            {{--<div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <input class="col-xs-12" type="text" value="" placeholder="Tên công ty"
                                       style="height: 30px;">
                            </div>--}}
                            @if($dataStaffLogin->checkRootStatus())
                                <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <a class="btn btn-primary btn-sm"
                                       href="{!! route('qc.ad3d.order.product_type.add.get') !!}">
                                        <i class="glyphicon glyphicon-plus"></i>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            <div class="qc_ad3d_list_content row"
                 data-href-view="{!! route('qc.ad3d.order.product_type.view.get') !!}"
                 data-href-edit="{!! route('qc.ad3d.order.product_type.edit.get') !!}"
                 data-href-add-img="{!! route('qc.ad3d.order.product_type_img.add.get') !!}"
                 data-href-del="{!! route('qc.ad3d.order.product_type.delete') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: whitesmoke;">
                            <th class="text-center">STT</th>
                            <th class="text-center">Mã loại</th>
                            <th>Tên</th>
                            <th>Mô tả</th>
                            <th>Ảnh mẫu</th>
                            <th class="text-center">Đơn vị tính</th>
                            <th class="text-center">Áp dụng</th>
                            <th class="text-center">Duyệt</th>
                            <th></th>
                        </tr>
                        @if($hFunction->checkCount($dataProductType))
                            <?php
                            $perPage = $dataProductType->perPage();
                            $currentPage = $dataProductType->currentPage();
                            $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                            ?>
                            @foreach($dataProductType as $productType)
                                <?php
                                $typeId = $productType->typeId();
                                $typeCode = $productType->typeCode();
                                $unit = $productType->unit();
                                $description = $productType->description();
                                $dataProductTypeImage = $productType->infoProductTypeImage();
                                ?>
                                <tr class="qc_ad3d_list_object @if($n_o%2) info @endif" data-object="{!! $typeId !!}">
                                    <td class="text-center">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td class="text-center">
                                        @if(!empty($typeCode))
                                            {!! $typeCode !!}
                                        @else
                                            <em class="qc-color-grey">---</em>
                                        @endif
                                    </td>
                                    <td>
                                        {!! $productType->name() !!}
                                    </td>
                                    <td class="qc-color-grey">
                                        @if(!$hFunction->checkEmpty($description))
                                            {!! $description !!}
                                        @else
                                            <em class="qc-color-grey">---</em>
                                        @endif
                                    </td>
                                    <td>
                                        @if($hFunction->checkCount($dataProductTypeImage))
                                            @foreach($dataProductTypeImage as $productTypeImage)
                                                <div style="position: relative; float: left; margin: 5px 10px 5px 10px; width: 70px; height: 70px; border: 1px solid #d7d7d7;">
                                                    <a class="qc_image_view qc-link"
                                                       data-href="{!! route('qc.ad3d.order.product_type_img.view', $productTypeImage->imageId()) !!}">
                                                        <img style="max-width: 100%; max-height: 100%;"
                                                             src="{!! $productTypeImage->pathSmallImage($productTypeImage->name()) !!}">
                                                    </a>
                                                    <a class="qc_delete_image_action qc-link"
                                                       data-href="{!! route('qc.ad3d.order.product_type_img.delete', $productTypeImage->imageId()) !!}">
                                                        <i style="position: absolute; font-weight: bold; padding: 0 3px; color: red; top: 3px; right: 3px; border: 1px solid #d7d7d7;">x</i>
                                                    </a>
                                                </div>
                                            @endforeach
                                            <div class="text-center"
                                                 style="position: relative; float: left; margin: 5px 10px 5px 10px; width: 70px; height: 70px; border: 1px solid #d7d7d7;">
                                                <a class="qc-link-green qc_add_image_action">+Thêm ảnh</a>
                                            </div>
                                            <br/>
                                        @else
                                            <a class="qc-link-green-bold qc_add_image_action">+Thêm ảnh</a>
                                        @endif

                                    </td>
                                    <td class="text-center">
                                        @if(!$hFunction->checkEmpty($unit))
                                            {!! $unit !!}
                                        @else
                                            <em class="qc-color-grey">---</em>
                                        @endif
                                    </td>
                                    <td class="text-center qc-color-grey">
                                        @if($productType->checkApplyStatus())
                                            <i class="qc-color-red glyphicon glyphicon-ok-circle" title="Được sử dụng"></i>
                                        @else
                                            <i class="qc-color-grey glyphicon glyphicon-ok-circle" title="Không được sử dụng"></i>
                                        @endif
                                    </td>
                                    <td class="text-center qc-color-grey">
                                        @if($productType->checkConfirmStatus())
                                            <i class="qc-color-green glyphicon glyphicon-ok-circle" title="Đã duyệt"></i>
                                        @else
                                            <i class="qc-color-grey glyphicon glyphicon-ok-circle" title="Chưa duyệt"></i>
                                            <a class="qc_confirm_apply qc-link-green-bold" data-href="{!! route('qc.ad3d.order.product_type.confirm.get',$typeId) !!}" title="Click để xác nhận">
                                                Duyệt
                                            </a>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <a class="qc_view qc-link-green" href="#">
                                            Chi tiết
                                        </a>
                                        @if($dataStaffLogin->checkRootStatus())
                                            <span>|</span>
                                            <a class="qc_edit qc-link-green" href="#">Sửa</a>
                                            <span>|</span>
                                            <a class="qc_delete qc-link-green" href="#">Xóa</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center" colspan="9">
                                    {!! $hFunction->page($dataProductType) !!}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="text-center" colspan="9">
                                    Chưa có thông tin
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
