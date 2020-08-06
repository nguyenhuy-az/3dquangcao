<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 5/15/2018
 * Time: 10:12 AM
 *
 * dataStaff
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$currentDay = (int)date('d');
$currentMonth = (int)date('m');
$currentYear = (int)date('Y');
?>
@extends('work.pay.pay-activity.index')
@section('qc_work_pay_activity_body')
    <div class="row">
        <div class="qc_work_import_add qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed #C2C2C2;">
                    <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6">
                        <a class="qc-link-red" onclick="qc_main.page_back();">
                            <i class="glyphicon glyphicon-backward"></i> Trở lại
                        </a>
                        <h4>NHẬP THÔNG TIN CHI</h4>
                    </div>
                </div>
            </div>
            <form id="frm_work_pay_activity_add" role="form" method="post" enctype="multipart/form-data"
                  action="{!! route('qc.work.pay.pay_activity.add.post') !!}">
                <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    @if (Session::has('notifyAddPayActivity'))
                        <div class="form-group text-center qc-color-red">
                            {!! Session::get('notifyAddPayActivity') !!}
                            <?php
                            Session::forget('notifyAddPayActivity');
                            ?>
                        </div>
                    @endif
                    <div class="row">
                        <div class="form-group form-group-sm col-xs-12 col-sm-12 col-md-12 col-lg-12"
                             style="margin: 0;">
                            <label class=" control-label">Ngày:</label>
                            <select name="cbPayActivityDay" style="height: 25px;">
                                @for($i = 1;$i<= 31; $i++)
                                    <option value="{!! $i !!}"
                                            @if($i == $currentDay) selected="selected" @endif>
                                        {!! $i !!}
                                    </option>
                                @endfor
                            </select>
                            <span>/</span>
                            <select name="cbPayActivityMonth" style="height: 25px;">
                                @for($month = 1;$month<= 12; $month++)
                                    <option value="{!! $month !!}"
                                            @if($month == $currentMonth) selected="selected" @endif>
                                        {!! $month !!}
                                    </option>
                                @endfor
                            </select>
                            <span>/</span>
                            <select name="cbPayActivityYear" style="height: 25px;">
                                <option value="{!! $currentYear !!}">{!! $currentYear !!}</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                             @if($mobileStatus) style="padding: 0 0;" @endif>
                            <div class="form-group form-group-sm qc-padding-none">
                                <label>
                                    Danh mục vực chi:
                                    <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                </label>
                                <select class="cbPayActivityList form-control" name="cbPayActivityList">
                                    <option value="">Chọn danh mục chi</option>
                                    @if(count($dataPayActivityList)> 0)
                                        @foreach($dataPayActivityList as $payActivityList)
                                            <option value="{!! $payActivityList->payListId() !!}">
                                                CHI: {!! $payActivityList->name() !!}
                                            </option>
                                        @endforeach
                                    @endif

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                             @if($mobileStatus) style="padding: 0 0;" @endif>
                            <div class="form-group form-group-sm qc-padding-none">
                                <label>
                                    Số tiền (VND):
                                    <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                </label>
                                <input type="text" class="form-control" name="txtMoney"
                                       onkeyup="qc_main.showFormatCurrency(this);"
                                       placeholder="Nhập số tiền" value="0">
                            </div>
                        </div>
                    </div>
                    {{-- Ảnh hóa đơn nhập --}}
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group form-group-sm">
                            <label class="col-sm-2 control-label">Ảnh hóa đơn:</label>
                            <div class="col-sm-8">
                                <input type="file" class="txtPayImage" name="txtPayImage">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                             @if($mobileStatus) style="padding: 0 0;" @endif>
                            <div class="form-group form-group-sm qc-padding-none">
                                <label>
                                    Ghi chú:
                                </label>
                                <input type="text" class="form-control" name="txtNote" placeholder="Nội dung ghi chú">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group form-group-sm">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <button type="button" class="qc_save btn btn-sm btn-primary">
                            Thêm
                        </button>
                        <button type="reset" class="btn btn-sm btn-default">
                            Nhập lại
                        </button>
                        <a href="{!! route('qc.work.pay.pay_activity.get') !!}">
                            <button type="button" class="btn btn-sm btn-default">
                                Đóng
                            </button>
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection
