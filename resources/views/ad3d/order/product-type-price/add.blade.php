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
?>
@extends('ad3d.order.product-type-price.index')
@section('titlePage')
    Thêm Bảng giá Loại sản phẩm
@endsection
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <h3 style="color: red;">THÊM BẢNG GIÁ MỚI</h3>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <form class="frmAdd" name="frmAdd" role="form" method="post"
                  action="{!! route('qc.ad3d.order.product_type_price.add.post') !!}">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        @if (Session::has('notifyAdd'))
                            <div class="form-group form-group-sm text-center qc-color-red qc-font-size-16">
                                {!! Session::get('notifyAdd') !!}
                                <?php
                                Session::forget('notifyAdd');
                                ?>
                            </div>
                        @endif
                    </div>

                </div>
                {{-- danh sach san pham --}}
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        <table class="table table-hover table-bordered">
                            <tr>
                                <td colspan="5" style="padding: 0;">
                                    <select class="cbCompany form-control" name="cbCompany">
                                        <option value="{!! $dataCompany->companyId() !!}">
                                            {!! $dataCompany->name() !!}
                                        </option>
                                    </select>
                                </td>
                            </tr>
                            <tr style="background-color: whitesmoke;">
                                <th class="text-center" style="width: 20px;">STT</th>
                                <th>Tên</th>
                                <th class="text-center">Đơn vị tính</th>
                                <th class="text-center" style="width: 150px;">Giá</th>
                                <th>Ghi chú</th>
                            </tr>
                            @if($hFunction->checkCount($dataProductType))
                                @foreach($dataProductType as $productType)
                                    <?php
                                    $typeId = $productType->typeId();
                                    $n_o = (isset($n_o)) ? $n_o + 1 : 1;
                                    ?>
                                    <tr class="qc_store_select">
                                        <td class="text-center">
                                            {!! $n_o !!}
                                            <input class="qc_product_type" type="hidden" name="txtProductType[]"
                                                   value="{!! $typeId !!}">
                                        </td>
                                        <td>
                                            <b>{!! $productType->name() !!}</b>
                                        </td>
                                        <td class="text-center">
                                            @if(!$hFunction->checkEmpty($productType->unit()))
                                                {!! $productType->unit() !!}
                                            @else
                                                <span>...</span>
                                            @endif
                                        </td>
                                        <td style="padding: 0;">
                                            <input class="txtPrice text-right form-control"
                                                   onkeyup="qc_main.showFormatCurrency(this);" type="text"
                                                   name="txtPrice[]" value="0">
                                        </td>
                                        <td style="padding: 0;">
                                            <input class="txtNote form-control" type="text" name="txtNote[]"
                                                   placeholder="Ghi chú"
                                                   value="">
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="5">
                                        Không sản phẩm chưa báo giá <br/>
                                        <button type="button" class="btn btn-sm btn-default"
                                                onclick="qc_main.page_back();">
                                            ĐÓNG
                                        </button>
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="3" style="padding: 0;">
                                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                    <button type="submit" class="qc_save btn btn-sm btn-primary form-control">
                                        THÊM
                                    </button>
                                </td>
                                <td colspan="2" style="padding: 0;">
                                    <a type="button" class="btn btn-sm btn-default form-control" href="{!! route('qc.ad3d.order.product_type_price.get') !!}">
                                        ĐÓNG
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
