/**
 * Created by HUY on 11/12/2018.
 */
var qc_ad3d_system_rank = {

    view: function (listObject) {
        qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-view') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
    },
    add: {
        save: function (form) {
            var txtName = $(form).find("input[name='txtName']");

            if (qc_main.check.inputNull(txtName, 'Nhập tên ')) {
                return false;
            } else {
                if (qc_main.check.inputMaxLength(txtName, 50, 'Tên không dài quá 50 ký tự')){
                    return false;
                }else{
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
            var containNotify = $(form).find('.frm_notify');

            if (qc_main.check.inputNull(txtName, 'Nhập tên')) {
                return false;
            } else {
                if (qc_main.check.inputMaxLength(txtName, 50, 'Tên không dài quá 50 ký tự')){
                    return false;
                }else{
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

//view
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_view', function () {
        qc_ad3d_system_rank.view($(this).parents('.qc_ad3d_list_object'));
    })
});

$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_delete', function () {
        qc_ad3d_system_rank.delete($(this).parents('.qc_ad3d_list_object'));
    })
});
//-------------------- edit ------------
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_edit', function () {
        qc_ad3d_system_rank.edit.get($(this).parents('.qc_ad3d_list_object'));
    })

    $('body').on('click', '.frmEdit .qc_save', function () {
        qc_ad3d_system_rank.edit.save($(this).parents('.frmEdit'));
    })
});

//-------------------- add ------------
$(document).ready(function () {
    $('.qc_ad3d_index_content').on('click', '.frmAdd .qc_save', function () {
        qc_ad3d_system_rank.add.save($(this).parents('.frmAdd'));
    })
});