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
?>
@extends('ad3d.work.work.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
             style="margin-bottom: 10px; padding-top : 10px;padding-bottom: 10px; border-bottom: 2px dashed brown;">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! route('qc.ad3d.work.work.get') !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <i class="qc-font-size-20 glyphicon glyphicon-list-alt"></i>
                    <label class="qc-font-size-20">THÔNG TIN LÀM VIỆC</label>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <select class="cbCompanyFilter" name="cbCompanyFilter" style="margin-top: 5px; height: 25px;"
                            data-href-filter="{!! route('qc.ad3d.work.work.get') !!}">
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
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="active">
                            <a href="{!! route('qc.ad3d.work.work.get') !!}">Mới (Sau tháng 7/2019)</a>
                        </li>
                        <li>
                            <a href="{!! route('qc.ad3d.work.work.old.get') !!}">Cũ (Trước tháng 8/2019)</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 2px 0 2px 0;">
                    <form name="" action="">
                        <div class="row">
                            <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="input-group">
                                    <input type="text" class="textFilterName form-control" name="textFilterName"
                                           style="height: 25px;"
                                           placeholder="Tìm theo tên" value="{!! $nameFiler !!}">
                                      <span class="input-group-btn">
                                            <button class="btFilterName btn btn-default" type="button"
                                                    style="height: 25px;"
                                                    data-href="{!! route('qc.ad3d.work.work.get') !!}">Tìm
                                            </button>
                                      </span>
                                </div>
                            </div>
                            <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <select class="cbMonthFilter" style="margin-top: 5px; height: 25px;"
                                        data-href="{!! route('qc.ad3d.work.work.get') !!}">
                                    <option value="0" @if((int)$monthFilter == 0) selected="selected" @endif >Tất cả
                                    </option>
                                    @for($i =1;$i<= 12; $i++)
                                        <option value="{!! $i !!}"
                                                @if((int)$monthFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                                    @endfor
                                </select>
                                <span>/</span>
                                <select class="cbYearFilter" style="margin-top: 5px; height: 25px;"
                                        data-href="{!! route('qc.ad3d.work.work.get') !!}">
                                    @for($i =2017;$i<= 2050; $i++)
                                        <option value="{!! $i !!}"
                                                @if($yearFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="qc_ad3d_list_content row"
                 data-href-view="{!! route('qc.ad3d.work.work.view.get') !!}"
                 data-href-end="{!! route('qc.ad3d.work.work.make_salary.get') !!}">
                @if(count($dataWork) > 0)
                    <?php
                    $perPage = $dataWork->perPage();
                    $currentPage = $dataWork->currentPage();
                    $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                    ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr style="background-color: whitesmoke;">
                                <th class="text-center">STT</th>
                                <th >Nhân viên</th>
                                <th class="text-center">Từ ngày</th>
                                <th class="text-center">Đến ngày</th>
                                <th class="text-center">Giờ chính(h)</th>
                                <th class="text-center">Tăng ca (h)</th>
                                <th ></th>
                            </tr>
                            @foreach($dataWork as $work)
                                <?php
                                $workId = $work->workId();
                                $companyStaffWorkId = $work->companyStaffWorkId();
                                $dataCompanyStaffWork = $work->companyStaffWork;
                                ?>
                                <tr class="qc_ad3d_list_object" data-object="{!! $workId !!}">
                                    <td class="text-center">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td>
                                        @if(!empty($companyStaffWorkId))
                                            {!! $work->companyStaffWork->staff->fullName() !!}
                                        @else
                                            {!! $work->staff->fullName() !!}
                                        @endif

                                    </td>
                                    <td class="text-center">
                                        {!! date('d-m-Y', strtotime($work->fromDate())) !!}
                                    </td>
                                    <td class="text-center">
                                        {!! date('d-m-Y', strtotime($work->toDate())) !!}
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
                    {!! $hFunction->page($dataWork) !!}
                </div>
            </div>
        </div>
    </div>
@endsection
