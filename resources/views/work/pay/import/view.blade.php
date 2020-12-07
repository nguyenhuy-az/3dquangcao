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
$importDate = $dataImport->importDate();
# tong tien nhap
$totalMoney = $dataImport->totalMoneyOfImport();
# chi tiet nhap
$dataImportDetail = $dataImport->importDetailGetInfo();
?>
@extends('work.pay.import.index')
@section('qc_work_pay_import_body')
    <div class="row">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <h4 style="color: red;">CHI TIẾT HÓA ĐƠN</h4>
                <em class="qc-color-grey">Người mua: </em>
                <b style="color: blue;">{!! $dataImport->staffImport->lastName() !!}</b>
                <em style="color: grey;">Ngày </em>
                <b style="color: blue;">{!! date('d/m/Y', strtotime($importDate)) !!}</b>
            </div>
            <div class="row">
                @if(!$hFunction->checkEmpty($image))
                    <div class="col-sx-12 col-sm-6 col-md-6 col-lg-6">
                        <img class="media-object qc-link" alt="..." onclick="qc_main.rotateImage(this);"
                             style="width: 100%; border: 1px solid #d7d7d7;" title="Click xoay hình"
                             src="{!! $dataImport->pathFullImage($image) !!}">
                    </div>
                @endif
                {{-- chi tiêt --}}
                <div class="col-sx-12 col-sm-6 col-md-6 col-lg-6">
                    <div class="row">
                        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <tr style="background-color: black; color: yellow;">
                                        <th>Tên VT/DC</th>
                                        <th>Thành tiền</th>
                                        <th>Phân loại</th>
                                    </tr>
                                    @if($hFunction->checkCount($dataImportDetail))
                                        @foreach($dataImportDetail as $importDetail)
                                            <?php
                                            $n_o = isset($n_o) ? $n_o + 1 : 1;
                                            $detailId = $importDetail->detailId();
                                            $suppliesId = $importDetail->suppliesId();
                                            $toolId = $importDetail->toolId();
                                            $productId = $importDetail->productId();
                                            ?>
                                            <tr class="@if($n_o%2==0) info @endif">
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
                                                    @if(!empty($productId))
                                                        <br/>
                                                        <em style="color: grey;">{!! $importDetail->product->productType->name() !!}</em>
                                                        <br/>
                                                        <em style="color: grey;">({!! $importDetail->product->order->name() !!}
                                                            )</em>
                                                    @endif
                                                </td>
                                                <td>
                                                    <b style="color: red;">{!! $hFunction->currencyFormat($importDetail->totalMoney()) !!}</b>
                                                    <br/>
                                                    <em style="color: grey;">SL: {!! $importDetail->amount() !!}</em>
                                                </td>
                                                <td style="padding: 0;">
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
                                            </tr>
                                        @endforeach
                                        <tr class="qc-color-red" style="border-top: 2px solid brown;">
                                            <td class="text-right">
                                                Tổng thanh toán
                                            </td>
                                            <td class="text-right">
                                                <b>{!! $hFunction->currencyFormat($totalMoney) !!}</b>
                                            </td>
                                            <td></td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="3">
                                                <em class="qc-color-red">Không có thông tin</em>
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="qc-padding-top-5 qc-padding-bot-5 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <a href="{!! $hFunction->getUrlReferer() !!}">
                        <button type="button" class="btn btn-sm btn-primary">
                            ĐÓNG
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
