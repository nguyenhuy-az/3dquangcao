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
            <div class="text-center col-sx-12 col-sm-12 col-md-6 col-lg-6 qc-color-red" style="padding: 20px;">
                {!! Session::get('notifySalaryPay') !!}
                <?php
                Session::forget('notifySalaryPay');
                ?>
                <br/>
                <a type="button" class="btn btn-sm btn-primary" href="{!! $urlReferer !!}">Đóng</a>
            </div>
        @else
            <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6">
                <form id="frm_work_pay_salary_pay" role="form" method="post"
                      action="{!! route('qc.work.pay.pay_salary.pay.post', $dataSalary->salaryId()) !!}">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr>
                                <th>
                                    <div class="media">
                                        <a class="pull-left" href="#">
                                            <img class="media-object"
                                                 style="background-color: white; width: 40px;height: 40px; border: 1px solid #d7d7d7;border-radius: 10px;"
                                                 src="{!! $dataStaffSalary->pathAvatar($dataStaffSalary->image()) !!}">
                                        </a>

                                        <div class="media-body">
                                            <h5 class="media-heading">{!! $dataStaffSalary->fullName() !!}</h5>
                                            <em>Từ: </em>
                                        <span class="qc-color-red qc-font-bold">
                                            {!! date('d-m-Y', strtotime($dataWork->fromDate())) !!}
                                        </span>
                                            <em>Đến: </em>
                                        <span class="qc-color-red qc-font-bold">
                                           {!! date('d-m-Y', strtotime($dataWork->toDate())) !!}
                                        </span>
                                        </div>
                                    </div>
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <b style="color: red;"> {!! $hFunction->currencyFormat($totalSalaryUnpaid) !!}</b>
                                    <input type="hidden" name="txtSalary" value="{!! $totalSalaryUnpaid !!}">
                                    <br/>
                                    <em class="qc-color-grey">Tổng tiền lương sau khi trừ ỨNG và PHẠT và THANH TOÁN</em>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-left">
                                    <b style="color: red;"> {!! $hFunction->currencyFormat($totalMoneyImportUnpaid) !!}</b>
                                    <input type="hidden" name="txtImport" value="{!! $totalMoneyImportUnpaid !!}">
                                    <br/>
                                    <em class="qc-color-grey">- Tiền mua vật tư ĐÃ DUYỆT và chưa được thanh toán</em>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <b class="showKPIMoney"
                                       style="color: red;"> {!! $hFunction->currencyFormat($totalKPIMoney) !!}</b>
                                    <input type="hidden" name="txtKPI" value="{!! $totalKPIMoney !!}">
                                    <br/>
                                    <em class="qc-color-grey">- Thưởng đạt doanh số KPI</em>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-group form-group-sm">
                                        <label style="color: blue;">Tiền cộng thêm (Thưởng...)</label>
                                        <input type="text" class="txtBenefitMoney form-control" name="txtBenefitMoney"
                                               placeholder="Tiền phát sinh"
                                               value="{!! $hFunction->currencyFormat($benefitMoney) !!}"
                                               onkeyup="qc_main.showFormatCurrency(this);">
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label style="color: blue;">Lý do cộng:</label>
                                        <input type="text" class="form-control" name="txtBenefitMoneyDescription"
                                               placeholder="Nhập lý do cộng" value="">
                                    </div>
                                </td>
                                {{--<td class="text-right" style="color: blue;">
                                    <b class="qc_showBenefitMoney"> {!! $hFunction->currencyFormat($benefitMoney) !!}</b>
                                </td>--}}
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-group form-group-sm">
                                        <label style="color: red;">Tiền Giữ lại:</label>
                                        <input type="text" class="txtKeepMoney form-control" name="txtKeepMoney"
                                               placeholder="Nhập tiền cần giữ"
                                               value="{!! $hFunction->currencyFormat($keepMoney) !!}"
                                               onkeyup="qc_main.showFormatCurrency(this);">
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label style="color: red;">Lý do giữ tiền:</label>
                                        <input type="text" class="form-control" name="txtKeepMoneyDescription"
                                               placeholder="Nhập lý do giữ" value="">
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label>Tổng thanh toán:</label>
                                    <input type="text" class="qc_totalMoneyPay qc-color-red form-control"
                                           name="txtTotalMoneyPay" disabled="true"
                                           value="{!! $hFunction->currencyFormat($totalMoneyAllPay) !!}">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span style="color: grey;">Tài khoản Ngân hàng:</span>&nbsp;
                                    @if(!empty($bankAccount))
                                        Số TK : <span>{!! $bankAccount !!}</span> - <span>{!! $bankName !!}</span>
                                    @else
                                        <em>Không có</em>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 0;">
                                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                    <button type="button" class="qc_save btn btn-sm btn-primary" style="width: 100%;">
                                        THANH TOÁN
                                    </button>
                                    {{--<button type="reset" class="btn btn-sm btn-default">NHẬP LẠI</button>--}}
                                </td>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>
        @endif

    </div>
@endsection
