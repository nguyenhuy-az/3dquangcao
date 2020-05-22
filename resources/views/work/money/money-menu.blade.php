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
            <li @if($objectAccess == 'moneyReceive') class="active " @endif>
                <a href="{!! route('qc.work.money.receive.get') !!}" @if($objectAccess == 'moneyReceive') style="background-color: whitesmoke; " @endif>
                    <label>Thu tiền ĐH và Giao tiền</label>
                </a>
            </li>
            {{--<li @if($objectAccess == 'moneyHistory') class="active" @endif>
                <a href="{!! route('qc.work.money.history.receive.get') !!}">
                    <label>Lịch sử thu ĐH</label>
                </a>
            </li>--}}
            <li @if($objectAccess == 'moneyTransfer') class="active" @endif>
                <a href="{!! route('qc.work.money.transfer.transfer.get') !!}" @if($objectAccess == 'moneyTransfer') style="background-color: whitesmoke; " @endif>
                    <label>Giao tiền</label>
                </a>
            </li>
            <li @if($objectAccess == 'moneyTransferReceive') class="active" @endif>
                <a href="{!! route('qc.work.money.transfer.receive.get') !!}" @if($objectAccess == 'moneyTransferReceive') style="background-color: whitesmoke; " @endif>
                    <label>Nhận tiền</label>
                </a>
            </li>
            <li @if($objectAccess == 'moneyStatisticalPayment') class="active" @endif>
                <a href="{!! route('qc.work.money.payment.get') !!}" @if($objectAccess == 'moneyStatisticalPayment') style="background-color: whitesmoke; " @endif>
                    <label>TK chi</label>
                </a>
            </li>
            <li @if($objectAccess == 'moneyStatistical') class="active" @endif>
                <a href="{!! route('qc.work.money.statistical.get') !!}" @if($objectAccess == 'moneyStatistical') style="background-color: whitesmoke; " @endif>
                    <label>Thống kê tiền C.TY</label>
                </a>
            </li>
        </ul>
    </div>
</div>
