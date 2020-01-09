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
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
             style="margin-bottom: 10px; padding-top : 10px;padding-bottom: 10px; border-bottom: 2px dashed black;">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! route('qc.ad3d.finance.salary.pay-before.get') !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <i class="qc-font-size-20 glyphicon glyphicon-list-alt"></i>
                    <label class="qc-font-size-20">ỨNG LƯƠNG</label>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">

                    <select class="cbCompanyFilter" name="cbCompanyFilter" style="margin-top: 5px; height: 25px;"
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
            <div class="row">
                <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 2px 0 2px 0; ">
                    <form name="" action="">
                        <div class="row">
                            <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <select class="cbDayFilter" style="margin-top: 5px; height: 25px;"
                                        data-href="{!! route('qc.ad3d.finance.salary.pay-before.get') !!}">
                                    <option value="100" @if((int)$monthFilter == 0) selected="selected" @endif >Tất cả
                                    </option>
                                    @for($i =1;$i<= 31; $i++)
                                        <option value="{!! $i !!}"
                                                @if((int)$dayFilter == $i) selected="selected" @endif >{!! $i !!}</option>
                                    @endfor
                                </select>
                                <span>|</span>
                                <select class="cbMonthFilter" style="margin-top: 5px; height: 25px;"
                                        data-href="{!! route('qc.ad3d.finance.salary.pay-before.get') !!}">
                                    <option value="100" @if((int)$monthFilter == 100) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($i =1;$i<= 12; $i++)
                                        <option value="{!! $i !!}"
                                                @if((int)$monthFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                                    @endfor
                                </select>
                                <span>|</span>
                                <select class="cbYearFilter" style="margin-top: 5px; height: 25px;"
                                        data-href="{!! route('qc.ad3d.finance.salary.pay-before.get') !!}">
                                    <option value="100" @if((int)$yearFilter == 100) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($i =2017;$i<= 2050; $i++)
                                        <option value="{!! $i !!}"
                                                @if($yearFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                                    @endfor
                                </select>
                                {{--<a class="btn btn-sm btn-primary"
                                   href="{!! route('qc.ad3d.finance.salary.pay-before.add.get') !!}">
                                    +
                                </a>--}}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="qc_ad3d_list_content row"
                 data-href-view="{!! route('qc.ad3d.finance.salary.pay-before.view.get') !!}"
                 data-href-edit="{!! route('qc.ad3d.finance.salary.pay-before.edit.get') !!}"
                 data-href-del="{!! route('qc.ad3d.finance.salary.pay-before.delete') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: whitesmoke;">
                            <th class="text-center">STT</th>
                            <th>Tên</th>
                            <th class="text-center">Ngày</th>
                            <th class="text-right">Ghi chú</th>
                            <th class="text-right"></th>
                            <th class="text-right">Tiền ứng</th>
                        </tr>
                        <tr>
                            <td class="text-center"></td>
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
                            <td class="text-center"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right">
                                <b class="qc-color-red">{!! $hFunction->currencyFormat($totalMoneyBeforePay)  !!}</b>
                            </td>
                        </tr>
                        @if(count($dataSalaryBeforePay) > 0)
                            <?php
                            $perPage = $dataSalaryBeforePay->perPage();
                            $currentPage = $dataSalaryBeforePay->currentPage();
                            $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                            ?>
                            @foreach($dataSalaryBeforePay as $salaryBeforePay)
                                <?php
                                $payId = $salaryBeforePay->payId();
                                $dataWork = $salaryBeforePay->work;
                                ?>
                                <tr class="qc_ad3d_list_object" data-object="{!! $payId !!}">
                                    <td class="text-center">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td>
                                        @if(!empty($dataWork->companyStaffWorkId()))
                                            {!! $dataWork->companyStaffWork->staff->fullName() !!}
                                        @else
                                            {!! $dataWork->staff->fullName() !!}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {!! date('d/m/Y', strtotime($salaryBeforePay->datePay())) !!}
                                    </td>

                                    <td class="text-right">
                                        {!! $salaryBeforePay->description() !!}
                                    </td>
                                    <td class="text-right">
                                        {{--<a class="qc_view qc-link-green">Chi tiết</a>--}}
                                        @if(!$salaryBeforePay->checkConfirm())
                                            <em style="color: brown;">Chưa xác nhận</em>
                                            @if($salaryBeforePay->checkStaffInput($dataStaffLogin->staffId()))
                                                &nbsp;<span>|</span>&nbsp;
                                                <a class="qc_edit qc-link-green">
                                                    <i class="glyphicon glyphicon-pencil" title="Sửa thông tin ứng"></i>
                                                </a>
                                                &nbsp;<span>|</span>&nbsp;
                                                <a class="qc_delete qc-link-red">
                                                    <i class="glyphicon glyphicon-trash" title="Hủy ứng lương"></i>
                                                </a>
                                            @endif
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
