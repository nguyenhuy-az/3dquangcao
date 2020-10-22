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
$totalNewLicenseLateWork = $modelStaff->totalNewLicenseLateWork();
$indexHref = route('qc.ad3d.work.late-work.get');
?>
@extends('ad3d.work.license-late-work.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! $indexHref !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <label class="qc-font-size-20" style="color: red;">XIN VÔ TRỄ</label>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-right: 0;">
                    <select class="cbCompanyFilter form-control" name="cbCompanyFilter"
                            data-href-filter="{!! $indexHref !!}">
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
            <div class="qc_ad3d_list_content row"
                 data-href-confirm="{!! route('qc.ad3d.work.late-work.confirm.get') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: black; color: yellow;">
                            <th class="text-center">STT</th>
                            <th>Nhân viên</th>
                            <th class="text-center">Thời gian xin</th>
                            <th class="text-center">Ngày trễ</th>
                            <th class="text-left">Chi chú xin</th>
                            <th class="text-left">Chi chú duyệt</th>
                            <th></th>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td style="padding: 0;">
                                <select class="cbDayFilter col-sx-4 col-sm-4 col-md-4 col-lg-4" style="height: 30px;"
                                        name="cbDayFilter"
                                        data-href="{!! $indexHref !!}">
                                    <option value="0" @if((int)$dayFilter == 100) selected="selected" @endif >Tất cả
                                    </option>
                                    @for($i =1;$i<= 31; $i++)
                                        <option value="{!! $i !!}"
                                                @if((int)$dayFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                                    @endfor
                                </select>
                                <select class="cbMonthFilter col-sx-4 col-sm-4 col-md-4 col-lg-4" style="height: 30px;"
                                        data-href="{!! $indexHref !!}">
                                    <option value="0" @if((int)$monthFilter == 100) selected="selected" @endif >Tất cả
                                    </option>
                                    @for($i =1;$i<= 12; $i++)
                                        <option value="{!! $i !!}"
                                                @if((int)$monthFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                                    @endfor
                                </select>
                                <select class="cbYearFilter col-sx-4 col-sm-4 col-md-4 col-lg-4" style="height: 30px;"
                                        data-href="{!! $indexHref !!}">
                                    <option value="0" @if((int)$yearFilter == 100) selected="selected" @endif >Tất cả
                                    @for($i =2017;$i<= 2050; $i++)
                                        <option value="{!! $i !!}"
                                                @if($yearFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                                    @endfor
                                </select>
                            </td>
                        </tr>
                        @if($hFunction->checkCount($dataLicenseLateWork ))
                            <?php
                            $perPage = $dataLicenseLateWork->perPage();
                            $currentPage = $dataLicenseLateWork->currentPage();
                            $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                            ?>
                            @foreach($dataLicenseLateWork as $licenseLateWork)
                                <?php
                                $licenseId = $licenseLateWork->licenseId();
                                $dateLate = $licenseLateWork->dateLate();
                                $note = $licenseLateWork->note();
                                $confirmNote = $licenseLateWork->confirmNote();
                                $createdAd = $licenseLateWork->createdAt();
                                # thong tin nguoi xin tre
                                $dataStaffLate = $licenseLateWork->staff;
                                ?>
                                <tr class="qc_ad3d_list_object @if($n_o%2) info @endif"
                                    data-object="{!! $licenseId !!}">
                                    <td class="text-center" style="width: 20px;">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td>
                                        <div class="media">
                                            <a class="pull-left" href="#">
                                                <img class="media-object"
                                                     style="max-width: 40px;height: 40px; border: 1px solid #d7d7d7;border-radius: 10px;"
                                                     src="{!! $dataStaffLate->pathAvatar($dataStaffLate->image()) !!}">
                                            </a>

                                            <div class="media-body">
                                                <h5 class="media-heading">{!! $dataStaffLate->fullName() !!}</h5>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span style="color: black;">{!! $hFunction->convertDateDMYFromDatetime($createdAd) !!}</span>&nbsp;
                                        <span style="font-weight: bold; color: red;">{!! date('H:i', strtotime($createdAd)) !!}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($licenseLateWork->checkConfirmStatus())
                                            <span style="color: black;">{!! $hFunction->convertDateDMYFromDatetime($dateLate) !!}</span>
                                        @else
                                            <span style="font-weight: bold; color: brown;">{!! $hFunction->convertDateDMYFromDatetime($dateLate) !!}</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <em class="qc-color-grey">{!! $note !!}</em>
                                    </td>
                                    <td class="text-left">
                                        <em class="qc-color-grey">{!! $licenseLateWork->confirmNote() !!}</em>
                                    </td>
                                    <td class="text-right">
                                        @if(!$licenseLateWork->checkConfirmStatus())
                                            <a class="qc_confirm qc-link-green">
                                                Xác nhận
                                            </a>
                                        @else
                                            @if($licenseLateWork->checkAgreeStatus())
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
                                    {!! $hFunction->page($dataLicenseLateWork) !!}
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
