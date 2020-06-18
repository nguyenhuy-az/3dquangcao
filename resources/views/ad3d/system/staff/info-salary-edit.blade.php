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
    ///$companyId = $dataCompanyStaffWork->companyId();
    ///$companyName = $dataCompanyStaffWork->company->name();
    ///$level = $dataCompanyStaffWork->level();
    ///$beginDate = $dataCompanyStaffWork->beginDate();
   /// $dataStaffWorkDepartment = $dataCompanyStaffWork->staffWorkDepartmentInfoActivity();
    $dataStaffWorkSalary = $dataCompanyStaffWork->staffWorkSalaryActivity();
} else {
    # thiet ke cu
    ///$companyId = null;
    ///$companyName = null;
    ///$level = null;
    ///$beginDate = $hFunction->carbonNow();
    //$dataStaffWorkDepartment = null;
    $dataStaffWorkSalary = null;
   // $departmentStaff = null;
}
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
<form id="frmStaffInfoSalaryEdit" class="frmStaffInfoSalaryEdit" name="frmStaffInfoSalaryEdit" role="form" method="post"
      action="{!! route('qc.ad3d.system.staff_info.salary.edit.post', $staffId) !!}">
    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
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
                <input type="text" class="form-control" name="txtSalary"
                       placeholder="VND"
                       onkeyup="qc_ad3d_staff_staff.edit.checkInputSalary(this);"
                       value="{!! $hFunction->currencyFormat($salary) !!}">
            </div>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-3 col-lg-3">
            <div class="form-group form-group-sm" style="margin: 0;">
                <label>Bảo hiểm(%):</label>
                <input type="text" class="form-control" name="txtInsurance"
                       title="Bảo hiểm"
                       placeholder="Bảo hiêm " disabled="disabled"
                       value="{!! $hFunction->currencyFormat($insurance) !!}">
            </div>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-3 col-lg-3">
            <div class="form-group form-group-sm" style="margin: 0;">
                <label>Ngày nghỉ tính lương (1 Số ngày):</label>
                <input type="text" class="form-control" name="txtDateOff"
                       disabled="disabled"
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
                <input type="text" class="form-control" name="txtTotalSalaryRemain"
                       disabled="disabled"
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
                <input type="text" class="form-control" name="txtResponsibility"
                       placeholder="VND"
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
                       title="Số tại khoản ngân hàng"
                       placeholder="Số tại khoản ngân hàng"
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
                <button type="button" class="qc_save btn btn-sm btn-primary">Lưu thay đổi
                </button>
                <button type="reset" class="btn btn-sm btn-default">Nhập lại</button>
                <button type="button"
                        class="frmStaffSalaryEdit_close btn btn-sm btn-default"
                        onclick="qc_main.window_reload();">
                    Đóng
                </button>
            </div>
        </div>
    </div>
</form>
