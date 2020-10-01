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
$dataOrder = $dataOrderAllocation->orders;
?>
@extends('components.container.container-6')
@section('qc_container_content')
    <div class="qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
                <h3>XÁC NHẬN HOÀN THÀNH </h3>
                <label style="color: red;">Thời gian báo hoàn thành là giờ hiện tại</label>
            </div>
        </div>
        <div class="row">
            <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form class="frmWorkAllocationConstructionConfirm" role="form" method="post"
                      action="{!! route('qc.work.work_allocation.construction.report_finish.post', $dataOrderAllocation->allocationId()) !!}">
                    <div class="row">
                        <div class="notifyConfirm qc-color-red text-center col-xs-12 col-sm-12 col-md-6 col-lg-6"></div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                             @if($mobileStatus) style="padding: 0 0;" @endif>
                            <div class="form-group qc-padding-none">
                                <em class="qc-color-grey">Đơn hàng:</em>
                                <label style="font-size: 2em;">
                                    {!! $dataOrder->name() !!}
                                </label>
                            </div>
                        </div>
                    </div>
                    @if($dataOrder->checkExistsProductNotFinish())
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group qc-padding-none">
                                <b class="qc-color-red">TỒN TẠI SẢN PHẨM CHƯA HOÀN THÀNH</b>
                                <b class="qc-color-red"> - TẤT CẢ CÁC SẢN PHẨM SẼ KẾT THÚC THEO CÔNG TRÌNH</b>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="row">
                        <div class="text-left col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label style="text-decoration: underline; background-color: red; padding: 3px; color: yellow;">Thu tiền:</label>
                                <select class="form-control" name="cbPaymentStatus">
                                    <option value="0">Không thu tiền</option>
                                    <option value="1">Có thu tiền</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="text-left col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label style="text-decoration: underline;">Ghi chú:</label><br/>
                                <input name="txtFinishNote" class="form-control" type="text" value="" >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <b style="color: blue; font-size: 1.5em;">HOÀN THÀNH ĐH TRỄ KHÔNG PHẢI DO MÌNH, GHI CHÚ VÀO BÁO CÁO</b>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="text-center qc-padding-top-20 qc-padding-bot-20  qc-border-none col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <button type="button" class="qc_save btn btn-sm btn-primary">Xác nhận</button>
                            <button type="button" class="qc_container_close btn btn-sm btn-default">Đóng</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
