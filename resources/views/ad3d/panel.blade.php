<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 8:57 AM
 *
 * modelStaff
 */
$hFunction = new Hfunction();
$currentYear = $hFunction->currentYear();
$dataStaffLogin = $modelStaff->loginStaffInfo();
$staffLoginId = $dataStaffLogin->staffId();
$companyLoginId = $dataStaffLogin->companyId($dataStaffLogin->staffId());
$dataCompany = $modelStaff->companyInfoActivity($dataStaffLogin->staffId());
$staffLevel = $dataStaffLogin->level();
# thong tin chi hoat dong chua duyet cua cong ty
$totalPayActivityNotConfirmOfCompany = $dataCompany->totalPayActivityNotConfirmOfCompany($companyLoginId);
$totalPayActivityNotConfirmOfCompany = ($hFunction->checkEmpty($totalPayActivityNotConfirmOfCompany)) ? 0 : $totalPayActivityNotConfirmOfCompany;
# bao cham cong theo cong ty dang nhap
$totalNewTimekeepingProvisional = $dataCompany->totalTimekeepingProvisionalUnconfirmed();
# xin di tre chưa duyet theo cong ty dang nhap
$totalNewLicenseOffWork = $dataCompany->totalLicenseOffWorkUnconfirmed();
# xin nghỉ chưa duyet theo cong ty dang nhap
$totalNewLicenseLateWork = $dataCompany->totalLicenseLateWorkUnconfirmed();
# yeu cau ung luong chưa duyet theo cong ty dang nhap
$totalNewSalaryBeforePayRequest = $dataCompany->totalSalaryBeforePayRequestUnconfirmed();


# so luong ho so tuyen dung chua duyet
$totalJobApplicationUnconfirmed = $dataCompany->totalJobApplicationUnconfirmed();
# ho so phong van chua xac nhan
$totalJobApplicationInterviewUnconfirmed = $dataCompany->totalJobApplicationInterviewUnconfirmed();
#nhan nop tien chua xac nhan cua 1 thu quy
$totalReceiveMoneyUnconfirmed = $dataCompany->sumReceiveMoneyUnconfirmedOfStaff();

# kiem tra bo phan truy cap
$manageDepartmentStatus = $dataStaffLogin->checkManageDepartment();
$businessDepartmentStatus = $dataStaffLogin->checkBusinessDepartment();
?>
@extends('ad3d.index')
@section('titlePage')
    Trang chủ
@endsection
<style type="text/css">
    .qc-ad3d-panel {
        text-align: center;
        height: 60px;
        padding-top: 10px;
        /*line-height: 100px;*/
        border: 1px solid #d7d7d7;
    }

    .qc-ad3d-panel:hover {
        background-color: #d7d7d7;
        color: red;
    }

    .qc-ad3d-panel-icon {
        font-size: 20px;
        margin-bottom: 5px;
    }
</style>
@section('qc_ad3d_body')
    @if($staffLevel <= 3)
        <div class="row" style="padding-top: 20px;">
            @if($manageDepartmentStatus)
                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-3">
                    <div class="list-group">
                        <a href="#" class="list-group-item active">
                            Thông tin mới
                        </a>
                        <a class="qc-link list-group-item"
                           href="{!! route('qc.ad3d.work.time_keeping_provisional.get') !!}">
                        <span class="qc-color-red badge qc-font-size-16" style="background:none;">
                             {!! $totalNewTimekeepingProvisional !!}
                        </span>
                            Chấm công
                            @if($totalNewTimekeepingProvisional > 0)
                                <em style="background-color: red; color: yellow; padding: 3px; border-radius: 5px;">
                                    + New
                                </em>
                            @endif
                        </a>
                        <a class="qc-link list-group-item" href="{!! route('qc.ad3d.finance.pay_activity.get') !!}">
                        <span class="qc-color-red badge qc-font-size-16" style="background:none;">
                            {!! $totalPayActivityNotConfirmOfCompany !!}
                        </span>
                            Duyệt chi hoạt động
                            @if($totalPayActivityNotConfirmOfCompany > 0)
                                <em style="background-color: red; color: yellow; padding: 3px; border-radius: 5px;">+
                                    New</em>
                            @endif
                        </a>
                        <a class="qc-link list-group-item" href="{!! route('qc.ad3d.work.off-work.get') !!}">
                        <span class="qc-color-red badge qc-font-size-16" style="background:none;">
                            {!! $totalNewLicenseOffWork !!}
                        </span>
                            Xin nghỉ
                            @if($totalNewLicenseOffWork > 0)
                                <em style="background-color: red; color: yellow; padding: 3px; border-radius: 5px;">+
                                    New</em>
                            @endif
                        </a>
                        <a class="qc-link list-group-item" href="{!! route('qc.ad3d.work.late-work.get') !!}">
                        <span class="qc-color-red badge qc-font-size-16" style="background:none;">
                            {!! $totalNewLicenseLateWork !!}
                        </span>
                            Xin trễ
                            @if($totalNewLicenseLateWork > 0)
                                <em style="background-color: red; color: yellow; padding: 3px; border-radius: 5px;">+
                                    New</em>
                            @endif
                        </a>
                        <a class="qc-link list-group-item"
                           href="{!! route('qc.ad3d.salary.before_pay_request.get') !!}">
                        <span class="qc-color-red badge qc-font-size-16" style="background:none;">
                            {!! $totalNewSalaryBeforePayRequest !!}
                        </span>
                            Xin ứng lương
                            @if($totalNewSalaryBeforePayRequest > 0)
                                <em style="background-color: red; color: yellow; padding: 3px; border-radius: 5px;">
                                    + New
                                </em>
                            @endif
                        </a>
                        <a class="qc-link list-group-item"
                           href="{!! route('qc.ad3d.finance.transfers.receive.get',"$companyLoginId/0/0/$currentYear/0/$staffLoginId") !!}">
                        <span class="qc-color-red badge qc-font-size-16" style="background:none;">
                            {!! $totalReceiveMoneyUnconfirmed !!}
                        </span>
                            Nhận nộp tiền
                            @if($totalReceiveMoneyUnconfirmed > 0)
                                <em style="background-color: red; color: yellow; padding: 3px; border-radius: 5px;">+
                                    New</em>
                            @endif
                        </a>
                        <a class="qc-link list-group-item" href="{!! route('qc.ad3d.system.job-application.get') !!}">
                        <span class="qc-color-red badge qc-font-size-16" style="background:none;">
                            {!! $totalJobApplicationUnconfirmed !!}
                        </span>
                            Hồ sơ tuyển dụng
                            @if($totalJobApplicationInterviewUnconfirmed > 0)
                                <em style="background-color: red; color: yellow; padding: 3px; border-radius: 5px;">+
                                    New</em>
                            @endif
                        </a>
                        <a class="qc-link list-group-item"
                           href="{!! route('qc.ad3d.system.job-application-interview.get') !!}">
                        <span class="qc-color-red badge qc-font-size-16" style="background:none;">
                             {!! $totalJobApplicationInterviewUnconfirmed !!}
                        </span>
                            Hồ sơ phỏng vấn
                            @if($totalJobApplicationInterviewUnconfirmed > 0)
                                <em style="background-color: red; color: yellow; padding: 3px; border-radius: 5px;">
                                    + New
                                </em>
                            @endif
                        </a>

                    </div>

                </div>
            @endif
            <div class="col-xs-12 col-sm-8 col-md-8 col-lg-9">
                <div class="row">
                    @if($manageDepartmentStatus)
                        <div class="qc-ad3d-panel col-xs-6 col-sm-4 col-md-4 col-lg-4">
                            <a class="ac-ad3d-panel-icon-link" href="{!! route('qc.ad3d.system.staff.get') !!}">
                                <i class="glyphicon glyphicon-user qc-ad3d-panel-icon" style="color: red;"></i><br>
                                NHÂN SỰ & HỆ THỐNG
                            </a>
                        </div>
                    @endif
                    @if($manageDepartmentStatus)
                        <div class="qc-ad3d-panel col-xs-6 col-sm-4 col-md-4 col-lg-4">
                            <a class="ac-ad3d-panel-icon-link"
                               href="{!! route('qc.ad3d.finance.order-payment.get') !!}">
                                <i class="glyphicon glyphicon-usd qc-ad3d-panel-icon" style="color: grey;"></i><br>
                                TÀI CHÍNH
                            </a>
                        </div>
                    @endif
                    @if($manageDepartmentStatus)
                        <div class="qc-ad3d-panel col-xs-6 col-sm-4 col-md-4 col-lg-4">
                            <a class="ac-ad3d-panel-icon-link" href="{!! route('qc.ad3d.work.time-keeping.get') !!}">
                                <i class="glyphicon glyphicon-list-alt qc-ad3d-panel-icon" style="color: grey;"></i><br>
                                LÀM VIỆC
                            </a>
                        </div>
                    @endif
                    @if($manageDepartmentStatus || $businessDepartmentStatus)
                        <?php
                        if ($businessDepartmentStatus && $manageDepartmentStatus) {
                            $orderHref = route('qc.ad3d.order.order.get');
                        } else {
                            # chi la bo phan kinh doanh => đi vao bang gia
                            if ($businessDepartmentStatus) {
                                # di  vao bang gia
                                $orderHref = route('qc.ad3d.order.product_type_price.get');
                            } else {
                                $orderHref = route('qc.ad3d.order.order.get');
                            }
                        }
                        ?>
                        <div class="qc-ad3d-panel col-xs-6 col-sm-4 col-md-4 col-lg-4">
                            <a class="ac-ad3d-panel-icon-link" href="{!! $orderHref !!}">
                                <i class="glyphicon glyphicon-shopping-cart qc-ad3d-panel-icon"
                                   style="color: grey;"></i><br>
                                ĐƠN HÀNG & SẢN PHẨM
                            </a>
                        </div>
                    @endif
                    @if($manageDepartmentStatus)
                        <div class="qc-ad3d-panel col-xs-6 col-sm-4 col-md-4 col-lg-4">
                            <a class="ac-ad3d-panel-icon-link"
                               href="{!! route("qc.ad3d.store.supplies.supplies.get") !!}">
                                <i class="glyphicon glyphicon-hdd qc-ad3d-panel-icon" style="color: grey;"></i><br>
                                DỤNG CỤ & VẬT TƯ
                            </a>
                        </div>
                    @endif
                    @if($manageDepartmentStatus)
                        <div class="qc-ad3d-panel col-xs-6 col-sm-4 col-md-4 col-lg-4">
                            <a class="ac-ad3d-panel-icon-link"
                               href="{!! route('qc.ad3d.statistic.revenue.company.get') !!}">
                                <i class="glyphicon glyphicon-wrench qc-ad3d-panel-icon" style="color: grey;"></i><br>
                                THỐNG KÊ
                            </a>
                        </div>
                    @endif
                    <div class="qc-ad3d-panel col-xs-6 col-sm-4 col-md-4 col-lg-4">
                        <a class="ac-ad3d-panel-icon-link" href="{!! route('qc.ad3d.login.exit') !!}">
                            <i class="glyphicon glyphicon-log-out qc-ad3d-panel-icon" style="color: grey;"></i><br>
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
