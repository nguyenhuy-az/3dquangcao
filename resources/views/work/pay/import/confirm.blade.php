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
# lay trang thai thanh toan mac dinh
$getDefaultNotPay = $modelImport->getDefaultNotPay(); // chua thanh toan
$getDefaultHasPay = $modelImport->getDefaultHasPay(); // da thanh toan
?>
@extends('components.container.container-10')
@section('qc_container_content')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <form id="frm_work_import_confirm" role="form" method="post" enctype="multipart/form-data"
                  action="{!! route('qc.work.pay.import.confirm.post', $importId) !!}">
                <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed #C2C2C2;">
                        <h4 style="color: red;">DUYỆT HÓA ĐƠN</h4>
                        <em class="qc-color-grey">Người mua: </em>
                        <b style="color: blue;">{!! $dataImport->staffImport->lastName() !!}</b>
                        <em style="color: grey;">Ngày </em>
                        <b style="color: blue;">{!! date('d/m/Y', strtotime($importDate)) !!}</b>
                    </div>

                    @if(!$hFunction->checkEmpty($image))
                        <div class="col-sx-12 col-sm-4 col-md-4 col-lg-4">
                            <img class="media-object qc-link" alt="..." onclick="qc_main.rotateImage(this);"
                                 style="width: 100%; border: 1px solid #d7d7d7;" title="Click xoay hình"
                                 src="{!! $dataImport->pathFullImage($image) !!}">
                        </div>
                    @endif

                    {{-- chi tiêt --}}
                    <div class="col-sx-12 col-sm-8 col-md-8 col-lg-8">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <tr style="background-color: black; color: yellow;">
                                    <th>Tên VT/DC</th>
                                    <th class="text-right">Thành tiền</th>
                                    <th>Phân loại</th>
                                    <th>Cấp phát (Dụng cụ)</th>
                                    <th>Đơn vị</th>
                                </tr>
                                @if($hFunction->checkCount($dataImportDetail))
                                    @foreach($dataImportDetail as $importDetail)
                                        <?php
                                        $n_o = (isset($n_o)) ? $n_o + 1 : 1;
                                        $detailId = $importDetail->detailId();
                                        $suppliesId = $importDetail->suppliesId();
                                        $toolId = $importDetail->toolId();
                                        $productId = $importDetail->productId();
                                        ?>
                                        <tr class="@if($n_o%2 == 0) info @endif">
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
                                                <input type="hidden" name="txtDetail[]" value="{!! $detailId !!}">
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
                                                <select class="cbNewSuppliesTool form-control"
                                                        name="cbNewSuppliesTool[]"
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
                                            <td style="padding: 0;">
                                                <select class="cbAllocationStatus form-control"
                                                        name="cbAllocationStatus[]">
                                                    <option value="2" selected="selected">Dùng cấp phát</option>
                                                    <option value="1">Dùng chung</option>
                                                </select>
                                            </td>
                                            <td class="text-center qc-color-red" style="padding: 0;">
                                                @if(empty($suppliesId) && empty($toolId))
                                                    <input class="txtUnit form-control" type="text" name="txtUnit[]"
                                                           placeholder="Đơn vị tính"
                                                           value="{!! $importDetail->newUnit() !!}">
                                                @else
                                                    @if(!empty($suppliesId))
                                                        <input class="txtUnit form-control" type="text" name="txtUnit[]"
                                                               readonly
                                                               value="{!! $importDetail->supplies->unit() !!}"
                                                               style="border: 0;">
                                                    @else
                                                        <input class="txtUnit form-control" type="text" name="txtUnit[]"
                                                               readonly
                                                               value="{!! $importDetail->tool->unit() !!}"
                                                               style="border: 0;">
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="qc-color-red" style="border-top: 2px solid brown;">
                                        <td>
                                            Tổng thanh toán
                                        </td>
                                        <td>
                                            <b>{!! $hFunction->currencyFormat($totalMoney) !!}</b>
                                        </td>
                                        <td colspan="3"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <label style="color: red;">THANH TOÁN:</label>
                                            <select class="cbPayStatus form-control" name="cbPayStatus">
                                                <option value="{!! $getDefaultHasPay !!}">Duyệt và thanh toán</option>
                                                <option value="{!! $getDefaultNotPay !!}">Duyệt và chưa thanh toán</option>
                                                <option value="2">KHÔNG DUYỆT</option>
                                            </select>
                                        </td>
                                        <td colspan="3">
                                            <div class="form-group form-group-sm">
                                                <label>Ghi chú:</label>
                                                <input type="text" class="form-control" name="txtConfirmNote"
                                                       placeholder="Nhập nội dung ghi chú" value="">
                                            </div>
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="5">
                                            <em class="qc-color-red">Không có thông tin</em>
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                        <div class="row">
                            <div class="qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <span style="color: red;">Dụng cụ dùng chung sẽ không cấp phát cho người mua</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="qc-padding-top-10 qc-padding-bot-10 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <button type="button" class="qc_save btn btn-sm btn-primary">
                                    XÁC NHẬN
                                </button>
                                <a type="button" class="qc_container_close btn btn-sm btn-default">ĐÓNG</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
@endsection
