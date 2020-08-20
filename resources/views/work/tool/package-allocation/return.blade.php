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
?>
@extends('work.tool.allocation.index')
@section('qc_work_tool_private_body')
    <div class="row qc_work_tool_private_wrap">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-8 col-lg-8">
            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <h4 style="color: red;">BÀN GIAO LẠI ĐỒ NGHỀ</h4>
                </div>
            </div>
            {{-- chi tiêt --}}
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form id="frmWorkToolPrivateReturn" role="form" name="frmWorkToolPrivateReturn" method="post"
                      enctype="multipart/form-data"
                      action="{!! route('qc.work.tool.allocation.return.post') !!}">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <tr style="background-color: black;color: yellow;">
                                    <th class="text-center" style="width: 20px;"></th>
                                    <th>Dụng cụ</th>
                                    <th>Ảnh được giao</th>
                                    <th>Ảnh hiện tại</th>
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
                                        ?>
                                        <tr class="@if($selectedDetailId == $storeId) info @endif">
                                            <td class="text-center" style="padding: 0;">
                                                <div class="form-group" style="margin: 0;">
                                                    <input type="checkbox" class="txtAllocationDetail form-control"
                                                           name="txtAllocationDetail[]" style="margin: 0;"
                                                           @if($selectedDetailId == $detailId) checked="checked"
                                                           @endif value="{!! $detailId !!}">
                                                </div>
                                            </td>
                                            <td>
                                                {!!  $storeName !!}
                                            </td>
                                            <td>
                                                <a class="qc_view_image_get qc-link"
                                                   data-href="{!! route('qc.work.tool.allocation.image.view',$detailId) !!}">
                                                    <img style="width: 70px; height: auto;"
                                                         src="{!! $toolAllocationDetail->pathFullImage($detailImage) !!}">
                                                </a>
                                            </td>
                                            <td>
                                                <div class="form-group" style="margin: 0;">
                                                    <input id="txtReturnImage_{!! $detailId !!}" type="file"
                                                           name="txtReturnImage_{!! $detailId !!}">
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="4" style="background-color:whitesmoke;">
                                            <div class="checkbox" style="margin: 0;">
                                                <label style="color: red; font-size: 20px;">
                                                    <input class="txtCheckAll" type="checkbox" name="txtCheckAll">
                                                    Giao hết
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">
                                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                            <a class="qc_save btn btn-sm btn-primary">
                                                GIAO
                                            </a>
                                            <button class="btn btn-sm btn-default" type="reset">
                                                Nhập lại
                                            </button>
                                            <a class="btn btn-sm btn-default" onclick="qc_main.page_back();">
                                                Về trang trước
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
