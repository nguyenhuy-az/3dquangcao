<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:32 PM
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$dataStaffLogin = $modelStaff->loginStaffInfo();
$companyLoginId = $dataStaffLogin->companyId(); # id cua cong nhan vien dang dang nhap
$hrefIndex = route('qc.ad3d.finance.salary.payment.get');
// dd($dataSalary);
?>
@extends('ad3d.finance.salary.payment.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-12 col-lg-12"
                     style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! $hrefIndex !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <label class="qc-font-size-20">BẢNG LƯƠNG</label>
                </div>
            </div>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="qc_ad3d_list_content row"
                 data-href-view="{!! route('qc.ad3d.finance.salary.payment.view.get') !!}"
                 data-href-add="{!! route('qc.ad3d.finance.salary.payment.add.get') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr>
                            <td colspan="2" style="padding: 0;">
                                <select class="cbMonthFilter col-sx-6 col-sm-6 col-md-6 col-lg-6"
                                        style="padding: 0; height: 34px; color: red;" data-href="{!! $hrefIndex !!}">
                                    <option value="0" @if((int)$monthFilter == 0) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($m =1;$m<= 12; $m++)
                                        <option value="{!! $m !!}"
                                                @if((int)$monthFilter == $m) selected="selected" @endif>
                                            Tháng {!! $m !!}
                                        </option>
                                    @endfor
                                </select>
                                <select class="cbYearFilter col-sx-6 col-sm-6 col-md-6 col-lg-6"
                                        style="padding: 0; height: 34px; color: red;" data-href="{!! $hrefIndex !!}">
                                    @for($y =2017;$y<= 2050; $y++)
                                        <option value="{!! $y !!}"
                                                @if($yearFilter == $y) selected="selected" @endif>{!! $y !!}</option>
                                    @endfor
                                </select>
                            </td>
                            <td colspan="3" style="padding: 0;">
                                <select class="cbCompanyFilter form-control" name="cbCompanyFilter"
                                        data-href-filter="{!! $hrefIndex !!}">
                                    @if($hFunction->checkCount($dataCompany))
                                        @foreach($dataCompany as $company)
                                            @if($dataStaffLogin->checkRootManage())
                                                <option value="{!! $company->companyId() !!}"
                                                        @if($companyFilterId == $company->companyId()) selected="selected" @endif >{!! $company->name() !!}</option>
                                            @else
                                                @if($companyFilterId == $company->companyId())
                                                    <option value="{!! $company->companyId() !!}">{!! $company->name() !!}</option>
                                                @endif
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </td>
                            <td colspan="6"></td>
                        </tr>
                        <tr style="background-color: black;color: yellow;">
                            <th style="width: 150px;">Nhân viên</th>
                            <th>
                                Chưa thanh toán
                                <br/>
                                <em style="color: white;">(Có tiền vật tư)</em>
                            </th>
                            <th>
                                Tổng lương lãnh
                                <br/>
                                <em style="color: white;">(Không tiền vật tư)</em>
                            </th>
                            <th>
                                Tổng lương cơ bản
                            </th>
                            <th>Thưởng</th>
                            <th>Công thêm</th>
                            <th>
                                Mua vật tư
                                <br/>
                                <em style="color: white;">(Đã duyệt chưa TT)</em>
                            </th>
                            <th>Ứng</th>
                            <th>Phạt</th>
                            <th>Đã thanh toán</th>
                            <th>Tiền giữ</th>
                        </tr>
                        <tr>
                            <td style="padding: 0 !important;">
                                <select class="cbStaffFilter form-control" data-href="{!! $hrefIndex !!}"
                                        name="cbStaffFilter">
                                    <option value="0" @if($staffFilterId == 0) selected="selected" @endif>
                                        Tất cả
                                    </option>
                                    @if($hFunction->checkCount($dataStaffFilter))
                                        @foreach($dataStaffFilter as $staff)
                                            <option @if($staff->staffId() == $staffFilterId) selected="selected"
                                                    @endif  value="{!! $staff->staffId() !!}">{!! $staff->lastName() !!}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        @if($hFunction->checkCount($dataSalary))
                            <?php
                            $perPage = $dataSalary->perPage();
                            $currentPage = $dataSalary->currentPage();
                            $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number

                            ?>
                            @foreach($dataSalary as $salary)
                                <?php
                                $salaryId = $salary->salaryId();
                                $benefitMoney = $salary->benefitMoney();
                                $bonusMoney = $salary->bonusMoney();
                                $minusMoney = $salary->minusMoney();
                                $salaryPay = $salary->salary();
                                $workSalaryId = $salary->workSalaryId();
                                # tien da thanh toan
                                $totalPaid = $salary->totalPaid();
                                # tong tien giu
                                $totalKeepMoney = $salary->totalKeepMoney();
                                # thong tin lam viec
                                $dataWork = $salary->work;
                                $fromDate = $dataWork->fromDate();
                                $dataCompanyStaffWork = $dataWork->companyStaffWork;
                                if ($hFunction->checkCount($dataCompanyStaffWork)) { # du lieu moi - 1 NV lam nhieu cty
                                    $dataOld = false;
                                    # thong tin nhan vien
                                    $dataStaffSalary = $dataCompanyStaffWork->staff;
                                    # tong luong co ban
                                    $totalSalaryBasic = $dataWork->totalSalaryBasicOfWorkInMonth($dataWork->workId());
                                    # tien thuong
                                    $totalBonusMoney = $dataWork->totalMoneyBonusApplied();
                                    # tong tien mua vat tu xac nhan chưa thanh toan
                                    $totalMoneyImportOfStaff = $modelStaff->importTotalMoneyHasConfirmNotPay($dataCompanyStaffWork->companyId(), $dataStaffSalary->staffId(), date('Y-m', strtotime($fromDate)));

                                    # luong da ung
                                    $totalMoneyConfirmedBeforePay = $dataWork->totalMoneyConfirmedBeforePay();
                                    # tong tien nhan
                                    $totalSalaryReceive = $totalSalaryBasic + $benefitMoney + $bonusMoney;
                                    # tong can thanh toan
                                    $totalUnpaid = $totalSalaryReceive + $totalMoneyImportOfStaff - $totalMoneyConfirmedBeforePay - $totalKeepMoney - $totalPaid - $minusMoney;

                                    $image = $dataStaffSalary->image();
                                    $src = $dataStaffSalary->pathAvatar($image);
                                } else {
                                    $dataOld = true;
                                }
                                $n_o += 1;
                                ?>
                                @if($dataOld)
                                    <tr>
                                        <td colspan="11">
                                            <em class="qc-color-red">DỮ LIỆU CŨ - HỦY</em>
                                        </td>
                                    </tr>
                                @else
                                    <tr class="qc_ad3d_list_object @if($n_o%2) info @endif"
                                        data-object="{!! $salaryId !!}">
                                        <td style="padding: 0;">
                                            <div class="media" style="margin: 3px;">
                                                <a class="pull-left" href="#">
                                                    <img class="media-object" src="{!! $src !!}"
                                                         style="background-color: white; width: 40px;height: 40px; border: 1px solid #d7d7d7;border-radius: 10px;">
                                                </a>

                                                <div class="media-body">
                                                    <h5 class="media-heading">{!! $dataStaffSalary->lastName() !!}</h5>
                                                    <em style="color: blue;">{!! date('m-Y',strtotime($dataWork->fromDate())) !!}</em>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <b style="color: red;">
                                                {!! $hFunction->currencyFormat($totalUnpaid) !!}
                                            </b>
                                        </td>
                                        <td>
                                            <b style="color: blue;">
                                                {!! $hFunction->currencyFormat($totalSalaryReceive) !!}
                                            </b>
                                        </td>
                                        <td>
                                            <b style="color: red;">
                                                {!! $hFunction->currencyFormat($totalSalaryBasic) !!}
                                            </b>
                                        </td>
                                        <td>
                                            <b style="color: blue;">{!! $hFunction->currencyFormat($totalBonusMoney) !!}</b>
                                        </td>
                                        <td>
                                            <b style="color: red;">
                                                {!! $hFunction->currencyFormat($benefitMoney) !!}
                                            </b>
                                        </td>
                                        <td>
                                            {!! $hFunction->currencyFormat($totalMoneyImportOfStaff) !!}
                                        </td>
                                        <td>
                                            <b style="color: blue;">
                                                {!! $hFunction->currencyFormat($dataWork->totalMoneyBeforePay()) !!}
                                            </b>
                                        </td>
                                        <td>
                                            <b>
                                                {!! $hFunction->currencyFormat($minusMoney) !!}
                                            </b>
                                        </td>
                                        <td>
                                            <b style="color: red;">
                                                {!! $hFunction->currencyFormat($totalPaid) !!}
                                            </b>
                                            <br/>
                                            <a class="qc_view qc-link-green-bold">
                                                - XEM
                                            </a>
                                        </td>
                                        <td>
                                            <b style="color: blue;">
                                                {!! $hFunction->currencyFormat($totalKeepMoney) !!}
                                            </b>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            <tr>
                                <td colspan="11"
                                    style="border-left: 5px solid brown; padding-top: 0; padding-bottom: 0;">
                                    {!! $hFunction->page($dataSalary) !!}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="11">
                                    <em class="qc-color-red">Không tìm thấy thông tin</em>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection
