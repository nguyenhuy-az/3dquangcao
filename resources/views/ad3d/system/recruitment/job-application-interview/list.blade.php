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
$indexHref = route('qc.ad3d.system.job-application-interview.get');
?>
@extends('ad3d.system.recruitment.job-application-interview.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="qc_ad3d_list_content row">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: black;color: yellow;">
                            <th class="text-center" style="width: 20px;">STT</th>
                            <th>Ảnh</th>
                            <th>Tên</th>
                            <th class="text-center">
                                Ngày phỏng vấn
                            </th>
                            <th class="text-center">
                                Xác nhận PV
                            </th>
                            <th>
                                Công ty
                            </th>
                            <th>Bộ phân</th>
                            <th>
                                Kỹ năng
                            </th>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="padding: 0;">
                                <select class="text-center cbConfirmStatusFilter form-control" name="cbConfirmStatusFilter"
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
                            </td>
                            <td style="padding: 0 !important;">
                                <select class="cbCompanyFilter form-control" name="cbCompanyFilter"
                                        data-href="{!! $indexHref !!}">
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
                            </td>
                            <td></td>
                            <td></td>
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
                                ?>
                                <tr class="qc_ad3d_list_object @if($n_o%2) info @endif"
                                    data-object="{!! $interviewId !!}">
                                    <td class="text-center">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td class="text-canter" style="padding: 0;">
                                        <img style="max-width: 50px;height: 50px;" src="{!! $src !!}">
                                    </td>
                                    <td>
                                        <b>{!! $dataJobApplication->firstName().' '.$dataJobApplication->lastName() !!}</b>
                                        <br/>
                                        <em style="color: grey;">Mã HS: {!! $dataJobApplication->nameCode() !!}</em>
                                        <br/>
                                        <a class="qc-link-green-bold"
                                           href="{!! route('qc.ad3d.system.job-application-interview.info.get', $interviewId) !!}">
                                            <i class="glyphicon glyphicon-info-sign qc-font-size-16"></i> Chi tiết
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        {!! date('d/m/Y', strtotime($jobApplicationInterview->interviewDate())) !!}
                                    </td>
                                    <td class="text-center">
                                        @if(!$checkConfirmStatus)
                                            <a class="qc-link-green"
                                               href="{!! route('qc.ad3d.system.job-application-interview.info.get', $interviewId) !!}">
                                                PHỎNG VẤN
                                            </a>
                                        @else
                                            <em style="color: grey;">Đã phỏng vấn</em>
                                            <br/>
                                            @if($jobApplicationInterview->checkAgreeStatus())
                                                <span>ĐẠT</span>
                                            @else
                                                <span>KHÔNG ĐẠT</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        {!! $dataJobApplication->company->name() !!}
                                    </td>
                                    <td>
                                        {!! $dataJobApplication->department->name() !!}
                                    </td>
                                    <td>
                                        @if($hFunction->checkCount($dataJobApplicationWork))
                                            @foreach($dataJobApplicationWork as $jobApplicationWork)
                                                <span>{!! $jobApplicationWork->departmentWork->name() !!}</span>,
                                            @endforeach
                                        @else
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
    </div>
@endsection
