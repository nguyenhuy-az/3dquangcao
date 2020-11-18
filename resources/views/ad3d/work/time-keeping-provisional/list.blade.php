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

?>
@extends('ad3d.work.time-keeping-provisional.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! route('qc.ad3d.work.time-keeping-provisional.get') !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <label class="qc-font-size-20" style="color: red;">DUYỆT CHẤM CÔNG</label>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding: 0;">
                    <select class="cbCompanyFilter form-control" name="cbCompanyFilter"
                            data-href-filter="{!! route('qc.ad3d.work.time-keeping-provisional.get') !!}">
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
            <div class="qc_ad3d_list_content row "
                 data-href-view="{!! route('qc.ad3d.work.time-keeping-provisional.get') !!}"
                 data-href-confirm="{!! route('qc.ad3d.work.time-keeping-provisional.confirm.get') !!}"
                 data-href-cancel="{!! route('qc.ad3d.work.time-keeping-provisional.cancel.get') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr>
                            <td colspan="7" style="background-color: red;">
                                <span style="color: white;">CHỈ DUYỆT CHẤM CÔNG TRONG THÁNG HIỆN TẠI</span>
                            </td>
                        </tr>
                        <tr style="background-color: black; color: yellow;">
                            <th class="text-center" style="width:20px;">STT</th>
                            <th>NHÂN VIÊN</th>
                            <th class="text-center">GIỜ CHẤM - GIỜ VÀO - GIỜ RA</th>
                            <th>
                                BÁO CÁO BUỔI SÁNG
                                <br/>
                                <em style="color: white;">(Trước 13h30)</em>
                            </th>
                            <th>
                                BÁO CÁO BUỔI CHIỀU
                                <br/>
                                <em style="color: white;">(Từ 13h30 -> Trước 18h)</em>
                            </th>
                            <th>
                                BÁO CÁO TĂNG CA
                                <br/>
                                <em style="color: white;">(Sau 18h)</em>
                            </th>
                            <th>GHI CHÚ</th>
                        </tr>
                        @if($hFunction->checkCount($dataTimekeepingProvisional ))
                            <?php
                            $perPage = $dataTimekeepingProvisional->perPage();
                            $currentPage = $dataTimekeepingProvisional->currentPage();
                            $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                            ?>
                            @foreach($dataTimekeepingProvisional as $timekeepingProvisional)
                                <?php
                                $timekeepingProvisionalId = $timekeepingProvisional->timekeepingProvisionalId();
                                $timeBegin = $timekeepingProvisional->timeBegin();
                                $timeEnd = $timekeepingProvisional->timeEnd();
                                $note = $timekeepingProvisional->note();
                                $createdAt = $timekeepingProvisional->createdAt();
                                $updatedAt = $timekeepingProvisional->updatedAt();
                                #thong tin lam viec cua nhan vien
                                $dataWork = $timekeepingProvisional->work;
                                $dataCompanyStaffWork = $dataWork->companyStaffWork;
                                $companyStaffWorkId = $dataCompanyStaffWork->workId();
                                #anh bao cao buoi sang
                                $dataTimekeepingProvisionalImageInMorning = $timekeepingProvisional->infoTimekeepingProvisionalImageInMorning();
                                #anh bao cao buoi chieu
                                $dataTimekeepingProvisionalImageInAfternoon = $timekeepingProvisional->infoTimekeepingProvisionalImageInAfternoon();
                                #anh bao cao tang ca
                                $dataTimekeepingProvisionalImageInEvening = $timekeepingProvisional->infoTimekeepingProvisionalImageInEvening();
                                $beginCheckDate = date('Y-m-d 08:00:00', strtotime($timeBegin));
                                $endCheck = $hFunction->datetimePlusDay($beginCheckDate, 1);
                                $currentDateCheck = $hFunction->carbonNow();
                                if ($endCheck < $currentDateCheck) {
                                    $endCheckStatus = false;
                                } else {
                                    $endCheckStatus = true;
                                }
                                $dataStaffTimekeepingProvisional = $dataWork->companyStaffWork->staff;
                                # anh dai dien
                                $image = $dataStaffTimekeepingProvisional->image();
                                $src = $dataStaffTimekeepingProvisional->pathAvatar($image);
                                # thong tin yeu cau tang ca
                                $dataOverTimeRequest = $dataCompanyStaffWork->overTimeRequestGetInfoInDate($timeBegin);
                                ?>
                                {{--chi lay thong tin chua tinh luong--}}
                                <tr class="qc_ad3d_list_object @if($n_o%2) info @endif"
                                    data-object="{!! $timekeepingProvisionalId !!}">
                                    <td class="text-center">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td>
                                        <div class="media">
                                            <a class="pull-left" href="#">
                                                <img class="media-object"
                                                     style="background-color: white; width: 40px;height: 40px; border: 1px solid #d7d7d7;border-radius: 10px;"
                                                     src="{!! $src !!}">
                                            </a>

                                            <div class="media-body">
                                                <h5 class="media-heading">{!! $dataStaffTimekeepingProvisional->fullName() !!}</h5>
                                                @if(!$timekeepingProvisional->work->checkSalaryStatus())
                                                    @if($hFunction->checkEmpty($timeEnd))
                                                        <em style="color: blue;">Chưa báo giờ ra</em>
                                                    @else
                                                        @if($timekeepingProvisional->checkConfirmStatus())
                                                            <em style="color: grey;">Đã duyệt</em>
                                                        @else
                                                            <a class="qc_confirm qc-link-green-bold qc-font-size-14">
                                                                XÁC NHẬN
                                                            </a>
                                                        @endif
                                                    @endif
                                                @else
                                                    @if(!$timekeepingProvisional->checkConfirmStatus())
                                                        <em style="color: grey;">Không duyệt-</em>
                                                    @endif
                                                    <span style="color: red;">
                                                        Đã xuất bảng lương
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <em class="qc-color-grey">{!! $hFunction->getTimeFromDate($createdAt) !!}
                                            - </em>
                                        <span style="color: brown;">{!! $hFunction->convertDateDMYFromDatetime($timeBegin) !!}</span>
                                        <span class="qc-font-bold">{!! date('H:i', strtotime($timeBegin))!!}</span>
                                        <br/>
                                        @if($hFunction->checkEmpty($timeEnd))
                                            {{--chi bao tang ca khi chua bao gio ra--}}
                                            @if($hFunction->checkCount($dataOverTimeRequest))
                                                <span style="background-color: red; color: yellow; padding: 3px;">Đã báo Tăng ca</span>
                                                <a class="qc_over_time_request_cancel qc-link-red qc-font-size-12"
                                                   data-href="{!! route('qc.ad3d.work.time_keeping_provisional.over_time.cancel',$dataOverTimeRequest->requestId()) !!}">
                                                    - HỦY
                                                </a>
                                            @else
                                                <a class="qc_over_time_request_get qc-link-red qc-font-size-14"
                                                   data-href="{!! route('qc.ad3d.work.time_keeping_provisional.over_time.get',$companyStaffWorkId) !!}">
                                                    BÁO TĂNG CA
                                                </a>
                                            @endif
                                        @else
                                            <em style="color: grey">{!! $hFunction->getTimeFromDate($updatedAt) !!}
                                                - </em>
                                            <span style="color: blue;">{!! $hFunction->convertDateDMYFromDatetime($timeEnd) !!}</span>
                                            <span class="qc-font-bold">{!! date('H:i', strtotime($timeEnd)) !!}</span>

                                            @if($timekeepingProvisional->checkAfternoonWork($timekeepingProvisionalId))
                                                <br/>
                                                <em style="color: grey;">Có làm trưa</em>
                                            @endif
                                            @if($hFunction->checkCount($dataOverTimeRequest))
                                                <br/>
                                                <span style="background-color: red; color: white; padding: 3px;">Có báo Tăng ca</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td style="padding: 3px 0; ">
                                        @if($hFunction->checkCount($dataTimekeepingProvisionalImageInMorning))
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 0;">
                                                @foreach($dataTimekeepingProvisionalImageInMorning as $timekeepingProvisionalImage)
                                                    <div style="position: relative; float: left; margin-left: 5px; width: 70px; height: 70px; border: 1px solid #d7d7d7;">
                                                        <a class="qc_ad3d_timekeeping_provisional_image_view qc-link"
                                                           data-href="{!! route('qc.ad3d.work.time-keeping-provisional.view.get',$timekeepingProvisionalImage->imageId()) !!}">
                                                            <img style="max-width: 100%; max-height: 100%;"
                                                                 src="{!! $timekeepingProvisionalImage->pathSmallImage($timekeepingProvisionalImage->name()) !!}">
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                                <em style="color: brown;">- Không có báo cáo</em>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($hFunction->checkCount($dataTimekeepingProvisionalImageInAfternoon))
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 0;">
                                                @foreach($dataTimekeepingProvisionalImageInAfternoon as $timekeepingProvisionalImage)
                                                    <div style="position: relative; float: left; margin-left: 5px; width: 70px; height: 70px; border: 1px solid #d7d7d7;">
                                                        <a class="qc_ad3d_timekeeping_provisional_image_view qc-link"
                                                           data-href="{!! route('qc.ad3d.work.time-keeping-provisional.view.get',$timekeepingProvisionalImage->imageId()) !!}">
                                                            <img style="max-width: 100%; max-height: 100%;"
                                                                 src="{!! $timekeepingProvisionalImage->pathSmallImage($timekeepingProvisionalImage->name()) !!}">
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                                <em style="color: brown;">- Không có báo cáo</em>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($hFunction->checkCount($dataTimekeepingProvisionalImageInEvening))
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 0;">
                                                @foreach($dataTimekeepingProvisionalImageInEvening as $timekeepingProvisionalImage)
                                                    <div style="position: relative; float: left; margin-left: 5px; width: 70px; height: 70px; border: 1px solid #d7d7d7;">
                                                        <a class="qc_ad3d_timekeeping_provisional_image_view qc-link"
                                                           data-href="{!! route('qc.ad3d.work.time-keeping-provisional.view.get',$timekeepingProvisionalImage->imageId()) !!}">
                                                            <img style="max-width: 100%; max-height: 100%;"
                                                                 src="{!! $timekeepingProvisionalImage->pathSmallImage($timekeepingProvisionalImage->name()) !!}">
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                                <em style="color: brown;">- Không có báo cáo</em>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$hFunction->checkEmpty($note))
                                            <em class="qc-color-grey">{!! $note !!}</em>
                                        @else
                                            <em class="qc-color-grey">...</em>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center qc-padding-top-20 qc-padding-bot-20" colspan="7">
                                    {!! $hFunction->page($dataTimekeepingProvisional) !!}
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
