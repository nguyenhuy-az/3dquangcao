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
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$loginStaffId = $dataStaff->staffId();

$dataToolAllocation = $dataStaff->toolAllocationOfReceiveStaffInfo();
?>
@extends('work.index')
@section('qc_work_body')
    <div class="row qc_work_tool_wrap">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed #C2C2C2;">
                    <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6">
                        <h3>Danh sách đồ nghề</h3>
                    </div>
                </div>
            </div>
            {{-- chi tiêt --}}
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                @if(count($dataToolAllocation) > 0)
                    @foreach($dataToolAllocation as $toolAllocation)
                        <?php
                        $allocationId = $toolAllocation->allocationId();
                        $allocationDate = $toolAllocation->allocationDate();
                        $dataToolAllocationDetail = $toolAllocation->toolAllocationDetailOfAllocation();
                        ?>
                        <div class="row qc_work_allocation" data-allocation="{!! $allocationId !!}"
                             style="border-bottom: 1px solid #d7d7d7;">
                            <div class="qc-padding-top-5  qc-padding-bot-5 col-xs-6 col-sm-12 col-md-6 col-lg-6">
                                <em>
                                    <i class="glyphicon glyphicon-chevron-right"></i>
                                    Ngày:
                                </em>
                                <span class="qc-color-red "
                                      style="color:red;">{!! date('d-m-Y',strtotime($allocationDate)) !!}</span>
                            </div>
                            <div class="text-right qc-padding-top-5 qc-padding-bot-5 col-xs-6 col-sm-12 col-md-3 col-lg-3">
                                <em>Số lượng :</em>
                                <b class="qc-color-red">
                                    {!! $toolAllocation->totalAmountToolOfAllocation() !!}
                                </b>
                            </div>
                            <div class="text-right qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                @if($toolAllocation->checkConfirm())
                                    <em class="qc-color-grey">Đã xác nhận</em>
                                @else
                                    <a class="qc_work_tool_confirm_receive_act qc-link-green"
                                       data-href="{!! route('qc.work.tool.confirm_receive.get')  !!}">
                                        Xác nhận
                                    </a>
                                @endif
                            </div>
                            @if(count($dataToolAllocationDetail) > 0)
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="table-responsive" style="border: none;">
                                        <table class="table table-hover">
                                            @foreach($dataToolAllocationDetail as $toolAllocationDetail)
                                                <?php
                                                $n_o = isset($n_o) ? $n_o + 1 : 1;
                                                ?>
                                                <tr>
                                                    <td style="border: none;">
                                                        &emsp;
                                                        <b>{!! $n_o !!}- </b>
                                                        {!!  $toolAllocationDetail->tool->name() !!}
                                                    </td>
                                                    <td class="text-right col-md-3 col-lg-3" style="border: none;">
                                                        {!! $toolAllocationDetail->amount() !!}
                                                    </td>
                                                    <td class="text-right col-md-3 col-lg-3" style="border: none;">
                                                        @if($toolAllocationDetail->checkNewStatus())
                                                            <em>Mới</em>
                                                        @else
                                                            <em>Cũ</em>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <?php
                                            $n_o = 0;
                                            ?>
                                        </table>
                                    </div>

                                </div>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="row">
                        <div class="text-center qc-color-red qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <span>Không có đồ nghề</span>
                        </div>
                    </div>
                @endif
            </div>


            <div class="row">
                <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <a href="{!! route('qc.work.tool.get') !!}">
                        <button type="button" class="btn btn-sm btn-primary">
                            Đóng
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
