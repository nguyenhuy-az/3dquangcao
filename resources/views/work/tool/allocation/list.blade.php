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
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-6 col-lg-6">
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr style="background-color: black;color: yellow;">
                                <th class="text-center" style="width: 20px;">STT</th>
                                <th>Dụng cụ</th>
                                <th class="text-center">
                                    Ngày giao
                                </th>
                                <th>Ảnh</th>
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
                                        <td class="text-center">
                                            {!! $hFunction->convertDateDMYFromDatetime($detailDate) !!}
                                        </td>
                                        <td>
                                            @if ($hFunction->checkEmpty($detailImage))
                                                {{--giao lan dau--}}
                                                {{--lay thong tin hinh anh nhap kho--}}
                                                <?php
                                                $dataImport = $toolAllocationDetail->companyStore->import;
                                                $dataImportImage = $dataImport->importImageInfoOfImport();
                                                ?>
                                                @if($hFunction->checkCount($dataImportImage))
                                                    @foreach($dataImportImage as $importImage)
                                                        <div style="position: relative; float: left; width: 70px; max-height: 70px; background-color: grey;">
                                                            <a class="qc-link">
                                                                <img style="max-width: 100%; max-height: 100%;"
                                                                     src="{!! $importImage->pathFullImage($importImage->name()) !!}">
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            @else
                                                Ảnh bàn giao
                                            @endif
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
                                                <a class="qc-link-green-bold"
                                                   href="{!! route('qc.work.tool.allocation.return.get',"$allocationId/$detailId") !!}">
                                                    Giao lại
                                                </a>
                                            @else
                                                <em class="qc-color-grey">Đã trả</em> <br/>
                                                @if($toolReturn->checkConfirm())
                                                    @if($toolReturn->checkAcceptStatus())
                                                        <em style="background-color: red; color: yellow;">
                                                            Không được chấp nhận
                                                        </em>
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
