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
$hrefIndex = route('qc.ad3d.finance.minus-money.get');
?>
@extends('ad3d.finance.minus-money.index')
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
                    <label class="qc-font-size-20">PHẠT</label>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <select class="cbCompanyFilter" name="cbCompanyFilter" style="margin-top: 5px; height: 25px;"
                            data-href-filter="{!! $hrefIndex !!}">
                        @if($dataStaffLogin->checkRootManage())
                            <option value="1000">Tất cả</option>
                        @endif
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
            <div class="row">
                <div class="text-right col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <a class="btn btn-sm btn-primary"
                       href="{!! route('qc.ad3d.finance.minus-money.add.get',$companyFilterId) !!}">
                        +
                    </a>
                </div>
            </div>
            <div class="qc_ad3d_list_content row"
                 data-href-cancel="{!! route('qc.ad3d.finance.minus-money.cancel') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: whitesmoke;">
                            <th class="text-center" style="width: 20px;">STT</th>
                            <th>Tên</th>
                            <th class="text-center">Ngày</th>
                            <th class="text-center">Nguyên nhân</th>
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
                            <td class="text-center" style="padding:0 ;">
                                <select class="cbDayFilter" style="height: 30px;" data-href="{!! $hrefIndex !!}">
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
                                <span>/</span>
                                <select class="cbMonthFilter" style="height: 30px;" data-href="{!! $hrefIndex !!}">
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
                                <select class="cbYearFilter" style="height: 30px;" data-href="{!! $hrefIndex !!}">
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
                                <select class="form-control cbPunishContentFilter" data-href="{!! $hrefIndex !!}">
                                    <option value="0" @if($punishContentFilterId == 0) selected="selected" @endif>
                                        Tất cả
                                    </option>
                                    @if($hFunction->checkCount($dataPunishContent))
                                        @foreach($dataPunishContent as $punishContent)
                                            <option value="{!! $punishContent->punishId() !!}"
                                                    @if($punishContent->punishId() == $punishContentFilterId) selected="selected" @endif>
                                                {!! $punishContent->name() !!}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </td>
                            <td class="text-right"></td>
                            <td class="text-center"></td>
                            <td class="text-right">
                                <b> {!! $hFunction->currencyFormat($totalMinusMoney)  !!}</b>
                            </td>
                        </tr>
                        @if($hFunction->checkCount($dataMinusMoney))
                            <?php
                            $perPage = $dataMinusMoney->perPage();
                            $currentPage = $dataMinusMoney->currentPage();
                            $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                            ?>
                            @foreach($dataMinusMoney as $minusMoney)
                                <?php
                                $minusId = $minusMoney->minusId();
                                $orderAllocationId = $minusMoney->orderAllocationId();
                                $reason = $minusMoney->reason();
                                $cancelStatus = $minusMoney->checkCancelStatus();
                                if ($cancelStatus) {
                                    $money = 0;
                                } else {
                                    $money = $minusMoney->money();
                                }
                                $dataWork = $minusMoney->work;
                                ?>
                                <tr class="qc_ad3d_list_object" data-object="{!! $minusId !!}">
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
                                        {!! date('d/m/Y', strtotime($minusMoney->dateMinus())) !!}
                                    </td>
                                    <td>
                                        {!! $minusMoney->punishContent->name() !!}
                                    </td>
                                    <td>
                                        {{--{!! $minusMoney->reason() !!}--}}
                                        @if(!$hFunction->checkEmpty($orderAllocationId))
                                            <em>Đơn hàng:</em>
                                            <b>{!! $minusMoney->orderAllocation->orders->name() !!}</b>
                                            <br/>
                                            <em>Ghi chú báo cáo:</em>
                                            <b>{!! $minusMoney->orderAllocation->noted() !!}</b>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($cancelStatus)
                                            <em style="color: grey;">Đã hủy</em>
                                        @else
                                            @if($minusMoney->checkEnableApply())
                                                <em>Có hiệu lực</em>
                                            @else
                                                <span>Tạm thời</span>
                                            @endif
                                            <br/>
                                            <a class="qc_cancel_act qc-link-red">Hủy</a>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        {!! $hFunction->currencyFormat($money) !!}
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center" colspan="7">
                                    {!! $hFunction->page($dataMinusMoney) !!}
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
