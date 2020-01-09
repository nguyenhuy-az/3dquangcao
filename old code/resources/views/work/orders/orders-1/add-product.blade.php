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
?>
<div class="qc_work_orders_product_add qc-margin-top-5 col-xs-12 col-sm-12 col-md-12 col-lg-12"
     style="border: 1px solid #d7d7d7; border-left: 5px solid brown;">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
            <div class="form-group form-group-sm qc-margin-none">
                <label>Loại sản phẩm: <i
                            class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                <select class="cbProductType form-control" name="cbProductType[]" style="height: 25px;">
                    <option value="">Chọn loại sản phẩm</option>
                    @if(count($dataProductType) > 0)
                        @foreach($dataProductType as $productType)
                            <option value="{!! $productType->typeId() !!}">{!! $productType->name() !!}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="col-xs-6 col-sm-12 col-md-3 col-lg-3">
            <div class="form-group form-group-sm qc-margin-none">
                <label>Rộng<em>(mm)</em>: <i
                            class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                <input class="txtWidth form-control" type="text" onkeyup="qc_main.showNumberInput(this);" name="txtWidth[]" style="height: 25px;"
                       placeholder="Chiều rộng sản phẩm" value="0">
            </div>
        </div>
        <div class="col-xs-6 col-sm-12 col-md-3 col-lg-3">
            <div class="form-group form-group-sm qc-margin-none">
                <label>Cao<em>(mm)</em>: <i
                            class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                <input class="txtHeight form-control" type="text" name="txtHeight[]"
                       onkeyup="qc_main.showNumberInput(this);" style="height: 25px;"
                       placeholder="Chiều cao sản phẩm" value="">
            </div>
        </div>
        <div class="col-xs-6 col-sm-12 col-md-3 col-lg-3">
            <div class="form-group form-group-sm qc-margin-none">
                <label>
                    Sâu<em>(mm)</em>:
                </label>
                <input class="form-control" type="text" onkeyup="qc_main.showNumberInput(this);" name="txtDepth[]"
                       style="height: 25px;"
                       placeholder="Chiều sâu sản phẩm" value="">
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
            <div class="form-group form-group-sm qc-margin-none">
                <label>
                    Giá/sản phẩm:
                    <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                </label>
                <input class="txtPrice form-control" type="text" name="txtPrice[]" placeholder="Gía trên một sản phẩm"
                       value="0" style="height:25px;">
            </div>
        </div>
        <div class="col-xs-6 col-sm-12 col-md-3 col-lg-3">
            <div class="form-group form-group-sm qc-margin-none">
                <label>Số lượng: <i
                            class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                <input class="txtAmount form-control" type="text" name="txtAmount[]" style="height: 25px;"
                       placeholder="Số lượng sản phẩm" value="1">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="form-group form-group-sm qc-margin-none">
                <label>Chi chú</label>
                <input type="text" class="txtDescription form-control" name="txtDescription[]"
                       placeholder="Chú thích sản phảm" value="" style="height: 25px;">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <a class="qc_delete qc-link-red" data-href="">
                Xóa
            </a>
        </div>
    </div>
</div>
