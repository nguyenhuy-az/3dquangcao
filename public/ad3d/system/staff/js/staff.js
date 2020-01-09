/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_staff_staff = {
    filter: function (href) {
        qc_main.url_replace(href);
    },
    view: function (listObject) {
        qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-view') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
    },
    changePass: function (formObject) {
        var txtOldPass = $(formObject).find("input[name='txtOldPass']");
        var txtNewPass = $(formObject).find("input[name='txtNewPass']");
        var txtConfirmPass = $(formObject).find("input[name='txtConfirmPass']");
        if (qc_main.check.inputNull(txtOldPass, 'Nhập mật khẩu')) {
            return false;
        }
        if (qc_main.check.inputNull(txtNewPass, 'Nhập mật khẩu mới')) {
            return false;
        }

        if (qc_main.check.inputNull(txtConfirmPass, 'Nhập lại mật khẩu')) {
            return false;
        } else {
            if (txtNewPass.val() !== txtConfirmPass.val()) {
                alert('Mật khẩu xác nhận không đúng');
                txtConfirmPass.focus();
                return false;
            } else {
                //qc_ad3d_submit.normalForm(formObject);
                qc_ad3d_submit.ajaxFormHasReload(formObject, '', false);
                qc_main.scrollTop();
            }
        }
    },
    changeAccount: function (formObject) {
        var txtNewAccount = $(formObject).find("input[name='txtNewAccount']");
        var txtConfirmPass = $(formObject).find("input[name='txtConfirmPass']");
        if (qc_main.check.inputNull(txtNewAccount, 'Nhập tên tài khoản')) {
            return false;
        }
        if (qc_main.check.inputNull(txtConfirmPass, 'Nhập mật khẩu xác nhận')) {
            return false;
        } else {
            qc_ad3d_submit.ajaxFormHasReload(formObject, '', false);
            qc_main.scrollTop();
        }
    },
    add: {
        addDepartment: function (href) {
            qc_ad3d_submit.ajaxNotReload(href, '#qc_staff_permission_contain', false);
        },

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
                if (qc_main.check.inputMaxLength(txtFirstName, 30, 'Hô không dài quá 30 ký tự')) return false;
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

            if (txtTotalSalary.val() == '' || txtTotalSalary.val() == 0) {
                txtTotalSalary.focus();
                alert('Nhập lương tổng');
                return false;
            } else {
                var numberTotalSalaryRemainShow = qc_main.getNumberInput(txtTotalSalaryRemainShow.val());
                if (numberTotalSalaryRemainShow > 0) {
                    alert('Phải phân bổ hết mức lương chưa phát');
                    return false;
                }
            }
            // thông tin bộ pha
            if ($('#frmAdd .qc_ad3d_staff_add_department').length > 0) {
                qc_ad3d_staff_staff.add.checkDepartmentInput();
            } else {
                alert('Nhập bộ phận');
                return false;
            }
        },
        checkDepartmentInput: function () {
            $('#frmAdd .qc_ad3d_staff_add_department').filter(function () {
                if ($(this).is(':last-child')) {
                    var cbDepartment = $(this).find('.cbDepartment');
                    var cbRank = $(this).find('.cbRank');
                    if (qc_main.check.inputNull(cbDepartment, 'Chọn bộ phận')) {
                        cbDepartment.focus();
                        return false;
                    }

                    if (cbRank.val() == 0 || cbRank.val() == '') {
                        alert('Chọn cấp bậc');
                        cbRank.focus();
                        return false;
                    } else {
                        qc_ad3d_submit.ajaxFormHasReload('#frmAdd', '', false);
                        qc_main.scrollTop();
                        $('.qc_reset').click();
                    }
                }

            });
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
    edit: {
        addDepartment: function (href) {
            $.ajax({
                url: href,
                type: 'GET',
                cache: false,
                data: {},
                beforeSend: function () {
                    qc_ad3d.loadStatus();
                },
                success: function (data) {
                    if (data) {
                        $('#qc_staff_permission_contain_edit').append(data);
                    }
                },
                complete: function () {
                    qc_ad3d.loadStatus();
                }
            });
        },
        get: function (listObject) {
            qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-edit') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
        },
        saveSalary: function (frm) {
            var txtTotalSalary = $(frm).find("input[name='txtTotalSalary']");
            var txtTotalSalaryRemainShow = $(frm).find("input[name='txtTotalSalaryRemainShow']");
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
                    if (confirm('Bạng muốn cập nhật bảng lương?')) {
                        qc_ad3d_submit.ajaxFormHasReload(frm, '.frm_staff_salary_notify', true);
                        $('#qc_container_content').animate({
                            scrollTop: 0
                        })
                    }
                }
            }

        },
        saveInfo: function (frm) {
            var txtFirstName = $(frm).find("input[name='txtFirstName']");
            var txtLastName = $(frm).find("input[name='txtLastName']");
            var txtIdentityCard = $(frm).find("input[name='txtIdentityCard']");
            var cbGender = $(frm).find("select[name='cbGender']");
            var txtPhone = $(frm).find("input[name='txtPhone']");
            var txtAddress = $(frm).find("input[name='txtAddress']");
            var txtEmail = $(frm).find("input[name='txtEmail']");
            if (qc_main.check.inputNull(txtFirstName, 'Nhập họ')) {
                return false;
            } else {
                if (qc_main.check.inputMaxLength(txtFirstName, 30, 'Hô không dài quá 30 ký tự')) return false;
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

            if (qc_main.check.inputNull(txtPhone, 'Nhập số điện thoại')) {
                return false;
            } else {
                if (qc_main.check.inputMaxLength(txtPhone, 30, 'Số điện thoại không quá 30 ký tự')) return false;
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
            if (qc_main.check.inputNull(txtAddress, 'Nhập địa chỉ')) {
                if (qc_main.check.inputMaxLength(txtAddress, 50, 'Địa chỉ dài quá 50 ký tự')) return false;
                return false;
            } else {
                qc_ad3d_submit.ajaxFormHasReload('#frmStaffInfoEdit', '.frm_info_edit_notify', true);
                $('#qc_container_content').animate({
                    scrollTop: 0
                })
            }

        },
        saveWork: function (frm) {
            var txtDateWork = $(frm).find("input[name='txtDateWork']");
            var containNotify = $(frm).find('.frm_notify');
            if (qc_main.check.inputNull(txtDateWork, 'Nhập Ngày vào')) {
                return false;
            }
            // thông tin bộ pha
            if ($('#frmStaffWorkEdit .qc_ad3d_staff_add_department').length > 0) {
                qc_ad3d_staff_staff.edit.checkDepartmentInput();
            } else {
                alert('Chon bộ phận làm việc');
                return false;
            }

        },
        checkDepartmentInput: function () {
            $('#frmStaffWorkEdit .qc_ad3d_staff_add_department').filter(function () {
                if ($(this).is(':last-child')) {
                    var cbDepartment = $(this).find('.cbDepartment');
                    var cbRank = $(this).find('.cbRank');
                    if (qc_main.check.inputNull(cbDepartment, 'Chọn bộ phận')) {
                        cbDepartment.focus();
                        return false;
                    }

                    if (cbRank.val() == 0 || cbRank.val() == '') {
                        alert('Chọn cấp bậc');
                        cbRank.focus();
                        return false;
                    } else {
                        qc_ad3d_submit.normalForm('#frmStaffWorkEdit');
                        /*qc_ad3d_submit.ajaxFormHasReload('#frmStaffWorkEdit', '.frm_work_notify', true);
                         $('#qc_container_content').animate({
                         scrollTop: 0
                         })*/
                    }
                }

            });
        },
        checkInputTotalSalary: function () {
            var txtTotalSalary = $('#frmStaffSalaryEdit').find("input[name='txtTotalSalary']");
            var txtTotalSalaryRemain = $('#frmStaffSalaryEdit').find("input[name='txtTotalSalaryRemain']");
            var txtTotalSalaryRemainShow = $('#frmStaffSalaryEdit').find("input[name='txtTotalSalaryRemainShow']");
            var txtSalary = $('#frmStaffSalaryEdit').find("input[name='txtSalary']");
            var txtResponsibility = $('#frmStaffSalaryEdit').find("input[name='txtResponsibility']");
            var txtInsurance = $('#frmStaffSalaryEdit').find("input[name='txtInsurance']");
            var txtDateOff = $('#frmStaffSalaryEdit').find("input[name='txtDateOff']");
            var txtUsePhone = $('#frmStaffSalaryEdit').find("input[name='txtUsePhone']");
            var txtFuel = $('#frmStaffSalaryEdit').find("input[name='txtFuel']");

            var valTotalSalary = txtTotalSalary.val();
            if (valTotalSalary == '') valTotalSalary = '0';
            var numberTotalSalary = parseInt(qc_main.getNumberInput(valTotalSalary)); // chuyen sang dang so
            var numberDateOffNew = Math.floor(parseInt(numberTotalSalary / 26));
            txtDateOff.val(qc_main.formatCurrency(String(numberDateOffNew)));
            txtSalary.val(0);
            txtResponsibility.val(0);
            txtInsurance.val(0);
            txtUsePhone.val(0);
            txtFuel.val(0);

            var numberTotalSalaryRemain = numberTotalSalary - numberDateOffNew;
            txtTotalSalaryRemain.val(qc_main.formatCurrency(String(numberTotalSalaryRemain)));
            txtTotalSalaryRemainShow.val(qc_main.formatCurrency(String(numberTotalSalaryRemain)));
            txtTotalSalary.val(qc_main.formatCurrency(String(numberTotalSalary))); // hien lai dang tien te
        },
        checkInputSalary: function () {
            var txtTotalSalary = $('#frmStaffSalaryEdit').find("input[name='txtTotalSalary']");
            var txtTotalSalaryRemain = $('#frmStaffSalaryEdit').find("input[name='txtTotalSalaryRemain']");
            var txtTotalSalaryRemainShow = $('#frmStaffSalaryEdit').find("input[name='txtTotalSalaryRemainShow']");
            var txtSalary = $('#frmStaffSalaryEdit').find("input[name='txtSalary']");
            var txtResponsibility = $('#frmStaffSalaryEdit').find("input[name='txtResponsibility']");
            var txtInsurance = $('#frmStaffSalaryEdit').find("input[name='txtInsurance']");
            var txtDateOff = $('#frmStaffSalaryEdit').find("input[name='txtDateOff']");
            var txtUsePhone = $('#frmStaffSalaryEdit').find("input[name='txtUsePhone']");
            var txtFuel = $('#frmStaffSalaryEdit').find("input[name='txtFuel']");
            if (qc_main.check.inputNull(txtTotalSalary, 'Phải nhập tổng lương')) {
                $(txtTotalSalary).focus();
                (txtSalary).val(0);
                return false;
            } else {
                var valTotalSalary = txtTotalSalary.val();
                var numberTotalSalary = parseInt(qc_main.getNumberInput(valTotalSalary));

                var valSalary = txtSalary.val();
                if (valSalary == '') valSalary = '0';
                var numberSalary = parseInt(qc_main.getNumberInput(valSalary)); //chuyen sang kieu so

                var valInsurance = txtInsurance.val();
                var numberInsurance = parseInt(qc_main.getNumberInput(valInsurance)); //chuyen sang kieu so

                var valDateOff = txtDateOff.val();
                var numberDateOff = parseInt(qc_main.getNumberInput(valDateOff)); //chuyen sang kieu so

                var valTotalSalaryRemain = txtTotalSalaryRemain.val();
                var numberTotalSalaryRemain = parseInt(qc_main.getNumberInput(valTotalSalaryRemain)); //chuyen sang kieu so

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
                    txtTotalSalaryRemain.val(qc_main.formatCurrency(String(numberTotalSalaryRemain)));
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
                    //var numberDateOffNew = Math.floor(parseInt(numberTotalSalary / 26));
                    numberTotalSalaryRemain = numberTotalSalary - numberSalary - (parseInt(numberSalary) * 0.215) - numberDateOff;
                    if (numberTotalSalaryRemain > 0) {
                        txtTotalSalaryRemain.val(qc_main.formatCurrency(String(Math.floor(numberTotalSalaryRemain))));
                        txtTotalSalaryRemainShow.val(qc_main.formatCurrency(String(Math.floor(numberTotalSalaryRemain))));
                    } else {
                        txtTotalSalaryRemain.val(0);
                        txtTotalSalaryRemainShow.val(0);
                    }
                }
                txtUsePhone.val(0);
                txtResponsibility.val(0);
                txtFuel.val(0);

            }
        },
        showInput: function () {
            var txtTotalSalary = $('#frmStaffSalaryEdit').find("input[name='txtTotalSalary']");
            var txtTotalSalaryRemain = $('#frmStaffSalaryEdit').find("input[name='txtTotalSalaryRemain']");
            var txtTotalSalaryRemainShow = $('#frmStaffSalaryEdit').find("input[name='txtTotalSalaryRemainShow']");
            var txtSalary = $('#frmStaffSalaryEdit').find("input[name='txtSalary']");
            var txtResponsibility = $('#frmStaffSalaryEdit').find("input[name='txtResponsibility']");
            var txtInsurance = $('#frmStaffSalaryEdit').find("input[name='txtInsurance']");
            var txtDateOff = $('#frmStaffSalaryEdit').find("input[name='txtDateOff']");
            var txtUsePhone = $('#frmStaffSalaryEdit').find("input[name='txtUsePhone']");
            var txtFuel = $('#frmStaffSalaryEdit').find("input[name='txtFuel']");
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
                /*if (numberTotalSalary > 0) {
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
                 }*/

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
            var txtTotalSalaryRemain = $('#frmStaffSalaryEdit').find("input[name='txtTotalSalaryRemain']");
            var txtTotalSalaryRemainShow = $('#frmStaffSalaryEdit').find("input[name='txtTotalSalaryRemainShow']");
            var txtUsePhone = $('#frmStaffSalaryEdit').find("input[name='txtUsePhone']");
            var txtResponsibility = $('#frmStaffSalaryEdit').find("input[name='txtResponsibility']");
            var txtFuel = $('#frmStaffSalaryEdit').find("input[name='txtFuel']");

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
    resetPass: {
        getFrom: function (listObject) {
            qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-reset') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
        },
        save: function (form) {
            if (confirm('Lấy lại mật khẩu mặc định')) {
                qc_ad3d_submit.ajaxFormHasReload(form, '#', true);
            }
        }
    },
    /*mo cham cong*/
    openWork: function (href) {
        if (confirm('Bạn đồng ý mở chấm công cho nhân viên này')) {
            qc_ad3d_submit.ajaxHasReload(href, '', false);
        }
    },
    image: {
        getAdd: function (href) {
            qc_ad3d_submit.ajaxNotReload(href, $('#' + qc_ad3d.bodyIdName()), false);
        },
        saveAdd: function (frm) {
            if (confirm('Bạn đồng ý cập nhật hình ảnh')) {
                qc_ad3d_submit.ajaxFormHasReload(frm, '', false);
            }
        },
        delete: function (href) {
            if (confirm('Bạn muốn xóa ảnh này')) {
                qc_ad3d_submit.ajaxHasReload(href, '', false);
            }
        }
    },
    delete: function (listObject) {
        if (confirm('Bạn muốn xóa nhân viên này')) {
            qc_ad3d_submit.ajaxHasReload($(listObject).parents('.qc_ad3d_list_content').data('href-del') + '/' + $(listObject).data('object'), '', false);
        }
    }
}

//-------------------- HINH ANH ------------
$(document).ready(function () {
    $('.qc_ad3d_sys_staff_edit_wrap').on('click', '.qc_ad3d_staff_edit_image_act', function () {
        qc_ad3d_staff_staff.image.getAdd($(this).data('href'));
    });

    $('body').on('click', '.qc_ad3d_frm_staff_add_image .qc_save', function () {
        qc_ad3d_staff_staff.image.saveAdd($(this).parents('.qc_ad3d_frm_staff_add_image'));
    });
    /*xoa hinh anh*/
    $('.qc_ad3d_sys_staff_edit_wrap').on('click', '.qc_ad3d_staff_edit_image_act_del', function () {
        qc_ad3d_staff_staff.image.delete($(this).data('href'));
    });
});

//-------------------- xem chi tiet ------------
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_view', function () {
        qc_ad3d_staff_staff.view($(this).parents('.qc_ad3d_list_object'));
    })
});

//-------------------- filter ------------
$(document).ready(function () {
    $('body').on('change', '.cbCompanyFilter', function () {
        var companyId = $(this).val();
        var href = $(this).data('href-filter');
        if (companyId != '') {
            href = href + '/' + companyId;
        }
        qc_ad3d_staff_staff.filter(href);
    })
});

//-------------------- edit ------------
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_edit', function () {
        qc_ad3d_staff_staff.edit.get($(this).parents('.qc_ad3d_list_object'));
    });

    //--------- cap nhat thong tin lam viec
    //them bo phan
    $('body').on('click', '.frmStaffWorkEdit .qc_staff_department_edit_add_action', function () {
        qc_ad3d_staff_staff.edit.addDepartment($(this).data('href'));
    });
    //xoa bo phan
    $('body').on('click', '.frmStaffWorkEdit .qc_ad3d_staff_add_department .qc_delete', function () {
        $(this).parents('.qc_ad3d_staff_add_department').remove();
    });
    $('body').on('click', '.frmStaffWorkEdit .qc_save', function () {
        qc_ad3d_staff_staff.edit.saveWork($(this).parents('.frmStaffWorkEdit'));
    })

    //--------cap nhat thong tin co ban
    $('body').on('click', '.frmStaffInfoEdit .qc_save', function () {
        qc_ad3d_staff_staff.edit.saveInfo($(this).parents('.frmStaffInfoEdit'));
    });

    //--------cap nhat thong tin lương
    $('body').on('click', '.frmStaffSalaryEdit .qc_save', function () {
        qc_ad3d_staff_staff.edit.saveSalary($(this).parents('.frmStaffSalaryEdit'));
    });
});

//-------------------- lấy mật khẩu mặt định ------------
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_reset_pass', function () {
        qc_ad3d_staff_staff.resetPass.getFrom($(this).parents('.qc_ad3d_list_object'));
    }),
        $('body').on('click', '.qc_ad_frm_reset_pass .qc_save ', function () {
            qc_ad3d_staff_staff.resetPass.save($(this).parents('.qc_ad_frm_reset_pass'));
        })
});
//-------------------- mo cham cong ------------
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_ad3d_staff_open_work_act', function () {
        qc_ad3d_staff_staff.openWork($(this).data('href'));
    })
});

//-------------------- xoan ------------
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_delete', function () {
        qc_ad3d_staff_staff.delete($(this).parents('.qc_ad3d_list_object'));
    })
});
//-------------------- add ------------
$(document).ready(function () {
    //add department
    $('#frmAdd').on('click', '.qc_staff_department_add_action', function () {
        qc_ad3d_staff_staff.add.addDepartment($(this).data('href'));
    })

    //delete product
    $('body').on('click', '#frmAdd .qc_ad3d_staff_add_department .qc_delete', function () {
        $(this).parents('.qc_ad3d_staff_add_department').remove();
    })

    $('.qc_ad3d_index_content').on('click', '.frmAdd .qc_save', function () {
        qc_ad3d_staff_staff.add.save($(this).parents('.frmAdd'));
    })
});

//-------------------- change pass ------------
$(document).ready(function () {
    $('.qc_ad3d_index_content').on('click', '.frmChangePassword .qc_save', function () {
        qc_ad3d_staff_staff.changePass($(this).parents('.frmChangePassword'));
    })
});

//-------------------- change account ------------
$(document).ready(function () {
    $('.qc_ad3d_index_content').on('click', '.frmChangeAccount .qc_save', function () {
        qc_ad3d_staff_staff.changeAccount($(this).parents('.frmChangeAccount'));
    })
});