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
$dataBonus = $dataWork->getInfoHasApplyBonus();
?>
@extends('ad3d.system.staff.index')
@section('qc_ad3d_index_content')
    <div class="qc_ad3d_sys_staff_statistical_wrap qc-padding-bot-30 row">
        <div class="row" style="margin-bottom: 20px;">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <a class="qc-link-white-bold btn btn-sm btn-primary" onclick="qc_main.page_back_go();">
                    <i class="glyphicon glyphicon-backward"></i> Về trang trước
                </a>
            </div>
        </div>
        <div class="row" style="margin-bottom: 10px;">
            <div class="col-sx-12 col-sm-6 col-md-6 col-lg-6">
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
            <div class="col-sx-12 col-sm-6 col-md-6 col-lg-6">
                <h3 style="color: red;">{!! date('m-Y', strtotime($fromDate)) !!}</h3>
            </div>
        </div>
        {{--Ngay di lam--}}
        <div class="row">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <label style="color: red; font-size: 1.5em;">
                    THỐNG KÊ NHẬN THƯỞNG - {!! $hFunction->getCount($dataBonus) !!}
                </label>
            </div>
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        @if($hFunction->checkCount($dataBonus))
                            <?php
                            $totalMoney = 0;
                            ?>
                            @foreach($dataBonus as $bonus)
                                <?php
                                $bonusId = $bonus->bonusId();
                                # thi cong - quan ly thi cong
                                $orderAllocationId = $bonus->orderAllocationId();
                                # kinh doanh quan ly don hang
                                $orderConstructionId = $bonus->orderConstructionId();
                                # thi cong san pham
                                $workAllocationId = $bonus->workAllocationId();
                                # thanh toan
                                $orderPayId = $bonus->orderPayId();
                                $cancelNote = $bonus->cancelNote();
                                $cancelImage = $bonus->cancelImage();
                                $cancelStatus = $bonus->checkCancelStatus();
                                $money = $bonus->money();
                                if (!$cancelStatus) {
                                    $totalMoney = $totalMoney + $money;
                                }
                                $n_o = (isset($n_o))? $n_o + 1: 1;
                                ?>
                                <tr @if($n_o%2 == 0) class="info" @endif>
                                    <td>
                                        <b>{!! $n_o !!}).</b>
                                        <b style="@if(!$cancelStatus) color: red; @endif">
                                            {!! $hFunction->currencyFormat($money) !!}
                                        </b>
                                        <br/>&emsp;
                                        <em style="color: blue;">{!! date('d/m/Y', strtotime($bonus->bonusDate())) !!} </em>
                                        <br/>&emsp;
                                        <em style="color: grey;">- {!! $bonus->note() !!}</em>
                                        @if(!$hFunction->checkEmpty($workAllocationId))
                                            <br/>&emsp;
                                            <em style="color: grey;">- SP:</em>
                                            <b style="color: blue;">{!! $bonus->workAllocation->product->productType->name() !!}</b>
                                            <br/>&emsp;
                                            <em style="color: grey;">- ĐH:</em>
                                            <b style="color: deeppink;">{!! $bonus->workAllocation->product->order->name() !!}</b>
                                        @endif
                                        @if(!$hFunction->checkEmpty($orderAllocationId))
                                            <br/>&emsp;
                                            <em style="color: grey;">- ĐH:</em>
                                            <b style="color: blue;">{!! $bonus->orderAllocation->orders->name() !!}</b>
                                        @endif
                                        @if(!$hFunction->checkEmpty($orderConstructionId))
                                            <br/>&emsp;
                                            <em style="color: grey;">- ĐH:</em>
                                            <b style="color: red;">{!! $bonus->orderConstruction->name() !!}</b>
                                        @endif
                                        @if(!$hFunction->checkEmpty($orderPayId))
                                            <br/>&emsp;
                                            <em style="color: grey;">- ĐH:</em>
                                            <b style="color: blue;">{!! $bonus->orderPay->order->name() !!}</b>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="text-center qc-padding-none">
                                    Không có thông tin thưởng
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
