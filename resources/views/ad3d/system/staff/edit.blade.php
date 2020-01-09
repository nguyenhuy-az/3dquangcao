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
if (count($dataCompanyStaffWork) > 0) {
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
if (count($dataStaffWorkMethod) > 0) {
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
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <h3>THÔNG TIN NHÂN VIÊN</h3>
            </div>
            {{--THONG TIN CO BAN--}}
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="qc-color-red col-xs-12 col-sm-12 col-dm-12 col-lg-12"
                         style="border-bottom: 2px solid black;background-color: whitesmoke;">
                        <i class="glyphicon glyphicon-record"></i>
                        <em>Thông tin cơ bản</em>
                        <a class="qc-link-red pull-right glyphicon glyphicon-pencil" title="Sửa thông tin"
                           onclick="qc_main.hide('#staffInfoEditShow'); qc_main.show('#frmStaffInfoEdit');"></a>
                    </div>
                </div>
                <div id="staffInfoEditShow" class="row">
                    <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4" style="border: 1px dotted brown;">
                        <div class="row">
                            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="height: 200px;">
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
                            <div class="text-center col-sx-12 col-sm-12 col-md-6 col-lg-6" style="max-height: 100px;">
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
                            <div class="text-center col-sx-12 col-sm-12 col-md-6 col-lg-6" style="max-height: 100px;">
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
                <form id="frmStaffInfoEdit" class="frmStaffInfoEdit qc-display-none" name="frmStaffInfoEdit" role="form"
                      method="post"
                      action="{!! route('qc.ad3d.system.staff.info.edit.post',$staffId ) !!}">
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
                         @if($mobileStatus) style="padding: 0 0;" @endif>
                        <div class="frm_info_edit_notify form-group form-group-sm qc-color-red"></div>
                    </div>
                    <div class="row">
                        <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>Họ <i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                <input type="text" class="form-control" name="txtFirstName" placeholder="Nhập họ"
                                       value="{!! $firstName !!}">
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="form-group form-group-sm qc-padding-none" style="margin: 0;">
                                <label>Tên <i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                <input type="text" class="form-control" name="txtLastName" placeholder="Nhập Tên"
                                       value="{!! $lastName !!}">
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>Chứng minh thư <i
                                            class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                <input type="text" class="form-control" name="txtIdentityCard"
                                       placeholder="Số chứng minh nhân dân"
                                       value="{!! $identityCard !!}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4"
                             @if($mobileStatus) style="padding: 0 0;" @endif>
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>Giới tính</label>
                                <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                <select class="form-control" name="cbGender">
                                    <option value="">Chọn giới tính</option>
                                    <option value="1" @if($gender == 1) selected="selected" @endif>
                                        Nam
                                    </option>
                                    <option value="0" @if($gender == 0) selected="selected" @endif>Nữ
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>Ngày sinh</label>
                                <input id="txtBirthday" type="text" class="form-control" name="txtBirthday"
                                       placeholder="Ngày sinh"
                                       value="{!! $birthday !!}">
                                <script type="text/javascript">
                                    qc_main.setDatepicker('#txtBirthday');
                                </script>

                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4"
                             @if($mobileStatus) style="padding: 0 0;" @endif>
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>Điện thoại<i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                <input type="text" class="form-control" name="txtPhone"
                                       onkeyup="qc_main.showNumberInput(this);" placeholder="Số điện thoại"
                                       value="{!! $phone !!}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sx-12 col-sm-12 col-md-8 col-lg-8"
                             @if($mobileStatus) style="padding: 0 0;" @endif>
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>Địa chỉ</label>
                                <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                <input type="text" class="form-control" name="txtAddress"
                                       placeholder="Thông tin địa chỉ"
                                       value="{!! $address !!}">
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>Email</label>
                                <input type="text" class="form-control" name="txtEmail" placeholder="Địa chỉ email"
                                       value="{!! $email !!}">
                            </div>
                        </div>
                    </div>

                    <div class="text-right qc-padding-top-10 qc-padding-bot-10 col-sx-12 col-sm-12 col-md-12 col-lg-12 ">
                        <div class="form-group form-group-sm" style="margin: 0;">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <button type="button" class="qc_save btn btn-sm btn-primary">Lưu thay đổi</button>
                            <button type="button" class="btn btn-sm btn-default"
                                    onclick="qc_main.hide('#frmStaffInfoEdit'); qc_main.show('#staffInfoEditShow');">
                                Đóng
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            {{--THONG TIN LAM VIEC--}}
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="qc-color-red col-xs-12 col-sm-12 col-dm-12 col-lg-12"
                         style="border-bottom: 2px solid black;background-color: whitesmoke;">
                        <i class="glyphicon glyphicon-record"></i>
                        <em>Làm việc</em>
                        <a class="qc-link-red pull-right glyphicon glyphicon-pencil" title="Sửa thông tin"
                           onclick="qc_main.hide('#staffWorkEditShow'); qc_main.show('#frmStaffWorkEdit');"></a>
                    </div>
                </div>
                <div id="staffWorkEditShow" class="row">
                    <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <i class="glyphicon glyphicon-arrow-right"></i>
                        <em>Công ty:</em>&nbsp;;
                        <b>{!! $companyName !!}</b>
                    </div>
                    <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-3 col-lg-3">
                        <i class="glyphicon glyphicon-arrow-right"></i>
                        <em>Cấp Admin: </em>&nbsp;
                        <b>{!! $level !!}</b>
                    </div>
                    <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-3 col-lg-3">
                        <i class="glyphicon glyphicon-arrow-right"></i>
                        <em>Ngày làm: </em> &nbsp;
                        <b>{!! date('d-m-Y', strtotime($beginDate)) !!}</b>
                    </div>
                    <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-3 col-lg-3">
                        <i class="glyphicon glyphicon-arrow-right"></i>
                        <em>Hình thức làm: </em> &nbsp;&nbsp;
                        <b>{!! $workMethodLabel !!}</b>
                    </div>
                    <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-3 col-lg-3">
                        <i class="glyphicon glyphicon-arrow-right"></i>
                        <em>Nội quy: </em> &nbsp;&nbsp;
                        <b>{!! $applyRuleLabel !!}</b>
                    </div>
                    @if(count($dataStaffWorkDepartment) > 0)
                        @foreach($dataStaffWorkDepartment as $staffWorkDepartment)
                            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12"
                                     style="border-top: 1px dashed brown;">
                                    <i class="glyphicon glyphicon-arrow-right"></i>
                                    <em>Bộ phân: </em>&nbsp;&nbsp;
                                    <b>{!! $staffWorkDepartment->department->name() !!}</b>
                                </div>
                                <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                    <i class="glyphicon glyphicon-arrow-right"></i>
                                    <em>Vị trí: </em>&nbsp;&nbsp;
                                    <b>{!! $staffWorkDepartment->rank->name() !!}</b>
                                </div>
                                <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                    <i class="glyphicon glyphicon-arrow-right"></i>
                                    <em>Phân quyền HĐ: </em>&nbsp;&nbsp;
                                    <b>{!! $staffWorkDepartment->permission() !!}</b>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <form id="frmStaffWorkEdit" class="frmStaffWorkEdit qc-display-none" name="frmStaffWorkEdit" role="form"
                      method="post"
                      action="{!! route('qc.ad3d.system.staff.work.edit.post', $staffId) !!}">
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
                         @if($mobileStatus) style="padding: 0 0;" @endif>
                        <div class="frm_work_notify form-group form-group-sm qc-color-red"></div>
                    </div>
                    <div class="row">
                        <div class="col-sx-12 col-sm-12 col-md-8 col-lg-8"
                             @if($mobileStatus) style="padding: 0 0;" @endif>
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>
                                    Công ty
                                    <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                </label>
                                <select class="form-control" name="cbCompany">
                                    @if(count($dataCompany) > 0)
                                        @foreach($dataCompany as $company)
                                            <option value="{!! $company->companyId() !!}"
                                                    @if($company->companyId() == $companyId ) selected="true" @endif >{!! $company->name() !!}</option>
                                        @endforeach
                                    @endif

                                </select>
                            </div>
                        </div>
                        @if($dataStaff->level() > 0)
                            <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4"
                                 @if($mobileStatus) style="padding: 0 0;" @endif>
                                <div class="form-group form-group-sm" style="margin: 0;">
                                    <label>
                                        Cấp bậc truy cập
                                        <i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                    <em style="color: brown;">Cấp < 4 sẽ truy cập vào trang quản lý</em>
                                    <select class="form-control" name="cbLevel">
                                        <option value="1" @if($dataStaff->level() == 1) selected="selected" @endif>1
                                        </option>
                                        <option value="2" @if($dataStaff->level() == 2) selected="selected" @endif>2
                                        </option>
                                        <option value="3" @if($dataStaff->level() == 3) selected="selected" @endif>3
                                        </option>
                                        <option value="4" @if($dataStaff->level() == 4) selected="selected" @endif>4
                                        </option>
                                        <option value="5" @if($dataStaff->level() == 5) selected="selected" @endif>5
                                        </option>
                                    </select>
                                </div>
                            </div>
                        @else
                            <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4"
                                 @if($mobileStatus) style="padding: 0 0;" @endif>
                                <div class="form-group form-group-sm" style="margin: 0;">
                                    <input type="hidden" name="cbLevel" value="{!! $dataStaff->level() !!}">
                                </div>
                            </div>
                        @endif
                        <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>Ngày vào</label>
                                <input id="txtDateWork" type="text" class="form-control" name="txtDateWork"
                                       value="{!! $beginDate !!}">
                                <script type="text/javascript">
                                    qc_main.setDatepicker('#txtDateWork');
                                </script>
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4"
                             @if($mobileStatus) style="padding: 0 0;" @endif>
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>
                                    Hình thức làm<i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                </label>
                                <select class="form-control" name="cbWorkMethod">
                                    <option value="1" @if($workMethod == 1) selected="selected" @endif>
                                        Chính thức
                                    </option>
                                    <option value="2" @if($workMethod == 2) selected="selected" @endif>
                                        Không chính thức
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4"
                             @if($mobileStatus) style="padding: 0 0;" @endif>
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>
                                    Áp dụng nội quy<i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                </label>
                                <select class="form-control" name="cbApplyRule">
                                    <option value="1" @if($applyRule == 1) selected="selected" @endif>
                                        Áp dụng
                                    </option>
                                    <option value="2" @if($applyRule == 2) selected="selected" @endif>
                                        Không áp dụng (báo giờ làm)
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    {{--them bo phan--}}
                    <div class="row qc-padding-top-10">
                        <div id="qc_staff_permission_contain_edit"
                             class="qc-margin-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            @if(count($dataStaffWorkDepartment) > 0)
                                @foreach($dataStaffWorkDepartment as $staffWorkDepartment)
                                    @include('ad3d.system.staff.add-department', compact('staffWorkDepartment','dataDepartment','dataRank'))
                                @endforeach
                            @else
                                @include('ad3d.system.staff.add-department', compact('staffWorkDepartment','dataDepartment','dataRank'))
                            @endif
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
                             @if($mobileStatus) style="padding: 0 0;" @endif>
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <a class="qc_staff_department_edit_add_action qc-link-green"
                                   data-href="{!! route('qc.ad3d.system.staff.department.add') !!}">
                                    <em>+ Thêm bộ phận</em>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row qc-padding-top-10 qc-padding-bot-10">
                        <div class="col-sx-12 col-sm-12 col-md-8 col-lg-8 ">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label class="radio-inline">
                                    <input type="radio" checked="checked" name="salaryStatus" value="1"> Theo bảng lương
                                    cũ
                                </label>
                            </div>
                        </div>
                        <div class="text-right  col-sx-12 col-sm-12 col-md-4 col-lg-4 ">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <button type="button" class="qc_save btn btn-sm btn-primary">Lưu</button>
                                <button type="button" class="btn btn-sm btn-default"
                                        onclick="qc_main.hide('#frmStaffWorkEdit'); qc_main.show('#staffWorkEditShow');">
                                    Đóng
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            {{--THONG TIN LUONG--}}
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="qc-color-red col-xs-12 col-sm-12 col-dm-12 col-lg-12"
                         style="border-bottom: 2px solid black;background-color: whitesmoke;">
                        <i class="glyphicon glyphicon-record"></i>
                        <em>Lương</em>
                        <a class="qc-link-red pull-right glyphicon glyphicon-pencil" title="Sửa thông tin"
                           onclick="qc_main.hide('#staffSalaryEditShow'); qc_main.show('#frmStaffSalaryEdit');"></a>
                    </div>
                </div>
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
                <div id="staffSalaryEditShow" class="row">
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
                <form id="frmStaffSalaryEdit" class="frmStaffSalaryEdit qc-display-none" name="frmStaffSalaryEdit"
                      role="form" method="post"
                      action="{!! route('qc.ad3d.system.staff.salary.edit.post', $staffId) !!}">
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
                         @if($mobileStatus) style="padding: 0 0;" @endif>
                        <div class="frm_staff_salary_notify form-group form-group-sm qc-color-red"></div>
                    </div>
                    <div class="row">
                        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label class="qc-color-red">Tổng lương <i
                                            class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                <input type="text" class="form-control" name="txtTotalSalary"
                                       placeholder="Tổng lương nhân viên"
                                       onkeyup="qc_ad3d_staff_staff.edit.checkInputTotalSalary();"
                                       value="{!! $hFunction->currencyFormat($totalSalary) !!}">
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>Lương cơ bản <i
                                            class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                <input type="text" class="form-control" name="txtSalary" placeholder="VND"
                                       onkeyup="qc_ad3d_staff_staff.edit.checkInputSalary(this);"
                                       value="{!! $hFunction->currencyFormat($salary) !!}">
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>Bảo hiểm(%):</label>
                                <input type="text" class="form-control" name="txtInsurance" title="Bảo hiểm"
                                       placeholder="Bảo hiêm " disabled="disabled"
                                       value="{!! $hFunction->currencyFormat($insurance) !!}">
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>Ngày nghỉ tính lương (1 Số ngày):</label>
                                <input type="text" class="form-control" name="txtDateOff" disabled="disabled"
                                       title="Số ngày nghỉ trong tháng"
                                       value="{!! $hFunction->currencyFormat($dateOff) !!}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sx-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label class="qc-color-red">Tổng Lương còn lại (Không cố định) <i
                                            class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                <input type="text" class="form-control" name="txtTotalSalaryRemain" disabled="disabled"
                                       value="{!! $hFunction->currencyFormat($totalSalary - $salary - $insurance - $dateOff) !!}">
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-9 col-lg-9">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label class="qc-color-red">Mức lương chưa phát <i
                                            class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                <input type="text" class="form-control" name="txtTotalSalaryRemainShow"
                                       disabled="disabled" value="0">
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>P/C Điện thoại(VNĐ) <i
                                            class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                <input type="text" class="form-control" name="txtUsePhone"
                                       onkeyup="qc_ad3d_staff_staff.edit.showInputRemain(this);"
                                       title="Phụ cấp sử dụng điện thoại"
                                       placeholder="VND"
                                       value="{!! $hFunction->currencyFormat($usePhone) !!}">
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>P/C Trách nhiệm(VNĐ):</label>
                                <input type="text" class="form-control" name="txtResponsibility" placeholder="VND"
                                       onkeyup="qc_ad3d_staff_staff.edit.showInputRemain(this);"
                                       title="Phụ cấp trách nhiệm thi công"
                                       value="{!! $hFunction->currencyFormat($responsibility) !!}">
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>P/C đi lại(VNĐ) <i
                                            class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                <input type="text" class="form-control" name="txtFuel"
                                       title="Phụ cấp sử dụng điện thoại" placeholder="VND"
                                       onkeyup="qc_ad3d_staff_staff.edit.showInputRemain(this);"
                                       value="{!! $hFunction->currencyFormat($fuel) !!}">
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>Phu cấp tăng ca /1h(VNĐ) <i
                                            class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                <input type="text" class="form-control" name="txtOvertimeHour"
                                       title="Phụ cấp ăn uống khi tăng ca" placeholder="VND"
                                       onkeyup="qc_main.showFormatCurrency(this);"
                                       value="{!! $hFunction->currencyFormat($overtimeHour) !!}">
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>Số TK ngân hàng:</label>
                                <input type="text" class="form-control" name="txtBankAccount"
                                       title="Số tại khoản ngân hàng" placeholder="Số tại khoản ngân hàng"
                                       value="{!! $bankAccount !!}">
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>Ngân hàng</label>
                                <select class="form-control" name="cbBankName">
                                    <option value="ACB">ACB</option>
                                </select>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="text-right qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12 ">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <button type="button" class="qc_save btn btn-sm btn-primary">Lưu</button>
                                <button type="reset" class="btn btn-sm btn-default">Nhập lại</button>
                                <button type="button" class="frmStaffSalaryEdit_close btn btn-sm btn-default"
                                        onclick="qc_main.hide('#frmStaffSalaryEdit'); qc_main.show('#staffSalaryEditShow');">
                                    Đóng
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
