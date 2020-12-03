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
$hrefIndex = route('qc.ad3d.work.time-keeping.get');

?>
@extends('ad3d.work.time-keeping.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! $hrefIndex !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <label class="qc-font-size-20" style="color: red;">CHẤM CÔNG ĐÃ DUYỆT</label>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-right: 0;">
                    <select class="cbCompanyFilter form-control" name="cbCompanyFilter"
                            data-href-filter="{!! $hrefIndex !!}">
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
            <div class="qc_ad3d_list_content row">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style=" background-color: black; color: yellow;">
                            <th style="width: 150px;">NHÂN VIÊN</th>
                            <th class="text-left" style="width: 150px">GIỜ VÀO - GIỜ RA</th>
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
                        <tr>
                            <td style="padding: 0 !important;">
                                <select class="cbStaffFilter form-control" data-href="{!! $hrefIndex !!}"
                                        name="cbStaffFilter">
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
                            <td class="text-center" style="padding: 0;">
                                <select class="cbDayFilter col-sx-4 col-sm-4 col-md-4 col-lg-4"
                                        style="height: 34px; padding: 0;"
                                        data-href="{!! $hrefIndex !!}">
                                    <option value="0" @if((int)$dayFilter == 100) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($d =1;$d<= 31; $d++)
                                        <option value="{!! $d !!}"
                                                @if((int)$dayFilter == $d) selected="selected" @endif >
                                            {!! $d !!}
                                        </option>
                                    @endfor
                                </select>
                                <select class="cbMonthFilter col-sx-4 col-sm-4 col-md-4 col-lg-4"
                                        style="height: 34px; padding: 0;"
                                        data-href="{!! $hrefIndex !!}">
                                    <option value="0" @if((int)$monthFilter == 100) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($m =1;$m<= 12; $m++)
                                        <option value="{!! $m !!}"
                                                @if((int)$monthFilter == $m) selected="selected" @endif>
                                            {!! $m !!}
                                        </option>
                                    @endfor
                                </select>
                                <select class="cbYearFilter col-sx-4 col-sm-4 col-md-4 col-lg-4"
                                        style="height: 34px; padding: 0;"
                                        data-href="{!! $hrefIndex !!}">
                                    <option value="0" @if((int)$yearFilter == 100) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($y =2019;$y<= 2050; $y++)
                                        <option value="{!! $y !!}"
                                                @if($yearFilter == $y) selected="selected" @endif>
                                            {!! $y !!}
                                        </option>
                                    @endfor
                                </select>
                            </td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td></td>
                        </tr>
                        @if($hFunction->checkCount($dataTimekeeping))
                            <?php
                            $perPage = $dataTimekeeping->perPage();
                            $currentPage = $dataTimekeeping->currentPage();
                            $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                            ?>
                            @foreach($dataTimekeeping as $timekeeping)
                                <?php
                                $timekeepingId = $timekeeping->timekeepingId();
                                $timeBegin = $timekeeping->timeBegin();
                                $timeEnd = $timekeeping->timeEnd();
                                $note = $timekeeping->note();
                                $confirmNote = $timekeeping->confirmNote();
                                #bang cham com
                                $dataWork = $timekeeping->work;
                                $companyStaffWorkId = $dataWork->companyStaffWorkId();
                                # hinh anh bao cao
                                $dataTimekeepingImage = $timekeeping->imageOfTimekeeping($timekeepingId);
                                #anh bao cao buoi sang
                                $dataTimekeepingImageInMorning = $timekeeping->infoTimekeepingImageInMorning();
                                #anh bao cao buoi chieu
                                $dataTimekeepingImageInAfternoon = $timekeeping->infoTimekeepingImageInAfternoon();
                                #anh bao cao tang ca
                                $dataTimekeepingImageInEvening = $timekeeping->infoTimekeepingImageInEvening();
                                if (!empty($companyStaffWorkId)) {
                                    $dataStaffTimekeeping = $dataWork->companyStaffWork->staff;
                                } else {
                                    $dataStaffTimekeeping = $dataWork->staff;
                                }
                                # anh dai dien
                                $image = $dataStaffTimekeeping->image();
                                $src = $dataStaffTimekeeping->pathAvatar($image);
                                $n_o = $n_o + 1;
                                ?>
                                <tr class="qc_ad3d_list_object @if($n_o%2 == 0) info @endif"
                                    data-object="{!! $timekeepingId !!}">
                                    <td>
                                        <div class="media">
                                            <a class="pull-left" href="#">
                                                <img class="media-object"
                                                     style="background-color: white; width: 40px;height: 40px; border: 1px solid #d7d7d7;border-radius: 10px;"
                                                     src="{!! $src !!}">
                                            </a>

                                            <div class="media-body">
                                                <h5 class="media-heading">{!! $dataStaffTimekeeping->lastName() !!}</h5>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-left">
                                        @if(!$timekeeping->checkOff())
                                            <span style="color: blue;">{!! date('d-m-Y',strtotime($timeBegin)) !!}</span>
                                            <b>
                                                {!! date('H:i',strtotime($timeBegin)) !!}
                                            </b>
                                            @if(!empty($timeEnd))
                                                <br/>
                                                <span style="color: brown;">{!! date('d-m-Y',strtotime($timeEnd)) !!}</span>
                                                <b> {!! date('H:i',strtotime($timeEnd   )) !!}</b>
                                                <br/>
                                                @if($timekeeping->checkAfternoonStatus())
                                                    <em style="color: grey;">Có làm trưa</em>
                                                @endif
                                            @else
                                                <br/>
                                                <em style="color: grey;">Không có giờ ra</em>
                                            @endif
                                        @else
                                            @if($timekeeping->checkPermissionStatus())
                                                <b>{!! date('d-m-Y', strtotime($timekeeping->dateOff())) !!}</b>
                                                <br/>
                                                <em style="color: grey;">Nghỉ có phép</em>
                                            @else
                                                <b style="color: red;">{!! date('d-m-Y', strtotime($timekeeping->dateOff())) !!}</b>
                                                <br/>
                                                <em style="color: grey;">Nghỉ không phép</em>
                                            @endif
                                        @endif
                                    </td>
                                    <td style="padding: 3px 0; ">
                                        @if($hFunction->checkCount($dataTimekeepingImageInMorning))
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 0;">
                                                @foreach($dataTimekeepingImageInMorning as $timekeepingImage)
                                                    <div style="position: relative; float: left; margin-left: 5px; width: 70px; height: 70px; border: 1px solid #d7d7d7;">
                                                        <a class="qc_ad3d_timekeeping_image_view qc-link"
                                                           data-href="{!! route('qc.ad3d.work.time-keeping.image.view.get',$timekeepingImage->imageId()) !!}">
                                                            <img style="max-width: 100%; max-height: 100%;"
                                                                 src="{!! $timekeepingImage->pathSmallImage($timekeepingImage->name()) !!}">
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
                                        @if($hFunction->checkCount($dataTimekeepingImageInAfternoon))
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 0;">
                                                @foreach($dataTimekeepingImageInAfternoon as $timekeepingImage)
                                                    <div style="position: relative; float: left; margin-left: 5px; width: 70px; height: 70px; border: 1px solid #d7d7d7;">
                                                        <a class="qc_ad3d_timekeeping_image_view qc-link"
                                                           data-href="{!! route('qc.ad3d.work.time-keeping.image.view.get',$timekeepingImage->imageId()) !!}">
                                                            <img style="max-width: 100%; max-height: 100%;"
                                                                 src="{!! $timekeepingImage->pathSmallImage($timekeepingImage->name()) !!}">
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
                                        @if($hFunction->checkCount($dataTimekeepingImageInEvening))
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 0;">
                                                @foreach($dataTimekeepingImageInEvening as $timekeepingImage)
                                                    <div style="position: relative; float: left; margin-left: 5px; width: 70px; height: 70px; border: 1px solid #d7d7d7;">
                                                        <a class="qc_ad3d_timekeeping_image_view qc-link"
                                                           data-href="{!! route('qc.ad3d.work.time-keeping.image.view.get',$timekeepingImage->imageId()) !!}">
                                                            <img style="max-width: 100%; max-height: 100%;"
                                                                 src="{!! $timekeepingImage->pathSmallImage($timekeepingImage->name()) !!}">
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
                                        @if(!empty($note))
                                            <span>Chấm công:</span>
                                            <em class="qc-color-grey">{!! $note !!}</em>
                                        @endif
                                        @if(!empty($confirmNote))
                                            @if(!empty($note)) <br/> @endif
                                            <em class="qc-color-grey">{!! $confirmNote !!}</em>
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="6"
                                    style="border-left: 5px solid blue; padding-top: 0px;padding-bottom: 0;">
                                    {!! $hFunction->page($dataTimekeeping) !!}
                                </td>
                            </tr>
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
        </div>
    </div>
@endsection
