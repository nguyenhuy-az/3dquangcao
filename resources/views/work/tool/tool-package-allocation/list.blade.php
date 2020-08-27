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
$hrefIndex = route('qc.work.tool.package_allocation.get');
$currentMonth = $hFunction->currentMonth();
# thong tin ban giaotui do nghe
$allocationId = $dataToolPackageAllocation->allocationId();

?>
@extends('work.tool.tool-package-allocation.index')
@section('qc_work_tool_private_body')
    <div class="row qc_work_tool_wrap">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr style="background-color: black;color: yellow;">
                                <th class="text-center" style="width: 20px;">STT</th>
                                <th>Loại Dụng cụ</th>
                                <th class="text-center">
                                    Số lượng
                                </th>
                            </tr>
                            {{--<tr style="background-color: black;color: yellow;">
                                <th class="text-center" style="width: 20px;">STT</th>
                                <th>Dụng cụ</th>
                                <th>
                                    Ngày giao
                                </th>
                                <th class="text-center">
                                    Ảnh được giao
                                </th>
                                <th class="text-center">
                                    Hiện trạng
                                    <giao></giao>
                                </th>
                                <th class="text-center">
                                    Ảnh trả
                                </th>
                                <th class="text-center">Trạng thái</th>
                            </tr>--}}
                            @if($hFunction->checkCount($dataTool))
                                @foreach($dataTool as $tool)
                                    <?php
                                    $n_o = (isset($n_o)) ? $n_o + 1 : 1;
                                    $toolId = $tool->toolId();
                                    $dataToolPackageAllocationDetail = $dataToolPackageAllocation->infoActivityOfToolAllocationAndTool($allocationId, $toolId);
                                    $amountDetail = count($dataToolPackageAllocationDetail);
                                    ?>
                                    <tr class="@if($n_o%2) info @endif">
                                        <td class="text-center">
                                            {!! $n_o !!}
                                        </td>
                                        <td>
                                            {!! $tool->name() !!}
                                        </td>
                                        <td class="text-center">
                                            @if($amountDetail > 0)
                                                {!! $amountDetail !!}
                                            @else
                                                <span style="color: red">Chưa có</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @if($amountDetail > 0)
                                        <tr class="@if($n_o%2) info @endif">
                                            <td class="text-center" style="background-color: brown;">

                                            </td>
                                            <td>
                                                {!! $tool->name() !!}
                                            </td>
                                            <td class="text-center">
                                                <a class="qc-link-green-bold">
                                                    Báo cáo
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="3">
                                        Hệ thống chưa có danh sách loại đồ nghề
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
