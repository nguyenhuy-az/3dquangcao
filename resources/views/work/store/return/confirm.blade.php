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
$allocationId = $dataToolAllocation->allocationId();
$dataToolReturn = $dataToolAllocation->toolReturnUnConfirmInfo($allocationId);
?>
@extends('components.container.container-8')
@section('qc_container_content')
    <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <h3 style="color: red;">XÁC NHẬN TRẢ ĐỒ NGHỀ</h3>
        <span style="padding: 4px; background-color: red; color: yellow;">{!! $dataToolAllocation->companyStaffWork->staff->fullName() !!}</span>
        <form id="frmWorkStoreReturnConfirm" role="form" name="frmWorkStoreReturnConfirm" method="post"
              enctype="multipart/form-data" action="{!! route('qc.work.store.return.confirm.post', $allocationId) !!}">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <tr style="background-color: black;color: yellow;">
                        <th class="text-center"  style="width: 70px;">
                            Đồng ý
                        </th>
                        <th>Đồ nghề</th>
                        <th>
                            Ngày trả
                        </th>
                        <th>
                            Ảnh bàn giao
                        </th>
                        <th>
                            Ảnh trả
                        </th>
                    </tr>
                    @if($hFunction->checkCount($dataToolReturn))
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
                            ?>
                            {{--chi tra nhung dung cu con lai--}}
                            <tr>
                                <td class="text-center">
                                    <div class="form-group" style="margin: 0 !important; ">
                                        <input type="hidden" class="txtToolReturn form-control" checked="checked" style="margin: 0;"
                                               name="txtToolReturn[]" value="{!! $returnId !!}">
                                        <input type="checkbox" class="txtToolReturnAccept form-control" checked="checked" style="margin: 0;"
                                               name="txtToolReturnAccept[]" value="{!! $returnId !!}">
                                    </div>
                                </td>
                                <td>
                                    {!!  $dataCompanyStore->name() !!}
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
                                <td>
                                    <div style="width: 70px; max-height: 70px;">
                                        <a class="qc-link">
                                            <img style="max-width: 100%; max-height: 100%;"
                                                 src="{!! $toolReturn->pathFullImage($returnImage) !!}">
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        {{--<tr style="background-color:whitesmoke;">
                            <td>
                                <div class="form-group" style="margin: 0 !important; ">
                                    <input type="checkbox" class="txtCheckAll form-control" name="txtCheckAll" checked="checked" style="margin: 0;"
                                           name="txtToolReturn[]" value="{!! $returnId !!}">
                                </div>
                            </td>
                            <td colspan="4">
                                <label style="color: red; font-size: 20px;">
                                    Chọn tất cả
                                </label>
                            </td>
                        </tr>--}}
                        <tr>
                            <td colspan="5">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <a class="qc_save btn btn-sm btn-primary">
                                    XÁC NHẬN
                                </a>
                                <a class="qc_container_close btn btn-sm btn-default">
                                    Đóng
                                </a>
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
        </form>
    </div>
@endsection
