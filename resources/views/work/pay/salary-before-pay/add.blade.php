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

if (count($dataWorkSelect) > 0) {
    $workSelectId = $dataWorkSelect->workId();
} else {
    $workSelectId = null;
}
?>
@extends('work.pay.salary-before-pay.index')
@section('qc_work_pay_salary_before_pay_body')
    <div class="col-sx-12 col-sm-12 col-md-8 col-lg-6">
        <div class="row">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
                <h3>ỨNG LƯƠNG MUA VẬT TƯ</h3>
            </div>
        </div>
        @if (Session::has('notifyAdd'))
            <div class="row">
                <div class="qc-padding-top-20 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group text-center qc-color-red">
                        {!! Session::get('notifyAdd') !!}
                        <?php
                        Session::forget('notifyAdd');
                        ?>
                    </div>
                </div>
                <div class="qc-padding-top-20 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group text-center qc-color-red">
                        <a href="{!! route('qc.work.pay.salary_before_pay.add.get') !!}">
                            <button type="button" class="btn btn-sm btn-primary">Tiếp tục</button>
                        </a>
                        <a href="{!! route('qc.work.pay.salary_before_pay.get') !!}">
                            <button type="button" class="btn btn-sm btn-default">Đóng</button>
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <form id="frmWorkPaySalaryBeforePayAdd" class="frmWorkPaySalaryBeforePayAdd" role="form" method="post"
                          action="{!! route('qc.work.pay.salary_before_pay.add.post') !!}">

                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group form-group-sm qc-padding-none">
                                    <label>Nhân viên: <i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                    <select name="cbWork" class="cbWork form-control"
                                            data-href="{!! route('qc.work.pay.salary_before_pay.add.post') !!}">
                                        <option value="">Chọn nhân viên</option>
                                        @if(count($dataWork) > 0)
                                            @foreach($dataWork as $work)
                                                <option value="{!! $work->workId() !!}"
                                                        @if($work->workId() == $workSelectId) selected="selected" @endif>
                                                    @if(!empty($work->companyStaffWorkId()))
                                                        {!! $work->companyStaffWork->staff->fullName() !!}
                                                        - {!! $work->companyStaffWork->staff->identityCard() !!}
                                                    @else
                                                        {!! $work->staff->fullName() !!}
                                                        - {!! $work->staff->identityCard() !!}
                                                    @endif
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        @if(!empty($workSelectId))
                            <?php
                            $workId = $dataWorkSelect->workId();
                            $totalMoneyMinus = $dataWorkSelect->totalMoneyMinus();
                            $totalMoneyBeforePay = $dataWorkSelect->totalMoneyBeforePay();

                            $limitBeforePay = $dataWorkSelect->limitBeforePay($workId);
                            $limitBeforePay = ($limitBeforePay > 0) ? $limitBeforePay : 0;
                            $companyStaffWorkId = $dataWorkSelect->companyStaffWorkId();

                            if (!empty($companyStaffWorkId)) {
                                $dataStaffWorkSalary = $modelCompanyStaffWork->staffWorkSalaryActivity($companyStaffWorkId);
                                if (count($dataStaffWorkSalary) > 0) { # da co ban luong co ban cua he thong
                                    $salaryOneHour = $dataStaffWorkSalary->salaryOnHour();
                                    $totalCurrentSalary = $dataWorkSelect->totalSalaryBasicOfWorkInMonth($workId);//$dataWork->totalCurrentSalary();
                                }
                            } else {
                                $dataStaffWorkSalary = $modelCompanyStaffWork->staffWorkSalaryActivityOfStaff($staffId);
                                if (count($dataStaffWorkSalary) > 0) {
                                    $salaryBasic = $dataStaffWorkSalary->salary();
                                    $salaryOneHour = $dataStaffWorkSalary->salaryOnHour();
                                    $totalCurrentSalary = $dataWorkSelect->totalSalaryBasicOfWorkInMonth($workId);//$dataWork->totalCurrentSalary();
                                } else {
                                    # truong hop phien ban cu chua cap nhat
                                    $salaryBasic = $dataWorkSelect->staff->salaryBasicOfStaff($staffId);
                                    if (count($salaryBasic) > 0) { # da co ban luong co ban cua he thong
                                        $salaryOneHour = floor($salaryBasic / 208);
                                        $totalCurrentSalary = $dataWorkSelect->totalSalaryBasicOfWorkInMonth();//$dataWork->totalCurrentSalary();
                                    }
                                }
                            }
                            $limitBeforePay = ($limitBeforePay > 2000000) ? 2000000 : $limitBeforePay;
                            ?>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-group form-group-sm qc-padding-none">
                                        <label>Hạn mức ứng:</label>
                                        <input type="text" class="qc_limitBeforePay form-control" readonly
                                               name="txtLimitBeforePay"
                                               value="{!! $hFunction->currencyFormat($limitBeforePay) !!}">
                                    </div>
                                </div>
                            </div>
                            @if($limitBeforePay >= 100000)
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-group form-group-sm qc-padding-none">
                                            <label>Số tiền (VND)<i class="qc-color-red glyphicon glyphicon-star-empty"></i>:</label>
                                            <input type="text" class="form-control" name="txtMoney"
                                                   onkeyup="qc_main.showFormatCurrency(this);"
                                                   placeholder="Nhập số tiền">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-group form-group-sm qc-padding-none">
                                            <label>Ghi chú <i class="qc-color-red glyphicon glyphicon-star-empty"></i>:
                                            </label>
                                            <input type="text" class="form-control" name="txtDescription" value="">
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-group form-group-sm text-center qc-padding-none">
                                            <em class="qc-color-red">Không còn đủ tiền để ứng</em>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        @endif

                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group form-group-sm qc-padding-none">
                                    <em style="text-decoration: underline">Lưu ý:</em><br/>
                                    <span class="qc-color-red">Khi người ứng xác nhận mới giao tiền</span>
                                </div>
                            </div>
                            <div class="qc-padding-bot-20  qc-border-none col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                @if(!empty($workSelectId) && $limitBeforePay >= 100000)
                                    <button type="button" class="qc_save btn btn-sm btn-primary">Lưu</button>
                                    <button type="reset" class="btn btn-sm btn-default">Hủy</button>
                                    <a href="{!! route('qc.work.pay.salary_before_pay.get') !!}">
                                        <button type="button" class="btn btn-sm btn-default">Đóng</button>
                                    </a>
                                @else
                                    <a href="{!! route('qc.work.pay.salary_before_pay.get') !!}">
                                        <button type="button" class="btn btn-sm btn-primary">Đóng</button>
                                    </a>
                                @endif

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif

    </div>
@endsection
