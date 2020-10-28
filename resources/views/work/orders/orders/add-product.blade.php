<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 1/4/2018
 * Time: 5:41 PM
 *
 *
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
?>
<div class="qc_work_orders_product_add qc-margin-top-5 col-xs-12 col-sm-12 col-md-12 col-lg-12"
     style="border-left: 3px solid brown; padding: 0;">
    <table class="table" style=" margin: 0; border: 1px solid #d7d7d7;">
        <tr>
            <td>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <div class="form-group form-group-sm qc-margin-none">
                            <label>Loại sản phẩm: <i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                            <input type="text" class="txtProductType form-control" name="txtProductType[]"
                                   data-href-check-name="{!! route('qc.work.orders.add.product_type.check.name') !!}"
                                   placeholder="Nhập loại sản phẩm" value="">
                        </div>
                        <div class="qc_order_add_product_type_suggestions_wrap col-xs-12 col-sm-12 col-md-12 col-lg-12"
                             style="border: 1px solid #d7d7d7; display: none;">
                            <a class='qc_order_add_product_type_suggestions_close pull-right qc-link-red'>X</a>

                            <div class="row">
                                <div class="qc_order_add_product_type_suggestions_content col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-12 col-md-2 col-lg-2">
                        <div class="form-group form-group-sm qc-margin-none">
                            <label>Rộng<em>(mm)</em>:
                                <i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                            <input class="txtWidth form-control" type="text" onkeyup="qc_main.showNumberInput(this);"
                                   name="txtWidth[]" style="height: 25px;" placeholder="Chiều rộng sản phẩm" value="">
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-12 col-md-2 col-lg-2">
                        <div class="form-group form-group-sm qc-margin-none">
                            <label>Cao<em>(mm)</em>: <i
                                        class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                            <input class="txtHeight form-control" type="text" name="txtHeight[]"
                                   onkeyup="qc_main.showNumberInput(this);" style="height: 25px;"
                                   placeholder="Chiều cao sản phẩm" value="">
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-12 col-md-2 col-lg-2">
                        <div class="form-group form-group-sm qc-margin-none">
                            <label>
                                Đơn vị tính:
                            </label>
                            <input class="txtUnit form-control" type="text" name="txtUnit[]" style="height: 25px;"
                                   placeholder="Đơn vị tính" value="">
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-12 col-md-2 col-lg-2">
                        <div class="form-group form-group-sm qc-margin-none">
                            <label>Số lượng: <i
                                        class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                            <input class="txtAmount form-control" type="text" name="txtAmount[]" style="height: 25px;"
                                   placeholder="Số lượng sản phẩm" value="1">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <div class="form-group form-group-sm qc-margin-none">
                            <label>
                                Giá/sản phẩm:
                                <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                            </label>
                            <input class="txtPrice form-control" type="text" name="txtPrice[]"
                                   placeholder="Gía trên một sản phẩm" value="" >
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                        <div class="form-group form-group-sm qc-margin-none">
                            <label>
                                Bảo hành (Tháng):
                                <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                            </label>
                            <input class="txtWarrantyTime form-control" type="text" name="txtWarrantyTime[]"
                                   placeholder="Số tháng bảo hành" value="0">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group form-group-sm qc-margin-none">
                            <label>Chi chú</label>
                            <input type="text" class="txtDescription form-control" name="txtDescription[]"
                                   placeholder="Chú thích sản phảm" value="">
                        </div>
                    </div>
                </div>
            </td>
            <td class="text-center" style="background-color: whitesmoke;vertical-align: middle; padding: 0;">
                <a class="qc_delete qc-link-red" data-href="{!! route('qc.work.orders.add.product.cancel.get') !!}">
                    Xóa
                </a>
            </td>
        </tr>
    </table>
</div>
