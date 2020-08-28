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
# thong tin ban giao tui do nghe
$allocationId = $dataToolPackageAllocation->allocationId();
# tui do nghe
$dataPackage = $dataToolPackageAllocation->toolPackage;
?>
@extends('work.tool.tool-package-allocation.index')
@section('qc_work_tool_package_allocation_body')
    <div class="row qc_work_tool_wrap">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr>
                                <td colspan="3">
                                    <span style="color: red; font-size: 1.5em;">
                                        Túi đồ {!! $dataPackage->name !!}
                                    </span>
                                </td>
                            </tr>
                            <tr style="background-color: black;color: yellow;">
                                <th class="text-center" style="width: 20px;">STT</th>
                                <th>Loại Dụng cụ</th>
                                <th class="text-center">
                                    Số lượng
                                </th>
                            </tr>
                            @if($hFunction->checkCount($dataTool))
                                @foreach($dataTool as $tool)
                                    <?php
                                    $n_o = (isset($n_o)) ? $n_o + 1 : 1;
                                    $toolId = $tool->toolId();
                                    $dataToolPackageAllocationDetail = $dataToolPackageAllocation->infoDetailOfToolAllocationAndTool($allocationId, $toolId);
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
                                        @foreach($dataToolPackageAllocationDetail as $toolPackageAllocationDetail)
                                            <tr class="@if($n_o%2) info @endif">
                                                <td class="text-center"></td>
                                                <td>
                                                    <i class="glyphicon glyphicon-play"></i>
                                                    <a class="qc_view_image_get qc-link"
                                                       data-href="{!! route('qc.work.tool.package_allocation.image.view',$toolPackageAllocationDetail->detailId()) !!}">
                                                        <img style="width: 70px; height: auto;"
                                                             src="{!! $toolPackageAllocationDetail->pathSmallImage($toolPackageAllocationDetail->image()) !!}">
                                                    </a>
                                                    {!! $toolPackageAllocationDetail->companyStore->name() !!}
                                                </td>
                                                <td class="text-center">
                                                    <a class="qc-link-green-bold"
                                                       data-href="{!! route('qc.work.tool.package_allocation.report.get') !!}">
                                                        Báo cáo
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
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
