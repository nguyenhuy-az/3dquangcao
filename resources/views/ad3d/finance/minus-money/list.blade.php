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
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-12 col-lg-12"
                     style="padding-left: 0;padding-right: 0;">

                </div>
            </div>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="qc_ad3d_list_content row"
                 data-href-cancel="{!! route('qc.ad3d.finance.minus-money.cancel') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr>
                            <td colspan="2">
                                <a class="qc-link-green-bold" href="{!! $hrefIndex !!}">
                                    <i class="qc-font-size-16 glyphicon glyphicon-refresh"></i>
                                </a>
                                <label class="qc-font-size-16">PHẠT</label>
                            </td>
                            <td style="padding: 0;">
                                <a class="qc-link-white-bold form-control btn btn-primary"
                                   href="{!! route('qc.ad3d.finance.minus_money.add.get', $companyFilterId) !!}">
                                    <i class="qc-font-size-16 glyphicon glyphicon-plus"></i>
                                    <span style="font-size: 16px;">PHẠT</span>
                                </a>
                            </td>
                            <td style="padding: 0;">
                                <select class="cbCompanyFilter form-control" name="cbCompanyFilter"
                                        style="height: 34px;"
                                        data-href-filter="{!! $hrefIndex !!}">
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
                            </td>
                        </tr>
                        <tr style="background-color: black; color: yellow;">
                            <th style="width: 150px;">NGÀY</th>
                            <th style="width: 200px;">
                                SỐ TIỀN - GHI CHÚ
                                <br/>
                                <b style="color: white;"> {!! $hFunction->currencyFormat($totalMinusMoney)  !!}</b>
                            </th>
                            <th style="width: 150px;">
                                NHÂN VIÊN
                            </th>
                            <th>PHẢN HỒI</th>
                        </tr>
                        <tr>
                            <td style="padding:0 ;">
                                <select class="cbDayFilter col-sx-3 col-sm-3 col-md-3 col-lg-3"
                                        style="padding: 0;height: 34px;" data-href="{!! $hrefIndex !!}">
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
                                $reason = $minusMoney->reason();
                                # ban giao thi cong don hang
                                $orderAllocationId = $minusMoney->orderAllocationId();
                                # quan ly thi cong - quan ly tong
                                $orderConstructionId = $minusMoney->orderConstructionId();
                                # thi cong san pham
                                $workAllocationId = $minusMoney->workAllocationId();
                                # kiem tra do nghe
                                $companyStoreCheckReportId = $minusMoney->companyStoreCheckReportId();
                                $cancelStatus = $minusMoney->checkCancelStatus();
                                if ($cancelStatus) {
                                    $money = 0;
                                } else {
                                    $money = $minusMoney->money();
                                }
                                $dataWork = $minusMoney->work;
                                # thong tin phan
                                $dataMinusMoneyFeedback = $minusMoney->infoMinusMoneyFeedback();
                                $checkMinusMoneyLostToolStatus = false;
                                # thong tin nhan vien
                                if (!empty($dataWork->companyStaffWorkId())) {
                                    $dataStaffMinus = $dataWork->companyStaffWork->staff;
                                } else {
                                    $dataStaffMinus = $dataWork->staff; // phien ban cu
                                }
                                $n_o = $n_o + 1;
                                ?>
                                <tr class="qc_ad3d_list_object @if($n_o%2 == 0) info @endif"
                                    data-object="{!! $minusId !!}">
                                    <td>
                                        <b style="color: blue;">{!! date('d/m/Y', strtotime($minusMoney->dateMinus())) !!}</b>
                                        <br/>
                                        @if($cancelStatus)
                                            <em style="color: grey;">Đã hủy</em>
                                        @else
                                            @if($minusMoney->checkEnableApply())
                                                <em style="color: grey;">Có hiệu lực</em>
                                            @else
                                                <em style="color: grey;">Tạm thời  </em>
                                                {{--chi hien khi khong co phan hoi--}}
                                                @if(!$checkMinusMoneyLostToolStatus)
                                                    <span> | </span>
                                                    <a class="qc_cancel_act qc-link-red">HỦY</a>
                                                @endif
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        <b style="color: red;">
                                            {!! $hFunction->currencyFormat($money) !!}
                                        </b>
                                        <br/>
                                        <em style="color: grey;">
                                            {!! $minusMoney->punishContent->name() !!}
                                        </em>
                                        @if(!$hFunction->checkEmpty($reason))
                                            <br/>
                                            <span style="color: grey;">- {!! $reason !!}</span>
                                        @endif
                                        @if(!$hFunction->checkEmpty($orderAllocationId))
                                            <br/>
                                            <em style="color: grey;">- Đơn hàng:</em>
                                            <a class="qc-link" style="color: blue !important;">
                                                {!! $minusMoney->orderAllocation->orders->name() !!}
                                            </a>
                                        @endif
                                        @if(!$hFunction->checkEmpty($workAllocationId))
                                            <br/>
                                            <em style="color: grey;">- Sản phẩm:</em>
                                            <a style="color: blue;">
                                                {!! $minusMoney->workAllocation->product->productType->name() !!}
                                            </a>
                                            <br/>
                                            <em style="color: grey;">- Đơn hàng:</em>
                                            <a style="color: blue;">
                                                {!! $minusMoney->workAllocation->product->order->name() !!}
                                            </a>
                                        @endif
                                        @if(!$hFunction->checkEmpty($companyStoreCheckReportId))
                                            <br/>
                                            <b style="color: red;">
                                                {!! $minusMoney->companyStoreCheckReport->companyStore->name() !!}
                                            </b>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="media">
                                            <a class="pull-left" href="#">
                                                <img class="media-object"
                                                     style="background-color: white; width: 40px;height: 40px; border: 1px solid #d7d7d7;border-radius: 10px;"
                                                     src="{!! $dataStaffMinus->pathAvatar($dataStaffMinus->image()) !!}">
                                            </a>

                                            <div class="media-body">
                                                <h5 class="media-heading">{!! $dataStaffMinus->lastName() !!}</h5>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {{--co phan hoi--}}
                                        @if($hFunction->checkCount($dataMinusMoneyFeedback))
                                            <?php
                                            $feedbackId = $dataMinusMoneyFeedback->feedbackId();
                                            $feedbackContent = $dataMinusMoneyFeedback->content();
                                            $feedbackImage = $dataMinusMoneyFeedback->image();
                                            ?>
                                            <span>{!! $feedbackContent !!}</span>
                                            @if(!$hFunction->checkEmpty($feedbackImage))
                                                <br/>
                                                <a class="qc_view_image qc-link"
                                                   data-href="{!! route('qc.ad3d.finance.minus-money.view_image.get',$feedbackId) !!}">
                                                    <img style="height: 70px;" alt="..."
                                                         src="{!! $dataMinusMoneyFeedback->pathSmallImage($feedbackImage) !!}">
                                                </a>
                                            @endif
                                            {{--chi duyet phan hoi bao mat do nghe--}}
                                            @if($minusMoney->checkMinusMoneyLostTool())
                                                <?php
                                                $checkMinusMoneyLostToolStatus = true;
                                                ?>
                                                @if(!$dataMinusMoneyFeedback->checkConfirm())
                                                    <br/><br/>
                                                    <a class="qc_minus_money_feedback_confirm_get qc-link-green-bold"
                                                       title="Xác nhận phản hồi"
                                                       data-href="{!! route('qc.ad3d.finance.minus-money.feedback.confirm.get',$feedbackId) !!}">
                                                        XÁC NHẬN
                                                    </a>
                                                @endif
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center" colspan="4">
                                    {!! $hFunction->page($dataMinusMoney) !!}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="text-center" colspan="4">
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
