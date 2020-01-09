<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 *
 * dataWorkSelect
 */
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$hFunction = new Hfunction();
$dataStaffLogin = $modelStaff->loginStaffInfo();

if (count($dataWorkSelect) > 0) {
    $workSelectId = $dataWorkSelect->workId();
} else {
    $workSelectId = null;
}
?>
@extends('ad3d.work.time-keeping.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
            <h3>CHẤM CÔNG </h3>
        </div>
        <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <form id="frmAdd" class="frmAdd" role="form" method="post"
                  action="{!! route('qc.ad3d.work.time-keeping.add.post') !!}">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
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
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group form-group-sm">
                            <label>Công ty (<em>tên cty của nhân viên đăng nhập</em>): <i
                                        class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                            <select class="cbCompany form-control" name="cbCompany"
                                    data-href="{!! route('qc.ad3d.work.time-keeping.add.get') !!}">
                                @if(count($dataCompany)> 0)
                                    @foreach($dataCompany as $company)
                                        @if($dataStaffLogin->checkRootManage())
                                            <option value="{!! $company->companyId() !!}"
                                                    @if($companyLoginId == $company->companyId()) selected="selected" @endif >{!! $company->name() !!}</option>
                                        @else
                                            @if($companyLoginId == $company->companyId())
                                                <option value="{!! $company->companyId() !!}">{!! $company->name() !!}</option>
                                            @endif
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group form-group-sm">
                            <label>Nhân viên: <i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                            <select name="cbWork" class="cbWork form-control"
                                    data-href="{!! route('qc.ad3d.work.time-keeping.add.get') !!}">
                                <option value="">Chọn nhân viên</option>
                                @if(count($dataWork) > 0)
                                    @foreach($dataWork as $work)
                                        <option value="{!! $work->workId() !!}"
                                                @if($work->workId() == $workSelectId) selected="selected" @endif>
                                            {!! $work->staff->fullName() !!}
                                            -{!! $work->staff->nameCode() !!}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
                @if(count($dataWorkSelect) > 0)
                    <div class="row">
                        <div class="form-group form-group-sm">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <?php
                                $fromDate = $dataWorkSelect->fromDate();
                                ?>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label>Giờ vào: <i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <select name="cbDayBegin" style="margin-top: 5px; height: 25px;">
                                    <option value="">Ngày</option>
                                    @for($i = $hFunction->getDayFromDate($fromDate);$i<= 31; $i++)
                                        <option value="{!! $i !!}">{!! $i !!}</option>
                                    @endfor
                                </select>
                                <span>/</span>
                                <select name="cbMonthBegin" style="margin-top: 5px; height: 25px;">
                                    {{--<option name="cbMonthIn" value="">Tháng</option>--}}
                                    <option value="{!! (int)$hFunction->getMonthFromDate($fromDate) !!}">{!! (int)$hFunction->getMonthFromDate($fromDate) !!}</option>
                                </select>
                                <span>/</span>
                                <select name="cbYearBegin" style="margin-top: 5px; height: 25px;">
                                    {{--<option value="">Năm</option>--}}
                                    <option value="{!! $hFunction->getYearFromDate($fromDate) !!}">{!! $hFunction->getYearFromDate($fromDate) !!}</option>
                                </select>
                                <select name="cbHoursBegin" style="margin-top: 5px; height: 25px;">
                                    <option value="">Giờ</option>
                                    @for($i =1;$i<= 24; $i++)
                                        <option value="{!! $i !!}"
                                                @if($i == 8) selected="selected" @endif>{!! $i !!}</option>
                                    @endfor
                                </select>
                                <span>:</span>
                                <select name="cbMinuteBegin" style="margin-top: 5px; height: 25px;">
                                    @for($i =0;$i<= 55; $i = $i+5)
                                        <option value="{!! $i !!}">{!! $i !!}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row qc-margin-top-15">
                        <div class="form-group form-group-sm">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label><em>Thời gian trễ là:</em></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <select class="form-control" name="cbPermissionStatus"
                                        style="margin-top: 5px; height: 30px;">
                                    <option value="0">Không phép</option>
                                    <option value="1">Có phép</option>
                                </select>
                            </div>
                        </div>
                    </div>

                @endif
                <div class="row qc-margin-top-15">
                    <div class="form-group form-group-sm">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6"
                             @if($mobileStatus) style="padding: 5px 0 5px 0;" @endif>
                            <label>Ghi chú:</label>
                            <textarea name="txtNote" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="qc-padding-top-20 qc-padding-bot-20  qc-border-none col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <button type="button" class="qc_save btn btn-sm btn-primary">Lưu</button>
                        <button type="reset" class="btn btn-sm btn-default">Hủy</button>
                        <a href="{!! route('qc.ad3d.work.time-keeping.get') !!}">
                            <button type="button" class="btn btn-sm btn-default">Đóng</button>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
