<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:32 PM
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$dataStaffLogin = $modelStaff->loginStaffInfo();
$indexHref = route('qc.ad3d.work.work.get');
?>
@extends('ad3d.work.work.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 10px; padding-top : 10px;">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! $indexHref !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <label class="qc-font-size-20">THÔNG TIN LÀM VIỆC</label>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <select class="cbCompanyFilter form-control" name="cbCompanyFilter"
                            data-href-filter="{!! $indexHref !!}">
                        @if($dataStaffLogin->checkRootManage())
                            <option value="0">Tất cả</option>
                        @endif
                        @if($hFunction->checkCount($dataCompany)> 0)
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
            <div class="qc_ad3d_list_content row"
                 data-href-view="{!! route('qc.ad3d.work.work.view.get') !!}"
                 data-href-end="{!! route('qc.ad3d.work.work.make_salary.get') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: black; color: yellow;">
                            <th class="text-center" style="width: 20px;">STT</th>
                            <th>Nhân viên</th>
                            <th style="width: 120px;">Từ ngày</th>
                            <th style="width: 120px;">Đến ngày</th>
                            <th class="text-center">Giờ chính(h)</th>
                            <th class="text-center">Tăng ca (h)</th>
                            <th></th>
                        </tr>
                        <tr>
                            <td class="text-center"></td>
                            <td style="padding: 0;">
                                <div class="input-group">
                                    <input type="text" class="textFilterName form-control" name="textFilterName"
                                           placeholder="Tìm theo tên" value="{!! $nameFiler !!}">
                                      <span class="input-group-btn">
                                            <button class="btFilterName btn btn-default" type="button"
                                                    data-href="{!! $indexHref !!}">
                                                <i class="glyphicon glyphicon-search"></i>
                                            </button>
                                      </span>
                                </div>
                            </td>
                            <td class="text-center" style="padding: 0;">
                                <select class="cbMonthFilter col-sx-6 col-sm-6 col-md-6 col-lg-6" style="height: 34px; padding: 0;" data-href="{!! $indexHref !!}">
                                    <option value="0" @if((int)$monthFilter == 0) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($m =1;$m<= 12; $m++)
                                        <option value="{!! $m !!}"
                                                @if((int)$monthFilter == $m) selected="selected" @endif>
                                            {!! $m !!}
                                        </option>
                                    @endfor
                                </select>
                                <select class="cbYearFilter col-sx-6 col-sm-6 col-md-6 col-lg-6" style="height: 34px; padding: 0;" data-href="{!! $indexHref !!}">
                                    @for($y =2019;$y<= 2050; $y++)
                                        <option value="{!! $y !!}"
                                                @if($yearFilter == $y) selected="selected" @endif>
                                            {!! $y !!}
                                        </option>
                                    @endfor
                                </select>
                            </td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td></td>
                        </tr>
                        @if($hFunction->checkCount($dataWork))
                            <?php
                            $perPage = $dataWork->perPage();
                            $currentPage = $dataWork->currentPage();
                            $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                            ?>
                            @foreach($dataWork as $work)
                                <?php
                                $workId = $work->workId();
                                $companyStaffWorkId = $work->companyStaffWorkId();
                                $dataCompanyStaffWork = $work->companyStaffWork;
                                ?>
                                <tr class="qc_ad3d_list_object @if($n_o%2) info @endif" data-object="{!! $workId !!}">
                                    <td class="text-center">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td>
                                        @if(!$hFunction->checkEmpty($companyStaffWorkId))
                                            {!! $work->companyStaffWork->staff->fullName() !!}
                                        @else
                                            {!! $work->staff->fullName() !!}
                                        @endif

                                    </td>
                                    <td>
                                        {!! $hFunction->convertDateDMYFromDatetime($work->fromDate()) !!}
                                    </td>
                                    <td>
                                        {!! $hFunction->convertDateDMYFromDatetime($work->toDate()) !!}
                                    </td>
                                    <td class="text-center">
                                        {!! floor(($work->sumMainMinute()- $work->sumMainMinute()%60)/60) !!}
                                        <b>h</b>{!! $work->sumMainMinute()%60 !!}
                                    </td>
                                    <td class="text-center">
                                        {!! floor($work->sumPlusMinute()/60) !!}
                                        <b>h</b>{!! $work->sumPlusMinute()%60 !!}
                                    </td>
                                    <td class="text-right">
                                        <span class="qc_view qc-link-green">Chi tiết</span>
                                        @if(!$work->checkActivity())
                                            <span>&nbsp;|&nbsp;</span>
                                            <span class="qc-color-grey">
                                                Hết hạn
                                            </span>
                                        @endif
                                        @if(!$work->checkSalaryStatus())
                                            <span>&nbsp;|&nbsp;</span>
                                            <span class="qc_end qc-link-green">
                                                Tính lương
                                            </span>
                                        @else
                                            <span>&nbsp;|&nbsp;</span>
                                            <span class="qc-color-grey">
                                                Đã Tính lương
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="qc-padding-top-5 qc-padding-bot-5 text-center col-xs-12 col-sm-12 col-md-12 col-lg-12"
                                    colspan="7">
                                    <em class="qc-color-red">Không tìm thấy thông tin phù hợp</em>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>

            </div>
            <div class="row">
                <div class="text-center qc-padding-top-20 qc-padding-bot-20 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    {!! $hFunction->page($dataWork) !!}
                </div>
            </div>
        </div>
    </div>
@endsection
