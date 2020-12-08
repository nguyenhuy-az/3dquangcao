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
            {{--system info--}}
            @include('work.components.finance-statistic', compact('modelStaff'))
            {{-- chi tiêt --}}
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="qc_work_salary_salary_content row">

                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr style="background-color: black; color: yellow;">
                                <th style="width: 120px;">NGÀY THÁNG</th>
                                <th style="width: 130px;">
                                    Chưa thanh toán
                                    <br/>
                                    <em style="color: white;">(Có tính vật tư)</em>
                                </th>
                                <th style="width: 120px;">
                                    Tổng lương nhận
                                    <br/>
                                    <em style="color: white;">(Không tính vật tư)</em>
                                </th>
                                <th style="width: 120px;">Lương cơ bản</th>
                                <th style="width: 120px;">Thưởng</th>
                                <th>Cộng thêm</th>
                                <th>
                                    Mua vật tư
                                    <br/>
                                    <em style="color: white;">(Đã duyệt chưa TT)</em>
                                </th>
                                <th>Phạt</th>
                                <th>Ứng</th>
                                <th>Đã thanh toán</th>
                                <th>Giữ tiền lại</th>
                            </tr>
                            @if($hFunction->checkCount($dataSalary))
                                @foreach($dataSalary as $salary)
                                    <?php
                                    $salaryId = $salary->salaryId();
                                    $benefitMoney = $salary->benefitMoney();
                                    $bonusMoney = $salary->bonusMoney();
                                    $minusMoney = $salary->minusMoney();
                                    $salaryPay = $salary->salary();
                                    # tong tien giu
                                    $totalKeepMoney = $salary->totalKeepMoney();

                                    # tien thuong da ap dung
                                    $totalBonusMoney = $salary->bonusMoney();
                                    $dataSalaryPayInfo = $salary->infoSalaryPay();
                                    # thong tin lam viec
                                    $dataWork = $salary->work;
                                    $dataCompanyStaffWork = $dataWork->companyStaffWork;
                                    if ($hFunction->checkCount($dataCompanyStaffWork)) { # du lieu moi - 1 NV lam nhieu cty
                                        $dataOld = false;
                                        # tong luong co ban
                                        $totalSalaryBasic = $dataWork->totalSalaryBasicOfWorkInMonth($dataWork->workId());
                                        $fromDate = $dataWork->fromDate();
                                        $monthOfFromDate = date('m', strtotime($fromDate));
                                        $yearOfFromDate = date('Y', strtotime($fromDate));
                                        # tong tien mua vat tu da duyet chua thanh toan
                                        $totalMoneyImportOfStaff = $modelStaff->importTotalMoneyHasConfirmNotPay($dataCompanyStaffWork->companyId(), $dataStaff->staffId(), date('Y-m', strtotime($fromDate)));
                                        # luong da thanh toan
                                        $totalPaid = $salary->totalPaid();//totalPayConfirmed();
                                        # luong da ung
                                        $totalMoneyConfirmedBeforePay = $dataWork->totalMoneyConfirmedBeforePay();
                                        # tong tien nhan
                                        $totalSalaryReceive = $totalSalaryBasic + $benefitMoney + $bonusMoney;
                                        # tong can thanh toan
                                        $totalUnpaid = $totalSalaryReceive + $totalMoneyImportOfStaff - $totalMoneyConfirmedBeforePay - $totalKeepMoney - $totalPaid - $minusMoney;
                                    } else {
                                        # du lieu cũ bỏ. 1 NV lam 1 cty
                                        $dataOld = true;
                                    }
                                    ?>
                                    @if($dataOld)
                                        <tr>
                                            <td colspan="11">
                                                <em class="qc-color-red">Dữ liệu cũ --> HỦY</em>
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td>
                                                <b style="color: blue;">
                                                    {!! date('m-Y', strtotime($fromDate)) !!}
                                                </b>
                                                <br/>
                                                <a class="qc-link-green-bold qc-font-size-12"
                                                   href="{!! route('qc.work.salary.salary.detail',$salaryId) !!}">
                                                    CHI TIẾT
                                                </a>
                                            </td>
                                            <td>
                                                <b style="color: red;">
                                                    {!! $hFunction->currencyFormat($totalUnpaid) !!}
                                                </b>
                                                @if(!$salary->checkPaid())
                                                    <br/>
                                                    <em class="qc-color-grey">-Chưa Thanh toán hết</em>
                                                @else
                                                    <br/>
                                                    <i class="glyphicon glyphicon-ok" style="color: green;"></i>
                                                    <em style="color: grey;">Đã Thanh toán</em>
                                                @endif
                                                @if(!$salary->checkPaid())
                                                    @if($salary->salaryPayCheckExistUnConfirm())
                                                        <br/>
                                                        <a class="qc_salary_pay_confirm_get qc-link"
                                                           style="background-color: red; padding: 3px; color: yellow !important;"
                                                           data-href="{!! route('qc.work.salary.salary.confirm.get',$salaryId) !!}">
                                                            Xác nhận đã nhận tiền
                                                        </a>
                                                    @endif
                                                @else
                                                    @if(!$salary->salaryPayCheckExistUnConfirm())
                                                        <br/>
                                                        <i class="glyphicon glyphicon-ok" style="color: green;"></i>
                                                        <em class="qc-color-grey">Đã xác nhận</em>
                                                    @else
                                                        <br/>
                                                        <a class="qc_salary_pay_confirm_get qc-link"
                                                           style="background-color: red; padding: 3px; color: yellow !important;"
                                                           data-href="{!! route('qc.work.salary.salary.confirm.get',$salaryId) !!}">
                                                            Xác nhận đã nhận tiền
                                                        </a>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                <b style="color: blue;">{!! $hFunction->currencyFormat($totalSalaryReceive) !!}</b>
                                            </td>
                                            <td>
                                                <b style="color: red;">{!! $hFunction->currencyFormat($totalSalaryBasic) !!}</b>
                                            </td>
                                            <td>
                                                <b style="color: blue;">
                                                    {!! $hFunction->currencyFormat($bonusMoney) !!}
                                                </b>
                                            </td>
                                            <td class="qc-color-red">
                                                + {!! $hFunction->currencyFormat($benefitMoney) !!}
                                            </td>
                                            <td>
                                                + {!! $hFunction->currencyFormat($totalMoneyImportOfStaff) !!}
                                            </td>
                                            <td>
                                            <span>
                                                - {!! $hFunction->currencyFormat($minusMoney) !!}
                                            </span>
                                                <br/>
                                                <a class="qc-link-green qc-font-12"
                                                   href="{!! route('qc.work.minus_money.get', "$monthOfFromDate/$yearOfFromDate") !!}">
                                                    XEM
                                                </a>
                                            </td>
                                            <td>
                                            <span>
                                                - {!! $hFunction->currencyFormat($totalMoneyConfirmedBeforePay) !!}
                                            </span>
                                                <br/>
                                                <a class="qc-link-green qc-font-12"
                                                   href="{!! route('qc.work.salary.before_pay.get', "$monthOfFromDate/$yearOfFromDate") !!}">
                                                    XEM
                                                </a>
                                            </td>
                                            <td>
                                                - {!! $hFunction->currencyFormat($totalPaid) !!}
                                            </td>

                                            <td class="qc-color-red">
                                            <span>
                                                {!! $hFunction->currencyFormat($totalKeepMoney) !!}
                                            </span>
                                                <br/>
                                                <a class="qc-link-green qc-font-size-12"
                                                   href="{!! route('qc.work.salary.keep_money.get') !!}"
                                                   title="Click xem chi tiết">
                                                    XEM
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="11">
                                        <em class="qc-color-red">Chưa có bảng lương</em>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
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