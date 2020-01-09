<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 */
$sumMainMinute = $dataWork->sumMainMinute();
$sumPlusMinute = $dataWork->sumPlusMinute();// * 1.5;
$sumMinusMinute = $dataWork->sumMinusMinute();
?>
@extends('ad3d.components.container.container-10')
@section('qc_ad3d_container_content')
    <div class="qc-padding-top-20 qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed #C2C2C2;">
            <h3>CHI TIẾT LÀM VIỆC</h3>
        </div>

        {{-- thông tin khách hàng --}}
        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <em>Tên:</em>
                    <span class="qc-font-bold">{!! $dataWork->staff->fullName() !!}</span>
                </div>
                <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <em>Mã NV:</em>
                    <span class="qc-font-bold">{!! $dataWork->staff->nameCode() !!}</span>
                </div>
                <div class="text-right qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-4 col-lg-4"
                     style="color: brown;">
                    <em class="qc-text-under">Từ:</em>
                    <span class="qc-font-bold"> {!! date('d-m-Y',strtotime($dataWork->fromDate())) !!}</span>&nbsp;&nbsp;
                    <em class="qc-text-under">đến </em>
                    <span class="qc-font-bold"> {!! date('d-m-Y',strtotime($dataWork->toDate())) !!}</span>
                </div>
            </div>

        </div>

        {{-- chi tiêt --}}
        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            @if(count($dataTimekeeping)>0)
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr style="background-color: whitesmoke;">
                                <th class="text-center qc-padding-none">STT</th>
                                <th class="text-center qc-padding-none">Giờ vào</th>
                                <th class="text-center qc-padding-none">Giờ ra</th>
                                <th class="qc-padding-none">Ghi chú</th>
                                <th class="text-center qc-padding-none">Nghỉ có phép</th>
                                <th class="text-center qc-padding-none">Nghỉ không phép</th>
                                <th class="text-center qc-padding-none">Giờ chính (h)</th>
                                <th class="text-center qc-padding-none">Tăng ca (h)</th>
                            </tr>
                            <?php $n_o = 0; ?>
                            @foreach($dataTimekeeping as $timekeeping)
                                <?php
                                $timekeepingId = $timekeeping->timekeepingId();
                                ?>
                                <tr>
                                    <td class="text-center qc-padding-none">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td class="text-center qc-padding-none">
                                        @if(!$timekeeping->checkOff())
                                            <span>{!! date('d-m-Y',strtotime($timekeeping->timeBegin())) !!}</span>
                                            <b>{!! date('H:i',strtotime($timekeeping->timeBegin())) !!}</b>
                                        @else
                                            <span>---</span>
                                        @endif
                                    </td>
                                    <td class="text-center qc-padding-none">
                                        @if(!$timekeeping->checkOff())
                                            @if(!empty($timekeeping->timeEnd()))
                                                <span>{!! date('d-m-Y',strtotime($timekeeping->timeEnd())) !!}</span>
                                                <b>{!! date('H:i',strtotime($timekeeping->timeEnd())) !!}</b>
                                            @else
                                                <span>Null</span>
                                            @endif
                                        @else
                                            <span>---</span>
                                        @endif
                                    </td>
                                    <td class="text-center qc-color-red qc-padding-none">
                                        @if($timekeeping->checkOff() && $timekeeping->checkPermissionStatus())
                                            <b>{!! date('d-m-Y', strtotime($timekeeping->dateOff())) !!}</b>
                                        @endif
                                    </td>
                                    <td class="text-center qc-color-red qc-padding-none">
                                        @if($timekeeping->checkOff() && !$timekeeping->checkPermissionStatus())
                                            <b>{!! date('d-m-Y', strtotime($timekeeping->dateOff())) !!}</b>
                                        @endif
                                    </td>
                                    <td>
                                        <em class="qc-color-grey"> {!! $timekeeping->note() !!}</em>
                                    </td>
                                    <td class="text-center qc-padding-none">
                                        @if(!$timekeeping->checkOff())
                                            <b>{!! ($timekeeping->mainMinute() - $timekeeping->mainMinute()%60 )/60 !!}</b>
                                            <span>h</span>
                                            <b>{!! $timekeeping->mainMinute()%60 !!}</b>
                                        @else
                                            <span>---</span>
                                        @endif
                                    </td>
                                    <td class="text-center qc-padding-none">
                                        @if(!$timekeeping->checkOff())
                                            <b>{!! ($timekeeping->plusMinute()-$timekeeping->plusMinute()%60)/60 !!}</b>
                                            <span>h</span>
                                            <b>{!! $timekeeping->plusMinute()%60 !!}</b>
                                        @else
                                            <span>---</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="qc-color-red">
                                <td class="qc-padding-none" colspan="4" style="background-color: whitesmoke;"></td>
                                <td class="text-center qc-padding-none">
                                    {!! $dataWork->sumOffWorkTrue() !!}
                                </td>
                                <td class="text-center qc-padding-none">
                                    {!! $dataWork->sumOffWorkFalse() !!}
                                </td>
                                <td class="text-center qc-padding-none">
                                    <span class="qc-font-bold">{!! floor(($sumMainMinute-$sumMainMinute%60)/60) !!}</span><span>h</span> {!! $sumMainMinute%60 !!}
                                </td>
                                <td class="text-center qc-padding-none">
                                    <span class="qc-font-bold">{!! floor(($sumPlusMinute-$sumPlusMinute%60)/60) !!}</span><span>h</span> {!! $sumPlusMinute%60 !!}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            @endif
        </div>


        <div class="row">
            <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <button type="button" class="qc_ad3d_container_close btn btn-primary">
                    Đóng
                </button>
            </div>
        </div>
    </div>
@endsection
