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
        <div class="qc-padding-bot-20 col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <a class="qc-link-green-bold" href="{!! $productTypePrice_href_get !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <label class="qc-font-size-20" style="color: red;">BẢNG GIÁ</label>
                </div>
            </div>
            {{-- chi tiêt --}}
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="input-group">
                        <input type="text" class="qc_work_textNameFilter form-control" name="textNameFilter"
                               placeholder="Tìm theo tên" style="height: 30px;" value="{!! $nameFilter !!}">
                              <span class="input-group-btn">
                                    <button class="btFilterName btn btn-sm btn-default" type="button"
                                            style="height: 30px;"
                                            data-href="{!! $productTypePrice_href_get !!}">Tìm
                                    </button>
                              </span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr style="background-color: black; color: yellow;">
                                <th>Loại sản phẩm</th>
                                <th>Mô tả SP</th>
                                <th>Ghi chú</th>
                            </tr>
                            @if($hFunction->checkCount($dataProductTypePrice))
                                @foreach($dataProductTypePrice as $productTypePrice)
                                    <?php
                                    $priceId = $productTypePrice->priceId();
                                    $productType = $productTypePrice->productType;
                                    $dataProductTypeImage = $productType->infoProductTypeImage();
                                    $n_o = (isset($n_o)) ? $n_o + 1 : 1;
                                    ?>
                                    <tr>
                                        <td>
                                            <em>{!! $n_o !!}). </em>
                                            <b>{!!  $productType->name() !!}</b>
                                            <br/>&emsp;
                                            <span style="color: red;">
                                                {!! $hFunction->currencyFormat($productTypePrice->price()) !!}
                                            </span>
                                            <em>/</em>
                                            <span style="color: grey;">{!!  $productType->unit() !!}</span>
                                        </td>
                                        <td class="qc-color-grey">
                                            {!!  $productType->description() !!}
                                            <br/>
                                            @if($hFunction->checkCount($dataProductTypeImage))
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
                                        <td class="qc-color-grey">
                                            {!!  $productTypePrice->note() !!}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center qc-color-red" colspan="3">
                                        Không có thông tin giá
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <a class="btn btn-sm btn-primary" href="{!! route('qc.work.home') !!}">Đóng</a>
                </div>
            </div>
        </div>
    </div>
@endsection
