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

$statisticDate = $dataAccess['statisticDate'];
$hrefIndex = route('qc.ad3d.statistic.revenue.company.get');
$companyStatisticId = $dataCompanyStatistic->companyId();
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
                    <label class="qc-font-size-20">{!! $dataCompanyStatistic->name() !!}</label>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <select class="cbCompanyFilter" name="cbCompanyFilter" style="margin-top: 5px; height: 25px;"
                            data-href-filter="{!! $hrefIndex !!}">
                        @if(count($dataCompany)> 0)
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
                <div class="qc-padding-none text-right col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <select class="cbDayFilter" style="margin-top: 5px; height: 30px;"
                            data-href="{!! $hrefIndex !!}">
                        <option value="0" @if((int)$dayFilter == 0) selected="selected" @endif >Tất cả
                        </option>
                        @for($i =1;$i<= 31; $i++)
                            <option value="{!! $i !!}"
                                    @if((int)$dayFilter == $i) selected="selected" @endif >{!! $i !!}</option>
                        @endfor
                    </select>
                    /
                    <select class="cbMonthFilter" style="margin-top: 5px; height: 30px;"
                            data-href="{!! $hrefIndex !!}">
                        <option value="0" @if((int)$monthFilter == 0) selected="selected" @endif >Tất cả
                        @for($i =1;$i<= 12; $i++)
                            <option value="{!! $i !!}"
                                    @if((int)$monthFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                        @endfor
                    </select>
                    /
                    <select class="cbYearFilter" style="margin-top: 5px; height: 30px;"
                            data-href="{!! $hrefIndex !!}">
                        <option value="0" @if((int)$yearFilter == 0) selected="selected" @endif >Tất cả
                        @for($i =2017;$i<= 2050; $i++)
                            <option value="{!! $i !!}"
                                    @if($yearFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                        @endfor
                    </select>
                </div>
            </div>
            {{-- nội dung --}}
            <div class="qc_ad3d_list_content row">
                <div class="qc-margin-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row qc-ad3d-table-container">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <tr style="background-color: whitesmoke;">
                                    <th class="text-center">STT</th>
                                    <th>Nhân viên</th>
                                    <th class="text-right">Thu</th>
                                    <th class="text-right">Chi</th>
                                    <th class="text-right">
                                        Lợi nhuận <br/>
                                        <em>(Trước thuế)</em>
                                    </th>
                                    <th class="text-right">
                                        Lãi ròng <br/>
                                        <em>(sau thuế)</em>
                                    </th>
                                </tr>
                                @if(count($dataStaffOfCompany) > 0)
                                    <?php
                                    $n_o = 0;
                                    $sumMoneyOrder = 0;
                                    $sumCollectMoney = 0;
                                    $sumPaymentMoney = 0;
                                    ?>
                                    @foreach($dataStaffOfCompany as $staff)
                                        <?php
                                        $staffId = $staff->staffId();
                                        #tong doanh so
                                        $statisticalTotalMoneyOrder = 0;
                                        $sumMoneyOrder = $sumMoneyOrder + $statisticalTotalMoneyOrder;

                                        #tong thu
                                        $statisticalTotalCollectMoney = $staff->totalReceivedMoney($staffId,$dateFilter);
                                        $sumCollectMoney = $sumCollectMoney + $statisticalTotalCollectMoney;
                                        #tong chi
                                        $statisticalTotalPaymentMoney = $staff->totalPaidMoney($staffId,$dateFilter);
                                        $sumPaymentMoney = $sumPaymentMoney + $statisticalTotalPaymentMoney;
                                        ?>
                                        <tr class="qc_ad3d_list_object @if($n_o%2) info @endif">
                                            <td class="text-center">
                                                {!! $n_o += 1 !!}
                                            </td>
                                            <td>
                                                <a class="qc-link" href="{!! route('qc.ad3d.statistic.revenue.company.staff.get',"$staffId/$dateFilter") !!}">
                                                    {!! $staff->fullName() !!}
                                                </a>
                                            </td>
                                            <td class="text-right">
                                                {!! $hFunction->currencyFormat($statisticalTotalCollectMoney)  !!}
                                            </td>
                                            <td class="text-right">
                                                {!! $hFunction->currencyFormat($statisticalTotalPaymentMoney)  !!}
                                            </td>
                                            <td class="text-right">
                                                {!! $hFunction->currencyFormat($statisticalTotalCollectMoney - $statisticalTotalPaymentMoney)  !!}
                                            </td>
                                            <td class="text-right">
                                                @if(($statisticalTotalCollectMoney - $statisticalTotalPaymentMoney) > 0)
                                                    {!! $hFunction->currencyFormat(($statisticalTotalCollectMoney - $statisticalTotalPaymentMoney)*0.8)  !!}
                                                @else
                                                    {!! $hFunction->currencyFormat(($statisticalTotalCollectMoney - $statisticalTotalPaymentMoney))  !!}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr style="color: red;border-top: 2px solid brown;">
                                        <td class="text-right" style="background-color: #d7d7d7;" colspan="2"></td>
                                        <td class="text-right">
                                            <b>{!! $hFunction->currencyFormat($sumCollectMoney) !!}</b>
                                        </td>
                                        <td class="text-right">
                                            <b>{!! $hFunction->currencyFormat($sumPaymentMoney) !!}</b>
                                        </td>
                                        <td class="text-right">
                                            <b>{!! $hFunction->currencyFormat($sumCollectMoney - $sumPaymentMoney) !!}</b>
                                        </td>
                                        <td class="text-right">
                                            <b>
                                                @if(($sumCollectMoney - $sumPaymentMoney) > 0)
                                                    {!! $hFunction->currencyFormat(($sumCollectMoney - $sumPaymentMoney)*0.8)  !!}
                                                @else
                                                    {!! $hFunction->currencyFormat(($sumCollectMoney - $sumPaymentMoney)) !!}
                                                @endif
                                            </b>

                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td class="text-right" colspan="6">
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
    </div>
@endsection
