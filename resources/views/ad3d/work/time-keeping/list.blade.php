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
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
             style="margin-bottom: 10px; padding-top : 10px;padding-bottom: 10px; border-bottom: 2px dashed brown;">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! $indexHref !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <i class="qc-font-size-20 glyphicon glyphicon-list-alt"></i>
                    <label class="qc-font-size-20">CHẤM CÔNG</label>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <select class="cbCompanyFilter" name="cbCompanyFilter" style="margin-top: 5px; padding: 3px 0;"
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
            <div class="row">
                <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 2px 0 2px 0; ">
                    <form name="" action="">
                        <div class="row">
                            <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <select class="cbDayFilter" style="margin-top: 5px; height: 25px;" data-href="{!! $indexHref !!}">
                                    <option value="100" @if((int)$dayFilter == 100) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($i =1;$i<= 31; $i++)
                                        <option value="{!! $i !!}"
                                                @if((int)$dayFilter == $i) selected="selected" @endif >{!! $i !!}</option>
                                    @endfor
                                </select>
                                <span>/</span>
                                <select class="cbMonthFilter" style="margin-top: 5px; height: 25px;" data-href="{!! $indexHref !!}">
                                    <option value="100" @if((int)$monthFilter == 100) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($i =1;$i<= 12; $i++)
                                        <option value="{!! $i !!}"
                                                @if((int)$monthFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                                    @endfor
                                </select>
                                <span>/</span>
                                <select class="cbYearFilter" style="margin-top: 5px; height: 25px;" data-href="{!! $indexHref !!}">
                                    <option value="100" @if((int)$yearFilter == 100) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($i =2017;$i<= 2050; $i++)
                                        <option value="{!! $i !!}"
                                                @if($yearFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                                    @endfor
                                </select>
                                {{--<a class="btn btn-sm btn-primary" style="height: 25px;" href="{!! route('qc.ad3d.work.time-keeping.off.add.get') !!}">
                                    Nghĩ
                                </a>--}}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="qc_ad3d_list_content row"
                 data-href-view="{!! route('qc.ad3d.work.work.view.get') !!}"
                 data-href-confirm="{!! route('qc.ad3d.work.time-keeping.confirm.get') !!}"
                 data-href-del="{!! route('qc.ad3d.work.time-keeping.delete') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style=" background-color: whitesmoke;">
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
                            <td class="text-center"></td>
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
                                #bang cham com
                                $dataWork = $timekeeping->work;
                                $companyStaffWorkId = $dataWork->companyStaffWorkId();
                                # hinh anh bao cao
                                $dataTimekeepingImage = $timekeeping->imageOfTimekeeping($timekeepingId);
                                ?>
                                <tr class="qc_ad3d_list_object @if($n_o%2) info @endif" data-object="{!! $timekeepingId !!}">
                                    <td class="text-center">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td>
                                        @if(!empty($companyStaffWorkId))
                                            {!! $dataWork->companyStaffWork->staff->fullName() !!}
                                        @else
                                            {!! $dataWork->staff->fullName() !!}
                                        @endif

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
                                        <em class="qc-color-grey">{!! $timekeeping->note() !!}</em>
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
