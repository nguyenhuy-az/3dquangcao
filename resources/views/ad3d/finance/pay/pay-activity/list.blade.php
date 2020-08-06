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
$hrefIndex = route('qc.ad3d.finance.pay_activity.get');
$dataStaffLogin = $modelStaff->loginStaffInfo();
$companyLoginId = $dataStaffLogin->companyId(); # id cua cong nhan vien dang dang nhap
?>
@extends('ad3d.finance.pay.pay-activity.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
             style="margin-bottom: 10px; padding-top : 10px;padding-bottom: 10px; border-bottom: 2px dashed black;">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! $hrefIndex !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <i class="qc-font-size-20 glyphicon glyphicon-list-alt"></i>
                    <label class="qc-font-size-20">CHI HOẠT ĐỘNG</label>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">

                    <select class="cbCompanyFilter" name="cbCompanyFilter" style="margin-top: 5px; height: 25px;"
                            data-href-filter="{!! $hrefIndex !!}">
                        {{--@if($dataStaffLogin->checkRootManage())
                            <option value="0">Tất cả</option>
                        @endif--}}
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
            <div class="qc_ad3d_list_content qc-ad3d-table-container row"
                 data-href-view="{!! route('qc.ad3d.finance.pay_activity.view.get') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: black; color:yellow;">
                            <th class="text-center" style="width: 20px;">STT</th>
                            <th style="width: 170px;">Ngày</th>
                            <th>Danh mục chi</th>
                            <th style="width: 400px;">Ghi chú chi</th>
                            <th>Loại chi phí</th>
                            <th>Người chi</th>
                            <th>Người duyệt</th>
                            <th>Ghi chú duyệt</th>
                            <th>Xác nhận</th>
                            <th class="text-right">Số tiền</th>
                        </tr>
                        <tr>
                            <td class="text-center"></td>
                            <td style="padding: 0 !important;">
                                <select class="cbDayFilter" style="height: 30px;"
                                        data-href="{!! $hrefIndex !!}">
                                    <option value="0" @if((int)$dayFilter == 0) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($d =1;$d<= 31; $d++)
                                        <option value="{!! $d !!}"
                                                @if((int)$dayFilter == $d) selected="selected" @endif >
                                            {!! $d !!}
                                        </option>
                                    @endfor
                                </select>
                                <select class="cbMonthFilter" style="height: 30px;"
                                        data-href="{!! $hrefIndex !!}">
                                    <option value="0" @if($monthFilter == 0) selected="selected" @endif>
                                        Tất cả
                                    </option>
                                    @for($m =1;$m<= 12; $m++)
                                        <option value="{!! $m !!}"
                                                @if((int)$monthFilter == $m) selected="selected" @endif>
                                            {!! $m !!}
                                        </option>
                                    @endfor
                                </select>
                                <select class="cbYearFilter" style="height: 30px;"
                                        data-href="{!! $hrefIndex !!}">
                                    @for($y =2017;$y<= 2050; $y++)
                                        <option value="{!! $y !!}"
                                                @if($yearFilter == $y) selected="selected" @endif>
                                            {!! $y !!}
                                        </option>
                                    @endfor
                                </select>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="padding: 0 !important;">
                                <select class="cbStaffFilterId form-control" data-href="{!! $hrefIndex !!}">
                                    <option value="0" @if($staffFilterId == 0) selected="selected" @endif>
                                        Tất cả
                                    </option>
                                    @if($hFunction->checkCount($dataStaff))
                                        @foreach($dataStaff as $staff)
                                            <option @if($staff->staffId() == $staffFilterId) selected="selected"
                                                    @endif  value="{!! $staff->staffId() !!}">{!! $staff->lastName() !!}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </td>
                            <td></td>

                            <td class="text-center"></td>
                            <td style="padding: 0 !important;">
                                <select class="cbConfirmStatusFilter form-control" name="cbConfirmStatusFilter"
                                        data-href="{!! $hrefIndex !!}">
                                    <option value="3" @if($confirmStatusFilter == 3) selected="selected" @endif>Tất cả
                                    </option>
                                    <option value="0" @if($confirmStatusFilter == 0) selected="selected" @endif>Chưa xác
                                        nhận
                                    </option>
                                    <option value="1" @if($confirmStatusFilter == 1) selected="selected" @endif>Đã xác
                                        nhận
                                    </option>
                                </select>
                            </td>
                            <td class="text-right">
                                <b class="qc-color-red">{!! $hFunction->currencyFormat($totalMoneyPayActivity)  !!}</b>
                            </td>
                        </tr>
                        @if($hFunction->checkCount($dataPayActivityDetail))
                            <?php $n_o = 0; ?>
                            @foreach($dataPayActivityDetail as $payActivityDetail)
                                <?php
                                $payId = $payActivityDetail->payId();
                                $money = $payActivityDetail->money();
                                $payDate = $payActivityDetail->payDate();
                                $confirmStatus = $payActivityDetail->checkConfirm();
                                $payCompanyId = $payActivityDetail->companyId();
                                ?>
                                <tr class="qc_ad3d_list_object qc-ad3d-list-object @if($n_o%2) info @endif"
                                    data-object="{!! $payId !!}">
                                    <td class="text-center">
                                        {!! $n_o = $n_o+1 !!}
                                    </td>
                                    <td>
                                        {!! date('d/m/Y',strtotime($payDate))  !!}
                                    </td>
                                    <td>
                                        {!! $payActivityDetail->payActivityList->name()  !!}
                                    </td>
                                    <td>
                                        @if(!empty($payActivityDetail->note()))
                                            {!! $payActivityDetail->note()  !!}
                                        @else
                                            <em class="qc-color-grey">---</em>
                                        @endif
                                    </td>
                                    <td style="color: grey;">
                                        {!! $payActivityDetail->payActivityList->typeLabel() !!}
                                    </td>
                                    <td style="color: grey;">
                                        {!! $payActivityDetail->staff->fullName()  !!}
                                    </td>
                                    <td>
                                        @if($confirmStatus)
                                            {!! $payActivityDetail->confirmStaff->fullName()  !!}
                                        @else
                                            <em>---</em>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($payActivityDetail->confirmNote()))
                                            {!! $payActivityDetail->confirmNote()  !!}
                                        @else
                                            <em class="qc-color-grey">---</em>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$confirmStatus)
                                            @if($payCompanyId == $companyLoginId)
                                                <a class="qc_confirm_get qc-link-green-bold"
                                                   data-href="{!! route('qc.ad3d.finance.pay_activity.confirm.get', $payId) !!}">Duyệt</a>
                                            @else
                                                <em class="qc-color-grey">Chỉ được duyệt của cty mình</em>
                                            @endif
                                        @else
                                            @if($payActivityDetail->checkInvalid())
                                                <em class="qc-color-grey">Đã được duyệt</em>
                                            @else
                                                <em class="qc-color-grey">Không được duyệt</em>
                                            @endif

                                        @endif
                                    </td>
                                    <td class="text-right" style="color: blue;">
                                        {!! $hFunction->currencyFormat($money)  !!}
                                    </td>

                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center qc-padding-top-20 qc-padding-bot-20" colspan="10">
                                    {!! $hFunction->page($dataPayActivityDetail) !!}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="text-center" colspan="10">
                                    <em class="qc-color-red">Không có thông chi</em>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
