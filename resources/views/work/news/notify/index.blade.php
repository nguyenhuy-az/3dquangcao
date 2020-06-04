<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 8:57 AM
 *
 * modelStaff
 */
$hFunction = new Hfunction();
$dataStaffLogin = $modelStaff->loginStaffInfo();
$currentDate = $hFunction->currentDate();
$hrefIndex = route('qc.work.news.notify.get');
?>
@extends('work.index')
@section('titlePage')
    Thông báo
@endsection
<style type="text/css">
    .qc-work-panel {
        text-align: center;
        height: 50px;
        line-height: 50px;
        border: 1px solid #d7d7d7;
    }

    .qc-work-panel:hover {
        background-color: #d7d7d7;
        color: red;
    }
</style>
@section('qc_work_body')
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                {{--system info--}}
                @include('work.components.system-info.system-info', compact('modelCompany','modelStaff'))
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: black;color: yellow;">
                            <th class="text-center" style="width: 20px;">STT</th>
                            <th style="width: 110px !important;">Ngày thông báo</th>
                            <th></th>
                            <th>Nội dung</th>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="padding: 0;">
                                <select class="qc_work_news_notify_filter_month" style="height: 25px;"
                                        data-href="{!! $hrefIndex !!}">
                                    @for($m =1;$m<= 12; $m++)
                                        <option value="{!! $m !!}"
                                                @if((int)$monthFilter == $m) selected="selected" @endif>
                                            Tháng {!! $m !!}
                                        </option>
                                    @endfor
                                </select>
                                <span>/</span>
                                <select class="qc_work_news_notify_filter_year" style="height: 25px;"
                                        data-href="{!! $hrefIndex !!}">
                                    @for($y =2017;$y<= 2050; $y++)
                                        <option value="{!! $y !!}"
                                                @if($yearFilter == $y) selected="selected" @endif>{!! $y !!}</option>
                                    @endfor
                                </select>
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                        @if($hFunction->checkCount($dataStaffNotify))
                            <?php
                            $n_o = 0;
                            ?>
                            @foreach($dataStaffNotify as $staffNotify)
                                <?php
                                $notifyId = $staffNotify->notifyId();
                                $notifyDate = $staffNotify->createdAt();
                                $orderId = $staffNotify->orderId();
                                #ban giao don hang
                                $orderAllocationId = $staffNotify->orderAllocationId();
                                # thong bao giao thi cong san pham
                                $workAllocationId = $staffNotify->workAllocationId();
                                # thuong nong
                                $bonusId = $staffNotify->bonusId();
                                # phat
                                $minusMoneyId = $staffNotify->minusMoneyId();
                                #bao hoan thanh thi cong don hang
                                $orderAllocationFinishId = $staffNotify->orderAllocationFinishId();
                                ?>
                                {{--them don hang mơi--}}
                                @if(!$hFunction->checkEmpty($orderId))
                                    <tr class="@if($n_o%2) info @endif ">
                                        <td class="text-center">
                                            {!! $n_o += 1 !!}
                                        </td>
                                        <td>
                                            {!! $hFunction->convertDateDMYFromDatetime($notifyDate) !!}
                                        </td>
                                        <td>
                                            <a class="qc-link-green"
                                               href="{!! route('qc.work.work_allocation.manage.order.view', "$orderId/$notifyId") !!}">
                                                Xem
                                            </a>
                                            @if($staffNotify->checkNewInfo())
                                                <em style="color: red;"> - Chưa xem</em>
                                            @else
                                                <em style="color: grey;">- Đã xem</em>
                                            @endif
                                        </td>
                                        <td>
                                            <span style="color: deeppink;">Đã thêm ĐH:</span>
                                            <a class="qc-link-green-bold"
                                               href="{!! route('qc.work.work_allocation.manage.order.view', "$orderId/$notifyId") !!}">
                                                {!! $staffNotify->orders->name() !!}
                                            </a>
                                            <span style="background-color: blue; padding: 3px; color: white;">
                                                <i class="glyphicon glyphicon-plus"></i>
                                                <i class="glyphicon glyphicon-shopping-cart"></i>
                                            </span>
                                        </td>
                                    </tr>
                                    {{--ban giao don hang thi cong--}}
                                @elseif(!$hFunction->checkEmpty($orderAllocationId))
                                    <tr class="@if($n_o%2) info @endif ">
                                        <td class="text-center">
                                            {!! $n_o += 1 !!}
                                        </td>
                                        <td>
                                            {!! $hFunction->convertDateDMYFromDatetime($notifyDate) !!}
                                        </td>
                                        <td>
                                            <a class="qc-link-green"
                                               href="{!! route('qc.work.work_allocation.construction.product.get',$orderAllocationId) !!}">
                                                Xem
                                            </a>
                                            @if($staffNotify->checkNewInfo())
                                                <em style="color: red;"> - Chưa xem</em>
                                            @else
                                                <em style="color: grey;">- Đã xem</em>
                                            @endif
                                        </td>
                                        <td>
                                            <span style="color: blue;">{!! $staffNotify->note() !!}:</span>
                                            <a class="qc-link-green-bold"
                                               href="{!! route('qc.work.work_allocation.construction.product.get',$orderAllocationId) !!}">
                                                {!! $staffNotify->orderAllocation->orders->name() !!}
                                            </a>
                                            <span style="background-color: black; padding: 3px; color: white;">
                                                <i class="glyphicon glyphicon-calendar"></i>
                                            </span>
                                        </td>
                                    </tr>
                                    {{--phan viec--}}
                                @elseif(!$hFunction->checkEmpty($workAllocationId))
                                    <tr class="@if($n_o%2) info @endif ">
                                        <td class="text-center">
                                            {!! $n_o += 1 !!}
                                        </td>
                                        <td>
                                            {!! $hFunction->convertDateDMYFromDatetime($notifyDate) !!}
                                        </td>
                                        <td>
                                            <a class="qc-link-green"
                                               href="{!! route('qc.work.work_allocation.detail.get', $workAllocationId) !!}">
                                                Xem
                                            </a>
                                            @if($staffNotify->checkNewInfo())
                                                <em style="color: red;"> - Chưa xem</em>
                                            @else
                                                <em style="color: grey;">- Đã xem</em>
                                            @endif
                                        </td>
                                        <td>
                                            <span style="color: brown;">{!! $staffNotify->note() !!}:</span>
                                            <a class="qc-link-green-bold"
                                               href="{!! route('qc.work.work_allocation.detail.get', $workAllocationId) !!}">
                                                {!! $staffNotify->workAllocation->product->productType->name() !!}
                                            </a>
                                            <span style="background-color: black; padding: 3px; color: yellow;">
                                                <i class="glyphicon glyphicon-plus"></i>
                                                <i class="glyphicon glyphicon-wrench"></i>
                                            </span>
                                        </td>
                                    </tr>
                                    {{--thuong--}}
                                @elseif(!$hFunction->checkEmpty($bonusId))
                                    <?php
                                    $dataBonus = $staffNotify->bonus;
                                    ?>
                                    <tr class="@if($n_o%2) info @endif ">
                                        <td class="text-center">
                                            {!! $n_o += 1 !!}
                                        </td>
                                        <td>
                                            {!! $hFunction->convertDateDMYFromDatetime($notifyDate) !!}
                                        </td>
                                        <td>
                                            <a class="qc-link-green" href="{!! route('qc.work.bonus.get') !!}"
                                               title="Xem thông tin thưởng">
                                                Xem
                                            </a>
                                            @if($staffNotify->checkNewInfo())
                                                <em style="color: red;"> - Chưa xem</em>
                                            @else
                                                <em style="color: grey;">- Đã xem</em>
                                            @endif
                                        </td>
                                        <td>
                                            <span style="color: red;">{!! $staffNotify->note() !!}:</span>
                                            <a class="qc-link-green-bold"
                                               href="{!! route('qc.work.work_allocation.construction.product.get',$dataBonus->orderAllocationId()) !!}">
                                                {!! $staffNotify->bonus->orderAllocation->orders->name() !!}
                                            </a>
                                            <span style="background-color: red; padding: 3px; color: yellow;">
                                                <i class="glyphicon glyphicon-plus"></i>
                                                <i class="glyphicon glyphicon-usd"></i>
                                            </span>
                                        </td>
                                    </tr>
                                    {{--Phat--}}
                                @elseif(!$hFunction->checkEmpty($minusMoneyId))
                                    <?php
                                    $dataMinusMoney = $staffNotify->minusMoney;
                                    $orderAllocationId = $dataMinusMoney->orderAllocationId();
                                    $orderConstructionId = $dataMinusMoney->orderConstructionId();
                                    ?>
                                    {{--Phat thi cong--}}
                                    @if(!empty($orderAllocationId))
                                        <tr class="@if($n_o%2) info @endif ">
                                            <td class="text-center">
                                                {!! $n_o += 1 !!}
                                            </td>
                                            <td>
                                                {!! $hFunction->convertDateDMYFromDatetime($notifyDate) !!}
                                            </td>
                                            <td>
                                                <a class="qc-link-green"
                                                   href="{!! route('qc.work.minus_money.get') !!}">
                                                    Xem
                                                </a>
                                                @if($staffNotify->checkNewInfo())
                                                    <em style="color: red;"> - Chưa xem</em>
                                                @else
                                                    <em style="color: grey;">- Đã xem</em>
                                                @endif
                                            </td>
                                            <td>
                                                <span style="color: black;">Phạt {!! $staffNotify->note() !!}:</span>
                                                <a class="qc-link-green-bold"
                                                   href="{!! route('qc.work.minus_money.get') !!}">
                                                    {!! $dataMinusMoney->orderAllocation->orders->name() !!}
                                                </a>
                                            <span style="background-color: red; padding: 3px; color: white;">
                                                <i class="glyphicon glyphicon-minus"></i>
                                                <i class="glyphicon glyphicon-usd"></i>
                                            </span>
                                            </td>
                                        </tr>
                                    @endif
                                    {{--Phat quan ly thi cong--}}
                                    @if(!empty($orderConstructionId))
                                        <tr class="@if($n_o%2) info @endif ">
                                            <td class="text-center">
                                                {!! $n_o += 1 !!}
                                            </td>
                                            <td>
                                                {!! $hFunction->convertDateDMYFromDatetime($notifyDate) !!}
                                            </td>
                                            <td>
                                                <a class="qc-link-green"
                                                   href="{!! route('qc.work.minus_money.get') !!}">
                                                    Xem
                                                </a>
                                                @if($staffNotify->checkNewInfo())
                                                    <em style="color: red;"> - Chưa xem</em>
                                                @else
                                                    <em style="color: grey;">- Đã xem</em>
                                                @endif
                                            </td>
                                            <td>
                                                <span style="color: black;">Phạt {!! $staffNotify->note() !!}:</span>
                                                <a class="qc-link-green-bold"
                                                   href="{!! route('qc.work.work_allocation.manage.order.construction.get',$orderConstructionId) !!}">
                                                    {!! $dataMinusMoney->orderConstruction->name() !!}
                                                </a>
                                            <span style="background-color: red; padding: 3px; color: white;">
                                                <i class="glyphicon glyphicon-minus"></i>
                                                <i class="glyphicon glyphicon-usd"></i>
                                            </span>
                                            </td>
                                        </tr>
                                    @endif

                                    {{--Thong bao hoan thanh thi cong don hang --}}
                                @elseif(!$hFunction->checkEmpty($orderAllocationFinishId))
                                    <?php
                                    $order = $staffNotify->orderAllocationFinish->orders;
                                    $orderId = $order->orderId();
                                    ?>
                                    <tr class="@if($n_o%2) info @endif ">
                                        <td class="text-center">
                                            {!! $n_o += 1 !!}
                                        </td>
                                        <td>
                                            {!! $hFunction->convertDateDMYFromDatetime($notifyDate) !!}
                                        </td>
                                        <td>
                                            <a class="qc-link-green"
                                               href="{!! route('qc.work.work_allocation.manage.order.construction.get', "$orderId/$notifyId") !!}">
                                                Xem
                                            </a>
                                            @if($staffNotify->checkNewInfo())
                                                <em style="color: red;"> - Chưa xem</em>
                                            @else
                                                <em style="color: grey;">- Đã xem</em>
                                            @endif
                                        </td>
                                        <td>
                                            <span style="color: black;">{!! $staffNotify->note() !!}:</span>
                                            <a class="qc-link-green-bold"
                                               href="{!! route('qc.work.work_allocation.manage.order.construction.get',"$orderId/$notifyId") !!}">
                                                {!! $order->name() !!}
                                            </a>
                                            <span style="background-color: blue; padding: 3px 10px; color: yellow;">
                                                <i class="glyphicon glyphicon-saved"></i>
                                            </span>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @else
                            <tr>
                                <td class="text-center" colspan="4">
                                    <em>Không có thông báo</em>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
