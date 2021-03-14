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
$dataCompanyLogin = $modelStaff->companyLogin();
# dang nhap vao cty dang lam - cua minh
$actionStatus = true;
if ($companyFilterId != $dataCompanyLogin->companyId()) $actionStatus = false;
$indexHref = route('qc.ad3d.system.job-application-interview.get');
?>
@extends('ad3d.system.recruitment.job-application-interview.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="padding: 0;">
                    <select class="cbCompanyFilter form-control" name="cbCompanyFilter"
                            data-href="{!! $indexHref !!}">
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
                    <select class="cbConfirmStatusFilter form-control"
                            name="cbConfirmStatusFilter"
                            data-href="{!! $indexHref !!}">
                        <option value="100" @if($confirmStatusFilter == 100) selected="selected" @endif>
                            TẤT CẢ
                        </option>
                        <option value="0" @if($confirmStatusFilter == 0) selected="selected" @endif>
                            CHƯA PHỎNG VẤN
                        </option>
                        <option value="1" @if($confirmStatusFilter == 1) selected="selected" @endif>Đã
                            ĐÃ PHỎNG VẤN
                        </option>
                    </select>
                </div>
            </div>
            <div class="qc_ad3d_list_content row">
                <table class="table table-hover table-bordered">
                    <tr style="background-color: black;color: yellow;">
                        <th></th>
                        <th style="min-width: 180px;">Tên</th>
                        <th>Bộ phân</th>
                    </tr>
                    @if($hFunction->checkCount($dataJobApplicationInterview))
                        <?php
                        $perPage = $dataJobApplicationInterview->perPage();
                        $currentPage = $dataJobApplicationInterview->currentPage();
                        $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                        ?>
                        @foreach($dataJobApplicationInterview as $jobApplicationInterview)
                            <?php
                            $interviewId = $jobApplicationInterview->interviewId();
                            # trang thai xac nhan phong van
                            $checkConfirmStatus = $jobApplicationInterview->checkInterviewConfirm();
                            # thong tin ho so
                            $dataJobApplication = $jobApplicationInterview->jobApplication;
                            # anh dai dien
                            $image = $dataJobApplication->image();
                            if ($hFunction->checkEmpty($image)) {
                                $src = $dataJobApplication->pathDefaultImage();
                            } else {
                                $src = $dataJobApplication->pathFullImage($image);
                            }
                            # thong tin tay nghe
                            $dataJobApplicationWork = $dataJobApplication->jobApplicationWorkGetInfo();
                            $n_o += 1;
                            ?>
                            <tr class="qc_ad3d_list_object @if($n_o%2) info @endif"
                                data-object="{!! $interviewId !!}">
                                <td class="text-center" style="padding: 0;">
                                    <img class="img-circle"
                                         style="border: 1px solid grey; max-width: 50px;height: 50px;"
                                         src="{!! $src !!}">
                                    <br/>
                                    <span style="color: blue;">
                                        {!! date('d/m/Y', strtotime($jobApplicationInterview->interviewDate())) !!}
                                    </span>
                                </td>
                                <td>
                                    <em>{!! $n_o !!}). </em>
                                    <b>{!! $dataJobApplication->firstName().' '.$dataJobApplication->lastName() !!}</b>
                                    <br/>
                                    <em style="color: grey;">Mã HS: {!! $dataJobApplication->nameCode() !!}</em>
                                    <br/>
                                    <a class="qc-link-green-bold"
                                       href="{!! route('qc.ad3d.system.job-application-interview.info.get', $interviewId) !!}">
                                        <i class="glyphicon glyphicon-info-sign qc-font-size-16"></i> CHI TIẾT
                                    </a>
                                    @if(!$checkConfirmStatus)
                                        <span>&nbsp;|&nbsp;</span>
                                        <a class="qc-link-green"
                                           href="{!! route('qc.ad3d.system.job-application-interview.info.get', $interviewId) !!}">
                                            PHỎNG VẤN
                                        </a>
                                    @else
                                        <span>&nbsp;|&nbsp;</span>
                                        @if($jobApplicationInterview->checkAgreeStatus())
                                            <span style="color: grey;">ĐẠT</span>
                                        @else
                                            <span style="color: grey;">KHÔNG ĐẠT</span>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <b>{!! $dataJobApplication->department->name() !!}</b>
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
                            <td class="text-center" colspan="8">
                                {!! $hFunction->page($dataJobApplicationInterview) !!}
                            </td>
                        </tr>
                    @endif
                </table>
            </div>

        </div>
    </div>
@endsection
