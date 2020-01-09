<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:32 PM
 */
/*
 *$dataCompany
 */
$hFunction = new Hfunction();
$dataStaffLogin = $modelStaff->loginStaffInfo();
?>
@extends('ad3d.system.kpi.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
             style="margin-bottom: 10px; padding-top: 10px; padding-bottom: 10px; border-bottom: 2px dashed brown;">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <i class="qc-font-size-20 glyphicon glyphicon-list-alt"></i>
                    <label class="qc-font-size-20">DANH MỤC KPI</label>
                </div>
            </div>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 2px 0 2px 0; ">
                    <form name="" action="">
                        <div class="row">
                            <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <a class="btn btn-primary btn-sm"
                                   href="{!! route('qc.ad3d.system.kpi.add.get') !!}">
                                    <i class="glyphicon glyphicon-plus"></i>Thêm
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="qc_ad3d_list_content qc-ad3d-table-container row"
                 data-href-edit="{!! route('qc.ad3d.system.kpi.edit.get') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: whitesmoke;">
                            <th class="text-center"></th>
                            <th>Hạn mức</th>
                            <th class="text-center">% thưởng</th>
                            <th class="text-center">% trừ</th>
                            <th>Mô tả</th>
                            <th class="text-center">Bộ phận</th>
                            <th></th>
                        </tr>
                        @if(count($dataKpi) > 0)
                            <?php
                            $perPage = $dataKpi->perPage();
                            $currentPage = $dataKpi->currentPage();
                            $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                            ?>
                            @foreach($dataKpi as $kpi)
                                <?php
                                $kpiId = $kpi->kpiId();
                                ?>
                                <tr class="qc_ad3d_list_object" data-object="{!! $kpiId !!}">
                                    <td class="text-center">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td>
                                        {!! $hFunction->currencyFormat($kpi->kpiLimit()) !!}
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
                                    <td class="text-center">
                                        {!! $kpi->department->name() !!}
                                    </td>
                                    <td class="text-right">
                                        {{--<a class="qc_edit qc-link-green btn btn-default btn-sm" href="#">Sửa</a>
                                        <a class="qc_delete qc-link-green btn btn-default btn-sm" href="#">Xóa</a>--}}
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center" colspan="7">
                                    {!! $hFunction->page($dataKpi) !!}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="text-center qc-color-red" colspan="7">
                                    Chưa có dữ liệu
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
