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
# dang nhap vao cty dang lam - cua minh
$actionStatus = true;
if ($companyFilterId != $dataCompanyLogin->companyId()) $actionStatus = false;
?>
@extends('ad3d.work.license-off-work.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! $indexHref !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <label class="qc-font-size-20" style="color: red;">XIN NGHỈ</label>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-right: 0;">
                    <select class="cbCompanyFilter form-control" name="cbCompanyFilter"
                            data-href-filter="{!! $indexHref !!}">
                        @if($hFunction->checkCount($dataCompany))
                            @foreach($dataCompany as $company)
                                @if($dataCompanyLogin->checkParent())
                                    <option value="{!! $company->companyId() !!}"
                                            @if($companyFilterId == $company->companyId()) selected="selected" @endif >{!! $company->name() !!}
                                    </option>
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
                 data-href-confirm="{!! route('qc.ad3d.work.off-work.confirm.get') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: black; color: yellow;">
                            <th >NHÂN VIÊN</th>
                            <th style="width: 170px;">NGÀY NGHỈ - XIN</th>
                            <th>GHI CHÚ</th>
                        </tr>
                        <tr>
                            <td style="padding: 0 !important;">
                                <select class="cbStaffFilter form-control" data-href="{!! $indexHref !!}">
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
                            <td style="padding: 0;">
                                <select class="cbDayFilter col-xs-4 col-sm-4 col-md-4 col-lg-4"
                                        style="height: 34px; padding: 0;" name="cbDayFilter"
                                        data-href="{!! $indexHref !!}">
                                    <option value="0" @if((int)$dayFilter == 100) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($i =1;$i<= 31; $i++)
                                        <option value="{!! $i !!}"
                                                @if((int)$dayFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                                    @endfor
                                </select>
                                <select class="cbMonthFilter col-xs-4 col-sm-4 col-md-4 col-lg-4"
                                        style="height: 34px; padding: 0;" data-href="{!! $indexHref !!}">
                                    <option value="0" @if((int)$monthFilter == 100) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($i =1;$i<= 12; $i++)
                                        <option value="{!! $i !!}"
                                                @if((int)$monthFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                                    @endfor
                                </select>
                                <select class="cbYearFilter col-xs-4 col-sm-4 col-md-4 col-lg-4"
                                        style="height: 34px; padding: 0;" data-href="{!! $indexHref !!}">
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
                                $staffOffId = $licenseOffWork->staffId();
                                # thong tin nguoi nghi
                                $dataStaffOff = $licenseOffWork->staff;
                                $n_o = $n_o + 1;
                                # thong tin nghi cung ngay
                                $dataLicenseOffWorkInDate = $dataCompanyLogin->licenseOffWorkGetAllInfoInDate($dateOff);
                                ?>
                                <tr class="qc_ad3d_list_object @if($n_o%2 == 0) info @endif"
                                    data-object="{!! $licenseId !!}">
                                    <td>
                                        <div class="media">
                                            <a class="pull-left" href="#">
                                                <img class="media-object"
                                                     style="background-color: white; width: 40px;height: 40px; border: 1px solid #d7d7d7;border-radius: 10px;"
                                                     src="{!! $dataStaffOff->pathAvatar($dataStaffOff->image()) !!}">
                                            </a>

                                            <div class="media-body">
                                                <h5 class="media-heading">{!! $dataStaffOff->lastName() !!}</h5>
                                                @if(!$licenseOffWork->checkConfirmStatus())
                                                    @if($actionStatus)
                                                        <a class="qc_confirm qc-link-red-bold">
                                                            XÁC NHẬN
                                                        </a>
                                                    @endif
                                                @else
                                                    @if($licenseOffWork->checkAgreeStatus())
                                                        <i class="glyphicon glyphicon-ok" style="color: green;"></i>
                                                        <em class="qc-color-grey">Đồng ý</em>
                                                    @else
                                                        <i class="glyphicon glyphicon-ok" style="color: red;"></i>
                                                        <em class="qc-color-grey">Không đồng ý</em>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span style="color: blue;">{!! $hFunction->convertDateDMYFromDatetime($dateOff) !!}</span>
                                        <br/>
                                        <em style="color: grey;">- {!! $hFunction->formatDateToDMYHI($createdAd) !!}</em>
                                    </td>
                                    <td class="text-left">
                                        @if(!$hFunction->checkEmpty($note))
                                            <span>- {!! $note !!}</span>
                                        @endif
                                        @if(!$hFunction->checkEmpty($confirmNote))
                                            <br/>
                                            <em style="color: grey;">- Duyệt:</em>
                                            <span class="qc-color-grey">{!! $confirmNote !!}</span>
                                        @endif
                                        <div class="row">
                                            @if($hFunction->checkCount($dataLicenseOffWorkInDate) && count($dataLicenseOffWorkInDate) >= 2)
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    <em style="color: red;">
                                                        - Có người cùng xin nghỉ
                                                    </em>
                                                </div>
                                                @foreach($dataLicenseOffWorkInDate as $licenseOffWorkInDate)
                                                    <?php
                                                    # thong tin nguoi nghi
                                                    $dataStaffOffInDate = $licenseOffWorkInDate->staff;
                                                    $staffOffInDateId = $dataStaffOffInDate->staffId();
                                                    ?>
                                                    @if($staffOffInDateId != $staffOffId)
                                                        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                                            <div class="media">
                                                                <a class="pull-left" href="#">
                                                                    <img class="media-object"
                                                                         style="background-color: white; width: 40px;height: 40px; border: 1px solid #d7d7d7;border-radius: 10px;"
                                                                         src="{!! $dataStaffOffInDate->pathAvatar($dataStaffOffInDate->image()) !!}">
                                                                </a>

                                                                <div class="media-body">
                                                                    <h5 class="media-heading">{!! $dataStaffOffInDate->lastName() !!}</h5>
                                                                    @if(!$licenseOffWorkInDate->checkConfirmStatus())
                                                                        <a class="qc_confirm qc-link-red-bold">
                                                                            Chưa duyệt
                                                                        </a>
                                                                    @else
                                                                        @if($licenseOffWorkInDate->checkAgreeStatus())
                                                                            <i class="glyphicon glyphicon-ok"
                                                                               style="color: green;"></i>
                                                                            <em class="qc-color-grey">Đồng ý</em>
                                                                        @else
                                                                            <i class="glyphicon glyphicon-ok"
                                                                               style="color: red;"></i>
                                                                            <em class="qc-color-grey">Không đồng ý</em>
                                                                        @endif
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="3"
                                    style="border-left: 5px solid blue; padding-top: 0px;padding-bottom: 0;">
                                    {!! $hFunction->page($dataLicenseOffWork) !!}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="qc-padding-top-5 qc-padding-bot-5" colspan="3">
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
