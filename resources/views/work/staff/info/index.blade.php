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
# lay thong tin lam viec sau cung tai cty
$dataCompanyStaffWork = $dataStaff->companyStaffWorkLastInfo();
$companyStaffWorkStatus = ($hFunction->checkCount($dataCompanyStaffWork)) ? true : false; # co dang lam cho 1 cty hay ko
?>
@extends('work.staff.index')
@section('qc_work_staff_body')
    <div class="qc_work_staff_info_wrap qc-padding-bot-30 col-xs-12 col-sm-12 col-md-12 col-lg-12"
         @if($mobileStatus) style="padding: 0;" @endif>
        <div class="row">
            <div class="col-xs-12 col-sm-9 col-md-10 col-lg-10">
                {{-- Menu --}}
                @include('work.staff.menu')
            </div>
            <div class="text-right col-xs-12 col-sm-3 col-md-2 col-lg-2">
                <a class="qc-link-green-bold" href="{!! route('qc.work.staff.account.update.get') !!}">
                    ĐỔI TÀI KHOẢN
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <a class="qc-link-white-bold btn btn-sm btn-primary" onclick="qc_main.page_back_go();">
                    <i class="glyphicon glyphicon-backward"></i> Về trang trước
                </a>
            </div>
        </div>
        {{--THONG TIN CO BAN--}}
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" @if($mobileStatus) style="padding: 0;" @endif>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="glyphicon glyphicon-record" style="font-size: 1.5em;"></i>
                        <label style="font-size: 1.5em; color: red;">THÔNG TIN CÁ NHÂN</label>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                <div class="row">
                                    <div class="text-center col-xs-12 col-sm-12 col-md-12 col-lg-12"
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
                                    <div class="text-center col-xs-6 col-sm-6 col-md-6 col-lg-6"
                                         style="max-height: 100px;">
                                        <div style="position: relative; width: 100%; height: 100%;">
                                            <a class="qc-link" data-href="#">
                                                <img style="max-width: 100%;height: 90px;border: 1px solid #d7d7d7;"
                                                     src="{!! $identityCardFrontSrc !!}">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="text-center col-xs-6 col-sm-6 col-md-6 col-lg-6"
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
                            <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
                                <table class="table table-hover table-condensed" style="margin: 0;">
                                    <tr>
                                        <td style="width: 100px; border-top: none;">
                                            <em>Họ và tên </em>
                                        </td>
                                        <td style="border-top: none;">
                                            <b>{!! $dataStaff->fullName() !!}</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <em>Mã nhân viên: </em>
                                        </td>
                                        <td>
                                            <b>{!! $dataStaff->nameCode() !!}</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <em>CMND: </em>
                                        </td>
                                        <td>
                                            <b>{!! $identityCard !!}</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <em>Ngày sinh: </em>
                                        </td>
                                        <td>
                                            @if(!$hFunction->checkEmpty($birthday))
                                                <b>{!! date('d-m-Y', strtotime($birthday)) !!}</b>
                                            @else
                                                <em>.....................</em>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <em>Điện thoại: </em>
                                        </td>
                                        <td>
                                            <b>{!! $phone !!}</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <em>Địa chỉ: </em>&nbsp;&nbsp;
                                        </td>
                                        <td>
                                            <b>{!! $address !!}</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <em>Email: </em>&nbsp;&nbsp;
                                        </td>
                                        <td>
                                            @if(!$hFunction->checkEmpty($email))
                                                <b>{!! $email !!}</b>
                                            @else
                                                <em>.....................</em>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <em>Ngày vào: </em>
                                        </td>
                                        <td>
                                            <b>{!! date('d-m-Y', strtotime($dateAdd)) !!}</b>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        {{-- THONG TIN LAM VIEC --}}
        <div class="row">
            {{--THONG TIN LAM VIEC--}}
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" @if($mobileStatus) style="padding: 0;" @endif>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="glyphicon glyphicon-record" style="font-size: 1.5em;"></i>
                        <label style="font-size: 1.5em; color: red;">CHUYÊN MÔN</label>
                    </div>
                    <div class="panel-body">
                        @if ($companyStaffWorkStatus)
                            <?php
                            $companyStaffWorkId = $dataCompanyStaffWork->workId();
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
                            # hinh thuc lam viec
                            $dataStaffWorkMethod = $dataStaff->staffWorkMethodLastInfo();
                            $workMethod = $dataStaffWorkMethod->method();
                            $applyRule = $dataStaffWorkMethod->applyRule();
                            $workMethodLabel = $dataStaffWorkMethod->methodLabel($workMethod);
                            $applyRuleLabel = $dataStaffWorkMethod->applyRuleLabel($applyRule);
                            # bo phan lam viec
                            $dataStaffWorkDepartment = $dataCompanyStaffWork->staffWorkDepartmentGetInfoHasAction();
                            ?>
                            <div class="row" style="margin-bottom: 10px;">
                                <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
                                    <em>Công ty:</em> &nbsp;
                                    <b style="color: blue;">{!! $companyName !!}</b>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                    <em>Cấp Admin: </em>&nbsp;
                                    <b>{!! $level !!}</b>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                    <em>Ngày làm: </em> &nbsp;
                                    <b>{!! date('d-m-Y', strtotime($beginDate)) !!}</b>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                    <em>Hình thức làm: </em> &nbsp;&nbsp;
                                    <b>{!! $workMethodLabel !!}</b>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                    <em>Nội quy: </em> &nbsp;&nbsp;
                                    <b>{!! $applyRuleLabel !!}</b>
                                </div>
                            </div>
                            @if($hFunction->checkCount($dataStaffWorkDepartment))
                                <div class="row">
                                    @if($hFunction->checkCount($dataStaffWorkDepartment))
                                        @foreach($dataStaffWorkDepartment as $staffWorkDepartment)
                                            <?php
                                            # bo phan
                                            $dataDepartment = $staffWorkDepartment->department;
                                            $departmentId = $dataDepartment->departmentId();
                                            # cap bac
                                            $dataRank = $staffWorkDepartment->rank;
                                            # cong viec trong bo phan
                                            $dataDepartmentWork = $dataDepartment->departmentWorkGetInfo();
                                            ?>
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <table class="table table-hover table-condensed">
                                                    <tr style="border-bottom: 1px solid #d7d7d7;">
                                                        <td>
                                                            <i class="glyphicon glyphicon-arrow-right"></i>
                                                            <em>Bộ phận:</em>
                                                            <b style="color: blue;">{!! $dataDepartment->name() !!}</b>
                                                        </td>
                                                        <td class="text-right">
                                                            @if($dataRank->checkManageRank())
                                                                Cấp quản lý
                                                            @else
                                                                Cấp nhân viên
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @if($hFunction->checkCount($dataDepartmentWork))
                                                        @foreach($dataDepartmentWork as $departmentWork)
                                                            <?php
                                                            $departmentWorkId = $departmentWork->workId();
                                                            # lay ky nang lam viec
                                                            $dataWorkSkill = $dataCompanyStaffWork->workSkillGetLastInfoOfDepartmentWork($departmentWorkId);
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    &emsp;
                                                                    <b>{!! $departmentWork->name() !!}</b>
                                                                    @if($hFunction->checkCount($dataWorkSkill))
                                                                        <br/>&emsp;
                                                                        <em style="color: grey;">
                                                                            - {!! $hFunction->mp_strtoupper($dataWorkSkill->levelLabel($dataWorkSkill->level())) !!}
                                                                        </em>
                                                                    @else
                                                                        <br/> &emsp;
                                                                        <em style="color: grey;">
                                                                            - Không biết
                                                                        </em>
                                                                    @endif
                                                                </td>
                                                                <td class="text-right">
                                                                    <a class="qc_update_work_skill_get qc-link-red"
                                                                       data-href="{!! route('qc.work.staff.skill.update.get',"$companyStaffWorkId/$departmentWorkId") !!}">
                                                                        @if($hFunction->checkCount($dataWorkSkill))
                                                                            Cập nhật
                                                                        @else
                                                                            <i class="glyphicon glyphicon-plus"></i>
                                                                            THÊM
                                                                        @endif
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </table>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
            {{--THONG TIN LƯƠNG--}}
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" @if($mobileStatus) style="padding: 0;" @endif>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="glyphicon glyphicon-record" style="font-size: 1.5em;"></i>
                        <label style="font-size: 1.5em; color: red;">LƯƠNG</label>
                    </div>
                    <div id="staffInfoSalaryContainer" class="panel-body">
                        @if ($companyStaffWorkStatus)
                            <?php
                            $dataStaffWorkSalary = $dataCompanyStaffWork->staffWorkSalaryActivity();
                            ?>
                            @if ($hFunction->checkCount($dataStaffWorkSalary))
                                <?php
                                $totalSalary = $dataStaffWorkSalary->totalSalary();
                                $salary = $dataStaffWorkSalary->salary();
                                $responsibility = $dataStaffWorkSalary->responsibility();
                                $insurance = $dataStaffWorkSalary->totalMoneyInsurance();
                                $usePhone = $dataStaffWorkSalary->usePhone();
                                $fuel = $dataStaffWorkSalary->fuel();
                                $dateOff = $dataStaffWorkSalary->salaryOneDateOff();
                                $overtimeHour = $dataStaffWorkSalary->overtimeHour();
                                ?>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <table class="table table-hover table-condensed" style="margin: 0;">
                                            <tr>
                                                <td style="width: 150px; border-top: none;">
                                                    <em>Tổng lương: </em>
                                                </td>
                                                <td class="text-right" style="border-top: none;">
                                                    <b class="qc-color-red">{!! $hFunction->currencyFormat($totalSalary) !!}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <em>Lương cơ bản: </em>
                                                </td>
                                                <td class="text-right">
                                                    <b>{!! $hFunction->currencyFormat($salary) !!}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <em>Phụ cấp trách nhiệm: </em>
                                                </td>
                                                <td class="text-right">
                                                    <b>{!! $hFunction->currencyFormat($responsibility) !!}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <em>Bảo hiểm 21,5% LCB: </em>
                                                </td>
                                                <td class="text-right">
                                                    <b>{!! $hFunction->currencyFormat($insurance) !!}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <em>Phụ cấp điện thoại: </em>
                                                </td>
                                                <td class="text-right">
                                                    <b>{!! $hFunction->currencyFormat($usePhone) !!}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <em>Phụ cấp đi lại: </em>
                                                </td>
                                                <td class="text-right">
                                                    <b>{!! $hFunction->currencyFormat($fuel) !!}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <em>Lương 1 ngày nghỉ : </em>
                                                </td>
                                                <td class="text-right">
                                                    <b>{!! $hFunction->currencyFormat($dateOff) !!}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <em>Phụ cấp tăng ca: </em>
                                                </td>
                                                <td class="text-right">
                                                    <b>{!! $hFunction->currencyFormat($overtimeHour) !!} </b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <em>Số TK: </em>
                                                </td>
                                                <td class="text-right">
                                                    @if($hFunction->checkEmpty($bankAccount))
                                                        <b>.............</b>
                                                    @else
                                                        <b>{!! $bankAccount !!} </b><br>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <em>Ngân hàng: </em>
                                                </td>
                                                <td class="text-right">
                                                    @if($hFunction->checkEmpty($bankAccount))
                                                        <b>.............</b>
                                                    @else
                                                        <b>{!! $bankName !!} </b>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            @endif

                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
