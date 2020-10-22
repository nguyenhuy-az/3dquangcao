<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 7/4/2019
 * Time: 2:02 PM
 */
$viewLoginObject = $dataAccess['object'];
$totalOrdersProvisional = (isset($dataOrdersProvisional)) ? $hFunction->getCountFromData($dataOrdersProvisional) : 0;
?>
<div class="row">
    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <ul class="nav nav-tabs" role="tablist">
                <li @if($viewLoginObject == 'orders') class="active" @endif>
                    <a class="qc-link" href="{!! route('qc.work.orders.get') !!}"
                       @if($viewLoginObject == 'orders') style="background-color: whitesmoke;" @endif>
                        <i class="glyphicon glyphicon-refresh" style="color: red;"></i>
                        ĐƠN HÀNG
                    </a>
                </li>
                <li @if($viewLoginObject == 'ordersProvisional') class="active" @endif>
                    <a class="qc-link" href="{!! route('qc.work.orders.provisional.get') !!}"
                       @if($viewLoginObject == 'ordersProvisional') style="background-color: whitesmoke;" @endif>
                        BÁO GIÁ
                        <em class="qc-font-size-14" style="color: red;">({!! $totalOrdersProvisional !!})</em>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
