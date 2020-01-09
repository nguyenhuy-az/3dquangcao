/**
 * Created by HUY on 1/18/2019.
 */
var qc_ad3d_system_punish_content = {
    view: function (listObject) {
        qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-view') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
        qc_main.scrollTop();
    },
    add: {
        save: function (form) {
            var cbPunishType = $(form).find("select[name='cbPunishType']");
            var txtPunishName = $(form).find("input[name='txtPunishName']");
            var txtName = $(form).find("input[name='txtName']");
            var txtMoney = $(form).find("input[name='txtMoney']");
            var txtNote = $(form).find("input[name='txtNote']");
            if (qc_main.check.inputNull(cbPunishType, 'Chọn lĩnh vực phạt')) {
                return false;
            }

            if (qc_main.check.inputNull(txtPunishName, 'Nhập mã nội dung phạt')) {
                return false;
            }

            if (qc_main.check.inputNull(txtName, 'Nhập nội dung phạt')) {
                return false;
            }
            if (qc_main.check.inputNull(txtMoney, 'Nhập số tiền phạt')) {
                return false;
            }

            qc_ad3d_submit.ajaxFormHasReload(form, '', false);
            qc_main.scrollTop();
        }
    },
    edit: {
        get: function (listObject) {
            qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-edit') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
        },
        save: function (form) {
            var txtName = $(form).find("input[name='txtName']");
            var txtMoney = $(form).find("input[name='txtMoney']");
            var containNotify = $(form).find('.frm_notify');

            if (qc_main.check.inputNull(txtName, 'Nhập nội dung phạt')) {
                return false;
            }
            if (qc_main.check.inputNull(txtMoney, 'Nhập số tiền phạt')) {
                return false;
            }

            qc_ad3d_submit.ajaxFormHasReload(form, containNotify, true);
            $('#qc_container_content').animate({
                scrollTop: 0
            })
        }
    },
    delete: function (listObject) {
        if (confirm('Sẽ xóa các thông tin liên quan, và không phục hồi được, vẫn xóa?')) {
            qc_ad3d_submit.ajaxHasReload($(listObject).parents('.qc_ad3d_list_content').data('href-del') + '/' + $(listObject).data('object'), '', false);
        }
    }
}

//-------------------- filter ------------
$(document).ready(function () {
    //khi chọn  lĩnh vực phạt
    $('body').on('change', '.cbPunishTypeFilter', function () {
        var href = $(this).data('href') + '/' + $('.cbPunishTypeFilter').val();
        qc_main.url_replace(href);
    })

});
//view
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_view', function () {
        qc_ad3d_system_punish_content.view($(this).parents('.qc_ad3d_list_object'));
    })
});

//-------------------- add ------------
$(document).ready(function () {
    //lưu
    $('.qc_ad3d_index_content').on('click', '.frmAdd .qc_save', function () {
        qc_ad3d_system_punish_content.add.save($(this).parents('.frmAdd'));
    })
});

//-------------------- edit ------------
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_edit', function () {
        qc_ad3d_system_punish_content.edit.get($(this).parents('.qc_ad3d_list_object'));
    });

    $('body').on('click', '.frmEdit .qc_save', function () {
        qc_ad3d_system_punish_content.edit.save($(this).parents('.frmEdit'));
    });
});

//delete
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_delete', function () {
        qc_ad3d_system_punish_content.delete($(this).parents('.qc_ad3d_list_object'));
    });
});