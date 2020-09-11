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
$allocationId = $dataToolPackageAllocation->allocationId();
$dataToolPackageAllocationReturn = $dataToolPackageAllocation->toolReturnUnConfirmInfo($allocationId);
?>
@extends('components.container.container-10')
@section('qc_container_content')
    <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <h3 style="color: red;">XÁC NHẬN TRẢ ĐỒ NGHỀ</h3>
        <span style="padding: 4px; background-color: red; color: yellow;">{!! $dataToolPackageAllocation->companyStaffWork->staff->fullName() !!}</span>
        <form id="frmStoreToolPackageAllocationReturnConfirm" role="form" name="frmStoreToolPackageAllocationReturnConfirm" method="post"
              enctype="multipart/form-data" action="{!! route('qc.work.store.tool_package_allocation_return.confirm.post', $allocationId) !!}">
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
                        <th class="text-center">
                            Trạng thái sử dụng
                        </th>
                    </tr>
                    @if($hFunction->checkCount($dataToolPackageAllocationReturn))
                        @foreach($dataToolPackageAllocationReturn as $toolPackageAllocationReturn)
                            <?php
                            $returnId = $toolPackageAllocationReturn->returnId();
                            $returnDate = $toolPackageAllocationReturn->returnDate();
                            $returnImage = $toolPackageAllocationReturn->image();

                            # thong tin duoc giao
                            $dataToolPackageAllocationDetail = $toolPackageAllocationReturn->toolPackageAllocationDetail;
                            $detailImage = $dataToolPackageAllocationDetail->image();
                            # thong tin kho
                            $dataCompanyStore = $dataToolPackageAllocationDetail->companyStore;
                            ?>
                            {{--chi tra nhung dung cu con lai--}}
                            <tr>
                                <td class="text-center">
                                    <div class="form-group" style="margin: 0 !important; ">
                                        <input type="hidden" class="txtToolPackageAllocationReturn form-control" checked="checked" style="margin: 0;"
                                               name="txtToolPackageAllocationReturn[]" value="{!! $returnId !!}">
                                        <input type="checkbox" class="txtToolPackageAllocationReturnAccept form-control" checked="checked" style="margin: 0;"
                                               name="txtToolPackageAllocationReturnAccept[]" value="{!! $returnId !!}">
                                    </div>
                                </td>
                                <td>
                                    {!!  $dataCompanyStore->name() !!}
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
                                        <a class="qc-link">
                                            <img style="max-width: 100%; max-height: 100%;"
                                                 src="{!! $toolPackageAllocationReturn->pathFullImage($returnImage) !!}">
                                        </a>
                                    </div>
                                </td>
                                <td class="text-center">
                                    {!! $toolPackageAllocationReturn->labelUseStatus($returnId) !!}
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
                            <td colspan="6">
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
