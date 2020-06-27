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
<div class="qc_work_import_supplies_tool_new_add qc-margin-top-5 col-xs-12 col-sm-12 col-md-12 col-lg-12"
     style="border: 1px solid #d7d7d7; border-left: 5px solid red;">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
            <label>Vật tư /Dụng cụ mới: </label><br/>
            <input class="txtSuppliesToolNew" type="text" name="txtSuppliesToolNew[]" placeholder="Tên vật tư mới" value="">
        </div>
        <div class="col-xs-6 col-sm-6 col-md-2 col-lg-2">
            <label>Số lượng: </label><br/>
            <input class="txtSuppliesToolNewAmount" type="number" name="txtSuppliesToolNewAmount[]" placeholder="Số lượng" value="1">
        </div>
        <div class="col-xs-6 col-sm-6 col-md-2 col-lg-2">
            <label>Thành tiền: </label><br/>
            <input class="txtSuppliesToolNewMoney" type="text" name="txtSuppliesToolNewMoney[]" placeholder="Nhập tiền" onkeyup="qc_main.showFormatCurrency(this);" value="">
        </div>
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <label>Sản phẩm: </label><br/>
            <select class="cbSuppliesToolNewProduct" name="cbSuppliesToolNewProduct[]" style="width: 100%;">
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
