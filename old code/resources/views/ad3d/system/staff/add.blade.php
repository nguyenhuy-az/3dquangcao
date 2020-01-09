<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 *
 * dataCompany
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$dataStaffLogin = $modelStaff->loginStaffInfo();
$companyLoginId = $dataStaffLogin->companyId();
?>
@extends('ad3d.system.staff.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="padding-top: 0;padding-bottom: 0;">
            <a class="qc-link-red" href="{!! route('qc.ad3d.system.staff.get') !!}">
                <i class="glyphicon glyphicon-backward"></i> Trởlại
            </a>
        </div>
        <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <h3>THÊM NHÂN VIÊN</h3>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <form id="frmAdd" class="frmAdd" name="frmAdd" role="form" method="post" enctype="multipart/form-data"
                  action="{!! route('qc.ad3d.system.staff.add.post') !!}">
                <div class="row">
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
                         @if($mobileStatus) style="padding: 0 0;" @endif>
                        @if (Session::has('notifyAdd'))
                            <div class="form-group form-group-sm text-center qc-color-red">
                                {!! Session::get('notifyAdd') !!}
                                <?php
                                Session::forget('notifyAdd');
                                ?>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-dm-12 col-lg-12"
                             style="border-bottom: 2px solid black;background-color: whitesmoke;">
                            <i class="glyphicon glyphicon-record"></i>
                            <em>Thông tin cơ bản</em>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>Họ <i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                <input type="text" class="form-control" name="txtFirstName" placeholder="Nhập họ"
                                       value="">
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="form-group form-group-sm qc-padding-none" style="margin: 0;">
                                <label>Tên <i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                <input type="text" class="form-control" name="txtLastName" placeholder="Nhập Tên"
                                       value="">
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>Chứng minh thư <i
                                            class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                <input type="text" class="form-control" name="txtIdentityCard"
                                       placeholder="Số chứng minh nhân dân" value="">
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
                                    <option value="1">Nam</option>
                                    <option value="0">Nữ</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>Ngày sinh</label>
                                <input id="txtBirthday" type="text" class="form-control" name="txtBirthday"
                                       placeholder="Ngày sinh" value="">
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
                                       onkeyup="qc_main.showNumberInput(this);" placeholder="Số điện thoại" value="">
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
                                       placeholder="Thông tin địa chỉ" value="">
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>Email</label>
                                <input type="text" class="form-control" name="txtEmail" placeholder="Địa chỉ email"
                                       value="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4"
                             @if($mobileStatus) style="padding: 0 0;" @endif>
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>Ảnh cá nhân</label>
                                <input type="file" name="txtImage" title="Ảnh cá nhân" value="">
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4"
                             @if($mobileStatus) style="padding: 0 0;" @endif>
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>Ảnh CMND mặt trước</label>
                                <input type="file" name="txtIdentityCardFront" title="Ảnh CMND mặt trước" value="">
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4"
                             @if($mobileStatus) style="padding: 0 0;" @endif>
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>Ảnh CMND mặt sau</label>
                                <input type="file" name="txtIdentityCardBack" title="Ảnh CMND mặt sau" value="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-dm-12 col-lg-12"
                             style="border-bottom: 2px solid black;background-color: whitesmoke;">
                            <i class="glyphicon glyphicon-record"></i>
                            <em>Làm việc</em>
                        </div>
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
                                    <option value="">
                                        Chọn công ty
                                    </option>
                                    @if(count($dataCompany) > 0)
                                        @foreach($dataCompany as $company)
                                            <option value="{!! $company->companyId() !!}">
                                                {!! $company->name() !!}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4"
                             @if($mobileStatus) style="padding: 0 0;" @endif>
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>
                                    Cấp bậc truy cập
                                    <i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                <em style="color: brown;">Cấp < 4 sẽ truy cập vào trang quản lý</em>
                                <select class="form-control" name="cbLevel">
                                    <option value="1">1
                                    </option>
                                    <option value="2">2
                                    </option>
                                    <option value="3">3
                                    </option>
                                    <option value="4">4
                                    </option>
                                    <option value="5"  selected="selected">5
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>
                                    Ngày vào <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                </label>
                                <input id="txtDateWork" type="text" class="form-control" name="txtDateWork" value="">
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
                                    <option value="1" selected="selected">
                                        Chính thức
                                    </option>
                                    <option value="2">
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
                                    <option value="1" selected="selected">
                                        Áp dụng
                                    </option>
                                    <option value="2">
                                        Không áp dụng (báo giờ làm)
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row qc-padding-top-10">
                        <div id="qc_staff_permission_contain"
                             class="qc-margin-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            @include('ad3d.system.staff.add-department', compact('dataDepartment','dataRank'))
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
                             @if($mobileStatus) style="padding: 0 0;" @endif>
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <a class="qc_staff_department_add_action qc-link-green"
                                   data-href="{!! route('qc.ad3d.system.staff.department.add') !!}">
                                    <em>+ Thêm bộ phận</em>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-dm-12 col-lg-12"
                             style="border-bottom: 2px solid black;background-color: whitesmoke;">
                            <i class="glyphicon glyphicon-record"></i>
                            <em>Lương</em>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label class="qc-color-red">Tổng lương <i
                                            class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                <input  type="text" class="form-control" name="txtTotalSalary" placeholder="Tổng lương nhân viên"
                                       onkeyup="qc_ad3d_staff_staff.add.showInput();" value="0">
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label >Lương cơ bản <i
                                            class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                <input type="text" class="form-control" name="txtSalary" placeholder="VND"
                                       onkeyup="qc_ad3d_staff_staff.add.showInput();" value="0">
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>Bảo hiểm 21,5% LCB:</label>
                                <input type="text" class="form-control" name="txtInsurance" title="Bảo hiểm" placeholder="Bảo hiêm" disabled="disabled" value="0">
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label> Ngày nghỉ tính lương (1 ngày):</label>
                                <input type="text" class="form-control" name="txtDateOff"
                                       title="Số ngày nghỉ trong tháng được lãnh lương" disabled="disabled" placeholder="Lương Ngày phép" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sx-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label class="qc-color-red">Tổng Lương còn lại (Không cố định) <i
                                            class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                <input  type="text" class="form-control" name="txtTotalSalaryRemain" disabled="disabled" value="0">
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-9 col-lg-9">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label class="qc-color-red">Mức lương chưa phát  <i
                                            class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                <input  type="text" class="form-control" name="txtTotalSalaryRemainShow" disabled="disabled" value="0">
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>P/C Điện thoại(VNĐ) <i
                                            class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                <input type="text" class="form-control" name="txtUsePhone"
                                       onkeyup="qc_ad3d_staff_staff.add.showInputRemain(this);" title="Phụ cấp sử dụng điện thoại"
                                       placeholder="VND" value="0">
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>P/C Trách nhiệm(VNĐ):</label>
                                <input type="text" class="form-control" name="txtResponsibility"
                                       onkeyup="qc_ad3d_staff_staff.add.showInputRemain(this);" title="Phụ cấp trách nhiệm thi công"
                                       placeholder="VND" value="0">
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>P/C đi lại(VNĐ) <i
                                            class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                <input type="text" class="form-control" name="txtFuel"
                                       title="Phụ cấp sử dụng điện thoại" placeholder="VND"
                                       onkeyup="qc_ad3d_staff_staff.add.showInputRemain(this);" value="0">
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>P/C tăng ca theo giờ(VNĐ) <i
                                            class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                <input type="text" class="form-control" name="txtOvertimeHour"
                                       title="Phụ cấp ăn uống khi tăng ca" placeholder="VND"
                                       onkeyup="qc_main.showFormatCurrency(this);" value="{!! $hFunction->currencyFormat(10000) !!}">
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>Số TK nhận lương:</label>
                                <input type="text" class="form-control" name="txtBankAccount"
                                       title="Số tại khoản ngân hàng" placeholder="Số tại khoản ngân hàng">
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
                </div>
                <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="text-center qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12 ">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <button type="button" class="qc_save btn btn-sm btn-primary">Lưu</button>
                                <button type="reset" class="qc_reset btn btn-sm btn-default">Nhập lại</button>
                                <button type="button" class="btn btn-sm btn-default" onclick="qc_main.page_back();">
                                    Đóng
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection
