<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 */
/*
 *dataCompany
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
?>
@extends('components.container.container-6')
@section('qc_container_content')
    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
                <h3>XÁC NHẬN ĐÃ NHẬN TIỀN  </h3>
            </div>
        </div>
        <div class="row">
            <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form id="frmWorkSalaryPayConfirm" class="frmWorkSalaryPayConfirm" role="form" method="post"
                      action="{!! route('qc.work.salary.salary.confirm.post', $salaryId) !!}">
                    <div class="row">
                        <div class="frm_notify qc-color-red text-center col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <tr class="qc-color-red">
                                        <th class="qc-padding-none" colspan="7">Chi tiết thanh toán lương</th>
                                    </tr>
                                    <tr style="background-color: whitesmoke;">
                                        <th class="text-center qc-padding-none">STT</th>
                                        <th class="text-center qc-padding-none">Ngày</th>
                                        <th class="text-center qc-padding-none">Thủ quỹ</th>
                                        <th class="text-right qc-padding-none">Số tiền</th>
                                    </tr>
                                    @if(count($dataSalaryPay) > 0)
                                        @foreach($dataSalaryPay as $salaryPay)
                                            <tr>
                                                <td class="text-center qc-padding-none">
                                                    {!! $n_o_pay = (isset($n_o_pay)) ? $n_o_pay + 1 : 1 !!}
                                                </td>
                                                <td class="text-center qc-padding-none">
                                                    {!!  date('d/m/Y',strtotime($salaryPay->datePay())) !!}
                                                </td>
                                                <td class="text-center qc-padding-none">
                                                    {!! $salaryPay->staff->fullName()  !!}
                                                </td>
                                                <td class="text-right qc-color-red qc-padding-none">
                                                    {!! $hFunction->currencyFormat($salaryPay->money()) !!}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td class="qc-padding-none" colspan="5">
                                                Không có thông tin thanh toán
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="text-center qc-padding-top-20 qc-padding-bot-20  qc-border-none col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <button type="button" class="qc_save btn btn-sm btn-primary">Xác nhận</button>
                            <button type="button" class="qc_container_close btn btn-sm btn-default">Đóng</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
