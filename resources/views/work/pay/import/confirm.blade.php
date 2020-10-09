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
$totalMoney = $dataImport->totalMoneyOfImport();
$importDate = $dataImport->importDate();
$dataImportDetail = $dataImport->infoDetailOfImport();
$dataImportImage = $dataImport->importImageInfoOfImport();
?>
@extends('components.container.container-10')
@section('qc_container_content')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <form id="frm_work_import_confirm" role="form" method="post" enctype="multipart/form-data"
                  action="{!! route('qc.work.pay.import.confirm.post', $importId) !!}">
                <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed #C2C2C2;">
                        <h3>DUYỆT HÓA ĐƠN</h3>
                        <b class="qc-color-red" style="font-size: 1.5em;" >{!! $dataImport->staffImport->lastName() !!}</b>
                        <b class="qc-color-red" style="font-size: 1.5em;"> -- {!! date('d/m/Y', strtotime($importDate)) !!}</b>
                    </div>
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        @if(!$hFunction->checkEmpty($image))
                            <img class="media-object qc-link" alt="..." onclick="qc_main.rotateImage(this);"
                                 style="max-width: 30%; border: 1px solid #d7d7d7;"  title="Click xoay hình"
                                 src="{!! $dataImport->pathFullImage($image) !!}">

                        @else
                            Không có Ảnh HĐ
                        @endif
                    </div>
                    {{-- chi tiêt --}}
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <tr style="background-color: black; color: yellow;">
                                    <th class="text-center" style="width: 20px;">STT</th>
                                    <th >Tên VT/DC</th>
                                    <th>Làm sản phẩm</th>
                                    <th>Phân loại</th>
                                    <th>Cấp phát (Dụng cụ)</th>
                                    <th class="text-center">Số lượng</th>
                                    <th>Đơn vị tính</th>
                                    <th class="text-right">Thành tiền</th>
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
                                        <tr class="@if($n_o%2) info @endif">
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
                                                <input type="hidden" name="txtDetail[]" value="{!! $detailId !!}">
                                            </td>
                                            <td >
                                                @if(!empty($productId))
                                                    {!! $importDetail->product->productType->name() !!}
                                                    <em style="color: brown;">({!! $importDetail->product->order->name() !!}
                                                        )</em>
                                                @else
                                                    <em>---</em>
                                                @endif
                                            </td>
                                            <td style="padding: 0;">
                                                <select class="cbNewSuppliesTool form-control" name="cbNewSuppliesTool[]"
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
                                                <select class="cbAllocationStatus form-control" name="cbAllocationStatus[]">
                                                    <option value="2" selected="selected">Dùng cấp phát</option>
                                                    <option value="1">Dùng chung</option>
                                                </select>
                                            </td>
                                            <td class="text-center qc-color-red">
                                                {!! $importDetail->amount() !!}
                                            </td>
                                            <td class="text-center qc-color-red" style="padding: 0;">
                                                @if(empty($suppliesId) && empty($toolId))
                                                    <input class="txtUnit form-control" type="text" name="txtUnit[]"
                                                           placeholder="Đơn vị tính" value="{!! $importDetail->newUnit() !!}">
                                                @else
                                                    @if(!empty($suppliesId))
                                                        <input class="txtUnit form-control" type="text" name="txtUnit[]" readonly
                                                               value="{!! $importDetail->supplies->unit() !!}"
                                                               style="border: 0;">
                                                    @else
                                                        <input class="txtUnit form-control" type="text" name="txtUnit[]" readonly
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
                                    <tr>
                                        <td colspan="3">
                                            <label style="color: red;">THANH TOÁN:</label>
                                            <select class="cbPayStatus form-control" name="cbPayStatus">
                                                <option value="1">Duyệt và thanh toán</option>
                                                <option value="0">Duyệt và chưa thanh toán</option>
                                                <option value="2">Không hợp lệ</option>
                                            </select>
                                        </td>
                                        <td colspan="6">
                                            <div class="form-group form-group-sm">
                                                <label>Ghi chú:</label>
                                                <input type="text" class="form-control" name="txtConfirmNote"
                                                       placeholder="Nhập nội dung ghi chú" value="">
                                            </div>
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td class="text-center qc-color-red">
                                            <em class="qc-color-red">Không có thông tin</em>
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>
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
                                Xác nhận
                            </button>
                            <a type="button" class="qc_container_close btn btn-sm btn-default">Đóng</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
@endsection
