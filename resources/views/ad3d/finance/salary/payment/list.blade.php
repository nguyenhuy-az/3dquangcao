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
$companyLoginId = $dataStaffLogin->companyId(); # id cua cong nhan vien dang dang nhap
?>
@extends('ad3d.finance.salary.payment.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
             style="margin-bottom: 10px; padding-top : 10px;padding-bottom: 10px; border-bottom: 2px dashed brown;">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! route('qc.ad3d.finance.salary.payment.get') !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <i class="qc-font-size-20 glyphicon glyphicon-list-alt"></i>
                    <label class="qc-font-size-20">BẢNG LƯƠNG</label>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <select class="cbCompanyFilter" name="cbCompanyFilter" style="margin-top: 5px; height: 25px;"
                            data-href-filter="{!! route('qc.ad3d.finance.salary.payment.get') !!}">
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
                                           placeholder="Tìm theo tên" value="{!! $nameFiler !!}" style="height: 25px;">
                                      <span class="input-group-btn">
                                            <button class="btFilterName btn btn-default" type="button"
                                                    style="height: 25px;"
                                                    data-href="{!! route('qc.ad3d.finance.salary.payment.get') !!}">Tìm
                                            </button>
                                      </span>
                                </div>
                            </div>
                            <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <select class="cbMonthFilter" style="margin-top: 5px; height: 25px;"
                                        data-href="{!! route('qc.ad3d.finance.salary.payment.get') !!}">
                                    <option value="0" @if((int)$monthFilter == 0) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($i =1;$i<= 12; $i++)
                                        <option value="{!! $i !!}"
                                                @if((int)$monthFilter == $i) selected="selected" @endif>
                                            {!! $i !!}
                                        </option>
                                    @endfor
                                </select>
                                <span>/</span>
                                <select class="cbYearFilter" style="margin-top: 5px; height: 25px;"
                                        data-href="{!! route('qc.ad3d.finance.salary.payment.get') !!}">
                                    @for($i =2017;$i<= 2050; $i++)
                                        <option value="{!! $i !!}"
                                                @if($yearFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="qc_ad3d_list_content row"
                 data-href-view="{!! route('qc.ad3d.finance.salary.payment.view.get') !!}"
                 data-href-add="{!! route('qc.ad3d.finance.salary.payment.add.get') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: whitesmoke;">
                            <th class="text-center">STT</th>
                            <th>Tên</th>
                            <th class="text-center"></th>
                            <th class="text-right">Tổng lương</th>
                            <th class="text-right">Ứng</th>
                            <th class="text-right">Phạt</th>
                            <th class="text-right">Lương lãnh</th>
                            <th class="text-right">Đã thanh toán</th>
                            <th class="text-right">Chưa thanh toán</th>

                        </tr>
                        @if(count($dataSalary) > 0)
                            <?php
                            $perPage = $dataSalary->perPage();
                            $currentPage = $dataSalary->currentPage();
                            $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                            ?>
                            @foreach($dataSalary as $salary)
                                <?php
                                $salaryId = $salary->salaryId();
                                $salaryPay = $salary->salary();
                                $totalPaid = $salary->totalPaid();
                                $dataWork = $salary->work;
                                $workCompanyId = $dataWork->companyIdOfWork()[0];
                                $totalSalary = $dataWork->totalSalaryBasicOfWorkInMonth($dataWork->workId());
                                ?>
                                <tr class="qc_ad3d_list_object" data-object="{!! $salaryId !!}">
                                    <td class="text-center">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td>
                                        @if(!empty($dataWork->companyStaffWorkId()))
                                            {!! $dataWork->companyStaffWork->staff->fullName() !!}
                                        @else
                                            {!! $salary->work->staff->fullName() !!}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if(!$salary->checkPaid())
                                            @if($workCompanyId == $companyLoginId)
                                                <a class="qc_add qc-link-green">
                                                    Thanh toán
                                                </a>
                                            @else
                                                <em class="qc-color-grey">Chỉ Thanh toán của cty mình</em>
                                            @endif
                                        @else
                                            <em class="qc-color-grey">Đã Thanh toán</em>
                                        @endif
                                        <span>|</span>
                                        <a class="qc_view qc-link-green">
                                            Chi tiết
                                        </a>
                                    </td>
                                    <td class="text-right qc-color-red">
                                        {!! $hFunction->currencyFormat($totalSalary) !!}
                                    </td>
                                    <td class="text-right">
                                        {!! $hFunction->currencyFormat($dataWork->totalMoneyBeforePay()) !!}
                                    </td>
                                    <td class="text-right">
                                        {!! $hFunction->currencyFormat($dataWork->totalMoneyMinus()) !!}
                                    </td>
                                    <td class="text-right qc-color-red">
                                        {!! $hFunction->currencyFormat($salary->salary()) !!}
                                    </td>
                                    <td class="text-right">
                                        {!! $hFunction->currencyFormat($totalPaid) !!}
                                    </td>
                                    <td class="text-right qc-color-red">
                                        {!! $hFunction->dotNumber($salaryPay-$totalPaid) !!}
                                    </td>

                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center qc-padding-top-20 qc-padding-bot-20" colspan="9">
                                    {!! $hFunction->page($dataSalary) !!}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="qc-padding-top-20 qc-padding-bot-20 text-center" colspan="9" >
                                    <em class="qc-color-red">Không tìm thấy thông tin phù hợp</em>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection
