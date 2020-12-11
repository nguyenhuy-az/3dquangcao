<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$salaryId = $dataSalary->salaryId();
$dataWork = $dataSalary->work;
# thong tin nhan vien
$dataStaffSalary = $dataWork->companyStaffWork->staff;
?>
@extends('components.container.container-6')
@section('qc_container_content')
    <div class="row col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <h3 style="color: red;">CỘNG TIỀN THÊM</h3>
            <b style="color: blue; font-size: 1.5em;">(Một bảng lương chỉ được cộng 1 lần)</b>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 20px;">
            <div class="media">
                <a class="pull-left" href="#">
                    <img class="media-object"
                         style="background-color: white; width: 40px;height: 40px; border: 1px solid #d7d7d7;border-radius: 10px;"
                         src="{!! $dataStaffSalary->pathAvatar($dataStaffSalary->image()) !!}">
                </a>
                <div class="media-body">
                    <h5 class="media-heading">{!! $dataStaffSalary->fullName() !!}</h5>
                    <em>Từ: </em>
                    <span class="qc-color-red qc-font-bold">
                        {!! date('d-m-Y', strtotime($dataWork->fromDate())) !!}
                    </span>
                    <em>Đến: </em>
                    <span class="qc-color-red qc-font-bold">
                       {!! date('d-m-Y', strtotime($dataWork->toDate())) !!}
                    </span>
                </div>
            </div>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <form id="frm_work_pay_salary_benefit_add" role="form" method="post"
                  action="{!! route('qc.work.pay.pay_salary.benefit_add.post', $salaryId) !!}">
                <div class="form-group">
                    <label>Số tiền</label>
                    <input type="text" class="txtBenefitMoney form-control" name="txtBenefitMoney"
                           placeholder="Nhập số tiền công thêm" value=""
                           onkeyup="qc_main.showFormatCurrency(this);">
                </div>
                <div class="form-group">
                    <label>Lý do:</label>
                    <input type="text" class="form-control" name="txtBenefitMoneyDescription" placeholder="Nhập lý do cộng" value="">
                </div>
                <div class="text-center form-group">
                    <span style="background-color: red; color: yellow; padding: 5px;">LƯU Ý: Sau khi thêm sẽ không được thay đổi</span>
                </div>
                <div class="text-center form-group">
                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                    <button type="button" class="qc_save btn btn-sm btn-primary">
                        THÊM
                    </button>
                    <button type="button" class="qc_container_close btn btn-sm btn-default">
                        ĐÓNG
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
