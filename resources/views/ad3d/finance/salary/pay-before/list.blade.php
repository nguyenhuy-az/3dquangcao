<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:32 PM
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$dataStaffLogin = $modelStaff->loginStaffInfo();
?>
@extends('ad3d.finance.salary.pay-before.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! route('qc.ad3d.finance.salary.pay-before.get') !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <label class="qc-font-size-20">ỨNG LƯƠNG</label>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <select class="cbCompanyFilter form-control" name="cbCompanyFilter" style="height: 34px;"
                            data-href-filter="{!! route('qc.ad3d.finance.salary.pay-before.get') !!}">
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
            <div class="qc_ad3d_list_content row"
                 data-href-view="{!! route('qc.ad3d.finance.salary.pay-before.view.get') !!}"
                 data-href-edit="{!! route('qc.ad3d.finance.salary.pay-before.edit.get') !!}"
                 data-href-del="{!! route('qc.ad3d.finance.salary.pay-before.delete') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: black; color: yellow;">
                            <th style="width: 20px;">STT</th>
                            <th style="width: 150px;">Ngày</th>
                            <th>Tên</th>
                            <th class="text-right">Ghi chú</th>
                            <th class="text-right"></th>
                            <th class="text-right">Tiền ứng</th>
                        </tr>
                        <tr>
                            <td class="text-center"></td>
                            <td style="padding: 0;">
                                <select class="cbDayFilter col-sx-3 col-sm-3 col-md-3 col-lg-3" style="padding: 0;height: 34px;"
                                        data-href="{!! route('qc.ad3d.finance.salary.pay-before.get') !!}">
                                    <option value="100" @if((int)$dayFilter == 0) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($d =1;$d<= 31; $d++)
                                        <option value="{!! $d !!}"
                                                @if((int)$dayFilter == $d) selected="selected" @endif >
                                            {!! $d !!}
                                        </option>
                                    @endfor
                                </select>
                                <select class="cbMonthFilter col-sx-3 col-sm-3 col-md-3 col-lg-3" style="padding: 0; height: 34px;"
                                        data-href="{!! route('qc.ad3d.finance.salary.pay-before.get') !!}">
                                    <option value="100" @if((int)$monthFilter == 100) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($m =1;$m<= 12; $m++)
                                        <option value="{!! $m !!}"
                                                @if((int)$monthFilter == $m) selected="selected" @endif>
                                            {!! $m !!}
                                        </option>
                                    @endfor
                                </select>
                                <select class="cbYearFilter col-sx-6 col-sm-6 col-md-6 col-lg-6" style="padding: 0;height: 34px;"
                                        data-href="{!! route('qc.ad3d.finance.salary.pay-before.get') !!}">
                                    <option value="100" @if((int)$yearFilter == 100) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($y =2017;$y<= 2050; $y++)
                                        <option value="{!! $y !!}"
                                                @if($yearFilter == $y) selected="selected" @endif>
                                            {!! $y !!}
                                        </option>
                                    @endfor
                                </select>
                            </td>
                            <td style="padding: 0;">
                                <div class="input-group">
                                    <input type="text" class="textFilterName form-control" name="textFilterName"
                                           placeholder="Tìm theo tên" value="">
                                      <span class="input-group-btn">
                                            <button class="btFilterName btn btn-default" type="button"
                                                    data-href="{!! route('qc.ad3d.finance.salary.pay-before.get') !!}">
                                                <i class="glyphicon glyphicon-search"></i>
                                            </button>
                                      </span>
                                </div>
                            </td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right">
                                <b class="qc-color-red">{!! $hFunction->currencyFormat($totalMoneyBeforePay)  !!}</b>
                            </td>
                        </tr>
                        @if($hFunction->checkCount($dataSalaryBeforePay))
                            <?php
                            $perPage = $dataSalaryBeforePay->perPage();
                            $currentPage = $dataSalaryBeforePay->currentPage();
                            $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                            ?>
                            @foreach($dataSalaryBeforePay as $salaryBeforePay)
                                <?php
                                $payId = $salaryBeforePay->payId();
                                $dataWork = $salaryBeforePay->work;
                                # thong tin nhan vien
                                if (!empty($dataWork->companyStaffWorkId())) {
                                    $dataStaffWork = $dataWork->companyStaffWork->staff;
                                } else {
                                    $dataStaffWork = $dataWork->staff; // phien ban cu
                                }
                                ?>
                                <tr class="qc_ad3d_list_object" data-object="{!! $payId !!}">
                                    <td class="text-center">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td>
                                        {!! date('d/m/Y', strtotime($salaryBeforePay->datePay())) !!}
                                    </td>

                                    <td>
                                        <img style="max-width: 40px;height: 40px; border: 1px solid #d7d7d7;"
                                             src="{!! $dataStaffWork->pathAvatar($dataStaffWork->image()) !!}">
                                        {!! $dataStaffWork->fullName() !!}
                                    </td>
                                    <td class="text-right">
                                        {!! $salaryBeforePay->description() !!}
                                    </td>
                                    <td class="text-right">
                                        {{--<a class="qc_view qc-link-green">Chi tiết</a>--}}
                                        @if(!$salaryBeforePay->checkConfirm())
                                            <em style="color: brown;">Chưa xác nhận</em>
                                            {{--@if($salaryBeforePay->checkStaffInput($dataStaffLogin->staffId()))
                                                &nbsp;<span>|</span>&nbsp;
                                                <a class="qc_edit qc-link-green">
                                                    <i class="glyphicon glyphicon-pencil" title="Sửa thông tin ứng"></i>
                                                </a>
                                                &nbsp;<span>|</span>&nbsp;
                                                <a class="qc_delete qc-link-red">
                                                    <i class="glyphicon glyphicon-trash" title="Hủy ứng lương"></i>
                                                </a>
                                            @endif--}}
                                        @else
                                            <em class="qc-color-grey">Đã xác nhận</em>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        {!! $hFunction->currencyFormat($salaryBeforePay->money()) !!}
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center qc-padding-top-20 qc-padding-bot-20" colspan="6">
                                    {!! $hFunction->page($dataSalaryBeforePay) !!}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="qc-padding-top-20 qc-padding-bot-20 text-center" colspan="6">
                                    <em class="qc-color-red">Không tìm thấy thông tin</em>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection
