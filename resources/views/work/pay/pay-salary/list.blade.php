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
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$loginStaffId = $dataStaff->staffId();

$currentMonth = $hFunction->currentMonth();
?>
@extends('work.pay.pay-salary.index')
@section('qc_work_pay_salary_pay_body')
    <div class="row qc_work_pay_salary_wrap">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <label style="color: blue; font-size: 1.5em;">*** BẢNG LƯƠNG </label>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr>
                                <td class="text-left" style="padding: 0;">
                                    <select class="qc_work_pay_salary_month_filter text-right col-xs-6 col-sm-6 col-md-6 col-lg-6"
                                            style="height: 34px; color: red; padding: 0;"
                                            data-href="{!! route('qc.work.pay.pay_salary.get') !!}">
                                        @for($m = 1; $m <=12; $m++)
                                            <option @if($filterMonth == $m) selected="selected"
                                                    @endif value="{!! $m !!}">
                                                Tháng {!! $m !!}
                                            </option>
                                        @endfor
                                    </select>
                                    <select class="qc_work_pay_salary_year_filter text-right col-xs-6 col-sm-6 col-md-6 col-lg-6"
                                            style=" height: 34px; color: red; padding: 0;"
                                            data-href="{!! route('qc.work.pay.pay_salary.get') !!}">
                                        @for($y = 2017; $y <=2050; $y++)
                                            <option @if($filterYear == $y) selected="selected"
                                                    @endif value="{!! $y !!}">
                                                {!! $y !!}
                                            </option>
                                        @endfor
                                    </select>
                                </td>
                                <td colspan="10"></td>
                            </tr>
                            <tr style="background-color: black; color: yellow;">
                                <th style="width: 200px;">Nhân viên</th>
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
                                <th style="width: 140px;">Tổng lương cơ bản</th>
                                <th>
                                    Thưởng
                                </th>
                                <th>Cộng thêm</th>
                                <th>
                                    Mua vật tư
                                    <br/>
                                    <em style="color: white;">(Đã duyệt chưa TT)</em>
                                </th>
                                <td>
                                    Ứng
                                </td>
                                <td>
                                    Phạt
                                </td>
                                <th>Đã thanh toán</th>
                                <th>
                                    Giữ lại
                                    <br/>
                                    <em style="color: white;">(Lương cơ bản)</em>
                                </th>
                            </tr>
                            @if($hFunction->checkCount($dataSalary))
                                <?php
                                $sumSalary = 0;
                                $sumBonus = 0;
                                $sumBeforePay = 0;
                                $sumMinus = 0;
                                $sumSalaryReceive = 0;
                                $sumSalaryPaid = 0;
                                $sumSalaryUnpaid = 0;
                                $sumSalaryBenefit = 0;
                                $sumMoneyImportOfStaff = 0;
                                $sumKeepMoney = 0;
                                ?>
                                @foreach($dataSalary as $salary)
                                    <?php
                                    $salaryId = $salary->salaryId();
                                    $bonusMoney = $salary->bonusMoney();
                                    # phat
                                    $minusMoney = $salary->minusMoney();
                                    $sumMinus = $sumMinus + $minusMoney;
                                    # cong them
                                    $benefitMoney = $salary->benefitMoney();
                                    $sumSalaryBenefit = $sumSalaryBenefit + $benefitMoney;
                                    $salaryPay = $salary->salary();
                                    $sumSalary = $sumSalary + $salaryPay;
                                    # tien thuong da ap dung
                                    $totalBonusMoney = $salary->bonusMoney();
                                    $sumBonus = $sumBonus + $totalBonusMoney;
                                    # giu tiem trong thang
                                    $totalKeepMoney = $salary->totalKeepMoney();
                                    $sumKeepMoney = $sumKeepMoney + $totalKeepMoney;
                                    # da thanh toan
                                    $totalPaid = $salary->totalPaid();
                                    $sumSalaryPaid = $sumSalaryPaid + $totalPaid;

                                    $dataWork = $salary->work;
                                    $fromDate = $dataWork->fromDate();
                                    $dataCompanyStaffWork = $dataWork->companyStaffWork;
                                    if ($hFunction->checkCount($dataCompanyStaffWork)) { # du lieu moi - 1 NV lam nhieu cty
                                        $dataOld = false;
                                        # tong luong co ban
                                        $totalSalaryBasic = $dataWork->totalSalaryBasicOfWorkInMonth($dataWork->workId());
                                        # thong tin nhan vien
                                        $dataStaffDetail = $dataWork->companyStaffWork->staff;
                                        # tong tien mua vat tu xac nhan chưa thanh toan
                                        $totalMoneyImportOfStaff = $modelStaff->importTotalMoneyHasConfirmNotPay($dataCompanyStaffWork->companyId(), $dataStaffDetail->staffId(), date('Y-m', strtotime($fromDate)));
                                        $sumMoneyImportOfStaff = $sumMoneyImportOfStaff + $totalMoneyImportOfStaff;
                                        # luong da ung
                                        $totalMoneyConfirmedBeforePay = $dataWork->totalMoneyConfirmedBeforePay();
                                        $sumBeforePay = $sumBeforePay + $totalMoneyConfirmedBeforePay;
                                        # tong luong nhan duoc
                                        $totalSalaryReceive = $totalSalaryBasic + $benefitMoney + $bonusMoney;
                                        $sumSalaryReceive = $totalSalaryReceive + $sumSalaryReceive;

                                        # chua thanh toan
                                        $totalUnpaid = $totalSalaryReceive + $totalMoneyImportOfStaff - $totalMoneyConfirmedBeforePay - $totalKeepMoney - $totalPaid - $minusMoney;
                                        $sumSalaryUnpaid = $sumSalaryUnpaid + $totalUnpaid;

                                        $bankAccount = $dataStaffDetail->bankAccount();
                                        $bankName = $dataStaffDetail->bankName();
                                    } else {
                                        $dataOld = true; // du lieu cu
                                    }
                                    $n_o = isset($n_o) ? $n_o + 1 : 1;
                                    ?>
                                    @if($dataOld)
                                        <tr>
                                            <td colspan="11">
                                                <em class="qc-color-red">Dữ liệu cũ 1 người chỉ làm 1 cty - HỦY</em>
                                            </td>
                                        </tr>
                                    @else
                                        <tr class="@if($n_o%2 == 0) info @endif">
                                            <td>
                                                <div class="media">
                                                    <a class="pull-left" href="#">
                                                        <img class="media-object"
                                                             style="background-color: white; width: 40px;height: 40px; border: 1px solid #d7d7d7;border-radius: 10px;"
                                                             src="{!! $dataStaffDetail->pathAvatar($dataStaffDetail->image()) !!}">
                                                    </a>

                                                    <div class="media-body">
                                                        <label class="media-heading">{!! $dataStaffDetail->lastName() !!}</label>
                                                        @if(!empty($bankAccount))
                                                            <br/>
                                                            <em style="color: grey;">Số TK:</em>
                                                            <span>{!! $bankAccount !!}</span>
                                                            <br/>
                                                            <em style="color: grey;">Ngân hàng:</em>
                                                            <span>{!! $bankName !!}</span>
                                                        @else
                                                            <br/>
                                                            <em style="color: grey;">TK ngân hàng:</em>
                                                            <span style="color: grey;">Không có</span>
                                                        @endif

                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <b style="color: red;">{!! $hFunction->currencyFormat($totalUnpaid) !!}</b>
                                                @if($totalUnpaid < 0)
                                                    <br/>
                                                    <b style="background-color: red; color: yellow; padding: 2px;">Âm
                                                        lương</b>
                                                @else
                                                    <br/>
                                                    @if(!$salary->checkPaid())
                                                        <a class="qc-link-green-bold qc-font-size-12"
                                                           href="{!! route('qc.work.pay.pay_salary.pay.get',$salaryId) !!}">
                                                            THANH TOÁN
                                                        </a>
                                                    @else
                                                        <em class="qc-color-grey">Đã Thanh toán</em>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                <b style="color: blue;">{!! $hFunction->currencyFormat($totalSalaryReceive) !!}</b>
                                                <br/>
                                                <a class="qc-link-green"
                                                   href="{!! route('qc.work.salary.salary.detail',$salaryId) !!}">
                                                    - Xem chi tiết
                                                </a>
                                            </td>
                                            <td>
                                                <b style="color: red;">{!! $hFunction->currencyFormat($totalSalaryBasic) !!}</b>
                                            </td>
                                            <td>
                                                <b style="color: blue;">{!! $hFunction->currencyFormat($totalBonusMoney) !!}</b>
                                            </td>
                                            <td>
                                                <b style="color: red;">
                                                    {!! $hFunction->currencyFormat($benefitMoney) !!}
                                                </b>
                                                @if($salary->checkExistBenefit())
                                                    <br/>
                                                    <i class="glyphicon glyphicon-ok" style="color: green;"></i>
                                                    <em style="color: grey;">Đã cộng thêm</em>
                                                @else
                                                    @if(!$salary->checkPaid())
                                                        <br/>
                                                        <a class="qc_salary_benefit_money_get qc-link-green-bold qc-font-size-12"
                                                           data-href="{!! route('qc.work.pay.pay_salary.benefit_add.get',$salaryId) !!}">
                                                            <i class="glyphicon glyphicon-plus"></i>
                                                            THÊM
                                                        </a>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                {!! $hFunction->currencyFormat($totalMoneyImportOfStaff) !!}
                                            </td>
                                            <td>
                                                <b style="color: red;">
                                                    {!! $hFunction->currencyFormat($totalMoneyConfirmedBeforePay) !!}
                                                </b>
                                            </td>
                                            <td>
                                                <b>
                                                    {!! $hFunction->currencyFormat($minusMoney) !!}
                                                </b>
                                            </td>
                                            <td>
                                                <b style="color: green;">
                                                    {!! $hFunction->currencyFormat($totalPaid) !!}
                                                </b>
                                            </td>
                                            <td>
                                                <a style="color: red;">
                                                    {!! $hFunction->currencyFormat($totalKeepMoney) !!}
                                                </a>
                                                @if(!$salary->checkPaid())
                                                    <br/>
                                                    <a class="qc_salay_keep_money_get qc-link-green-bold qc-font-size-12"
                                                       data-href="{!! route('qc.work.pay.pay_salary.pay.get',$salaryId) !!}">
                                                        <i class="glyphicon glyphicon-plus"></i>
                                                        GIỮ LẠI
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                @if(!$dataOld)
                                    <tr>
                                        <td class="qc-color-red" style="background-color: whitesmoke;"></td>
                                        <td class="qc-color-red">
                                            <b style="font-size: 14px; color: red;">
                                                {!! $hFunction->currencyFormat($sumSalaryUnpaid)  !!}
                                            </b>
                                        </td>
                                        <td style="width: 120px;">
                                            <b style="font-size: 14px; color: blue;">
                                                {!! $hFunction->currencyFormat($sumSalaryReceive) !!}
                                            </b>
                                        </td>
                                        <td class="qc-color-red">
                                            <b style="font-size: 14px; color: red;">
                                                {!! $hFunction->currencyFormat($sumSalary)  !!}
                                            </b>
                                        </td>
                                        <td>
                                            <b style="font-size: 14px; color: blue;">
                                                {!! $hFunction->currencyFormat($sumBonus) !!}
                                            </b>
                                        </td>
                                        <td class="qc-color-red">
                                            <b style="font-size: 14px; color: red;">
                                                {!! $hFunction->currencyFormat($sumSalaryBenefit)  !!}
                                            </b>
                                        </td>
                                        <td class="qc-color-red">
                                            <b style="font-size: 14px; color: red;">
                                                {!! $hFunction->currencyFormat($sumMoneyImportOfStaff)  !!}
                                            </b>
                                        </td>
                                        <td class="qc-color-red">
                                            <b style="font-size: 14px; color: red;">
                                                {!! $hFunction->currencyFormat($sumBeforePay)  !!}
                                            </b>
                                        </td>
                                        <td class="qc-color-red">
                                            <b style="font-size: 14px; color: red;">
                                                {!! $hFunction->currencyFormat($sumMinus)  !!}
                                            </b>
                                        </td>
                                        <td class="qc-color-red">
                                            <b>
                                                {!! $hFunction->currencyFormat($sumSalaryPaid)  !!}
                                            </b>
                                        </td>
                                        <td>
                                            <b style="font-size: 14px; color: blue;">
                                                {!! $hFunction->currencyFormat($sumKeepMoney)  !!}
                                            </b>
                                        </td>
                                    </tr>
                                @endif
                            @else
                                <tr>
                                    <td class="text-center" colspan="11">
                                        <em class="qc-color-red">Không có bảng lương</em>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <label style="color: blue; font-size: 1.5em;">*** CHI THANH TOÁN LƯƠNG TRONG THÁNG</label>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr style="background-color: black;color: yellow;">
                                <th style="width: 120px;">Ngày thanh toán</th>
                                <th style="width: 120px;">Số tiền</th>
                                <th>Người nhận</th>
                            </tr>
                            @if($hFunction->checkCount($dataSalaryPay))
                                <?php
                                $n_o = 0;
                                $totalMoneyPaid = 0;
                                ?>
                                @foreach($dataSalaryPay as $salaryPay)
                                    <?php
                                    $payId = $salaryPay->payId();
                                    $money = $salaryPay->money();
                                    $totalMoneyPaid = $totalMoneyPaid + $money;
                                    # trang thai xac nhan
                                    $confirmStatus = $salaryPay->checkConfirmed();
                                    # bang cham cong
                                    $dataWork = $salaryPay->salary->work;
                                    $dataStaffReceive = $dataWork->companyStaffWork->staff;
                                    $n_o = $n_o + 1;
                                    ?>
                                    <tr class="@if($n_o%2 == 0) info @endif">
                                        <td>
                                            <b style="color: blue;">{!! date('d-m-Y',strtotime($salaryPay->datePay()))  !!}</b>
                                            @if(!$confirmStatus)
                                                <br/>
                                                <i class="glyphicon glyphicon-ok qc-font-size-14"
                                                   style="color: red;"></i>
                                                <em>Chưa xác nhận</em>
                                                <br/>
                                                <a class="qc_salary_pay_del qc-link-green-bold qc-font-size-14"
                                                   data-href="{!! route('qc.work.pay.pay_salary.delete.get', $payId) !!}">
                                                    HỦY
                                                </a>
                                            @else
                                                <br/>
                                                <i class="glyphicon glyphicon-ok qc-font-size-14"
                                                   style="color: green;"></i>
                                                <em>Đã xác nhận</em>
                                            @endif
                                        </td>
                                        <td>
                                            <b style="color: red;">{!! $hFunction->currencyFormat($money) !!}</b>
                                            <br/>
                                            <em style="color: grey;">
                                                - Lương {!! date('m-Y', strtotime($dataWork->fromDate())) !!}
                                            </em>
                                        </td>
                                        <td>
                                            <div class="media">
                                                <a class="pull-left" href="#">
                                                    <img class="media-object"
                                                         style="background-color: white; width: 40px;height: 40px; border: 1px solid #d7d7d7;border-radius: 10px;"
                                                         src="{!! $dataStaffReceive->pathAvatar($dataStaffReceive->image()) !!}">
                                                </a>

                                                <div class="media-body">
                                                    <label class="media-heading">{!! $dataStaffReceive->lastName() !!}</label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr style="border-top: 2px solid brown;">
                                    <td></td>
                                    <td>
                                        <b style="color: red;">{!! $hFunction->currencyFormat($totalMoneyPaid) !!}</b>
                                    </td>
                                    <td></td>
                                </tr>
                            @else
                                <tr>
                                    <td class="qc-padding-top-5 qc-padding-bot-5 text-center" colspan="3">
                                        <em class="qc-color-red">Không có thông tin thanh toán</em>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
