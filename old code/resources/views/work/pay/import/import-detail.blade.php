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
@extends('work.index')
@section('qc_work_body')
    <div class="row">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed #C2C2C2;">
                <a class="qc-link-red" onclick="qc_main.page_back();">
                    <i class="glyphicon glyphicon-backward"></i> Trở lại
                </a>
                <h3>CHI TIẾT HÓA ĐƠN</h3>
            </div>
            @if(count($dataImportImage) > 0)
                <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        @foreach($dataImportImage as $importImage)
                            <div class="table-responsive">
                                <table class="table ">
                                    <tr>
                                        <td class="tex-center">
                                            <img class="qc-link" onclick="qc_main.rotateImage(this);" alt="..." title="Click xoay hình"
                                                 src="{!! $importImage->pathFullImage($importImage->name()) !!}"
                                                 style="max-width: 100%; border: 1px solid #d7d7d7;">
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        @endforeach
                    </div>
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
                <div class="qc-container-table row">
                    @if(count($dataImportDetail) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <tr style="background-color: whitesmoke;">
                                    <th class="text-center">STT</th>
                                    <th>Tên VT/DC</th>
                                    <th class="text-center">Làm sản phẩm</th>
                                    <th class="text-center">Phân loại</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-right">Thành tiền</th>
                                </tr>
                                @foreach($dataImportDetail as $importDetail)
                                    <?php
                                    $n_o = (isset($n_o)) ? $n_o + 1 : 1;
                                    $suppliesId = $importDetail->suppliesId();
                                    $toolId = $importDetail->toolId();
                                    $productId = $importDetail->productId();
                                    ?>
                                    <tr>
                                        <td class="text-center">
                                            {!! $n_o !!}
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
                                        </td>
                                        <td class="text-center">
                                            @if(!empty($productId))
                                                {!! $importDetail->product->productType->name() !!}
                                                <em>({!! $importDetail->product->order->name() !!})</em>
                                            @else
                                                <em>---</em>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if(empty($suppliesId) && empty($toolId))
                                                <em>Chưa phân loại</em>
                                            @else
                                                @if(!empty($suppliesId))
                                                    <em>Vật tư</em>
                                                @else
                                                    <em>Dụng cụ</em>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {!! $importDetail->amount() !!}
                                        </td>
                                        <td class="text-right qc-color-red">
                                            {!! $hFunction->currencyFormat($importDetail->totalMoney()) !!}
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="qc-color-red" style="border-top: 2px solid brown;">
                                    <td class="text-right" colspan="5">
                                        Tổng thanh toán
                                    </td>
                                    <td class="text-right" colspan="1">
                                        <b>{!! $hFunction->currencyFormat($totalMoney) !!}</b>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    @else
                        <div class="qc_ad3d_list_object qc-ad3d-list-object row">
                            <div class="qc-padding-top-5 qc-padding-bot-5 text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <em class="qc-color-red">Chưa thanh toán</em>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
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
