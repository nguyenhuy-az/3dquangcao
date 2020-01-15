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
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <a class=" qc-link-red" onclick="qc_main.page_back();">
                <i class="qc-font-size-16 glyphicon glyphicon-backward"></i>
                <span class="qc-font-size-16" style="color: blue;">Trởlại</span>
            </a>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
            <h3>SAO CHÉP BẢNG GIÁ MỚI</h3>
        </div>
        <div class="qc-padding-top-20 col-sx-12 col-sm-12 col-md-12 col-lg-10">
            <form class="frmAd3dProductTypePriceCopy" name="frmAd3dProductTypePriceCopy" role="form" method="post"
                  action="{!! route('qc.ad3d.order.product_type_price.copy.post') !!}">
                @if (Session::has('notifyAdd'))
                    <div class="row">
                        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
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
                                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <span class="qc-color-red qc-font-size-16">
                                    HỆ THỐNG CHỈ SAO CHÉP NHỮNG LOẠI SẢN PHẨM CHƯA CÓ TRONG BẢNG GIÁ
                                </span>
                                </div>
                                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                    <button type="button" class="qc_save btn btn-sm btn-primary">
                                        Sao chép
                                    </button>
                                </div>
                            </div>
                        @endif
                        <div class="qc-ad3d-table-container row">
                            <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered">
                                        <tr style="background-color: whitesmoke;">
                                            <th class="text-center" style="width: 20px;">STT</th>
                                            <th>Tên</th>
                                            <th>Ghi chú</th>
                                            <th class="text-center">Đơn vị tính</th>
                                            <th class="text-right">Giá</th>

                                        </tr>
                                        @if($hFunction->checkCount($dataProductTypePrice))
                                            <?php
                                            $n_o = 0;
                                            ?>
                                            @foreach($dataProductTypePrice as $productTypePrice)
                                                <tr class="qc_store_select @if($productTypePrice->checkExistProductTypeOfCompany($companyLoginId, $productTypePrice->typeId())) danger @elseif($n_o%2) info @endif">
                                                    <td class="text-center">
                                                        {!! $n_o += 1 !!}
                                                    </td>
                                                    <td>
                                                        {!! $productTypePrice->productType->name() !!}
                                                    </td>
                                                    <td>
                                                        {!! $productTypePrice->note() !!}
                                                    </td>
                                                    <td class="text-center">
                                                        @if(!$hFunction->checkEmpty($productTypePrice->productType->unit()))
                                                            {!! $productTypePrice->productType->unit() !!}
                                                        @else
                                                            <span>...</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-right">
                                                        {!! $hFunction->currencyFormat($productTypePrice->price()) !!}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td class="qc-color-red" colspan="5">
                                                    <b>KHÔNG CÓ BẢNG GIÁ</b>
                                                </td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </form>
        </div>
    </div>
@endsection
