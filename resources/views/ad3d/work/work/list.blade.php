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
$hrefIndex = route('qc.ad3d.work.work.get');
$dataCompanyLogin = $modelStaff->companyLogin();
# dang nhap vao cty dang lam - cua minh
$actionStatus = true;
if ($companyFilterId != $dataCompanyLogin->companyId()) $actionStatus = false;
?>
@extends('ad3d.work.work.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! $hrefIndex !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <label class="qc-font-size-20" style="color: red;">THÔNG TIN LÀM VIỆC</label>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <select class="cbCompanyFilter form-control" name="cbCompanyFilter"
                            data-href-filter="{!! $hrefIndex !!}">
                        @if($hFunction->checkCount($dataCompany)> 0)
                            @foreach($dataCompany as $company)
                                @if($dataCompanyLogin->checkParent())
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
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="qc_ad3d_list_content row"
                 data-href-view="{!! route('qc.ad3d.work.work.view.get') !!}"
                 data-href-end="{!! route('qc.ad3d.work.work.make_salary.get') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: black; color: yellow;">
                            <th style="width: 200px !important;">NHÂN VIÊN</th>
                            <th style="width: 120px;">THÁNG</th>
                            <th>GIỜ CHÍNH - TĂNG CA</th>
                        </tr>
                        <tr>
                            <td style="padding: 0 !important;">
                                <select class="cbStaffFilter form-control" data-href="{!! $hrefIndex !!}"
                                        name="cbStaffFilter">
                                    <option value="0" @if($staffFilterId == 0) selected="selected" @endif>
                                        Tất cả
                                    </option>
                                    @if($hFunction->checkCount($dataStaffFilter))
                                        @foreach($dataStaffFilter as $staff)
                                            <option @if($staff->staffId() == $staffFilterId) selected="selected"
                                                    @endif  value="{!! $staff->staffId() !!}">{!! $staff->lastName() !!}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </td>
                            <td class="text-center" style="padding: 0;">
                                <select class="cbMonthFilter col-xs-6 col-sm-6 col-md-6 col-lg-6"
                                        style="height: 34px; padding: 0;" data-href="{!! $hrefIndex !!}">
                                    @for($m =1;$m<= 12; $m++)
                                        <option value="{!! $m !!}"
                                                @if((int)$monthFilter == $m) selected="selected" @endif>
                                            {!! $m !!}
                                        </option>
                                    @endfor
                                </select>
                                <select class="cbYearFilter col-xs-6 col-sm-6 col-md-6 col-lg-6"
                                        style="height: 34px; padding: 0;" data-href="{!! $hrefIndex !!}">
                                    @for($y =2019;$y<= 2050; $y++)
                                        <option value="{!! $y !!}"
                                                @if($yearFilter == $y) selected="selected" @endif>
                                            {!! $y !!}
                                        </option>
                                    @endfor
                                </select>
                            </td>
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
                                if (!$hFunction->checkEmpty($companyStaffWorkId)) {
                                    $dataStaffWork = $work->companyStaffWork->staff;
                                } else {
                                    $dataStaffWork = $work->staff;
                                }
                                $n_o = $n_o + 1;
                                ?>
                                <tr class="qc_ad3d_list_object @if($n_o%2 == 0) info @endif"
                                    data-object="{!! $workId !!}">
                                    <td>
                                        <div class="media">
                                            <a class="pull-left" href="#">
                                                <img class="media-object"
                                                     style="background-color: white; width: 40px;height: 40px; border: 1px solid #d7d7d7;border-radius: 10px;"
                                                     src="{!! $dataStaffWork->pathAvatar($dataStaffWork->image()) !!}">
                                            </a>

                                            <div class="media-body">
                                                <h5 class="media-heading">{!! $dataStaffWork->lastName() !!}</h5>
                                                @if(!$work->checkSalaryStatus())
                                                    @if($actionStatus)
                                                        <a class="qc_end qc-link-red-bold" style="color: red;">
                                                            TÍNH LƯƠNG
                                                        </a>
                                                    @endif
                                                @else
                                                    <em class="qc-color-grey">
                                                        Đã Tính lương
                                                    </em>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span>{!! $hFunction->getMonthYearFromDate($work->fromDate()) !!}</span>
                                        <br/>
                                        <span class="qc_view qc-link-green-bold">CHI TIẾT</span>
                                    </td>
                                    <td>
                                        <span>{!! floor(($work->sumMainMinute()- $work->sumMainMinute()%60)/60) !!}</span>
                                        <b>h</b>
                                        <span>{!! $work->sumMainMinute()%60 !!}</span>
                                        <br/>
                                        <span style="color: blue;">{!! floor($work->sumPlusMinute()/60) !!}</span>
                                        <b style="color: blue;">h</b>
                                        <span style="color: blue;">{!! $work->sumPlusMinute()%60 !!}</span>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="3"
                                    style="border-left: 5px solid blue; padding-top: 0px;padding-bottom: 0;">
                                    {!! $hFunction->page($dataWork) !!}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="3">
                                    <em class="qc-color-red">Không tìm thấy thông tin phù hợp</em>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection
