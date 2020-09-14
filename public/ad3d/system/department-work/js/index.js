/**
 * Created by HUY on 11/10/2018.
 */
/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_system_department_work = {
    add: {
        save: function (form) {
            var txtName = $(form).find("input[name='txtName']");
            var txtDepartmentCode = $(form).find("input[name='txtDepartmentCode']");

            if (qc_main.check.inputNull(txtName, 'Nhập tên bộ phận')) {
                return false;
            } else {
                if (qc_main.check.inputMaxLength(txtName, 50, 'Tên không dài quá 50 ký tự')) return false;
            }
            if (qc_main.check.inputNull(txtDepartmentCode, 'Nhập mã bộ phận')) {
                return false;
            } else {
                if (qc_main.check.inputMaxLength(txtDepartmentCode, 30, 'Mã bộ phận dài không quá 30 ký tự')) {
                    return false;
                } else {
                    qc_ad3d_submit.ajaxFormHasReload(form, '', false);
                    qc_main.scrollTop();
                }
            }

        }
    },
    edit: {
        get: function (href) {
            qc_ad3d_submit.ajaxNotReload(href, $('#' + qc_ad3d.bodyIdName()), false);
        },
        save: function (form) {
            var txtName = $(form).find("input[name='txtName']");
            var containNotify = $(form).find('.frm_notify');
            if (qc_main.check.inputNull(txtName, 'Nhập tên bộ phận')) {
                return false;
            } else {
                if (qc_main.check.inputMaxLength(txtName, 50, 'Tên không dài quá 50 ký tự')) {
                    return false;
                } else {
                    if(confirm('Bạn đồng ý cập nhật thông tin này?')){
                        qc_ad3d_submit.ajaxFormHasReload(form, containNotify, true);
                    }
                }
            }

        }
    },
    delete: function (href) {
        if (confirm('Bạn muốn xóa dụng cụ này')) {
            qc_ad3d_submit.ajaxHasReload(href,'', false);
        };
    }
}

//loc theo bo phan
$(document).ready(function () {
    $('.qc_ad3d_index_content').on('change', '.cbDepartment', function () {
        var href = $(this).data('href') + '/' + $(this).val();
        qc_main.url_replace(href);
    })
});
// xoa thong tin
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_delete', function () {
        qc_ad3d_system_department_work.delete($(this).data('href'));
    })
});
//-------------------- edit ------------
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_edit', function () {
        qc_ad3d_system_department_work.edit.get($(this).data('href'));
    });

    $('body').on('click', '.frmEdit .qc_save', function () {
        qc_ad3d_system_department_work.edit.save($(this).parents('.frmEdit'));
    })
});

//-------------------- add ------------
/*
$(document).ready(function () {
    $('.qc_ad3d_index_content').on('click', '.frmAdd .qc_save', function () {
        qc_ad3d_system_department_work.add.save($(this).parents('.frmAdd'));
    })
});*/
