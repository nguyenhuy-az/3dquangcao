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
$dataStaffReceive = $dataToolPackageAllocation->companyStaffWork->staff;
?>
@extends('work.store.tool-package-allocation.index')
@section('qc_work_store_allocation_body')
    <div class="qc_work_store_allocation_check_info qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <a class="btn btn-sm btn-primary" onclick="qc_main.page_back();">
                    Về trang trước
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6">
                <label style="color: red; font-size: 1.5em;">DANH SÁCH ĐỒ NGHỀ TRONG TÚI</label>
                <label style="color: blue; font-size: 1.5em;">{!! $dataToolPackageAllocation->toolPackage->name() !!}</label>
            </div>
            <div class="text-right col-sx-12 col-sm-12 col-md-6 col-lg-6">
                <span style="background-color: blue;color: yellow; padding: 5px 10px;">{!! $dataStaffReceive->fullName() !!}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: black;color: yellow;">
                            <th class="text-center" style="width: 20px;">STT</th>
                            <th>Dụng cụ</th>
                            <th>
                                Ngày giao
                            </th>
                            <th class="text-center">Ảnh giao</th>
                            <th class="text-center">
                                Hiện trạng
                            </th>
                            <th class="text-center">Ảnh trả</th>
                            <th class="text-center">Trạng thái</th>
                        </tr>
                        @if($hFunction->checkCount($dataToolPackageAllocationDetail))
                            @foreach($dataToolPackageAllocationDetail as $toolPackageAllocationDetail)
                                <?php
                                $detailId = $toolPackageAllocationDetail->detailId();
                                $detailImage = $toolPackageAllocationDetail->image();
                                $detailDate = $toolPackageAllocationDetail->createdAt();
                                $allocationId = $toolPackageAllocationDetail->allocationId();
                                $storeId = $toolPackageAllocationDetail->storeId();
                                $storeName = $toolPackageAllocationDetail->companyStore->name();
                                # lay thong tin tra la sau cung
                                $toolReturn = $toolPackageAllocationDetail->lastInfoOfToolReturn();
                                $returnStatus = $hFunction->checkCount($toolReturn);
                                ?>
                                <tr class="@if($returnStatus) info @endif">
                                    <td class="text-center" style="padding: 0;">
                                        {!! $n_o = (isset($n_o))?$n_o + 1:1 !!}
                                    </td>
                                    <td>
                                        {!!  $storeName !!}
                                    </td>
                                    <td>
                                        {!! $hFunction->convertDateDMYFromDatetime($detailDate) !!}
                                    </td>
                                    <td class="text-center">
                                        <a class="qc_view_image_get qc-link"
                                           data-href="{!! route('qc.work.store.tool_package_allocation.check.image.view',$detailId) !!}">
                                            <img style="width: 70px; height: auto;"
                                                 src="{!! $toolPackageAllocationDetail->pathFullImage($detailImage) !!}">
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        @if($toolPackageAllocationDetail->checkNewStatus())
                                            <span>Mới</span>
                                        @else
                                            <span>Đã qua sử dụng</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{--da bao tra--}}
                                        @if($returnStatus)
                                            <a class="qc_view_image_get qc-link"
                                               data-href="{!! route('qc.work.store.tool_package_allocation.check.return_image.view',$toolReturn->returnId()) !!}">
                                                <img style="width: 70px; height: auto;"
                                                     src="{!! $toolReturn->pathFullImage($toolReturn->image()) !!}">
                                            </a>
                                        @else
                                            <span style="color: red;">X</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if(!$returnStatus)
                                            <a class="qc_minus_money_get qc-link-green-bold"
                                               data-href="{!! route('qc.work.store.tool_package_allocation.check.minus_money.get',$detailId) !!}">
                                                Phạt
                                            </a>
                                        @else
                                            <em class="qc-color-grey">Đã trả</em> <br/>
                                            @if($toolReturn->checkConfirm())
                                                @if(!$toolReturn->checkAcceptStatus())
                                                    <em style="background-color: red; color: yellow;">
                                                        Không được chấp nhận
                                                    </em> <br/>
                                                    <a class="qc_minus_money_get qc-link-green-bold"
                                                       data-href="{!! route('qc.work.store.tool_package_allocation.check.minus_money.get',$detailId) !!}">
                                                        Phạt
                                                    </a>
                                                @endif
                                            @else
                                                <em style="background-color: blue; color: white;">Chờ Xác nhận</em>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="text-center" colspan="7">
                                    Chưa có đồ nghề
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
