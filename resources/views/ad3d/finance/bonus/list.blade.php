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
$hrefIndex = route('qc.ad3d.finance.bonus.get');
?>
@extends('ad3d.finance.bonus.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! $hrefIndex !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <label class="qc-font-size-20">THÔNG TIN THƯỞNG</label>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <select class="cbCompanyFilter form-control" name="cbCompanyFilter" style="height: 34px;"
                            data-href-filter="{!! $hrefIndex !!}">
                        {{--@if($dataStaffLogin->checkRootManage())
                            <option value="1000">Tất cả</option>
                        @endif--}}
                        @if($hFunction->checkCount($dataCompany))
                            @foreach($dataCompany as $company)
                                @if($dataStaffLogin->checkRootManage())
                                    <option value="{!! $company->companyId() !!}"
                                            @if($companyFilterId == $company->companyId()) selected="selected" @endif >
                                        {!! $company->name() !!}
                                    </option>
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
                 data-href-cancel="{!! route('qc.ad3d.finance.bonus.cancel') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: black; color: yellow;">
                            <th class="text-center" style="width: 20px;">STT</th>
                            <th>Tên</th>
                            <th style="width: 150px;">Ngày</th>
                            <th>Nguyên nhân</th>
                            <th>Ghi chú</th>
                            <th class="text-center">Áp dụng</th>
                            <th class="text-right">Thành tiền</th>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center" style="padding: 0px;">
                                <div class="input-group">
                                    <input type="text" class="textFilterName form-control" name="textFilterName"
                                           placeholder="Tìm theo tên" value="{!! $nameFiler !!}">
                                      <span class="input-group-btn">
                                            <button class="btFilterName btn btn-default" type="button"
                                                    data-href="{!! $hrefIndex !!}">
                                                <i class="glyphicon glyphicon-search"></i>
                                            </button>
                                      </span>
                                </div>
                            </td>
                            <td style="padding:0 ;">
                                <select class="cbDayFilter col-sx-3 col-sm-3 col-md-3 col-lg-3"
                                        style="padding: 0; height: 34px;" data-href="{!! $hrefIndex !!}">
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
                                <select class="cbMonthFilter col-sx-3 col-sm-3 col-md-3 col-lg-3"
                                        style="padding: 0;height: 34px;" data-href="{!! $hrefIndex !!}">
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
                                <select class="cbYearFilter col-sx-6 col-sm-6 col-md-6 col-lg-6"
                                        style="padding: 0;height: 34px;" data-href="{!! $hrefIndex !!}">
                                    <option value="100" @if((int)$yearFilter == 100) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($y =2017;$y<= 2050; $y++)
                                        <option value="{!! $y !!}" @if($yearFilter == $y) selected="selected" @endif>
                                            {!! $y !!}
                                        </option>
                                    @endfor
                                </select>
                            </td>
                            <td class="text-center" style="padding: 0px;">
                            </td>
                            <td class="text-right"></td>
                            <td class="text-center"></td>
                            <td class="text-right">
                                <b style="color: red"> {!! $hFunction->currencyFormat($totalBonusMoney)  !!}</b>
                            </td>
                        </tr>
                        @if($hFunction->checkCount($dataBonus))
                            <?php
                            $perPage = $dataBonus->perPage();
                            $currentPage = $dataBonus->currentPage();
                            $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                            ?>
                            @foreach($dataBonus as $bonus)
                                <?php
                                $bonusId = $bonus->bonusId();
                                $orderAllocationId = $bonus->orderAllocationId();
                                $orderConstructionId = $bonus->orderConstructionId();
                                $orderPayId = $bonus->orderPayId();
                                $note = $bonus->note();
                                $cancelStatus = $bonus->checkCancelStatus();
                                if ($cancelStatus) {
                                    $money = 0;
                                } else {
                                    $money = $bonus->money();
                                }
                                $dataWork = $bonus->work;
                                # thong tin nhan vien
                                if (!empty($dataWork->companyStaffWorkId())) {
                                    $dataStaffBonus = $dataWork->companyStaffWork->staff;
                                } else {
                                    $dataStaffBonus = $dataWork->staff; // phien ban cu
                                }
                                ?>
                                <tr class="qc_ad3d_list_object @if($n_o%2) info @endif" data-object="{!! $bonusId !!}">
                                    <td class="text-center">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td style="padding: 0;">
                                        <img style="max-width: 40px;height: 40px; border: 1px solid #d7d7d7;"
                                             src="{!! $dataStaffBonus->pathAvatar($dataStaffBonus->image()) !!}">
                                        {!! $dataStaffBonus->fullName() !!}

                                    </td>
                                    <td>
                                        {!! date('d/m/Y', strtotime($bonus->bonusDate())) !!}
                                    </td>
                                    <td>
                                        {!! $bonus->note() !!}
                                    </td>
                                    <td>
                                        @if(!$hFunction->checkEmpty($orderAllocationId))
                                            <em>Đơn hàng:</em>
                                            <b style="color: red;">{!! $bonus->orderAllocation->orders->name() !!}</b>
                                        @endif
                                        @if(!$hFunction->checkEmpty($orderConstructionId))
                                            <em>Đơn hàng:</em>
                                            <b style="color: red;">{!! $bonus->orderConstruction->name() !!}</b>
                                        @endif
                                        @if(!$hFunction->checkEmpty($orderPayId))
                                            <em>Đơn hàng:</em>
                                            <b style="color: red;">{!! $bonus->orderPay->order->name() !!}</b>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($cancelStatus)
                                            <em style="color: grey;">Đã hủy</em>
                                        @else
                                            @if($bonus->checkEnableApply())
                                                <em>Có hiệu lực</em>
                                            @else
                                                <span>Tạm thời</span>
                                            @endif
                                            {{--<br/>
                                            <a class="qc_cancel_act qc-link-red">Hủy</a>--}}
                                        @endif
                                    </td>
                                    <td class="text-right" style="color: red;">
                                        {!! $hFunction->currencyFormat($money) !!}
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center" colspan="7">
                                    {!! $hFunction->page($dataBonus) !!}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="text-center" colspan="7">
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
