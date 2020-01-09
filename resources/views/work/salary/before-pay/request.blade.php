<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 *
 * dataCompany
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();

$fromDate = $dataWork->fromDate();
$limitBeforePay = $dataWork->limitBeforePay();
$dayCurrent = (int)date('d');
?>
@extends('components.container.container-6')
@section('qc_container_content')
    <div class="qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
                <h3>ĐỀ XUẤT ỨNG LƯƠNG</h3>
                <em class="qc-color-red">(Được ứng sau 3 ngày gửi yêu cầu)</em>
            </div>
            <div class="qc-padding-top-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form class="frm_work_before_pay_request form-horizontal" name="frm_work_before_pay_request" role="form"
                      method="post"
                      action="{!! route('qc.work.salary.before_pay.request.post') !!}">
                    <div class="row">
                        @if(!$dataWork->checkUnconfirmedBeforePayRequest())
                            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="frm_notify text-center form-group qc-color-red"></div>
                            </div>
                            <div class="form-group form-group-sm col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label class="col-sm-3 control-label">Giới hạn ứng: </label>
                                <div class="col-sm-9">
                                    <input class="form-control" name="txtRequestLimit" disabled
                                           value="{!! ($limitBeforePay>0)?$hFunction->dotNumber($limitBeforePay):0 !!}">
                                </div>
                            </div>
                            @if($limitBeforePay > 100)
                                <div class="form-group form-group-sm col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <label class="col-sm-3 control-label">Số tiền:</label>

                                    <div class="col-sm-9">
                                        <input class="form-control" type="text" data-limit="{!! $limitBeforePay !!}"
                                               name="txtMoneyRequest" value="">
                                    </div>
                                </div>
                            @else
                                <div class="form-group form-group-sm text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <span class="qc-color-red">Giới hạn ứng phải lớn hơn 100.000</span>
                                </div>
                            @endif


                        @else
                            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="frm_notify text-center form-group qc-color-red">
                                    <span>Đã có yêu cầu đang chờ duyệt</span>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="text-center form-group form-group-sm">
                                @if($limitBeforePay > 100 && !$dataWork->checkUnconfirmedBeforePayRequest())
                                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                    <input type="hidden" name="txtWork" value="{!! $dataWork->workId() !!}">
                                    <button type="button" class="qc_save btn btn-sm btn-primary">Lưu</button>
                                @endif

                                <button type="button" class="qc_container_close btn btn-sm btn-default">Đóng</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
