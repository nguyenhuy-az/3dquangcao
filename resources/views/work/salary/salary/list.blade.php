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
?>
@extends('work.salary.salary.index')
@section('qc_work_salary_salary_body')
    <div class="row">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            {{--<div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <em>Tên:</em>
                        <span class="qc-font-bold">{!! $dataStaff->fullName() !!}</span>
                    </div>
                    <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <em>Mã NV:</em>
                        <span class="qc-font-bold">{!! $dataStaff->nameCode() !!}</span>
                    </div>
                </div>
            </div>--}}
            {{--system info--}}
            @include('work.components.finance-statistic', compact('modelStaff'))

            {{-- chi tiêt --}}
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="qc_work_salary_salary_content row">
                    @if($hFunction->checkCount($dataSalary))
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <tr style="background-color: whitesmoke;">
                                    <th class="text-center" style="width: 20px;">STT</th>
                                    <th class="text-center">Từ ngày</th>
                                    <th class="text-center">Đến ngày</th>
                                    <th class="text-right">Tổng lương</th>
                                    <th class="text-right">
                                        Mua vật tư
                                        <br/>
                                        <em>(Đã duyệt chưa TT)</em>
                                    </th>
                                    <th class="text-right">Cộng thêm</th>
                                    <th class="text-right">Ứng</th>
                                    <th class="text-right">Phạt</th>
                                    <th class="text-right">Đã thanh toán</th>
                                    <th class="text-right">Còn lại</th>
                                    <th class="text-center">TT thanh toán</th>
                                    <th class="text-right"></th>

                                </tr>
                                @foreach($dataSalary as $salary)
                                    <?php
                                    $salaryId = $salary->salaryId();
                                    $salaryBenefit = $salary->benefitMoney();
                                    $salaryPay = $salary->salary();
                                    $totalPaid = $salary->totalPaid();
                                    $dataSalaryPayInfo = $salary->infoSalaryPay();
                                    $dataWork = $salary->work;
                                    $totalSalary = $dataWork->totalSalaryBasicOfWorkInMonth($dataWork->workId());
                                    $fromDate = $salary->work->fromDate();
                                    $monthOfFromDate = date('m', strtotime($fromDate));
                                    $yearOfFromDate = date('Y', strtotime($fromDate));
                                    // 2 = tong tien mua vat tu da duyet chua thanh toan
                                    $totalMoneyImportOfStaff = $modelStaff->totalMoneyImportOfStaff($dataStaff->staffId(), date('Y-m', strtotime($fromDate)), 2);
                                    ?>
                                    <tr>
                                        <td class="text-center">
                                            {!! $n_o = (isset($n_o)) ? $n_o + 1 : 1 !!}
                                        </td>
                                        <td class="text-center">
                                            {!! date('d/m/Y', strtotime($fromDate)) !!}
                                        </td>
                                        <td class="text-center">
                                            {!! date('d/m/Y', strtotime($salary->work->toDate())) !!}
                                        </td>
                                        <td class="text-right qc-color-red">
                                            {!! $hFunction->currencyFormat($totalSalary) !!}
                                        </td>
                                        <td class="text-right qc-color-red">
                                            + {!! $hFunction->currencyFormat($totalMoneyImportOfStaff) !!}
                                        </td>
                                        <td class="text-right qc-color-red">
                                            + {!! $hFunction->currencyFormat($salaryBenefit) !!}
                                        </td>
                                        <td class="text-right">
                                            <a href="{!! route('qc.work.salary.before_pay.get', "$monthOfFromDate/$yearOfFromDate") !!}">
                                                - {!! $hFunction->currencyFormat($dataWork->totalMoneyBeforePay()) !!}
                                            </a>
                                        </td>
                                        <td class="text-right">
                                            <a href="{!! route('qc.work.minus_money.get', "$monthOfFromDate/$yearOfFromDate") !!}">
                                                - {!! $hFunction->currencyFormat($dataWork->totalMoneyMinus()) !!}
                                            </a>
                                        </td>
                                        <td class="text-right">
                                            - {!! $hFunction->currencyFormat($totalPaid) !!}
                                        </td>
                                        <td class="text-right qc-color-red">
                                            {!! $hFunction->currencyFormat($salaryPay + $totalMoneyImportOfStaff + $salaryBenefit -$totalPaid) !!}
                                        </td>
                                        <td class="text-center">
                                            @if(!$salary->checkPaid())
                                                @if($salary->salaryPayCheckExistUnConfirm())
                                                    <a class="qc_salary_pay_confirm_get qc-link-red"
                                                       data-href="{!! route('qc.work.salary.salary.confirm.get',$salaryId) !!}">
                                                        Xác nhận đã nhận tiền
                                                    </a>
                                                @else
                                                    <em class="qc-color-grey">Chưa Thanh toán hết</em>
                                                @endif
                                            @else
                                                <span>Đã Thanh toán |</span>
                                                @if(!$salary->salaryPayCheckExistUnConfirm())
                                                    <em class="qc-color-green">Đã xác nhận</em>
                                                @else
                                                    <a class="qc_salary_pay_confirm_get qc-link-red"
                                                       data-href="{!! route('qc.work.salary.salary.confirm.get',$salaryId) !!}">
                                                        Xác nhận đã nhận tiền
                                                    </a>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            <a class="qc_work_salary_view"
                                               href="{!! route('qc.work.salary.salary.detail',$salaryId) !!}">
                                                Chi tiết lương
                                            </a>
                                        </td>

                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    @else
                        <div class="qc_ad3d_list_object qc-ad3d-list-object row">
                            <div class="qc-padding-top-5 qc-padding-bot-5 text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <em class="qc-color-red">Chưa có bảng lương</em>
                            </div>
                        </div>
                    @endif
                </div>
            </div>


            <div class="row">
                <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <a href="{!! route('qc.work.home') !!}">
                        <button type="button" class="qc_ad3d_container_close btn btn-sm btn-primary">
                            Đóng
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
