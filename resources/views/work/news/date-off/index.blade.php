<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 8:57 AM
 *
 * modelStaff
 */
$hFunction = new Hfunction();
$dataStaffLogin = $modelStaff->loginStaffInfo();
$currentDate = $hFunction->currentDate();
$hrefIndex = route('qc.work.news.date_off.get')
?>
@extends('work.index')
@section('titlePage')
    Ngày nghỉ
@endsection
<style type="text/css">
    .qc-work-panel {
        text-align: center;
        height: 50px;
        line-height: 50px;
        border: 1px solid #d7d7d7;
    }

    .qc-work-panel:hover {
        background-color: #d7d7d7;
        color: red;
    }
</style>
@section('qc_work_body')
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            @include('work.components.system-info.system-info', compact('modelCompany','modelStaff'))
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <tr style="background-color: black;color: yellow;">
                        <th class="text-center" style="width: 20px;">STT</th>
                        <th>Ngày</th>
                        <th>Mô tả</th>
                        <th>Hình thức</th>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="padding: 0;">
                            <select class="qc_work_news_date_off_filter_year" style="height: 25px;" data-href="{!! $hrefIndex !!}">
                                @for($y =2017;$y<= 2050; $y++)
                                    <option value="{!! $y !!}" @if($yearFilter == $y) selected="selected" @endif>
                                       Năm {!! $y !!}
                                    </option>
                                @endfor
                            </select>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    @if($hFunction->checkCount($dataSystemDateOff))
                        <?php
                        $n_o = 0;
                        ?>
                        @foreach($dataSystemDateOff as $systemDateOff)
                            <?php
                                $dateOff = $systemDateOff->dateOff();
                            ?>
                            <tr class="@if($dateOff < $currentDate) qc-color-red @endif @if($n_o%2) info @endif ">
                                <td class="text-center">
                                    {!! $n_o += 1 !!}
                                </td>
                                <td>
                                    {!! $hFunction->convertDateDMYFromDatetime($systemDateOff->dateOff) !!}
                                </td>
                                <td>
                                    {!! $systemDateOff->description() !!}
                                </td>
                                <td>
                                    <em class="qc-color-grey">
                                        {!! $systemDateOff->typeLabel($systemDateOff->dateOffId()) !!}
                                    </em>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="text-center" colspan="4">
                                <em>Không có lịch nghỉ</em>
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection