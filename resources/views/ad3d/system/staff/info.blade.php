<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 *
 * dataStaff
 */

$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$staffId = $dataStaff->staffId();
$firstName = $dataStaff->firstName();
$lastName = $dataStaff->lastName();
$identityCard = $dataStaff->identityCard();
$birthday = $dataStaff->birthday();
$gender = $dataStaff->gender();
$image = $dataStaff->image();
$identityCardFront = $dataStaff->identityCardFront();
$identityCardBack = $dataStaff->identityCardBack();
$phone = $dataStaff->phone();
$address = $dataStaff->address();
$email = $dataStaff->email();
$bankAccount = $dataStaff->bankAccount();
$bankName = $dataStaff->bankName();
$dateAdd = $dataStaff->createdAt();
# lay thong tin lam viec tai cty
$dataCompanyStaffWork = $dataStaff->companyStaffWorkInfoActivity();
if ($hFunction->checkCount($dataCompanyStaffWork)) {
    # ap dung chuyen doi tu phien ban cua phan tích moi nv chi lam tai 1 cty
    # thiet ke moi
    $companyId = $dataCompanyStaffWork->companyId();
    $companyName = $dataCompanyStaffWork->company->name();
    $level = $dataCompanyStaffWork->level();
    $beginDate = $dataCompanyStaffWork->beginDate();
    $dataStaffWorkDepartment = $dataCompanyStaffWork->staffWorkDepartmentInfoActivity();
    $dataStaffWorkSalary = $dataCompanyStaffWork->staffWorkSalaryActivity();
} else {
    # thiet ke cu
    $companyId = null;
    $companyName = null;
    $level = null;
    $beginDate = $hFunction->carbonNow();
    $dataStaffWorkDepartment = null;
    $dataStaffWorkSalary = null;
    $departmentStaff = null;
}
# hinh thuc lam viec
$dataStaffWorkMethod = $dataStaff->infoActivityStaffWorkMethod();
if ($hFunction->checkCount($dataStaffWorkMethod)) {
    $workMethod = $dataStaffWorkMethod->method();
    $applyRule = $dataStaffWorkMethod->applyRule();
    $workMethodLabel = $dataStaffWorkMethod->methodLabel($workMethod);
    $applyRuleLabel = $dataStaffWorkMethod->applyRuleLabel($applyRule);
} else {
    $workMethod = 1; # mac dinh
    $applyRule = 1; # mac dinh
    $workMethodLabel = 'Chính thức';
    $applyRuleLabel = 'Áp dụng';
}
?>
@extends('ad3d.system.staff.index')
@section('qc_ad3d_index_content')
    <div class="qc_ad3d_sys_staff_edit_wrap qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <a class="qc-link-red" href="{!! route('qc.ad3d.system.staff.get') !!}">
                    <i class="glyphicon glyphicon-backward"></i> Trởlại
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <h3>THÔNG TIN NHÂN VIÊN</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        {{--THONG TIN CO BAN--}}
                        <tr>
                            <td style="color: red;">
                                <i class="glyphicon glyphicon-record" style="font-size: 1.5em;"></i>
                                <label style="font-size: 1.5em;">Thông tin cơ bản</label>
                                <a class="qc_staffInfoBasicContainerEdit qc-link-red pull-right "
                                   data-href="{!! route('qc.ad3d.system.staff.info_basic.edit.get',$staffId) !!}"
                                   title="Sửa thông tin">
                                    <i class="glyphicon glyphicon-pencil" style="font-size: 1.5em;"></i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td id="staffInfoBasicContainer">
                                <div class="row">
                                    <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                                        <div class="row">
                                            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12"
                                                 style="height: 200px;">
                                                @if(!empty($image))
                                                    <div style="position: relative; margin: 5px 10px 5px 10px; width: 100%; height: 100%;">
                                                        <a class="qc-link" data-href="#">
                                                            <img style="max-width: 100%;height: 190px;"
                                                                 src="{!! $dataStaff->pathFullImage($image) !!}">
                                                        </a>
                                                        <a class="qc_ad3d_staff_edit_image_act_del qc-link"
                                                           data-href="{!! route('qc.ad3d.system.staff.image.delete.get', "$staffId/avatar") !!}">
                                                            <i style="position: absolute; font-weight: bold; padding: 0 3px; color: red; top: 3px; right: 3px; border: 1px solid #d7d7d7;">x</i>
                                                        </a>
                                                    </div>
                                                @else
                                                    <span>Ảnh</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="text-center col-sx-12 col-sm-12 col-md-6 col-lg-6"
                                                 style="max-height: 100px;">
                                                @if(!empty($identityCardFront))
                                                    <div style="position: relative; width: 100%; height: 100%;">
                                                        <a class="qc-link" data-href="#">
                                                            <img style="max-width: 100%;height: 90px;"
                                                                 src="{!! $dataStaff->pathFullImage($identityCardFront) !!}">
                                                        </a>
                                                        <a class="qc_ad3d_staff_edit_image_act_del qc-link"
                                                           data-href="{!! route('qc.ad3d.system.staff.image.delete.get', "$staffId/identityCardFront") !!}">
                                                            <i style="position: absolute; font-weight: bold; padding: 0 3px; color: red; top: 3px; right: 3px; border: 1px solid #d7d7d7;">x</i>
                                                        </a>
                                                    </div>
                                                @else
                                                    <span>Mặt trước CMND</span>
                                                @endif
                                            </div>
                                            <div class="text-center col-sx-12 col-sm-12 col-md-6 col-lg-6"
                                                 style="max-height: 100px;">
                                                @if(!empty($identityCardBack))
                                                    <div style="position: relative; width: 100%; height: 100%;">
                                                        <a class="qc-link" data-href="#">
                                                            <img style="max-width: 100%; height: 90px;"
                                                                 src="{!! $dataStaff->pathFullImage($identityCardBack) !!}">
                                                        </a>
                                                        <a class="qc_ad3d_staff_edit_image_act_del qc-link"
                                                           data-href="{!! route('qc.ad3d.system.staff.image.delete.get', "$staffId/identityCardBack") !!}">
                                                            <i style="position: absolute; font-weight: bold; padding: 0 3px; color: red; top: 3px; right: 3px; border: 1px solid #d7d7d7;">x</i>
                                                        </a>
                                                    </div>
                                                @else
                                                    <span>Mặt sau CMND</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                            <a class=" qc_ad3d_staff_edit_image_act qc-link-green"
                                               data-href="{!! route('qc.ad3d.system.staff.image.add.get',$staffId) !!}">
                                                Cập nhật hình ảnh
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-sx-12 col-sm-12 col-md-8 col-lg-8">
                                        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                            <i class="glyphicon glyphicon-arrow-right"></i>
                                            <em>Họ và tên </em>
                                            <b>{!! $dataStaff->fullName() !!}</b>
                                        </div>
                                        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                            <i class="glyphicon glyphicon-arrow-right"></i>
                                            <em>Mã nhân viên: </em>&nbsp;&nbsp;
                                            <b>{!! $dataStaff->nameCode() !!}</b>
                                        </div>
                                        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                            <i class="glyphicon glyphicon-arrow-right"></i>
                                            <em>CMND: </em> &nbsp;&nbsp;
                                            <b>{!! $identityCard !!}</b>
                                        </div>
                                        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                            <i class="glyphicon glyphicon-arrow-right"></i>
                                            <em>Ngày sinh: </em>&nbsp;&nbsp;
                                            <b>{!! date('d-m-Y', strtotime($birthday)) !!}</b>
                                        </div>
                                        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                            <i class="glyphicon glyphicon-arrow-right"></i>
                                            <em>Điện thoại: </em>
                                            <b>{!! $phone !!}</b>
                                        </div>
                                        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                            <i class="glyphicon glyphicon-arrow-right"></i>
                                            <em>Địa chỉ: </em>&nbsp;&nbsp;
                                            <b>{!! $address !!}</b>
                                        </div>
                                        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                            <i class="glyphicon glyphicon-arrow-right"></i>
                                            <em>Email: </em>&nbsp;&nbsp;
                                            <b>{!! $email !!}</b>
                                        </div>
                                        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                            <i class="glyphicon glyphicon-arrow-right"></i>
                                            <em>Ngày vào: </em>&nbsp;&nbsp;
                                            <b>{!! date('d-m-Y', strtotime($dateAdd)) !!}</b>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        {{--THONG TIN LAM VIEC--}}
                        <tr>
                            <td style="color: red;">
                                <i class="glyphicon glyphicon-record" style="font-size: 1.5em;"></i>
                                <label style="font-size: 1.5em;">Làm việc</label>
                                <a class="qc_staffInfoWorkContainerEdit qc-link-red pull-right"
                                   data-href="{!! route('qc.ad3d.system.staff_info.work.edit.get',$staffId) !!}"
                                   title="Sửa thông tin">
                                    <i class=" glyphicon glyphicon-pencil" style="font-size: 1.5em;"></i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td id="staffInfoWorkContainer">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered" style="margin-bottom: 0;">
                                        <tr>
                                            <td>
                                                <em>Công ty:</em> &nbsp;
                                                <b>{!! $companyName !!}</b>
                                            </td>
                                            <td>
                                                <em>Cấp Admin: </em>&nbsp;
                                                <b>{!! $level !!}</b>
                                            </td>
                                            <td>
                                                <em>Ngày làm: </em> &nbsp;
                                                <b>{!! date('d-m-Y', strtotime($beginDate)) !!}</b>
                                            </td>
                                            <td>
                                                <em>Hình thức làm: </em> &nbsp;&nbsp;
                                                <b>{!! $workMethodLabel !!}</b>
                                            </td>
                                            <td>
                                                <em>Nội quy: </em> &nbsp;&nbsp;
                                                <b>{!! $applyRuleLabel !!}</b>
                                            </td>

                                        </tr>
                                    </table>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-condensed">
                                        <tr>
                                            <th>
                                                BỘ PHẬN
                                            </th>
                                            <th class="text-center">
                                                CẤP QUẢN LÝ
                                            </th>
                                            <th class="text-center">
                                                CẤP NHÂN VIÊN
                                            </th>
                                        </tr>
                                        @if($hFunction->checkCount($dataDepartment))
                                            @foreach($dataDepartment as $department)
                                                <tr>
                                                    <td>
                                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                                        {!! $department->name() !!}
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="checkbox">
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="checkbox">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </table>
                                </div>
                            </td>
                        </tr>
                        {{--THONG TIN LUONG--}}
                        <tr>
                            <td style="color: red;">
                                <i class="glyphicon glyphicon-record" style="font-size: 1.5em;"></i>
                                <label style="font-size: 1.5em;">Lương</label>
                                <a class="qc_staffInfoSalaryContainerEdit qc-link-red pull-right" title="Sửa thông tin"
                                   data-href="{!! route('qc.ad3d.system.staff_info.salary.edit.get',$staffId) !!}">
                                    <i class=" glyphicon glyphicon-pencil" style="font-size: 1.5em;"></i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td id="staffInfoSalaryContainer">
                                <?php
                                if (count($dataStaffWorkSalary) > 0) {
                                    $totalSalary = $dataStaffWorkSalary->totalSalary();
                                    $salary = $dataStaffWorkSalary->salary();
                                    $responsibility = $dataStaffWorkSalary->responsibility();
                                    $insurance = $dataStaffWorkSalary->totalMoneyInsurance();
                                    $usePhone = $dataStaffWorkSalary->usePhone();
                                    $fuel = $dataStaffWorkSalary->fuel();
                                    $dateOff = $dataStaffWorkSalary->salaryOneDateOff();
                                    $overtimeHour = $dataStaffWorkSalary->overtimeHour();
                                } else {
                                    $totalSalary = 0;
                                    $salary = 0;
                                    $responsibility = 0;
                                    $insurance = 0;
                                    $usePhone = 0;
                                    $fuel = 0;
                                    $dateOff = 0;
                                    $overtimeHour = 0;
                                }
                                ?>
                                <div class="row">
                                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                        <em>Tổng lương: </em>
                                        <b class="qc-color-red">{!! $hFunction->currencyFormat($totalSalary) !!}</b>
                                    </div>
                                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                        <em>Lương cơ bản: </em>
                                        <b>{!! $hFunction->currencyFormat($salary) !!}</b>
                                    </div>
                                    <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                        <em>Phụ cấp trách nhiệm: </em>&nbsp;&nbsp;
                                        <b>{!! $hFunction->currencyFormat($responsibility) !!}</b>
                                    </div>
                                    <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                        <em>Bảo hiểm 21,5% LCB: </em> &nbsp;&nbsp;
                                        <b>{!! $hFunction->currencyFormat($insurance) !!}</b>
                                    </div>
                                    <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                        <em>Phụ cấp điện thoại: </em>&nbsp;&nbsp;
                                        <b>{!! $hFunction->currencyFormat($usePhone) !!}</b>
                                    </div>
                                    <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                        <em>Phụ cấp đi lại: </em>
                                        <b>{!! $hFunction->currencyFormat($fuel) !!}</b>
                                    </div>
                                    <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                        <em>Lương 1 ngày nghỉ : </em>&nbsp;&nbsp;
                                        <b>{!! $hFunction->currencyFormat($dateOff) !!}</b>
                                    </div>
                                    <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                        <em>Phụ cấp tăng ca: </em>&nbsp;&nbsp;
                                        <b>{!! $hFunction->currencyFormat($overtimeHour) !!} </b>
                                    </div>
                                    <div class="qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                        <hr style="margin-top: 0;">
                                        <label class="qc-color-red">Tài khoản ngân hàng</label>
                                        <br/>
                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                        <em>Số TK: </em>&nbsp;&nbsp;
                                        @if(empty($bankAccount))
                                            <b>Null</b>
                                        @else
                                            <b>{!! $bankAccount !!} </b><br>
                                            <i class="glyphicon glyphicon-arrow-right"></i>
                                            <em>Ngân hàng: </em>&nbsp;&nbsp;
                                            <b>{!! $bankName !!} </b>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
