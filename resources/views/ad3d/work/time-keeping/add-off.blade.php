<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
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
            <h3>THÔNG TIN NGHĨ </h3>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <form id="frmAddOff" class="frmAddOff" role="form" method="post"
                  action="{!! route('qc.ad3d.work.time-keeping.off.add.post') !!}">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        @if (Session::has('notifyAdd'))
                            <div class="form-group qc-padding-top-20 text-center qc-color-red">
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
                                    data-href="{!! route('qc.ad3d.work.time-keeping.off.add.get') !!}">
                                @if(count($dataCompany)> 0)
                                    @foreach($dataCompany as $company)
                                        <option value="{!! $company->companyId() !!}"
                                                @if($companyLoginId == $company->companyId()) selected="selected" @endif >{!! $company->name() !!}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group form-group-sm">
                            <label>Nhân viên: <i
                                        class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                            <select name="cbWork" class="cbWork form-control"
                                    data-href="{!! route('qc.ad3d.work.time-keeping.off.add.get') !!}">
                                <option value="">Chọn nhân viên</option>
                                @if(count($dataWork))
                                    @foreach($dataWork as $work)
                                        <option value="{!! $work->workId() !!}"
                                                @if($work->workId() == $workSelectId) selected="selected" @endif> {!! $work->companyStaffWork->staff->nameCode() !!}
                                            - {!! $work->companyStaffWork->staff->fullName() !!}</option>
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
                                <label>Ngày: <i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <select name="cbDayOff" style="margin-top: 5px; height: 25px;">
                                    <option value="">Ngày</option>
                                    @for($i = $hFunction->getDayFromDate($fromDate);$i<= 31; $i++)
                                        <option value="{!! $i !!}">{!! $i !!}</option>
                                    @endfor
                                </select>
                                <span>/</span>
                                <select name="cbMonthOff" style="margin-top: 5px; height: 25px;">
                                    {{--<option name="cbMonthIn" value="">Tháng</option>--}}
                                    <option value="{!! (int)$hFunction->getMonthFromDate($fromDate) !!}">{!! (int)$hFunction->getMonthFromDate($fromDate) !!}</option>
                                </select>
                                <span>/</span>
                                <select name="cbYearOff" style="margin-top: 5px; height: 25px;">
                                    {{--<option value="">Năm</option>--}}
                                    <option value="{!! $hFunction->getYearFromDate($fromDate) !!}">{!! $hFunction->getYearFromDate($fromDate) !!}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row qc-margin-top-15">
                        <div class="form-group form-group-sm">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label><em>Có phép:</em></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <select class="form-control" name="cbPermissionStatus" style="margin-top: 5px; height: 30px;">
                                    <option value="0">Không</option>
                                    <option value="1">Có</option>
                                </select>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row qc-margin-top-15">
                    <div class="form-group form-group-sm">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
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
