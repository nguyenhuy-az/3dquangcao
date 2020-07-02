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

?>
@extends('work.store.tool.index')
@section('qc_work_store_tool_body')
    <div class="row qc_work_store_tool_wrap">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-6 col-lg-6">
            {{-- chi tiêt --}}
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr style="background-color: black;color: yellow;">
                                <th class="text-center" style="width: 20px;">STT</th>
                                <th>Dụng cụ</th>
                                <th class="text-center">
                                    Loại
                                </th>
                                <th class="text-center">
                                    Số lượng
                                </th>
                                <th class="text-center">
                                    Bị hư
                                </th>
                                <th class="text-center">
                                    Đã giao
                                </th>
                                <th class="text-center">
                                    Còn lại
                                </th>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td style="padding: 0">
                                   <select class="cbToolType form-control" name="cbToolType">
                                       <option value="0">Tất cả</option>
                                       <option value="1">Dùng chung</option>
                                       <option value="2">Dùng cấp phát</option>
                                   </select>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @if($hFunction->checkCount($dataCompanyStore))
                                <?php $n_o = 0; ?>
                                @foreach($dataCompanyStore as $companyStore)
                                    <?php
                                    $storeId = $companyStore->storeId();
                                    $totalAmount = $companyStore->amount();
                                    $dataTool = $companyStore->tool;
                                    $toolId = $dataTool->toolId();
                                    $toolName = $dataTool->name();
                                    # so luong da giao
                                    $totalAllocation = 1;
                                    # so luong da huy
                                    $totalCancel = 2;
                                    # chu phat
                                    $totalNoAllocation = 3;
                                    $n_o = $n_o + 1;
                                    ?>
                                    <tr class="@if($totalNoAllocation == 0) warning @elseif($n_o%2) info @endif">
                                        <td class="text-center">
                                            {!! $n_o !!}
                                        </td>
                                        <td>
                                            {!!  $toolName !!}
                                        </td>
                                        <td class="text-center">
                                            {!! $dataTool->getLabelType() !!}
                                        </td>
                                        <td class="text-center">
                                            {!! $totalAmount !!}
                                        </td>
                                        <td class="text-center">
                                            {!! $totalCancel !!}
                                        </td>
                                        <td class="text-center">
                                            {!! $totalAllocation !!}
                                        </td>
                                        <td class="text-center">
                                            {!! $totalNoAllocation !!}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="7">
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
