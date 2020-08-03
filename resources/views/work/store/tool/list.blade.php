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
//$companyId = $dataCompany->companyId();
$hrefIndex = route('qc.work.store.tool.get');
$currentMonth = $hFunction->currentMonth();
//$dataToolAllocationDetail = null;
?>
@extends('work.store.tool.index')
@section('qc_work_store_tool_body')
    <div class="row qc_work_store_tool_wrap">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-8 col-lg-8">
            {{-- chi tiêt --}}
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr>
                                <td style="padding: 0" colspan="5">
                                    <select class="cbToolTypeFilter form-control" name="cbToolTypeFilter"
                                            data-href="{!! $hrefIndex !!}">
                                        <option value="1" @if($typeFilter == 1) selected="selected" @endif>
                                            Dùng chung
                                        </option>
                                        <option value="2" @if($typeFilter == 2) selected="selected" @endif>
                                            Dùng cấp phát
                                        </option>
                                    </select>
                                </td>
                            </tr>
                            <tr style="background-color: black;color: yellow;">
                                <th class="text-center" style="width: 20px;">STT</th>
                                <th>Dụng cụ</th>
                                <th>
                                    Hình ảnh
                                </th>
                                <th class="text-center">
                                    Trạng thái sử dụng
                                </th>
                                <th class="text-center">
                                    Đã phát
                                </th>
                            </tr>
                            <tr>
                                <td></td>
                                <td style="padding: 0;">
                                    <select class="cbToolFilter form-control" name="cbToolFilter"
                                            data-href="{!! $hrefIndex !!}">
                                        <option value="0" @if($typeFilter == 0) selected="selected" @endif>Tất cả
                                        </option>
                                        @if($hFunction->checkCount($dataTool))
                                            @foreach($dataTool as $tool)
                                                <option value="{!! $tool->toolId() !!}"
                                                        @if($toolFilter == $tool->toolId()) selected="selected" @endif>
                                                    {!! $tool->name()  !!}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @if($hFunction->checkCount($dataCompanyStore))
                                <?php $n_o = 0; ?>
                                @foreach($dataCompanyStore as $companyStore)
                                    <?php
                                    $storeId = $companyStore->storeId();
                                    $storeName = $companyStore->name();
                                    $dataTool = $companyStore->tool;
                                    $n_o = $n_o + 1;
                                    # thong tin nhap kho
                                    $dataImport = $companyStore->import;
                                    $dataImportImage = $dataImport->importImageInfoOfImport();
                                    ?>
                                    <tr class="@if($n_o%2) info @endif">
                                        <td class="text-center">
                                            {!! $n_o !!}
                                        </td>
                                        <td>
                                            {!!  $storeName !!}
                                        </td>
                                        <td>
                                            {{--dung cu cap phat nhan vien--}}
                                            @if($dataTool->checkPrivateType())
                                                {{--co cap phat--}}
                                                <?php
                                                # lay thong tin giao sau cung
                                                $dataToolAllocationDetail = $companyStore->toolAllocationDetailLastInfo();
                                                ?>
                                                @if($hFunction->checkCount($dataToolAllocationDetail))
                                                    <?php
                                                    $detailImage = $dataToolAllocationDetail->image();
                                                    # lay thong tin tra sau cung cua lan giao
                                                    $dataToolReturn = $dataToolAllocationDetail->lastInfoOfToolReturn();
                                                    ?>
                                                    @if($hFunction->checkCount($dataToolReturn))
                                                        <div style="width: 70px; max-height: 70px;">
                                                            <a class="qc_view_image_get qc-link"
                                                               data-href="{!! route('qc.work.store.tool.return_image.get',$dataToolReturn->returnId()) !!}">
                                                                <img style="max-width: 100%; max-height: 100%;"
                                                                     src="{!! $dataToolReturn->pathFullImage($dataToolReturn->image()) !!}">
                                                            </a>
                                                        </div>
                                                    @else
                                                        @if($hFunction->checkCount($dataImportImage))
                                                            @foreach($dataImportImage as $importImage)
                                                                <div style="position: relative; float: left; width: 70px; max-height: 70px; background-color: grey;">
                                                                    <a class="qc_view_image_get qc-link"
                                                                       data-href="{!! route('qc.work.store.tool.import_image.get',$importImage->imageId()) !!}">
                                                                        <img style="max-width: 100%; max-height: 100%;"
                                                                             src="{!! $importImage->pathFullImage($importImage->name()) !!}">
                                                                    </a>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    @endif

                                                @else
                                                    @if($hFunction->checkCount($dataImportImage))
                                                        @foreach($dataImportImage as $importImage)
                                                            <div style="position: relative; float: left; width: 70px; max-height: 70px; background-color: grey;">
                                                                <a class="qc_view_image_get qc-link"
                                                                   data-href="{!! route('qc.work.store.tool.import_image.get',$importImage->imageId()) !!}">
                                                                    <img style="max-width: 100%; max-height: 100%;"
                                                                         src="{!! $importImage->pathFullImage($importImage->name()) !!}">
                                                                </a>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                @endif
                                            @else
                                                {{--do nghe dung chung--}}
                                                @if($hFunction->checkCount($dataImportImage))
                                                    @foreach($dataImportImage as $importImage)
                                                        <div style="position: relative; float: left; width: 70px; max-height: 70px; background-color: grey;">
                                                            <a class="qc_view_image_get qc-link"
                                                               data-href="{!! route('qc.work.store.tool.import_image.get',$importImage->imageId()) !!}">
                                                                <img style="max-width: 100%; max-height: 100%;"
                                                                     src="{!! $importImage->pathFullImage($importImage->name()) !!}">
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {!! $companyStore->labelUseStatus() !!}
                                        </td>
                                        <td class="text-center">
                                            @if($dataTool->checkPrivateType())
                                                @if($hFunction->checkCount($dataToolAllocationDetail))
                                                    {{--con dang giao--}}
                                                    @if($dataToolAllocationDetail->checkActivity())
                                                        {!! $dataToolAllocationDetail->toolAllocation->companyStaffWork->staff->fullName() !!}
                                                    @else
                                                        <span>---</span>
                                                    @endif
                                                @else
                                                    <span>---</span>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="5">
                                        Hê thống chưa có đồ nghề
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
