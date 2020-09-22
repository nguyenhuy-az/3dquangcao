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
?>
@extends('ad3d.system.recruitment.job-application.index')
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
                            <th>
                                Công ty
                            </th>
                            <th>Bộ phân</th>
                            <th>
                                Kỹ năng
                            </th>
                            <th class="text-center">
                                Ngày nộp
                            </th>
                            <th class="text-center">
                                Duyệt
                            </th>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
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
                            <td></td>
                            <td style="padding: 0;">
                                <select class="cbConfirmStatusFilter form-control" name="cbConfirmStatusFilter"
                                        data-href="{!! $indexHref !!}">
                                    <option value="100" @if($confirmStatusFilter == 100) selected="selected" @endif>Tất cả</option>
                                    <option value="0" @if($confirmStatusFilter == 0) selected="selected" @endif>Chưa duyệt</option>
                                    <option value="1" @if($confirmStatusFilter == 1) selected="selected" @endif>Đã duyệt</option>
                                </select>
                            </td>
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
                                            {!! $n_o += 1 !!}
                                        </td>
                                        <td class="text-canter" style="padding: 0;">
                                            <img style="max-width: 50px;height: 50px;" src="{!! $src !!}">
                                        </td>
                                        <td>
                                            <b>{!! $jobApplication->firstName().' '.$jobApplication->lastName() !!}</b>
                                            <br/>
                                            <em style="color: grey;">Mã HS: {!! $jobApplication->nameCode() !!}</em>
                                            <br/>
                                            <a class="qc-link-green-bold" href="{!! route('qc.ad3d.system.job-application.info.get', $jobApplicationId) !!}">
                                                <i class="glyphicon glyphicon-info-sign qc-font-size-16"></i> Chi tiết
                                            </a>
                                        </td>
                                        <td>
                                            {!! $jobApplication->company->name() !!}
                                        </td>
                                        <td>
                                            {!! $jobApplication->department->name() !!}
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
                                        <td class="text-center">
                                            {!! date('d/m/Y', strtotime($jobApplication->createdAt())) !!}
                                        </td>
                                        <td class="text-center">
                                            @if(!$checkConfirmStatus)
                                                <a class="qc_confirm_get qc-link-green" href="{!! route('qc.ad3d.system.job-application.info.get', $jobApplicationId) !!}">
                                                    Duyệt
                                                </a>
                                            @else
                                                <em style="color: grey;">Đã duyệt</em>
                                            @endif
                                        </td>
                                    </tr>
                            @endforeach
                            <tr>
                                <td class="text-center" colspan="8">
                                    {!! $hFunction->page($dataJobApplication) !!}
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
