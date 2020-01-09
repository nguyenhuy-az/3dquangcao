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
$payId = $dataSalaryBeforePay->payId();
$money = $dataSalaryBeforePay->money();
$description = $dataSalaryBeforePay->description();

$dataWork = $dataSalaryBeforePay->work;
$workId = $dataSalaryBeforePay->workId();
$totalMoneyMinus = $dataWork->totalMoneyMinus();
$totalMoneyBeforePay = $dataWork->totalMoneyBeforePay();

$limitBeforePay = $dataWork->limitBeforePay($workId);
$limitBeforePay = ($limitBeforePay > 0) ? $limitBeforePay : 0;
$companyStaffWorkId = $dataWork->companyStaffWorkId();

if (!empty($companyStaffWorkId)) {
    $dataStaffWorkSalary = $modelCompanyStaffWork->staffWorkSalaryActivity($companyStaffWorkId);
    if (count($dataStaffWorkSalary) > 0) { # da co ban luong co ban cua he thong
        $salaryOneHour = $dataStaffWorkSalary->salaryOnHour();
        $totalCurrentSalary = $dataWork->totalSalaryBasicOfWorkInMonth($workId);//$dataWork->totalCurrentSalary();
    }
} else {
    $dataStaffWorkSalary = $modelCompanyStaffWork->staffWorkSalaryActivityOfStaff($staffId);
    if (count($dataStaffWorkSalary) > 0) {
        $salaryBasic = $dataStaffWorkSalary->salary();
        $salaryOneHour = $dataStaffWorkSalary->salaryOnHour();
        $totalCurrentSalary = $dataWork->totalSalaryBasicOfWorkInMonth($workId);//$dataWork->totalCurrentSalary();
    } else {
        # truong hop phien ban cu chua cap nhat
        $salaryBasic = $dataWork->staff->salaryBasicOfStaff($staffId);
        if (count($salaryBasic) > 0) { # da co ban luong co ban cua he thong
            $salaryOneHour = floor($salaryBasic / 208);
            $totalCurrentSalary = $dataWork->totalSalaryBasicOfWorkInMonth();//$dataWork->totalCurrentSalary();
        }
    }
}
$limitBeforePay = $limitBeforePay + $money;
$limitBeforePay = ($limitBeforePay > 2000000) ? 2000000 : $limitBeforePay;
?>
@extends('components.container.container-6')
@section('qc_container_content')
    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
                <h3>SỬA THÔNG TIN ỨNG</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form id="frmWorkPaySalaryBeforePayEdit" class="frmWorkPaySalaryBeforePayEdit" role="form" method="post"
                      action="{!! route('qc.work.pay.salary_before_pay.edit.post',$payId) !!}">
                    <div class="row">
                        <div class="qc-padding-top-20 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="qc_notify form-group text-center qc-color-red">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm qc-padding-none">
                                <label>Nhân viên:</label>
                                <input type="text" class="form-control" readonly
                                       name="txtStaff" value="{!! $dataWork->companyStaffWork->staff->fullName() !!}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm qc-padding-none">
                                <label>Hạn mức ứng:</label>
                                <input type="text" class="txtLimitBeforePay form-control" readonly
                                       name="txtLimitBeforePay"
                                       value="{!! $hFunction->currencyFormat($limitBeforePay) !!}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm qc-padding-none">
                                <label>Số tiền (VND)
                                    <i class="qc-color-red glyphicon glyphicon-star-empty"></i>:</label>
                                <input type="text" class="form-control" name="txtMoney" value="{!! $hFunction->currencyFormat($money) !!}"
                                       onkeyup="qc_main.showFormatCurrency(this);"
                                       placeholder="Nhập số tiền">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm qc-padding-none">
                                <label>Ghi chú <i class="qc-color-red glyphicon glyphicon-star-empty"></i>: </label>
                                <input type="text" class="form-control" name="txtDescription" value="{!! $description !!}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm qc-padding-none">
                                <em style="text-decoration: underline">Lưu ý:</em><br/>
                                <span class="qc-color-red">Khi người ứng xác nhận mới giao tiền</span>
                            </div>
                        </div>
                        <div class="qc-padding-bot-20  qc-border-none col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <button type="button" class="qc_save btn btn-sm btn-primary">Lưu</button>
                            <button type="button" class="qc_container_close btn btn-sm btn-default">Đóng</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
