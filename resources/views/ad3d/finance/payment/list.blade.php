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
@extends('ad3d.finance.payment.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
             style="margin-bottom: 10px; padding-top : 10px;padding-bottom: 10px; border-bottom: 2px dashed black;">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! route('qc.ad3d.finance.payment.get') !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <i class="qc-font-size-20 glyphicon glyphicon-list-alt"></i>
                    <label class="qc-font-size-20">DANH SÁCH CHI</label>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">

                    <select class="cbCompanyFilter" name="cbCompanyFilter" style="margin-top: 5px; height: 30px;"
                            data-href-filter="{!! route('qc.ad3d.finance.payment.get') !!}">
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
                                <select class="cbDayFilter" style="margin-top: 5px; height: 30px;"
                                        data-href="{!! route('qc.ad3d.finance.payment.get') !!}">
                                    <option value="0" @if((int)$monthFilter == 0) selected="selected" @endif >Tất cả
                                    </option>
                                    @for($i =1;$i<= 31; $i++)
                                        <option value="{!! $i !!}"
                                                @if((int)$dayFilter == $i) selected="selected" @endif >{!! $i !!}</option>
                                    @endfor
                                </select>
                                /
                                <select class="cbMonthFilter" style="margin-top: 5px; height: 30px;"
                                        data-href="{!! route('qc.ad3d.finance.payment.get') !!}">
                                    @for($i =1;$i<= 12; $i++)
                                        <option value="{!! $i !!}"
                                                @if((int)$monthFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                                    @endfor
                                </select>
                                /
                                <select class="cbYearFilter" style="margin-top: 5px; height: 30px;"
                                        data-href="{!! route('qc.ad3d.finance.payment.get') !!}">
                                    @for($i =2017;$i<= 2050; $i++)
                                        <option value="{!! $i !!}"
                                                @if($yearFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                                    @endfor
                                </select>
                                <select class="cbPaymentTypeFilter" name="cbPaymentTypeFilter"
                                        style="margin-top: 5px; height: 30px;"
                                        data-href="{!! route('qc.ad3d.finance.payment.get') !!}">
                                    <option value="0">Tất cả</option>
                                    @if(count($dataPaymentType)> 0)
                                        @foreach($dataPaymentType as $paymentType)
                                            <option value="{!! $paymentType->typeId() !!}"
                                                    @if($paymentTypeId == $paymentType->typeId()) selected="selected" @endif >{!! $paymentType->name() !!}</option>
                                        @endforeach
                                    @endif
                                </select>
                                    <a class="btn btn-sm btn-primary"
                                       href="{!! route('qc.ad3d.finance.payment.add.get') !!}">
                                        +
                                    </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="text-right qc-color-red col-xs-12 col-sm-12 col-md-12 col-lg-12"
                     style="padding: 2px 0 2px 0; ">
                    <em class="qc-text-under">Tồng Tiền:</em>
                    <span class="qc-font-bold">{!! $hFunction->dotNumber($totalMoneyPayment)  !!}</span>
                </div>
            </div>
            <div class="qc_ad3d_list_content row"
                 data-href-view="{!! route('qc.ad3d.finance.payment.view.get') !!}"
                 data-href-edit="{!! route('qc.ad3d.finance.payment.edit.get') !!}"
                 data-href-del="{!! route('qc.ad3d.finance.payment.delete') !!}">
                @if(count($dataPayment) > 0)
                    <?php
                    $perPage = $dataPayment->perPage();
                    $currentPage = $dataPayment->currentPage();
                    $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                    ?>
                    @foreach($dataPayment as $payment)
                        <?php
                        $paymentId = $payment->paymentId();
                        ?>
                        <div class="qc_ad3d_list_object qc-ad3d-list-object row" data-object="{!! $paymentId !!}">
                            <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-2 col-lg-2">
                                <span>{!! $n_o += 1 !!}).</span>
                                {!! date('d/m/Y', strtotime($payment->datePay())) !!}
                            </div>
                            <div class="text-right qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <b>{!! $hFunction->currencyFormat($payment->money()) !!} </b> đ
                                <em class="qc-color-grey"> - {!! $payment->staff->lastName() !!}</em>
                            </div>
                            <div class="text-right qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <em>{!! $payment->note() !!}</em>
                            </div>
                            <div class="qc-padding-top-5 qc-padding-bot-5 text-right col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <button type="button" class="qc_view qc-link-green btn btn-default btn-sm">
                                    Chi tiết
                                </button>
                                    @if($payment->checkStaffInput($dataStaffLogin->staffId()))
                                        <button type="button" class="qc_edit qc-link-green btn btn-default btn-sm">
                                            Sửa
                                        </button>
                                        <button type="button" class="qc_delete qc-link-green btn btn-default btn-sm">
                                            Xóa
                                        </button>
                                    @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="qc_ad3d_list_object qc-ad3d-list-object row">
                        <div class="qc-padding-top-5 qc-padding-bot-5 text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <em class="qc-color-red">Không tìm thấy thông tin</em>
                        </div>
                    </div>
                @endif
            </div>
            <div class="row">
                <div class="text-center qc-padding-top-20 qc-padding-bot-20 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    {!! $hFunction->page($dataPayment) !!}
                </div>
            </div>
        </div>
    </div>
@endsection
