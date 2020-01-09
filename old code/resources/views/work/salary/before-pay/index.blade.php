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
@extends('work.index')
@section('qc_work_body')
    <div class="row">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <ul class="nav nav-tabs" role="tablist">
                        <li>
                            <a href="{!! route('qc.work.salary.salary.get') !!}">
                                <label>Lương</label>
                            </a>
                        </li>
                        <li class="active">
                            <a href="{!! route('qc.work.salary.before_pay.get') !!}">
                                <label>Ứng lương</label>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            @if(count($dataWork) > 0)
                @if($infoSalaryBasic)
                    <div class="row">
                        <div class="text-right col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <a class="qc_work_before_pay_request_action qc-link-green"
                               data-href="{!! route('qc.work.salary.before_pay.request.get', $workId) !!}">
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
                    <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="row qc-container-table">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <tr style="background-color: whitesmoke;">
                                        <th class="text-center">Hạn mức ứng</th>
                                        <th class="text-center">Số lần ứng</th>
                                        <th class="text-center">Tổng ứng</th>
                                    </tr>
                                    <tr>
                                        <td class="text-center">
                                            @if($dataWork->limitBeforePay()>0)
                                                {!! $hFunction->currencyFormat($dataWork->limitBeforePay()) !!}
                                            @else
                                                <em class="qc-color-red">Lương không đủ ứng</em>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {!! count($dataBeforePay) !!}
                                            @if(count($dataBeforePay) > 2)
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

                    {{-- chi tiêt --}}
                    <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        {{-- Chi tiet ung luong   --}}
                        <div class="row qc-container-table">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <tr class="qc-color-red">
                                        <th colspan="7">Chi tiết ứng lương</th>
                                    </tr>
                                    <tr style="background-color: whitesmoke;">
                                        <th class="text-center">STT</th>
                                        <th class="text-center">Ngày</th>
                                        <th class="text-center">Thủ quỹ</th>
                                        <th class="text-center">Ghú</th>
                                        <th class="text-center">Số tiền</th>
                                    </tr>
                                    @if(count($dataBeforePay) > 0)
                                        @foreach($dataBeforePay as $beforePay)
                                            <tr>
                                                <td class="text-center">
                                                    {!! $n_o = (isset($n_o)) ? $n_o + 1 : 1 !!}
                                                </td>
                                                <td class="text-center">
                                                    {!! date('d-m-Y', strtotime($beforePay->datePay())) !!}
                                                </td>
                                                <td class="text-center">
                                                    {!! $beforePay->staff->fullName() !!}
                                                </td>
                                                <td class="text-center">
                                                    {!! $beforePay->description() !!}
                                                </td>
                                                <td class="text-center">
                                                    {!! $hFunction->currencyFormat($beforePay->money()) !!}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td class="text-center" colspan="5">
                                                Không có thông tin ứng
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>

                        {{-- de xuat ung luong   --}}
                        <div class="row qc-container-table">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <tr class="qc-color-red">
                                        <th colspan="7">Đề xuất ứng lương</th>
                                    </tr>
                                    <tr style="background-color: whitesmoke;">
                                        <th class="text-center">STT</th>
                                        <th class="text-center">Ngày</th>
                                        <th class="text-center">Ghi chú</th>
                                        <th class="text-center">Duyệt</th>
                                        <th class="text-center">Trạng thái nhận</th>
                                        <th class="text-center">Số tiền</th>
                                    </tr>
                                    @if(count($dataBeforePayRequest) > 0)
                                        @foreach($dataBeforePayRequest as $beforePayRequest)
                                            <?php
                                            $request_n_o = (isset($request_n_o)) ? $request_n_o + 1 : 1;
                                            $requestId = $beforePayRequest->requestId();
                                            $moneyConfirm = $beforePayRequest->moneyConfirm();
                                            ?>
                                            <tr>
                                                <td class="text-center">
                                                    {!! $request_n_o = (isset($request_n_o)) ? $request_n_o + 1 : 1 !!}
                                                </td>
                                                <td class="text-center">
                                                    {!! date('d/m/Y', strtotime($beforePayRequest->dateRequest())) !!}
                                                </td>
                                                <td class="text-center">
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
                                            <td class="text-center" colspan="5">
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
                        Tính năng đang bảo trì
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <button type="button" class="btn btn-sm btn-primary" onclick="qc_main.page_back();">
                        Đóng
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
