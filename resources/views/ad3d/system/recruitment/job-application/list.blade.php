<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:32 PM
 *
 * dataCompany
 *
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$dataStaffLogin = $modelStaff->loginStaffInfo();
$indexHref = route('qc.ad3d.system.job-application.get');
$dataCompanyLogin = $modelStaff->companyLogin();
# dang nhap vao cty dang lam - cua minh
$actionStatus = true;
if ($companyFilterId != $dataCompanyLogin->companyId()) $actionStatus = false;

# mac dinh
$hasConfirm = $modelJobApplication->getDefaultHasConfirm();
$notConfirm = $modelJobApplication->getDefaultNotConfirm();
$allConfirm = $modelJobApplication->getDefaultAllConfirm();
?>
@extends('ad3d.system.recruitment.job-application.index')
@section('qc_ad3d_index_content')
    <div id="qc_ad3d_container_content" class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="padding: 0;">
                    <select class="cbCompanyFilter form-control" name="cbCompanyFilter" data-href="{!! $indexHref !!}">
                        @if($hFunction->checkCount($dataListCompany))
                            @foreach($dataListCompany as $company)
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
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="padding: 0;">
                    <select class="cbConfirmStatusFilter form-control" name="cbConfirmStatusFilter"
                            data-href="{!! $indexHref !!}">
                        <option value="{!! $allConfirm !!}"
                                @if($confirmStatusFilter == $allConfirm) selected="selected" @endif>
                            TẤT CẢ
                        </option>
                        <option value="{!! $notConfirm !!}"
                                @if($confirmStatusFilter == $notConfirm) selected="selected" @endif>
                            CHƯA DUYỆT
                        </option>
                        <option value="{!! $hasConfirm !!}"
                                @if($confirmStatusFilter == $hasConfirm) selected="selected" @endif>
                            ĐÃ DUYỆT
                        </option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="qc_ad3d_list_content row">
                        <table class="table table-hover table-bordered">
                            <tr style="background-color: black;color: yellow;">
                                <th class="text-center"></th>
                                <th style="min-width: 180px;">Tên</th>
                                <th>Bộ phân</th>
                            </tr>
                            @if($hFunction->checkCount($dataJobApplication))
                                <?php
                                $perPage = $dataJobApplication->perPage();
                                $currentPage = $dataJobApplication->currentPage();
                                $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                                ?>
                                @foreach($dataJobApplication as $jobApplication)
                                    <?php
                                    $jobApplicationId = $jobApplication->jobApplicationId();
                                    # anh dai dien
                                    $image = $jobApplication->image();
                                    if ($hFunction->checkEmpty($image)) {
                                        $src = $jobApplication->pathDefaultImage();
                                    } else {
                                        $src = $jobApplication->pathFullImage($image);
                                    }
                                    # trang thai xac nhan
                                    $checkConfirmStatus = $jobApplication->checkConfirmStatus();
                                    # thong tin tay nghe
                                    $dataJobApplicationWork = $jobApplication->jobApplicationWorkGetInfo();
                                    ?>
                                    <tr class="qc_ad3d_list_object @if($n_o%2) info @endif"
                                        data-object="{!! $jobApplicationId !!}">
                                        <td class="text-center">
                                            <img class="img-circle"
                                                 style="border: 1px solid grey; max-width: 50px;height: 50px;"
                                                 src="{!! $src !!}">
                                            <br/>
                                            <span style="color: blue;">
                                                {!! date('d/m/Y', strtotime($jobApplication->createdAt())) !!}
                                            </span>
                                        </td>
                                        <td>
                                            <em>{!! $n_o += 1 !!}).</em>
                                            <b>{!! $jobApplication->firstName().' '.$jobApplication->lastName() !!}</b>
                                            <br/>
                                            <em style="color: grey;">Mã : {!! $jobApplication->nameCode() !!}</em>
                                            <br/>
                                            <a class="qc-link-green-bold"
                                               href="{!! route('qc.ad3d.system.job-application.info.get', $jobApplicationId) !!}">
                                                <i class="glyphicon glyphicon-info-sign qc-font-size-16"></i>
                                                CHI TIẾT
                                            </a>
                                            @if(!$checkConfirmStatus)
                                                @if($actionStatus)
                                                    <span>&nbsp;|&nbsp;</span>
                                                    <a class="qc_confirm_get qc-link-red-bold"
                                                       href="{!! route('qc.ad3d.system.job-application.info.get', $jobApplicationId) !!}">
                                                        DUYỆT
                                                    </a>
                                                @endif
                                            @else
                                                <span>&nbsp;|&nbsp;</span>
                                                @if($jobApplication->checkAgreeStatus())
                                                    <span style="color: grey;">ĐẠT</span>
                                                @else
                                                    <span style="color: grey;">KHÔNG ĐẠT</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <b>{!! $jobApplication->department->name() !!}</b>
                                            @if($hFunction->checkCount($dataJobApplicationWork))
                                                <br/>
                                                @foreach($dataJobApplicationWork as $jobApplicationWork)
                                                    <span style="color: grey;">
                                                        {!! $jobApplicationWork->departmentWork->name() !!}
                                                    </span>
                                                    <span>&nbsp;|&nbsp;</span>
                                                @endforeach
                                            @else
                                                <br/>
                                                <em style="color: grey;">Chưa xác định</em>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="3">
                                        {!! $hFunction->page($dataJobApplication) !!}
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="3">
                                        <span style="color: red;">KHÔNG CÓ HỒ SƠ</span>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection
