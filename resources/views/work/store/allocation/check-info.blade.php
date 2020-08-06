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
$dataStaffReceive = $dataToolAllocation->companyStaffWork->staff;
?>
@extends('work.store.allocation.index')
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
                <label style="color: red; font-size: 1.5em;">DANH SÁCH ĐỒ NGHỀ</label>
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
                            <th>Ảnh giao</th>
                            <th class="text-center">
                                Hiện trạng
                            </th>
                            <th class="text-center">Trạng thái</th>
                        </tr>
                        @if($hFunction->checkCount($dataToolAllocationDetail))
                            @foreach($dataToolAllocationDetail as $toolAllocationDetail)
                                <?php
                                $detailId = $toolAllocationDetail->detailId();
                                $detailImage = $toolAllocationDetail->image();
                                $detailDate = $toolAllocationDetail->createdAt();
                                $allocationId = $toolAllocationDetail->allocationId();
                                $storeId = $toolAllocationDetail->storeId();
                                $storeName = $toolAllocationDetail->companyStore->name();
                                # lay thong tin tra la sau cung
                                $toolReturn = $toolAllocationDetail->lastInfoOfToolReturn();
                                $returnStatus = $hFunction->checkCount($toolReturn);
                                ?>
                                <tr class="@if($returnStatus) info @endif">
                                    <td class="text-center" style="padding: 0;">
                                        <div class="form-group" style="margin: 0;">
                                            <input type="checkbox" class="form-control" disabled
                                                   name="txtAllocationDetail[]" style="margin: 0;"
                                                   checked="checked">
                                        </div>
                                    </td>
                                    <td>
                                        {!!  $storeName !!}
                                    </td>
                                    <td>
                                        {!! $hFunction->convertDateDMYFromDatetime($detailDate) !!}
                                    </td>
                                    <td>
                                        <a class="qc_view_image_get qc-link"
                                           data-href="{!! route('qc.work.store.allocation.check.image.view',$detailId) !!}">
                                            <img style="width: 70px; height: auto;"
                                                 src="{!! $toolAllocationDetail->pathFullImage($detailImage) !!}">
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        @if($toolAllocationDetail->checkNewStatus())
                                            <span>Mới</span>
                                        @else
                                            <span>Đã qua sử dụng</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if(!$hFunction->checkCount($toolReturn))
                                            <a class="qc_minus_money_get qc-link-green-bold"
                                               data-href="{!! route('qc.work.store.allocation.check.minus_money.get',$detailId) !!}">
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
                                                       data-href="{!! route('qc.work.store.allocation.check.minus_money.get',$detailId) !!}">
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
                                <td class="text-center" colspan="6">
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
