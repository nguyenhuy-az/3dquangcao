<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 */
/*
 *dataCompany
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
?>
@extends('ad3d.components.container.container-8')
@section('qc_ad3d_container_content')
    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
        <h3>CHẤM CÔNG </h3>
    </div>
    <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <form class="frmConfirm" role="form" method="post"
              action="{!! route('qc.ad3d.work.time-keeping.confirm.post', $dataTimekeeping->timeKeepingId() ) !!}">
            <div class="row">
                <div class="notifyConfirm qc-color-red text-center col-xs-12 col-sm-12 col-md-6 col-lg-6"></div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="form-group form-group-sm">
                        <label>Nhân viên:</label>
                        <input class="form-control" readonly="true"
                               value="{!! $dataTimekeeping->work->staff->fullName() !!}"/>
                    </div>
                </div>
            </div>
            @if(count($dataTimekeeping) > 0)
                <div class="row">
                    <div class="form-group form-group-sm">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <?php
                            $timeBegin = $dataTimekeeping->timeBegin();
                            $yearBegin = $hFunction->getYearFromDate($timeBegin);
                            $monthBegin = $hFunction->getMonthFromDate($timeBegin);
                            $dayBegin = $hFunction->getDayFromDate($timeBegin);
                            $timeEnd = $dataTimekeeping->timeEnd();
                            if (!empty($timeEnd)) {
                                $yearEnd = $hFunction->getYearFromDate($timeBegin);
                                $monthEnd = $hFunction->getMonthFromDate($timeBegin);
                                $dayEnd = $hFunction->getDayFromDate($timeBegin);
                                $hoursEnd = $hFunction->getHoursFromTime($timeEnd);
                                $minuteEnd = $hFunction->getMinuteFromTime($timeEnd);

                            } else {
                                $yearEnd = $yearBegin;
                                $monthEnd = $monthBegin;
                                $dayEnd = $dayBegin;
                                $hoursEnd = 0;
                                $minuteEnd = 0;

                            }
                            ?>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label>Giờ vào:</label>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <input class="form-control" readonly="true" value="{!! $dataTimekeeping->timeBegin() !!}"/>
                        </div>
                    </div>
                </div>
                <div class="row qc-margin-top-15">
                    <div class="form-group form-group-sm">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label>Giờ ra (Năm\Tháng\Ngày\Giờ\Phút): <i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <select name="cbYearEnd" style="margin-top: 5px; height: 25px;">
                                <option value="">Năm</option>
                                <option value="{!! $yearBegin -1 !!}"
                                        @if($yearBegin - 1 == $yearEnd) selected="selected" @endif >
                                    {!! $yearBegin-1 !!}
                                </option>
                                <option value="{!! $yearBegin !!}"
                                        @if($yearBegin == $yearEnd) selected="selected" @endif>
                                    {!! $yearBegin !!}
                                </option>
                                <option value="{!! $yearBegin +1 !!}"
                                        @if($yearBegin + 1 == $yearEnd) selected="selected" @endif >
                                    {!! $yearBegin+1 !!}
                                </option>
                            </select>
                            <span>/</span>
                            <select name="cbMonthEnd" style="margin-top: 5px; height: 25px;">
                                <option value="">Tháng</option>
                                @for($i = 1;$i<= 12; $i++)
                                    <option value="{!! $i !!}"
                                            @if($monthEnd == $i) selected="selected" @endif> {!! $i !!}</option>
                                @endfor
                            </select>
                            <span>/</span>
                            <select name="cbDayEnd" style="margin-top: 5px; height: 25px;">
                                <option value="">Ngày</option>
                                @for($i = 1;$i<= 31; $i++)
                                    <option value="{!! $i !!}" @if($dayEnd == $i) selected="selected"  @endif>{!! $i !!}</option>
                                @endfor
                            </select>

                            <select name="cbHoursEnd" style="margin-top: 5px; height: 25px;">
                                <option value="">Giờ</option>
                                @for($i =1;$i<= 24; $i++)
                                    <option value="{!! $i !!}" @if($i == 17) selected="selected"  @endif >{!! $i !!}</option>
                                @endfor
                            </select>
                            <select name="cbMinuteEnd" style="margin-top: 5px; height: 25px;">
                                @for($i =0;$i<= 55; $i = $i+5)
                                    <option value="{!! $i !!}" @if($i == 30) selected="selected"  @endif >{!! $i !!}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group form-group-sm">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="txtAfternoonStatus"> Có làm trưa
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group form-group-sm">
                            <label>Ghi chú:</label>
                            <input class="form-control" name="txtNote" value="{!! $dataTimekeeping->note() !!}"/>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="qc-padding-top-20 qc-padding-bot-20  qc-border-none col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                    <button type="button" class="qc_save btn btn-sm btn-primary">Lưu</button>
                    <button type="reset" class="btn btn-sm btn-default">Hủy</button>
                    <button type="button" class="qc_ad3d_container_close btn btn-sm btn-default">Đóng</button>
                </div>
            </div>
        </form>
    </div>
@endsection
