<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 8:57 AM
 *
 * modelStaff
 */
$dataStaffLogin = $modelStaff->loginStaffInfo();
$companyLoginId = $dataStaffLogin->companyId();
$staffLevel = $dataStaffLogin->level();
# bao cham cong theo cong ty dang nhap
$totalNewTimekeepingProvisional = $modelStaff->totalNewTimekeepingProvisional($companyLoginId);
# xin di tre chưa duyet theo cong ty dang nhap
$totalNewLicenseOffWork = $modelStaff->totalNewLicenseOffWork($companyLoginId);
# xin nghỉ chưa duyet theo cong ty dang nhap
$totalNewLicenseLateWork = $modelStaff->totalNewLicenseLateWork($companyLoginId);
# yeu cau ung luong chưa duyet theo cong ty dang nhap
$totalNewSalaryBeforePayRequest = $modelStaff->totalNewSalaryBeforePayRequest($companyLoginId);
$manageDepartmentStatus = true;// $dataStaffLogin->checkManageDepartment();
?>
@extends('ad3d.index')
@section('titlePage')
    Trang chủ
@endsection
@section('qc_ad3d_body')
    @if($staffLevel <= 3)
        <div class="row">
            <div class="qc-padding-top-20 col-xs-12 col-sm-4 col-md-4 col-lg-3">
                <div class="list-group">
                    <a href="#" class="list-group-item active">
                        Thông tin mới
                    </a>
                    <a class="qc-link list-group-item" href="{!! route('qc.ad3d.work.time-keeping-provisional.get') !!}" >
                        <span class="qc-color-red badge" style="background:none;">
                            [ {!! $totalNewTimekeepingProvisional !!} ]
                        </span>
                        Chấm công
                    </a>
                    <a class="qc-link list-group-item" href="{!! route('qc.ad3d.work.off-work.get') !!}" >
                        <span class="qc-color-red badge" style="background:none;">
                            [ {!! $totalNewLicenseOffWork !!} ]
                        </span>
                        Xin nghỉ
                    </a>
                    <a class="qc-link list-group-item" href="{!! route('qc.ad3d.work.late-work.get') !!}" >
                        <span class="qc-color-red badge" style="background:none;">
                            [ {!! $totalNewLicenseLateWork !!} ]
                        </span>
                        Xin trễ
                    </a>
                    <a class="qc-link list-group-item" href="{!! route('qc.ad3d.salary.before_pay_request.get') !!}">
                        <span class="qc-color-red badge" style="background:none;">
                            [ {!! $totalNewSalaryBeforePayRequest !!} ]
                        </span>
                        Xin ứng lương
                    </a>
                </div>

            </div>
            <div class="col-xs-12 col-sm-8 col-md-8 col-lg-9">
                @if($manageDepartmentStatus)
                    <div class="ac-ad3d-panel col-xs-12 col-sm-4 col-md-4 col-lg-4">
                        <div class="ac-ad3d-panel-icon-wrap">
                            <a class="ac-ad3d-panel-icon-link" href="{!! route('qc.ad3d.system.staff.get') !!}">
                                <i class="glyphicon glyphicon-user" style="font-size: 20px; color: grey;"></i><br>
                                Nhân sự & Hệ thống
                            </a>
                        </div>
                    </div>
                @endif
                <div class="ac-ad3d-panel col-xs-12 col-sm-6 col-md-4 col-lg-4">
                    <div class="ac-ad3d-panel-icon-wrap">
                        <a class="ac-ad3d-panel-icon-link" href="{!! route('qc.ad3d.finance.order-payment.get') !!}">
                            <i class="glyphicon glyphicon-usd" style="font-size: 20px; color: grey;"></i><br>
                            Tài chính
                        </a>
                    </div>
                </div>
                <div class="ac-ad3d-panel text-center col-xs-12 col-sm-6 col-md-4 col-lg-4">
                    <div class="ac-ad3d-panel-icon-wrap">
                        <a class="ac-ad3d-panel-icon-link" href="{!! route('qc.ad3d.work.time-keeping.get') !!}">
                            <i class="glyphicon glyphicon-list-alt" style="font-size: 20px; color: grey;"></i><br>
                            Làm việc
                        </a>
                    </div>
                </div>
                <div class="ac-ad3d-panel col-xs-12 col-sm-6 col-md-4 col-lg-4">
                    <div class="ac-ad3d-panel-icon-wrap">
                        <a class="ac-ad3d-panel-icon-link" href="{!! route('qc.ad3d.order.order.get') !!}">
                            <i class="glyphicon glyphicon-shopping-cart" style="font-size: 20px; color: grey;"></i><br>
                            Đơn hàng & Sản phẩm
                        </a>
                    </div>
                </div>
                <div class="ac-ad3d-panel col-xs-12 col-sm-6 col-md-4 col-lg-4">
                    <div class="ac-ad3d-panel-icon-wrap">
                        <a class="ac-ad3d-panel-icon-link" href="{!! route("qc.ad3d.store.import.get") !!}">
                            <i class="glyphicon glyphicon-hdd" style="font-size: 30px; color: grey;"></i><br>
                            Dụng cụ - Vật Tư
                        </a>
                    </div>
                </div>
                <div class="ac-ad3d-panel col-xs-12 col-sm-6 col-md-4 col-lg-4">
                    <div class="ac-ad3d-panel-icon-wrap">
                        <a class="ac-ad3d-panel-icon-link" href="{!! route('qc.ad3d.statistic.revenue.company.get') !!}">
                            <i class="glyphicon glyphicon-wrench" style="font-size: 20px; color: grey;"></i><br>
                            Thống kê
                        </a>
                    </div>
                </div>
                <div class="ac-ad3d-panel col-xs-12 col-sm-6 col-md-4 col-lg-4">
                    <div class="ac-ad3d-panel-icon-wrap">
                        <a class="ac-ad3d-panel-icon-link" href="{!! route('qc.ad3d.login.exit') !!}">
                            <i class="glyphicon glyphicon-lock" style="font-size: 20px; color: grey;"></i><br>
                            Đăng xuất
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="text-align: center; color: red">
            <span>
                Bạn không có quyền truy cập
            </span>
            </div>
        </div>
    @endif
@endsection