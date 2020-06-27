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
$currentDate = date('d/m/Y', strtotime($hFunction->carbonNow()));
?>
@extends('work.import.index')
@section('qc_work_import_body')
    <div class="row">
        <div class="qc_work_import_add qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <form id="frm_work_import_add" role="form" method="post" enctype="multipart/form-data"
                  action="{!! route('qc.work.import.add.post') !!}">
                <div class="row" style="padding-top: 20px;">
                    @if (Session::has('notifyAddImport'))
                        <div class="form-group text-center qc-color-red">
                            {!! Session::get('notifyAddImport') !!}
                            <?php
                            Session::forget('notifyAddImport');
                            ?>
                        </div>
                    @endif
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 20px;">
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

                    {{-- Ảnh hóa đơn nhập --}}
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 20px;">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <i class="glyphicon glyphicon-pencil qc-color-green"></i>
                                <span class="qc-color-red qc-font-size-16">Ảnh hóa đơn</span>
                            </div>
                        </div>
                        <div class="row">
                            <div id="qc_work_import_add_image_wrap" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                @include('work.pay.import.add-image')
                            </div>
                        </div>
                        <div class="row">
                            <div class="qc-padding-top-5 qc-border-none text-left col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <a class="qc_work_import_add_image qc-link-green qc-font-size-12"
                                   data-href="{!! route('qc.work.import.add.image.get') !!}">
                                    <i class="glyphicon glyphicon-plus"></i>
                                    Thêm ảnh
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Vật tư --}}
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 20px;">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="color: red;">
                                <i class="glyphicon glyphicon-pencil qc-color-green"></i>
                                <span class="qc-font-size-16">Vật Tư</span>
                            </div>
                        </div>
                        <div class="row">
                            <div id="qc_work_import_add_supplies_wrap" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                @include('work.pay.import.add-supplies', compact('dataSupplies'))
                            </div>
                        </div>
                        <div class="row">
                            <div class="qc-padding-top-5 qc-border-none text-left col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <a class="qc_work_import_add_supplies qc-link-green qc-font-size-12"
                                   data-href="{!! route('qc.work.import.add.supplies.get') !!}">
                                    <i class="glyphicon glyphicon-plus"></i>
                                    Thêm vật tư
                                </a>
                            </div>
                        </div>
                    </div>


                    {{-- Dụng cụ --}}
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="color: red;">
                                <i class="glyphicon glyphicon-pencil qc-color-green"></i>
                                <span class="qc-font-size-16">Dụng cụ</span>
                            </div>
                        </div>
                        <div class="row">
                            <div id="qc_work_import_add_tool_wrap" class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                @include('work.pay.import.add-tool', compact('dataTool'))
                            </div>
                        </div>
                        <div class="row">
                            <div class="qc-padding-top-5 qc-border-none text-left col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <a class="qc_work_import_add_tool qc-font-size-12 qc-link-green"
                                   data-href="{!! route('qc.work.import.add.tool.get') !!}">
                                    <i class="glyphicon glyphicon-plus"></i>
                                    Thêm dụng cụ
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <a href="#">
                            <button type="button" class="qc_work_import_save btn btn-sm btn-primary">
                                Thêm
                            </button>
                        </a>
                        <a href="#">
                            <button type="reset" class="qc_work_import_reset btn btn-sm btn-default">
                                Nhập lại
                            </button>
                        </a>
                        <a href="{!! route('qc.work.import.get') !!}">
                            <button type="button" class="qc_ad3d_container_close btn btn-sm btn-default">
                                Đóng
                            </button>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
