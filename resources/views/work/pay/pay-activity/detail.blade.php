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
                <div class="row">
                    <div class="text-right qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <em class="qc-color-grey">Ngày </em>
                        <b class="qc-color-red">{!! date('d/m/Y', strtotime($importDate)) !!}</b>
                    </div>
                    <div class="text-right qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        @if($dataImport->checkPay())
                            @if($dataImport->checkPayConfirmOfImport($importId))
                                <em class="qc-color-grey">Đã Nhận tiền</em>
                            @else
                                <a class="qc-color-grey">
                                    Chưa Xác nhận thanh toán
                                </a>
                            @endif
                        @else
                            <em>Chưa thanh toán</em>
                        @endif
                    </div>
                    <div class="text-right qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <em class="qc-color-grey">Tổng tiền: </em>
                        <b class="qc-color-red">{!! $hFunction->dotNumber($totalMoney) !!}</b>
                    </div>
                </div>
            </div>
            {{-- chi tiêt --}}
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    @if(count($dataImportDetail) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <tr style="background-color: whitesmoke;">
                                    <th class="text-center qc-padding-none">STT</th>
                                    <th class="qc-padding-none">Tên VT/DC</th>
                                    <th class="text-center qc-padding-none">Làm sản phẩm</th>
                                    <th class="text-center qc-padding-none">Phân loại</th>
                                    <th class="text-center qc-padding-none">Số lượng</th>
                                    <th class="text-right qc-padding-none">Thành tiền</th>
                                </tr>
                                @foreach($dataImportDetail as $importDetail)
                                    <?php
                                    $n_o = (isset($n_o)) ? $n_o + 1 : 1;
                                    $suppliesId = $importDetail->suppliesId();
                                    $toolId = $importDetail->toolId();
                                    $productId = $importDetail->productId();
                                    ?>
                                    <tr>
                                        <td class="text-center qc-padding-none">
                                            {!! $n_o !!}
                                        </td>
                                        <td class="qc-padding-none">
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
                                        <td class="text-center qc-padding-none">
                                            @if(!empty($productId))
                                                {!! $importDetail->product->productType->name() !!}
                                                <em>({!! $importDetail->product->order->name() !!})</em>
                                            @else
                                                <em>---</em>
                                            @endif
                                        </td>
                                        <td class="text-center qc-padding-none">
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
                                        <td class="text-center qc-padding-none">
                                            {!! $importDetail->amount() !!}
                                        </td>
                                        <td class="text-right qc-color-red qc-padding-none">
                                            {!! $hFunction->currencyFormat($importDetail->totalMoney()) !!}
                                        </td>
                                    </tr>
                                @endforeach
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
                    <button type="button" class="btn btn-sm btn-primary" onclick="window.history.back();">
                        Đóng
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
