<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 *
 * dataCompany
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
# thong tin nhan vien dang nhap
$dataStaff = $modelStaff->loginStaffInfo();
# thong tin cong viec duoc giao
$dataWorkAllocation = $dataStaff->workAllocationActivityOfStaffReceive();
# ma cham cong
$timekeepingProvisionalId =  $dataTimekeepingProvisional->timekeepingProvisionalId();
?>
@extends('components.container.container-6')
@section('qc_container_content')
    <div class="qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
                <h3 style="color: red;">ẢNH BÁO CÁO CÔNG VIỆC</h3>
            </div>
            <div class="qc-padding-top-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form class="qc_frm_timekeeping_image_add form" name="qc_frm_timekeeping_image_add" role="form" method="post" enctype="multipart/form-data"
                      action="{!! route('qc.work.timekeeping.timekeeping_provisional_image.add.post') !!}">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <span style="padding: 5px; background-color: red; color: yellow;">PHẢI CÓ ẢNH BÁO CÁO</span>
                        </div>
                    </div>
                    <div class="row" style="padding-top: 5px; padding-bottom: 5px; border-bottom: 1px solid brown">
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <label>Ảnh báo cáo 1:</label>
                            <input type="file" class="txtTimekeepingImage_1 form-control"
                                   name="txtTimekeepingImage_1">
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <label>Sản phẩm: </label><br/>
                            <select class="cbWorkAllocation_1 form-control" name="cbWorkAllocation_1">
                                @if(count($dataWorkAllocation) > 0)
                                    <option value="0">Chọn sản phẩm</option>
                                    @foreach($dataWorkAllocation as $workAllocation)
                                        <option value="{!! $workAllocation->allocationId() !!}">
                                            {!! $workAllocation->product->productType->name() !!} -
                                            ({!! $workAllocation->product->order->name() !!})
                                        </option>
                                    @endforeach
                                @else
                                    <option value="0">Không có sản phẩm</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="row" style="padding-top: 5px; padding-bottom: 5px; border-bottom: 1px solid brown">
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <label>Ảnh ảnh báo 2:</label>
                            <input class="txtTimekeepingImage_2 form-control" type="file"
                                   name="txtTimekeepingImage_2">
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <label>Sản phẩm: </label><br/>
                            <select class="cbWorkAllocation_2 form-control" name="cbWorkAllocation_2"
                                    style="height: 34px;">
                                @if(count($dataWorkAllocation) > 0)
                                    <option value="0">Chọn sản phẩm</option>
                                    @foreach($dataWorkAllocation as $workAllocation)
                                        <option value="{!! $workAllocation->allocationId() !!}">
                                            {!! $workAllocation->product->productType->name() !!} -
                                            ({!! $workAllocation->product->order->name() !!})
                                        </option>
                                    @endforeach
                                @else
                                    <option value="0">Không có sản phẩm</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="row" style="padding-top: 5px; padding-bottom: 5px; border-bottom: 1px solid brown;">
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <label>Ảnh báo cáo 3:</label>
                            <input type="file" class="txtTimekeepingImage_3 form-control"
                                   name="txtTimekeepingImage_3">
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <label>Sản phẩm: </label><br/>
                            <select class="cbWorkAllocation_3 form-control" name="cbWorkAllocation_3"
                                    style="height: 34px;">
                                @if(count($dataWorkAllocation) > 0)
                                    <option value="0">Chọn sản phẩm</option>
                                    @foreach($dataWorkAllocation as $workAllocation)
                                        <option value="{!! $workAllocation->allocationId() !!}">
                                            {!! $workAllocation->product->productType->name() !!} -
                                            ({!! $workAllocation->product->order->name() !!})
                                        </option>
                                    @endforeach
                                @else
                                    <option value="0">Không có sản phẩm</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="frm_notify text-center form-group form-group-sm qc-color-red"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="text-center form-group form-group-sm">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <input type="hidden" name="txtTimekeepingProvisional"
                                       value="{!! $timekeepingProvisionalId !!}">
                                <button type="button" class="qc_save btn btn-sm btn-primary">GỬI</button>
                                <button type="reset" class="btn btn-sm btn-default">Nhập lại</button>
                                <button type="button" class="qc_container_close btn btn-sm btn-default">Đóng</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
