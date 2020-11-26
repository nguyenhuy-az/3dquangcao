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
$loginStaffId = $dataStaff->staffId();
$currentDate = date('Y-m-d', strtotime($hFunction->carbonNow()));
?>
@extends('work.import.index')
@section('qc_work_import_body')
    <div class="row">
        <div class="qc_work_import_add qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <form id="frm_work_import_add" role="form" method="post" enctype="multipart/form-data"
                  action="{!! route('qc.work.import.add.post') !!}">
                <div class="row" style="padding-top: 20px;">
                    @if (Session::has('notifyAddImport'))
                        <div class="form-group qc-font-size-14 qc-color-red">
                            {!! Session::get('notifyAddImport') !!}
                            <?php
                            Session::forget('notifyAddImport');
                            ?>
                        </div>
                    @endif
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 10px;">
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                                 style="border-bottom: 1px solid grey;">
                                <i class="glyphicon glyphicon-pencil qc-color-green"></i>
                                <span class="qc-color-red qc-font-size-16">THÔNG TIN MUA VẬT TƯ - DỤNG CỤ</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="form-group form-group-sm qc-margin-none">
                                    <label>Ngày mua:<i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                    <input id="txtImportDate" type="text" name="txtImportDate" class="form-control"
                                           value="{!! $currentDate !!}"
                                           placeholder="Ngày mua">
                                    <script type="text/javascript">
                                        qc_main.setDatepicker('#txtImportDate');
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 10px;">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="color: red;">
                                <i class="glyphicon glyphicon-warning-sign"
                                   style="color: orangered; font-size: 16px;"></i>
                                <span style="background-color: red; padding: 5px; color: yellow;">
                                    Không tách Vật tư / Đồ nghề ra riêng sẽ không được duyệt
                                </span>
                            </div>
                        </div>
                    </div>
                    {{-- Vật tư / dụng cu --}}
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 10px;">
                        {{--<div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="color: red;">
                                <i class="glyphicon glyphicon-pencil qc-color-green"></i>
                                <span class="qc-font-size-16">Vật Tư / Dụng cụ</span>
                            </div>
                        </div>--}}
                        <div class="row">
                            <div id="qc_work_import_add_object_wrap" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                @include('work.import.add-object')
                            </div>
                        </div>
                        <div class="row">
                            <div class="qc-padding-top-5 qc-border-none text-left col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <a class="qc_work_import_add_object qc-link-green qc-font-size-12"
                                   data-href="{!! route('qc.work.import.add.object.get') !!}">
                                    <i class="glyphicon glyphicon-plus"></i>
                                    THÊM VẬT TƯ / DỤNG CỤ
                                </a>
                            </div>
                        </div>
                    </div>
                    {{-- Ảnh hóa đơn nhập --}}
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 10px;">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="color: red;">
                                <i class="glyphicon glyphicon-warning-sign"
                                   style="color: orangered; font-size: 16px;"></i>
                                <span style="background-color: red; padding: 5px; color: yellow;">
                                    KHÔNG CÓ ẢNH HÓA ĐƠN sẽ không được duyệt
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 10px;">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10" style="overflow: hidden;">
                                <label>Chọn ảnh:</label>
                                <input class="txtImportImage"onclick="$(this).click();" type="file" name="txtImportImage" value="">
                            </div>
                            <div class="text-right col-xs-12 col-sm-12 col-md-2 col-lg-2">
                                <a class="qc_delete qc-link-red" data-href="">
                                    <i class="qc-font-size-20 glyphicon glyphicon-remove"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <a href="#">
                            <button type="button" class="qc_work_import_save btn btn-sm btn-primary">
                                LƯU HÓA ĐƠN
                            </button>
                        </a>
                        <a href="#">
                            <button type="reset" class="qc_work_import_reset btn btn-sm btn-default">
                                NHẬP LẠI
                            </button>
                        </a>
                        <a href="{!! route('qc.work.import.get') !!}">
                            <button type="button" class="qc_ad3d_container_close btn btn-sm btn-default">
                                <d>ĐÓNG</d>
                            </button>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
