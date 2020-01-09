<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:32 PM
 */
/*
 *dataProductType
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$dataStaffLogin = $modelStaff->loginStaffInfo();
$companyFilterId = $dataAccess['companyFilterId'];
?>
@extends('ad3d.store.supplies.supplies.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
             style="padding-top: 10px; padding-bottom: 10px; border-bottom: 2px dashed brown;">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! route('qc.ad3d.store.supplies.supplies.get') !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <label class="qc-font-size-20">VẬT TƯ CỦA HỆ THỐNG</label>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    {{--<select class="cbCompanyFilter" name="cbCompanyFilter" style="height: 25px;"
                            data-href-filter="{!! route('qc.ad3d.store.supplies.supplies.get') !!}">
                        --}}{{--@if($dataStaffLogin->checkRootManage())
                            <option value="0">Tất cả</option>
                        @endif--}}{{--
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
                    </select>--}}
                </div>
            </div>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 2px 0 2px 0; ">
                    <form name="" action="">
                        <div class="row">
                            {{--<div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <input class="col-xs-12" type="text" value="" placeholder="Tên công ty"
                                       style="height: 30px;">
                            </div>--}}
                            @if($dataStaffLogin->checkRootStatus())
                                <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <a class="btn btn-primary btn-sm"
                                       href="{!! route('qc.ad3d.store.supplies.supplies.add.get') !!}">
                                        <i class="glyphicon glyphicon-plus"></i>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            <div class="qc_ad3d_list_content row"
                 data-href-view="{!! route('qc.ad3d.store.supplies.supplies.view.get') !!}"
                 data-href-edit="{!! route('qc.ad3d.store.supplies.supplies.edit.get') !!}"
                 data-href-del="{!! route('qc.ad3d.store.supplies.supplies.del.get') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: whitesmoke;">
                            <th class="text-center">STT</th>
                            <th>Tên</th>
                            <th class="text-center">Đơn vị tính</th>
                            <th class="text-center" style="width: 100px;">Hoạt động</th>
                            <th class="text-right"></th>
                        </tr>
                        {{--<tr>
                            <th class="text-center"></th>
                            <th></th>
                            <th class="text-center"></th>
                            <th class="text-center" style="padding: 0;">
                                <select class="form-control qcActivityStatus">
                                    <option value="2">Tất cả</option>
                                    <option value="1">Đang hoạt động </option>
                                    <option value="0">Đã hủy</option>
                                </select>
                            </th>
                            <th class="text-right"></th>
                        </tr>--}}
                        @if(count($dataSupplies) > 0)
                            <?php
                            $perPage = $dataSupplies->perPage();
                            $currentPage = $dataSupplies->currentPage();
                            $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                            ?>
                            @foreach($dataSupplies as $supplies)
                                <?php
                                $suppliesId = $supplies->suppliesId();
                                ?>
                                <tr class="qc_ad3d_list_object qc-ad3d-list-object @if($n_o%2) info @endif"
                                    data-object="{!! $suppliesId !!}">
                                    <td class="text-center">
                                        {!! $n_o +=1 !!}
                                    </td>
                                    <td>
                                        {!! $supplies->name() !!}
                                    </td>
                                    <td class="text-center">
                                        {!! $supplies->unit() !!}
                                    </td>
                                    <td class="text-center">
                                        @if($supplies->checkAction())
                                            <i class="glyphicon glyphicon-ok qc-color-green"></i>
                                        @else
                                            <i class="glyphicon glyphicon-ok qc-color-red"></i>
                                        @endif

                                    </td>
                                    <td class="text-right">
                                        <a class="qc_view qc-link-green" href="#" title="Xem chi tiết">
                                            <i class="glyphicon glyphicon-eye-open"></i>
                                        </a>
                                        @if($supplies->checkAction())
                                            <span>|</span>
                                            <a class="qc_edit qc-link-blue" href="#" title="Sửa">
                                                <i class="glyphicon glyphicon-pencil"></i>
                                            </a>
                                            <span>|</span>
                                            <a class="qc_delete qc-link-red " href="#" title="Hủy">
                                                <i class="glyphicon glyphicon-remove"></i>
                                            </a>
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center" colspan="4">
                                    {!! $hFunction->page($dataSupplies) !!}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="text-center qc-padding-none" colspan="4">
                                    Không có đữ liệu
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
