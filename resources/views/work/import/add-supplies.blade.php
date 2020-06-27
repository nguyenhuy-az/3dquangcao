<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 1/4/2018
 * Time: 5:41 PM
 *
 * dataProductType
 *
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$dataStaffLogin = $modelStaff->loginStaffInfo();
$dataProduct = $dataStaffLogin->getInfoProductOfWorkAllocationOf();
?>
<div class="qc_work_import_supplies_add qc-margin-top-10 col-xs-12 col-sm-12 col-md-12 col-lg-12"
     style="border: 1px solid #d7d7d7; border-left: 5px solid black;">
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
            <div class="form-group form-group-sm qc-margin-none">
                <label>Loại vật tư:</label>
                <input type="text" class="txtImportSupplies form-control" name="txtImportSupplies[]"
                       data-href-check-name="{!! route('qc.work.orders.add.product_type.check.name') !!}"
                       placeholder="Nhập vật tư" value="">
                <div class="qc_import_add_supplies_suggestions_wrap col-xs-12 col-sm-12 col-md-12 col-lg-12"
                     style="border: 1px solid #d7d7d7; display: none;">
                    <a class='qc_import_add_supplies_suggestions_close pull-right qc-link-red'>X</a>

                    <div class="row">
                        <div class="qc_import_add_supplies_suggestions_content col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        </div>
                    </div>
                </div>
                {{--<select class="cbImportSupplies form-control" name="cbImportSupplies[]" style="width: 100%;">
                    <option value="">Chọn vật tư</option>
                    @if(count($dataSupplies) > 0)
                        @foreach($dataSupplies as $supplies)
                            <option value="{!! $supplies->suppliesId() !!}">{!! $supplies->name() !!}</option>
                        @endforeach
                    @endif
                </select>--}}
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-2 col-lg-2">
            <div class="form-group form-group-sm qc-margin-none">
                <label>Số lượng: </label>
                <input class="txtSuppliesAmount form-control" type="number" name="txtSuppliesAmount[]"
                       style="width: 100%;"
                       placeholder="Số lượng" value="1">
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-2 col-lg-2">
            <div class="form-group form-group-sm qc-margin-none">
                <label>Thành tiền: </label>
                <input class="txtSuppliesMoney form-control" type="text" name="txtSuppliesMoney[]" style="width: 100%;"
                       placeholder="Nhập tiền" onkeyup="qc_main.showFormatCurrency(this);" value="">
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
            <div class="form-group form-group-sm qc-margin-none">
                <label>Sản phẩm: </label>
                <select class="cbSuppliesProduct form-control" name="cbSuppliesProduct[]" style="width: 100%;">
                    @if($hFunction->checkCount($dataProduct))
                        <option value="0">Chọn sản phẩm</option>
                        @foreach($dataProduct as $product)
                            <option value="{!! $product->productId() !!}">
                                {!! $product->productType->name() !!} - ({!! $product->order->name() !!})
                            </option>
                        @endforeach
                    @else
                        <option value="0">Không có sản phẩm</option>
                    @endif
                </select>
            </div>
        </div>
        <div class="text-right col-xs-12 col-sm-12 col-md-1 col-lg-1">
            <div class="form-group form-group-sm qc-margin-none">
                <a class="qc_delete qc-link-red" data-href="">
                    <i class="qc-font-size-20 glyphicon glyphicon-remove"></i>
                </a>
            </div>
        </div>
    </div>
</div>
