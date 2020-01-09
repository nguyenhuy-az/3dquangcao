/**
 * Created by HUY on 11/10/2018.
 */
/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_system_department = {

    updateStatus: function (listObject) {
        qc_ad3d_submit.ajaxHasReload($(listObject).parents('.qc_ad3d_list_content').data('href-status') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
    },

    view: function (listObject) {
        qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-view') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
    },
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
        get: function (listObject) {
            qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-edit') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
        },
        save: function (form) {
            var txtName = $(form).find("input[name='txtName']");
            var txtDepartmentCode = $(form).find("input[name='txtDepartmentCode']");
            var containNotify = $(form).find('.frm_notify');

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
                    qc_ad3d_submit.ajaxFormHasReload(form, containNotify, true);
                    $('#qc_container_content').animate({
                        scrollTop: 0
                    })
                }
            }
            
        }
    },
    delete: function (form) {
        if (confirm('Bạn muốn xóa dụng cụ này')) {
            alert('Chưa phát triển tính năng này');
        };
    }
}

//activity status
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_status', function () {
        qc_ad3d_system_department.updateStatus($(this).parents('.qc_ad3d_list_object'));
    })
});

//view
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_view', function () {
        qc_ad3d_system_department.view($(this).parents('.qc_ad3d_list_object'));
    })
});

$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_delete', function () {
        qc_ad3d_system_department.delete($(this).parents('.qc_ad3d_list_object'));
    })
});
//-------------------- edit ------------
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_edit', function () {
        qc_ad3d_system_department.edit.get($(this).parents('.qc_ad3d_list_object'));
    })

    $('body').on('click', '.frmEdit .qc_save', function () {
        qc_ad3d_system_department.edit.save($(this).parents('.frmEdit'));
    })
});

//-------------------- add ------------
$(document).ready(function () {
    $('.qc_ad3d_index_content').on('click', '.frmAdd .qc_save', function () {
        qc_ad3d_system_department.add.save($(this).parents('.frmAdd'));
    })
});