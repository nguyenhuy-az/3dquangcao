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
?>
@extends('ad3d.store.store.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
             style="margin-bottom: 10px; padding-top : 5px;padding-bottom: 5px; border-bottom: 2px dashed brown;">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! route('qc.ad3d.store.store.tool.get') !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <i class="qc-font-size-20 glyphicon glyphicon-list-alt"></i>
                    <label class="qc-font-size-20">DANH SÁCH DỤNG CỤ</label>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <select class="cbCompanyFilter" name="cbCompanyFilter" style="margin-top: 5px; padding: 3px 0;"
                            data-href-filter="{!! route('qc.ad3d.store.store.tool.get') !!}">
                        @if($dataStaffLogin->checkRootManage())
                            <option value="0">Tất cả</option>
                        @endif
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
                <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 2px 0 2px 0; ">
                    <form name="" action="">
                        <div class="row">
                            <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="input-group">
                                    <input type="text" class="textFilterName form-control" name="textFilterName"
                                           style="height: 25px;"
                                           placeholder="Tìm theo tên" value="{!! $nameFiler !!}">
                                      <span class="input-group-btn">
                                            <button class="btFilterName btn btn-sm btn-default" type="button"
                                                    style="height: 25px;"
                                                    data-href="{!! route('qc.ad3d.store.store.tool.get') !!}">Tìm
                                            </button>
                                      </span>
                                </div>
                            </div>
                            {{--<div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <select class="cbDayFilter" style="margin-top: 5px; "
                                        data-href="{!! route('qc.ad3d.store.tool.allocation.get') !!}">
                                    <option value="0" @if((int)$monthFilter == 0) selected="selected" @endif >Tất cả
                                    </option>
                                    @for($i =1;$i<= 31; $i++)
                                        <option value="{!! $i !!}"
                                                @if((int)$dayFilter == $i) selected="selected" @endif >{!! $i !!}</option>
                                    @endfor
                                </select>
                                <span>/</span>
                                <select class="cbMonthFilter" style="margin-top: 5px; "
                                        data-href="{!! route('qc.ad3d.store.tool.allocation.get') !!}">
                                    @for($i =1;$i<= 12; $i++)
                                        <option value="{!! $i !!}"
                                                @if((int)$monthFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                                    @endfor
                                </select>
                                <span>/</span>
                                <select class="cbYearFilter" style="margin-top: 5px;"
                                        data-href="{!! route('qc.ad3d.store.tool.allocation.get') !!}">
                                    @for($i =2017;$i<= 2050; $i++)
                                        <option value="{!! $i !!}"
                                                @if($yearFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                                    @endfor
                                </select>
                            </div>--}}
                        </div>
                    </form>
                </div>
            </div>
            <div class="qc_ad3d_list_content row">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: whitesmoke;">
                            <th class="text-center">STT</th>
                            <th>Tên</th>
                            <th class="text-center">Số lượng</th>
                            <th class="text-center">Đã giao</th>
                            <th class="text-center">Còn lại</th>
                            <th class="text-center">Công ty</th>
                        </tr>
                        @if(count($dataCompanyStore) > 0)
                            <?php
                            $perPage = $dataCompanyStore->perPage();
                            $currentPage = $dataCompanyStore->currentPage();
                            $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                            ?>
                            @foreach($dataCompanyStore as $companyStore)
                                <?php
                                $storeId = $companyStore->storeId();
                                $updateDate = $companyStore->updateDate();
                                $amount = $companyStore->amount();
                                $toolAllocationDetailTotalAmount = $companyStore->toolAllocationDetailTotalAmount();
                                $amountInventoryInStore = $amount - $toolAllocationDetailTotalAmount;
                                ?>
                                <tr class="qc_ad3d_list_object qc-ad3d-list-object" data-object="{!! $storeId !!}">
                                    <td class="text-center">
                                        {!! $n_o +=1 !!}
                                    </td>
                                    <td>
                                        {!! $companyStore->tool->name() !!}
                                    </td>
                                    <td class="qc-color-red text-center">
                                        {!! $companyStore->amount() !!}
                                    </td>
                                    <td class="text-center">
                                        {!! $toolAllocationDetailTotalAmount !!}
                                    </td>
                                    <td class="qc-color-red text-center">
                                        {!! $amountInventoryInStore !!}
                                    </td>
                                    <td class="text-center">
                                        {!! $companyStore->company->name() !!}
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center" colspan="6">
                                    {!! $hFunction->page($dataCompanyStore) !!}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="text-center" colspan="6">
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
