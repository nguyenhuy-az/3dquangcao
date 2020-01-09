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
$totalNewLicenseOffWork = $modelStaff->totalNewLicenseOffWork();

?>
@extends('ad3d.work.license-off-work.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
             style="margin-bottom: 10px; padding-top : 10px;padding-bottom: 10px; border-bottom: 2px dashed brown;">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! route('qc.ad3d.work.off-work.get') !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <i class="qc-font-size-20 glyphicon glyphicon-list-alt"></i>
                    <label class="qc-font-size-20">XIN NGHỈ</label>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <select class="cbCompanyFilter" name="cbCompanyFilter" style="margin-top: 5px; height: 25px;"
                            data-href-filter="{!! route('qc.ad3d.work.off-work.get') !!}">
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
                            <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <select class="cbDayFilter" style="margin-top: 5px; height: 25px;" name="cbDayFilter"
                                        data-href="{!! route('qc.ad3d.work.off-work.get') !!}">
                                    <option value="0" @if((int)$dayFilter == 0) selected="selected" @endif >Tất cả
                                    </option>
                                    @for($i =1;$i<= 31; $i++)
                                        <option value="{!! $i !!}"
                                                @if((int)$dayFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                                    @endfor
                                </select>
                                <span>/</span>
                                <select class="cbMonthFilter" style="margin-top: 5px; height: 25px;"
                                        data-href="{!! route('qc.ad3d.work.off-work.get') !!}">
                                    <option value="0" @if((int)$monthFilter == 0) selected="selected" @endif >Tất cả
                                    </option>
                                    @for($i =1;$i<= 12; $i++)
                                        <option value="{!! $i !!}"
                                                @if((int)$monthFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                                    @endfor
                                </select>
                                <span>/</span>
                                <select class="cbYearFilter" style="margin-top: 5px; height: 25px;"
                                        data-href="{!! route('qc.ad3d.work.off-work.get') !!}">
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
                 data-href-confirm="{!! route('qc.ad3d.work.off-work.confirm.get') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: whitesmoke;">
                            <th class="text-center">STT</th>
                            <th>Nhân viên</th>
                            <th class="text-center">Thời gian xin</th>
                            <th class="text-center">Ngày nghỉ</th>
                            <th class="text-left">Chi chú xin</th>
                            <th class="text-left">Chi chú duyệt</th>
                            <th></th>
                        </tr>
                        @if(count($dataLicenseOffWork ) > 0)
                            <?php
                            $perPage = $dataLicenseOffWork->perPage();
                            $currentPage = $dataLicenseOffWork->currentPage();
                            $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                            ?>
                            @foreach($dataLicenseOffWork as $licenseOffWork)
                                <?php
                                $licenseId = $licenseOffWork->licenseId();
                                $dateOff = $licenseOffWork->dateOff();
                                $note = $licenseOffWork->note();
                                $confirmNote = $licenseOffWork->confirmNote();
                                $createdAd = $licenseOffWork->createdAt();
                                ?>
                                <tr class="qc_ad3d_list_object" data-object="{!! $licenseId !!}">
                                    <td class="text-center">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td>
                                        {!! $licenseOffWork->staff->fullname() !!}
                                    </td>
                                    <td class="text-center">
                                        <span style="color: black;">{!! date('d-m-Y', strtotime($createdAd)) !!}</span>&nbsp;
                                        <span style="font-weight: bold; color: red;">{!! date('H:i', strtotime($createdAd)) !!}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($licenseOffWork->checkConfirmStatus())
                                            <span style="color: black;">{!! date('d-m-Y', strtotime($dateOff)) !!}</span>
                                        @else
                                            <span style="font-weight: bold; color: brown;">{!! date('d-m-Y', strtotime($dateOff)) !!}</span>
                                        @endif
                                    </td>
                                    <td class="text-left">
                                        <em class="qc-color-grey">{!! $note !!}</em>
                                    </td>
                                    <td class="text-left">
                                        <em class="qc-color-grey">{!! $licenseOffWork->confirmNote() !!}</em>
                                    </td>
                                    <td class="text-right">
                                        @if(!$licenseOffWork->checkConfirmStatus())
                                            <a class="qc_confirm qc-link-bold">
                                                Xác nhận
                                            </a>
                                        @else
                                            @if($licenseOffWork->checkAgreeStatus())
                                                <span class="qc-color-grey">Đồng ý</span>
                                            @else
                                                <span class="qc-color-grey">Không đồng ý</span>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center qc-padding-top-20 qc-padding-bot-20" colspan="7">
                                    {!! $hFunction->page($dataLicenseOffWork) !!}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="qc-padding-top-5 qc-padding-bot-5 text-center" colspan="7">
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
