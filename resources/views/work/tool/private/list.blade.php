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
$dataStaff = $modelStaff->loginStaffInfo();
$loginStaffId = $dataStaff->staffId();
$companyId = $dataStaff->companyId();
$hrefIndex = route('qc.work.tool.private.get');
$currentMonth = $hFunction->currentMonth();
$dataCompanyStaffWork = $dataStaff->companyStaffWorkInfoActivity();
?>
@extends('work.tool.private.index')
@section('qc_work_tool_private_body')
    <div class="row qc_work_tool_wrap">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-6 col-lg-6">
            @if($hFunction->checkCount($dataCompanyStaffWork))
                <?php
                $workId = $dataCompanyStaffWork->workId();
                ?>
                {{-- chi tiêt --}}
                <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <tr style="background-color: black;color: yellow;">
                                    <th class="text-center" style="width: 20px;">STT</th>
                                    <th>Dụng cụ</th>
                                    <th class="text-center">
                                        Số lượng <br/> đã nhận
                                    </th>
                                    <th class="text-center">
                                        Số lượng <br/> báo trả
                                    </th>
                                    <th class="text-center">
                                        Số lượng <br/> đã trả
                                    </th>
                                    <th class="text-center">Trạng thái</th>
                                </tr>
                                @if($hFunction->checkCount($dataCompanyStore))
                                    @foreach($dataCompanyStore as $companyStore)
                                        <?php
                                        $storeId = $companyStore->storeId();
                                        $dataTool = $companyStore->tool;
                                        $toolId = $dataTool->toolId();
                                        $toolName = $dataTool->name();
                                        $totalToolReceiveOfStaff = $dataCompanyStaffWork->totalToolReceive($toolId, $workId);
                                        $totalToolReturnOfStaff = 0;
                                        ?>
                                        <tr class="@if($totalToolReceiveOfStaff == 0) info @endif">
                                            <td class="text-center">
                                                {!! $n_o = (isset($n_o)) ? $n_o + 1 : 1 !!}
                                            </td>
                                            <td>
                                                {!!  $toolName !!}
                                            </td>
                                            <td class="text-center">
                                                {!! $totalToolReceiveOfStaff !!}
                                            </td>
                                            <td class="text-center">
                                                {!! $toolId !!}
                                            </td>
                                            <td class="text-center">
                                                {!! $toolId !!}
                                            </td>
                                            <td class="text-center">
                                                @if($totalToolReceiveOfStaff > $totalToolReturnOfStaff)
                                                    <a class="qc-link-green-bold"
                                                       href="{!! route('qc.work.tool.private.return.get', $storeId) !!}">
                                                        Giao lại
                                                    </a>
                                                @else
                                                    <a class="qc-link-bold">
                                                        {{--Yêu cầu phát--}}
                                                        Chưa có
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-center" colspan="6">
                                            Không có thông tin phạt
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-6 col-lg-6">
                    <span>Không có thông tin làm việc</span>
                </div>
            @endif
            <div class="row">
                <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <a class="btn btn-sm btn-primary" href="{!! route('qc.work.home') !!}">Đóng</a>
                </div>
            </div>
        </div>
    </div>
@endsection
