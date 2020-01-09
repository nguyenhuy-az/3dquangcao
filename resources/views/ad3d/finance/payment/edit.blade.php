<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 *
 * dataStaffSalaryBasic
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$dataStaffLogin = $modelStaff->loginStaffInfo();

?>
@extends('ad3d.components.container.container-6')
@section('qc_ad3d_container_content')
    <div class="qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <h3>SỬA</h3>
            </div>
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form class="frmEdit" name="frmEdit" role="form" method="post"
                      action="{!! route('qc.ad3d.finance.payment.edit.get',$dataPayment->paymentId()) !!}">
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
                         @if($mobileStatus) style="padding: 0 0;" @endif>
                        <div class="frm_notify text-center form-group qc-color-red"></div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                             @if($mobileStatus) style="padding: 0 0;" @endif>
                            <div class="form-group qc-padding-none">
                                <label>
                                    Công ty:
                                    <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                </label>
                                <select class="cbCompanyAdd form-control" name="cbCompanyAdd">
                                    <?php
                                    $companyLoginId = $dataPayment->companyId();
                                    ?>
                                    @if(count($dataCompany)> 0)
                                        @foreach($dataCompany as $company)
                                            @if($dataStaffLogin->checkRootManage())
                                                <option value="{!! $company->companyId() !!}"
                                                        @if($companyLoginId == $company->companyId()) selected="selected" @endif >
                                                    {!! $company->name() !!}
                                                </option>
                                            @else
                                                @if($companyLoginId == $company->companyId())
                                                    <option value="{!! $company->companyId() !!}">{!! $company->name() !!}</option>
                                                @endif
                                            @endif
                                        @endforeach
                                    @endif

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                             @if($mobileStatus) style="padding: 0 0;" @endif>
                            <div class="form-group qc-padding-none">
                                <label>
                                    Lĩnh vực chi:
                                    <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                </label>
                                <select class="cbPaymentType form-control" name="cbPaymentType">
                                    @if(count($dataPaymentType)> 0)
                                        @foreach($dataPaymentType as $paymentType)
                                            <option value="{!! $paymentType->typeId() !!}"
                                                    @if($dataPayment->typeId() == $paymentType->typeId()) selected="selected" @endif>
                                                {!! $paymentType->name() !!}
                                            </option>
                                        @endforeach
                                    @endif

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                             @if($mobileStatus) style="padding: 5px 0 5px 0;" @endif>
                            <label>
                                Ngày chi:
                                <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                            </label>
                            <?php
                            $datePay = $dataPayment->datePay();
                            ?>
                            <div class="form-group">
                                <select name="cbDay" style="margin-top: 5px; height: 30px;">
                                    @for($i = 1;$i<= 31; $i++)
                                        <option value="{!! $i !!}"
                                                @if($i === (int)$hFunction->getDayFromDate($datePay)) selected="selected" @endif >
                                            {!! $i !!}
                                        </option>
                                    @endfor
                                </select>
                                /
                                <select name="cbMonth" style="margin-top: 5px; height: 30px;">
                                    @for($i = 1;$i<= 12; $i++)
                                        <option value="{!! $i !!}"
                                                @if($i === (int)$hFunction->getMonthFromDate($datePay)) selected="selected" @endif >
                                            {!! $i !!}
                                        </option>
                                    @endfor
                                </select>
                                /
                                <select name="cbYear" style="margin-top: 5px; height: 30px;">
                                    @for($i = 2017;$i<= 2050; $i++)
                                        <option value="{!! $i !!}"
                                                @if($i === (int)$hFunction->getYearFromDate($datePay)) selected="selected" @endif>
                                            {!! $i !!}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                             @if($mobileStatus) style="padding: 0 0;" @endif>
                            <div class="form-group qc-padding-none">
                                <label>
                                    Số tiền (VND):
                                    <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                </label>
                                <input type="number" class="form-control" name="txtMoney"
                                       value="{!! $dataPayment->money() !!}"
                                       placeholder="Nhập số tiền">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                             @if($mobileStatus) style="padding: 0 0;" @endif>
                            <div class="form-group qc-padding-none">
                                <label>
                                    Lý do:
                                    <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                </label>
                                <input type="text" class="form-control" name="txtReason"
                                       value="{!! $dataPayment->note() !!}">
                            </div>
                        </div>
                    </div>
                    <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <button type="button" class="qc_save btn btn-primary">Lưu</button>
                        <button type="reset" class="btn btn-default">Hủy</button>
                        <button type="button" class="qc_ad3d_container_close btn btn-default">Đóng</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
