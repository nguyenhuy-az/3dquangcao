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
$hrefIndex = route('qc.work.store.tool_package_allocation_return.get');
$currentMonth = $hFunction->currentMonth();

?>
@extends('work.store.tool-package-allocation-return.index')
@section('qc_work_store_package_allocation_return_body')
    <div class="row qc_work_store_package_allocation_return_wrap">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            {{-- chi tiêt --}}
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr style="background-color: black;color: yellow;">
                                <th class="text-center" style="width: 20px;">STT</th>
                                <th>Đồ nghề</th>
                                <th>Người trả</th>
                                <th>
                                    Ngày trả
                                </th>
                                <th>
                                    Ảnh bàn giao
                                </th>
                                <th>
                                    Ảnh trả
                                </th>
                                <th class="text-center">
                                    Xác nhận
                                </th>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td class="text-center" style="padding: 0px; margin: 0;">
                                    <select class="cbStaffFilterId form-control" data-href="{!! $hrefIndex !!}">
                                        <option value="0" @if($staffFilterId == 0) selected="selected" @endif>
                                            Tất cả
                                        </option>
                                        @if($hFunction->checkCount($dataListStaff))
                                            @foreach($dataListStaff as $staff)
                                                <option @if($staff->staffId() == $staffFilterId) selected="selected"
                                                        @endif  value="{!! $staff->staffId() !!}">{!! $staff->lastName() !!}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="padding: 0">
                                    <select class="cbConfirmStatusFilter text-center form-control"
                                            name="cbConfirmStatusFilter"
                                            data-href="{!! $hrefIndex !!}">
                                        <option value="100"
                                                @if($cbConfirmStatusFilter ==100) selected="selected" @endif>
                                            Tất cả
                                        </option>
                                        <option value="0" @if($cbConfirmStatusFilter == 0) selected="selected" @endif>
                                            Chưa duyệt
                                        </option>
                                        <option value="1" @if($cbConfirmStatusFilter == 1) selected="selected" @endif>Đã
                                            duyệt
                                        </option>
                                    </select>
                                </td>
                            </tr>
                            @if($hFunction->checkCount($dataToolPackageAllocationReturn))
                                <?php $n_o = 0; ?>
                                @foreach($dataToolPackageAllocationReturn as $toolPackageAllocationReturn)
                                    <?php
                                    $returnId = $toolPackageAllocationReturn->returnId();
                                    $returnDate = $toolPackageAllocationReturn->returnDate();
                                    $returnImage = $toolPackageAllocationReturn->image();
                                    # thong tin duoc giao
                                    $dataToolPackageAllocationDetail = $toolPackageAllocationReturn->toolPackageAllocationDetail;
                                    $allocationId = $dataToolPackageAllocationDetail->allocationId();
                                    $detailImage = $dataToolPackageAllocationDetail->image();
                                    # thong tin kho
                                    $dataCompanyStore = $dataToolPackageAllocationDetail->companyStore;
                                    # thong tin nhan vien tra
                                    $dataStaffReturn = $toolPackageAllocationReturn->toolPackageAllocationDetail->toolPackageAllocation->companyStaffWork->staff;
                                    $n_o = $n_o + 1;
                                    ?>
                                    <tr class="@if($n_o%2) info @endif">
                                        <td class="text-center">
                                            {!! $n_o !!}
                                        </td>
                                        <td>
                                            {!!  $dataCompanyStore->name() !!}
                                        </td>
                                        <td>
                                            {!!  $dataStaffReturn->fullName() !!}
                                        </td>
                                        <td>
                                            {!! date('d/m/Y', strtotime($returnDate)) !!}
                                        </td>
                                        <td>
                                            <a class="qc_view_image_get qc-link"
                                               data-href="{!! route('qc.work.store.tool_package_allocation_return.detail_image.get',$dataToolPackageAllocationDetail->detailId()) !!}">
                                                <img style="width: 70px; height: auto;"
                                                     src="{!! $dataToolPackageAllocationDetail->pathFullImage($dataToolPackageAllocationDetail->image()) !!}">
                                            </a>
                                        </td>
                                        <td>
                                            <div style="width: 70px; max-height: 70px;">
                                                <a class="qc_view_image_get qc-link"
                                                   data-href="{!! route('qc.work.store.tool_package_allocation_return.return_image.get',$returnId) !!}">
                                                    <img style="max-width: 100%; max-height: 100%;"
                                                         src="{!! $toolPackageAllocationReturn->pathFullImage($returnImage) !!}">
                                                </a>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @if($toolPackageAllocationReturn->checkConfirm())
                                                @if($toolPackageAllocationReturn->checkAcceptStatus())
                                                    <em>Đồng ý</em>
                                                @else
                                                    <em style="color: red;">Không đồng ý</em>
                                                @endif
                                            @else
                                                <a class="qc_confirm_get qc-link-green-bold"
                                                   data-href="{!! route('qc.work.store.tool_package_allocation_return.confirm.get', $allocationId) !!}">
                                                    Duyệt
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="6">
                                        Không có thông tin trả
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
