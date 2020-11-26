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
$hrefIndex = route('qc.ad3d.work.time_keeping_provisional.get')
?>
@extends('ad3d.work.time-keeping-provisional.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! $hrefIndex !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <label class="qc-font-size-20" style="color: red;">DUYỆT CHẤM CÔNG</label>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding: 0;">
                    <select class="cbCompanyFilter form-control" name="cbCompanyFilter"
                            data-href-filter="{!! $hrefIndex !!}">
                        @if($hFunction->checkCount($dataCompany))
                            @foreach($dataCompany as $company)
                                @if($dataStaffLogin->checkRootManage())
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
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="qc_ad3d_list_content row ">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr>
                            <td colspan="5" style="background-color: red;">
                                <b style="color: white;">
                                    - Chấm công bị cảnh báo mà không cập nhật sẽ không được tính công.
                                </b>
                                <br/>
                                <b style="color: yellow;">
                                    - Nếu không duyệt Cuối tháng duyệt tự động mặc đinh là đồng Ý.
                                </b>
                            </td>
                        </tr>
                        <tr style="background-color: black; color: yellow;">
                            <th style="width: 220px;">NHÂN VIÊN</th>
                            <th class="text-center" style="width: 200px;">GIỜ CHẤM - GIỜ VÀO - GIỜ RA</th>
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
                                # lay thong tin canh bao cham cong
                                $dataTimekeepingProvisionalWarning = $timekeepingProvisional->timekeepingProvisionalWarningGetInfo();
                                $n_o = $n_o + 1;
                                ?>
                                {{--chi lay thong tin chua tinh luong--}}
                                <tr class="qc_ad3d_list_object @if($n_o%2 == 0) info @endif"
                                    data-object="{!! $timekeepingProvisionalId !!}">
                                    <td>
                                        <div class="media">
                                            <a class="pull-left" href="#">
                                                <img class="media-object"
                                                     style="background-color: white; width: 40px;height: 40px; border: 1px solid #d7d7d7;border-radius: 10px;"
                                                     src="{!! $src !!}">
                                            </a>

                                            <div class="media-body">
                                                <h5 class="media-heading">{!! $dataStaffTimekeepingProvisional->lastName() !!}</h5>
                                                @if(!$timekeepingProvisional->work->checkSalaryStatus())
                                                    @if($hFunction->checkEmpty($timeEnd))
                                                        <em style="color: blue;">Chưa báo giờ ra</em>
                                                    @else
                                                        @if($timekeepingProvisional->checkConfirmStatus())
                                                            <em style="color: grey;">Đã duyệt</em>
                                                        @else
                                                            <a class="qc_confirm qc-link-red qc-font-size-14"
                                                               data-href="{!! route('qc.ad3d.work.time_keeping_provisional.confirm.get', $timekeepingProvisionalId) !!}">
                                                                XÁC NHẬN
                                                            </a>
                                                        @endif
                                                    @endif
                                                @else
                                                    @if(!$timekeepingProvisional->checkConfirmStatus())
                                                        <em style="color: grey;">Không duyệt-</em>
                                                    @endif
                                                @endif
                                                @if(!$hFunction->checkEmpty($note))
                                                    <br/>
                                                    <em class="qc-color-grey">- {!! $note !!}</em>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a class="qc_warning_time_begin qc-link-red" title="Cảnh báo chấm sai"
                                           data-href="{!! route('qc.ad3d.work.time_keeping_provisional.warning_begin.get',$timekeepingProvisionalId) !!}">
                                            <i class="glyphicon glyphicon-warning-sign qc-font-size-14"></i>
                                        </a>
                                        <em class="qc-color-grey">
                                            {!! $hFunction->getTimeFromDate($createdAt) !!} -
                                        </em>
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
                                            <a class="qc_warning_time_end qc-link-red" title="Cảnh báo chấm sai"
                                               data-href="{!! route('qc.ad3d.work.time_keeping_provisional.warning_end.get',$timekeepingProvisionalId) !!}">
                                                <i class="glyphicon glyphicon-warning-sign qc-font-size-14"></i>
                                            </a>
                                            <em style="color: grey">
                                                {!! $hFunction->getTimeFromDate($updatedAt) !!} -
                                            </em>
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
                                        @if($hFunction->checkCount($dataTimekeepingProvisionalWarning))
                                            <br/>
                                            <span style="background-color: black; color: lime;">Bị cảnh báo</span>
                                            @foreach($dataTimekeepingProvisionalWarning as $timekeepingProvisionalWarning)
                                                <?php
                                                $warningId = $timekeepingProvisionalWarning->warningId();
                                                $warningUpdate = $timekeepingProvisionalWarning->updateDate();
                                                ?>
                                                {{--canh bao gio vao--}}
                                                @if($timekeepingProvisionalWarning->checkWarningTimeBegin())
                                                    @if($timekeepingProvisionalWarning->checkUpdateTimeBegin())
                                                        <br/>
                                                        <em style="color: grey;">Báo lại VÀO:</em>
                                                        <span style="color: blue;">{!! $hFunction->convertDateDMYFromDatetime($warningUpdate) !!}</span>
                                                        <span class="qc-font-bold">{!! date('H:i', strtotime($warningUpdate)) !!}</span>
                                                    @else
                                                        <br/>
                                                        <em style="color: grey;">Đã gửi CẢNH BÁO GIỜ VÀO</em>
                                                        <span>|</span>
                                                        <a class="qc_warning_time_begin_cancel qc-link-red qc-font-size-14"
                                                           data-href="{!! route('qc.ad3d.work.time_keeping_provisional.warning_timekeeping.cancel',$warningId) !!}">
                                                            HỦY
                                                        </a>
                                                    @endif
                                                    {{--canh bao gio ra--}}
                                                @endif
                                                @if($timekeepingProvisionalWarning->checkWarningTimeEnd())
                                                    @if($timekeepingProvisionalWarning->checkUpdateTimeEnd())
                                                        <br/>
                                                        <em style="color: grey;">Báo lại giờ ra:</em>
                                                        <span style="color: blue;">{!! $hFunction->convertDateDMYFromDatetime($warningUpdate) !!}</span>
                                                        <span class="qc-font-bold">{!! date('H:i', strtotime($warningUpdate)) !!}</span>
                                                    @else
                                                        <br/>
                                                        <em style="color: grey;">Đã gửi CẢNH BÁO RA</em>
                                                        <span>|</span>
                                                        <a class="qc_warning_time_end_cancel qc-link-red qc-font-size-14"
                                                           data-href="{!! route('qc.ad3d.work.time_keeping_provisional.warning_timekeeping.cancel',$warningId) !!}">
                                                            HỦY
                                                        </a>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endif
                                    </td>
                                    <td style="padding: 3px; ">
                                        @if($hFunction->checkCount($dataTimekeepingProvisionalImageInMorning))
                                            @foreach($dataTimekeepingProvisionalImageInMorning as $timekeepingProvisionalImage)
                                                <a class="qc_ad3d_timekeeping_provisional_image_view qc-link"
                                                   data-href="{!! route('qc.ad3d.work.time_keeping_provisional.view.get',$timekeepingProvisionalImage->imageId()) !!}">
                                                    <img style="width: 70px; height: 70px;"
                                                         src="{!! $timekeepingProvisionalImage->pathSmallImage($timekeepingProvisionalImage->name()) !!}">
                                                </a>
                                            @endforeach
                                        @else
                                            <em style="color: brown;">- Không có báo cáo</em>
                                        @endif
                                    </td>
                                    <td style="padding: 3px;">
                                        @if($hFunction->checkCount($dataTimekeepingProvisionalImageInAfternoon))
                                            @foreach($dataTimekeepingProvisionalImageInAfternoon as $timekeepingProvisionalImage)
                                                <a class="qc_ad3d_timekeeping_provisional_image_view qc-link"
                                                   data-href="{!! route('qc.ad3d.work.time_keeping_provisional.view.get',$timekeepingProvisionalImage->imageId()) !!}">
                                                    <img style="width: 70px; height: 70px;"
                                                         src="{!! $timekeepingProvisionalImage->pathSmallImage($timekeepingProvisionalImage->name()) !!}">
                                                </a>
                                            @endforeach
                                        @else
                                            <em style="color: brown;">- Không có báo cáo</em>
                                        @endif
                                    </td>
                                    <td>
                                        @if($hFunction->checkCount($dataTimekeepingProvisionalImageInEvening))
                                            @foreach($dataTimekeepingProvisionalImageInEvening as $timekeepingProvisionalImage)
                                                <a class="qc_ad3d_timekeeping_provisional_image_view qc-link"
                                                   data-href="{!! route('qc.ad3d.work.time_keeping_provisional.view.get',$timekeepingProvisionalImage->imageId()) !!}">
                                                    <img style="width: 70px; height: 70px;"
                                                         src="{!! $timekeepingProvisionalImage->pathSmallImage($timekeepingProvisionalImage->name()) !!}">
                                                </a>
                                            @endforeach
                                        @else
                                            <em style="color: brown;">- Không có báo cáo</em>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center qc-padding-top-20 qc-padding-bot-20" colspan="5">
                                    {!! $hFunction->page($dataTimekeepingProvisional) !!}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="qc-padding-top-5 qc-padding-bot-5 text-center" colspan="5">
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
