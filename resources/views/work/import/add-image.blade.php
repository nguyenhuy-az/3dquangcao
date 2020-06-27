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
<div class="qc_work_import_image_add qc-margin-top-10 col-xs-12 col-sm-12 col-md-12 col-lg-12"
     style="border: 1px solid #d7d7d7; border-left: 5px solid brown;">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10" style="overflow: hidden;">
            <label>Chọn ảnh:</label>
            <input class="txtImportImage" type="file" name="txtImportImage[]" >
        </div>
        <div class="text-right col-xs-12 col-sm-12 col-md-2 col-lg-2">
            <a class="qc_delete qc-link-red" data-href="">
                <i class="qc-font-size-20 glyphicon glyphicon-remove"></i>
            </a>
        </div>
    </div>
</div>
