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
$currentDay = (int)date('d');
$currentMonth = (int)date('m');
$currentYear = (int)date('Y');
?>
@extends('work.index')
@section('qc_work_body')
    <div class="row">
        <div class="qc_work_import_add qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed #C2C2C2;">
                    <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6">
                        <a class="qc-link-red" onclick="qc_main.page_back();">
                            <i class="glyphicon glyphicon-backward"></i> Trở lại
                        </a>
                        <h4>THÔNG TIN MUA</h4>
                    </div>
                </div>
            </div>
            <form id="frm_work_import_add" role="form" method="post" enctype="multipart/form-data"
                  action="{!! route('qc.work.import.add.post') !!}">
                <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    @if (Session::has('notifyAddImport'))
                        <div class="form-group text-center qc-color-red">
                            {!! Session::get('notifyAddImport') !!}
                            <?php
                            Session::forget('notifyAddImport');
                            ?>
                        </div>
                    @endif
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="row">
                            <div class="form-group form-group-sm col-xs-12 col-sm-12 col-md-12 col-lg-12"
                                 style="margin: 0;">
                                <label class=" control-label">Ngày:</label>
                                <select name="cbImportDay" style="height: 25px;">
                                    @for($i = 1;$i<= 31; $i++)
                                        <option value="{!! $i !!}"
                                                @if($i == $currentDay) selected="selected" @endif>
                                            {!! $i !!}
                                        </option>
                                    @endfor
                                </select>
                                <span>/</span>
                                <select name="cbImportMonth" style="height: 25px;">
                                    @for($month = 1;$month<= 12; $month++)
                                        <option value="{!! $month !!}"
                                                @if($month == $currentMonth) selected="selected" @endif>
                                            {!! $month !!}
                                        </option>
                                    @endfor
                                </select>
                                <span>/</span>
                                <select name="cbImportYear" style="height: 25px;">
                                    <option value="{!! $currentYear !!}">{!! $currentYear !!}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    {{-- Ảnh hóa đơn nhập --}}
                    <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                                 style="color: red;border-bottom: 1px solid brown; background-color: whitesmoke;">
                                <i class="glyphicon glyphicon-pencil qc-color-green"></i>
                                <span class="qc-font-size-20">Ảnh hóa đơn</span>
                            </div>
                        </div>
                        <div id="qc_work_import_add_image_wrap" class="row qc-padding-top-10">
                            @include('work.pay.import.add-image')
                        </div>
                    </div>
                    <div class="row">
                        <div class="qc-border-none text-left col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <a class="qc_work_import_add_image qc-link-green"
                               data-href="{!! route('qc.work.import.add.image.get') !!}">
                                <i class="glyphicon glyphicon-plus"></i>
                                Thêm ảnh
                            </a>
                        </div>
                    </div>

                    {{-- Vật tư --}}
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                                 style="color: red;border-bottom: 1px solid black; background-color: whitesmoke;">
                                <i class="glyphicon glyphicon-pencil qc-color-green"></i>
                                <span class="qc-font-size-20">Vật Tư</span>
                            </div>
                        </div>
                        <div id="qc_work_import_add_supplies_wrap" class="row">
                            @include('work.pay.import.add-supplies', compact('dataSupplies'))
                        </div>
                    </div>
                    <div class="row">
                        <div class="qc-padding-top-5 qc-border-none text-left col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <a class="qc_work_import_add_supplies qc-link-green"
                               data-href="{!! route('qc.work.import.add.supplies.get') !!}">
                                <i class="glyphicon glyphicon-plus"></i>
                                Thêm vật tư
                            </a>
                        </div>
                    </div>

                    {{-- Dụng cụ --}}
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                                 style="color: red; border-bottom: 1px solid blue; background-color: whitesmoke;">
                                <i class="glyphicon glyphicon-pencil qc-color-green"></i>
                                <span class="qc-font-size-20">Dụng cụ</span>
                            </div>
                        </div>
                        <div id="qc_work_import_add_tool_wrap" class="row">
                            @include('work.pay.import.add-tool', compact('dataTool'))
                        </div>
                    </div>
                    <div class="row">
                        <div class="qc-padding-top-5 qc-border-none text-left col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <a class="qc_work_import_add_tool qc-link-green"
                               data-href="{!! route('qc.work.import.add.tool.get') !!}">
                                <i class="glyphicon glyphicon-plus"></i>
                                Thêm dụng cụ
                            </a>
                        </div>
                    </div>

                    {{-- Dụng cụ --}}
                    <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                                 style="color: red; border-bottom: 1px solid red;background-color: whitesmoke;">
                                <i class="glyphicon glyphicon-pencil qc-color-green"></i>
                                <span class="qc-font-size-20">Vật liệu mới</span>
                            </div>
                        </div>
                        <div id="qc_work_import_supplies_tool_new_wrap" class="row">
                            @include('work.pay.import.add-supplies-tool-new')
                        </div>
                    </div>
                    <div class="row">
                        <div class="qc-padding-top-5 qc-border-none text-left col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <a class="qc_work_import_supplies_tool_add_new qc-link-green"
                               data-href="{!! route('qc.work.import.add.supplies_tool.get') !!}">
                                <i class="glyphicon glyphicon-plus"></i>
                                Thêm vật liệu mới
                            </a>
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
