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
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
            <h3>THÊM BẢNG GIÁ MỚI</h3>
        </div>
        <div class="qc-padding-top-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <form class="frmAdd" name="frmAdd" role="form" method="post"
                  action="{!! route('qc.ad3d.order.product_type_price.add.post') !!}">
                <div class="row">
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
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
                <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding: 2px 0 2px 0; ">
                            <select class="cbCompany" name="cbCompany">
                                <option value="{!! $dataCompany->companyId() !!}">
                                    {!! $dataCompany->name() !!}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="qc-ad3d-table-container row">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <tr style="background-color: whitesmoke;">
                                    <th class="text-center" style="width: 20px;">STT</th>
                                    <th>Tên</th>
                                    <th class="text-center" >Đơn vị tính</th>
                                    <th class="text-center" style="width: 150px;">Giá</th>
                                    <th>Ghi chú</th>
                                </tr>
                                @if($hFunction->checkCount($dataProductType))
                                    @foreach($dataProductType as $productType)
                                        <?php
                                        $typeId = $productType->typeId();
                                        ?>
                                        <tr class="qc_store_select">
                                            <td class="text-center">
                                                {!! $n_o = (isset($n_o))?$n_o + 1: 1 !!}
                                                <input class="qc_product_type" type="hidden" name="txtProductType[]"
                                                       value="{!! $typeId !!}">
                                            </td>
                                            <td>
                                                {!! $productType->name() !!}
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
                                                <input class="txtNote form-control" type="text" name="txtNote[]" placeholder="Ghi chú"
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
                                                Đóng
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <button type="submit" class="qc_save btn btn-sm btn-primary">
                            Đồng ý
                        </button>
                        <a href="{!! route('qc.ad3d.order.product_type_price.get') !!}">
                            <button type="button" class="btn btn-sm btn-default">
                                Đóng
                            </button>
                        </a>

                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
