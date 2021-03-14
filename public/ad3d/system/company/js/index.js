/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_staff_company = {
    view: function (listObject) {
        qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-view') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
    },
    manager: {
        saveAdd: function (frm) {
            var txtSelectObject = $(frm).find("input[name='txtSelectObject']").val();
            if (txtSelectObject == 'selectNew') {
                var txtFirstName = $(frm).find("input[name='txtFirstName']");
                var txtLastName = $(frm).find("input[name='txtLastName']");
                var txtIdentityCard = $(frm).find("input[name='txtIdentityCard']");
                var cbGender = $(frm).find("select[name='cbGender']");
                var txtStaffPhone = $(frm).find("input[name='txtStaffPhone']");
                var txtStaffEmail = $(frm).find("input[name='txtStaffEmail']");
                if (qc_main.check.inputNull(txtFirstName, 'Nhập họ người quản lý')) {
                    return false;
                } else {
                    if (qc_main.check.inputMaxLength(txtFirstName, 30, 'Tên không dài quá 30 ký tự')) return false;
                }
                if (qc_main.check.inputNull(txtLastName, 'Nhập tên người quản lý')) {
                    return false;
                } else {
                    if (qc_main.check.inputMaxLength(txtLastName, 20, 'Tên không dài quá 20 ký tự')) return false;
                }
                if (qc_main.check.inputNull(txtIdentityCard, 'Nhập chứng minh thư')) {
                    return false;
                } else {
                    if (qc_main.check.inputMaxLength(txtIdentityCard, 20, 'Chứng minh thư dài không quá 20 ký tự')) return false;
                }
                if (qc_main.check.inputNull(cbGender, 'Chọn giới tính')) {
                    return false;
                }

                if (!qc_main.check.inputNull(txtStaffEmail, '')) {
                    var staffEmailVal = txtStaffEmail.val();
                    if (!qc_main.check.emailJavascript(staffEmailVal)) {
                        alert('Email không hợp lệ');
                        txtStaffEmail.focus();
                        return false;
                    }
                }
                if (qc_main.check.inputNull(txtStaffPhone, 'Nhập số điện thoại người quản lý')) {
                    return false;
                } else {
                    qc_ad3d_submit.normalForm(frm);
                }

            } else {
                qc_ad3d_submit.normalForm(frm);
            }

        }
    },
    add: {
        save: function (frm) {
            var txtName = $(frm).find("input[name='txtName']");
            var txtCompanyCode = $(frm).find("input[name='txtCompanyCode']");
            var txtNameCode = $(frm).find("input[name='txtNameCode']");
            var txtAddress = $(frm).find("input[name='txtAddress']");
            var txtPhone = $(frm).find("input[name='txtPhone']");
            var txtEmail = $(frm).find("input[name='txtEmail']");
            var txtWebsite = $(frm).find("input[name='txtWebsite']");

            var txtFirstName = $(frm).find("input[name='txtFirstName']");
            var txtLastName = $(frm).find("input[name='txtLastName']");
            var txtIdentityCard = $(frm).find("input[name='txtIdentityCard']");
            var cbGender = $(frm).find("select[name='cbGender']");
            var txtStaffPhone = $(frm).find("input[name='txtStaffPhone']");
            var txtStaffEmail = $(frm).find("input[name='txtStaffEmail']");
            if (qc_main.check.inputNull(txtName, 'Nhập tên công ty')) {
                return false;
            } else {
                if (qc_main.check.inputMaxLength(txtName, 50, 'Tên không dài quá 50 ký tự')) return false;
            }
            if (qc_main.check.inputNull(txtCompanyCode, 'Nhập mã số thuế')) {
                return false;
            } else {
                if (qc_main.check.inputMaxLength(txtCompanyCode, 30, 'Mã số thuế dài không quá 30 ký tự')) return false;
            }
            if (qc_main.check.inputNull(txtNameCode, 'Nhập mã cty')) {
                return false;
            } else {
                if (qc_main.check.inputMaxLength(txtNameCode, 30, 'Mã cty dài không quá 30 ký tự')) return false;
            }
            if (qc_main.check.inputNull(txtPhone, 'Nhập số điện thoại cty')) {
                return false;
            }
            if (qc_main.check.inputNull(txtAddress, 'Nhập địa chỉ')) {
                if (qc_main.check.inputMaxLength(txtAddress, 50, 'Địa chỉ dài quá 50 ký tự')) return false;
                return false;
            }
            if (!qc_main.check.inputNull(txtEmail, '')) {
                var email = txtEmail.val();
                if (!qc_main.check.emailJavascript(email)) {
                    alert('Email không hợp lệ');
                    txtEmail.focus();
                    return false;
                } else {

                }
            }

            if (qc_main.check.inputNull(txtFirstName, 'Nhập họ người quản lý')) {
                return false;
            } else {
                if (qc_main.check.inputMaxLength(txtFirstName, 30, 'Tên không dài quá 30 ký tự')) return false;
            }
            if (qc_main.check.inputNull(txtLastName, 'Nhập tên người quản lý')) {
                return false;
            } else {
                if (qc_main.check.inputMaxLength(txtLastName, 20, 'Tên không dài quá 20 ký tự')) return false;
            }
            if (qc_main.check.inputNull(txtIdentityCard, 'Nhập chứng minh thư')) {
                return false;
            } else {
                if (qc_main.check.inputMaxLength(txtIdentityCard, 20, 'Chứng minh thư dài không quá 20 ký tự')) return false;
            }
            if (qc_main.check.inputNull(cbGender, 'Chọn giới tính')) {
                return false;
            }
            if (qc_main.check.inputNull(txtStaffPhone, 'Nhập số điện thoại người quản lý')) {
                return false;
            }
            if (!qc_main.check.inputNull(txtStaffEmail, '')) {
                var staffEmailVal = txtStaffEmail.val();
                if (!qc_main.check.emailJavascript(staffEmailVal)) {
                    alert('Email không hợp lệ');
                    txtStaffEmail.focus();
                    return false;
                }
            }
            qc_ad3d_submit.ajaxFormHasReload(frm, '', false);
            qc_main.scrollTop();
        }
    },
    edit: {
        get: function (listObject) {
            qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-edit') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
        },
        save: function (formObject) {
            var txtName = $(formObject).find("input[name='txtName']");
            var txtCompanyCode = $(formObject).find("input[name='txtCompanyCode']");
            var txtNameCode = $(formObject).find("input[name='txtNameCode']");
            var txtAddress = $(formObject).find("input[name='txtAddress']");
            var txtPhone = $(formObject).find("input[name='txtPhone']");
            var txtEmail = $(formObject).find("input[name='txtEmail']");
            var txtWebsite = $(formObject).find("input[name='txtWebsite']");
            var containNotify = $(formObject).find('.frm_notify');

            if (qc_main.check.inputNull(txtName, 'Nhập tên công ty')) {
                return false;
            } else {
                if (qc_main.check.inputMaxLength(txtName, 50, 'Tên không dài quá 50 ký tự')) return false;
            }
            if (qc_main.check.inputNull(txtCompanyCode, 'Nhập mã số thuế')) {
                return false;
            } else {
                if (qc_main.check.inputMaxLength(txtCompanyCode, 30, 'Mã số thuế dài không quá 30 ký tự')) return false;
            }
            if (qc_main.check.inputNull(txtNameCode, 'Nhập mã cty')) {
                return false;
            } else {
                if (qc_main.check.inputMaxLength(txtNameCode, 30, 'Mã cty dài không quá 30 ký tự')) return false;
            }
            if (qc_main.check.inputNull(txtAddress, 'Nhập địa chỉ')) {
                if (qc_main.check.inputMaxLength(txtAddress, 50, 'Địa chỉ dài quá 50 ký tự')) return false;
                return false;
            }
            if (!qc_main.check.inputNull(txtEmail, '')) {
                var email = txtEmail.val();
                if (!qc_main.check.emailJavascript(email)) {
                    alert('Email không hợp lệ');
                    txtEmail.focus();
                    return false;
                } else {

                }
            }
            qc_ad3d_submit.ajaxFormHasReload(formObject, containNotify, true);
            $('#qc_container_content').animate({
                scrollTop: 0
            })
        }
    },
    delete: function (formObject) {
        if (confirm('Bạn muốn xóa dụng cụ này')) {
            alert('Chưa phát triển tính năng này');
        }
        ;
    }
}

//view
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_view', function () {
        qc_ad3d_staff_company.view($(this).parents('.qc_ad3d_list_object'));
    })
});

$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_delete', function () {
        qc_ad3d_staff_company.delete($(this).parents('.qc_ad3d_list_object'));
    })
});
//-------- ------------ lay link tuyen dung ---------  -------------
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_recruitment_get_link', function () {
        qc_ad3d_submit.ajaxNotReload($(this).data('href'), $('#' + qc_ad3d.bodyIdName()), false);
    });
    $('body').on('click', '.frmAd3dGetLink .qc_copy', function () {
        $('#txtRecruitmentLink').select();
        document.execCommand("copy");
        alert('Link đã được copy');
        $('.qc_ad3d_container_close').click();
    });
});
//-------------------- sua thong tin ------------
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_edit', function () {
        qc_ad3d_staff_company.edit.get($(this).parents('.qc_ad3d_list_object'));
    });

    $('body').on('click', '.frmEdit .qc_save', function () {
        qc_ad3d_staff_company.edit.save($(this).parents('.frmEdit'));
    });
});

//-------------------- them cong ty moi ------------
$(document).ready(function () {
    $('.qc_ad3d_index_content').on('click', '.frmAdd .qc_save', function () {
        qc_ad3d_staff_company.add.save($(this).parents('.frmAdd'));
    });
});

//-------------------- them quan lý moi ------------
$(document).ready(function () {
    $('.frmAddManager').on('click', '.qc_save', function () {
        qc_ad3d_staff_company.manager.saveAdd($(this).parents('.frmAddManager'));
    });
});