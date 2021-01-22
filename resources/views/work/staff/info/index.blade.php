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
if ($hFunction->checkEmpty($image)) {
    $imageSrc = $dataStaff->pathDefaultNotImage();
} else {
    $imageSrc = $dataStaff->pathFullImage($image);
}
$identityCardFront = $dataStaff->identityCardFront();
if ($hFunction->checkEmpty($image)) {
    $identityCardFrontSrc = $dataStaff->pathDefaultNotImage();
} else {
    $identityCardFrontSrc = $dataStaff->pathFullImage($identityCardFront);
}
$identityCardBack = $dataStaff->identityCardBack();
if ($hFunction->checkEmpty($image)) {
    $identityCardBackSrc = $dataStaff->pathDefaultNotImage();
} else {
    $identityCardBackSrc = $dataStaff->pathFullImage($identityCardBack);
}
$phone = $dataStaff->phone();
$address = $dataStaff->address();
$email = $dataStaff->email();
$bankAccount = $dataStaff->bankAccount();
$bankName = $dataStaff->bankName();
$dateAdd = $dataStaff->createdAt();
# lay thong tin lam viec tai cty
$dataCompanyStaffWork = $dataStaff->companyStaffWorkInfoActivity();
$companyStaffWorkStatus = ($hFunction->checkCount($dataCompanyStaffWork)) ? true : false; # co dang lam cho 1 cty hay ko
if ($companyStaffWorkStatus) {
    $manageRankId = $modelRank->manageRankId();
    $staffRankId = $modelRank->staffRankId();
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
@extends('work.staff.index')
@section('qc_work_staff_body')
    <div class="qc_work_staff_info_wrap qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                {{-- Menu --}}
                @include('work.staff.menu')
            </div>
        </div>
        <div class="row">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <a class="qc-link-white-bold btn btn-sm btn-primary" onclick="qc_main.page_back_go();">
                    <i class="glyphicon glyphicon-backward"></i> Về trang trước
                </a>
            </div>
        </div>
        @if(!$dataStaff->checkWorkStatus())
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
                 style=" background-color: red; color: yellow; padding: 5px;">
                <span>ĐÃ NGHỈ</span>
            </div>
        @endif
        <div class="row">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="table-responsive">
                    <table class="table table-bordered" style="border: none;">
                        {{--THONG TIN CO BAN--}}
                        <tr>
                            <td style="color:red; padding-bottom: 0; border: none;">
                                <i class="glyphicon glyphicon-record" style="font-size: 1.5em;"></i>
                                <label style="font-size: 1.5em;">THÔNG TIN CÁ NHÂN</label>
                            </td>
                        </tr>
                        <tr>
                            <td id="staffInfoBasicContainer" style="border: none;">
                                <div class="row">
                                    <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                                        <div class="row">
                                            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12"
                                                 style="height: 160px;">
                                                <div style="position: relative; margin: 5px 10px 5px 10px; width: 100%; height: 100%;">
                                                    <a class="qc-link" data-href="#">
                                                        <img style="max-width: 100%;height: 150px; border: 1px solid #d7d7d7;"
                                                             src="{!! $imageSrc !!}">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="text-center col-sx-12 col-sm-12 col-md-6 col-lg-6"
                                                 style="max-height: 100px;">
                                                <div style="position: relative; width: 100%; height: 100%;">
                                                    <a class="qc-link" data-href="#">
                                                        <img style="max-width: 100%;height: 90px;border: 1px solid #d7d7d7;"
                                                             src="{!! $identityCardFrontSrc !!}">
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="text-center col-sx-12 col-sm-12 col-md-6 col-lg-6"
                                                 style="max-height: 100px;">
                                                <div style="position: relative; width: 100%; height: 100%;">
                                                    <a class="qc-link" data-href="#">
                                                        <img style="max-width: 100%; height: 90px; border: 1px solid #d7d7d7;"
                                                             src="{!! $identityCardBackSrc !!}">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sx-12 col-sm-12 col-md-8 col-lg-8">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-condensed" style="margin: 0;">
                                                <tr>
                                                    <td style="border-top: none;">
                                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                                        <em>Họ và tên </em>
                                                        <b>{!! $dataStaff->fullName() !!}</b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                                        <em>Mã nhân viên: </em>&nbsp;&nbsp;
                                                        <b>{!! $dataStaff->nameCode() !!}</b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                                        <em>CMND: </em> &nbsp;&nbsp;
                                                        <b>{!! $identityCard !!}</b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                                        <em>Ngày sinh: </em>&nbsp;&nbsp;
                                                        @if(!$hFunction->checkEmpty($birthday))
                                                            <b>{!! date('d-m-Y', strtotime($birthday)) !!}</b>
                                                        @else
                                                            <em>.....................</em>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                                        <em>Điện thoại: </em>
                                                        <b>{!! $phone !!}</b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                                        <em>Địa chỉ: </em>&nbsp;&nbsp;
                                                        <b>{!! $address !!}</b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                                        <em>Email: </em>&nbsp;&nbsp;
                                                        @if(!$hFunction->checkEmpty($email))
                                                            <b>{!! $email !!}</b>
                                                        @else
                                                            <em>.....................</em>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                                        <em>Ngày vào: </em>&nbsp;&nbsp;
                                                        <b>{!! date('d-m-Y', strtotime($dateAdd)) !!}</b>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        {{--THONG TIN LAM VIEC--}}
                        <tr>
                            <td style="color: red;padding-bottom: 0; border: none;">
                                <i class="glyphicon glyphicon-record" style="font-size: 1.5em;"></i>
                                <label style="font-size: 1.5em;">CHUYÊN MÔN</label>
                            </td>
                        </tr>
                        <tr>
                            <td id="staffInfoWorkContainer" style="border: none;">
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
                                    <table class="table table-hover table-condensed">
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
                                                <?php
                                                $departmentId = $department->departmentId(); //
                                                ?>
                                                @if($companyStaffWorkStatus)
                                                    <tr>
                                                        <td>
                                                            <i class="glyphicon glyphicon-arrow-right"></i>
                                                            {!! $department->name() !!}
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="checkbox"
                                                                   disabled="disabled"
                                                                   @if($dataCompanyStaffWork->checkExistActivityWorkDepartmentAndRank($departmentId, $manageRankId)) checked="checked" @endif>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="checkbox" disabled="disabled"
                                                                   @if($dataCompanyStaffWork->checkExistActivityWorkDepartmentAndRank($departmentId, $staffRankId)) checked="checked" @endif>
                                                        </td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td>
                                                            <i class="glyphicon glyphicon-arrow-right"></i>
                                                            {!! $department->name() !!}
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="checkbox" disabled="disabled">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="checkbox" disabled="disabled">
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endif
                                    </table>
                                </div>
                            </td>
                        </tr>
                        {{--THONG TIN LUONG--}}
                        <tr>
                            <td style="color: red; padding-bottom: 0; border: none;">
                                <i class="glyphicon glyphicon-record" style="font-size: 1.5em;"></i>
                                <label style="font-size: 1.5em;">LƯƠNG</label>
                            </td>
                        </tr>
                        <tr>
                            <td id="staffInfoSalaryContainer" style="border: none;">
                                <?php
                                if ($hFunction->checkCount($dataStaffWorkSalary)) {
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
                                        <div class="table-responsive">
                                            <table class="table table-hover table-condensed" style="margin: 0;">
                                                <tr>
                                                    <td>
                                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                                        <em>Tổng lương: </em>
                                                        <b class="qc-color-red">{!! $hFunction->currencyFormat($totalSalary) !!}</b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                                        <em>Tổng lương: </em>
                                                        <b class="qc-color-red">{!! $hFunction->currencyFormat($totalSalary) !!}</b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                                        <em>Lương cơ bản: </em>
                                                        <b>{!! $hFunction->currencyFormat($salary) !!}</b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                                        <em>Phụ cấp trách nhiệm: </em>&nbsp;&nbsp;
                                                        <b>{!! $hFunction->currencyFormat($responsibility) !!}</b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                                        <em>Bảo hiểm 21,5% LCB: </em> &nbsp;&nbsp;
                                                        <b>{!! $hFunction->currencyFormat($insurance) !!}</b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                                        <em>Phụ cấp điện thoại: </em>&nbsp;&nbsp;
                                                        <b>{!! $hFunction->currencyFormat($usePhone) !!}</b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                                        <em>Phụ cấp đi lại: </em>
                                                        <b>{!! $hFunction->currencyFormat($fuel) !!}</b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                                        <em>Lương 1 ngày nghỉ : </em>&nbsp;&nbsp;
                                                        <b>{!! $hFunction->currencyFormat($dateOff) !!}</b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                                        <em>Phụ cấp tăng ca: </em>&nbsp;&nbsp;
                                                        <b>{!! $hFunction->currencyFormat($overtimeHour) !!} </b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <label class="qc-color-red">Tài khoản ngân hàng</label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                                        <em>Số TK: </em>&nbsp;&nbsp;
                                                        @if(empty($bankAccount))
                                                            <b>.............</b>
                                                        @else
                                                            <b>{!! $bankAccount !!} </b><br>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                                        <em>Ngân hàng: </em>&nbsp;&nbsp;
                                                        @if(empty($bankAccount))
                                                            <b>.............</b>
                                                        @else
                                                            <b>{!! $bankName !!} </b>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
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
