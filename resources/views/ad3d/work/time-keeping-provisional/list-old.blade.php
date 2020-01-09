<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:32 PM
 *
 * modelStaff
 * dataAccess
 * dataTimekeeping
 * dateFilter
 *
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$dataStaffLogin = $modelStaff->loginStaffInfo();

?>
@extends('ad3d.work.time-keeping-provisional.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
             style="margin-bottom: 10px; padding-top : 10px;padding-bottom: 10px; border-bottom: 2px dashed brown;">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! route('qc.ad3d.work.time-keeping-provisional.get') !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <i class="qc-font-size-20 glyphicon glyphicon-list-alt"></i>
                    <label class="qc-font-size-20">DUYỆT CHẤM CÔNG</label>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <select class="cbCompanyFilter" name="cbCompanyFilter" style="margin-top: 5px; height: 25px;"
                            data-href-filter="{!! route('qc.ad3d.work.time-keeping-provisional.get') !!}">
                        @if($dataStaffLogin->checkRootManage())
                            <option value="0">Tất cả</option>
                        @endif
                        @if(count($dataCompany)> 0)
                            @foreach($dataCompany as $company)
                                @if($dataStaffLogin->checkRootManage())
                                    <option value="{!! $company->companyId() !!}"
                                            @if($companyFilterId == $company->companyId()) selected="selected" @endif >{!! $company->name() !!}</option>
                                @else
                                    @if($companyFilterId == $company->companyId())
                                        <option value="{!! $company->companyId() !!}">{!! $company->name() !!}</option>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 2px 0 2px 0; ">
                    <form name="" action="">
                        <div class="row">
                            {{--<div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6">--}}
                                {{--<div class="input-group">--}}
                                    {{--<input type="text" class="textFilterName form-control" name="textFilterName"--}}
                                           {{--placeholder="Tìm theo tên" value="{!! $nameFiler !!}">--}}
                                      {{--<span class="input-group-btn">--}}
                                            {{--<button class="btFilterName btn btn-default" type="button"--}}
                                                    {{--data-href="{!! route('qc.ad3d.work.time-keeping.get') !!}">Tìm--}}
                                            {{--</button>--}}
                                      {{--</span>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6">--}}
                                {{--<select class="cbDayFilter" style="margin-top: 5px; height: 30px;"--}}
                                        {{--data-href="{!! route('qc.ad3d.work.time-keeping-provisional.get') !!}">--}}
                                    {{--<option value="0" @if((int)$monthFilter == 0) selected="selected" @endif >--}}
                                        {{--Tất cả--}}
                                    {{--</option>--}}
                                    {{--@for($i =1;$i<= 31; $i++)--}}
                                        {{--<option value="{!! $i !!}"--}}
                                                {{--@if((int)$dayFilter == $i) selected="selected" @endif >{!! $i !!}</option>--}}
                                    {{--@endfor--}}
                                {{--</select>--}}
                                {{--<span>/</span>--}}
                                {{--<select class="cbMonthFilter" style="margin-top: 5px; height: 30px;"--}}
                                        {{--data-href="{!! route('qc.ad3d.work.time-keeping-provisional.get') !!}">--}}
                                    {{--<option value="null" @if($monthFilter == null) selected="selected" @endif >--}}
                                        {{--Tất cả--}}
                                    {{--</option>--}}
                                    {{--@for($i =1;$i<= 12; $i++)--}}
                                        {{--<option value="{!! $i !!}"--}}
                                                {{--@if((int)$monthFilter == $i) selected="selected" @endif>{!! $i !!}</option>--}}
                                    {{--@endfor--}}
                                {{--</select>--}}
                                {{--<span>/</span>--}}
                                {{--<select class="cbYearFilter" style="margin-top: 5px; height: 30px;"--}}
                                        {{--data-href="{!! route('qc.ad3d.work.time-keeping-provisional.get') !!}">--}}
                                    {{--@for($i =2017;$i<= 2050; $i++)--}}
                                        {{--<option value="{!! $i !!}"--}}
                                                {{--@if($yearFilter == $i) selected="selected" @endif>{!! $i !!}</option>--}}
                                    {{--@endfor--}}
                                {{--</select>--}}

                            {{--</div>--}}
                        </div>
                    </form>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <ul class="nav nav-tabs" role="tablist">
                        <li>
                            <a href="{!! route('qc.ad3d.work.time-keeping-provisional.get') !!}">Mới (Sau tháng 7/2019)</a>
                        </li>
                        <li class="active">
                            <a href="{!! route('qc.ad3d.work.old-time-keeping-provisional.get') !!}">Cũ (Trước tháng 8/2019)</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="qc_ad3d_list_content row"
                 data-href-view="{!! route('qc.ad3d.work.time-keeping-provisional.get') !!}"
                 data-href-confirm="{!! route('qc.ad3d.work.time-keeping-provisional.confirm.get') !!}"
                 data-href-cancel="{!! route('qc.ad3d.work.time-keeping-provisional.cancel.get') !!}">
                @if(count($dataTimekeepingProvisional ) > 0)
                    <?php
                    $perPage = $dataTimekeepingProvisional->perPage();
                    $currentPage = $dataTimekeepingProvisional->currentPage();
                    $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                    ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr style="background-color: whitesmoke;">
                                <th class="text-center qc-padding-none">STT</th>
                                <th class="qc-padding-none">Nhân viên</th>
                                <th class="text-center qc-padding-none">Giờ vào</th>
                                <th class="text-center qc-padding-none">Giờ ra</th>
                                <th class="text-center qc-padding-none">Làm trưa</th>
                                <th class="text-center qc-padding-none">Ảnh BC</th>
                                <th class="text-center qc-padding-none">Ghi chú</th>
                                <th class="qc-padding-none"></th>
                            </tr>
                            @foreach($dataTimekeepingProvisional as $timekeepingProvisional)
                                <?php
                                $timekeepingProvisionalId = $timekeepingProvisional->timekeepingProvisionalId();
                                $timeBegin = $timekeepingProvisional->timeBegin();
                                $timeEnd = $timekeepingProvisional->timeEnd();
                                $note = $timekeepingProvisional->note();
                                $dataTimekeepingProvisionalImage = $timekeepingProvisional->imageOfTimekeepingProvisional($timekeepingProvisionalId);
                                ?>
                                <tr class="qc_ad3d_list_object" data-object="{!! $timekeepingProvisionalId !!}">
                                    <td class="text-center qc-padding-none">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td class="qc-padding-none">
                                        {!! $timekeepingProvisional->work->staff->fullName() !!}
                                    </td>
                                    <td class="text-center qc-padding-none">
                                        <span style="color: brown;">{!! date('d-m-Y', strtotime($timeBegin)) !!}</span>
                                        <span class="qc-font-bold">{!! date('H:i', strtotime($timeBegin)) !!}</span>
                                    </td>
                                    <td class="text-center qc-padding-none">
                                        @if(empty($timeEnd))
                                            <span style="color: brown;">Null</span>
                                        @else
                                            <span style="color: brown;">{!! date('d-m-Y', strtotime($timeEnd)) !!}</span>
                                            <span class="qc-font-bold">{!! date('H:i', strtotime($timeEnd)) !!}</span>
                                        @endif
                                    </td>
                                    <td class="text-center qc-padding-none">
                                        @if($timekeepingProvisional->checkAfternoonWork($timekeepingProvisionalId))
                                            <em style="color: grey;">Có tăng ca trưa</em>
                                        @else
                                            <em class="qc-color-grey">...</em>
                                        @endif
                                    </td>
                                    <td class="qc-padding-none">
                                        @if(count($dataTimekeepingProvisionalImage) > 0)
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                @foreach($dataTimekeepingProvisionalImage as $timekeepingProvisionalImage)
                                                    <div style="position: relative; float: left; margin-right: 10px; width: 70px; height: 70px; border: 1px solid #d7d7d7;">
                                                        <a class="qc_ad3d_timekeeping_provisional_image_view qc-link"
                                                           data-href="{!! route('qc.ad3d.work.time-keeping-provisional.view.get',$timekeepingProvisionalImage->imageId()) !!}">
                                                            <img style="max-width: 100%; max-height: 100%;"
                                                                 src="{!! $timekeepingProvisionalImage->pathSmallImage($timekeepingProvisionalImage->name()) !!}">
                                                        </a>

                                                        <a class="ac_delete_image_action qc-link"
                                                           data-href="{!! route('qc.work.timekeeping.timekeeping_provisional_image.delete', $timekeepingProvisionalImage->imageId()) !!}">
                                                            <i style="position: absolute; font-weight: bold; padding: 0 3px; color: red; top: 3px; right: 3px; border: 1px solid #d7d7d7;">x</i>
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center qc-padding-none">
                                        @if(!empty($note))
                                            <em class="qc-color-grey">{!! $note !!}</em>
                                        @else
                                            <em class="qc-color-grey">...</em>
                                        @endif
                                    </td>
                                    <td class="text-right qc-padding-none">
                                        @if(!$timekeepingProvisional->work->checkSalaryStatus())
                                            @if(!empty($timeEnd))
                                                <a class="qc_confirm qc-link-green">Xác nhận</a>
                                            @endif
                                        @else
                                            <em class="qc-color-grey">Đã tính lương </em>
                                            <span>&nbsp;|&nbsp;</span>
                                            <a class="qc_cancel qc-link-green">Hủy</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                @else
                    <div class="qc_ad3d_list_object qc-ad3d-list-object row">
                        <div class="qc-padding-top-5 qc-padding-bot-5 text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <em class="qc-color-red">Không tìm thấy thông tin phù hợp</em>
                        </div>
                    </div>
                @endif
            </div>
            <div class="row">
                <div class="text-center qc-padding-top-20 qc-padding-bot-20 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    {!! $hFunction->page($dataTimekeepingProvisional) !!}
                </div>
            </div>
        </div>
    </div>
@endsection
