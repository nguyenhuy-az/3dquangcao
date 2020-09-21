/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_system_job_application = {
    filter: function (href) {
        qc_main.url_replace(href);
    },
    view: function (listObject) {
        qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-view') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
    },
    add: {
        /*addDepartment: function (href) {
         qc_ad3d_submit.ajaxNotReload(href, '#qc_staff_permission_contain', false);
         },
         */
        save: function (formObject) {
            var cbCompany = $(formObject).find("select[name='cbCompany']");
            var txtFirstName = $(formObject).find("input[name='txtFirstName']");
            var txtLastName = $(formObject).find("input[name='txtLastName']");
            var txtIdentityCard = $(formObject).find("input[name='txtIdentityCard']");
            var cbGender = $(formObject).find("select[name='cbGender']");
            var txtAddress = $(formObject).find("input[name='txtAddress']");
            var txtEmail = $(formObject).find("input[name='txtEmail']");
            var txtDateWork = $(formObject).find("input[name='txtDateWork']");
            var txtSalary = $(formObject).find("input[name='txtSalary']");
            var txtTotalSalary = $(formObject).find("input[name='txtTotalSalary']");
            var txtTotalSalaryRemainShow = $(formObject).find("input[name='txtTotalSalaryRemainShow']");
            if (qc_main.check.inputNull(txtFirstName, 'Nhập họ')) {
                return false;
            } else {
                if (qc_main.check.inputMaxLength(txtFirstName, 30, 'Họ không dài quá 30 ký tự')) return false;
            }
            if (qc_main.check.inputNull(txtLastName, 'Nhập tên')) {
                return false;
            } else {
                if (qc_main.check.inputMaxLength(txtLastName, 20, 'Tên dài không quá 30 ký tự')) return false;
            }
            if (qc_main.check.inputNull(txtIdentityCard, 'Nhập chứng minh thư')) {
                return false;
            } else {
                if (qc_main.check.inputMaxLength(txtIdentityCard, 20, 'Chứng minh thư dài không quá 20 ký tự')) return false;
            }

            if (qc_main.check.inputNull(cbGender, 'Chọn giới tính')) {
                return false;
            }

            if (qc_main.check.inputNull(txtAddress, 'Nhập địa chỉ')) {
                if (qc_main.check.inputMaxLength(txtAddress, 50, 'Địa chỉ dài quá 50 ký tự')) return false;
                return false;
            }

            if (txtEmail.val().length > 0) {
                var email = txtEmail.val();
                if (!qc_main.check.emailJavascript(email)) {
                    alert('Email không hợp lệ');
                    txtEmail.focus();
                    return false;
                }
            }

            if (qc_main.check.inputNull(cbCompany, 'Chọn công ty')) {
                return false;
            }

            if (qc_main.check.inputNull(txtDateWork, 'Nhập Ngày vào')) {
                return false;
            }

            // thông tin chon vị tri lam viec
            if (!qc_ad3d_system_job_application.add.checkSelectDepartmentManageRank()) {
                if (!qc_ad3d_system_job_application.add.checkSelectDepartmentStaffRank()) {
                    alert('Phải chọn 1 vị trí làm việc');
                    return false;
                }
            }
            // thong tin luong
            if (txtTotalSalary.val() == '' || txtTotalSalary.val() == 0) {
                txtTotalSalary.focus();
                alert('Nhập lương tổng');
                return false;
            } else {
                var numberTotalSalaryRemainShow = qc_main.getNumberInput(txtTotalSalaryRemainShow.val());
                if (numberTotalSalaryRemainShow > 0) {
                    alert('Phải phân bổ hết mức lương chưa phát');
                    return false;
                } else {
                    //--- qc_ad3d_submit.ajaxFormNotReload('#frmAdd', '', false);
                    qc_ad3d_submit.ajaxFormHasReload('#frmAdd', '', false);
                    qc_main.scrollTop();
                    $('.qc_reset').click();
                }
            }
            ;
        },
        // kiem tra co chon bo phan cap quan ly
        checkSelectDepartmentManageRank: function () {
            var checkStatus = false;
            $('#frmAdd .departmentManageRank').filter(function () {
                if ($(this).is(':checked')) checkStatus = true;
            });
            return checkStatus;
        },
        // kiem tra co chon bo phan cap nhan vien
        checkSelectDepartmentStaffRank: function () {
            var checkStatus = false;
            $('#frmAdd .departmentStaffRank').filter(function () {
                if ($(this).is(':checked')) checkStatus = true;
            });
            return checkStatus;
        },
        showInput: function () {
            var txtTotalSalary = $('#frmAdd').find("input[name='txtTotalSalary']");
            var txtTotalSalaryRemain = $('#frmAdd').find("input[name='txtTotalSalaryRemain']");
            var txtTotalSalaryRemainShow = $('#frmAdd').find("input[name='txtTotalSalaryRemainShow']");
            var txtSalary = $('#frmAdd').find("input[name='txtSalary']");
            var txtResponsibility = $('#frmAdd').find("input[name='txtResponsibility']");
            var txtInsurance = $('#frmAdd').find("input[name='txtInsurance']");
            var txtDateOff = $('#frmAdd').find("input[name='txtDateOff']");
            var txtUsePhone = $('#frmAdd').find("input[name='txtUsePhone']");
            var txtFuel = $('#frmAdd').find("input[name='txtFuel']");
            if (qc_main.check.inputNull(txtTotalSalary, 'Phải nhập tổng lương')) {
                $(txtTotalSalary).focus();
                $(txtSalary).val(0);
                return false;
            } else {
                var valTotalSalary = txtTotalSalary.val();
                var numberTotalSalary = parseInt(qc_main.getNumberInput(valTotalSalary));

                var valSalary = txtSalary.val();
                var numberSalary = parseInt(qc_main.getNumberInput(valSalary)); //chuyen sang kieu so

                var valInsurance = txtInsurance.val();
                var numberInsurance = parseInt(qc_main.getNumberInput(valInsurance)); //chuyen sang kieu so

                var valDateOff = txtDateOff.val();
                var numberDateOff = parseInt(qc_main.getNumberInput(valDateOff)); //chuyen sang kieu so

                var valTotalSalaryRemain = txtTotalSalaryRemain.val();
                var numberTotalSalaryRemain = parseInt(qc_main.getNumberInput(valTotalSalaryRemain)); //chuyen sang kieu so

                //hien so tien ngay nghi
                if (numberTotalSalary > 0) {
                    var numberDateOffNew = Math.floor(parseInt(numberTotalSalary / 26));
                    txtDateOff.val(qc_main.formatCurrency(String(numberDateOffNew)));
                    qc_main.showFormatCurrency(txtTotalSalary);
                    numberTotalSalaryRemain = numberTotalSalary - numberSalary - (parseInt(numberSalary) * 0.215) - numberDateOffNew;
                    if (numberTotalSalaryRemain > 0) {
                        txtTotalSalaryRemain.val(qc_main.formatCurrency(String(numberTotalSalaryRemain)));
                        txtTotalSalaryRemainShow.val(qc_main.formatCurrency(String(numberTotalSalaryRemain)));
                    } else {
                        txtTotalSalaryRemain.val(0);
                        txtTotalSalaryRemainShow.val(0);
                    }
                } else {
                    txtDateOff.val(0);
                }

                //xu ly luong co ban
                if (numberSalary > numberTotalSalary) {
                    alert('Lương cơ bản phải nhỏ hơn lương tổng');
                    var stringSalary = String(numberSalary);
                    numberSalary = stringSalary.substring(0, stringSalary.length - 1);// xoa bo so moi nhap
                    txtSalary.val(qc_main.formatCurrency(numberSalary));
                    $(txtSalary).focus();
                    if (parseInt(numberSalary) > 0) {
                        txtInsurance.val(qc_main.formatCurrency(String(parseInt(numberSalary) * 0.215)));
                    } else {
                        txtInsurance.val(0);
                    }
                    return false;
                } else {
                    qc_main.showFormatCurrency(txtSalary);
                    if (numberSalary > (numberTotalSalary * 0.785)) { //luong co ban + bao hiem lon hon luong tong
                        txtInsurance.val(0);
                    } else {
                        if (numberSalary > 0) {
                            txtInsurance.val(qc_main.formatCurrency(String(numberSalary * 0.215)));
                        } else {
                            txtInsurance.val(0);
                        }
                    }


                }
                txtUsePhone.val(0);
                txtResponsibility.val(0);
                txtFuel.val(0);

            }
            //var cbCompany = $('#frmAdd').find("input[name='txtTotalSalary']");
        },
        showInputRemain: function (objectInput) {
            var txtTotalSalaryRemain = $('#frmAdd').find("input[name='txtTotalSalaryRemain']");
            var txtTotalSalaryRemainShow = $('#frmAdd').find("input[name='txtTotalSalaryRemainShow']");
            var txtUsePhone = $('#frmAdd').find("input[name='txtUsePhone']");
            var txtResponsibility = $('#frmAdd').find("input[name='txtResponsibility']");
            var txtFuel = $('#frmAdd').find("input[name='txtFuel']");

            var valTotalSalaryRemain = txtTotalSalaryRemain.val();
            if (valTotalSalaryRemain == '') valTotalSalaryRemain = '0';
            var numberTotalSalaryRemain = parseInt(qc_main.getNumberInput(valTotalSalaryRemain)); //chuyen sang kieu so

            var valTotalSalaryRemainShorw = txtTotalSalaryRemainShow.val();
            if (valTotalSalaryRemainShorw == 0) valTotalSalaryRemainShorw = '0';
            var numberTotalSalaryRemainShow = parseInt(qc_main.getNumberInput(valTotalSalaryRemainShorw)); //chuyen sang kieu so

            var valUsePhone = txtUsePhone.val();
            if (valUsePhone == '') valUsePhone = '0';
            var numberUsePhone = parseInt(qc_main.getNumberInput(valUsePhone)); //chuyen sang kieu so

            var valResponsibility = txtResponsibility.val();
            if (valResponsibility == '') valResponsibility = '0';
            var numberResponsibility = parseInt(qc_main.getNumberInput(valResponsibility)); //chuyen sang kieu so

            var valFuel = txtFuel.val();
            if (valFuel == '') valFuel = '0';
            var numberFuel = parseInt(qc_main.getNumberInput(valFuel)); //chuyen sang kieu so

            var valObjectInput = $(objectInput).val();
            if (valObjectInput == '') valObjectInput = '0';
            var numberObjectInput = parseInt(qc_main.getNumberInput(valObjectInput)); //chuyen sang kieu so

            var totalCheck = parseInt(numberUsePhone) + parseInt(numberResponsibility) + parseInt(numberFuel);

            //alert(numberTotalSalaryRemainShow  + '--' + numberUsePhone + '--' + numberResponsibility + '--' + numberFuel);

            if (totalCheck > numberTotalSalaryRemain) {
                alert('Không được nhập vượt lương tổng còn lại');
                var stringObjectInput = String(numberObjectInput);
                valObjectInput = stringObjectInput.substring(0, stringObjectInput.length - 1);// xoa bo so moi nhap
                $(objectInput).val(qc_main.formatCurrency(valObjectInput));
                $(objectInput).focus();
                //txtTotalSalaryRemainShow.val(qc_main.formatCurrency(String(numberTotalSalaryRemain - parseInt(valObjectInput) - parseInt(numberResponsibility) - parseInt(numberFuel))));
                return false;
            } else {
                var newNumberTotalSalaryRemainShow = numberTotalSalaryRemain - (numberUsePhone + numberResponsibility + numberFuel);
                //alert(numberTotalSalaryRemain + '--' + numberUsePhone + '--' + numberResponsibility + '--' + numberFuel);
                txtTotalSalaryRemainShow.val(qc_main.formatCurrency(String(newNumberTotalSalaryRemainShow)));
                $(objectInput).val(qc_main.formatCurrency(String(numberObjectInput)));
            }

        }
    },

    delete: function (listObject) {
        if (confirm('Bạn muốn xóa nhân viên này')) {
            qc_ad3d_submit.ajaxHasReload($(listObject).parents('.qc_ad3d_list_content').data('href-del') + '/' + $(listObject).data('object'), '', false);
        }
    }
}

//-------------------- loc thong tin ------------
$(document).ready(function () {
    $('.qc_ad3d_list_content').on('change', '.cbCompanyFilter', function () {
        var href = $(this).data('href') + '/' + $(this).val() + '/' + $('.cbConfirmStatusFilter').val();
        qc_ad3d_system_job_application.filter(href);
    });
    $('.qc_ad3d_list_content').on('change', '.cbConfirmStatusFilter', function () {
        var href = $(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + $(this).val();
        qc_ad3d_system_job_application.filter(href);
    });
    /*$('body').on('click', '.qc_ad_frm_reset_pass .qc_save ', function () {
     qc_ad3d_system_job_application.resetPass.save($(this).parents('.qc_ad_frm_reset_pass'));
     });*/
});

//-------------------- change account ------------
$(document).ready(function () {
    $('.qc_ad3d_index_content').on('click', '.frmChangeAccount .qc_save', function () {
        //qc_ad3d_system_job_application.changeAccount($(this).parents('.frmChangeAccount'));
    })
});