<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 *
 * dataStaff
 */

$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$dataCompanyStaffWork = $dataWork->companyStaffWork;
$fromDate = $dataWork->fromDate();
//$dateFilter = date('Y-m', strtotime($fromDate));
$companyStaffWorkId = $dataCompanyStaffWork->workId();
$dataStaff = $dataCompanyStaffWork->staff;
$statisticStaffId = $dataStaff->staffId();
$dataCompany = $dataCompanyStaffWork->company;
# thong tin thuong
$dataMinusMoney = $dataWork->getInfoHasApplyMinusMoney();
?>
@extends('ad3d.system.staff.index')
@section('qc_ad3d_index_content')
    <div class="qc_ad3d_sys_staff_statistical_wrap qc-padding-bot-30 row">
        <div class="row" style="margin-bottom: 20px;">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <a class="qc-link-white-bold btn btn-sm btn-primary" onclick="qc_main.page_back_go();">
                    <i class="glyphicon glyphicon-backward"></i> Về trang trước
                </a>
            </div>
        </div>
        <div class="row" style="margin-bottom: 10px;">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <div class="media">
                    <a class="pull-left">
                        <img class="media-object"
                             style="background-color: white; width: 60px;height: 60px; border: 1px solid #d7d7d7;border-radius: 10px;"
                             src="{!! $dataStaff->pathAvatar($dataStaff->image()) !!}">
                    </a>

                    <div class="media-body">
                        <h5 class="media-heading">{!! $dataStaff->fullName() !!}</h5>
                        <em style="color: grey;">{!! $dataCompany->name() !!}</em>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <h3 style="color: red;">{!! date('m-Y', strtotime($fromDate)) !!}</h3>
            </div>
        </div>
        {{--Ngay di lam--}}
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <label style="color: red; font-size: 1.5em;">
                    THỐNG KÊ PHẠT - {!! $hFunction->getCount($dataMinusMoney) !!}
                </label>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        @if($hFunction->checkCount($dataMinusMoney))
                            <?php
                            $totalMoney = 0;
                            ?>
                            @foreach($dataMinusMoney as $minusMoney)
                                <?php
                                $minusId = $minusMoney->minusId();
                                $dateMinus = $minusMoney->dateMinus();
                                $reasonMinus = $minusMoney->reason();
                                $reasonImage = $minusMoney->reasonImage();
                                # ban giao thi cong don hang
                                $orderAllocationId = $minusMoney->orderAllocationId();
                                # quan ly thi cong - quan ly tong
                                $orderConstructionId = $minusMoney->orderConstructionId();
                                # thi cong san pham
                                $workAllocationId = $minusMoney->workAllocationId();
                                $reason = $minusMoney->reason();
                                $cancelStatus = $minusMoney->checkCancelStatus();
                                $money = $minusMoney->money();
                                if (!$cancelStatus) {
                                    $totalMoney = $totalMoney + $money;
                                }
                                # thong tin phan
                                $dataMinusMoneyFeedback = $minusMoney->infoMinusMoneyFeedback();
                                $n_o = (isset($n_o)) ? $n_o + 1 : 1;
                                ?>
                                <tr @if($n_o%2) class="info" @endif>
                                    <td>
                                        <b>{!! $n_o !!}).</b>
                                        <b style="@if(!$cancelStatus) color: red; @endif">
                                            {!! $hFunction->currencyFormat($money) !!}
                                        </b>
                                        <br/>&emsp;
                                        <em style="color: blue;">{!! date('d-m-Y', strtotime($minusMoney->dateMinus())) !!}</em>
                                        <br/>&emsp;
                                        <em style="color: grey;">
                                            - {!! $minusMoney->punishContent->name() !!}
                                        </em>
                                        @if(!$hFunction->checkEmpty($reasonMinus))
                                            <br/>&emsp;
                                            <em style="color: grey;">** {!! $reasonMinus !!}</em>
                                        @endif
                                        @if(!$hFunction->checkEmpty($reasonImage))
                                            <br/>&emsp;
                                            <a class="qc-link">
                                                <img style="max-width: 200px; max-height: 200px; border: 1px solid #d7d7d7;"
                                                     alt="..."
                                                     src="{!! $minusMoney ->pathSmallImage($reasonImage) !!}">
                                            </a>
                                        @endif
                                        @if(!$hFunction->checkEmpty($orderAllocationId))
                                            <br/>&emsp;
                                            <em style="color: grey;">- Đơn hàng:</em>
                                            <a class="qc-link" style="color: blue !important;"
                                               href="{!! route('qc.work.work_allocation.order_allocation.product.get',$orderAllocationId) !!}">
                                                {!! $minusMoney->orderAllocation->orders->name() !!}
                                            </a>
                                        @endif
                                        @if(!$hFunction->checkEmpty($workAllocationId))
                                            <br/>&emsp;
                                            <em style="color: grey;">- SP:</em>
                                            <a style="color: blue;">
                                                {!! $minusMoney->workAllocation->product->productType->name() !!}
                                            </a>
                                            <br/>&emsp;
                                            <em style="color: grey;">- ĐH:</em>
                                            <a style="color: blue;">
                                                {!! $minusMoney->workAllocation->product->order->name() !!}
                                            </a>
                                        @endif
                                        @if(!$hFunction->checkEmpty($orderConstructionId))
                                            <br/>&emsp;
                                            <em style="color: grey;">- Đơn hàng:</em>
                                            <a class="qc-link" style="color: blue !important;"
                                               href="{!! route('qc.work.work_allocation.order.construction.get',$orderConstructionId) !!}">
                                                {!! $minusMoney->orderConstruction->name() !!}
                                            </a>
                                        @endif
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
                                                   data-href="{!! route('qc.work.minus_money.feedback.view_image.get',$feedbackId) !!}">
                                                    <img style="max-width: 150px; max-height: 150px; border: 1px solid #d7d7d7;"
                                                         alt="..."
                                                         src="{!! $dataMinusMoneyFeedback->pathSmallImage($feedbackImage) !!}">
                                                </a>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr style="background-color: black;">
                                <td>
                                    <b class="qc-font-size-14" style="color: yellow;">
                                        {!! $hFunction->currencyFormat($totalMoney) !!}
                                    </b>
                                    <b style="color: white;">(Tổng)</b>
                                </td>
                                <td class="text-right"></td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="2">
                                    <label style="color: red;">Không có thông tin phạt</label>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
