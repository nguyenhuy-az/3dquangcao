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
$indexHref = route('qc.ad3d.system.system_date_off.get');
?>
@extends('ad3d.system.system-date-off.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
             style="margin-bottom: 10px; padding-top: 10px; padding-bottom: 10px; border-bottom: 2px dashed brown;">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green"
                       href="{!! $indexHref !!}">
                        <i class="glyphicon glyphicon-refresh qc-font-size-20"></i>
                    </a>
                    <i class="qc-font-size-20 glyphicon glyphicon-list-alt"></i>
                    <label class="qc-font-size-20">NGÀY NGHỈ</label>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <select class="cbCompanyFilter" name="cbCompanyFilter" style="margin-top: 5px; height: 25px;"
                            data-href-filter="{!! $indexHref !!}">
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
                    <a class="qc-link-red" title="Sao chép ngày nghỉ từ công ty khác"
                       href="{!! route('qc.ad3d.system.system_date_off.copy.get') !!}">
                        <i class="qc-font-size-16 glyphicon glyphicon-download-alt"></i>
                        Sao chép lịch nghỉ
                    </a>
                    &nbsp;|&nbsp;
                    <a class="qc-link-green" href="{!! route('qc.ad3d.system.system_date_off.add.get') !!}">
                        <i class="glyphicon glyphicon-plus"></i>
                        Thêm
                    </a>
                </div>
            </div>
            <div class="qc_ad3d_list_content row">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: black; color: yellow;">
                            <th class="text-center" style="width: 20px;">STT</th>
                            <th>Ngày</th>
                            <th>Mô tả</th>
                            <th>Hình thức nghỉ</th>
                            <th></th>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="padding: 0px;">
                                <select class="cbMonthFilter" style="height: 30px;" data-href="{!! $indexHref !!}">
                                    <option value="100" @if((int)$monthFilter == 100) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($m =1;$m<= 12; $m++)
                                        <option value="{!! $m !!}"
                                                @if((int)$monthFilter == $m) selected="selected" @endif>
                                            Tháng {!! $m !!}
                                        </option>
                                    @endfor
                                </select>
                                <select class="cbYearFilter" style="height: 30px;" data-href="{!! $indexHref !!}">
                                    <option value="100" @if((int)$monthFilter == 100) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($y =2017;$y<= 2050; $y++)
                                        <option value="{!! $y !!}" @if($yearFilter == $y) selected="selected" @endif>
                                            {!! $y !!}
                                        </option>
                                    @endfor
                                </select>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        @if($hFunction->checkCount($dataSystemDateOff))
                            <?php
                            $perPage = $dataSystemDateOff->perPage();
                            $currentPage = $dataSystemDateOff->currentPage();
                            $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                            ?>
                            @foreach($dataSystemDateOff as $systemDateOff)
                                <?php
                                $dateOffId = $systemDateOff->dateOffId();
                                $description = $systemDateOff->description();
                                ?>
                                <tr class="qc_ad3d_list_object @if($n_o%2) info @endif"
                                    data-object="{!! $dateOffId !!}">
                                    <td class="text-center">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td>
                                        {!! $hFunction->convertDateDMYFromDatetime($systemDateOff->dateOff()) !!}
                                    </td>
                                    <td class="qc-link-grey">
                                        @if(!$hFunction->checkEmpty($description))
                                            {!! $description !!}
                                        @else
                                            <em>---</em>
                                        @endif
                                    </td>
                                    <td>
                                        {!! $systemDateOff->typeLabel($dateOffId) !!}
                                    </td>
                                    <td class="text-right">
                                        <a class="qc_edit qc-link" title="Sửa"
                                           data-href="{!! route('qc.ad3d.system.system_date_off.edit.get', $dateOffId) !!}">
                                            <i class="glyphicon glyphicon-pencil"></i>
                                        </a>
                                        <span>|</span>
                                        <a class="qc_delete qc-link-red" title="Hủy"
                                           data-href="{!! route('qc.ad3d.system.system_date_off.delete', $dateOffId) !!}">
                                            <i class="glyphicon glyphicon-trash qc-font-size-16"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center" colspan="5">
                                    {!! $hFunction->page($dataSystemDateOff) !!}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="text-center qc-color-red" colspan="5">
                                    Không có thông tin nghỉ
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
