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
$detailId = $dataToolPackageAllocationDetail->detailId();
?>
@extends('components.container.container-6')
@section('qc_container_content')
    <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <form id="qcFrmToolPackageAllocationReport" class="form" name="qcFrmToolPackageAllocationReport"
              role="form" method="post" enctype="multipart/form-data"
              action="{!! route('qc.work.tool.package_allocation.report.post', $detailId) !!}">
            <div class="row">
                <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <h3 style="color: red;">BÁO CÁO ĐỒ NGHỀ</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    @if (Session::has('notifyAdd'))
                        <div class="form-group text-center qc-color-red">
                            {!! Session::get('notifyAdd') !!}
                            <?php
                            Session::forget('notifyAdd');
                            ?>
                        </div>
                    @endif
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group form-group-sm">
                        <label>Trạng thái sử dụng:</label>
                        <select class="form-control cbReportUseStatus" name="cbReportUseStatus">
                            <option value="2">
                                Bị Hư
                            </option>
                            <option value="3">
                                Bị Mất
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group form-group-sm">
                        <label>Lý do:</label>
                        <textarea class="form-control txtReportNote" name="txtReportNote"></textarea>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group form-group-sm">
                        <label>Hình ảnh:</label>
                        <input type="file" class="txtReportImage" name="txtReportImage">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="text-center form-group form-group-sm">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <button type="button" class="qc_save btn btn-sm btn-primary">
                            BÁO CÁO
                        </button>
                        <a class="qc_container_close btn btn-sm btn-default">
                            Đóng
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
