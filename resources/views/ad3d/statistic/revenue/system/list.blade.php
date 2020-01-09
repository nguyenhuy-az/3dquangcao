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
$hrefIndex = route('qc.ad3d.statistic.revenue.system.get');
//chi
#$totalPaid = $dataAccess['totalPaid'];
#$totalSalaryBeforePay = $dataAccess['totalSalaryBeforePay'];
#$totalSalaryPaid = $dataAccess['totalSalaryPaid'];
//Thu
#$totalOrderPay = $dataAccess['totalOrderPay'];
?>
@extends('ad3d.statistic.revenue.system.index')
@section('qc_ad3d_index_content')
    <div class="row" style="padding-bottom: 50px;">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
             style="padding-top:10px; padding-bottom: 10px; border-bottom: 2px dashed brown; ">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-12 col-lg-12"
                     style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! $hrefIndex !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <label class="qc-font-size-20">THỐNG KÊ DOANH THU</label>
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
            <div class="row">
                <div class="qc-margin-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <tr style="background-color: whitesmoke;">
                                    <th class="text-center" style="padding-top: 0; padding-bottom: 0;">STT</th>
                                    <th style="padding-top: 0; padding-bottom: 0;">Công ty</th>
                                    <th class="text-right" style="padding-top: 0; padding-bottom: 0;">Doanh số</th>
                                    <th class="text-right" style="padding-top: 0; padding-bottom: 0;">Thu</th>
                                    <th class="text-right" style="padding-top: 0; padding-bottom: 0;">Chi</th>
                                    <th class="text-right" style="padding-top: 0; padding-bottom: 0;">
                                        Lợi nhuận <br/>
                                        <em>(Trước thuế)</em>
                                    </th>
                                    <th class="text-right" style="padding-top: 0; padding-bottom: 0;">
                                        Lãi ròng <br/>
                                        <em>(sau thuế)</em>
                                    </th>
                                </tr>
                                @if(count($dataCompany) > 0)
                                    <?php
                                    $sumMoneyOrder = 0;
                                    $sumCollectMoney = 0;
                                    $sumPaymentMoney = 0;
                                    ?>
                                    @foreach($dataCompany as $company)
                                        <?php
                                        $companyId = $company->companyId();
                                        #tong doanh so
                                        $statisticalTotalMoneyOrder = $company->statisticalTotalMoneyOrder($companyId, $statisticDate);
                                        $sumMoneyOrder = $sumMoneyOrder + $statisticalTotalMoneyOrder;

                                        #tong thu
                                        $statisticalTotalCollectMoney = $company->statisticalTotalCollectMoney($companyId, $statisticDate);
                                        $sumCollectMoney = $sumCollectMoney + $statisticalTotalCollectMoney;
                                        #tong chi
                                        $statisticalTotalPaymentMoney = $company->statisticalTotalPaymentMoney($companyId, $statisticDate);
                                        $sumPaymentMoney = $sumPaymentMoney + $statisticalTotalPaymentMoney;
                                        ?>
                                        <tr>
                                            <td class="text-center" style="padding-top: 0; padding-bottom: 0;">
                                                {!! $n_o = (isset($n_o))?$n_o+1: 1 !!}
                                            </td>
                                            <td style="padding-top: 0; padding-bottom: 0;">
                                                <a class="qc-link">
                                                    {!! $company->name() !!}
                                                </a>
                                            </td>
                                            <td class="text-right" style="padding-top: 0; padding-bottom: 0;">
                                                <b style="color: blue;">
                                                    {!! $hFunction->currencyFormat($statisticalTotalMoneyOrder)  !!}
                                                </b>
                                            </td>
                                            <td class="text-right" style="padding-top: 0; padding-bottom: 0;">
                                                <b style="color: blue;">
                                                    {!! $hFunction->currencyFormat($statisticalTotalCollectMoney)  !!}
                                                </b>
                                            </td>
                                            <td class="text-right" style="padding-top: 0; padding-bottom: 0;">
                                                <b style="color: blue;">
                                                    {!! $hFunction->currencyFormat($statisticalTotalPaymentMoney)  !!}
                                                </b>
                                            </td>
                                            <td class="text-right" style="padding-top: 0; padding-bottom: 0;">
                                                <b style="color: red;">
                                                    {!! $hFunction->currencyFormat($statisticalTotalMoneyOrder - $statisticalTotalPaymentMoney)  !!}
                                                </b>
                                            </td>
                                            <td class="text-right" style="padding-top: 0; padding-bottom: 0;">
                                                <b style="color: blue;">
                                                    @if(($statisticalTotalMoneyOrder - $statisticalTotalPaymentMoney) > 0)
                                                        {!! $hFunction->currencyFormat(($statisticalTotalMoneyOrder - $statisticalTotalPaymentMoney)*0.8)  !!}
                                                    @else
                                                        {!! $hFunction->currencyFormat(($statisticalTotalMoneyOrder - $statisticalTotalPaymentMoney))  !!}
                                                    @endif
                                                </b>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr style="color: red;">
                                        <td class="text-right" colspan="2"></td>
                                        <td class="text-right"
                                            style="padding-top: 0; padding-bottom: 0;background-color: #d7d7d7;">
                                            <b>{!! $hFunction->currencyFormat($sumMoneyOrder) !!}</b>
                                        </td>
                                        <td class="text-right"
                                            style="padding-top: 0; padding-bottom: 0;background-color: #d7d7d7;">
                                            <b>{!! $hFunction->currencyFormat($sumCollectMoney) !!}</b>
                                        </td>
                                        <td class="text-right"
                                            style="padding-top: 0; padding-bottom: 0;background-color: #d7d7d7;">
                                            <b>{!! $hFunction->currencyFormat($sumPaymentMoney) !!}</b>
                                        </td>
                                        <td class="text-right"
                                            style="padding-top: 0; padding-bottom: 0;background-color: #d7d7d7;">
                                            <b>{!! $hFunction->currencyFormat($sumCollectMoney - $sumPaymentMoney) !!}</b>
                                        </td>
                                        <td class="text-right"
                                            style="padding-top: 0; padding-bottom: 0;background-color: #d7d7d7;">
                                            <b>
                                            @if(($sumMoneyOrder - $sumPaymentMoney) > 0)
                                                {!! $hFunction->currencyFormat(($sumMoneyOrder - $sumPaymentMoney)*0.8)  !!}
                                            @else
                                                {!! $hFunction->currencyFormat(($sumMoneyOrder - $sumPaymentMoney)) !!}
                                            @endif
                                            </b>

                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td class="text-right qc-padding-none" colspan="7">
                                            <em class="qc-color-red">Không có công ty hoạt động</em>
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td class="text-center qc-padding-none" colspan="7">
                                        <em class="qc-color-red" style="text-decoration:underline;">*Doanh số: </em>
                                        <em class="qc-color-grey"> Tổng tiền của đơn hàng đã nhận </em>
                                        <br/>
                                        <em class="qc-color-red" style="text-decoration:underline;">*Thu: </em>
                                        <em class="qc-color-grey"> Đã Thu từ đơn hàng (thu đủ / cọc / phần cần
                                            lại) </em>
                                        <br/>
                                        <em class="qc-color-red" style="text-decoration:underline;">*Chi: </em>
                                        <em class="qc-color-grey"> Chi hoạt động, thanh toán tiền vật tư, ứng lương,
                                            thanh toán lương </em>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
