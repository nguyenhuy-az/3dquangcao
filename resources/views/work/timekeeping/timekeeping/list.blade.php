<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 5/15/2018
 * Time: 10:12 AM
 *
 * dataStaff
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$loginStaffId = $dataStaff->staffId();
$dataWork = $dataStaff->workInfoActivityOfStaff();
if ($hFunction->checkCount($dataWork)) {
    $workId = $dataWork->workId();
    #thong tin lam viec cua nhan vien
    $dataCompanyStaffWork = $dataWork->companyStaffWork;
// chấm công
    $dataTimekeepingProvisional = $dataWork->infoTimekeepingProvisional($workId, 'DESC');

// xin nghỉ
    $dataLicenseOffWork = $dataStaff->timekeepingOffWorkOfStaff($loginStaffId, date('Y-m'));

// xin trễ
    $dataLicenseLateWork = $dataStaff->timekeepingLateWorkOfStaff($loginStaffId, date('Y-m'));

}

?>
@extends('work.timekeeping.timekeeping.index')
@section('qc_work_timekeeping_body')
    <div class="qc_timekeeping_container_wrap row" data-work="{!! $workId !!}">
        @if($hFunction->checkCount($dataWork))
            {{-- thông tin khách hàng --}}
            <div class="qc_timekeeping_contain col-sx-12 col-sm-12 col-md-12 col-lg-12"
                 data-href-time-end="{!! route('qc.work.timekeeping.timeEnd.get') !!}"
                 data-href-image="{!! route('qc.work.timekeeping.timekeeping_provisional_image.add.get') !!}"
                 data-href-cancel="{!! route('qc.work.timekeeping.cancel.get') !!}">
                {{-- thong tin lam viec --}}
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr>
                            <td style="padding: 0;">
                                <a class="qc_time_begin_action form-control qc-link-white-bold btn btn-primary"
                                   data-href="{!! route('qc.work.timekeeping.timeBegin.get') !!}">
                                    BÁO GIỜ VÀO
                                </a>
                            </td>
                            <td style="padding: 0;">
                                <a class="ac_off_work_action form-control qc-link-green-bold btn btn-default"
                                   data-href="{!! route('qc.work.timekeeping.offWork.get') !!}">
                                    XIN NGHỈ
                                </a>
                            </td>
                            <td style="padding: 0;">
                                <a class="ac_late_work_action form-control qc-link-green-bold btn btn-default"
                                   data-href="{!! route('qc.work.timekeeping.lateWork.get') !!}">
                                    XIN TRỄ
                                </a>
                            </td>
                            <td colspan="3" style="padding: 0;">
                                <div class="form-control col-sx-12 col-sm-12 col-md-12 col-lg-12"
                                     style="background-color: red;">
                                    <span class="qc-font-bold" style="color: white; padding: 3px; font-size: 1.5em;">
                                            17h30
                                    </span>
                                    <span class="qc-font-bold"
                                          style="color: yellow; padding: 3px; font-size: 1.5em;">
                                        Phải có ảnh báo cáo
                                    </span>
                                    <span class="qc-font-bold"
                                          style="color: white; padding: 3px; font-size: 1.5em;">
                                        NẾU KHÔNG SẼ VÔ HIỆU CHẤM CÔNG
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <tr style="background-color: black;color: yellow;">
                            <th style="width: 150px;">GIỜ VÀO</th>
                            <th style="width: 170px;">GIỜ RA</th>
                            <th>
                                BÁO CÁO
                            </th>
                            <th>GHI CHÚ</th>
                        </tr>
                        @if($hFunction->checkCount($dataTimekeepingProvisional))
                            @foreach($dataTimekeepingProvisional as $timekeepingProvisional)
                                <?php
                                $timekeepingProvisionalId = $timekeepingProvisional->timekeepingProvisionalId();
                                $timeBegin = $timekeepingProvisional->timeBegin();
                                $timeEnd = $timekeepingProvisional->timeEnd();
                                $timekeepingNote = $timekeepingProvisional->note();
                                # bao cao buoi sang
                                $dataTimekeepingProvisionalImageInMorning = $timekeepingProvisional->infoTimekeepingProvisionalImageInMorning();
                                # bao cao buoi chieu
                                $dataTimekeepingProvisionalImageInAfternoon = $timekeepingProvisional->infoTimekeepingProvisionalImageInAfternoon();
                                # bao cao tang ca
                                $dataTimekeepingProvisionalImageInEvening = $timekeepingProvisional->infoTimekeepingProvisionalImageInEvening();
                                $checkConfirmStatus = $timekeepingProvisional->checkConfirmStatus($timekeepingProvisionalId);
                                # kiem tra co bao cao trong ngay hay chua
                                if ($hFunction->checkCount($dataTimekeepingProvisionalImageInMorning) || $hFunction->checkCount($dataTimekeepingProvisionalImageInAfternoon) || $hFunction->checkCount($dataTimekeepingProvisionalImageInEvening)) {
                                    # co bao cao
                                    $reportStatus = true;
                                } else {
                                    # khong co bao cao
                                    $reportStatus = false;
                                }
                                # xet han bao gio ra
                                $endCheckStatus = $timekeepingProvisional->checkTimeOutToEndWork();
                                # lay thong tin canh bao gio vao
                                $dataTimekeepingProvisionalWarningTimeBegin = $timekeepingProvisional->timekeepingProvisionalWarningGetTimeBegin();
                                # lay thong tin canh bao gio ra
                                $dataTimekeepingProvisionalWarningTimeEnd = $timekeepingProvisional->timekeepingProvisionalWarningGetTimeEnd();
                                $n_o = (isset($n_o)) ? $n_o + 1 : 1;
                                ?>
                                <tr class="qc_timekeeping_provisional_object @if(!($n_o%2)) info @endif"
                                    data-timekeeping-provisional="{!! $timekeepingProvisionalId !!}">
                                    <td>
                                        <b style="color: blue;">
                                            {!! date('d-m-Y ', strtotime($timeBegin)) !!}
                                        </b>
                                        <span class="qc-font-bold" style="color: brown;">
                                            {!! date('H:i', strtotime($timeBegin)) !!}
                                        </span>
                                        @if($hFunction->checkCount($dataTimekeepingProvisionalWarningTimeBegin))
                                            <?php
                                            $warningNote = $dataTimekeepingProvisionalWarningTimeBegin->note();
                                            $warningImage = $dataTimekeepingProvisionalWarningTimeBegin->image();
                                            ?>
                                            @if($dataTimekeepingProvisionalWarningTimeBegin->checkUpdateTimeBegin())
                                                <br/>
                                                <em style="color: grey;">
                                                    - Báo lại:
                                                </em>
                                                <b style="color: brown;">
                                                    {!! date('H:i ', strtotime($dataTimekeepingProvisionalWarningTimeBegin->updateDate())) !!}
                                                </b>
                                            @else
                                                @if(!$hFunction->checkEmpty($warningNote))
                                                    <br/>
                                                    <span style="background-color: red;color: yellow; padding: 2px 3px;">
                                                        {!! $warningNote !!}
                                                    </span>
                                                @endif
                                                @if(!$hFunction->checkEmpty($warningImage))
                                                    <br/>
                                                    <img style="width: 100%;" alt="anh_canh_bao"
                                                         src="{!! $dataTimekeepingProvisionalWarningTimeBegin->pathSmallImage($warningImage) !!}">
                                                @endif
                                            @endif
                                            @if(!$checkConfirmStatus)
                                                <br/>
                                                <a class="qc_time_begin_edit_action qc-font-size-14 qc-link-red-bold"
                                                   data-href="{!! route('qc.work.timekeeping.timeBegin.edit.get',$timekeepingProvisionalId) !!}">
                                                    SỬA
                                                </a>
                                            @endif
                                        @endif
                                        {{--Huy cham cong--}}
                                        @if($timekeepingProvisional->checkAllowCancel())
                                            <br/>
                                            <a class="qc_time_end_cancel qc-link-red-bold">
                                                <span class="qc-font-size-14" title="Hủy">
                                                    HỦY CHẤM CÔNG
                                                </span>
                                            </a>
                                        @endif
                                        @if($dataCompanyStaffWork->checkExistOverTimeRequestOfDate($timeBegin))
                                            @if($endCheckStatus)
                                                <br/>
                                                <span style="background-color: black; color: lime; padding: 3px;">Yêu cầu tăng ca</span>
                                            @else
                                                <br/>
                                                <em style="color: grey;;">- Có yêu cầu tăng ca</em>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$hFunction->checkEmpty($timeEnd))
                                            <b style="color: blue;">
                                                {!! date('d-m-Y ', strtotime($timeEnd)) !!}
                                            </b>
                                            <span class="qc-font-bold" style="color: brown;">
                                                    {!! date('H:i', strtotime($timeEnd)) !!}
                                            </span>
                                            @if($hFunction->checkCount($dataTimekeepingProvisionalWarningTimeEnd))
                                                <?php
                                                $warningNote = $dataTimekeepingProvisionalWarningTimeEnd->note();
                                                $warningImage = $dataTimekeepingProvisionalWarningTimeEnd->image();
                                                ?>
                                                @if($dataTimekeepingProvisionalWarningTimeEnd->checkUpdateTimeEnd())
                                                    <br/>
                                                    <em style="color: grey;">
                                                        - Báo lại:
                                                    </em>
                                                    <b style="color: grey;"> {!! date('d-m-Y ', strtotime($dataTimekeepingProvisionalWarningTimeEnd->updateDate())) !!}</b>
                                                    <b style="color: brown;"> {!! date('H:i ', strtotime($dataTimekeepingProvisionalWarningTimeEnd->updateDate())) !!}</b>
                                                @else
                                                    @if(!$hFunction->checkEmpty($warningNote))
                                                        <br/>
                                                        <span style="background-color: red;color: yellow; padding: 2px 3px;">
                                                        {!! $warningNote !!}
                                                    </span>
                                                    @endif
                                                    @if(!$hFunction->checkEmpty($warningImage))
                                                        <br/>
                                                        <img style="width: 100%;"
                                                             src="{!! $dataTimekeepingProvisionalWarningTimeEnd->pathSmallImage($warningImage) !!}"
                                                             alt="anh_canh_bao">
                                                    @endif
                                                @endif
                                            @endif
                                            @if(!$checkConfirmStatus)
                                                <br/>
                                                <a class="qc_time_end_edit_action qc-font-size-14 qc-link-red-bold"
                                                   data-href="{!! route('qc.work.timekeeping.timeEnd.edit.get',$timekeepingProvisionalId) !!}">
                                                    BÁO LẠI
                                                </a>
                                            @else
                                                <br/>
                                                <em style="color: grey;">Đã duyệt</em>
                                            @endif
                                            @if($timekeepingProvisional->checkAfternoonWork($timekeepingProvisionalId))
                                                <em class="qc-color-grey"> - Có làm trưa</em>
                                            @endif
                                        @else
                                            @if($endCheckStatus)
                                                @if($timekeepingProvisional->checkDisableReportEndCurrentDate())
                                                    <em style="background: red; color: yellow; padding: 3px;">
                                                        Bị vô hiệu - KHÔNG CÓ BÁO CÁO CUỐI NGÀY
                                                    </em>
                                                @else
                                                    <a class="qc_time_end_action qc-link-red-bold"
                                                       style="font-size: 1.5em;">
                                                        BÁO GIỜ RA
                                                    </a>
                                                @endif
                                            @else
                                                <em class="qc-color-red">Hết hạn báo</em>
                                            @endif
                                        @endif

                                    </td>
                                    <td>
                                        @if($endCheckStatus && !$checkConfirmStatus)
                                            <a class="qc_timekeeping_provisional_image_action qc-link-bold qc-font-size-14">
                                                BÁO CÁO TIẾN ĐỘ
                                            </a>
                                        @endif
                                        {{--Bao cao tien do buoi sang--}}
                                        @if($hFunction->checkCount($dataTimekeepingProvisionalImageInMorning))
                                            <div class="row">
                                                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                                    <em style="color: grey; padding: 3px;">
                                                        Sáng:
                                                    </em>
                                                </div>
                                                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                                    @foreach($dataTimekeepingProvisionalImageInMorning as $timekeepingProvisionalImage)
                                                        <div style="background: white; position: relative; float: left; margin-left: 5px; width: 70px; height: 70px; border: 1px solid #d7d7d7;">
                                                            <a class="qc_work_allocation_report_image_view qc-link"
                                                               data-href="{!! route('qc.work.timekeeping_provisional.image.get', $timekeepingProvisionalImage->imageId()) !!}">
                                                                <img style="max-width: 100%; max-height: 100%;"
                                                                     src="{!! $timekeepingProvisionalImage->pathSmallImage($timekeepingProvisionalImage->name()) !!}">
                                                            </a>
                                                            @if(!$checkConfirmStatus)
                                                                <a class="ac_delete_image_action qc-link"
                                                                   data-href="{!! route('qc.work.timekeeping.timekeeping_provisional_image.delete', $timekeepingProvisionalImage->imageId()) !!}">
                                                                    <i style="background-color: white; position: absolute; font-weight: bold; padding: 0 3px; color: red; top: 3px; right: 3px; border: 1px solid #d7d7d7;">x</i>
                                                                </a>
                                                            @endif
                                                            <em style="position: absolute; left: 0; bottom: 0; color: grey;">
                                                                {!! date('H:i',strtotime($timekeepingProvisionalImage->createdAt())) !!}
                                                            </em>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                        {{--Bao cao tien do buoi chieu--}}
                                        @if($hFunction->checkCount($dataTimekeepingProvisionalImageInAfternoon))
                                            <div class="row">
                                                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                                    <em style="color: grey; padding: 3px;">
                                                        Chiều:
                                                    </em>
                                                </div>
                                                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                                    @foreach($dataTimekeepingProvisionalImageInAfternoon as $timekeepingProvisionalImage)
                                                        <div style="background: white; position: relative; float: left; margin-left: 5px; width: 70px; height: 70px; border: 1px solid #d7d7d7;">
                                                            <a class="qc_work_allocation_report_image_view qc-link"
                                                               data-href="{!! route('qc.work.timekeeping_provisional.image.get', $timekeepingProvisionalImage->imageId()) !!}">
                                                                <img style="max-width: 100%; max-height: 100%;"
                                                                     src="{!! $timekeepingProvisionalImage->pathSmallImage($timekeepingProvisionalImage->name()) !!}">
                                                            </a>
                                                            @if(!$checkConfirmStatus)
                                                                <a class="ac_delete_image_action qc-link"
                                                                   data-href="{!! route('qc.work.timekeeping.timekeeping_provisional_image.delete', $timekeepingProvisionalImage->imageId()) !!}">
                                                                    <i style="background-color: white; position: absolute; font-weight: bold; padding: 0 3px; color: red; top: 3px; right: 3px; border: 1px solid #d7d7d7;">x</i>
                                                                </a>
                                                            @endif
                                                            <em style="position: absolute; left: 0; bottom: 0; color: grey;">
                                                                {!! date('H:i',strtotime($timekeepingProvisionalImage->createdAt())) !!}
                                                            </em>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                        {{--Bao cao tien tang ca--}}
                                        @if($hFunction->checkCount($dataTimekeepingProvisionalImageInEvening))
                                            <div class="row">
                                                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                                    <em style="color: grey; padding: 3px;">
                                                        Tăng ca:
                                                    </em>
                                                </div>
                                                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
                                                     style="padding: 0;">
                                                    @foreach($dataTimekeepingProvisionalImageInEvening as $timekeepingProvisionalImage)
                                                        <div style="background-color: white; position: relative; float: left; margin-left: 5px; width: 70px; height: 70px; border: 1px solid #d7d7d7;">
                                                            <a class="qc_work_allocation_report_image_view qc-link"
                                                               data-href="{!! route('qc.work.timekeeping_provisional.image.get', $timekeepingProvisionalImage->imageId()) !!}">
                                                                <img style="max-width: 100%; max-height: 100%;"
                                                                     src="{!! $timekeepingProvisionalImage->pathSmallImage($timekeepingProvisionalImage->name()) !!}">
                                                            </a>
                                                            @if(!$checkConfirmStatus)
                                                                <a class="ac_delete_image_action qc-link"
                                                                   data-href="{!! route('qc.work.timekeeping.timekeeping_provisional_image.delete', $timekeepingProvisionalImage->imageId()) !!}">
                                                                    <i style="background-color: white; position: absolute; font-weight: bold; padding: 0 3px; color: red; top: 3px; right: 3px; border: 1px solid #d7d7d7;">x</i>
                                                                </a>
                                                            @endif
                                                            <em style="position: absolute; left: 0; bottom: 0; color: grey;">
                                                                {!! date('H:i',strtotime($timekeepingProvisionalImage->createdAt())) !!}
                                                            </em>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                        @if(!$reportStatus)
                                            <div class="row">
                                                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                                    <i class="glyphicon glyphicon-warning-sign qc-font-size-14"
                                                       style="color: red;"></i>
                                                    <em style="color: grey; padding: 3px;">
                                                        Không có báo cáo
                                                    </em>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$hFunction->checkEmpty($timekeepingNote))
                                            <em class="qc-color-grey">{!! $timekeepingNote !!}</em>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="text-center" colspan="4">
                                    Không có thông tin
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
                {{-- xin di tre --}}
                <div class="row">
                    <div class="qc-padding-top-10 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <i class="glyphicon glyphicon-calendar"></i>
                        <b class="qc-color-red" style="font-size: 1.5em;">XIN TRỄ</b>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: black; color: white;">
                            <th class="text-center" style="width: 20px;">STT</th>
                            <th>Ngày</th>
                            <th>Ghi chú trễ</th>
                            <th>Ghi chú Duyệt</th>
                            <th class="text-center">Duyệt</th>
                            <th class="text-right"></th>
                        </tr>
                        @if($hFunction->checkCount($dataLicenseLateWork))
                            @foreach($dataLicenseLateWork as $licenseLateWork)
                                <?php
                                $licenseLateWorkId = $licenseLateWork->licenseId();
                                $dateLate = $licenseLateWork->dateLate();
                                $lateConfirmStatus = $licenseLateWork->confirmStatus();
                                $lateNote = $licenseLateWork->note();
                                $lateConfirmNote = $licenseLateWork->confirmNote();
                                ?>
                                <tr class="qc_timekeeping_late_work_object"
                                    data-late-work="{!! $licenseLateWorkId !!}"
                                    data-href-late-cancel="{!! route('qc.work.timekeeping.lateWork.cancel.get') !!}">
                                    <td class="text-center">
                                        {!! $n_o_late = (isset($n_o_late)) ? $n_o_late + 1 : 1 !!}
                                    </td>
                                    <td>
                                        {!! date('d-m-Y ', strtotime($dateLate)) !!}
                                    </td>
                                    <td>
                                        @if(!$hFunction->checkEmpty($lateNote))
                                            <em class="qc-color-grey">{!! $lateNote !!}</em>
                                        @else
                                            <span>---</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$hFunction->checkEmpty($lateConfirmNote))
                                            <em class="qc-color-grey">{!! $lateConfirmNote !!}</em>
                                        @else
                                            <span>---</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if(!$licenseLateWork->checkConfirmStatus())
                                            <em>Chờ duyệt</em>
                                        @else
                                            @if($licenseLateWork->checkAgreeStatus())
                                                <em>Đồng ý</em>
                                            @else
                                                <em>Không đồng ý</em>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if(!$licenseLateWork->checkConfirmStatus())
                                            <a class="ac_late_work_cancel qc-link-red-bold">
                                                <i class="glyphicon glyphicon-trash" title="Hủy"></i>
                                            </a>
                                        @else
                                            <em>---</em>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="text-center" colspan="6">
                                    Không có thông tin
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
                {{-- xin nghi --}}
                <div class="row ">
                    <div class="qc-padding-top-10 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <i class="glyphicon glyphicon-calendar"></i>
                        <b class="qc-color-red" style="font-size: 1.5em;">XIN NGHỈ</b>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: black; color: white;">
                            <th class="text-center" style="width: 20px;">STT</th>
                            <th>Ngày</th>
                            <th>Ghi chú nghỉ</th>
                            <th>Ghi chú Duyệt</th>
                            <th class="text-center">Duyệt</th>
                            <th class="text-right"></th>
                        </tr>
                        @if($hFunction->checkCount($dataLicenseOffWork))
                            @foreach($dataLicenseOffWork as $licenseOffWork)
                                <?php
                                $licenseOffWorkId = $licenseOffWork->licenseId();
                                $dateOff = $licenseOffWork->dateOff();
                                $offConfirmStatus = $licenseOffWork->confirmStatus();
                                $offNote = $licenseOffWork->note();
                                $confirmNote = $licenseOffWork->confirmNote();
                                ?>
                                <tr class="qc_timekeeping_off_work_object" data-off-work="{!! $licenseOffWorkId !!}"
                                    data-href-off-cancel="{!! route('qc.work.timekeeping.offWork.cancel.get') !!}">
                                    <td class="text-center">
                                        {!! $n_o_off = (isset($n_o_off)) ? $n_o_off + 1 : 1 !!}
                                    </td>
                                    <td>
                                        {!! $hFunction->convertDateDMYFromDatetime($dateOff) !!}
                                    </td>

                                    <td>
                                        @if(!$hFunction->checkEmpty($offNote))
                                            <em class="qc-color-grey">{!! $offNote !!}</em>
                                        @else
                                            <span>---</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$hFunction->checkEmpty($confirmNote))
                                            <em class="qc-color-grey">{!! $confirmNote !!}</em>
                                        @else
                                            <span>---</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if(!$licenseOffWork->checkConfirmStatus())
                                            <em>Chờ duyệt</em>
                                        @else
                                            @if($licenseOffWork->checkAgreeStatus())
                                                <em>Đồng ý</em>
                                            @else
                                                <em>Không đồng ý</em>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if(!$licenseOffWork->checkConfirmStatus())
                                            <a class="ac_off_work_cancel qc-link-red-bold">
                                                <i class="glyphicon glyphicon-trash" title="Hủy"></i>
                                            </a>
                                        @else
                                            <em>---</em>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="text-center" colspan="6">
                                    Không có thông tin
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        @else
            <div class="text-center qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <h3 style="color: red">Đã tắt chấm công</h3>
            </div>
        @endif
    </div>
@endsection
