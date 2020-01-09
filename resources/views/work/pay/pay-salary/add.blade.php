<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$dataWork = $dataSalary->work;
if(!empty($dataWork->companyStaffWorkId())){
    $staffName = $dataWork->companyStaffWork->staff->fullName();
}else{
    $staffName = $dataSalary->work->staff->fullName();
}
?>
@extends('components.container.container-6')
@section('qc_container_content')
    <div class="qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12"
                 style="padding-top: 20px;padding-bottom: 20px; border-bottom: 2px dashed brown;">
                <h3>THANH TOÁN LƯƠNG</h3>
            </div>
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form id="frm_work_pay_salary_add" role="form" method="post"
                      action="{!! route('qc.work.pay.pay_salary.add.post', $dataSalary->salaryId()) !!}">
                    <div class="row">
                        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group frm_notify text-center qc-color-red qc-padding-top-20"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sx-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group form-group-sm">
                                <label>Nhân viên</label>
                                <input type="text" class="form-control" readonly
                                       value="{!! $staffName  !!}">
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group form-group-sm">
                                <label>Lương lãnh:</label>
                                <input type="text" class="form-control" readonly
                                       value="{!! $hFunction->currencyFormat($dataSalary->salary()) !!}">
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group form-group-sm">
                                <label>Đã thanh toán:</label>
                                <input type="text" class="form-control" readonly
                                       value="{!! $hFunction->currencyFormat($dataSalary->totalPaid()) !!}">
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group form-group-sm">
                                <label>Còn lại:</label>
                                <input type="text" class="form-control" readonly
                                       value="{!! $hFunction->currencyFormat($dataSalary->salary() - $dataSalary->totalPaid()) !!}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm">
                                <label>Số tiền <i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                <input type="text" class="form-control" name="txtMoney" data-pay-limit="{!! $dataSalary->salary() - $dataSalary->totalPaid() !!}" placeholder="Nhập số tiền thanh toán"
                                       value="" onkeyup="qc_main.showFormatCurrency(this);">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <button type="button" class="qc_save btn btn-sm btn-primary">Lưu</button>
                                <button type="button" class="qc_container_close btn btn-sm btn-default">Đóng</button>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
