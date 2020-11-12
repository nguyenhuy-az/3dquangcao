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
$dataCompanyLogin = $modelStaff->companyLogin();
$totalNewLicenseOffWork = $dataCompanyLogin->totalLicenseOffWorkUnconfirmed();
$indexHref = route('qc.ad3d.work.off-work.get');
?>
@extends('ad3d.work.license-off-work.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! $indexHref !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <label class="qc-font-size-20" style="color: red;">XIN NGHỈ</label>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-right: 0;">
                    <select class="cbCompanyFilter form-control" name="cbCompanyFilter" data-href-filter="{!! $indexHref !!}">
                        @if($hFunction->checkCount($dataCompany))
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
                 data-href-confirm="{!! route('qc.ad3d.work.off-work.confirm.get') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: black; color: yellow;">
                            <th class="text-center" style="width: 20px;">STT</th>
                            <th>Nhân viên</th>
                            <th style="width: 170px;">Thời gian xin</th>
                            <th class="text-center">Ngày nghỉ</th>
                            <th class="text-left">Chi chú xin</th>
                            <th class="text-left">Chi chú duyệt</th>
                            <th></th>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td style="padding: 0;">
                                <select class="cbDayFilter col-sx-3 col-sm-3 col-md-3 col-lg-3" style="height: 34px; padding: 0;" name="cbDayFilter" data-href="{!! $indexHref !!}">
                                    <option value="0" @if((int)$dayFilter == 100) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($i =1;$i<= 31; $i++)
                                        <option value="{!! $i !!}"
                                                @if((int)$dayFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                                    @endfor
                                </select>
                                <select class="cbMonthFilter col-sx-3 col-sm-3 col-md-3 col-lg-3" style="height: 34px; padding: 0;" data-href="{!! $indexHref !!}">
                                    <option value="0" @if((int)$monthFilter == 100) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($i =1;$i<= 12; $i++)
                                        <option value="{!! $i !!}"
                                                @if((int)$monthFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                                    @endfor
                                </select>
                                <select class="cbYearFilter col-sx-6 col-sm-6 col-md-6 col-lg-6" style="height: 34px; padding: 0;" data-href="{!! $indexHref !!}">
                                    <option value="0" @if((int)$yearFilter == 100) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($i =2017;$i<= 2050; $i++)
                                        <option value="{!! $i !!}"
                                                @if($yearFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                                    @endfor
                                </select>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        @if($hFunction->checkCount($dataLicenseOffWork ))
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
                                # thong tin nguoi nghi
                                $dataStaffOff = $licenseOffWork->staff;
                                ?>
                                <tr class="qc_ad3d_list_object @if($n_o%2) info @endif" data-object="{!! $licenseId !!}">
                                    <td class="text-center">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td>
                                        <div class="media">
                                            <a class="pull-left" href="#">
                                                <img class="media-object"
                                                     style="max-width: 40px;height: 40px; border: 1px solid #d7d7d7;border-radius: 10px;"
                                                     src="{!! $dataStaffOff->pathAvatar($dataStaffOff->image()) !!}">
                                            </a>

                                            <div class="media-body">
                                                <h5 class="media-heading">{!! $dataStaffOff->fullName() !!}</h5>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span style="color: black;">{!! $hFunction->convertDateDMYFromDatetime($createdAd) !!}</span>&nbsp;
                                        <span style="font-weight: bold; color: red;">{!! date('H:i', strtotime($createdAd)) !!}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($licenseOffWork->checkConfirmStatus())
                                            <span style="color: black;">{!! $hFunction->convertDateDMYFromDatetime($dateOff) !!}</span>
                                        @else
                                            <span style="font-weight: bold; color: brown;">{!! $hFunction->convertDateDMYFromDatetime($dateOff) !!}</span>
                                        @endif
                                    </td>
                                    <td class="text-left">
                                        <em class="qc-color-grey">{!! $note !!}</em>
                                    </td>
                                    <td class="text-left">
                                        <em class="qc-color-grey">{!! $licenseOffWork->confirmNote() !!}</em>
                                    </td>
                                    <td class="text-center">
                                        @if(!$licenseOffWork->checkConfirmStatus())
                                            <a class="qc_confirm qc-link-bold">
                                                XÁC NHẬN
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
