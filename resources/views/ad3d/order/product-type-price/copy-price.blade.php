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
$dataStaffLogin = $modelStaff->loginStaffInfo();
$companyLoginId = $dataStaffLogin->companyId();
if ($hFunction->checkCount($dataCompanySelected)) {
    $companySelectedId = $dataCompanySelected->companyId();
    $dataProductTypePrice = $dataCompanySelected->productTypePriceInfoActivity();
} else {
    $companySelectedId = $hFunction->setNull();
    $dataProductTypePrice = $hFunction->setNull();
}
?>
@extends('ad3d.order.product-type-price.index')
@section('titlePage')
    Thêm Bảng giá Loại sản phẩm
@endsection
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <button class="btn btn-sm btn-primary qc-link-white-bold" onclick="qc_main.page_back();">
                <i class="qc-font-size-16 glyphicon glyphicon-backward"></i>
                <span class="qc-font-size-16">TRỞ LẠI</span>
            </button>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
            <h3 style="color: red;">SAO CHÉP BẢNG GIÁ MỚI</h3>
        </div>
        <div class="qc-padding-top-20 col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <form class="frmAd3dProductTypePriceCopy" name="frmAd3dProductTypePriceCopy" role="form" method="post"
                  action="{!! route('qc.ad3d.order.product_type_price.copy.post') !!}">
                @if (Session::has('notifyAdd'))
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm text-center">
                                <span class="qc-color-red qc-font-size-16">{!! Session::get('notifyAdd') !!}</span>
                                <?php
                                Session::forget('notifyAdd');
                                ?>
                                <br/>
                                <a class="btn btn-primary"
                                   href="{!! route('qc.ad3d.order.product_type_price.get') !!}">
                                    <span class="qc-font-size-16">Đóng</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- danh sach san pham --}}
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group form-group-sm">
                                <label>
                                    Chọn công ty sao chép
                                </label>
                                <select class="cbCompanyCopy form-control" name="cbCompanyCopy"
                                        data-href="{!! route('qc.ad3d.order.product_type_price.copy.get') !!}">
                                    <option value="">Chọn công ty</option>
                                    @if($hFunction->checkCount($dataCompany))
                                        @foreach($dataCompany as $company)
                                            @if($dataStaffLogin->companyId() != $company->companyId())
                                                <option @if($companySelectedId == $company->companyId()) selected="selected"
                                                        @endif value="{!! $company->companyId() !!}">
                                                    {!! $company->name() !!}
                                                </option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                        </div>
                    </div>
                    @if($hFunction->checkCount($dataCompanySelected))
                        @if($hFunction->checkCount($dataProductTypePrice))
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <span class="qc-color-red qc-font-size-16">
                                    HỆ THỐNG CHỈ SAO CHÉP NHỮNG LOẠI SẢN PHẨM CHƯA CÓ TRONG BẢNG GIÁ
                                </span>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                    <button type="button" class="qc_save btn btn-sm btn-primary">
                                        SAO CHÉP
                                    </button>
                                </div>
                            </div>
                        @endif
                        <div class="row">
                            <div class="qc-padding-top-10 qc-padding-bot-20 qc-border-none col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <table class="table table-hover table-bordered">
                                    <tr style="background-color: whitesmoke;">
                                        <th>Sản phẩm</th>
                                        <th class="text-right" style="width: 170px;">
                                            Giá/Đơn vị tính
                                        </th>
                                        <th>Ghi chú</th>
                                    </tr>
                                    @if($hFunction->checkCount($dataProductTypePrice))
                                        @foreach($dataProductTypePrice as $productTypePrice)
                                            <?php
                                            $n_o = (isset($n_o)) ? $n_o + 1 : 1;
                                            ?>
                                            <tr class="qc_store_select @if($productTypePrice->checkExistProductTypeOfCompany($companyLoginId, $productTypePrice->typeId())) danger @elseif($n_o%2) info @endif">
                                                <td>
                                                    <em>{!! $n_o !!}). </em>
                                                    <b>{!! $productTypePrice->productType->name() !!}</b>
                                                </td>
                                                <td class="text-right">
                                                    <span style="color: blue;">
                                                        {!! $hFunction->currencyFormat($productTypePrice->price()) !!}
                                                    </span>
                                                    <em>/</em>
                                                    @if(!$hFunction->checkEmpty($productTypePrice->productType->unit()))
                                                        {!! $productTypePrice->productType->unit() !!}
                                                    @else
                                                        <span>...</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <em style="color: grey;">
                                                        {!! $productTypePrice->note() !!}
                                                    </em>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td class="qc-color-red" colspan="3">
                                                <b>KHÔNG CÓ BẢNG GIÁ</b>
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    @endif
                @endif
            </form>
        </div>
    </div>
@endsection
