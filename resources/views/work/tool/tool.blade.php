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

$dataToolAllocation = 0;// $dataStaff->toolAllocationOfReceiveStaffInfo();
?>
@extends('work.index')
@section('qc_work_body')
    <div class="row qc_work_tool_wrap">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <h3>Danh sách đồ nghề</h3>
                </div>
            </div>
            {{-- chi tiêt --}}
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="qc_container_table row">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr style="background-color: whitesmoke;">
                                <th class="text-center">STT</th>
                                <th>Dụng cụ</th>
                                <th class="text-center">Ngày phát</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-right">Trạng thái</th>
                            </tr>
                            @if(count($dataToolAllocationDetail) > 0)
                                @foreach($dataToolAllocationDetail as $toolAllocationDetail)
                                    <?php
                                    $detailId = $toolAllocationDetail->detailId();
                                    $dataToolAllocation = $toolAllocationDetail->toolAllocation;
                                    $allocationDate = $dataToolAllocation->allocationDate();
                                    $allocationId = $dataToolAllocation->allocationId();
                                    ?>
                                    <tr class="qc_work_allocation_detail" data-detail="{!! $detailId !!}" >
                                        <td class="text-center">
                                            {!! $n_o = (isset($n_o)) ? $n_o + 1 : 1 !!}
                                        </td>
                                        <td>
                                            {!!  $toolAllocationDetail->tool->name() !!}
                                        </td>
                                        <td class="text-center">
                                            {!! date('d-m-Y',strtotime($allocationDate)) !!}
                                        </td>
                                        <td class="text-center">
                                            {!! $toolAllocationDetail->amount() !!}
                                        </td>
                                        <td class="text-right">
                                            @if($dataToolAllocation->checkConfirm())
                                                <em class="qc-color-grey">Đã xác nhận</em>
                                            @else
                                                <a class="qc_work_tool_confirm_receive_act qc-link-green"
                                                   data-href="{!! route('qc.work.tool.confirm_receive.get', $allocationId)  !!}">
                                                    Xác nhận
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="5">
                                        Không có thông tin phạt
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <a class="btn btn-sm btn-primary" href="{!! route('qc.work.home') !!}">Đóng</a>
                </div>
            </div>
        </div>
    </div>
@endsection
