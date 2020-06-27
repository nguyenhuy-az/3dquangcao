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
?>
<div class="qc_work_import_tool_add qc-margin-top-10 col-xs-12 col-sm-12 col-md-12 col-lg-12"
     style="border: 1px solid #d7d7d7; border-left: 5px solid blue;">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <div class="form-group form-group-sm qc-margin-none">
                <label>Loại công cụ:</label>
                <select class="cbImportTool form-control" name="cbImportTool[]">
                    <option value="">Chọn dụng cụ</option>
                    @if(count($dataTool) > 0)
                        @foreach($dataTool as $tool)
                            <option value="{!! $tool->toolId() !!}">{!! $tool->name() !!}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-2 col-lg-2">
            <div class="form-group form-group-sm qc-margin-none">
                <label>Số lượng: </label>
                <input class="txtToolAmount form-control" type="number" name="txtToolAmount[]"
                       placeholder="Chiều rộng sản phẩm"
                       value="1">
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-2 col-lg-2">
            <div class="form-group form-group-sm qc-margin-none">
                <label>Thành tiền: </label>
                <input class="txtToolMoney form-control" type="text" name="txtToolMoney[]" placeholder="Nhập tiền"
                       onkeyup="qc_main.showFormatCurrency(this);" value="">
            </div>
        </div>
        <div class="text-right col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <a class="qc_delete qc-link-red" data-href="">
                <i class="qc-font-size-20 glyphicon glyphicon-remove"></i>
            </a>
        </div>
    </div>
</div>
