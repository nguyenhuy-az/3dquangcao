<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 5/15/2018
 * Time: 10:12 AM
 *
 * dataStaff
 */
$hFunction = new Hfunction();
$urlReferer = $hFunction->getUrlReferer();
$allocationId = $dataToolAllocation->allocationId();
$totalTool = $dataToolAllocation->totalAmountToolOfAllocation();
$allocationDate = $dataToolAllocation->allocationDate();
$dataToolAllocationDetail = $dataToolAllocation->toolAllocationDetailOfAllocation();
?>
@extends('ad3d.store.import.import.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed #C2C2C2; padding: 0px;">
                <h3>CHI TIẾT BÀN GIAO</h3>
            </div>

            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="text-right qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <em>Người nhận:</em>
                        <b class="qc-color-red">{!! $dataToolAllocation->receiveStaff->lastName() !!}</b>
                    </div>
                    <div class="text-right qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <em>Người giao:</em>
                        <b class="qc-color-red">{!! $dataToolAllocation->allocationStaff->lastName() !!}</b>
                    </div>
                    <div class="text-right qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-2 col-lg-2">
                        <em class="qc-color-grey">Ngày </em>
                        <b class="qc-color-red">{!! date('d/m/Y', strtotime($allocationDate)) !!}</b>
                    </div>
                    <div class="text-right qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-2 col-lg-2">
                        @if($dataToolAllocation->checkConfirm())
                            <em>Đã xác nhận</em>
                        @else
                            <em>Chưa xác nhận</em>
                        @endif
                    </div>
                    <div class="text-right qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-2 col-lg-2">
                        <em class="qc-color-grey">Tổng: </em>
                        <b class="qc-color-red">{!! $dataToolAllocation->totalAmountToolOfAllocation() !!}</b>
                    </div>
                </div>
            </div>
            {{-- chi tiêt --}}
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    @if(count($dataToolAllocationDetail) > 0)
                        @foreach($dataToolAllocationDetail as $allocationDetail)
                            <?php
                            $n_o = (isset($n_o)) ? $n_o + 1 : 1;
                            ?>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 qc-padding-top-5 qc-padding-bot-5"
                                 style="border-bottom: 1px solid lightgrey;">
                                <div class="row">
                                    <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                        <span>{!! $n_o !!}. </span>
                                        <b>
                                            {!! $allocationDetail->tool->name() !!}
                                        </b>
                                    </div>
                                    <div class="text-right col-xs-6 col-sm-12 col-md-3 col-lg-3">
                                        <em>Số lượng: </em>
                                        <b>{!! $allocationDetail->amount() !!}</b>
                                    </div>
                                    <div class="text-right col-xs-6 col-sm-12 col-md-3 col-lg-3">
                                        @if($allocationDetail->checkNewStatus())
                                            <em>Mới</em>
                                        @else
                                            <em>Qua sử dụng</em>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="qc_ad3d_list_object qc-ad3d-list-object row">
                            <div class="qc-padding-top-5 qc-padding-bot-5 text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <em class="qc-color-red">Chưa thanh toán</em>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="qc-padding-top-10 qc-padding-bot-10 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <a type="button" class="btn btn-sm btn-primary" href="{!! $urlReferer !!}">Đóng</a>
                </div>
            </div>
        </div>
    </div>
@endsection
