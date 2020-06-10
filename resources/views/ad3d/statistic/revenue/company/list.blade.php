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
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
             style="padding-top:10px; padding-bottom: 10px; border-bottom: 2px dashed brown; ">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6"
                     style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! $hrefIndex !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <label class="qc-font-size-20">THỐNG KÊ THU CHI CỦA CÔNG TY</label>
                    <br/>
                    <span>{!! $dataCompanyStatistic->name() !!}</span>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <select class="cbCompanyFilter" name="cbCompanyFilter" style="margin-top: 5px; height: 25px;"
                            data-href-filter="{!! $hrefIndex !!}">
                        @if($hFunction->checkCount($dataCompany))
                            @foreach($dataCompany as $company)
                                <option value="{!! $company->companyId() !!}"
                                        @if($companyStatisticId == $company->companyId()) selected="selected" @endif >{!! $company->name() !!}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            {{-- lọc theo ngày tháng --}}
            <div class="row">
                <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <select class="cbDayFilter" style="margin-top: 5px; height: 30px;" data-href="{!! $hrefIndex !!}">
                        <option value="100" @if((int)$dayFilter == 100) selected="selected" @endif >
                            Tất cả
                        </option>
                        @for($d =1;$d<= 31; $d++)
                            <option value="{!! $d !!}" @if((int)$dayFilter == $d) selected="selected" @endif >
                                {!! $d !!}
                            </option>
                        @endfor
                    </select>
                    <span>/</span>
                    <select class="cbMonthFilter" style="margin-top: 5px; height: 30px;" data-href="{!! $hrefIndex !!}">
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
                    <span>/</span>
                    <select class="cbYearFilter" style="margin-top: 5px; height: 30px;" data-href="{!! $hrefIndex !!}">
                        <option value="100" @if((int)$yearFilter == 100) selected="selected" @endif >
                            Tất cả
                        </option>
                        @for($y =2017;$y<= 2050; $y++)
                            <option value="{!! $y !!}" @if($yearFilter == $y) selected="selected" @endif>
                                {!! $y !!}
                            </option>
                        @endfor
                    </select>
                </div>
            </div>
            {{-- Thống kê tổng --}}
            <div class="row" style="margin-top: 10px;">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr style="background-color: black; color: yellow;">
                                <th class="text-center">Tổng doanh thu</th>
                                <th class="text-center">Tổng Chi</th>
                                <th class="text-center">Lợi nhân</th>
                                <th class="text-center">Lãi ròng</th>
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
                                <td class="text-center" style="color: red;">
                                    <b style="color: green;">
                                        {!! $hFunction->currencyFormat($totalMoneyReceiveOfCompany-$totalMoneyPayment)  !!}
                                    </b>
                                </td>
                                <td class="text-center" style="color: red;">
                                    <b style="color: green;">
                                        {!! $hFunction->currencyFormat(($totalMoneyReceiveOfCompany-$totalMoneyPayment)*0.8)  !!}
                                    </b>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            {{-- Thông tin chi tiết --}}
            <div class="qc_ad3d_list_content row">
                <div class="qc-margin-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr style="background-color: black; color: white;">
                                <th class="text-center">STT</th>
                                <th>Nhân viên</th>
                                <th class="text-right">Thu từ đơn hàng</th>
                                <th class="text-right">Thu từ đầu tư</th>
                                <th class="text-right">Chi Cố định</th>
                                <th class="text-right">Chi biến phí</th>
                                <th class="text-right">
                                    Lợi nhuận <br/>
                                    <em>(Trước thuế)</em>
                                </th>
                                <th class="text-right">
                                    Lãi ròng <br/>
                                    <em>(sau thuế)</em>
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
                                    # bien phi
                                    $totalPaidMoneyVariable = $staff->totalPaidMoneyVariable($staffId, $dateFilter);
                                    # phi co dinh
                                    $totalPaidMoneyPermanent = $staff->totalPaidMoneyPermanent($staffId, $dateFilter);
                                    # tong chi
                                    $totalPaid = $totalPaidMoneyVariable + $totalPaidMoneyPermanent;
                                    ?>
                                    <tr class="qc_ad3d_list_object @if($n_o%2) info @endif">
                                        <td class="text-center" style="width: 20px;">
                                            {!! $n_o += 1 !!}
                                        </td>
                                        <td>
                                            <a class="qc-link"
                                               href="{!! route('qc.ad3d.statistic.revenue.company.staff.get',"$staffId/$dateFilter") !!}">
                                                {!! $staff->fullName() !!}
                                            </a>
                                        </td>
                                        <td class="text-right">
                                            {!! $hFunction->currencyFormat($statisticalTotalMoneyFromOrder)  !!}
                                        </td>
                                        <td class="text-right">
                                            {!! $hFunction->currencyFormat($statisticalTotalMoneyFromInvestment)  !!}
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
                                        <td class="text-right">
                                            @if(($totalReceive - $totalPaid) > 0)
                                                {!! $hFunction->currencyFormat(($totalReceive - $totalPaid)*0.8)  !!}
                                            @else
                                                {!! $hFunction->currencyFormat(($totalReceive - $totalPaid))  !!}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-right" colspan="8">
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
