/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_staff_company = {
    view: function (listObject) {
        qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-view') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
    },
    add: {
        save: function (formObject) {
            var txtName = $(formObject).find("input[name='txtName']");
            var txtCompanyCode = $(formObject).find("input[name='txtCompanyCode']");
            var txtNameCode = $(formObject).find("input[name='txtNameCode']");
            var txtAddress = $(formObject).find("input[name='txtAddress']");
            var txtPhone = $(formObject).find("input[name='txtPhone']");
            var txtEmail = $(formObject).find("input[name='txtEmail']");
            var txtWebsite = $(formObject).find("input[name='txtWebsite']");

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
            qc_ad3d_submit.ajaxFormHasReload(formObject, '', false);
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
// lay link tuyen dung
$(document).ready(function () {
    //$(this).find('.qc_link').select();
    $('.qc_ad3d_list_object').on('click', '.qc_get_link', function () {
        var companyId = $(this).date('company');
        $('#qc_recruitment_link_' + companyId).select();
        document.execCommand('copy');
        //alert('LINK ĐÃ ĐƯỢC COPY');
        //qc_ad3d_staff_company.edit.get($(this).parents('.qc_ad3d_list_object'));
    });
});
//-------------------- edit ------------
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_edit', function () {
        qc_ad3d_staff_company.edit.get($(this).parents('.qc_ad3d_list_object'));
    })

    $('body').on('click', '.frmEdit .qc_save', function () {
        qc_ad3d_staff_company.edit.save($(this).parents('.frmEdit'));
    })
});

//-------------------- add ------------
$(document).ready(function () {
    $('.qc_ad3d_index_content').on('click', '.frmAdd .qc_save', function () {
        qc_ad3d_staff_company.add.save($(this).parents('.frmAdd'));
    })
});