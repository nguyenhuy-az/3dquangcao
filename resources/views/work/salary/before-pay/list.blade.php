<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 5/15/2018
 * Time: 10:12 AM
 *
 * dataStaff
 */
$hFunction = new Hfunction();
//$currentMonth = $hFunction = f
if (count($dataWork) > 0) {
    $workId = $dataWork->workId();
    $dataBeforePay = $dataWork->infoBeforePayOfWork();
    $dataBeforePayRequest = $dataWork->infoBeforePayRequestOfWork();
    $companyStaffWorkId = $dataWork->companyStaffWorkId();
    $infoSalaryBasic = true;
    if (!empty($companyStaffWorkId)) {
        $dataStaffWorkSalary = $modelStaffWorkSalary->infoActivityOfWork($companyStaffWorkId);
        if (count($dataStaffWorkSalary) == 0) $infoSalaryBasic = false;
    } else {
        # truong hop phien ban cu chua cap nhat
        $staffId = $this->staffId($workId);
        $dataStaffWorkSalary = $modelCompanyStaffWork->staffWorkSalaryActivityOfStaff($staffId);
        if (count($dataStaffWorkSalary) == 0) $infoSalaryBasic = false;
    }
}
?>
@extends('work.salary.before-pay.index')
@section('qc_work_salary_before_pay_body')
    <div class="qc_work_salary_before_pay_wrap row">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-right col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <a class="qc_work_before_pay_request_action qc-link-red-bold"
                       data-href="{!! route('qc.work.salary.before_pay.request.get') !!}">
                        <em>+Đề suất Ứng</em>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="text-right qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <select class="qc_work_salary_before_pay_month" style="height: 25px;"
                            data-href="{!! route('qc.work.salary.before_pay.get') !!}">
                        @for($i = 1; $i <=12; $i++)
                            <option @if($monthFilter == $i) selected="selected" @endif>
                                {!! $i !!}
                            </option>
                        @endfor
                    </select>
                    <span>/</span>
                    <select class="qc_work_salary_before_pay_year" style="height: 25px;"
                            data-href="{!! route('qc.work.salary.before_pay.get') !!}">
                        @for($i = 2017; $i <=2050; $i++)
                            <option @if($yearFilter == $i) selected="selected" @endif>
                                {!! $i !!}
                            </option>
                        @endfor
                    </select>
                </div>
            </div>
            @if($hFunction->checkCount($dataWork))
                @if($infoSalaryBasic)
                    {{-- HAN MUC UNG LUONG   --}}
                    <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="row qc-container-table">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered" style="table-layout: fixed;">
                                    <tr style="background-color: whitesmoke;">
                                        <th class="text-center">Hạn mức ứng</th>
                                        <th class="text-center">Số lần ứng</th>
                                        <th class="text-center">Tổng ứng</th>
                                    </tr>
                                    <tr>
                                        <td class="text-center qc-color-red">
                                            @if($dataWork->limitBeforePay()>0)
                                                {!! $hFunction->currencyFormat($dataWork->limitBeforePay()) !!}
                                            @else
                                                <em>Lương không đủ ứng</em>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {!! count($dataBeforePay) !!}
                                            @if($hFunction->getCountFromData($dataBeforePay) > 2)
                                                <em style="color: brown">Vượt số lần ứng</em>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {!! $hFunction->currencyFormat($dataWork->totalMoneyBeforePay()) !!}
                                        </td>

                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- CHI TIET UNG LUONG --}}
                    <div class="row">
                        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <i class="qc-font-size-14 glyphicon glyphicon-th-list"></i>
                            <label class="qc-color-red">CHI TIẾT ỨNG LƯƠNG</label>
                        </div>
                    </div>
                    <div class="row ">
                        <div class="qc-container-table col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <tr style="background-color: whitesmoke;">
                                        <th class="text-center" style="width: 20px;">STT</th>
                                        <th>Ngày</th>
                                        <th>Thủ quỹ</th>
                                        <th>Ghú</th>
                                        <th class="text-center">Xác nhận</th>
                                        <th class="text-right">Số tiền</th>
                                    </tr>
                                    @if($hFunction->checkCount($dataBeforePay))
                                        <?php
                                        $totalMoney = 0;
                                        ?>
                                        @foreach($dataBeforePay as $beforePay)
                                            <?php
                                            $payId = $beforePay->payId();
                                            $money = $beforePay->money();
                                            $totalMoney = $totalMoney + $money;
                                            ?>
                                            <tr>
                                                <td class="text-center">
                                                    {!! $n_o = (isset($n_o)) ? $n_o + 1 : 1 !!}
                                                </td>
                                                <td>
                                                    {!! $hFunction->convertDateDMYFromDatetime($beforePay->datePay()) !!}
                                                </td>
                                                <td>
                                                    {!! $beforePay->staff->fullName() !!}
                                                </td>
                                                <td>
                                                    <em class="qc-color-grey">{!! $beforePay->description() !!}</em>
                                                </td>
                                                <td class="text-center">
                                                    @if(!$beforePay->checkConfirm())
                                                        <a class="qc_confirm_receive_money qc-link-red" data-money="{!! $money !!}"
                                                           data-href="{!! route('qc.work.salary.before_pay.confirm.get',$payId) !!}">
                                                            Xác nhận
                                                        </a>
                                                    @else
                                                        <em class="qc-color-grey">Đã xác nhận</em>
                                                    @endif
                                                </td>
                                                <td class="text-right">
                                                    {!! $hFunction->currencyFormat($money) !!}
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="5"></td>
                                            <td class="text-right">
                                                <b class="qc-color-red">
                                                    {!! $hFunction->currencyFormat($totalMoney) !!}
                                                </b>
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td class="text-center" colspan="6">
                                                Không có thông tin ứng
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>

                    </div>
                    {{-- DE XUAT UNG LUONG   --}}
                    <div class="row">
                        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <i class="qc-font-size-14 glyphicon glyphicon-question-sign"></i>
                            <label class="qc-color-red">ĐỀ XUÂT ỨNG LƯƠNG</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="qc-container-table col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <tr style="background-color: whitesmoke;">
                                        <th class="text-center" style="width: 20px;">STT</th>
                                        <th>Ngày</th>
                                        <th>Ghi chú</th>
                                        <th class="text-center">Duyệt</th>
                                        <th class="text-center">Trạng thái nhận</th>
                                        <th class="text-center">Số tiền</th>
                                    </tr>
                                    @if($hFunction->checkCount($dataBeforePayRequest))
                                        @foreach($dataBeforePayRequest as $beforePayRequest)
                                            <?php
                                            $requestId = $beforePayRequest->requestId();
                                            $moneyConfirm = $beforePayRequest->moneyConfirm();
                                            ?>
                                            <tr>
                                                <td class="text-center">
                                                    {!! $request_n_o = (isset($request_n_o)) ? $request_n_o + 1 : 1 !!}
                                                </td>
                                                <td>
                                                    {!! $hFunction->convertDateDMYFromDatetime($beforePayRequest->dateRequest()) !!}
                                                </td>
                                                <td>
                                                    {!! $beforePayRequest->confirmNote() !!}
                                                </td>
                                                <td class="text-center">
                                                    <em class="qc-color-grey">Được duyệt:</em>
                                                    <b>{!! $hFunction->currencyFormat($beforePayRequest->moneyConfirm()) !!}</b>
                                                </td>
                                                <td class="text-center">
                                                    @if($beforePayRequest->checkConfirmStatus())
                                                        @if($beforePayRequest->checkAgreeStatus())
                                                            @if($beforePayRequest->checkTransferStatus())
                                                                <em class="qc-color-grey">Đã nhận</em>
                                                            @else
                                                                <em class="qc-color-grey">Chưa nhận</em>
                                                            @endif
                                                        @else
                                                            <em class="qc-color-red">Không được đồng ý</em>
                                                        @endif
                                                    @else
                                                        <em>Đang chờ duyệt</em>
                                                        <span> &nbsp;|&nbsp; </span>
                                                        <a class="qc_salary_before_pay_request_cancel qc-link-red"
                                                           data-href="{!! route('qc.work.salary.before_pay.request.delete',$requestId) !!}">Hủy</a>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    {!! $hFunction->currencyFormat($beforePayRequest->moneyRequest()) !!}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td class="text-center" colspan="6">
                                                Không có đè xuất ứng
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <span class="qc-color-red">Chưa có bảng lương</span>
                        </div>
                    </div>
                @endif
            @else
                <div class="row">
                    <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <b class="qc-color-red">Không có thông tin ứng</b>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
