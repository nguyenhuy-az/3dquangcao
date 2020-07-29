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
$hrefIndex = route('qc.work.store.return.get');
$currentMonth = $hFunction->currentMonth();

?>
@extends('work.store.return.index')
@section('qc_work_store_return_body')
    <div class="row qc_work_store_return_wrap">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-6 col-lg-6">
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
                                <td></td>
                                <td style="padding: 0">
                                    <select class="cbConfirmStatusFilter form-control" name="cbConfirmStatusFilter"
                                            data-href="{!! $hrefIndex !!}">
                                        <option value="100" @if($cbConfirmStatusFilter == 0) selected="selected" @endif>
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
                            @if($hFunction->checkCount($dataToolReturn))
                                <?php $n_o = 0; ?>
                                @foreach($dataToolReturn as $toolReturn)
                                    <?php
                                    $returnId = $toolReturn->returnId();
                                    $returnDate = $toolReturn->returnDate();
                                    $returnImage = $toolReturn->image();
                                    # thong tin duoc giao
                                    $dataToolAllocationDetail = $toolReturn->toolAllocationDetail;
                                    $allocationId = $dataToolAllocationDetail->allocationId();
                                    $detailImage = $dataToolAllocationDetail->image();
                                    # thong tin kho
                                    $dataCompanyStore = $dataToolAllocationDetail->companyStore;
                                    # thong tin nha vien tra
                                    $dataStaffReturn = $toolReturn->toolAllocationDetail->toolAllocation->companyStaffWork->staff;
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
                                            @if ($hFunction->checkEmpty($detailImage))
                                                {{--giao lan dau--}}
                                                {{--lay thong tin hinh anh nhap kho--}}
                                                <?php
                                                $dataImport = $dataToolAllocationDetail->companyStore->import;
                                                $dataImportImage = $dataImport->importImageInfoOfImport();
                                                ?>
                                                @if($hFunction->checkCount($dataImportImage))
                                                    @foreach($dataImportImage as $importImage)
                                                        <div style="position: relative; float: left; width: 70px; max-height: 70px; background-color: grey;">
                                                            <a class="qc_view_image_get qc-link"
                                                               data-href="{!! route('qc.work.store.return.import_image.get',$importImage->imageId()) !!}">
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
                                        <td>
                                            <div style="width: 70px; max-height: 70px;">
                                                <a class="qc_view_image_get qc-link" data-href="{!! route('qc.work.store.return.return_image.get',$returnId) !!}">
                                                    <img style="max-width: 100%; max-height: 100%;"
                                                         src="{!! $toolReturn->pathFullImage($returnImage) !!}">
                                                </a>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @if($toolReturn->checkConfirm())
                                                @if($toolReturn->checkAcceptStatus())
                                                    <em>Đồng ý</em>
                                                @else
                                                    <em style="color: red;">Không đồng ý</em>
                                                @endif
                                            @else
                                                <a class="qc_confirm_get qc-link-green-bold"
                                                   data-href="{!! route('qc.work.store.return.confirm.get', $allocationId) !!}">
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
