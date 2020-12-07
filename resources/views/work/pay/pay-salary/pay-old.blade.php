<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$urlReferer = $hFunction->getUrlReferer();
$mobileStatus = $mobile->isMobile();
$dataWork = $dataSalary->work;
# thong tin nhan vien
if (!empty($dataWork->companyStaffWorkId())) {
    //$staffName = $dataWork->companyStaffWork->staff->fullName();
    $dataStaffSalary = $dataWork->companyStaffWork->staff;
} else {
    $dataStaffSalary = $dataSalary->work->staff;
}
$bankAccount = $dataStaffSalary->bankAccount();
$bankName = $dataStaffSalary->bankName();
// luong
$salaryPay = $dataSalary->salary();
// cong them
$benefitMoney = $dataSalary->benefitMoney();
# da thanh toan
$totalSalaryPaid = $dataSalary->totalPaid();
# tien giu
$keepMoney = $dataSalary->totalKeepMoney();
# chua thanh toan
$totalSalaryUnpaid = $salaryPay - $totalSalaryPaid;

$totalMoneyAllPay = $totalSalaryUnpaid + $totalMoneyImportUnpaid + $totalKPIMoney + $benefitMoney - $keepMoney;
?>
@extends('work.pay.pay-salary.index')
@section('titlePage')
    Thanh toán lương
@endsection
@section('qc_work_pay_salary_pay_body')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <h3 style="color: red;">THANH TOÁN LƯƠNG</h3>
        </div>
        @if (Session::has('notifySalaryPay'))
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12 qc-color-red" style="padding: 20px;">
                {!! Session::get('notifySalaryPay') !!}
                <?php
                Session::forget('notifySalaryPay');
                ?>
                <br/>
                <a type="button" class="btn btn-sm btn-primary" href="{!! $urlReferer !!}">Đóng</a>
            </div>
        @else
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form id="frm_work_pay_salary_pay" role="form" method="post"
                      action="{!! route('qc.work.pay.pay_salary.pay.post', $dataSalary->salaryId()) !!}">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr>
                                <th colspan="3">
                                    <img style="width: 40px; height: 40px; border: 1px solid #d7d7d7;"
                                         src="{!! $dataStaffSalary->pathAvatar($dataStaffSalary->image())    !!}">
                                    <label>{!! $dataStaffSalary->fullName() !!}</label>
                                </th>
                                <th class="text-right">
                                    <em>Từ: </em>
                                    <span class="qc-color-red qc-font-bold">
                                        {!! date('d-m-Y', strtotime($dataWork->fromDate())) !!}
                                    </span>
                                    <br/>
                                    <em>Đến: </em>
                                    <span class="qc-color-red qc-font-bold">
                                       {!! date('d-m-Y', strtotime($dataWork->toDate())) !!}
                                    </span>
                                </th>
                            </tr>
                            {{--<tr>
                                <th style="width: 30px;"></th>
                                <th colspan="3">
                                    <b class="qc-color-red">Được thanh toán</b>
                                </th>
                            </tr>--}}
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" checked="checked" disabled="true" name="cbSalary">
                                </td>
                                <td>
                                    Tiền lương
                                </td>
                                <td>
                                    <em class="qc-color-grey">Tổng tiền lương sau khi trừ ỨNG và PHẠT và THANH TOÁN</em>
                                </td>
                                <td class="text-right">
                                    <b> {!! $hFunction->currencyFormat($totalSalaryUnpaid) !!}</b>
                                    <input type="hidden" name="txtSalary" value="{!! $totalSalaryUnpaid !!}">
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" checked="checked" disabled="true" name="cbImport">
                                </td>
                                <td>
                                    Mua vật tư
                                </td>
                                <td>
                                    <em class="qc-color-grey">Tiền mua vật tư ĐÃ DUYỆT và chưa được thanh toán</em>
                                </td>
                                <td class="text-right">
                                    <b> {!! $hFunction->currencyFormat($totalMoneyImportUnpaid) !!}</b>
                                    <input type="hidden" name="txtImport" value="{!! $totalMoneyImportUnpaid !!}">
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" checked="checked" disabled="true" name="cbKPI">
                                </td>
                                <td>
                                    Thưởng KPI
                                </td>
                                <td>
                                    <em class="qc-color-grey">Đạt doanh số KPI</em>
                                </td>
                                <td class="text-right">
                                    <b class="showKPIMoney"> {!! $hFunction->currencyFormat($totalKPIMoney) !!}</b>
                                    <input type="hidden" name="txtKPI" value="{!! $totalKPIMoney !!}">
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">

                                </td>
                                <td>
                                    <div class="form-group form-group-sm">
                                        <label style="color: blue;">Tiền cộng thêm (Thưởng...)</label>
                                        <input type="text" class="txtBenefitMoney form-control" name="txtBenefitMoney"
                                               placeholder="Tiền phát sinh"
                                               value="{!! $hFunction->currencyFormat($benefitMoney) !!}"
                                               onkeyup="qc_main.showFormatCurrency(this);">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group form-group-sm">
                                        <label style="color: blue;">Lý do cộng:</label>
                                        <input type="text" class="form-control" name="txtBenefitMoneyDescription"
                                               placeholder="Nhập lý do cộng" value="">
                                    </div>
                                </td>
                                <td class="text-right" style="color: blue;">
                                    <b class="qc_showBenefitMoney"> {!! $hFunction->currencyFormat($benefitMoney) !!}</b>
                                </td>
                            </tr>

                            {{--<tr>
                                <th style="width: 30px;"></th>
                                <th colspan="3">
                                    <b class="qc-color-red">Cần thanh toán</b>
                                </th>
                            </tr>--}}
                            <tr>
                                <td class="text-center"></td>
                                <td>
                                    <div class="form-group form-group-sm">
                                        <label style="color: red;">Tiền Giữ lại:</label>
                                        <input type="text" class="txtKeepMoney form-control" name="txtKeepMoney"
                                               placeholder="Nhập tiền cần giữ"
                                               value="{!! $hFunction->currencyFormat($keepMoney) !!}"
                                               onkeyup="qc_main.showFormatCurrency(this);">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group form-group-sm">
                                        <label style="color: red;">Lý do giữ tiền:</label>
                                        <input type="text" class="form-control" name="txtKeepMoneyDescription"
                                               placeholder="Nhập lý do giữ" value="">
                                    </div>
                                </td>
                                <td class="text-right" style="color: red;">
                                    <b class="qc_showKeepMoney"> {!! $hFunction->currencyFormat($keepMoney) !!}</b>
                                </td>
                            </tr>

                            <tr>
                                <td class="text-right qc-color-red" colspan="3">
                                    TỔNG THANH TOÁN
                                </td>
                                <td class="text-right qc-color-red" style="padding: 0;">
                                    {{--<b class="showTotalMoneyPay"> {!! $hFunction->currencyFormat($totalMoneyAllPay) !!}</b>--}}
                                    <input type="text"
                                           class="qc_salary_showTotalMoneyPay text-right qc-color-red form-control"
                                           name="showTotalMoneyPay" disabled="true"
                                           value="{!! $hFunction->currencyFormat($totalMoneyAllPay) !!}">
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right" colspan="3">
                                    <span>Tài khoản Ngân hàng:</span>&nbsp;
                                    @if(!empty($bankAccount))
                                        Số TK : <span>{!! $bankAccount !!}</span> - <span>{!! $bankName !!}</span>
                                    @else
                                        <em style="color: grey;">Không có</em>
                                    @endif
                                </td>
                                <td style="padding: 0;">
                                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                    <button type="button" class="qc_save btn btn-sm btn-primary" style="width: 100%;">THANH TOÁN</button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>
        @endif

    </div>
    </div>
@endsection
