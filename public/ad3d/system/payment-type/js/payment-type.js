/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_staff_payment_type = {

    view: function (listObject) {
        qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-view') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
    },
    add: {
        save: function (formObject) {
            var txtName = $(formObject).find("input[name='txtName']");

            if (qc_main.check.inputNull(txtName, 'Nhập tên lĩnh vực')) {
                return false;
            } else {
                if (qc_main.check.inputMaxLength(txtName, 100, 'Tên không dài quá 10 ký tự')) return false;
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
            var containNotify = $(formObject).find('.frm_notify');

            if (qc_main.check.inputNull(txtName, 'Nhập tên Lĩnh vực')) {
                return false;
            } else {
                if (qc_main.check.inputMaxLength(txtName, 100, 'Tên không dài quá 100 ký tự')) return false;
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
        };
    }
}

//view
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_view', function () {
        qc_ad3d_staff_payment_type.view($(this).parents('.qc_ad3d_list_object'));
    })
});

$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_delete', function () {
        qc_ad3d_staff_payment_type.delete($(this).parents('.qc_ad3d_list_object'));
    })
});
//-------------------- edit ------------
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_edit', function () {
        qc_ad3d_staff_payment_type.edit.get($(this).parents('.qc_ad3d_list_object'));
    })

    $('body').on('click', '.frmEdit .qc_save', function () {
        qc_ad3d_staff_payment_type.edit.save($(this).parents('.frmEdit'));
    })
});

//-------------------- add ------------
$(document).ready(function () {
    $('.qc_ad3d_index_content').on('click', '.frmAdd .qc_save', function () {
        qc_ad3d_staff_payment_type.add.save($(this).parents('.frmAdd'));
    })
});