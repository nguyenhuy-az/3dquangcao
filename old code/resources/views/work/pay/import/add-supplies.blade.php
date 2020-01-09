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
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$dataStaffLogin = $modelStaff->loginStaffInfo();
$dataProduct = $dataStaffLogin->getInfoProductOfWorkAllocationOf();
?>
<div class="qc_work_import_supplies_add qc-margin-top-5 col-xs-12 col-sm-12 col-md-12 col-lg-12"
     style="border: 1px solid #d7d7d7; border-left: 5px solid black;">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
            <label>Loại vật tư:</label><br/>
            <select class="cbImportSupplies" name="cbImportSupplies[]" style="width: 100%;">
                <option value="">Chọn vật tư</option>
                @if(count($dataSupplies) > 0)
                    @foreach($dataSupplies as $supplies)
                        <option value="{!! $supplies->suppliesId() !!}">{!! $supplies->name() !!}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-2 col-lg-2">
            <label>Số lượng: </label><br/>
            <input class="txtSuppliesAmount" type="number" name="txtSuppliesAmount[]" style="width: 100%;"
                   placeholder="Số lượng" value="1">
        </div>
        <div class="col-xs-6 col-sm-6 col-md-2 col-lg-2">
            <label>Thành tiền: </label><br/>
            <input class="txtSuppliesMoney" type="text" name="txtSuppliesMoney[]" style="width: 100%;"
                   placeholder="Nhập tiền" onkeyup="qc_main.showFormatCurrency(this);" value="">
        </div>
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <label>Sản phẩm: </label><br/>
            <select class="cbSuppliesProduct" name="cbSuppliesProduct[]" style="width: 100%;">
                @if(count($dataProduct) > 0)
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
        <div class="text-right col-xs-12 col-sm-12 col-md-1 col-lg-1">
            <a class="qc_delete qc-link-red" data-href="">
                Hủy
            </a>
        </div>
    </div>
</div>
