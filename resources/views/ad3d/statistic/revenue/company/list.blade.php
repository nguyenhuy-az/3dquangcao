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

$hrefIndex = route('qc.ad3d.statistic.revenue.company.get');
$companyStatisticId = $dataCompanyStatistic->companyId();

# tong thu tu don hang
$totalMoneyReceiveOfCompany = $dataCompanyStatistic->statisticalTotalCollectMoney($companyStatisticId, $dateFilter);

# tong thu chi
$totalMoneyPayment = $dataCompanyStatistic->statisticalTotalPaymentMoney($companyStatisticId, $dateFilter);
?>
@extends('ad3d.statistic.revenue.company.index')
@section('qc_ad3d_index_content')
    <div class="row" style="padding-bottom: 50px;">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-12 col-lg-12"
                     style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! $hrefIndex !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <label class="qc-font-size-20">THỐNG KÊ THU CHI CỦA THỦ QUỸ</label>
                </div>
            </div>
            {{-- Thống kê tổng --}}
            {{--
            <div class="row" style="margin-top: 10px;">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <td style="padding: 0;">
                                    <select class="cbCompanyFilter form-control" name="cbCompanyFilter"
                                            data-href-filter="{!! $hrefIndex !!}">
                                        @if($hFunction->checkCount($dataCompany))
                                            @foreach($dataCompany as $company)
                                                @if($dataStaffLogin->checkRootManage())
                                                    <option value="{!! $company->companyId() !!}"
                                                            @if($companyStatisticId == $company->companyId()) selected="selected" @endif >{!! $company->name() !!}</option>
                                                @else
                                                    @if($companyFilterId == $company->companyId())
                                                        <option value="{!! $company->companyId() !!}">{!! $company->name() !!}</option>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                </td>
                                <td style="padding: 0;">
                                    <select class="cbDayFilter col-xs-3 col-sm-3 col-md-3 col-lg-3" style="padding: 0; height: 34px;"
                                            data-href="{!! $hrefIndex !!}">
                                        <option value="100" @if((int)$dayFilter == 100) selected="selected" @endif >
                                            Tất cả
                                        </option>
                                        @for($d =1;$d<= 31; $d++)
                                            <option value="{!! $d !!}"
                                                    @if((int)$dayFilter == $d) selected="selected" @endif >
                                                {!! $d !!}
                                            </option>
                                        @endfor
                                    </select>
                                    <select class="cbMonthFilter col-xs-3 col-sm-3 col-md-3 col-lg-3" style="padding: 0; height: 34px;"
                                            data-href="{!! $hrefIndex !!}">
                                        <option value="100" @if((int)$monthFilter == 100) selected="selected" @endif >
                                            Tất cả
                                        </option>
                                        @for($m =1;$m<= 12; $m++)
                                            <option value="{!! $m !!}"
                                                    @if((int)$monthFilter == $m) selected="selected" @endif>
                                                {!! $m !!}
                                            </option>
                                        @endfor
                                    </select>
                                    <select class="cbYearFilter col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding: 0; height: 34px;"
                                            data-href="{!! $hrefIndex !!}">
                                        <option value="100" @if((int)$yearFilter == 100) selected="selected" @endif >
                                            Tất cả
                                        </option>
                                        @for($y =2017;$y<= 2050; $y++)
                                            <option value="{!! $y !!}"
                                                    @if($yearFilter == $y) selected="selected" @endif>
                                                {!! $y !!}
                                            </option>
                                        @endfor
                                    </select>
                                </td>
                            </tr>
                            <tr style="background-color: black; color: yellow;">
                                <th class="text-center">Tổng doanh thu</th>
                                <th class="text-center">Tổng Chi</th>
                            </tr>
                            <tr>
                                <td class="text-center" style="color: red;">
                                    <b style="color: blue;">
                                        {!! $hFunction->currencyFormat($totalMoneyReceiveOfCompany)  !!}
                                    </b>
                                </td>
                                <td class="text-center" style="color: red;">
                                    <b style="color: red;">
                                        {!! $hFunction->currencyFormat($totalMoneyPayment)  !!}
                                    </b>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            --}}
            {{-- Thông tin chi tiết --}}
            <div class="qc_ad3d_list_content row">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr>
                                <td style="padding: 0;" colspan="5">
                                    <select class="cbCompanyFilter form-control" name="cbCompanyFilter"
                                            data-href-filter="{!! $hrefIndex !!}">
                                        @if($hFunction->checkCount($dataCompany))
                                            @foreach($dataCompany as $company)
                                                @if($dataStaffLogin->checkRootManage())
                                                    <option value="{!! $company->companyId() !!}"
                                                            @if($companyStatisticId == $company->companyId()) selected="selected" @endif >{!! $company->name() !!}</option>
                                                @else
                                                    @if($companyStatisticId == $company->companyId())
                                                        <option value="{!! $company->companyId() !!}">{!! $company->name() !!}</option>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                </td>
                                <td style="padding: 0;" colspan="3">
                                    <select class="cbDayFilter col-xs-3 col-sm-3 col-md-3 col-lg-3" style="padding: 0; height: 34px;"
                                            data-href="{!! $hrefIndex !!}">
                                        <option value="100" @if((int)$dayFilter == 100) selected="selected" @endif >
                                            Tất cả
                                        </option>
                                        @for($d =1;$d<= 31; $d++)
                                            <option value="{!! $d !!}"
                                                    @if((int)$dayFilter == $d) selected="selected" @endif >
                                                {!! $d !!}
                                            </option>
                                        @endfor
                                    </select>
                                    <select class="cbMonthFilter col-xs-3 col-sm-3 col-md-3 col-lg-3" style="padding: 0; height: 34px;"
                                            data-href="{!! $hrefIndex !!}">
                                        <option value="100" @if((int)$monthFilter == 100) selected="selected" @endif >
                                            Tất cả
                                        </option>
                                        @for($m =1;$m<= 12; $m++)
                                            <option value="{!! $m !!}"
                                                    @if((int)$monthFilter == $m) selected="selected" @endif>
                                                {!! $m !!}
                                            </option>
                                        @endfor
                                    </select>
                                    <select class="cbYearFilter col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding: 0; height: 34px;"
                                            data-href="{!! $hrefIndex !!}">
                                        <option value="100" @if((int)$yearFilter == 100) selected="selected" @endif >
                                            Tất cả
                                        </option>
                                        @for($y =2017;$y<= 2050; $y++)
                                            <option value="{!! $y !!}"
                                                    @if($yearFilter == $y) selected="selected" @endif>
                                                {!! $y !!}
                                            </option>
                                        @endfor
                                    </select>
                                </td>
                            </tr>
                            <tr style="background-color: black; color: yellow;">
                                <th class="text-center">STT</th>
                                <th>THỦ QUỸ</th>
                                <th class="text-right">THU ĐƠN HÀNG</th>
                                <th class="text-right">NHẬN TIỀN ĐẦU TƯ</th>
                                <th class="text-right">
                                    NỘP CTY
                                </th>
                                <th class="text-right">CHI PHÍ CỐ ĐỊNH</th>
                                <th class="text-right">CHI BIẾN PHÍ</th>
                                <th class="text-right">
                                    TIỀN ĐANG GIỮ
                                </th>
                            </tr>
                            @if($hFunction->checkCount($dataStaffOfCompany))
                                <?php
                                $n_o = 0;
                                ?>
                                @foreach($dataStaffOfCompany as $staff)
                                    <?php
                                    $staffId = $staff->staffId();
                                    #tong thu tu don hang da xac nhan
                                    $statisticalTotalMoneyFromOrder = $staff->totalMoneyReceiveTransferOrderPayConfirmed($staffId, $dateFilter);
                                    #tong thu tu dau tu da xac nhan
                                    $statisticalTotalMoneyFromInvestment = $staff->totalMoneyReceiveTransferInvestmentConfirmed($staffId, $dateFilter);
                                    #tong thu
                                    $totalReceive = $statisticalTotalMoneyFromOrder + $statisticalTotalMoneyFromInvestment;
                                    # tong nop len cty
                                    $totalMoneyTransferForManage = $staff->totalMoneyConfirmedTransferForTreasurerManage($staffId, $dateFilter);
                                    # bien phi
                                    $totalPaidMoneyVariable = $staff->totalPaidMoneyVariable($staffId, $dateFilter);
                                    # phi co dinh
                                    $totalPaidMoneyPermanent = $staff->totalPaidMoneyPermanent($staffId, $dateFilter);
                                    # tong chi
                                    $totalPaid = $totalPaidMoneyVariable + $totalPaidMoneyPermanent + $totalMoneyTransferForManage;
                                    ?>
                                    <tr class="qc_ad3d_list_object @if($n_o%2) info @endif">
                                        <td class="text-center" style="width: 20px;">
                                            {!! $n_o += 1 !!}
                                        </td>
                                        <td>
                                            <div class="media">
                                                <a class="pull-left" href="{!! route('qc.ad3d.statistic.revenue.company.staff.get',"$staffId/$dateFilter") !!}">
                                                    <img class="media-object"
                                                         style="max-width: 40px;height: 40px; border: 1px solid #d7d7d7;border-radius: 10px;"
                                                         src="{!! $staff->pathAvatar($staff->image()) !!}">
                                                </a>
                                                <div class="media-body">
                                                    <h5 class="media-heading">{!! $staff->fullName() !!}</h5>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-right">
                                            {!! $hFunction->currencyFormat($statisticalTotalMoneyFromOrder)  !!}
                                        </td>
                                        <td class="text-right">
                                            {!! $hFunction->currencyFormat($statisticalTotalMoneyFromInvestment)  !!}
                                        </td>
                                        <td class="text-right">
                                            {!! $hFunction->currencyFormat($totalMoneyTransferForManage)  !!}
                                        </td>
                                        <td class="text-right">
                                            {!! $hFunction->currencyFormat($totalPaidMoneyVariable)  !!}
                                        </td>
                                        <td class="text-right">
                                            {!! $hFunction->currencyFormat($totalPaidMoneyPermanent)  !!}
                                        </td>
                                        <td class="text-right">
                                            <b style="color: red;">{!! $hFunction->currencyFormat($totalReceive - $totalPaid)  !!}</b>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-right" colspan="7">
                                        <em class="qc-color-red">Không có công ty hoạt động</em>
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
