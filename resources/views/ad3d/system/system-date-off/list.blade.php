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
@extends('ad3d.system.system-date-off.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
             style="margin-bottom: 10px; padding-top: 10px; padding-bottom: 10px; border-bottom: 2px dashed brown;">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green"
                       href="{!! route('qc.ad3d.system.system_date_off.get') !!}">
                        <i class="glyphicon glyphicon-refresh qc-font-size-20"></i>
                    </a>
                    <i class="qc-font-size-20 glyphicon glyphicon-list-alt"></i>
                    <label class="qc-font-size-20">NGÀY NGHỈ</label>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <select class="cbCompanyFilter" name="cbCompanyFilter" style="margin-top: 5px; height: 25px;"
                            data-href-filter="{!! route('qc.ad3d.system.system_date_off.get') !!}">
                        @if(count($dataCompany)> 0)
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
                <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <select class="cbMonthFilter" style="margin-top: 5px; height: 25px;"
                            data-href="{!! route('qc.ad3d.system.system_date_off.get') !!}">
                        <option value="0" @if((int)$monthFilter == 0) selected="selected" @endif >Tất cả
                        </option>
                        @for($i =1;$i<= 12; $i++)
                            <option value="{!! $i !!}"
                                    @if((int)$monthFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                        @endfor
                    </select>
                    <span>/</span>
                    <select class="cbYearFilter" style="margin-top: 5px; height: 25px;"
                            data-href="{!! route('qc.ad3d.system.system_date_off.get') !!}">
                        @for($i =2017;$i<= 2050; $i++)
                            <option value="{!! $i !!}"
                                    @if($yearFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                        @endfor
                    </select>
                    <a class="qc-link-green" href="{!! route('qc.ad3d.system.system_date_off.add.get') !!}">
                        +Thêm
                    </a>
                </div>
            </div>
            <div class="qc_ad3d_list_content qc-ad3d-table-container row"
                 data-href-del="{!! route('qc.ad3d.system.system_date_off.delete') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: whitesmoke;">
                            <th class="text-center">STT</th>
                            <th>Tên</th>
                            <th>Mô tả</th>
                            <th class="text-center">Hình thức nghỉ</th>
                            <th></th>
                        </tr>
                        @if(count($dataSystemDateOff) > 0)
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
                                <tr class="qc_ad3d_list_object qc-ad3d-list-object" data-object="{!! $dateOffId !!}">
                                    <td class="text-center">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td>
                                        {!! date('d/m/Y', strtotime($systemDateOff->dateOff())) !!}
                                    </td>
                                    <td class="qc-link-grey">
                                        @if(!empty($description))
                                            {!! $description !!}
                                        @else
                                            <em>---</em>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {!! $systemDateOff->typeLabel($dateOffId) !!}
                                    </td>
                                    <td class="text-right">
                                        <a class="qc_delete qc-link-red"
                                           data-href="{!! route('qc.ad3d.system.system_date_off.delete', $dateOffId) !!}">Xóa</a>
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
                                    Chưa có danh sách nghỉ
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
