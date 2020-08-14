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
?>
@extends('ad3d.finance.salary.payment.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! route('qc.ad3d.finance.salary.payment.get') !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <label class="qc-font-size-20">BẢNG LƯƠNG</label>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <select class="cbCompanyFilter form-control" name="cbCompanyFilter"
                            data-href-filter="{!! route('qc.ad3d.finance.salary.payment.get') !!}">
                        @if($dataStaffLogin->checkRootManage())
                            <option value="0">Tất cả</option>
                        @endif
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
                </div>
            </div>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="qc_ad3d_list_content row"
                 data-href-view="{!! route('qc.ad3d.finance.salary.payment.view.get') !!}"
                 data-href-add="{!! route('qc.ad3d.finance.salary.payment.add.get') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: black;color: yellow;">
                            <th class="text-center" style="width: 20px;">STT</th>
                            <th>Tên</th>
                            <th style="width: 100px;">Tháng -năm</th>
                            <th class="text-right">Tổng lương</th>
                            <th class="text-right">
                                Mua vật tư <br/>
                                <em>(Đã duyệt chưa TT)</em>
                            </th>
                            <th class="text-right">Ứng</th>
                            <th class="text-right">Phạt</th>
                            <th class="text-right">Tổng lãnh</th>
                            <th class="text-right">Đã thanh toán</th>
                            <th class="text-right">Chưa thanh toán</th>
                        </tr>
                        <tr>
                            <td class="text-center"></td>
                            <td style="padding: 0px;">
                                <div class="input-group">
                                    <input type="text" class="textFilterName form-control" name="textFilterName"
                                           placeholder="Tìm theo tên" value="{!! $nameFiler !!}">
                                      <span class="input-group-btn">
                                            <button class="btFilterName btn btn-default" type="button"
                                                    data-href="{!! route('qc.ad3d.finance.salary.payment.get') !!}">Tìm
                                            </button>
                                      </span>
                                </div>
                            </td>
                            <td style="padding: 0;">
                                <select class="cbMonthFilter col-sx-5 col-sm-5 col-md-5 col-lg-5"
                                        style="padding: 0; height: 34px;"
                                        data-href="{!! route('qc.ad3d.finance.salary.payment.get') !!}">
                                    <option value="0" @if((int)$monthFilter == 0) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($m =1;$m<= 12; $m++)
                                        <option value="{!! $m !!}"
                                                @if((int)$monthFilter == $m) selected="selected" @endif>
                                            {!! $m !!}
                                        </option>
                                    @endfor
                                </select>
                                <select class="cbYearFilter col-sx-7 col-sm-7 col-md-7 col-lg-7"
                                        style="padding: 0; height: 34px;"
                                        data-href="{!! route('qc.ad3d.finance.salary.payment.get') !!}">
                                    @for($y =2017;$y<= 2050; $y++)
                                        <option value="{!! $y !!}"
                                                @if($yearFilter == $y) selected="selected" @endif>{!! $y !!}</option>
                                    @endfor
                                </select>
                            </td>
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
                                $salaryPay = $salary->salary();
                                $totalPaid = $salary->totalPaid();
                                $dataWork = $salary->work;

                                $workCompanyId = $dataWork->companyIdOfWork();
                                $workCompanyId = (count($workCompanyId) > 0) ? $workCompanyId[0] : $workCompanyId;
                                //dd($workCompanyId);
                                $totalSalary = $dataWork->totalSalaryBasicOfWorkInMonth($dataWork->workId());
                                //2 tong tien mua vat tu xac nhan chưa thanh toan
                                $fromDate = $dataWork->fromDate();
                                $totalMoneyImportOfStaff = $modelStaff->totalMoneyImportOfStaff($dataWork->companyStaffWork->staff->staffId(), date('Y-m', strtotime($fromDate)), 2);
                                ?>
                                <tr class="qc_ad3d_list_object @if($n_o%2) info @endif" data-object="{!! $salaryId !!}">
                                    <td class="text-center">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td>
                                        @if(!empty($dataWork->companyStaffWorkId()))
                                            {!! $dataWork->companyStaffWork->staff->fullName() !!}
                                        @else
                                            {!! $dataWork->staff->fullName() !!}
                                        @endif
                                    </td>
                                    <td>
                                        <a class="qc_view qc-link-green">
                                            <span>{!! date('m-Y',strtotime($dataWork->fromDate())) !!}</span>
                                            &nbsp;
                                            <i class="glyphicon glyphicon-eye-open"></i>
                                        </a>
                                    </td>
                                    <td class="text-right qc-color-red">
                                        {!! $hFunction->currencyFormat($totalSalary) !!}
                                    </td>
                                    <td class="text-right">
                                        {!! $hFunction->currencyFormat($totalMoneyImportOfStaff) !!}
                                    </td>
                                    <td class="text-right">
                                        {!! $hFunction->currencyFormat($dataWork->totalMoneyBeforePay()) !!}
                                    </td>
                                    <td class="text-right">
                                        {!! $hFunction->currencyFormat($dataWork->totalMoneyMinus()) !!}
                                    </td>
                                    <td class="text-right qc-color-red">
                                        {!! $hFunction->currencyFormat($salary->salary() + $totalMoneyImportOfStaff) !!}
                                    </td>
                                    <td class="text-right">
                                        {!! $hFunction->currencyFormat($totalPaid) !!}
                                    </td>
                                    <td class="text-right qc-color-red">
                                        {!! $hFunction->dotNumber($salaryPay + $totalMoneyImportOfStaff -$totalPaid) !!}
                                    </td>

                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center qc-padding-top-20 qc-padding-bot-20" colspan="10">
                                    {!! $hFunction->page($dataSalary) !!}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="qc-padding-top-20 qc-padding-bot-20 text-center" colspan="10">
                                    <em class="qc-color-red">Không tìm thấy thông tin phù hợp</em>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection
