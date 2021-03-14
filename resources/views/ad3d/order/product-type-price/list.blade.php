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
$dataCompanyLogin = $modelStaff->companyLogin();
$indexHref = route('qc.ad3d.order.product_type_price.get');
?>
@extends('ad3d.order.product-type-price.index')
@section('titlePage')
    Bảng giá Loại sản phẩm
@endsection
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! route('qc.ad3d.order.product_type_price.get') !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <label class="qc-font-size-20">BẢNG GIÁ </label>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="qc_ad3d_list_content row"
                 data-href-edit="{!! route('qc.ad3d.order.product_type_price.edit.get') !!}"
                 data-href-del="{!! route('qc.ad3d.order.product_type_price.delete') !!}">
                <table class="table table-hover table-bordered">
                    <tr>
                        <td style="padding: 0;">
                            <select class="cbCompanyFilter form-control" name="cbCompanyFilter"
                                    data-href-filter="{!! $indexHref !!}">
                                @if($hFunction->checkCount($dataCompany))
                                    @foreach($dataCompany as $company)
                                        @if($dataCompanyLogin->checkParent())
                                            <option value="{!! $company->companyId() !!}"
                                                    @if($companyFilterId == $company->companyId()) selected="selected" @endif >{!! $company->name() !!}</option>
                                        @else
                                            @if($companyFilterId == $company->companyId())
                                                <option value="{!! $company->companyId() !!}">{!! $company->name() !!}</option>
                                            @endif
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                        </td>
                        <td style="padding: 0;">
                            <a class="form-control btn btn-primary qc-link-white-bold"
                               href="{!! route('qc.ad3d.order.product_type_price.add.get') !!}">
                                <i class="qc-font-size-16 glyphicon glyphicon-plus"></i>
                                THÊM
                            </a>
                        </td>
                        <td>
                            <a class="qc-link-red" title="Sao chép bảng giá từ công ty khác"
                               href="{!! route('qc.ad3d.order.product_type_price.copy.get') !!}">
                                <i class="qc-font-size-16 glyphicon glyphicon-download-alt"></i>
                                SAO CHÉP BẢNG GIÁ
                            </a>
                        </td>
                    </tr>
                    <tr style="background-color: black; color: yellow;">
                        <th>Tên</th>
                        <th class="text-right">Giá/Đơn vị</th>
                        <th>Ghi chú</th>
                    </tr>
                    <tr>
                        <td style="padding: 0;">
                            <div class="input-group">
                                <input type="text" class="textFilterName form-control" name="textFilterName"
                                       placeholder="Tìm theo tên" value="{!! $nameFilter !!}">
                                      <span class="input-group-btn">
                                            <button class="btn_filterName btn btn-default" type="button"
                                                    data-href="{!! $indexHref !!}">
                                                <i class="glyphicon glyphicon-search"></i>
                                            </button>
                                      </span>
                            </div>
                        </td>
                        <td class="text-right"></td>
                        <td class="text-center"></td>
                    </tr>
                    @if($hFunction->checkCount($dataProductTypePrice))
                        <?php
                        $perPage = $dataProductTypePrice->perPage();
                        $currentPage = $dataProductTypePrice->currentPage();
                        $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                        ?>
                        @foreach($dataProductTypePrice as $productTypePrice)
                            <?php
                            $priceId = $productTypePrice->priceId();
                            $productType = $productTypePrice->productType;
                            $n_o += 1;
                            ?>
                            <tr class="qc_ad3d_list_object @if($n_o%2) info @endif" data-object="{!! $priceId !!}">
                                <td>
                                    <em>{!! $n_o !!}). </em>
                                    <b>{!! $productType->name() !!}</b>
                                    <br/> &emsp;
                                    <a class="qc_edit qc-link" title="Sửa">
                                        <i class="glyphicon glyphicon-pencil"></i>
                                    </a>
                                    <span>|</span>
                                    <a class="qc_delete qc-link-red" title="Xóa">
                                        <i class="glyphicon glyphicon-trash"></i>
                                    </a>
                                </td>
                                <td class="text-right">
                                    <span style="color: blue;">{!! $hFunction->currencyFormat($productTypePrice->price()) !!}</span>
                                    <em> / </em>
                                    @if(!$hFunction->checkEmpty($productType->unit()))
                                        {!! $productType->unit() !!}
                                    @else
                                        <span>...</span>
                                    @endif
                                </td>
                                <td class="qc-color-grey">
                                    {!! $productTypePrice->note() !!}
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="text-center" colspan="3">
                                {!! $hFunction->page($dataProductTypePrice) !!}
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="3">
                                <span style="color: red;">CHƯA CÓ BẢNG GIÁ</span>
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
@endsection
