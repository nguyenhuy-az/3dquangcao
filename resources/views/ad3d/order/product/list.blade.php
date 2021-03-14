<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:32 PM
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$dataStaffLogin = $modelStaff->loginStaffInfo();
$dataCompanyLogin = $modelStaff->companyLogin();
# dang nhap vao cty dang lam - cua minh
$actionStatus = true;
if ($companyFilterId != $dataCompanyLogin->companyId()) $actionStatus = false;
$indexHref = route('qc.ad3d.order.product.get');

?>
@extends('ad3d.order.product.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
             style="margin-bottom: 10px; padding-top : 2px;padding-bottom: 2px; border-bottom: 2px dashed brown;">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! $indexHref !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <label class="qc-font-size-20">SẢN PHẨM</label>
                    <em class="qc-color-red">({!! $totalProduct !!})</em>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <select class="cbCompanyFilter" name="cbCompanyFilter" style="margin-top: 5px; height: 25px;"
                            data-href-filter="{!! $indexHref !!}">
                        <option value="{!! $dataCompany->companyId() !!}">{!! $dataCompany->name() !!}</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 2px 0 2px 0;">
                    <form name="" action="">
                        <div class="row">
                            <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <select class="cbDayFilter" style="height: 25px;"
                                        data-href="{!! $indexHref !!}">
                                    <option value="100" @if((int)$dayFilter == 100) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($i =1;$i<= 31; $i++)
                                        <option value="{!! $i !!}"
                                                @if((int)$dayFilter == $i) selected="selected" @endif >{!! $i !!}</option>
                                    @endfor
                                </select>
                                <span>/</span>
                                <select class="cbMonthFilter" style="height: 25px;"
                                        data-href="{!! $indexHref !!}">
                                    <option value="100" @if((int)$monthFilter == 100) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($i =1;$i<= 12; $i++)
                                        <option value="{!! $i !!}"
                                                @if((int)$monthFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                                    @endfor
                                </select>
                                <span>/</span>
                                <select class="cbYearFilter" style="height: 25px;" data-href="{!! $indexHref !!}">
                                    <option value="100" @if((int)$yearFilter == 100) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($i =2017;$i<= 2050; $i++)
                                        <option value="{!! $i !!}"
                                                @if($yearFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                                    @endfor
                                </select>

                                {{--
                                    <a class="btn btn-sm btn-primary"
                                       href="{!! route('qc.ad3d.order.product.add.get') !!}">
                                        +
                                    </a>
                                --}}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="qc_ad3d_list_content qc-order-order-object row"
                 data-href-view="{!! route('qc.ad3d.order.product.view.get') !!}"
                 data-href-confirm="{!! route('qc.ad3d.order.product.confirm.get') !!}"
                 data-href-del="{!! route('qc.ad3d.order.product.delete') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: whitesmoke;">
                            <th class="text-center" style="width: 20px;">STT</th>
                            <th>Sản phảm</th>
                            <th>Đơn hàng</th>
                            <th class="text-center">Số lượng</th>
                            <th class="text-center">TG nhận</th>
                            <th class="text-center">TG Giao</th>
                            <th class="text-center">Thi công</th>
                            <th></th>
                        </tr>
                        <tr>
                            <td class="text-center" style="width: 20px;"></td>
                            <td></td>
                            <td style="padding: 0;">
                                <div class="input-group">
                                    <input type="text" class="textKeywordFilter form-control" name="textKeywordFilter"
                                           placeholder="Tìm theo tên đơn hàng" value="{!! $keywordFiler !!}">
                                      <span class="input-group-btn">
                                            <button class="btFilterAction btn btn-default" type="button"
                                                    data-href="{!! route('qc.ad3d.order.product.get') !!}">
                                                <i class="glyphicon glyphicon-search"></i>
                                            </button>
                                      </span>
                                </div>
                            </td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td style="padding: 0;">
                                <select class="cbFinishStatus form-control" data-href="{!! $indexHref !!}">
                                    <option value="2" @if($finishStatus == 2) selected="selected" @endif>
                                        Tất cả
                                    </option>
                                    <option value="1" @if($finishStatus == 1) selected="selected" @endif>
                                        Đã hoàn thanh
                                    </option>
                                    <option value="0" @if($finishStatus == 0) selected="selected" @endif>
                                        Đang xử lý
                                    </option>
                                </select>
                            </td>
                            <td class="text-center"></td>
                        </tr>
                        @if($hFunction->checkCount($dataProduct))
                            <?php
                            $perPage = $dataProduct->perPage();
                            $currentPage = $dataProduct->currentPage();
                            $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                            ?>
                            @foreach($dataProduct as $product)
                                <?php
                                $productId = $product->productId();
                                ?>
                                <tr class="qc_ad3d_list_object @if($n_o%2) info @endif" data-object="{!! $productId !!}">
                                    <td class="text-center">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td>
                                        {!! $product->productType->name() !!}
                                    </td>
                                    <td>
                                        <em class="qc-color-grey">{!! $product->order->name() !!}</em>
                                    </td>
                                    <td class="text-center">
                                        {!! $product->amount() !!}
                                    </td>
                                    <td class="text-center">
                                        {!! $hFunction->convertDateDMYFromDatetime($product->order->receiveDate()) !!}
                                    </td>
                                    <td class="text-center">
                                        {!! $hFunction->convertDateDMYFromDatetime($product->order->deliveryDate()) !!}
                                    </td>
                                    <td class="text-center">
                                        @if($product->checkCancelStatus())
                                            <em class="qc-color-grey">Đã hủy</em>
                                        @else
                                            @if($product->checkFinishStatus())
                                                <em class="qc-color-grey">Đã hoàn thành</em>
                                            @else
                                                @if($product->workAllocationActivityOfProduct())
                                                    <em style="color: blue;">Đang thi công</em>
                                                @else
                                                    <em style="color: red;">Chưa thi công</em>
                                                @endif
                                                {{--<a class="qc_confirm qc-link-green"
                                                   href="{!! route('qc.ad3d.order.product.work-allocation.add.get',$productId) !!}">
                                                    Phân việc
                                                </a>--}}
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <a class="qc_view qc-link-green" href="#">
                                            Chi tiết
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center qc-padding-top-20 qc-padding-bot-20" colspan="8">
                                    {!! $hFunction->page($dataProduct) !!}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="qc-padding-top-5 qc-padding-bot-5 text-center" colspan="8">
                                    <em class="qc-color-red">Không tìm thấy thông tin phù hợp</em>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
