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
$hrefIndex = route('qc.work.tool.allocation.get');
$currentMonth = $hFunction->currentMonth();
?>
@extends('work.tool.allocation.index')
@section('qc_work_tool_private_body')
    <div class="row qc_work_tool_wrap">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr style="background-color: black;color: yellow;">
                                <th class="text-center" style="width: 20px;">STT</th>
                                <th>Dụng cụ</th>
                                <th>
                                    Ngày giao
                                </th>
                                <th class="text-center">Ảnh được giao</th>
                                <th class="text-center">
                                    Hiện trạng <giao></giao>
                                </th>
                                <th class="text-center">
                                    Ảnh trả
                                </th>
                                <th class="text-center">Trạng thái</th>
                            </tr>
                            @if($hFunction->checkCount($dataToolAllocationDetail))
                                @foreach($dataToolAllocationDetail as $toolAllocationDetail)
                                    <?php
                                    $n_o = (isset($n_o)) ? $n_o + 1 : 1;
                                    $detailId = $toolAllocationDetail->detailId();
                                    $detailImage = $toolAllocationDetail->image();
                                    $detailDate = $toolAllocationDetail->createdAt();
                                    $allocationId = $toolAllocationDetail->allocationId();
                                    $storeId = $toolAllocationDetail->storeId();
                                    $dataCompanyStore = $toolAllocationDetail->companyStore;
                                    $storeName = $dataCompanyStore->name();
                                    # lay thong tin tra la sau cung
                                    $toolReturn = $toolAllocationDetail->lastInfoOfToolReturn();
                                    $returnStatus = $hFunction->checkCount($toolReturn);
                                    # lay thong tin giao sau cung
                                    $dataToolAllocationDetail = $dataCompanyStore->toolAllocationDetailLastInfo();
                                    ?>
                                    <tr class="@if($returnStatus) info @endif">
                                        <td class="text-center">
                                            {!! $n_o !!}
                                        </td>
                                        <td>
                                            {!!  $storeName !!}
                                        </td>
                                        <td>
                                            {!! $hFunction->convertDateDMYFromDatetime($detailDate) !!}
                                        </td>
                                        <td class="text-center">
                                            <a class="qc_view_image_get qc-link"
                                               data-href="{!! route('qc.work.tool.allocation.image.view',$detailId) !!}">
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
                                            {{--da bao tra--}}
                                            @if($returnStatus)
                                                <a class="qc_view_image_get qc-link" data-href="{!! route('qc.work.tool.allocation.return_image.view',$toolReturn->returnId()) !!}">
                                                    <img style="width: 70px; height: auto;"
                                                         src="{!! $toolReturn->pathFullImage($toolReturn->image()) !!}">
                                                </a>
                                            @else
                                                <span style="color: red;">X</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if(!$hFunction->checkCount($toolReturn))
                                                <a class="qc-link-green-bold"
                                                   href="{!! route('qc.work.tool.allocation.return.get',"$allocationId/$detailId") !!}">
                                                    Giao lại
                                                </a>
                                            @else
                                                <em class="qc-color-grey">Đã trả</em> <br/>
                                                @if($toolReturn->checkConfirm())
                                                    @if($toolReturn->checkAcceptStatus())
                                                        <em>
                                                            Được chấp nhận
                                                        </em>
                                                    @else
                                                        <em style="background-color: red; color: yellow;">Không được
                                                            chấp nhận</em>
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
            <div class="row">
                <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <a class="btn btn-sm btn-primary" href="{!! route('qc.work.home') !!}">Đóng</a>
                </div>
            </div>
        </div>
    </div>
@endsection
