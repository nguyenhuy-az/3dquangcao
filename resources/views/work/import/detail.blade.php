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
$image = $dataImport->image();
# tong tien hoa don
$totalMoney = $dataImport->totalMoneyOfImport();
$importDate = $dataImport->importDate();
# chi tiet nhan
$dataImportDetail = $dataImport->importDetailGetInfo();
?>
@extends('work.import.index')
@section('qc_work_import_body')
    <div class="row">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <h3 style="color: red;">CHI TIẾT HÓA ĐƠN</h3>
                <em class="qc-color-grey">Người mua: </em>
                <b style="color: blue;">{!! $dataImport->staffImport->lastName() !!}</b>
                <em style="color: grey;">Ngày </em>
                <b style="color: blue;">{!! date('d/m/Y', strtotime($importDate)) !!}</b>
            </div>
            @if(!$hFunction->checkEmpty($image))
                <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-6 col-md-6 col-lg-6">
                    <img class="qc-link" onclick="qc_main.rotateImage(this);" alt="..." title="Click xoay hình"
                         src="{!! $dataImport->pathFullImage($image) !!}"
                         style="width: 100%; border: 1px solid #d7d7d7;">
                </div>
            @endif
            {{-- chi tiêt --}}
            <div class="col-sx-12 col-sm-6 col-md-6 col-lg-6">
                <div class="row">
                    @if($hFunction->checkCount($dataImportDetail))
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <tr style="background-color: black; color: yellow;">
                                    <th>Tên VT/DC</th>
                                    <th> Thành tiền</th>
                                    <th>Làm sản phẩm</th>
                                </tr>
                                @foreach($dataImportDetail as $importDetail)
                                    <?php
                                    $n_o = (isset($n_o)) ? $n_o + 1 : 1;
                                    $suppliesId = $importDetail->suppliesId();
                                    $toolId = $importDetail->toolId();
                                    $productId = $importDetail->productId();
                                    ?>
                                    <tr>
                                        <td>
                                            <em>{!! $n_o !!}) </em>
                                            @if(empty($suppliesId) && empty($toolId))
                                                <b style="color: blue;">{!! $importDetail->newName() !!}</b>
                                            @else
                                                @if(!empty($suppliesId))
                                                    <b style="color: blue;">{!! $importDetail->supplies->name() !!}</b>
                                                @else
                                                    <b style="color: blue;">{!! $importDetail->tool->name() !!}</b>
                                                @endif
                                            @endif
                                            @if(empty($suppliesId) && empty($toolId))
                                                <br/>
                                                <em style="color: grey;">- Chưa phân loại</em>
                                            @else
                                                @if(!empty($suppliesId))
                                                    <br/>
                                                    <em style="color: grey;">- Vật tư</em>
                                                @else
                                                    <br/>
                                                    <em style="color: grey;">- Dụng cụ</em>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <b style="color: red;">{!! $hFunction->currencyFormat($importDetail->totalMoney()) !!}</b>
                                            <br/>
                                            <em style="color: grey;">Số Lượng: {!! $importDetail->amount() !!}</em>
                                        </td>
                                        <td>
                                            @if(!empty($productId))
                                                <span>{!! $importDetail->product->productType->name() !!}</span>
                                                <em>({!! $importDetail->product->order->name() !!})</em>
                                            @else
                                                <em>---</em>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                <tr style="border-top: 2px solid brown;">
                                    <td class="text-right">
                                        <b>Tổng thanh toán</b>
                                    </td>
                                    <td>
                                        <b style="font-size: 1.5em; color: red;">{!! $hFunction->currencyFormat($totalMoney) !!}</b>
                                    </td>
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                    @else
                        <div class="row">
                            <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <em class="qc-color-red">Không có thông tin mua</em>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
