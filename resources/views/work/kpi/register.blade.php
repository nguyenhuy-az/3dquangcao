<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 *
 * dataCompany
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();

if (count($dataKpiSelected) > 0) {
    $kpiSelectedId = $dataKpiSelected->kpiId();
} else {
    $kpiSelectedId = 0;
}
?>
@extends('components.container.container-6')
@section('qc_container_content')
    <div class="frm_work_kpi_register_add_wrap qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
                <h3>ĐĂNG KÝ KPI</h3>
                <em class="qc-color-red">(Chỉ được áp dụng khi hệ thống đã duyệt)</em>
            </div>
            <div class="qc-padding-top-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form class="frm_work_kpi_register_add" name="frm_work_kpi_register_add" role="form"
                      method="post"
                      action="{!! route('qc.work.kpi.add_register.post') !!}">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm">
                                <label>Chọn Hạng mức:</label>
                                <select class="cbKpi form-control" name="cbKpi" data-href="{!! route('qc.work.kpi.add_register.get') !!}">
                                    <option value="0" @if($kpiSelectedId == 0) selected=selected @endif>Chọn người
                                        nhận
                                    </option>
                                    @if(count($dataKpi) > 0)
                                        @foreach($dataKpi as $kpi)
                                            <option @if($kpi->kpiId() == $kpiSelectedId) selected=selected
                                                    @endif  value="{!! $kpi->kpiId() !!}">{!! $hFunction->currencyFormat($kpi->kpiLimit()) !!}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @if(count($dataKpiSelected) > 0)
                            <div class="form-group form-group-sm col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label>Thưởng KPI:</label> {!! $dataKpiSelected->plusPercent() !!} % &nbsp;&nbsp;&nbsp;
                                <label>Trừ KPI:</label> {!! $dataKpiSelected->minusPercent() !!} %
                                <br/>
                                <label>Mô tả</label>

                                <p>
                                    {!! $dataKpiSelected->description() !!}
                                </p>
                            </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="text-center form-group form-group-sm">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <button type="button" class="qc_save btn btn-sm btn-primary">Đăng ký</button>
                                <button type="button" class="qc_container_close btn btn-sm btn-default">Đóng</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
