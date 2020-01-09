<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 */
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$hFunction = new Hfunction();
$dataStaffLogin = $modelStaff->loginStaffInfo();
?>
@extends('ad3d.finance.payment.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
            <h3>CHI</h3>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <form id="frmAdd" class="frmAdd" role="form" method="post"
                  action="{!! route('qc.ad3d.finance.payment.add.post') !!}">
                <div class="row">
                    <div class="qc-padding-top-20 col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        @if (Session::has('notifyAdd'))
                            <div class="form-group text-center qc-color-red">
                                {!! Session::get('notifyAdd') !!}
                                <?php
                                Session::forget('notifyAdd');
                                ?>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6"
                         @if($mobileStatus) style="padding: 0 0;" @endif>
                        <div class="form-group qc-padding-none">
                            <label>
                                Công ty:
                                <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                            </label>
                            <select class="cbCompanyAdd form-control" name="cbCompanyAdd">
                                @if($dataStaffLogin->checkRootManage())
                                    <option value="">Chọn cty</option>
                                @endif
                                <?php
                                $companyLoginId = $dataStaffLogin->companyId();
                                ?>
                                @if(count($dataCompany)> 0)
                                    @foreach($dataCompany as $company)
                                        @if($dataStaffLogin->checkRootManage())
                                            <option value="{!! $company->companyId() !!}">
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
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6"
                         @if($mobileStatus) style="padding: 0 0;" @endif>
                        <div class="form-group qc-padding-none">
                            <label>
                                Lĩnh vực chi:
                                <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                            </label>
                            <select class="cbPaymentType form-control" name="cbPaymentType">
                                <option value="">Chọn lĩnh vực</option>
                                @if(count($dataPaymentType)> 0)
                                    @foreach($dataPaymentType as $paymentType)
                                        <option value="{!! $paymentType->typeId() !!}">
                                            {!! $paymentType->name() !!}
                                        </option>
                                    @endforeach
                                @endif

                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6"
                         @if($mobileStatus) style="padding: 5px 0 5px 0;" @endif>
                        <label>
                            Ngày chi:
                            <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                        </label>

                        <div class="form-group">
                            <select name="cbDay" style="margin-top: 5px; height: 30px;">
                                @for($i = 1;$i<= 31; $i++)
                                    <option value="{!! $i !!}" @if($i === (int)date('d')) selected="selected" @endif >
                                        {!! $i !!}
                                    </option>
                                @endfor
                            </select>
                            <span>/</span>
                            <select name="cbMonth" style="margin-top: 5px; height: 30px;">
                                @for($i = 1;$i<= 12; $i++)
                                    <option value="{!! $i !!}" @if($i === (int)date('m')) selected="selected" @endif >
                                        {!! $i !!}
                                    </option>
                                @endfor
                            </select>
                            <span>/</span>
                            <select name="cbYear" style="margin-top: 5px; height: 30px;">
                                @for($i = 2017;$i<= 2050; $i++)
                                    <option value="{!! $i !!}" @if($i === (int)date('Y')) selected="selected" @endif>
                                        {!! $i !!}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6"
                         @if($mobileStatus) style="padding: 0 0;" @endif>
                        <div class="form-group qc-padding-none">
                            <label>
                                Số tiền (VND):
                                <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                            </label>
                            <input type="number" class="form-control" name="txtMoney"
                                   placeholder="Nhập số tiền">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6"
                         @if($mobileStatus) style="padding: 0 0;" @endif>
                        <div class="form-group qc-padding-none">
                            <label>
                                Lý do:
                                <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                            </label>
                            <input type="text" class="form-control" name="txtReason" value="">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="qc-padding-top-20 qc-padding-bot-20  qc-border-none col-sx-12 col-sm-12 col-md-12 col-lg-12"
                         @if($mobileStatus) style="padding: 0 0;" @endif>
                        <div class="text-left form-group qc-padding-none">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <button type="button" class="qc_save btn btn-primary">Lưu</button>
                            <button type="reset" class="btn btn-default">Hủy</button>
                            <a href="{!! route('qc.ad3d.finance.payment.get') !!}">
                                <button type="button" class="btn btn-default">Đóng</button>
                            </a>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
