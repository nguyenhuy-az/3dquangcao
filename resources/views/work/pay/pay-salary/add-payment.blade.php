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
$dataStaffSalary = $dataWork->companyStaffWork->staff;
$bankAccount = $dataStaffSalary->bankAccount();
$bankName = $dataStaffSalary->bankName();
# chua thanh toan
$totalSalaryUnpaid = $dataSalary->totalSalaryUnpaid();//$salaryPay - $totalSalaryPaid;

$totalMoneyAllPay = $totalSalaryUnpaid + $totalMoneyImportUnpaid;
?>
@extends('components.container.container-6')
@section('qc_container_content')
    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <h3 style="color: red;">THANH TOÁN LƯƠNG</h3>
        </div>

        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <form id="frm_work_pay_salary_payment_add" role="form" method="post"
                  action="{!! route('qc.work.pay.pay_salary.payment.add.post', $dataSalary->salaryId()) !!}">
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
                                <em class="qc-color-grey">Tiền lương nhận chưa thanh toán</em>
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
                            <td class="qc_notify" style="color: red; font-size: 1.5em;">

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <button type="button" class="qc_save btn btn-sm btn-primary">
                                    THANH TOÁN
                                </button>
                                <button type="button" class="qc_container_close btn btn-sm btn-default">
                                    ĐÓNG
                                </button>
                            </td>
                        </tr>
                    </table>
                </div>
            </form>
        </div>
    </div>
@endsection
