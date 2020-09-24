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
$indexHref = route('qc.ad3d.work.time-keeping.get');

?>
@extends('ad3d.work.time-keeping.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! $indexHref !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <label class="qc-font-size-20">CHẤM CÔNG</label>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-right: 0;">
                    <select class="cbCompanyFilter form-control" name="cbCompanyFilter"
                            data-href-filter="{!! $indexHref !!}">
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
            <div class="qc_ad3d_list_content row"
                 data-href-view="{!! route('qc.ad3d.work.work.view.get') !!}"
                 data-href-confirm="{!! route('qc.ad3d.work.time-keeping.confirm.get') !!}"
                 data-href-del="{!! route('qc.ad3d.work.time-keeping.delete') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style=" background-color: black; color: yellow;">
                            <th class="text-center" style="width: 20px;">STT</th>
                            <th>Nhân viên</th>
                            <th class="text-center">Giờ vào</th>
                            <th class="text-center">Giờ ra</th>
                            <th>Làm trưa</th>
                            <th class="text-center">Nghỉ có phép</th>
                            <th class="text-center">Không phép</th>
                            <th class="text-center">Ảnh BC</th>
                            <th>Ghi chú</th>
                        </tr>
                        <tr>
                            <td class="text-center"></td>
                            <td style="padding: 0px !important; ">
                                <div class="input-group">
                                    <input type="text" class="textFilterName form-control" name="textFilterName"
                                           placeholder="Tìm theo tên" value="{!! $nameFiler !!}">
                                      <span class="input-group-btn">
                                            <button class="btFilterName btn btn-default" type="button"
                                                    data-href="{!! $indexHref !!}">
                                                <i class="glyphicon glyphicon-search"></i>
                                            </button>
                                      </span>
                                </div>
                            </td>
                            <td class="text-center" style="padding: 0;">
                                <select class="cbDayFilter col-sx-3 col-sm-3 col-md-3 col-lg-3"
                                        style="height: 34px; padding: 0;"
                                        data-href="{!! $indexHref !!}">
                                    <option value="100" @if((int)$dayFilter == 100) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($d =1;$d<= 31; $d++)
                                        <option value="{!! $d !!}"
                                                @if((int)$dayFilter == $d) selected="selected" @endif >
                                            {!! $d !!}
                                        </option>
                                    @endfor
                                </select>
                                <select class="cbMonthFilter col-sx-3 col-sm-3 col-md-3 col-lg-3"
                                        style="height: 34px; padding: 0;"
                                        data-href="{!! $indexHref !!}">
                                    <option value="100" @if((int)$monthFilter == 100) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($m =1;$m<= 12; $m++)
                                        <option value="{!! $m !!}"
                                                @if((int)$monthFilter == $m) selected="selected" @endif>
                                            {!! $m !!}
                                        </option>
                                    @endfor
                                </select>
                                <select class="cbYearFilter col-sx-6 col-sm-6 col-md-6 col-lg-6"
                                        style="height: 34px; padding: 0;"
                                        data-href="{!! $indexHref !!}">
                                    <option value="100" @if((int)$yearFilter == 100) selected="selected" @endif >
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
                            <td></td>
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
                                if (!empty($companyStaffWorkId)) {
                                    $dataStaffTimekeeping = $dataWork->companyStaffWork->staff;
                                } else {
                                    $dataStaffTimekeeping = $dataWork->staff;
                                }
                                # anh dai dien
                                $image = $dataStaffTimekeeping->image();
                                $src = $dataStaffTimekeeping->pathAvatar($image);
                                ?>
                                <tr class="qc_ad3d_list_object @if($n_o%2) info @endif"
                                    data-object="{!! $timekeepingId !!}">
                                    <td class="text-center">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td>
                                        <img style="width: 40px; height: 40px; border: 1px solid black;"
                                             src="{!! $src !!}">
                                        {!! $dataStaffTimekeeping->fullName() !!}

                                    </td>
                                    <td class="text-center">
                                        @if(!$timekeeping->checkOff())
                                            @if($hFunction->checkDateIsSunday(date('Y-m-d', strtotime($timeBegin))))
                                                <span>Chủ nhật - </span>
                                            @endif
                                            <span @if($timekeeping->checkWorkLate()) class="qc-color-red" @endif >
                                                {!! $hFunction->convertDateDMYFromDatetime($timeBegin) !!}
                                            </span>
                                            <br/>
                                            <b>{!! $hFunction->getTimeFromDate($timeBegin) !!}</b>
                                        @else
                                            <span>---</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if(!$timekeeping->checkOff())
                                            @if($hFunction->checkEmpty($timeEnd))
                                                Null
                                            @else
                                                @if($timekeeping->checkAfternoonStatus())
                                                    <em>Làm trưa - </em>
                                                @endif
                                                <span @if($timekeeping->checkOvertime()) class="qc-color-green" @endif >
                                                    {!! $hFunction->convertDateDMYFromDatetime($timeEnd) !!}
                                                </span>
                                                <br/>
                                                <b>{!! $hFunction->getTimeFromDate($timeEnd) !!}</b>
                                            @endif
                                        @else
                                            <span>---</span>
                                        @endif
                                    </td>
                                    <td class="text-center qc-color-grey">
                                        @if($timekeeping->checkAfternoonStatus())
                                            <em>Có</em>
                                        @else
                                            <em>Không</em>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($timekeeping->checkOff() && $timekeeping->checkPermissionStatus())
                                            <b class="qc-color-red">{!! $hFunction->convertDateDMYFromDatetime($timekeeping->dateOff()) !!} </b>
                                        @else
                                            <span>---</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($timekeeping->checkOff() && !$timekeeping->checkPermissionStatus())
                                            <b class="qc-color-red">{!! $hFunction->convertDateDMYFromDatetime($timekeeping->dateOff()) !!} </b>
                                        @else
                                            <span>---</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($hFunction->checkCount($dataTimekeepingImage))
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                @foreach($dataTimekeepingImage as $timekeepingImage)
                                                    <div style="position: relative; float: left; margin: 5px; width: 70px; height: 70px; border: 1px solid #d7d7d7;">
                                                        <a class="qc_ad3d_timekeeping_image_view qc-link"
                                                           data-href="{!! route('qc.ad3d.work.time-keeping.image.view.get',$timekeepingImage->imageId()) !!}">
                                                            <img style="max-width: 100%; max-height: 100%;"
                                                                 src="{!! $timekeepingImage->pathSmallImage($timekeepingImage->name()) !!}">
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>

                                        @else
                                            <span>---</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($note))
                                            <span>Chấm công:</span>
                                            <em class="qc-color-grey">{!! $note !!}</em>
                                        @endif
                                        @if(!empty($confirmNote))
                                            @if(!empty($note)) <br/> @endif
                                            <span style="color: brown;">Ghi chú duyệt:</span>
                                            <em class="qc-color-grey">{!! $confirmNote !!}</em>
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="text-center" colspan="9">
                                    Không có thông tin
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>

            </div>
            <div class="row">
                <div class="text-center qc-padding-top-20 qc-padding-bot-20 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    {!! $hFunction->page($dataTimekeeping) !!}
                </div>
            </div>
        </div>
    </div>
@endsection
