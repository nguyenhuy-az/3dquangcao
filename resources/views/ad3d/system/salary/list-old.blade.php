<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:32 PM
 *
 * modelStaff
 * dataCompany
 * dataStaffSalaryBasic
 * companyFilterId
 *
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$dataStaffLogin = $modelStaff->loginStaffInfo();
?>
@extends('ad3d.system.salary.index')
@section('qc_ad3d_staff_salary')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
             style="margin-bottom: 10px; padding-top : 10px;padding-bottom: 10px; border-bottom: 2px dashed brown;">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <i class="qc-font-size-20 glyphicon glyphicon-list-alt"></i>
                    <label class="qc-font-size-20">Bảng lương cơ bản</label>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <select class="cbCompanyFilter" name="cbCompanyFilter" style="margin-top: 5px; height: 30px;"
                            data-href-filter="{!! route('qc.ad3d.system.salary.get') !!}">
                        @if($dataStaffLogin->checkRootManage())
                            <option value="">Tất cả</option>
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
            {{--
            <div class="row">
                <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 2px 0 2px 0; ">
                    <form name="" action="">
                        <div class="row">
                            <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <input class="col-xs-12" type="text" value="" placeholder="Tên hoặc mã nhân viên" style="height: 30px;">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            --}}
            <div class="qc_ad3d_list_content row"
                 data-href-view="{!! route('qc.ad3d.system.salary.view.get') !!}"
                 data-href-edit="{!! route('qc.ad3d.system.salary.edit.get') !!}">
                @if(count($dataStaffSalaryBasic) > 0)
                    <?php
                    $perPage = $dataStaffSalaryBasic->perPage();
                    $currentPage = $dataStaffSalaryBasic->currentPage();
                    $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                    ?>
                    @foreach($dataStaffSalaryBasic as $salaryBasic)
                        <?php
                        $salaryBasicId = $salaryBasic->salaryBasicId();
                        ?>
                        <div class="qc_ad3d_list_object qc-ad3d-list-object row" data-object="{!! $salaryBasicId !!}"
                             data-staff="{!! $salaryBasic->staffId() !!}">
                            <div class="text-left col-xs-12 col-sm-12 col-md-4 col-lg-4"
                                 style="padding-top:5px; padding-bottom: 5px; ">
                                <b>{!! $n_o += 1 !!}).</b> {!! $salaryBasic->staff->fullName() !!}
                            </div>
                            <div class="text-right col-xs-12 col-sm-12 col-md-4 col-lg-4"
                                 style="padding-top:5px; padding-bottom: 5px; ">
                                <span>
                                    {!! $hFunction->currencyFormat($salaryBasic->salary()) !!} <b>VND</b>
                                </span>
                            </div>
                            <div class="text-right col-xs-12 col-sm-12 col-md-4 col-lg-4"
                                 style="padding-top:5px; padding-bottom: 5px; ">
                                <a class="qc_view qc-link-green">
                                    Chi tiết
                                </a>
                                <span>|</span>
                                <a class="qc_edit qc-link-green" href="#">Sửa</a>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="row">
                <div class="text-center qc-padding-top-20 qc-padding-bot-20 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    {!! $hFunction->page($dataStaffSalaryBasic) !!}
                </div>
            </div>
        </div>
    </div>
@endsection
