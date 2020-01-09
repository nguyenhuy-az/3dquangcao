<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 7/4/2019
 * Time: 2:02 PM
 */
$objectAccess = $dataAccess['object'];
?>
<div class="row">
    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <ul class="nav nav-tabs" role="tablist">
            <li @if($objectAccess == 'orders') class="active " @endif>
                <a href="{!! route('qc.work.money.receive.get') !!}">
                    <label>Đơn hàng</label>
                </a>
            </li>
        </ul>
    </div>
</div>
