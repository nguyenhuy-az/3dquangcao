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
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
             style="margin-bottom: 10px; padding-top : 10px;padding-bottom: 10px; border-bottom: 2px dashed brown;">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! route('qc.ad3d.work.time-keeping-provisional.get') !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <i class="qc-font-size-20 glyphicon glyphicon-list-alt"></i>
                    <label class="qc-font-size-20">DUYỆT CHẤM CÔNG</label>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <select class="cbCompanyFilter" name="cbCompanyFilter" style="margin-top: 5px; height: 25px;"
                            data-href-filter="{!! route('qc.ad3d.work.time-keeping-provisional.get') !!}">
                        @if($dataStaffLogin->checkRootManage())
                            <option value="0">Tất cả</option>
                        @endif
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
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="active">
                            <a href="{!! route('qc.ad3d.work.time-keeping-provisional.get') !!}">Mới (Sau tháng
                                7/2019)</a>
                        </li>
                        <li>
                            <a href="{!! route('qc.ad3d.work.old-time-keeping-provisional.get') !!}">Cũ (Trước tháng
                                8/2019)</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="qc_ad3d_list_content row "
                 data-href-view="{!! route('qc.ad3d.work.time-keeping-provisional.get') !!}"
                 data-href-confirm="{!! route('qc.ad3d.work.time-keeping-provisional.confirm.get') !!}"
                 data-href-cancel="{!! route('qc.ad3d.work.time-keeping-provisional.cancel.get') !!}">

                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: whitesmoke;">
                            <th class="text-center" style="width:20px;">STT</th>
                            <th>Nhân viên</th>
                            <th class="text-center">Giờ chấm</th>
                            <th class="text-center">Giờ vào</th>
                            <th class="text-center">Giờ ra</th>
                            <th class="text-center">Làm trưa</th>
                            <th class="text-center">Ảnh BC</th>
                            <th class="text-center">Ghi chú</th>
                            <th></th>
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
                                #thong tin bang cham cong
                                $dataWork = $timekeepingProvisional->work;
                                $dataCompanyStaffWorkId = $dataWork->companyStaffWorkId();
                                #hinh anh bao cham cong
                                $dataTimekeepingProvisionalImage = $timekeepingProvisional->imageOfTimekeepingProvisional($timekeepingProvisionalId);

                                $beginCheckDate = date('Y-m-d 08:00:00', strtotime($timeBegin));
                                $endCheck = $hFunction->datetimePlusDay($beginCheckDate, 1);
                                $currentDateCheck = $hFunction->carbonNow();
                                if ($endCheck < $currentDateCheck) {
                                    $endCheckStatus = false;
                                } else {
                                    $endCheckStatus = true;
                                }
                                ?>
                                <tr class="qc_ad3d_list_object @if($n_o%2) info @endif"
                                    data-object="{!! $timekeepingProvisionalId !!}">
                                    <td class="text-center">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td>
                                        {!! $dataWork->companyStaffWork->staff->fullName() !!}
                                    </td>
                                    <td class="text-center">
                                        <span class="qc-color-grey">{!! $hFunction->getTimeFromDate($createdAt) !!}</span>
                                    </td>
                                    <td class="text-center">
                                        <span style="color: brown;">{!! $hFunction->convertDateDMYFromDatetime($timeBegin) !!}</span>
                                        <br/>
                                        <span class="qc-font-bold">{!! $hFunction->getTimeFromDate($timeBegin)!!}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($hFunction->checkEmpty($timeEnd))
                                            <span style="color: brown;">Null</span>
                                        @else
                                            <span style="color: brown;">{!! $hFunction->convertDateDMYFromDatetime($timeEnd) !!}</span>
                                            <br/>
                                            <span class="qc-font-bold">{!! $hFunction->getTimeFromDate($timeEnd) !!}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($timekeepingProvisional->checkAfternoonWork($timekeepingProvisionalId))
                                            <em style="color: grey;">Có tăng ca trưa</em>
                                        @else
                                            <em class="qc-color-grey">...</em>
                                        @endif
                                    </td>
                                    <td>
                                        @if($hFunction->checkCount($dataTimekeepingProvisionalImage))
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                @foreach($dataTimekeepingProvisionalImage as $timekeepingProvisionalImage)
                                                    <div style="position: relative; float: left; margin: 5px; width: 70px; height: 70px; border: 1px solid #d7d7d7;">
                                                        <a class="qc_ad3d_timekeeping_provisional_image_view qc-link"
                                                           data-href="{!! route('qc.ad3d.work.time-keeping-provisional.view.get',$timekeepingProvisionalImage->imageId()) !!}">
                                                            <img style="max-width: 100%; max-height: 100%;"
                                                                 src="{!! $timekeepingProvisionalImage->pathSmallImage($timekeepingProvisionalImage->name()) !!}">
                                                        </a>

                                                        <a class="ac_delete_image_action qc-link"
                                                           data-href="{!! route('qc.work.timekeeping.timekeeping_provisional_image.delete', $timekeepingProvisionalImage->imageId()) !!}">
                                                            <i style="position: absolute; font-weight: bold; padding: 0 3px; color: red; top: 3px; right: 3px; border: 1px solid #d7d7d7;">x</i>
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if(!$hFunction->checkEmpty($note))
                                            <em class="qc-color-grey">{!! $note !!}</em>
                                        @else
                                            <em class="qc-color-grey">...</em>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if(!$timekeepingProvisional->work->checkSalaryStatus())
                                            @if($hFunction->checkEmpty($timeEnd))
                                                @if($endCheckStatus)
                                                    <a class="qc_confirm qc-link-green">Xác nhận</a>
                                                @else
                                                    <em class="qc-color-grey">Hết hạn báo giờ ra</em>
                                                    <span>&nbsp;|&nbsp;</span>
                                                    <a class="qc_cancel qc-link-green">Hủy</a>
                                                @endif
                                            @else
                                                <a class="qc_confirm qc-link-green">Xác nhận</a>
                                            @endif
                                        @else
                                            <em class="qc-color-grey">Đã tính lương </em>
                                            <span>&nbsp;|&nbsp;</span>
                                            <a class="qc_cancel qc-link-green">Hủy</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center qc-padding-top-20 qc-padding-bot-20" colspan="9">
                                    {!! $hFunction->page($dataTimekeepingProvisional) !!}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="qc-padding-top-5 qc-padding-bot-5 text-center" colspan="9">
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
