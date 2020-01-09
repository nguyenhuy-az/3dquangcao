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
@extends('ad3d.order.product-type-price.index')
@section('titlePage')
    Bảng giá Loại sản phẩm
@endsection
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
             style="margin-bottom: 10px; padding-top: 10px; padding-bottom: 10px; border-bottom: 2px dashed brown;">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! route('qc.ad3d.order.product_type_price.get') !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <label class="qc-font-size-20">BẢNG GIÁ LOẠI SẢN PHẨM</label>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <select class="cbCompanyFilter" name="cbCompanyFilter" style="margin-top: 5px; padding: 3px 0;"
                            data-href-filter="{!! route('qc.ad3d.order.product_type_price.get') !!}">
                        @if(count($dataCompany)> 0)
                            @foreach($dataCompany as $company)
                                @if($dataStaffLogin->checkRootManage())
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
                </div>
            </div>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 2px 0 2px 0; ">
                    <form name="" action="">
                        <div class="row">
                            <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="input-group">
                                    <input type="text" class="textFilterName form-control" name="textFilterName"
                                           placeholder="Tìm theo tên" style="height: 25px;" value="{!! $nameFilter !!}">
                                      <span class="input-group-btn">
                                            <button class="btn_filterName btn btn-sm btn-default" type="button"
                                                    style="height: 25px;"
                                                    data-href="{!! route('qc.ad3d.order.product_type_price.get') !!}">Tìm
                                            </button>
                                      </span>
                                </div>
                            </div>
                            <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <a class="btn btn-primary btn-sm"
                                   href="{!! route('qc.ad3d.order.product_type_price.add.get') !!}">
                                    <i class="glyphicon glyphicon-plus">Thêm</i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="qc_ad3d_list_content row"
                 data-href-edit="{!! route('qc.ad3d.order.product_type_price.edit.get') !!}"
                 data-href-del="{!! route('qc.ad3d.order.product_type_price.delete') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: whitesmoke;">
                            <th class="text-center">STT</th>
                            <th >Tên</th>
                            <th class="text-right">Giá</th>
                            <th class="text-center">Đơn vị</th>
                            <th >Ghi chú</th>
                            <th></th>
                        </tr>
                        @if(count($dataProductTypePrice) > 0)
                            <?php
                            $perPage = $dataProductTypePrice->perPage();
                            $currentPage = $dataProductTypePrice->currentPage();
                            $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                            ?>
                            @foreach($dataProductTypePrice as $productTypePrice)
                                <?php
                                $priceId = $productTypePrice->priceId();
                                $productType = $productTypePrice->productType;
                                ?>
                                <tr class="qc_ad3d_list_object" data-object="{!! $priceId !!}">
                                    <td class="text-center">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td>
                                        {!! $productType->name() !!}
                                    </td>
                                    <td class="text-right">
                                        {!! $hFunction->currencyFormat($productTypePrice->price()) !!}
                                    </td>
                                    <td class="text-center">
                                        {!! $productType->unit() !!}
                                    </td>
                                    <td class="qc-color-grey">
                                        {!! $productTypePrice->note() !!}
                                    </td>
                                    <td class="text-right">
                                        {{--@if($dataStaffLogin->checkRootStatus())--}}
                                        <a class="qc_edit qc-link-green" href="#">Sửa</a>
                                        <span>|</span>
                                        <a class="qc_delete qc-link-green" href="#">Xóa</a>
                                        {{--@endif--}}
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center" colspan="6">
                                    {!! $hFunction->page($dataProductTypePrice) !!}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="qc-color-red text-center" colspan="6">
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
