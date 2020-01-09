<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
?>
@extends('ad3d.system.kpi.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
            <h3>THÊM MỚI</h3>
        </div>
        @if(count($dataDepartment) > 0)
            <div class="qc-padding-top-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form class="frmAdd" name="frmAdd" role="form" method="post"
                      action="{!! route('qc.ad3d.system.kpi.add.post') !!}">
                    <div class="qc-ad3d-table-container row">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                @if (Session::has('notifyAdd'))
                                    <tr>
                                        <td class="text-center qc-color-red" colspan="4" style="padding: 0;">
                                            <b>{!! Session::get('notifyAdd') !!}</b>
                                            <?php
                                            Session::forget('notifyAdd');
                                            ?>
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td class="text-center" colspan="2" style="padding: 0;">
                                        <select class="form-control" name="cbDepartment">
                                            <option value="{!! $dataDepartment->departmentId() !!}">{!! $dataDepartment->name() !!}</option>
                                        </select>
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                                <tr style="background-color: whitesmoke;">
                                    <th class="text-center">Mức</th>
                                    <th class="text-center">% Hưởng</th>
                                    <th class="text-center">% Trừ</th>
                                    <th class="text-center">Mô tả</th>
                                </tr>
                                @if(count($dataKpi))
                                    @foreach($dataKpi as $kpi)
                                        <tr >
                                            <td class="text-center">
                                                <b>{!! $hFunction->currencyFormat($kpi->kpiLimit()) !!}</b>
                                                <br/>
                                                <em class="qc-color-grey">(Đang dùng)</em>
                                            </td>
                                            <td class="text-center">
                                                {!! $kpi->plusPercent() !!}
                                            </td>
                                            <td class="text-center">
                                                {!! $kpi->minusPercent() !!}
                                            </td>
                                            <td>
                                                {!! $kpi->description() !!}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                <tr>
                                    <td style="padding: 0;">
                                        <select class="form-control" name="cbLimit">
                                            <option value="0">Chọn hạn mức</option>
                                            @for($j = 50000000;$j <= 500000000; $j = $j + 50000000)
                                                <option value="{!! $j !!}">{!! $hFunction->currencyFormat($j) !!}</option>
                                            @endfor
                                        </select>
                                    </td>
                                    <td style="padding: 0;">
                                        <select class="form-control" name="cbPlusPercent">
                                            @for($i = 1;$i < 15; $i++)
                                                <option value="{!! $i !!}">{!! $i !!}</option>
                                            @endfor
                                        </select>
                                    </td>
                                    <td style="padding: 0;">
                                        <select class="form-control" name="cbMinusPercent">
                                            @for($n = 0;$n < 15; $n++)
                                                <option value="{!! $n !!}">{!! $n !!}</option>
                                            @endfor
                                        </select>
                                    </td>
                                    <td style="padding: 0;">
                                        <input class="form-control" type="text" name="txtDescription"
                                               placeholder="Mô tả hạn mức KPI">
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <b class="qc-color-red">Nếu hạn mức trùng nhau sẽ chọn mức mới nhât</b>
                            </div>
                        </div>
                        <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <button type="button" class="qc_save btn btn-primary">Thêm</button>
                                <a class="btn btn-default" href="{!! $hFunction->getUrlReferer() !!}">
                                    Đóng
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        @else
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="padding: 20px;">
                Chưa có bộ phận
                <br/><br/>
                <a class="btn btn-primary" href="{!! $hFunction->getUrlReferer() !!}">
                    Đóng
                </a>
            </div>
        @endif
    </div>
@endsection
