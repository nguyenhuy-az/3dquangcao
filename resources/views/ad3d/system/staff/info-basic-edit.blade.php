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
?>
<form id="frmStaffInfoBasicEdit" class="frmStaffInfoBasicEdit"
      name="frmStaffInfoBasicEdit" role="form" method="post"
      action="{!! route('qc.ad3d.system.staff.info_basic.edit.post',$staffId ) !!}">
    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="frm_info_edit_notify form-group form-group-sm qc-color-red"></div>
    </div>
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
            <div class="form-group form-group-sm" style="margin: 0;">
                <label>Họ <i
                            class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                <input type="text" class="form-control" name="txtFirstName"
                       placeholder="Nhập họ"
                       value="{!! $firstName !!}">
            </div>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
            <div class="form-group form-group-sm qc-padding-none" style="margin: 0;">
                <label>Tên <i
                            class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                <input type="text" class="form-control" name="txtLastName"
                       placeholder="Nhập Tên"
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
        <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
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
                <input id="txtBirthday" type="text" class="form-control"
                       name="txtBirthday"
                       placeholder="Ngày sinh"
                       value="{!! $birthday !!}">
                <script type="text/javascript">
                    qc_main.setDatepicker('#txtBirthday');
                </script>

            </div>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
            <div class="form-group form-group-sm" style="margin: 0;">
                <label>Điện thoại<i
                            class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                <input type="text" class="form-control" name="txtPhone"
                       onkeyup="qc_main.showNumberInput(this);"
                       placeholder="Số điện thoại"
                       value="{!! $phone !!}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-8 col-lg-8">
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
                <input type="text" class="form-control" name="txtEmail"
                       placeholder="Địa chỉ email"
                       value="{!! $email !!}">
            </div>
        </div>
    </div>

    <div class="text-right qc-padding-top-10 qc-padding-bot-10 col-sx-12 col-sm-12 col-md-12 col-lg-12 ">
        <div class="form-group form-group-sm" style="margin: 0;">
            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
            <button type="button" class="qc_save btn btn-sm btn-primary">Lưu thay đổi
            </button>
            <button type="button" class="btn btn-sm btn-default" onclick="qc_main.window_reload();">
                Đóng
            </button>
        </div>
    </div>
</form>
