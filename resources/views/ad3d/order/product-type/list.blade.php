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
$hrefIndex = route('qc.ad3d.order.product-type.get');
?>
@extends('ad3d.order.product-type.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <a class="qc-link-green-bold" href="{!! $hrefIndex !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <label class="qc-font-size-20" style="color: red;">LOẠI SẢN PHẨM</label>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    @if($dataStaffLogin->checkRootStatus())
                        <a class="btn btn-primary btn-sm form-control"
                           href="{!! route('qc.ad3d.order.product_type.add.get') !!}">
                            <i class="glyphicon glyphicon-plus qc-font-size-16"></i>
                            <span class="qc-font-size-14">THÊM</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="qc_ad3d_list_content row"
                 data-href-view="{!! route('qc.ad3d.order.product_type.view.get') !!}"
                 data-href-edit="{!! route('qc.ad3d.order.product_type.edit.get') !!}"
                 data-href-add-img="{!! route('qc.ad3d.order.product_type_img.add.get') !!}"
                 data-href-del="{!! route('qc.ad3d.order.product_type.delete') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: black; color: yellow;">
                            <th>Tên</th>
                            <th style="max-width: 400px !important;">Mô tả</th>
                            <th>Ảnh mẫu</th>
                            <th>Danh mục thi công</th>
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
                                # danh muc thi cong
                                $dataConstructionWorkSelected = $productType->constructionWorkInfo();
                                $n_o += 1;
                                ?>
                                <tr class="qc_ad3d_list_object @if($n_o%2) info @endif" data-object="{!! $typeId !!}">
                                    <td>
                                        <em>{!! $n_o !!}). </em>
                                        <label>{!! $productType->name() !!}</label>
                                        <br/>&emsp;
                                        <em style="color: grey;">Bảo hành:</em>
                                        <span>{!! $productType->warrantyTime() !!} tháng</span>
                                        &emsp;
                                        <em style="color: grey;">Đơn vị tính:</em>
                                        @if($hFunction->checkEmpty($unit))
                                            <span>Chưa xác định</span>
                                        @else
                                            <span>{!! $unit !!}</span>
                                        @endif
                                        <br/>&emsp;
                                        <a class="qc_view qc-link" title="Xem chi tiết">
                                            <i class="glyphicon glyphicon-eye-open"></i>
                                        </a>
                                        <span>&nbsp;|&nbsp;</span>
                                        <a class="qc_edit qc-link-green" href="#" title="Sửa">
                                            <i class="glyphicon glyphicon-pencil"></i>
                                        </a>
                                        <span>&nbsp;|&nbsp;</span>
                                        <a class="qc_delete qc-link-red" href="#" title="Xóa">
                                            <i class="glyphicon glyphicon-trash"></i>
                                        </a>
                                        @if(!$productType->checkConfirmStatus())
                                            <span>&nbsp;|&nbsp;</span>
                                            <a class="qc_confirm_apply qc-link-red-bold"
                                               data-href="{!! route('qc.ad3d.order.product_type.confirm.get',$typeId) !!}"
                                               title="Click để xác nhận">
                                                DUYỆT
                                            </a>
                                        @else
                                            @if(!$productType->checkApplyStatus())
                                                <span>&nbsp;|&nbsp;</span>
                                                <em>Không áp dụng</em>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="qc-color-grey" style="max-width: 400px;">
                                        @if(!$hFunction->checkEmpty($description))
                                            {!! $description !!}
                                        @else
                                            <em class="qc-color-grey">---</em>
                                        @endif
                                    </td>
                                    <td style="padding: 5px;">
                                        @if($hFunction->checkCount($dataProductTypeImage))
                                            @foreach($dataProductTypeImage as $productTypeImage)
                                                <div style="position: relative; float: left; margin-left: 5px; width: 70px; height: 70px; border: 1px solid #d7d7d7;">
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
                                                <a class="qc-link-green qc_add_image_action">+THÊM ẢNH</a>
                                            </div>
                                            <br/>
                                        @else
                                            <a class="qc-link-green-bold qc_add_image_action">+THÊM ẢNH</a>
                                        @endif

                                    </td>
                                    <td style="max-width: 400px;">
                                        @if($hFunction->checkCount($dataConstructionWorkSelected))
                                            @foreach($dataConstructionWorkSelected as $constructionWorkSelected)
                                                <span>{!! $constructionWorkSelected->name() !!},</span>
                                            @endforeach
                                        @else
                                            <em class="qc-color-grey">Không có</em>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center" colspan="4">
                                    {!! $hFunction->page($dataProductType) !!}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="text-center" colspan="4">
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
