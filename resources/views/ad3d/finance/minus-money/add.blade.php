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
$currentDay = $hFunction->currentDay();
$dataStaffLogin = $modelStaff->loginStaffInfo();
if (count($dataWorkSelect) > 0) {
    $workSelectId = $dataWorkSelect->workId();
} else {
    $workSelectId = null;
}
?>
@extends('ad3d.finance.minus-money.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <a class="qc-link-white-bold btn btn-sm btn-primary" onclick="qc_main.page_back_go();">
                Về trang trước
            </a>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
            <h3 style="color: red;">PHẠT</h3>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6">
            <form id="frmAdd" class="frmAdd" role="form" method="post"
                  action="{!! route('qc.ad3d.finance.minus_money.add.post') !!}">
                {{-- thông tin khách hàng --}}
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group form-group-sm">
                            <label>
                                Công ty (<em style="color: red">tên cty của nhân viên đăng nhập</em>):
                            </label>
                            <select class="cbCompany form-control" name="cbCompany">
                                <option value="{!! $dataCompanyLogin->companyId() !!}">{!! $dataCompanyLogin->name() !!}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group form-group-sm">
                            <label>Nhân viên: <i
                                        class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                            <select name="cbWork" class="cbWork form-control"
                                    data-href="{!! route('qc.ad3d.finance.minus_money.add.get') !!}">
                                <option value="">Chọn nhân viên</option>
                                @if($hFunction->checkCount($dataWork))
                                    @foreach($dataWork as $work)
                                        <option value="{!! $work->workId() !!}"
                                                @if($work->workId() == $workSelectId) selected="selected" @endif>
                                            @if(!empty($work->companyStaffWorkId()))
                                                {!! $work->companyStaffWork->staff->fullName() !!}
                                                - {!! $work->companyStaffWork->staff->identityCard() !!}
                                            @else
                                                {!! $work->staff->fullName() !!}
                                                - {!! $work->staff->identityCard() !!}
                                            @endif
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
                @if(!empty($workSelectId))
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm">
                                <label>Lý do:</label>
                                <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                <select class="cbPunishContent form-control" name="cbPunishContent"
                                        data-href="{!! route('qc.ad3d.finance.minus_money.add.get') !!}">
                                    <option value="">Chọn lý do phạt</option>
                                    @if($hFunction->checkCount($dataPunishContent))
                                        @foreach($dataPunishContent as $punishContent)
                                            @if(!empty($dataPunishContentSelected))
                                                <option @if($dataPunishContentSelected->punishId() == $punishContent->punishId()) selected="selected"
                                                        @endif value="{!! $punishContent->punishId()!!}">
                                                    {!! $punishContent->name() !!}
                                                </option>
                                            @else
                                                <option value="{!! $punishContent->punishId()!!}">
                                                    {!! $punishContent->name() !!}
                                                </option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    @if(!empty($dataPunishContentSelected))
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group form-group-sm">
                                    <label>Số tiền:</label>
                                    <input type="text" class="form-control" name="txtMoney" readonly
                                           value="{!! $hFunction->dotNumber($dataPunishContentSelected->money()) !!}">
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm">
                                <label>Ghi chú:</label>
                                <input type="text" class="form-control" name="txtDescription" value="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label>Ảnh ghi chú:</label>
                            <input type="file" class="txtReasonImage form-control" name="txtReasonImage">
                        </div>
                    </div>
                @endif
                @if (Session::has('notifyAdd'))
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group" style="background-color: red;">
                                <span style="color: yellow; font-size: 1.5em;">
                                    {!! Session::get('notifyAdd') !!}
                                </span>
                                <?php
                                Session::forget('notifyAdd');
                                ?>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row">
                    <div class="qc-padding-top-20 qc-padding-bot-20  qc-border-none col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <button type="button" class="qc_save btn btn-primary btn-sm">ÁP DỤNG PHẠT</button>
                        <button type="reset" class="btn btn-default btn-sm">Nhập lại</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
