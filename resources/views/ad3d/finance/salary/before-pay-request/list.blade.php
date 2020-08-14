<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:32 PM
 *
 * modelStaff
 * dataAccess
 * dataTimekeeping
 * dateFilter
 *
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$dataStaffLogin = $modelStaff->loginStaffInfo();
$companyLoginId = $dataStaffLogin->companyId(); # id cua cong nhan vien dang dang nhap
$totalNewSalaryBeforePayRequest = $modelStaff->totalNewSalaryBeforePayRequest();
?>
@extends('ad3d.finance.salary.before-pay-request.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! route('qc.ad3d.salary.before_pay_request.get') !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <label class="qc-font-size-20">XIN ỨNG LƯƠNG</label>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <select class="cbCompanyFilter form-control" name="cbCompanyFilter" style="height: 34px;"
                            data-href-filter="{!! route('qc.ad3d.salary.before_pay_request.get') !!}">
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
            @if($totalNewSalaryBeforePayRequest > 0)
                <div class="row">
                    <div class="text-left col-xs-12 col-sm-12 col-md-12 col-lg-12"
                         style="padding-left: 0;padding-right: 0;">
                        <span class="qc-color-red">[{!! $totalNewSalaryBeforePayRequest !!}]</span>

                    </div>
                </div>
            @endif
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="qc_ad3d_list_content row"
                 data-href-confirm="{!! route('qc.ad3d.salary.before_pay_request.confirm.get') !!}"
                 data-href-transfer="{!! route('qc.ad3d.salary.before_pay_request.transfer.get') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: black; color: yellow;">
                            <th class="text-center">STT</th>
                            <th>Tên</th>
                            <th class="text-center">Ngày</th>
                            <th class="text-right">Xin ứng</th>
                            <th class="text-right">Đươc duyệt</th>
                            <th class="text-center">Ghi chú</th>
                            <th class="text-right"></th>
                        </tr>
                        @if($hFunction->checkCount($dataSalaryBeforePayRequest ))
                            <?php
                            $perPage = $dataSalaryBeforePayRequest->perPage();
                            $currentPage = $dataSalaryBeforePayRequest->currentPage();
                            $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                            ?>
                            @foreach($dataSalaryBeforePayRequest as $salaryBeforePayRequest)
                                <?php
                                $licenseId = $salaryBeforePayRequest->requestId();
                                $moneyRequest = $salaryBeforePayRequest->moneyRequest();
                                $moneyConfirm = $salaryBeforePayRequest->moneyConfirm();
                                $confirmNote = $salaryBeforePayRequest->confirmNote();
                                $confirmDate = $salaryBeforePayRequest->confirmDate();
                                $confirmStatus = $salaryBeforePayRequest->checkConfirmStatus();
                                $requestDate = $salaryBeforePayRequest->dateRequest();
                                $dataWork = $salaryBeforePayRequest->work;
                                $workCompanyId = $dataWork->companyIdOfWork()[0];
                                ?>
                                <tr class="qc_ad3d_list_object @if($n_o%2) info @endif" data-object="{!! $licenseId !!}">
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
                                    <td class="text-center">
                                        {!! date('d-m-Y', strtotime($requestDate)) !!}
                                    </td>
                                    <td class="text-right">
                                        {!! $hFunction->currencyFormat($moneyRequest) !!}
                                    </td>
                                    <td class="text-right">
                                        @if($moneyConfirm > 0)
                                            {!! $hFunction->currencyFormat($moneyConfirm) !!}
                                        @else
                                            <span>---</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($confirmStatus && !empty($confirmNote))
                                            <em>{!! $confirmNote !!}</em>
                                        @else
                                            <span>---</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if(!$confirmStatus)
                                            @if($workCompanyId == $companyLoginId)
                                                <a class="qc_confirm qc-link">Xác nhận</a>
                                            @else
                                                <em class="qc-color-grey">Chỉ Xác nhận của cty mình</em>
                                            @endif
                                        @else
                                            @if($salaryBeforePayRequest->checkAgreeStatus())
                                                @if(!$salaryBeforePayRequest->checkTransferStatus())
                                                    <em class="qc-color-grey">Đồng ý</em>
                                                    @if($workCompanyId == $companyLoginId)
                                                        <span>&nbsp;|&nbsp;</span>
                                                        <a class="qc_transfer qc-link-green">
                                                            Chuyển tiền
                                                        </a>
                                                    @else
                                                        <span>&nbsp;|&nbsp;</span>
                                                        <em class="qc-color-grey">Chỉ chuyển tiền của cty mình</em>
                                                    @endif

                                                @else
                                                    <em class="qc-color-grey">Đã chuyển</em>
                                                @endif
                                            @else
                                                <span class="qc-color-grey">Không đồng ý</span>
                                            @endif
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center qc-padding-top-20 qc-padding-bot-20" colspan="7">
                                    {!! $hFunction->page($dataSalaryBeforePayRequest) !!}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="qc-padding-top-5 qc-padding-bot-5 text-center" colspan="7">
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
