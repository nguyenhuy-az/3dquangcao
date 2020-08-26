<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 */
/*
 *dataCompany
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$detailId = $dataToolAllocationDetail->detailId();
$dataStaffApply = $dataToolAllocationDetail->toolAllocation->companyStaffWork->staff;
?>
@extends('components.container.container-6')
@section('qc_container_content')
    <div class="qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
                <h3 style="color: red;">XÁC NHẬN PHẠT ĐỒ NGHỀ </h3>
                <label style="color: blue; font-size: 1.5em;">{!! $dataStaffApply->fullName() !!}</label>
            </div>
        </div>
        <div class="row">
            <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form id="frmWorkStoreAllocationMinusMoney" role="form" method="post"
                      action="{!! route('qc.work.store.allocation.check.minus_money.post',$detailId) !!}">
                    @if($hFunction->checkCount($dataPunishContent))
                        <div class="row">
                            <div class="notifyConfirm qc-color-red text-center col-xs-12 col-sm-12 col-md-6 col-lg-6"></div>
                        </div>
                        <div class="row">
                            <div class="text-left col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group">
                                    <label style="text-decoration: underline; background-color: red; padding: 3px; color: yellow;">
                                        Lý dó:
                                    </label>
                                    <select class="form-control" name="cbPunishContent">
                                        <option value="{!! $dataPunishContent->punishId() !!}">{!! $dataPunishContent->name() !!}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group qc-padding-none">
                                    <label>Thi công đơn hàng:</label>
                                    <select class="cbOrder form-control" name="cbOrder">
                                        <option value="">Chọn đơn hàng</option>
                                        @if($hFunction->checkCount($dataOrder))
                                            @foreach($dataOrder as $order)
                                                <option value="{!! $order->orderId() !!}">{!! $order->name() !!}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="text-left col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group">
                                    <label style="text-decoration: underline;">Ghi chú:</label><br/>
                                    <input name="txtMinusMoneyNote" class="form-control" type="text" value="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="qc-padding-top-20 qc-padding-bot-20  qc-border-none col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <button type="button" class="qc_save btn btn-sm btn-primary">Áp dụng</button>
                                <button type="button" class="qc_container_close btn btn-sm btn-default">Hủy</button>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="text-left col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group">
                                    <span>Chưa áp dụng nội qui phat</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="qc-padding-top-20 qc-padding-bot-20  qc-border-none col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <button type="button" class="qc_container_close btn btn-sm btn-primary">Đóng</button>
                            </div>
                        </div>

                    @endif
                </form>
            </div>
        </div>
    </div>
@endsection
