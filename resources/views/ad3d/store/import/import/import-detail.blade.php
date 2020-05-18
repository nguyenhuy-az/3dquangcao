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

$importId = $dataImport->importId();
$totalMoney = $dataImport->totalMoneyOfImport();
$importDate = $dataImport->importDate();
$dataImportDetail = $dataImport->infoDetailOfImport();
$dataImportImage = $dataImport->importImageInfoOfImport();
?>
@extends('ad3d.store.import.import.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed #C2C2C2; padding: 0;">
                <h3>CHI TIẾT HÓA ĐƠN</h3>
            </div>
            @if(count($dataImportImage) > 0)
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    @foreach($dataImportImage as $importImage)
                        <div class="table-responsive">
                            <table class="table ">
                                <tr>
                                    <td class="tex-center">
                                        <img class="qc-link" onclick="qc_main.rotateImage(this);" alt="..."
                                             title="Click xoay hình"
                                             src="{!! $importImage->pathFullImage($importImage->name()) !!}"
                                             style="max-width: 30%; border: 1px solid #d7d7d7;">
                                    </td>
                                </tr>
                            </table>
                        </div>
                    @endforeach
                </div>
            @endif
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row qc-padding-top-5 qc-padding-bot-5">
                    <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <b class="qc-color-red">{!! $dataImport->staffImport->lastName() !!}</b>
                    </div>
                    <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <em class="qc-color-grey">Ngày </em>
                        <b class="qc-color-red">{!! date('d/m/Y', strtotime($importDate)) !!}</b>
                    </div>
                </div>
            </div>
            {{-- chi tiêt --}}
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="qc-ad3d-table-container row">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr style="background-color: whitesmoke;">
                                <th class="text-center">STT</th>
                                <th>Tên VT/DC</th>
                                <th class="text-center">Làm sản phẩm</th>
                                <th class="text-center">Phân loại</th>
                                <th class="text-center">Cấp phát</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-center">Đơn vị tính</th>
                                <th class="text-right">Thành tiền</th>
                            </tr>
                            @if($hFunction->checkCount($dataImportDetail))
                                @foreach($dataImportDetail as $importDetail)
                                    <?php
                                    $n_o = 0;
                                    $detailId = $importDetail->detailId();
                                    $suppliesId = $importDetail->suppliesId();
                                    $toolId = $importDetail->toolId();
                                    $productId = $importDetail->productId();
                                    ?>
                                    <tr class="@if($n_o%2) info @endif">
                                        <td class="text-center">
                                            {!! $n_o = $n_o + 1 !!}
                                        </td>
                                        <td>
                                            @if(empty($suppliesId) && empty($toolId))
                                                {!! $importDetail->newName() !!}
                                            @else
                                                @if(!empty($suppliesId))
                                                    {!! $importDetail->supplies->name() !!}
                                                @else
                                                    {!! $importDetail->tool->name() !!}
                                                @endif
                                            @endif
                                            <input type="hidden" name="txtDetail[]" value="{!! $detailId !!}">
                                        </td>
                                        <td class="text-center">
                                            @if(!empty($productId))
                                                {!! $importDetail->product->productType->name() !!}
                                                <em style="color: brown;">({!! $importDetail->product->order->name() !!}
                                                    )</em>
                                            @else
                                                <em>---</em>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <select class="cbNewSuppliesTool" name="cbNewSuppliesTool[]"
                                                    style="border: none;">
                                                @if(empty($suppliesId) && empty($toolId))
                                                    <option value="">Chọn phân loại</option>
                                                    <option value="1">Vật tư</option>
                                                    <option value="2">Dụng cụ</option>
                                                @else
                                                    <option value="3">
                                                        @if(!empty($suppliesId))
                                                            Vật tư
                                                        @else
                                                            Dụng cụ
                                                        @endif
                                                    </option>
                                                @endif
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <select class="cbAllocationStatus" name="cbAllocationStatus[]"
                                                    style="border: none;">
                                                <option value="1" selected="selected">Cấp phát</option>
                                                <option value="2">Không cấp</option>
                                            </select>
                                        </td>
                                        <td class="text-center qc-color-red">
                                            {!! $importDetail->amount() !!}
                                        </td>
                                        <td class="text-center qc-color-red">
                                            @if(empty($suppliesId) && empty($toolId))
                                                <input class="txtUnit" type="text" name="txtUnit[]"
                                                       placeholder="Đơn vị tính" value="">
                                            @else
                                                @if(!empty($suppliesId))
                                                    <input class="txtUnit" type="text" name="txtUnit[]" readonly
                                                           value="{!! $importDetail->supplies->unit() !!}"
                                                           style="border: 0;">
                                                @else
                                                    <input class="txtUnit" type="text" name="txtUnit[]" readonly
                                                           value="{!! $importDetail->tool->unit() !!}"
                                                           style="border: 0;">
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-right qc-color-red">
                                            {!! $hFunction->currencyFormat($importDetail->totalMoney()) !!}
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="qc-color-red" style="border-top: 2px solid brown;">
                                    <td class="text-right" colspan="7">
                                        Tổng thanh toán
                                    </td>
                                    <td class="text-right" colspan="1">
                                        <b>{!! $hFunction->currencyFormat($totalMoney) !!}</b>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td class="text-center qc-color-red qc-padding-none">
                                        <em class="qc-color-red">Không có thông tin</em>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="qc-padding-top-5 qc-padding-bot-5 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <a href="{!! $hFunction->getUrlReferer() !!}">
                        <button type="button" class="btn btn-sm btn-primary">
                            Đóng
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
