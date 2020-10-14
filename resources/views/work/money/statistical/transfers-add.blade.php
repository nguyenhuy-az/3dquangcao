<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 */
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$hFunction = new Hfunction();
$dataStaffLogin = $modelStaff->loginStaffInfo();
?>
@extends('work.money.statistical.index')
@section('titlePage')
    Nộp tiền lên công ty
@endsection
@section('qc_work_money_statistical_body')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <a class="btn btn-primary qc-link-white-bold" onclick="qc_main.page_back_go();">
                Về trang trước
            </a>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
            <h3 style="color: red;">NỘP TIỀN CHO CÔNG TY</h3>
        </div>
        @if($limitTransfers > 0)
            <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6">
                <form id="qc_frm_money_statistic_transfer" name="qc_frm_money_statistic_transfer" role="form"
                      method="post" action="{!! route('qc.work.money.statistical.transfers.post') !!}">
                    <div class="row">
                        <div class="qc-padding-top-20 col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            @if (Session::has('notifyAdd'))
                                <div class="form-group text-center qc-color-red">
                                    {!! Session::get('notifyAdd') !!}
                                    <?php
                                    Session::forget('notifyAdd');
                                    ?>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm">
                                <label>
                                    Người nhận <em style="color: red;">(Thủ quỹ cấp quản lý)</em>:
                                    <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                </label>
                                <select class="cbReceiveStaff form-control" name="cbReceiveStaff">
                                    <option value="">Chọn người nhận</option>
                                    @if(count($dataStaffReceiveTransfer)> 0)
                                        @foreach($dataStaffReceiveTransfer as $staffReceive)
                                            <option value="{!! $staffReceive->staffId() !!}">
                                                {!! $staffReceive->fullName() !!}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm">
                                <label>
                                    Số tiền (<em style="color: red;">Đang
                                        giữ: {!! $hFunction->currencyFormat($limitTransfers) !!}</em>):
                                    <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                </label>
                                <input type="text" class="txtMoney form-control" name="txtMoney"
                                       data-limit="{!! $limitTransfers !!}"
                                       onkeyup="qc_main.showFormatCurrency(this);"
                                       placeholder="Nhập số tiền chuyển"
                                       value="{!! $hFunction->currencyFormat($limitTransfers) !!}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm qc-margin-bot-none qc-padding-none">
                                <label>
                                    Lý do:
                                    <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                </label>
                                <input type="text" class="txtReason form-control" name="txtReason" value="">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="qc-padding-top-20 qc-padding-bot-20col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <button type="button" class="qc_save form-control btn btn-sm btn-primary">CHUYỂN</button>
                        </div>
                    </div>
                </form>
            </div>
        @else
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="background-color: red;">
                <em style="font-size: 1.5em; color: yellow;">Tiền đang giữ:</em>
                <label style="font-size: 2em; color: white;">{!! $hFunction->currencyFormat($limitTransfers) !!}</label>
                <h4 style="color: yellow;">KHÔNG CÓ TIỀN ĐỂ GIAO</h4>
            </div>
        @endif
    </div>
@endsection
