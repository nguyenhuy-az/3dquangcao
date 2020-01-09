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
$loginStaffId = $dataStaffLogin->staffId();

#href
$productTypePrice_href_get = route('qc.work.product_type_price.get');
?>
@extends('work.index')
@section('qc_work_body')
    <div class="row qc_work_product_type_wrap">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <a class="qc-link-green-bold" href="{!! $productTypePrice_href_get !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <label class="qc-font-size-20">BẢNG BÁO GIÁ</label>
                </div>
            </div>
            {{-- chi tiêt --}}
            <div class="row qc-margin-bot-5">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="input-group">
                        <input type="text" class="qc_work_textNameFilter form-control" name="textNameFilter"
                               placeholder="Tìm theo tên" style="height: 25px;" value="{!! $nameFilter !!}">
                              <span class="input-group-btn">
                                    <button class="btFilterName btn btn-sm btn-default" type="button"
                                            style="height: 25px;"
                                            data-href="{!! $productTypePrice_href_get !!}">Tìm
                                    </button>
                              </span>
                    </div>
                </div>
            </div>
            <div class="qc_container_table row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr style="background-color: whitesmoke;">
                                <th class="text-center">STT</th>
                                <th>Loại sản phẩm</th>
                                <th>Mô tả SP</th>
                                <th class="text-right">Giá</th>
                                <th class="text-center">Đơn vị</th>
                                <th>Ghi chú</th>
                            </tr>
                            @if(count($dataProductTypePrice) > 0)
                                @foreach($dataProductTypePrice as $productTypePrice)
                                    <?php
                                    $priceId = $productTypePrice->priceId();
                                    $productType = $productTypePrice->productType;
                                    $dataProductTypeImage = $productType->infoProductTypeImage();
                                    ?>
                                    <tr>
                                        <td class="text-center">
                                            {!! $n_o = (isset($n_o)) ? $n_o + 1 : 1 !!}
                                        </td>
                                        <td>
                                            {!!  $productType->name() !!}
                                        </td>
                                        <td class="qc-color-grey">
                                            {!!  $productType->description() !!}
                                            <br/>
                                            @if(count($dataProductTypeImage) > 0)
                                                <a class="qc-link-red"
                                                   onclick="qc_main.toggle('#qc_image_container_{!! $priceId !!}');">
                                                    Xem ảnh mẫu
                                                </a>
                                                <div id="qc_image_container_{!! $priceId !!}"
                                                     class="qc-display-none col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    @foreach($dataProductTypeImage as $productTypeImage)
                                                        <div style="margin: 5px 0px 5px 0px; width: 100%;">
                                                            <img style="max-width: 100%;"
                                                                 src="{!! $productTypeImage->pathSmallImage($productTypeImage->name()) !!}">
                                                        </div>
                                                    @endforeach
                                                    <a class="qc-link-red"
                                                       onclick="qc_main.hide('#qc_image_container_{!! $priceId !!}');">
                                                        Đóng ảnh mẫu
                                                    </a>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-right qc-color-red">
                                            {!! $hFunction->currencyFormat($productTypePrice->price()) !!}
                                        </td>
                                        <td class="text-center">
                                            {!!  $productType->unit() !!}
                                        </td>
                                        <td class="qc-color-grey">
                                            {!!  $productTypePrice->note() !!}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center qc-color-red" colspan="6">
                                        Không có thông tin giá
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <a class="btn btn-sm btn-primary" href="{!! route('qc.work.home') !!}">Đóng</a>
                </div>
            </div>
        </div>
    </div>
@endsection
