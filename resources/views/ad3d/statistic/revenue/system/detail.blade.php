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
$statisticCompanyId = $dataAccess['statisticCompanyId'];
$statisticDate = $dataAccess['statisticDate'];
//chi
$totalPaid = $dataAccess['totalPaid'];
$totalSalaryBeforePay = $dataAccess['totalSalaryBeforePay'];
$totalSalaryPaid = $dataAccess['totalSalaryPaid'];
//Thu
$totalOrderPay = $dataAccess['totalOrderPay'];
?>
@extends('ad3d.statistic.revenue.system.index')
@section('qc_ad3d_index_content')
    <div class="row" style="padding-bottom: 50px;">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
             style="padding-top:10px; padding-bottom: 10px; border-bottom: 2px dashed brown; ">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! route('qc.ad3d.statistic.revenue.get') !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <label class="qc-font-size-20">THỐNG KÊ DOANH THU</label>
                </div>
                {{--<div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <select class="cbCompanyFilter" name="cbCompanyFilter" style="margin-top: 5px; height: 30px;"
                            data-href-filter="{!! route('qc.ad3d.statistic.revenue.get') !!}">
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
                </div>--}}
            </div>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            {{-- lọc theo ngày tháng --}}
            <div class="row">
                <div class="qc-padding-none text-right col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <select class="cbDayFilter" style="margin-top: 5px; height: 30px;"
                            data-href="{!! route('qc.ad3d.statistic.revenue.get') !!}">
                        <option value="0" @if((int)$dayFilter == 0) selected="selected" @endif >Tất cả
                        </option>
                        @for($i =1;$i<= 31; $i++)
                            <option value="{!! $i !!}"
                                    @if((int)$dayFilter == $i) selected="selected" @endif >{!! $i !!}</option>
                        @endfor
                    </select>
                    /
                    <select class="cbMonthFilter" style="margin-top: 5px; height: 30px;"
                            data-href="{!! route('qc.ad3d.statistic.revenue.get') !!}">
                        <option value="0" @if((int)$monthFilter == 0) selected="selected" @endif >Tất cả
                        @for($i =1;$i<= 12; $i++)
                            <option value="{!! $i !!}"
                                    @if((int)$monthFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                        @endfor
                    </select>
                    /
                    <select class="cbYearFilter" style="margin-top: 5px; height: 30px;"
                            data-href="{!! route('qc.ad3d.statistic.revenue.get') !!}">
                        <option value="0" @if((int)$yearFilter == 0) selected="selected" @endif >Tất cả
                        @for($i =2017;$i<= 2050; $i++)
                            <option value="{!! $i !!}"
                                    @if($yearFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                        @endfor
                    </select>
                </div>
            </div>
            {{-- nội dung --}}
            <div class="row">
                <div class="qc-margin-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        <table class="table table-bordered">
                            <tr style="background-color: blue; color: white; font-weight: bold;">
                                <td class="text-center col-sx-4 col-sm-4 col-md-4 col-lg-4">Thu</td>
                                <td class="text-center col-sx-4 col-sm-4 col-md-4 col-lg-4">Chi</td>
                                <td class="text-center col-sx-4 col-sm-4 col-md-4 col-lg-4">Doanh thu</td>
                            </tr>
                            <tr class="qc-color-red">
                                <td class="text-center col-sx-4 col-sm-4 col-md-4 col-lg-4">
                                    {!! $hFunction->dotNumber($totalOrderPay) !!}
                                </td>
                                <td class="text-center col-sx-4 col-sm-4 col-md-4 col-lg-4">
                                    {!! $hFunction->dotNumber($totalPaid + $totalSalaryBeforePay + $totalSalaryPaid) !!}
                                </td>
                                <td class="text-center col-sx-4 col-sm-4 col-md-4 col-lg-4">
                                    {!! $hFunction->dotNumber($totalOrderPay - $totalPaid - $totalSalaryBeforePay - $totalSalaryPaid) !!}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                {{-- Chi --}}
                <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 3px solid brown;">
                            <i class="glyphicon glyphicon-arrow-right qc-color-green"></i>
                            <span class="qc-font-size-20 qc-color-green">Chi</span>
                            <b class="qc-color-red">
                                ({!! $hFunction->dotNumber($totalPaid + $totalSalaryBeforePay + $totalSalaryPaid) !!})
                            </b>
                        </div>
                    </div>
                    <?php
                    $dataPaymentStaffInfo = $dataAccess['dataPaymentStaffInfo'];
                    $dataPaymentType = $modelStaff->paymentTypeInfo();
                    $no_paymentStaffInfo = 0;
                    ?>
                    @if(count($dataPaymentStaffInfo))
                        @foreach($dataPaymentStaffInfo as $staffPaidInfo)
                            <?php
                            $totalPaidOfStaff = 0;
                            $staffPaidId = $staffPaidInfo->staffId();
                            $totalSalaryBeforePayOfStaff = $modelStaff->totalSalaryBeforeOfCompanyStaffDate($statisticCompanyId, $staffPaidId, $statisticDate);
                            $totalSalaryPayOfStaff = $modelStaff->totalSalaryPaidCompanyStaffDate($statisticCompanyId, $staffPaidId, $statisticDate);
                            ?>
                            <div class="row">
                                <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <b>{!! $no_paymentStaffInfo += 1  !!})</b>
                                    <b>{!! $staffPaidInfo->fullName() !!}</b>
                                </div>
                                @if(count($dataPaymentType)>0)
                                    @foreach($dataPaymentType as $paymentType)
                                        <?php
                                        $totalPaidOfType = $modelPayment->totalPaidOfCompanyStaffTypeDate($statisticCompanyId, $staffPaidId, $paymentType->typeId(), $statisticDate);
                                        $totalPaidOfStaff = $totalPaidOfStaff + $totalPaidOfType;
                                        ?>
                                        <div class="qc-padding-top-5 qc-padding-bot-5 text-right col-xs-12 col-sm-12 col-md-2 col-lg-2">
                                            <em>{!! $paymentType->name() !!}:</em>
                                            <b>{!! $hFunction->dotNumber($totalPaidOfType) !!}</b>
                                        </div>
                                    @endforeach
                                @endif
                                <div class="qc-padding-top-5 qc-padding-bot-5 text-right col-xs-12 col-sm-12 col-md-2 col-lg-2"
                                     style="color: brown;">
                                    <em>Ứng lương:</em>
                                    <b>{!! $hFunction->dotNumber($totalSalaryBeforePayOfStaff) !!}</b>
                                </div>
                                <div class="qc-padding-top-5 qc-padding-bot-5 text-right col-xs-12 col-sm-12 col-md-2 col-lg-2"
                                     style="color: brown;">
                                    <em>Trả lương:</em>
                                    <b>{!! $hFunction->dotNumber($totalSalaryPayOfStaff) !!}</b>
                                </div>
                                <div class="qc-padding-top-5 qc-padding-bot-5 text-right col-xs-12 col-sm-12 col-md-2 col-lg-2"
                                     style="color: red;">
                                    <em>Tổng chi:</em>
                                    <b>{!! $hFunction->dotNumber($totalPaidOfStaff + $totalSalaryBeforePayOfStaff + $totalSalaryPayOfStaff) !!}</b>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="row">
                            <div class="qc-color-red qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <em>Không có thông tin chi</em>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Thu --}}
                <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 3px solid brown;">
                            <i class="glyphicon glyphicon-arrow-right qc-color-green"></i>
                            <span class="qc-font-size-20 qc-color-green">Thu</span>
                            <b class="qc-color-red">
                                ({!! $hFunction->dotNumber($totalOrderPay) !!})
                            </b>
                        </div>
                    </div>
                    <?php
                    $dataStaffOrderPayInfo = $dataAccess['dataStaffOrderPayInfo'];
                    //$dataPaymentType = $modelStaff->paymentTypeInfo();
                    $no_staffOrderPayInfo = 0;
                    ?>
                    @if(count($dataStaffOrderPayInfo))
                        @foreach($dataStaffOrderPayInfo as $staffOrderPayInfo)
                            <?php
                            $totalOrderPayOfStaff = $modelCompany->totalOrderPayOfCompanyStaffDate($statisticCompanyId, $staffOrderPayInfo->staffId(), $statisticDate);
                            ?>
                            <div class="row">
                                <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <b>{!! $no_staffOrderPayInfo += 1  !!})</b>
                                    <b>{!! $staffOrderPayInfo->fullName() !!}</b>
                                </div>
                                <div class="qc-padding-top-5 qc-padding-bot-5 text-right col-xs-12 col-sm-12 col-md-6 col-lg-6"
                                     style="color: red;">
                                    <em>Tổng:</em>
                                    <b>{!! $hFunction->dotNumber($totalOrderPayOfStaff) !!}</b>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="row">
                            <div class="qc-color-red qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <em>Không có thông tin chi</em>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Doanh Thu cá nhân --}}
                <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 3px solid brown;">
                            <i class="glyphicon glyphicon-arrow-right qc-color-green"></i>
                            <span class="qc-font-size-20 qc-color-green">Doanh thu cá nhân</span>
                        </div>
                    </div>
                    <?php
                    $dataManageStaffInfo = $modelStaff->infoManagerOfCompany($statisticCompanyId);
                    //$dataPaymentType = $modelStaff->paymentTypeInfo();
                    $no_manageStaffInfo = 0;
                    ?>
                    @if(count($dataManageStaffInfo))
                        @foreach($dataManageStaffInfo as $manageStaffInfo)
                            <?php
                            $manageStaffId = $manageStaffInfo->staffId();
                            $totalOrderPayOfManageStaff = $modelCompany->totalOrderPayOfCompanyStaffDate($statisticCompanyId, $manageStaffId, $statisticDate);
                            $totalPaidOfStaffAndCompany = $modelPayment->totalPaidOfStaffAndCompany($statisticCompanyId, $manageStaffId, $statisticDate);
                            $totalMoneyOfManageStaffSalaryBeforePay = $modelStaff->totalSalaryBeforeOfCompanyStaffDate($statisticCompanyId, $manageStaffId, $statisticDate);
                            $totalReceiveMoneyOfManageStaff = $modelStaff->totalReceiveMoneyOfStaffAnfCompany($statisticCompanyId, $manageStaffId, $statisticDate);
                            $totalTransfersMoneyOfManageStaff = $modelStaff->totalTransfersMoneyOfStaff($manageStaffId, $statisticDate);
                            ?>
                            <div class="row">
                                <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-2 col-lg-2">
                                    <b>{!! $no_manageStaffInfo += 1  !!})</b>
                                    <b>{!! $manageStaffInfo->fullName() !!}</b>
                                </div>
                                <div class="qc-padding-top-5 qc-padding-bot-5 text-right col-xs-12 col-sm-12 col-md-2 col-lg-2">
                                    <em>Thu:</em>
                                    <b>{!! $hFunction->dotNumber($totalOrderPayOfManageStaff) !!}</b>
                                </div>
                                <div class="qc-padding-top-5 qc-padding-bot-5 text-right col-xs-12 col-sm-12 col-md-2 col-lg-2">
                                    <em>Chi:</em>
                                    <b>{!! $hFunction->dotNumber($totalPaidOfStaffAndCompany + $totalMoneyOfManageStaffSalaryBeforePay) !!}</b>
                                </div>
                                <div class="qc-padding-top-5 qc-padding-bot-5 text-right col-xs-12 col-sm-12 col-md-2 col-lg-2">
                                    <em>Tiền nhận:</em>
                                    <b style="color: brown;">{!! $hFunction->dotNumber($totalReceiveMoneyOfManageStaff) !!}</b>
                                </div>
                                <div class="qc-padding-top-5 qc-padding-bot-5 text-right col-xs-12 col-sm-12 col-md-2 col-lg-2">
                                    <em>Tiền giao:</em>
                                    <b style="color: brown;">{!! $hFunction->dotNumber($totalTransfersMoneyOfManageStaff) !!}</b>
                                </div>
                                <div class="qc-padding-top-5 qc-padding-bot-5 text-right col-xs-12 col-sm-12 col-md-2 col-lg-2">
                                    <em>Tổng tiền:</em>
                                    <b style="color: red;">{!! $hFunction->dotNumber($totalOrderPayOfManageStaff - $totalPaidOfStaffAndCompany + $totalMoneyOfManageStaffSalaryBeforePay  + $totalReceiveMoneyOfManageStaff - $totalTransfersMoneyOfManageStaff ) !!}</b>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="row">
                            <div class="qc-color-red qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <em>Không có thông tin chi</em>
                            </div>
                        </div>
                    @endif
                </div>


            </div>
        </div>
    </div>
@endsection
