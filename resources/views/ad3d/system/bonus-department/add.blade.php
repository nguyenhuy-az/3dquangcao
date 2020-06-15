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

$departmentId = $dataDepartment->departmentId();
$rankId = $dataRank->rankId();
# ton tai thong tin thuong dang hoat dong
if ($hFunction->checkCount($dataBonusDepartmentActivity)) {
    $percent = $dataBonusDepartmentActivity->percent();
} else {
    $percent = 0;
}
?>
@extends('ad3d.components.container.container-6')
@section('qc_ad3d_container_content')
    <div class="qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <h3>CẬP NHẬT PHẦN TRĂM THƯỞNG</h3>
            </div>
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form class="frmAdd" name="frmAdd" role="form" method="post"
                      action="{!! route('qc.ad3d.system.bonus_department.add.post',"$departmentId/$rankId") !!}">
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="frm_notify text-center form-group qc-color-red"></div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group qc-padding-none">
                                <label>
                                    Bộ phận:
                                    <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                </label>
                                <input type="text" class="form-control" readonly="readonly" name="txtDepartment"
                                       value="{!! $dataDepartment->name() !!}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group qc-padding-none">
                                <label>
                                    Cấp bậc:
                                    <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                </label>
                                <input type="text" class="form-control" readonly name="txtRank"
                                       value="{!! $dataRank->name() !!}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group qc-padding-none">
                                <label>
                                    Phần Trăm:
                                    <em style="color: red;">- Phần trăm trên Tổng giá trị đơn hàng</em>
                                    <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                </label>
                                <input type="number" class="form-control" name="txtPercent"
                                       value="{!! $percent !!}" placeholder="Nhập phần trăm thưởng">
                            </div>
                        </div>
                    </div>
                    <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <button type="button" class="qc_save btn btn-primary btn-sm">Lưu</button>
                        <button type="button" class="qc_ad3d_container_close btn btn-default btn-sm">Đóng</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
