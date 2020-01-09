<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 *
 * dataStaffSalaryBasic
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
?>
@extends('ad3d.components.container.container-6')
@section('qc_ad3d_container_content')
    <div class="qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <h3>LƯƠNG CƠ BẢN</h3>
            </div>
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form class="frmEdit" name="frmEdit" role="form" method="post"
                      action="{!! route('qc.ad3d.system.salary.edit.post', $dataStaffSalaryBasic->salaryBasicId()) !!}">
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
                         @if($mobileStatus) style="padding: 0 0;" @endif>
                        <div class="frm_notify form-group qc-color-red"></div>
                    </div>
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group form-group-sm">
                            <label >Nhân viên</label>
                            <input type="text" class="form-control" readonly value="{!! $dataStaffSalaryBasic->staff->fullName() !!}">
                        </div>
                        <div class="form-group form-group-sm">
                            <label for="exampleInputFile">Lương Cũ (VND/Tháng)</label>
                            <input type="text" class="form-control" readonly value="{!! $dataStaffSalaryBasic->salary() !!}">
                        </div>
                        <div class="form-group form-group-sm">
                            <label for="exampleInputFile">Lương Mới (VND/Tháng) <i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                            <input type="text" class="form-control" name="txtNewSalary"  placeholder="Nhập Lương mới" value="{!! $dataStaffSalaryBasic->salary() !!}">
                        </div>
                    </div>
                    <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <button type="button" class="qc_save btn btn-sm btn-primary">Lưu</button>
                        <button type="button" class="qc_ad3d_container_close btn btn-sm btn-default">Đóng</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
